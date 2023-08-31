<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inv_Itemcard extends Model
{
    use HasFactory;
    protected $table='inv_itemcard';
    protected $fillable=[
        'retail_uom_id',

        'item_code',
        'name',
        'item_type',
        'inv_itemcard_categories_id',
        'parent_inv_itemcard_id',
        'does_has_retailunit',
        'uom_id',
        'retail_uom_quntToParent',
        'added_by',
        'updated_by',
        'created_at',
        'updated_at',
        'active',
        'date',
        'com_code',
        'barcode',
        'price',
        'nos_gomla_price',
        'gomla_price',
        'price_retail',
        'nos_gomla_price_retail',
        'gomla_price_retail',
        'cost_price',
        'cost_price_retail',
        'has_fixed_price',
        'quentity',
        'quentity_retail',
        'quentity_all_retails',
        'ALL_QUANTITY',
        'photo'
    ];
}
