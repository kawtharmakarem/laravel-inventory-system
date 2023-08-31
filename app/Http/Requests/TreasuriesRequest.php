<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreasuriesRequest extends FormRequest
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
            'is_master'=>'required',
            'last_isal_exchange'=>'required|integer|min:0',
            'last_isal_collect'=>'required|integer|min:0',
            'active'=>'required'


        ];
    }
    public function messages()
    {
        return [
            'name.required'=>'Treasury Name is required',
            'is_master.required'=>'Treasury type is required',
            'last_isal_exchange.required'=>'The last financial receipt number for exchange is required',
            'last_isal_exchange.integer'=>'The last financial receipt number is an integer value',

            'last_isal_collect.required'=>'The last financial receipt number for collection is required',
            'last_isal_collect.integer'=>'The last financial receipt number for collection is an integer value',

            'active'=>'The status of treasury is required'


        ];
    }
}
