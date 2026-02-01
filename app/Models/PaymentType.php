<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentType extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'type', 'statement_day', 'last_four_digits'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
