<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGuaranteeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization will be handled by the Auth middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $guaranteeId = $this->route('guarantee');
        
        return [
            // Corporate reference number is immutable, so we don't include it
            'guarantee_type' => [
                'required',
                'string',
                Rule::in(['Bank', 'Bid Bond', 'Insurance', 'Surety']),
            ],
            'nominal_amount' => [
                'required',
                'numeric',
                'min:0',
            ],
            'nominal_amount_currency' => [
                'required',
                'string',
                'size:3',
            ],
            'expiry_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'applicant_name' => [
                'required',
                'string',
                'max:255',
            ],
            'applicant_address' => [
                'required',
                'string',
            ],
            'beneficiary_name' => [
                'required',
                'string',
                'max:255',
            ],
            'beneficiary_address' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'guarantee_type.in' => 'The guarantee type must be one of: Bank, Bid Bond, Insurance, Surety.',
            'expiry_date.after_or_equal' => 'The expiry date must be today or a future date.',
        ];
    }
}