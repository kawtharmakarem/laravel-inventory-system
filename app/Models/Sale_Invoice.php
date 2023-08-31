<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale_Invoice extends Model
{
    use HasFactory;
    protected $table='sales_invoices';
    protected $fillable=[
        'auto_serial', 'sales_material_type', 'invoice_date', 'customer_code', 'is_approved',
         'com_code', 'notes', 'discount_type', 'discount_percent', 'discount_value', 'tax_percent',
          'tax_value', 'total_cost_items', 'total_before_discount', 'total_cost', 'account_number', 
          'money_for_account', 'bill_type', 'what_paid', 'what_remain', 'treasuries_transaction_id',
           'customer_balance_before', 'customer_balance_after', 'added_by', 'created_at', 'updated_at',
            'updated_by', 'approved_by','is_has_customer','delegate_code','date'
    ];
}
