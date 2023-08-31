<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
            'name'=>'required',
            'suppliers_categories_id'=>'required',
            'start_balance_status'=>'required',
            'start_balance'=>'required|min:0',
            'active'=>'required',


        ];
    }
    public function messages()
    {
        return [
            'name.required'=>'Supplier name is required',
            'suppliers_categories_id'=>'Supplier Category is required',

            'start_balance_status.required'=>'start balance status is required',
            'start_balance.required'=>'start balance ammount is required',
             'active.required'=>'Activation case is required',


        ];

    }
}
