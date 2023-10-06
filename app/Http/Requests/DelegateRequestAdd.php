<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DelegateRequestAdd extends FormRequest
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
            'start_balance_status'=>'required',
            'start_balance'=>'required|min:0',
            'percent_type'=>'required',
            'percent_sales_commission_kataei'=>'required',
            'percent_sales_commission_nosjomla'=>'required',
            'percent_sales_commission_jomla'=>'required',
            'percent_collect_commission'=>'required'

        ];
    }
    public function messages()
    {
        return [
            'name.required'=>'Delegate name is required',
            'active.required'=>'Activation case is required',
            'start_balance_status.required'=>'Balance status is required',
            'start_balance.required'=>'Strat balance is required',
            'percent_type.required'=>'Delegate pecent type is required',
            'percent_sales_commission_kataei.required'=>'Delegate commission in sales/Popular price is required',
            'percent_sales_commission_nosjomla.required'=>'Delegate commission in sales/Half wholesale price is required',
            'percent_sales_commission_jomla.required'=>'Delegate commission in sales/Wholesale price is required',
            'percent_collect_commission.required'=>'Percent collect commission is required'

        ];
    }
}
