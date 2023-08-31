<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierWithOrder extends Model
{
    use HasFactory;
    protected $table='suppliers_with_orders';
    protected $fillable=[
        'order_type', 'auto_serial', 'doc_num', 'order_date', 'supplier_code', 'is_approved', 'com_code', 'notes', 'discount_type',
         'discount_percent', 'discount_value', 'tax_percent', 'tax_value','total_cost_items','total_before_discount', 'total_cost', 'account_number',
          'money_for_account', 'bill_type', 'what_paid', 'what_remain', 'treasuries_transaction_id', 'supplier_balance_before', 
          'supplier_balance_after', 'added_by', 'created_at', 'updated_at', 'updated_by','store_id','approved_by'
    ];
    
}
