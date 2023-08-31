<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuppliersWithOrdersRequest extends FormRequest
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
            'supplier_code'=>'required',
            'bill_type'=>'required',
            'order_date'=>'required',
            'store_id'=>'required'
        ];
    }
    public function messages()
    {
        return [
          'supplier_code.required'=>'Supplier name is required',
           'bill_type.required'=>'Bill type is required',
           'order_date.required'=>'Bill date is required',
           'store_id.required'=>'Branch which receives bill is required'
        ];
    }
}
