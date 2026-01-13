<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Receipt;
use App\Jobs\ProcessReceiptJob;
use App\Models\Category;
use App\Models\Expense;

class ScanController extends Controller
{
    public function create()
    {
        // show upload/capture UI
        return view('scan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240',
        ]);

        $user = Auth::user();
        $path = $request->file('image')->store('receipts', 'public');

        $receipt = Receipt::create([
            'user_id' => $user->id,
            'image_path' => $path,
            'status' => 'processing',
        ]);

        // dispatch background OCR job
        ProcessReceiptJob::dispatch($receipt);

        return redirect()->route('scan.review', $receipt->id);
    }

    public function review(Receipt $receipt)
    {
        $this->authorize('view', $receipt);

        // show the extracted fields (may be empty while processing)
        $categories = ['Food','Travel','Fuel','Shopping','Medical','Others'];
        return view('scan.review', compact('receipt', 'categories'));
    }

    public function confirm(Request $request, Receipt $receipt)
    {
        $this->authorize('update', $receipt);

        $data = $request->validate([
            'amount' => 'required|numeric',
            'date' => 'nullable|date',
            'category' => 'nullable|string',
            'merchant' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        // create expense but only after user confirmation
        $expense = Expense::create([
            'user_id' => $receipt->user_id,
            'category_id' => Category::firstWhere('name', $data['category'])?->id,
            'amount' => $data['amount'],
            'date' => $data['date'] ?? now(),
            'note' => $data['note'] ?? ('Receipt: ' . $receipt->id),
        ]);

        $receipt->expense_id = $expense->id;
        $receipt->status = 'processed';
        $receipt->save();

        return redirect()->route('expenses.index')->with('success', 'Expense saved from receipt.');
    }
}
