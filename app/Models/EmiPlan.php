<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmiPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'total_amount',
        'months',
        'start_month',
        'start_year',
        'interest_rate',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(EmiInstallment::class);
    }
}
