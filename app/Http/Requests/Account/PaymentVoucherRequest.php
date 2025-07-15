<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class PaymentVoucherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'date' => 'required|date',
            'payee' => 'required|string',
            'scheme_fund' => 'required|string',
            'payment_mode' => 'required|in:1,2', // 1 = Cash in Hand, 2 = Bank
            'bank' => 'nullable|string',
            'reference_number' => 'nullable|string',
            'reference_date' => 'nullable|date',
            'narration' => 'nullable|string',
            'cheque_no' => 'nullable|string',
            'cheque_date' => 'nullable|date',
            'ledger' => 'required|array',
            'ledger.*' => 'required|string',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric',
            'pay_deduct' => 'required|array',
            'pay_deduct.*' => 'required|in:1,2', // 1 = Pay, 2 = Deduct
        ];
    }

    public function messages() {
        return [
            'date.required' => 'The date field is required.',
            'payee.required' => 'The payee field is required.',
            'scheme_fund.required' => 'The scheme fund field is required.',
            'payment_mode.required' => 'The payment mode field is required.',
            // Add custom error messages as needed
        ];
    }
}
