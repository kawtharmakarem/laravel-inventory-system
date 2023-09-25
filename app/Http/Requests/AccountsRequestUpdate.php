<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountsRequestUpdate extends FormRequest
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
            'account_type'=>'required',
            'is_parent'=>'required',
            'parent_account_number'=>'required_if:is_parent,0',
            'active'=>'required'

        ];
    }
     
    public function messages()
    {
        return [
            'name.required'=>'Please enter account name',
            'account_type.required'=>'Please enter account type',
            'is_parent.required'=>'Please select if account is parent or not',
            'parent_account_number.required_if'=>'Please enter parent_account_number',
            'active.required'=>'please select if account is archived or not'

        ];
    }
}
