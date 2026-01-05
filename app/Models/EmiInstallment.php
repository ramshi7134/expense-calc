<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmiInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'emi_plan_id',
        'month',
        'year',
        'amount',
        'due_date',
        'status',
        'paid_date',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    public function emiPlan(): BelongsTo
    {
        return $this->belongsTo(EmiPlan::class);
    }
}
