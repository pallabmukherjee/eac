<?php

namespace App\Http\Requests\Employe;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeLeaveRequest extends FormRequest {
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
        $rules = [
            'emp_number' => 'required|string|unique:employee_leaves,emp_number',
            'emp_name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'bill_no' => 'required|string|max:255',
            'employee_id' => 'required|string|max:255',
            'cl' => 'required|integer|min:0|max:14',
            'cl_enjoyed' => 'integer|min:0',
            'cl_in_hand' => 'integer|min:0',
            'el' => 'required|integer|min:0',
            'el_enjoyed' => 'integer|min:0',
            'el_in_hand' => 'integer|min:0',
            'ml' => 'required|integer|min:0',
            'ml_enjoyed' => 'integer|min:0',
            'ml_in_hand' => 'integer|min:0',
        ];

        if ($this->isMethod('post') && $this->route('id')) {
            $rules['emp_number'] = [
                'required',
                'string',
                'unique:employee_leaves,emp_number,' . $this->route('id'),
            ];
        }

        return $rules;
    }
}
