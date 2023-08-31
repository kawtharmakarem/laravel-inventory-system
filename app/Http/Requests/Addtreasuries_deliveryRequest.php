<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Addtreasuries_deliveryRequest extends FormRequest
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
            'treasuries_can_delivery_id'=>'required'
        ];
    }
    public function messages()
    {
      return ['treasuries_can_delivery_id.required'=>'the sub_treasury name is required'];
    }
}
