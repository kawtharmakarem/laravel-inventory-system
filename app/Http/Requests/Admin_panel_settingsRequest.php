<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Admin_panel_settingsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'system_name'=>'required',
            'address'=>'required',
            'phone'=>'required',
            'customer_parent_account_number'=>'required',
            'supplier_parent_account_number'=>'required',
            'delegate_parent_account_number'=>'required',
            'employees_parent_account_number'=>'required'


        ];
    }
    public function messages()
    {
        return [
            'system_name.required'=>'Company name is required',
            'address.required'=>'Address is required',
            'phone.required'=>'Phone number is required',
            'customer_parent_account_number.required'=>'parent account number for customers is required',
            'supplier_parent_account_number.required'=>'parent account number  for suppliers is required',
            'delegate_parent_account_number.required'=>'parent account number for delegates is required',
            'employees_parent_account_number.required'=>'parent account number for employees is required'
        ];
    }
}
