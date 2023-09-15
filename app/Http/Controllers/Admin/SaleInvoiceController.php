<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Admin_Shift;
use App\Models\Customer;
use App\Models\Delegate;
use App\Models\Inv_Itemcard;
use App\Models\Inv_Itemcard_Batch;
use App\Models\Inv_Itemcard_Movement;
use App\Models\Inv_Uom;
use App\Models\Sale_Invoice;
use App\Models\Sales_material_types;
use App\Models\SalesInvoiceDetail;
use App\Models\Store;
use App\Models\Treasury;
use App\Models\Treasury_Transaction;
use Illuminate\Http\Request;

class SaleInvoiceController extends Controller
{
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $data = Sale_Invoice::select('*')->where(['com_code' => $com_code])->orderby('id', 'DESC')->paginate(PAGINATION_COUNT);
        if (!empty($data)) {
            foreach ($data as $key => $info) {
                $info->added_by_admin = Admin::where(['id' => $info->added_by, 'com_code' => $com_code])->value('name');
                $info->sales_material_type_name = Sales_material_types::where(['id' => $info->sales_material_type, 'com_code' => $com_code])->value('name');
                if ($info->is_has_customer == 1) {
                    $info->customer_name = Customer::where(['com_code' => $com_code, 'customer_code' => $info->customer_code])->value('name');
                } else {
                    $info->customer_name = "no customer";
                }
            }
        }
        return view('admin.sales_invoices.index', ['data' => $data]);
    }
    public function load_modal_add_mirror(Request $request)
    {
        $com_code = auth()->user()->com_code;
        if ($request->ajax()) {
            $item_cards = Inv_Itemcard::select('item_code', 'name', 'item_type')->where(['com_code' => $com_code, 'active' => 1])->get();
            $stores = Store::select('id', 'name')->where(['com_code' => $com_code, 'active' => 1])->orderby('id', 'ASC')->get();
            $user_shift = get_user_shift(new Admin_Shift(), new Treasury(), new Treasury_Transaction());
            return view('admin.sales_invoices.loadModalAddInvoiceMirror', ['item_cards' => $item_cards, 'stores' => $stores, 'user_shift' => $user_shift]);
        }
    }
    public function load_modal_add_active(Request $request)
    {
        $com_code = auth()->user()->com_code;
        if ($request->ajax()) {
            $item_cards = Inv_Itemcard::select('item_code', 'name', 'item_type')->where(['com_code' => $com_code, 'active' => 1])->get();
            $stores = Store::select('id', 'name')->where(['com_code' => $com_code, 'active' => 1])->orderby('id', 'ASC')->get();
            $user_shift = get_user_shift(new Admin_Shift(), new Treasury(), new Treasury_Transaction());
            $customers = Customer::select('customer_code', 'name')->where(['com_code' => $com_code, 'active' => 1])->get();
            $delegates = Delegate::select('delegate_code', 'name')->where(['com_code' => $com_code, 'active' => 1])->get();
            $sales_material_types = Sales_material_types::select('id', 'name')->where(['com_code' => $com_code, 'active' => 1])->get();

            return view('admin.sales_invoices.loadModalAddInvoiceActive', ['item_cards' => $item_cards, 'stores' => $stores, 'user_shift' => $user_shift, 'customers' => $customers, 'delegates' => $delegates, 'sales_material_types' => $sales_material_types]);
        }
    }

    public function get_item_uoms(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $item_code = $request->item_code;
            $item_card_data = Inv_Itemcard::select('does_has_retailunit', 'uom_id', 'retail_uom_id')->where(['com_code' => $com_code, 'item_code' => $item_code])->first();
            if (!empty($item_card_data)) {
                if ($item_card_data['does_has_retailunit'] == 1) {
                    $item_card_data['parent_uom_name'] = Inv_Uom::where(['com_code' => $com_code, 'id' => $item_card_data['uom_id']])->value('name');
                    $item_card_data['retail_uom_name'] = Inv_Uom::where(['com_code' => $com_code, 'id' => $item_card_data['retail_uom_id']])->value('name');
                } else {
                    $item_card_data['parent_uom_name'] = Inv_Uom::where(['com_code' => $com_code, 'id' => $item_card_data['uom_id']])->value('name');
                }
            }
            return view('admin.sales_invoices.get_item_uoms', ['item_card_data' => $item_card_data]);
        }
    }
    public function get_item_batches(Request $request)
    {
        $com_code = auth()->user()->com_code;

        if ($request->ajax()) {
            $item_card_Data = get_cols_where_row(new Inv_itemCard(), array("item_type", "uom_id", "retail_uom_quntToParent"), array("com_code" => $com_code, "item_code" => $request->item_code));
            if (!empty($item_card_Data)) {
                $requesed['uom_id'] = $request->uom_id;
                $requesed['store_id'] = $request->store_id;
                $requesed['item_code'] = $request->item_code;
                $parent_uom = $item_card_Data['uom_id'];
                $uom_Data = get_cols_where_row(new Inv_Uom(), array("name", "is_master"), array("com_code" => $com_code, "id" => $requesed['uom_id']));
                if (!empty($uom_Data)) {
                    //لو صنف مخزني يبقي ههتم بالتواريخ
                    if ($item_card_Data['item_type'] == 2) {
                        $inv_itemcard_batches = get_cols_where(
                            new Inv_Itemcard_Batch(),
                            array("unit_cost_price", "quantity", "production_date", "expired_date", "auto_serial"),
                            array("com_code" => $com_code, "store_id" => $requesed['store_id'], "item_code" => $requesed['item_code'], "inv_uoms_id" => $parent_uom),
                            'production_date',
                            'ASC'
                        );
                    } else {
                        $inv_itemcard_batches = get_cols_where(
                            new Inv_Itemcard_Batch(),
                            array("unit_cost_price", "quantity", "auto_serial"),
                            array("com_code" => $com_code, "store_id" => $requesed['store_id'], "item_code" => $requesed['item_code'], "inv_uoms_id" => $parent_uom),
                            'id',
                            'ASC'
                        );
                    }


                    return view("admin.sales_invoices.get_item_batches", ['item_card_Data' => $item_card_Data, 'requesed' => $requesed, 'uom_Data' => $uom_Data, 'inv_itemcard_batches' => $inv_itemcard_batches]);
                }
            }
        }
    }
    public function get_item_unit_price(Request $request)
    {
        $com_code = auth()->user()->com_code;
        if ($request->ajax()) {
            $item_card_Data = get_cols_where_row(new Inv_Itemcard(), array('uom_id', 'price', 'nos_gomla_price', 'gomla_price', 'price_retail', 'nos_gomla_price_retail', 'gomla_price_retail', 'does_has_retailunit', 'retail_uom_id'), array('com_code' => $com_code, 'item_code' => $request->item_code));
            if (!empty($item_card_Data)) {
                $uom_id = $request->uom_id;
                $sales_item_type = $request->sales_item_type;
                $uom_Data = get_cols_where_row(new Inv_Uom(), array('is_master'), array('id' => $uom_id));
                if (!empty($uom_Data)) {
                    if ($uom_Data['is_master'] == 1) {
                        if ($item_card_Data['uom_id'] == $uom_id) {
                            if ($sales_item_type == 1) {
                                echo json_encode($item_card_Data['price']);
                            } elseif ($sales_item_type == 2) {
                                echo json_encode($item_card_Data['nos_gomla_price']);
                            } else {
                                echo json_encode($item_card_Data['gomla_price']);
                            }
                        } else {
                            if ($item_card_Data['retail_uom_id'] = $uom_id and $item_card_Data['does_has_retailunit'] == 1) {
                                if ($sales_item_type == 1) {
                                    echo json_encode($item_card_Data['price_retail']);
                                } elseif ($sales_item_type == 2) {
                                    echo json_encode($item_card_Data['nos_gomla_price_retail']);
                                } else {
                                    echo json_encode($item_card_Data['gomla_price']);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    public function get_Add_new_item_row(Request $request)
    {
        $com_code = auth()->user()->com_code;
        if ($request->ajax()) {
            $received_data['store_id'] = $request->store_id;
            $received_data['sales_item_type'] = $request->sales_item_type;
            $received_data['item_code'] = $request->item_code;
            $received_data['uom_id'] = $request->uom_id;
            $received_data['inv_itemcard_batches_autoserial'] = $request->inv_itemcard_batches_autoserial;
            $received_data['item_quantity'] = $request->item_quantity;
            $received_data['item_price'] = $request->item_price;
            $received_data['is_normal_orOther'] = $request->is_normal_orOther;
            $received_data['item_total'] = $request->item_total;
            $received_data['store_name'] = $request->store_name;
            $received_data['uom_id_name'] = $request->uom_id_name;
            $received_data['item_code_name'] = $request->item_code_name;
            $received_data['sales_item_type_name'] = $request->sales_item_type_name;
            $received_data['is_normal_orOther_name'] = $request->is_normal_orOther_name;
            $received_data['isparentuom'] = $request->isparentuom;
            return view('admin.sales_invoices.get_Add_new_item_row', ['received_data' => $received_data]);
        }
    }
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $last_auto_serial_data = Sale_Invoice::select('auto_serial')->where(['com_code' => $com_code])->orderby('id', 'DESC')->first();
            if (!empty($last_auto_serial_data)) {
                $data_insert['auto_serial'] = $last_auto_serial_data['auto_serial'] + 1;
            } else {
                $data_insert['auto_serial'] = 1;
            }
            $data_insert['invoice_date'] = $request->invoice_date;
            $data_insert['is_has_customer'] = $request->is_has_customer;
            if ($request->is_has_customer == 1) {
                $data_insert['customer_code'] = $request->customer_code;
            }
            $data_insert['delegate_code'] = $request->delegate_code;
            $data_insert['bill_type'] = $request->bill_type;
            $data_insert['sales_material_type'] = $request->sales_material_type_id;
            $data_insert['added_by'] = auth()->user()->id;
            $data_insert['created_at'] = date('Y-m-d H:i:s');
            $data_insert['date'] = date('Y-m-d');
            $data_insert['com_code'] = $com_code;
            $flag = insert(new Sale_Invoice(), $data_insert, false);
            if ($flag) {
                echo json_encode($data_insert['auto_serial']);
            }
        }
    }
    public function load_invoice_update_modal(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $invoice_data = Sale_Invoice::select('*')->where(['com_code' => $com_code, 'auto_serial' => $request->auto_serial])->first();
            $item_cards = Inv_Itemcard::select('item_code', 'name', 'item_type')->where(['com_code' => $com_code, 'active' => 1])->get();
            $stores = Store::select('id', 'name')->where(['com_code' => $com_code, 'active' => 1])->orderby('id', 'ASC')->get();
            $user_shift = get_user_shift(new Admin_Shift(), new Treasury(), new Treasury_Transaction());
            $delegates = Delegate::select('delegate_code', 'name')->where(['com_code' => $com_code, 'active' => 1])->get();
            $customers = Customer::select('customer_code', 'name')->where(['com_code' => $com_code, 'active' => 1])->get();
            $sales_material_types = Sales_material_types::select('id', 'name')->where(['com_code' => $com_code, 'active' => 1])->get();
            $sales_invoice_details = SalesInvoiceDetail::select()->where(['com_code' => $com_code, 'sales_invoices_auto_serial' => $request->auto_serial])->get();
            if (!empty($sales_invoice_details)) {
                foreach ($sales_invoice_details as $info) {
                    $info->store_name = Store::where(['com_code' => $com_code, 'id' => $info->store_id])->value('name');
                    $info->item_name = Inv_Itemcard::where(['com_code' => $com_code, 'item_code' => $info->item_code])->value('name');
                    $info->uom_name = Inv_Uom::where(['com_code' => $com_code, 'id' => $info->uom_id])->value('name');
                }
            }
            return view('admin.sales_invoices.load_invoice_update_modal', ['invoice_data' => $invoice_data, 'item_cards' => $item_cards, 'stores' => $stores, 'user_shift' => $user_shift, 'delegates' => $delegates, 'customers' => $customers, 'sales_material_types' => $sales_material_types, 'sales_invoice_details' => $sales_invoice_details]);
        }
    }
    public function Add_item_to_invoice(Request $request)
    {
        try {
            if ($request->ajax()) {
                $com_code = auth()->user()->com_code;
                $invoice_data = get_cols_where_row(new Sale_Invoice(), array("is_approved", "invoice_date", "is_has_customer", "customer_code"), array("com_code" => $com_code, "auto_serial" => $request->invoiceautoserial));

                if (!empty($invoice_data)) {
                    if ($invoice_data['is_approved'] == 0) {
                        $batch_data = get_cols_where_row(new Inv_Itemcard_Batch(), array("quantity", "unit_cost_price", "id"), array("com_code" => $com_code, "auto_serial" => $request->inv_itemcard_batches_autoserial, 'store_id' => $request->store_id, 'item_code' => $request->item_code));
                        if (!empty($batch_data)) {

                            if ($batch_data['quantity'] >= $request->item_quantity) {
                                $itemCard_Data = get_cols_where_row(new Inv_Itemcard(), array("uom_id", "retail_uom_quntToParent", "retail_uom_id", "does_has_retailunit"), array("com_code" => $com_code, "item_code" => $request->item_code));
                                if (!empty($itemCard_Data)) {
                                    $MainUomName = get_field_value(new Inv_uom(), "name", array("com_code" => $com_code, "id" => $itemCard_Data['uom_id']));

                                    $datainsert_items['sales_invoices_auto_serial'] = $request->invoiceautoserial;
                                    $datainsert_items['store_id'] = $request->store_id;
                                    $datainsert_items['invoice_date'] = $invoice_data['invoice_date'];
                                    $datainsert_items['sales_item_type'] = $request->sales_item_type;
                                    $datainsert_items['item_code'] = $request->item_code;
                                    $datainsert_items['uom_id'] = $request->uom_id;
                                    $datainsert_items['batch_auto_serial'] = $request->inv_itemcard_batches_autoserial;
                                    $datainsert_items['quantity'] = $request->item_quantity;
                                    $datainsert_items['unit_price'] = $request->item_price;
                                    $datainsert_items['is_normal_orOther'] = $request->is_normal_orOther;
                                    $datainsert_items['total_price'] = $request->item_total;
                                    $datainsert_items['isparentuom'] = $request->isparentuom;
                                    $datainsert_items['added_by'] = auth()->user()->id;
                                    $datainsert_items['created_at'] = date("Y-m-d H:i:s");
                                    $datainsert_items['date'] = date("Y-m-d");
                                    $datainsert_items['com_code'] = $com_code;
                                    $flag_datainsert_items = insert(new SalesInvoiceDetail(), $datainsert_items, true);
                                    if (!empty($flag_datainsert_items)) {

                                        //خصم الكمية من الباتش 
                                        //كمية الصنف بكل المخازن قبل الحركة
                                        $quantityBeforMove = get_sum_where(
                                            new Inv_Itemcard_Batch(),
                                            "quantity",
                                            array(
                                                "item_code" => $request->item_code,
                                                "com_code" => $com_code
                                            )
                                        );

                                        //get Quantity Befor any Action  حنجيب كيمة الصنف  بالمخزن المحدد معه   الحالي قبل الحركة
                                        $quantityBeforMoveCurrntStore = get_sum_where(
                                            new Inv_Itemcard_Batch(),
                                            "quantity",
                                            array(
                                                "item_code" => $request->item_code, "com_code" => $com_code,
                                                'store_id' => $request->store_id
                                            )
                                        );

                                        //هنا حخصم الكمية لحظيا من باتش الصنف
                                        //update current Batch تحديث علي الباتش القديمة
                                        $dataUpdateOldBatch['quantity'] = $batch_data['quantity'] - $request->item_quantity;
                                        $dataUpdateOldBatch['total_cost_price'] = $batch_data['unit_cost_price'] * $dataUpdateOldBatch['quantity'];
                                        $dataUpdateOldBatch["updated_at"] = date("Y-m-d H:i:s");
                                        $dataUpdateOldBatch["updated_by"] = auth()->user()->id;
                                        $flag = update(new Inv_Itemcard_Batch(), $dataUpdateOldBatch, array("id" => $batch_data['id'], "com_code" => $com_code));

                                        if ($flag) {
                                            $quantityAfterMove = get_sum_where(
                                                new Inv_Itemcard_Batch(),
                                                "quantity",
                                                array(
                                                    "item_code" => $request->item_code,
                                                    "com_code" => $com_code
                                                )
                                            );

                                            //get Quantity Befor any Action  حنجيب كيمة الصنف  بالمخزن المحدد معه   الحالي قبل الحركة
                                            $quantityAfterMoveCurrentStore = get_sum_where(
                                                new Inv_Itemcard_Batch(),
                                                "quantity",
                                                array("item_code" => $request->item_code, "com_code" => $com_code, 'store_id' => $request->store_id)
                                            );
                                            //التاثير في حركة كارت الصنف

                                            $dataInsert_inv_itemcard_movements['inv_itemcard_movements_categories'] = 2;
                                            $dataInsert_inv_itemcard_movements['items_movements_types'] = 4;
                                            $dataInsert_inv_itemcard_movements['item_code'] = $request->item_code;
                                            //كود الفاتورة الاب
                                            $dataInsert_inv_itemcard_movements['FK_table'] = $request->invoiceautoserial;
                                            //كود صف الابن بتفاصيل الفاتورة
                                            $dataInsert_inv_itemcard_movements['FK_table_details'] = $flag_datainsert_items['id'];
                                            if ($invoice_data['is_has_customer'] == 1) {
                                                $customerName = get_field_value(new Customer(), "name", array("com_code" => $com_code, "customer_code" => $invoice_data['customer_code']));
                                            } else {
                                                $customerName = "no customer";
                                            }

                                            $dataInsert_inv_itemcard_movements['byan'] = "For customer sales :" . " " . $customerName . "Bill number :" . " " . $request->invoiceautoserial;
                                            //كمية الصنف بكل المخازن قبل الحركة
                                            $dataInsert_inv_itemcard_movements['quantity_befor_movement'] = "amount : " . " " . ($quantityBeforMove * 1) . " " . $MainUomName;
                                            // كمية الصنف بكل المخازن بعد  الحركة
                                            $dataInsert_inv_itemcard_movements['quantity_after_move'] = "amount : " . " " . ($quantityAfterMove * 1) . " " . $MainUomName;

                                            //كمية الصنف  المخزن الحالي قبل الحركة
                                            $dataInsert_inv_itemcard_movements['quantity_befor_move_store'] = "amount :" . " " . ($quantityBeforMoveCurrntStore * 1) . " " . $MainUomName;
                                            // كمية الصنف بالمخزن الحالي بعد الحركة الحركة
                                            $dataInsert_inv_itemcard_movements['quantity_after_move_store'] = "amount : " . " " . ($quantityAfterMoveCurrentStore * 1) . " " . $MainUomName;
                                            $dataInsert_inv_itemcard_movements["store_id"] = $request->store_id;

                                            $dataInsert_inv_itemcard_movements["created_at"] = date("Y-m-d H:i:s");
                                            $dataInsert_inv_itemcard_movements["added_by"] = auth()->user()->id;
                                            $dataInsert_inv_itemcard_movements["date"] = date("Y-m-d");
                                            $dataInsert_inv_itemcard_movements["com_code"] = $com_code;
                                            $flag = insert(new Inv_Itemcard_Movement(), $dataInsert_inv_itemcard_movements);
                                            if ($flag) {
                                                //update itemcard Quantity mirror  تحديث المرآه الرئيسية للصنف
                                                do_update_itemCardQuantity(
                                                    new Inv_itemCard(),
                                                    $request->item_code,
                                                    new Inv_Itemcard_Batch(),
                                                    $itemCard_Data['does_has_retailunit'],
                                                    $itemCard_Data['retail_uom_quntToParent']
                                                );

                                                echo  json_encode("done");
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $ex) {
            echo "there is error " . $ex->getMessage();
        }
    }
    public function reload_items_in_invoice(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $sales_invoice_details = SalesInvoiceDetail::select('*')->where(['com_code' => $com_code, 'sales_invoices_auto_serial' => $request->auto_serial])->get();
            if (!empty($sales_invoice_details)) {
                foreach ($sales_invoice_details as $info) {
                    $info->store_name = Store::where(['com_code' => $com_code, 'id' => $info->store_id])->value('name');
                    $info->item_name = Inv_Itemcard::where(['com_code' => $com_code, 'item_code' => $info->item_code])->value('name');
                    $info->uom_name = Inv_Uom::where(['com_code' => $com_code, 'id' => $info->uom_id])->value('name');
                }
            }
            return view('admin.sales_invoices.reload_items_in_invoice', ['sales_invoice_details' => $sales_invoice_details]);
        }
    }
    public function recalclate_parent_invoice(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $invoice_data = Sale_Invoice::select('*')->where(['com_code' => $com_code, 'auto_serial' => $request->auto_serial])->first();
            if (!empty($invoice_data)) {
                if (!empty($invoice_data['is_approved'] == 0)) {
                    $dataUpdateParent['invoice_date'] = $request->invoice_date;
                    $dataUpdateParent['is_has_customer'] = $request->is_has_customer;
                    if ($dataUpdateParent['is_has_customer'] != "") {
                        $dataUpdateParent['customer_code'] = $request->customer_code;
                    } else {
                        $dataUpdateParent['customer_code'] = null;
                    }
                    $dataUpdateParent['delegate_code'] = $request->delegate_code;
                    $dataUpdateParent['bill_type'] = $request->bill_type;
                    $dataUpdateParent['sales_material_type'] = $request->sales_material_type_id;
                    $dataUpdateParent['total_cost_items'] = $request->total_cost_items;
                    $dataUpdateParent['tax_percent'] = $request->tax_percent;
                    $dataUpdateParent['tax_value'] = $request->tax_value;
                    $dataUpdateParent['total_before_discount'] = $request->total_before_discount;
                    $dataUpdateParent['discount_type'] = $request->discount_type;
                    $dataUpdateParent['discount_percent'] = $request->discount_percent;
                    $dataUpdateParent['discount_value'] = $request->discount_value;
                    $dataUpdateParent['total_cost'] = $request->total_cost;
                    $dataUpdateParent['notes'] = $request->notes;
                    $dataUpdateParent['updated_at'] = date("Y-m-d H:i:s");
                    $dataUpdateParent['updated_by'] = auth()->user()->id;
                    update(new Sale_Invoice(), $dataUpdateParent, array('com_code' => $com_code, 'auto_serial' => $request->auto_serial));
                    echo json_encode('done');
                }
            }
        }
    }
    public function remove_active_row_item(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $invoice_data = Sale_Invoice::select('is_approved', 'is_has_customer', 'customer_code')->where(['com_code' => $com_code, 'auto_serial' => $request->auto_serial])->first();
            if (!empty($invoice_data)) {
                if ($invoice_data['is_approved'] == 0) {
                    $sales_invoice_details_data = SalesInvoiceDetail::select('batch_auto_serial', 'quantity', 'store_id', 'item_code')->where(['com_code' => $com_code, 'id' => $request->id])->first();
                    if (!empty($sales_invoice_details_data)) {
                        $batch_data = Inv_Itemcard_Batch::select('quantity', 'unit_cost_price', 'id')->where(['com_code' => $com_code, 'auto_serial' => $sales_invoice_details_data['batch_auto_serial']])->first();
                        if (!empty($batch_data)) {
                            $item_card_Data = Inv_Itemcard::select('uom_id', 'does_has_retailunit', 'retail_uom_quntToParent', 'retail_uom_id')->where(['com_code' => $com_code, 'item_code' => $sales_invoice_details_data['item_code']])->first();
                            if (!empty($item_card_Data)) {
                                $MainUomName = Inv_Uom::where(['com_code' => $com_code, 'id' => $item_card_Data['uom_id']])->value('name');

                                //delete row from the invoice
                                $flag = SalesInvoiceDetail::where(['com_code' => $com_code, 'id' => $request->id])->delete();
                                if ($flag) {
                                    //return quantity to batch
                                    $quantityBeforMove = Inv_Itemcard_Batch::where(['com_code' => $com_code, 'item_code' => $sales_invoice_details_data['item_code']])->sum('quantity');
                                    $quantityBeforMoveCurrntStore = Inv_Itemcard_Batch::where(['com_code' => $com_code, 'store_id' => $sales_invoice_details_data['store_id'], 'item_code' => $sales_invoice_details_data['item_code']])->sum('quantity');

                                    //update current batch
                                    $dataUpdateOldBatch['quantity'] = $batch_data['quantity'] + $sales_invoice_details_data['quantity'];
                                    $dataUpdateOldBatch['total_cost_price'] = $dataUpdateOldBatch['quantity'] * $batch_data['unit_cost_price'];
                                    $dataUpdateOldBatch['updated_at'] = date("Y-m-d H:i:s");
                                    $dataUpdateOldBatch['updated_by'] = auth()->user()->id;
                                    $flag = Inv_Itemcard_Batch::where(['id' => $batch_data['id'], 'com_code' => $com_code])->update($dataUpdateOldBatch);
                                    $quantityAfterMove = Inv_Itemcard_Batch::where(['com_code' => $com_code, 'item_code' => $sales_invoice_details_data['item_code']])->sum('quantity');
                                    $quantityAfterMoveCurrentStore = Inv_Itemcard_Batch::where(['com_code' => $com_code, 'item_code' => $sales_invoice_details_data['item_code'], 'store_id' => $sales_invoice_details_data['store_id']])->sum('quantity');
                                    $dataInsert_inv_itemcard_movements['inv_itemcard_movements_categories'] = 2;
                                    $dataInsert_inv_itemcard_movements['items_movements_types'] = 15;
                                    $dataInsert_inv_itemcard_movements['item_code'] = $sales_invoice_details_data['item_code'];
                                    $dataInsert_inv_itemcard_movements['FK_table'] = $request->auto_serial;
                                    $dataInsert_inv_itemcard_movements['FK_table_details'] = $request->id;
                                    if ($invoice_data['is_has_customer']) {
                                        $customerName = Customer::where(['com_code' => $com_code, 'customer_code' => $invoice_data['customer_code']])->value('name');
                                    } else {
                                        $customerName = "no customer";
                                    }
                                    $dataInsert_inv_itemcard_movements['byan'] = "Delete item from customer :" . " " . $customerName . "Bill number :" . " " . $request->invoiceautoserial;
                                    //كمية الصنف بكل المخازن قبل الحركة
                                    $dataInsert_inv_itemcard_movements['quantity_befor_movement'] = "amount : " . " " . ($quantityBeforMove * 1) . " " . $MainUomName;
                                    // كمية الصنف بكل المخازن بعد  الحركة
                                    $dataInsert_inv_itemcard_movements['quantity_after_move'] = "amount : " . " " . ($quantityAfterMove * 1) . " " . $MainUomName;

                                    //كمية الصنف  المخزن الحالي قبل الحركة
                                    $dataInsert_inv_itemcard_movements['quantity_befor_move_store'] = "amount :" . " " . ($quantityBeforMoveCurrntStore * 1) . " " . $MainUomName;
                                    // كمية الصنف بالمخزن الحالي بعد الحركة الحركة
                                    $dataInsert_inv_itemcard_movements['quantity_after_move_store'] = "amount : " . " " . ($quantityAfterMoveCurrentStore * 1) . " " . $MainUomName;
                                    $dataInsert_inv_itemcard_movements["store_id"] = $sales_invoice_details_data['store_id'];
                                    $dataInsert_inv_itemcard_movements['created_at'] = date("Y-m-d H:i:s");
                                    $dataInsert_inv_itemcard_movements['added_by'] = auth()->user()->id;
                                    $dataInsert_inv_itemcard_movements['date'] = date("Y-m-d");
                                    $dataInsert_inv_itemcard_movements['com_code'] = $com_code;
                                    $flag = Inv_Itemcard_Movement::create($dataInsert_inv_itemcard_movements);
                                    if ($flag) {
                                        do_update_itemCardQuantity(new Inv_Itemcard(), $sales_invoice_details_data['item_code'], new Inv_Itemcard_Batch(), $item_card_Data['does_has_retailunit'], $item_card_Data['retail_uom_quntToParent']);
                                        echo json_encode('done');
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    public function DoApproveInvoiceFinally(Request $request)
    {
        if($request->ajax()){
            $com_code=auth()->user()->com_code;
            $invoice_data=Sale_Invoice::select('is_approved','bill_type','customer_code','is_has_customer')->where(['com_code'=>$com_code,'auto_serial'=>$request->auto_serial])->first();
            if(!empty($invoice_data)){
                if($invoice_data['is_approved']==0){
                    $dataUpdateParent['money_for_account']=$invoice_data['total_cost'];
                    $dataUpdateParent['is_approved']=1;
                    $dataUpdateParent['approved_by']=auth()->user()->id;
                    $dataUpdateParent['updated_at']=date('Y-m-d H:i:s');
                    $dataUpdateParent['updated_by']=auth()->user()->id;
                    $dataUpdateParent['what_paid']=$request->what_paid;
                    $dataUpdateParent['what_remain']=$request->what_remain;
                    if($invoice_data['is_has_customer']==1){
                        $customerData=Customer::select('account_number')->where(['com_code'=>$com_code,'customer_code'=>$invoice_data['customer_code']])->first();
                        $dataUpdateParent['account_number']=$customerData['account_number'];
                    }
                    $flag=Sale_Invoice::where(['com_code'=>$com_code,'auto_serial'=>$request->auto_serial])->update($dataUpdateParent);
                    if($flag){
                        if($request->what_paid>0){
                            $user_shift=get_user_shift(new Admin_Shift(),new Treasury(),new Treasury_Transaction());
                            $treasury_data=Treasury::select('last_isal_collect')->where(['com_code'=>$com_code,'id'=>$user_shift['treasuries_id']])->first();
                            $last_record_treasuries_transactions_record=Treasury_Transaction::select('auto_serial')->where(['com_code'=>$com_code])->orderby('auto_serial','DESC')->first();
                            if(!empty($last_record_treasuries_transactions_record)){
                                $dataInsert_treasuries_transactions['auto_serial']=$last_record_treasuries_transactions_record['auto_serial']+1;
                            }else{
                                $dataInsert_treasuries_transactions['auto_serial']=1;
                            }
                            $dataInsert_treasuries_transactions['isal_number']=$treasury_data['last_isal_collect']+1;
                            $dataInsert_treasuries_transactions['shift_code']=$user_shift['shift_code'];
                            //credit
                            $dataInsert_treasuries_transactions['money']=$request->what_paid;
                            $dataInsert_treasuries_transactions['treasuries_id']=$user_shift['treasuries_id'];
                            $dataInsert_treasuries_transactions['mov_type']=5;
                            $dataInsert_treasuries_transactions['move_date']=date('Y-m-d');
                            if($invoice_data['is_has_customer']==1){
                                $dataInsert_treasuries_transactions['account_number']=$customerData['account_number'];
                                $dataInsert_treasuries_transactions['is_account']=1;
                            }
                            $dataInsert_treasuries_transactions['is_approved']=1;
                            $dataInsert_treasuries_transactions['the_foregin_key']=$request->auto_serial;
                            //debit
                            $dataInsert_treasuries_transactions['money_for_account']=$request->what_paid*(-1);
                            $dataInsert_treasuries_transactions['byan']="collect for :".$request->auto_serial;
                            $dataInsert_treasuries_transactions['created_at']=date('Y-m-d H:i:s');
                            $dataInsert_treasuries_transactions['added_by']=auth()->user()->id;
                            $dataInsert_treasuries_transactions['com_code']=$com_code;
                            $flag=Treasury_Transaction::create($dataInsert_treasuries_transactions);
                            if($flag){
                                $dataUpdateTreasuries['last_isal_exchange']=$dataInsert_treasuries_transactions['isal_number'];
                                Treasury::where(['com_code'=>$com_code,'id'=>$user_shift['treasuries_id']])->update($dataUpdateTreasuries);
                                echo json_encode('done');
                            }


                        }
                    }
                }
            }
        }

    }
    public function load_usershiftDiv(Request $request)
    {
        if($request->ajax()){
            $com_code=auth()->user()->com_code;
            $user_shift=get_user_shift(new Admin_Shift(),new Treasury(),new Treasury_Transaction());

        }
        return view('admin.sales_invoices.load_usershiftDiv',['user_shift'=>$user_shift]);
    }
}
