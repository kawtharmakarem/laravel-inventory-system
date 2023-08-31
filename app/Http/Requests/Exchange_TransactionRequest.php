<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Exchange_TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'move_date'=>'required',
            'mov_type'=>'required',
            'account_number'=>'required',
            'treasuries_id'=>'required',
            'money'=>'required',
            'byan'=>'required'


        ];
    }
    public function messages()
    {
        return [
            'move_date.required'=>'Transaction date is required',
            'mov_type.required'=>'Transaction type is required',
            'treasuries_id.required'=>'Treasury name is required',
            'money.required'=>'Value of paid amount is required',
            'byan.required'=>'Description is required',



        ];
    }
}
