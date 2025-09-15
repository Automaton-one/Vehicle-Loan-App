<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'date_of_birth',
        'vehicle_type',
        'vehicle_make',
        'vehicle_model',
        'purchase_price',
        'deposit_amount',
        'term_months',
        'status',
        'consent_to_credit_check',
        'submitted_at'
    ];

    protected $casts = [
        'consent_to_credit_check' => 'boolean',
        'submitted_at' => 'datetime',
    ];
}
