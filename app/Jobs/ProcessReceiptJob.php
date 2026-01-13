<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Receipt;

class ProcessReceiptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $receipt;

    public function __construct(Receipt $receipt)
    {
        $this->receipt = $receipt;
    }

    public function handle()
    {
        $receipt = $this->receipt->fresh();
        $receipt->status = 'processing';
        $receipt->save();

        $localPath = Storage::disk('public')->path($receipt->image_path);

        // Basic preprocessing: convert to grayscale and increase contrast using ImageMagick if available
        $preprocessed = $localPath . '.proc.png';
        if (shell_exec('which convert')) {
            // try to preprocess for better OCR
            @shell_exec("convert {$localPath} -colorspace Gray -resize 2000x2000 -contrast-stretch 0.1% {$preprocessed}");
            if (!file_exists($preprocessed)) {
                $preprocessed = $localPath;
            }
        } else {
            $preprocessed = $localPath;
        }

        // Run Tesseract CLI if available
        $ocrText = null;
        if (shell_exec('which tesseract')) {
            $tmpTxt = tempnam(sys_get_temp_dir(), 'ocr_');
            // tesseract outputs without extension
            $outBase = $tmpTxt;
            $cmd = "tesseract " . escapeshellarg($preprocessed) . " " . escapeshellarg($outBase) . " -l eng --oem 1 --psm 3";
            @shell_exec($cmd . " 2>/dev/null");
            $txtFile = $outBase . '.txt';
            if (file_exists($txtFile)) {
                $ocrText = file_get_contents($txtFile);
                @unlink($txtFile);
            }
        }

        if (!$ocrText) {
            $receipt->status = 'failed';
            $receipt->save();
            return;
        }

        $receipt->ocr_text = $ocrText;

        // Heuristics: extract amount (numbers with decimals and common total keywords)
        $amount = $this->extractAmount($ocrText);
        $receipt->extracted_amount = $amount;

        // Heuristics: extract date
        $date = $this->extractDate($ocrText);
        $receipt->extracted_date = $date;

        // Heuristics: merchant (first non-empty line)
        $lines = array_filter(array_map('trim', preg_split('/\r?\n/', $ocrText)));
        $receipt->merchant = $lines ? array_values($lines)[0] : null;

        // Heuristics: category mapping using keywords
        $receipt->extracted_category = $this->matchCategory($ocrText);

        $receipt->status = 'processed';
        $receipt->save();
    }

    protected function extractAmount($text)
    {
        // look for lines with total, amount, subtotal etc. with currency
        $lines = preg_split('/\r?\n/', $text);
        $candidates = [];
        foreach ($lines as $line) {
            if (preg_match('/(total|amount|grand total|balance due|net total|pay)[:\s]*([0-9,]+\.?[0-9]{0,2})/i', $line, $m)) {
                $candidates[] = floatval(str_replace(',', '', $m[2]));
            } elseif (preg_match('/([0-9]{1,3}(?:,[0-9]{3})*(?:\.[0-9]{1,2}))/', $line, $m)) {
                $candidates[] = floatval(str_replace(',', '', $m[1]));
            }
        }
        // prefer the largest candidate
        if ($candidates) {
            return max($candidates);
        }
        return null;
    }

    protected function extractDate($text)
    {
        // simple date regexes
        if (preg_match('/(\d{4}[-\/\.]\d{1,2}[-\/\.]\d{1,2})/', $text, $m)) {
            return $m[1];
        }
        if (preg_match('/(\d{1,2}[\/-]\d{1,2}[\/-]\d{2,4})/', $text, $m)) {
            // try to normalize
            $d = date('Y-m-d', strtotime($m[1]));
            return $d;
        }
        // month name with day
        if (preg_match('/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]*[\s\.]*(\d{1,2}),?\s*(\d{4})/i', $text, $m)) {
            return date('Y-m-d', strtotime($m[0]));
        }
        return null;
    }

    protected function matchCategory($text)
    {
        $keywords = [
            'Food' => ['restaurant','cafe','coffee','diner','pizza','burger','food','meal','bakery'],
            'Travel' => ['uber','taxi','flight','airways','bus','rail','train','ticket','transit'],
            'Fuel' => ['petrol','petrol station','fuel','gas station','diesel','petro'],
            'Shopping' => ['shop','store','mall','amazon','shopping','purchase','department store'],
            'Medical' => ['clinic','hospital','pharmacy','medic','doctor','health'],
        ];

        $lower = strtolower($text);
        foreach ($keywords as $cat => $words) {
            foreach ($words as $w) {
                if (strpos($lower, $w) !== false) {
                    return $cat;
                }
            }
        }
        return 'Others';
    }
}
