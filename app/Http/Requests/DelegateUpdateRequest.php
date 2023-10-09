<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DelegateUpdateRequest extends FormRequest
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
            'active'=>'required',
            'percent_type'=>'required',
            'percent_sales_commission_kataei'=>'required',
            'percent_sales_commission_nosjomla'=>'required',
            'percent_sales_commission_jomla'=>'required',
            'percent_collect_commission'=>'required'
        ];
    }
    public function message()
    {
        return [
            'name.required'=>'Delegate name is required',
            'active.required'=>'Activation case is required',
            'percent_type.required'=>'percent type is required',
            'percent_sales_commission_kataei.required'=>'Popular price is required',
            'percent_sales_commission_nosjomla.required'=>'Half wholesale price is required',
            'percent_sales_commission_jomla.required'=>'Wholesale price is required',
            'percent_collect_commission.required'=>'Percent collect commission is required'
        ];
    }
}
