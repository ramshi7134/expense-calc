<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receipt extends Model
{
    protected $fillable = [
        'user_id', 'expense_id', 'image_path', 'ocr_text',
        'extracted_amount', 'extracted_category', 'merchant', 'extracted_date', 'status'
    ];

    protected $casts = [
        'extracted_amount' => 'decimal:2',
        'extracted_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }
}
