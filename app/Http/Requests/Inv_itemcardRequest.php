<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Inv_itemcardRequest extends FormRequest
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
        //todo delete some files
        return [
            'name'=>'required',
            'item_type'=>'required',
            'inv_itemcard_categories_id'=>'required',
            'uom_id'=>'required',
            'does_has_retailunit'=>'required',
            'retail_uom_id'=>'required_if:does_has_retailunit,1',
            'retail_uom_quntToParent'=>'required_if:does_has_retailunit,1',
            'price'=>'required',
             'nos_gomla_price'=>'required',
            'gomla_price'=>'required',
            'cost_price'=>'required',
            'price_retail'=>'required_if:does_has_retailunit,1',
            'nos_gomla_price_retail'=>'required_if:does_has_retailunit,1',
            'gomla_price_retail'=>'required_if:does_has_retailunit,1',
            'cost_price_retail'=>'required_if:does_has_retailunit,1',
             'has_fixed_price'=>'required',
            'active'=>'required',
        
        ];
    }
    public function messages()
    {
        //todo delete 
        return [
            'name.required'=>'Category name is required',
            'item_type.required'=>'Expenses type is required',
            'inv_itemcard_categories_id.required'=>'Main Category is required',
            'uom_id.required'=>'Basic unit is required',
            'does_has_retailunit.required'=>'this field is required',
            'retail_uom_id.required_if'=>'fragment unit is required',
            'retail_uom_quntToParent.required_if'=>'The ratio of basicUnit/fragmentUnit is required',
            'price.required'=>'price is required',
            'nos_gomla_price.required'=>'price is required',
            'gomla_price.required'=>'price is required',
            'cost_price.required'=>'price is required',
            'price_retail.required_if'=>'price is required',
             'nos_gomla_price_retail.required_if'=>'price is required',
            'gomla_price_retail.required_if'=>'price is required',
            'cost_price_retail.required_if'=>'price is required',
            'has_fixed_price.required'=>'This field is required',
            'active.required'=>'status is required'
        ];
    }
}
