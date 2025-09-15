<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanApplicationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string'],
            'date_of_birth' => ['required', 'date', 'before:-18 years'],
            'vehicle_type' => ['required'],
            'vehicle_make' => ['required', 'string'],
            'vehicle_model' => ['required', 'string'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'deposit_amount' => ['required', 'numeric', 'min:0', 'lte:purchase_price'],
            'term_months' => ['required', 'integer', 'between:6,96'],
            'status' => ['in:submitted,in_review,approved,declined'],
            'consent_to_credit_check' => ['accepted'],
        ];
    }
}
