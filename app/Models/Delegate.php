<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delegate extends Model
{
    use HasFactory;
    protected $table='delegates';
    protected $fillable=[
        'delegate_code', 'name', 'account_number', 'start_balance', 'start_balance_status',
         'current_balance', 'notes', 'added_by', 'updated_by', 'created_at', 'updated_at', 
         'active', 'com_code', 'date', 'phones', 'address', 'percent_type', 'percent_collect_commission',
          'percent_sales_commission_jomla', 'percent_sales_commission_nosjomla', 'percent_sales_commission_kataei'
    ];
}
