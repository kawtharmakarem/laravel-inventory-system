<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuppliersWithOrdersRequest;
use App\Models\Account;
use App\Models\Admin;
use App\Models\Admin_panel_setting;
use App\Models\Admin_Shift;
use App\Models\Inv_Itemcard;
use App\Models\Inv_Itemcard_Batch;
use App\Models\Inv_Itemcard_Movement;
use App\Models\Inv_Uom;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\SupplierWithOrder;
use App\Models\SupplierWithOrderDetail;
use App\Models\Treasury;
use App\Models\Treasury_Transaction;
use Illuminate\Http\Request;

class SuppliersWithOrdersController extends Controller
{
  public function index()
  {
    $com_code = auth()->user()->com_code;
    $data = SupplierWithOrder::select()->orderby('id', 'DESC')->paginate(PAGINATION_COUNT);
    if (!empty($data)) {
      foreach ($data as $info) {
        $info->added_by_admin = Admin::where(['id' => $info->added_by, 'com_code' => $com_code])->value('name');
        $info->supplier_name = Supplier::where(['supplier_code' => $info->supplier_code, 'com_code' => $com_code])->value('name');
        $info->store_name = Store::where('id', $info->store_id)->value('name');
        if ($info->updated_by > 0 and $info->updated_by > 0) {
          $info->updated_by_admin = Admin::where(['id' => $info->updated_by, 'com_code' => $com_code])->value('name');
        }
      }
    }
    $suppliers = Supplier::select('supplier_code', 'name')->where(['com_code' => $com_code])->orderby('id', 'DESC')->get();
    $stores = Store::select('id', 'name')->where(['com_code' => $com_code, 'active' => 1])->orderby('id', 'DESC')->get();
    return view('admin.suppliers_with_orders.index', ['data' => $data, 'suppliers' => $suppliers, 'stores' => $stores]);
  }
  public function create()
  {
    $com_code = auth()->user()->com_code;
    $suppliers = Supplier::select('supplier_code', 'name')->where(['com_code' => $com_code, 'active' => 1])->orderby('id', 'DESC')->get();
    $stores = get_cols_where(new Store(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
    return view('admin.suppliers_with_orders.create', ['suppliers' => $suppliers, 'stores' => $stores]);
  }
  public function store(SuppliersWithOrdersRequest $request)
  {
    try {
      $com_code = auth()->user()->com_code;
      $suppliersData = get_cols_where_row(new Supplier(), array('account_number'), array('supplier_code' => $request->supplier_code, 'com_code' => $com_code));
      if (empty($suppliersData)) {
        return redirect()->back()->with(['error' => "Unable to find required supplier's data"])->withInput();
      }

      $row = get_cols_where_row_orderby(new SupplierWithOrder(), array('auto_serial'), array('com_code' => $com_code), 'id', 'DESC');
      if (!empty($row)) {
        $data_insert['auto_serial'] = $row['auto_serial'] + 1;
      } else {
        $data_insert['auto_serial'] = 1;
      }
      $data_insert['order_date'] = $request->order_date;
      $data_insert['doc_num'] = $request->doc_num;
      $data_insert['supplier_code'] = $request->supplier_code;
      $data_insert['bill_type'] = $request->bill_type;
      $data_insert['account_number'] = $suppliersData['account_number'];
      $data_insert['order_type'] = 1; //purchases,
      $data_insert['store_id'] = $request->store_id;
      $data_insert['added_by'] = auth()->user()->id;
      $data_insert['created_at'] = date("Y-m-d H:i:s");
      $data_insert['com_code'] = $com_code;
      SupplierWithOrder::create($data_insert);
      $id = get_field_value(new SupplierWithOrder(), 'id', array('auto_serial' => $data_insert['auto_serial'], 'com_code' => $com_code, 'order_type' => 1));
      return redirect()->route('admin.supplierswithorders.show', $id)->with(['success' => 'Data is added successfully .']);
    } catch (\Exception $ex) {
      return redirect()->back()->with(['error' => 'Sorry! Something went wrong ..' . $ex->getMessage()])->withInput();
    }
  }
  public function show($id)
  {
    try {
      $com_code = auth()->user()->com_code;
      $data = get_cols_where_row(new SupplierWithOrder(), array('*'), array('id' => $id, 'com_code' => $com_code, 'order_type' => 1));
      if (empty($data)) {
        return redirect()->route('admin.supplierswithorders.index')->with(['error' => 'Sorry !Unable to find reuired data.']);
      }
      $data['added_by_admin'] = Admin::where(['id' => $data['added_by'], 'com_code' => $com_code])->value('name');
      $data['supplier_name'] = Supplier::where('supplier_code', $data['supplier_code'])->value('name');
      $data['store_name'] = Store::where(['id' => $data['store_id'], 'com_code' => $com_code])->value('name');

      if ($data['updated_by'] > 0 and $data['updated_by'] != null) {
        $data['updated_by_admin'] = Admin::where(['id' => $data['updated_by'], 'com_code' => $com_code])->value('name');
      }
      $details = SupplierWithOrderDetail::select()->where(['suppliers_with_orders_auto_serial' => $data['auto_serial'], 'order_type' => 1, 'com_code' => $com_code])->orderby('id', 'DESC')->get();
      if (!empty($details)) {
        foreach ($details as $info) {
          $info->item_card_name = Inv_Itemcard::where(['item_code' => $info->item_code])->value('name');
          $info->uom_name = Inv_Uom::where('id', $info->uom_id)->value('name');


          $data['added_by_admin'] = Admin::where('id', $data['added_by'])->value('name');
          if ($data['updated_by'] > 0 and $data['updated_by']) {
            $data['updated_by_admin'] = Admin::where('id', $data['updated_by'])->value('name');
          }
        }
      }
      //if bill is still open
      if ($data['is_approved'] == 0) {
        $item_cards = Inv_Itemcard::select('name', 'item_code', 'item_type')->where(['com_code' => $com_code, 'active' => 1])->orderby('id', 'DESC')->get();
      } else {
        $item_cards = array();
      }

      return view('admin.suppliers_with_orders.show', ['data' => $data, 'details' => $details, 'item_cards' => $item_cards]);
    } catch (\Exception $ex) {
      return redirect()->back()->with(['error' => 'Sorry! Something went wrong ..' . $ex->getMessage()]);
    }
  }
  public function get_item_uoms(Request $request)
  {
    if ($request->ajax()) {
      $com_code = auth()->user()->com_code;
      $item_code = $request->item_code;
      $item_card_data = get_cols_where_row(new Inv_Itemcard(), array('does_has_retailunit', 'retail_uom_id', 'uom_id'), array('item_code' => $item_code, 'com_code' => $com_code));
      if (!empty($item_card_data)) {
        if ($item_card_data['does_has_retailunit'] == 1) {
          $item_card_data['parent_uom_name'] = get_field_value(new Inv_Uom(), 'name', array('id' => $item_card_data['uom_id']));
          $item_card_data['retail_uom_name'] = get_field_value(new Inv_Uom(), 'name', array('id' => $item_card_data['retail_uom_id']));
        } else {
          $item_card_data['parent_uom_name'] = get_field_value(new Inv_Uom(), 'name', array('id' => $item_card_data['uom_id']));
        }
      }
      return view('admin.suppliers_with_orders.get_item_uoms', ['item_card_data' => $item_card_data]);
    }
  }

  public function add_new_details(Request $request)
  {
    if ($request->ajax()) {
      $com_code = auth()->user()->com_code;
      $item_code = $request->item_code_add;
      $suppliers_with_ordersData = get_cols_where_row(new SupplierWithOrder(), array('is_approved', 'order_date', 'tax_value', 'discount_value'), array('auto_serial' => $request->autoserialparent, 'com_code' => $com_code, 'order_type' => 1));
      if (!empty($suppliers_with_ordersData)) {
        if ($suppliers_with_ordersData['is_approved'] == 0) {
          $data_insert['suppliers_with_orders_auto_serial'] = $request->autoserialparent;
          $data_insert['order_type'] = 1;
          $data_insert['item_code'] = $item_code;
          $data_insert['deliverd_quantity'] = $request->quantity_add;
          $data_insert['unit_price'] = $request->price_add;
          $data_insert['uom_id'] = $request->uom_id_Add;
          $data_insert['isparentuom'] = $request->isparentuom;
          if ($request->type == 2) {
            $data_insert['production_date'] = $request->production_date;
            $data_insert['expire_date'] = $request->expire_date;
          }
          $data_insert['item_card_type'] = $request->type;
          $data_insert['total_price'] = $request->total_add;
          $data_insert['order_date'] = $suppliers_with_ordersData['order_date'];
          $data_insert['added_by'] = auth()->user()->id;
          $data_insert['created_at'] = date('Y-m-d H:i:s');
          $data_insert['com_code'] = $com_code;
          $flag = SupplierWithOrderDetail::create($data_insert);
          if ($flag) {
            /*--update parent bill-- */
            $total_details_sum = SupplierWithOrderDetail::where(['suppliers_with_orders_auto_serial' => $request->autoserialparent, 'order_type' => 1, 'com_code' => $com_code])->sum('total_price');
            $dataUpdateParent['total_cost_items'] = $total_details_sum;
            $dataUpdateParent['total_before_discount'] = $total_details_sum + $suppliers_with_ordersData['tax_value'];
            $dataUpdateParent['total_cost'] = $dataUpdateParent['total_before_discount'] - $suppliers_with_ordersData['discount_value'];
            $dataUpdateParent['updated_by'] = auth()->user()->id;
            $dataUpdateParent['updated_at'] = date('Y-m-d H:i:s');
            SupplierWithOrder::where(['auto_serial' => $request->autoserialparent, 'order_type' => 1, 'com_code' => $com_code])->update($dataUpdateParent);

            echo json_encode('done');
          }
        }
      }
    }
  }

  public function reload_itemsdetails(Request $request)
  {
    if ($request->ajax()) {
      $com_code = auth()->user()->com_code;
      $auto_serial = $request->autoserialparent;
      $data = get_cols_where_row(new SupplierWithOrder(), array('is_approved'), array('auto_serial' => $auto_serial, 'com_code' => $com_code, 'order_type' => 1));
      if (!empty($data)) {
        $details = get_cols_where(new SupplierWithOrderDetail(), array('*'), array('suppliers_with_orders_auto_serial' => $auto_serial, 'order_type' => 1, 'com_code' => $com_code), 'id', 'DESC');
        if (!empty($details)) {
          foreach ($details as $info) {
            $info->item_card_name = Inv_Itemcard::where('item_code', $info->item_code)->value('name');
            $info->uom_name = Inv_Uom::where(['id' => $info->uom_id])->value('name');
            $data['added_by_admin'] = Admin::where(['id' => $data['added_by']])->value('name');
            if ($data['updated_by'] > 0 and $data['updated_by'] != null) {
              $data['updated_by_admin'] = Admin::where(['id' => $data['updated_by']])->value('name');
            }
          }
        }
      }
      return view('admin.suppliers_with_orders.reload_itemsdetails', ['data' => $data, 'details' => $details]);
    }
  }
  public function reload_parent_bill(Request $request)
  {
    if ($request->ajax()) {
      $com_code = auth()->user()->com_code;
      $data = get_cols_where_row(new SupplierWithOrder(), array("*"), array('auto_serial' => $request->autoserialparent, 'com_code' => $com_code, 'order_type' => 1));
      if (!empty($data)) {
        $data['added_by_admin'] = Admin::where('id', $data['added_by'])->value('name');
        $data['supplier_name'] = Supplier::where('supplier_code', $data['supplier_code'])->value('name');
        if ($data['updated_by'] > 0 and $data['updated_by'] != null) {
          $data['updated_by_admin'] = Admin::where('id', $data['updated_by'])->value('name');
        }
      }
      return view('admin.suppliers_with_orders.reload_parent_bill', ['data' => $data]);
    }
  }
  public function edit($id)
  {
    $com_code = auth()->user()->com_code;
    $data = SupplierWithOrder::select('*')->where(['id' => $id, 'com_code' => $com_code, 'order_type' => 1])->first();
    if (empty($data)) {
      return redirect()->route('admin.supplierswithorders.index')->with(['error' => "unable to find required data !!"]);
    }
    if ($data['is_approved'] == 1) {
      return redirect()->route('admin.supplierswithorders.index')->with(['error' => 'Sorry ! The bill is closed an archived']);
    }
    $suppliers = Supplier::select('supplier_code', 'name')->where(['com_code' => $com_code, 'active' => 1])->orderby('id', 'DESC')->get();
    $stores = Store::select('id', 'name')->where(['com_code' => $com_code, 'active' => 1])->orderby('id', 'DESC')->get();
    return view('admin.suppliers_with_orders.edit', ['data' => $data, 'suppliers' => $suppliers, 'stores' => $stores]);
  }
  public function update($id, SuppliersWithOrdersRequest $request)
  {
    try {
      $com_code = auth()->user()->com_code;
      $data = get_cols_where(new SupplierWithOrder(), array('is_approved'), array('id' => $id, 'com_code' => $com_code, 'order_type' => 1), 'id', 'DESC');
      if (empty($data)) {
        return redirect()->route('admin.supplierswithorders.index')->with(['error' => 'Unable to find required data!!']);
      }
      $suppliersData = Supplier::select('account_number', 'supplier_code')->where(['supplier_code' => $request->supplier_code, 'com_code' => $com_code])->first();
      if (empty($suppliersData)) {
        return redirect()->back()->with(['error' => 'Sorry ! unable to find the required supplier information'])->withInput();
      }
      $data_update['order_date'] = $request->order_date;
      $data_update['order_type'] = 1;
      $data_update['doc_num'] = $request->doc_num;
      $data_update['supplier_code'] = $request->supplier_code;
      $data_update['bill_type'] = $request->bill_type;
      $data_update['store_id'] = $request->store_id;
      $data_update['account_number'] = $suppliersData['account_number'];
      $data_update['updated_by'] = auth()->user()->id;
      $data_update['updated_at'] = date('Y-m-d H:i:s');
      update(new SupplierWithOrder(), $data_update, array('id' => $id, 'com_code' => $com_code, 'order_type' => 1));
      return redirect()->route('admin.supplierswithorders.show', $id)->with(['success' => 'Data is updated successfully .']);
    } catch (\Exception $ex) {
      return redirect()->back()->with(['error' => 'Sorry ! Something went wrong ..' . $ex->getMessage()])->withInput();
    }
  }
  public function load_edit_item_details(Request $request)
  {
    if ($request->ajax()) {
      $com_code = auth()->user()->com_code;
      $parent_bill_data = SupplierWithOrder::select('is_approved')->where(['auto_serial' => $request->autoserialparent, 'com_code' => $com_code, 'order_type' => 1])->first();
      if (!empty($parent_bill_data)) {
        if ($parent_bill_data['is_approved'] == 0) {
          $item_data_details = SupplierWithOrderDetail::select('*')->where(['suppliers_with_orders_auto_serial' => $request->autoserialparent, 'id' => $request->id, 'com_code' => $com_code, 'order_type' => 1])->first();
          $item_cards = Inv_Itemcard::select('name', 'item_code', 'item_type')->where(['com_code' => $com_code, 'active' => 1])->orderby('id', 'DESC')->get();
          $item_card_data = Inv_Itemcard::select('does_has_retailunit', 'retail_uom_id', 'uom_id')->where(['item_code' => $item_data_details['item_code'], 'com_code' => $com_code])->first();
          if (!empty($item_card_data)) {
            if ($item_card_data['does_has_retailunit'] == 1) {
              $item_card_data['parent_uom_name'] = Inv_Uom::where(['id' => $item_card_data['uom_id']])->value('name');
              $item_card_data['retail_uom_name'] = Inv_Uom::where(['id' => $item_card_data['retail_uom_id']])->value('name');
            } else {
              $item_card_data['parent_uom_name'] = Inv_Uom::where(['id' => $item_card_data['uom_id']])->value('name');
            }
          }
          return view('admin.suppliers_with_orders.load_edit_item_details', ['parent_bill_data' => $parent_bill_data, 'item_data_details' => $item_data_details, 'item_cards' => $item_cards, 'item_card_data' => $item_card_data]);
        }
      }
    }
  }
  public function load_modal_add_details(Request $request)
  {
    if ($request->ajax()) {
      $com_code = auth()->user()->com_code;
      $parent_bill_data = SupplierWithOrder::select('is_approved')->where(['com_code' => $com_code, 'auto_serial' => $request->autoserialparent, 'order_type' => 1])->first();
      if (!empty($parent_bill_data)) {
        if ($parent_bill_data['is_approved'] == 0) {
          $item_cards = Inv_Itemcard::select('name', 'item_code', 'item_type')->where(['com_code' => $com_code, 'active' => 1])->orderby('id', 'DESC')->get();
          return view('admin.suppliers_with_orders.load_add_new_itemdetails', ['parent_bill_data' => $parent_bill_data, 'item_cards' => $item_cards]);
        }
      }
    }
  }
  public function edit_item_details(Request $request)
  {
    if ($request->ajax()) {
      $com_code = auth()->user()->com_code;
      $parent_bill_data = SupplierWithOrder::select('is_approved', 'order_date', 'tax_value', 'discount_value')->where(['auto_serial' => $request->autoserialparent, 'com_code' => $com_code, 'order_type' => 1])->first();
      if (!empty($parent_bill_data)) {
        if ($parent_bill_data['is_approved'] == 0) {
          $data_to_update['item_code'] = $request->item_code_add;
          $data_to_update['deliverd_quantity'] = $request->quantity_add;
          $data_to_update['unit_price'] = $request->price_add;
          $data_to_update['uom_id'] = $request->uom_id_Add;
          $data_to_update['isparentuom'] = $request->isparentuom;
          if ($request->type == 2) {
            $data_to_update['production_date'] = $request->production_date;
            $data_to_update['expire_date'] = $request->expire_date;
          }
          $data_to_update['item_card_type'] = $request->type;
          $data_to_update['total_price'] = $request->total_add;
          $data_to_update['order_date'] = $parent_bill_data['order_date'];
          $data_to_update['updated_by'] = auth()->user()->id;
          $data_to_update['updated_at'] = date('Y-m-d H:i:s');
          $data_to_update['com_code'] = $com_code;
          $flag = update(new SupplierWithOrderDetail(), $data_to_update, array('id' => $request->id, 'com_code' => $com_code, 'order_type' => 1, 'suppliers_with_orders_auto_serial' => $request->autoserialparent));
          if ($flag) {
            /*update parent bill */
            $total_details_sum = get_sum_where(new SupplierWithOrderDetail(), 'total_price', array('suppliers_with_orders_auto_serial' => $request->autoserialparent, 'order_type' => 1, 'com_code' => $com_code));
            $dataUpdateParent['total_cost_items'] = $total_details_sum;
            $dataUpdateParent['total_before_discount'] = $total_details_sum + $parent_bill_data['tax_value'];
            $dataUpdateParent['total_cost'] = $dataUpdateParent['total_before_discount'] - $parent_bill_data['discount_value'];
            $dataUpdateParent['updated_by'] = auth()->user()->id;
            $dataUpdateParent['updated_at'] = date('Y-m-d H:i:s');
            update(new SupplierWithOrder(), $dataUpdateParent, array('auto_serial' => $request->autoserialparent, 'com_code' => $com_code, 'order_type' => 1));
            echo json_encode('done');
          }
        }
      }
    }
  }
  public function delete_details($id, $parent_id)
  {
    try {
      $com_code = auth()->user()->com_code;
      $parent_bill_data = SupplierWithOrder::select('is_approved', 'auto_serial')->where(['id' => $parent_id, 'com_code' => $com_code, 'order_type' => 1])->first();
      if (empty($parent_bill_data)) {
        return redirect()->back()->with(['error' => 'Sorry! Something went wrong.']);
      }
      if ($parent_bill_data['is_approved'] == 1) {
        if (empty($parent_bill_data)) {
          return redirect()->back()->with(['error' => 'You can not deleted closed and archived bill !!!']);
        }
      }
      $item_row = SupplierWithOrderDetail::select('id')->where(['id' => $id])->first();
      if (!empty($item_row)) {
        $flag = $item_row->delete();
        if ($flag) {
          /**update parent bill */
          $total_details_sum = SupplierWithOrderDetail::where(['suppliers_with_orders_auto_serial' => $parent_bill_data['auto_serial'], 'order_type' => 1, 'com_code' => $com_code])->sum('total_price');
          $dataUpdateParent['total_cost_items'] = $total_details_sum;
          $dataUpdateParent['total_before_discount'] = $total_details_sum + $parent_bill_data['tax_value'];
          $dataUpdateParent['total_cost'] = $dataUpdateParent['total_before_discount'] - $parent_bill_data['discount_value'];
          $dataUpdateParent['updated_by'] = auth()->user()->id;
          $dataUpdateParent['updated_at'] = date('Y-m-d H:i:s');
          update(new SupplierWithOrder(), $dataUpdateParent, array('id' => $parent_id, 'com_code' => $com_code, 'order_type' => 1));
          return redirect()->back()->with(['success' => 'Data is deleted successfully..']);
        } else {
          return redirect()->back()->with(['error' => 'Sorry!Something went wrong !!']);
        }
      } else {
        return redirect()->back()->with(['error' => 'Unable to find required data !!!']);
      }
    } catch (\Exception $ex) {
      return redirect()->back()->with(['error' => 'Sorry ! Something went wrong ..' . $ex->getMessage()]);
    }
  }
  public function delete($id)
  {
    try {
      $com_code = auth()->user()->com_code;
      $parent_bill_data = SupplierWithOrder::select('is_approved', 'auto_serial')->where(['id' => $id, 'com_code' => $com_code, 'order_type' => 1])->first();
      if (empty($parent_bill_data)) {
        return redirect()->back()->with(['error' => 'Sorry ! Something went wrong !!!']);
      }
      if ($parent_bill_data['is_approved'] == 1) {
        if (empty($parent_bill_data)) {
          return redirect()->back()->with(['error' => 'You can not delete closed an archived bill !!!']);
        }
      }
      $flag = delete(new SupplierWithOrder(), array('id' => $id, 'com_code' => $com_code, 'order_type' => 1));
      if ($flag) {
        //delete all details
        delete(new SupplierWithOrderDetail(), array('suppliers_with_orders_auto_serial' => $parent_bill_data['auto_serial'], 'com_code' => $com_code, 'order_type' => 1));
        return redirect()->route('admin.supplierswithorders.index')->with(['success' => 'Data is deleted successfully..']);
      }
    } catch (\Exception $ex) {
      return redirect()->back()->with(['error' => 'Sorry!Something went wrong..' . $ex->getMessage()]);
    }
  }
  public function load_modal_approve_invoice(Request $request)
  {
    if ($request->ajax()) {
      $com_code = auth()->user()->com_code;
      $data = SupplierWithOrder::select("*")->where(['auto_serial' => $request->autoserialparent, 'com_code' => $com_code, 'order_type' => 1])->first();
      //current user shift
      $user_shift = get_user_shift(new Admin_Shift(), new Treasury(), new Treasury_Transaction());
    }
    return view('admin.suppliers_with_orders.load_modal_approve_invoice', ['data' => $data, 'user_shift' => $user_shift]);
  }

  public function load_usershiftDiv(Request $request)
  {
    if ($request->ajax()) {
      $com_code = auth()->user()->com_code;
      //current user shift
      $user_shift = get_user_shift(new Admin_Shift(), new Treasury(), new Treasury_Transaction());
    }
    return view('admin.suppliers_with_orders.load_usershiftDiv', ['user_shift' => $user_shift]);
  }

  public function do_approve($auto_serial, Request $request)
  {
    $com_code = auth()->user()->com_code;
    $data = get_cols_where_row(new SupplierWithOrder(), array('total_cost_items', 'is_approved', 'id', 'account_number', 'store_id', 'supplier_code'), array('auto_serial' => $auto_serial, 'com_code' => $com_code, 'order_type' => 1));
    if (empty($data)) {
      return redirect()->route('admin.supplierswithorders.index')->with(['error' => 'Unable to find required data!!']);
    }
    $supplierName = get_field_value(new Supplier(), 'name', array('supplier_code' => $data['supplier_code'], 'com_code' => $com_code));
    if ($data['is_approved'] == 1) {
      return redirect()->route('admin.supplierswithorders.show', $data['id'])->with(['error' => 'A previously approved invoice cannot br approved!!!']);
    }
    $dataUpdateParent['tax_percent'] = $request['tax_percent'];
    $dataUpdateParent['tax_value'] = $request['tax_value'];
    $dataUpdateParent['total_before_discount'] = $request['total_before_discount'];
    $dataUpdateParent['discount_type'] = $request['discount_type'];
    $dataUpdateParent['discount_percent'] = $request['discount_percent'];
    $dataUpdateParent['discount_value'] = $request['discount_value'];
    $dataUpdateParent['total_cost'] = $request['total_cost'];
    $dataUpdateParent['bill_type'] = $request['bill_type'];
    $dataUpdateParent['money_for_account'] = $request['total_cost'] * (-1);

    $dataUpdateParent['is_approved'] = 1;
    $dataUpdateParent['approved_by'] = auth()->user()->com_code;
    $dataUpdateParent['updated_at'] = date('Y-m-d H:i:s');
    $dataUpdateParent['updated_by'] = auth()->user()->com_code;
    //first check for bill type
    if ($request['bill_type'] == 1) {
      if ($request['what_paid'] != $request['total_cost']) {
        return redirect()->route('admin.supplierswithorders.show', $data['id'])->with(['error' => 'Sorry , the full ammount must be paid in the case of a cash bill !!!']);
      }
    }
    if ($request['bill_type'] == 2) {
      if ($request['what_paid'] == $request['total_cost']) {
        return redirect()->route('admin.supplierswithorders.show', $data['id'])->with(['error' => 'Sorry , the full ammount must not be paid in the case of deferred bill !!!']);
      }
    }
    $dataUpdateParent['what_paid'] = $request['what_paid'];
    $dataUpdateParent['what_remain'] = $request['what_remain'];

    //check for what paid
    if ($request['what_paid'] > 0) {
      if ($request['what_paid'] > $request['total_cost']) {
        return redirect()->route('admin.supplierswithorders.show', $data['id'])->with(['error' => "Sorry ,paid amount must be less than total of the bill !!!"]);
      }
      //check for user shift
      $user_shift = get_user_shift(new Admin_Shift(), new Treasury(), new Treasury_Transaction());
      if (empty($user_shift)) {
        return redirect()->route('admin.supplierswithorders.show', $data['id'])->with(['error' => "Sorry ,There isn't open shift now !!!!!"]);
      }
      //check for balance
      if ($user_shift['balance'] < $request['what_paid']) {
        return redirect()->route('admin.supplierswithorders.show', $data['id'])->with(['error' => "Sorry , there is no enough balance in treasury !!"]);
      }
    }
    $flag = update(new SupplierWithOrder(), $dataUpdateParent, array('auto_serial' => $auto_serial, 'com_code' => $com_code, 'order_type' => 1));
    if ($flag) {
      //affect on supplier balance
      //make treasuries_transaction action
      if ($request['what_paid'] > 0) {
        $treasury_data = Treasury::select('last_isal_exchange')->where(['com_code' => $com_code, 'id' => $user_shift['treasuries_id']])->first();
        if (empty($treasury_data)) {
          return redirect()->route('admin.supplierswithorders.show', $data['id'])->with(['error' => "Unable to find required treasury data !!!"]);
        }
        $last_record_treasuries_transactions_record = Treasury_Transaction::select('auto_serial')->where(['com_code' => $com_code])->orderby('auto_serial', 'DESC')->first();
        if (!empty($last_record_treasuries_transactions_record)) {
          $data_insert_treasury_transaction['auto_serial'] = $last_record_treasuries_transactions_record['auto_serial'] + 1;
        } else {
          $data_insert_treasury_transaction['auto_serial'] = 1;
        }
        $data_insert_treasury_transaction['isal_number'] = $treasury_data['last_isal_exchange'] + 1;
        $data_insert_treasury_transaction['shift_code'] = $user_shift['shift_code'];
        //credit
        $data_insert_treasury_transaction['money'] = $request['what_paid'] * (-1);
        $data_insert_treasury_transaction['treasuries_id'] = $user_shift['treasuries_id'];
        $data_insert_treasury_transaction['mov_type'] = 9;
        $data_insert_treasury_transaction['move_date'] = date("Y-m-d");
        $data_insert_treasury_transaction['account_number'] = $data["account_number"];
        $data_insert_treasury_transaction['is_account'] = 1;
        $data_insert_treasury_transaction['is_approved'] = 1;
        $data_insert_treasury_transaction['the_foregin_key'] = $data['auto_serial'];
        //debit
        $data_insert_treasury_transaction['money_for_account'] = $request['what_paid'];
        $data_insert_treasury_transaction['byan'] = "Exchange for purchases bill number :" . $auto_serial;
        $data_insert_treasury_transaction['created_at'] = date("Y-m-d H:i:s");
        $data_insert_treasury_transaction['added_by'] = auth()->user()->id;
        $data_insert_treasury_transaction['com_code'] = $com_code;
        $flag = insert(new Treasury_Transaction(), $data_insert_treasury_transaction);
        if ($flag) {
          //update 
          $dataUpdateTreasuries['last_isal_exchange'] = $data_insert_treasury_transaction['isal_number'];
          update(new Treasury(), $dataUpdateTreasuries, array('com_code' => $com_code, "id" => $user_shift['treasuries_id']));
        }
      }
      refresh_account_balance_supplier($data['account_number'], new Account(), new Supplier(), new Treasury_Transaction(), new SupplierWithOrder(), false);

      $items = SupplierWithOrderDetail::select('*')->where(['suppliers_with_orders_auto_serial' => $auto_serial, 'com_code' => $com_code, 'order_type' => 1])->orderby('id', 'ASC')->get();
      if (!empty($items)) {
        foreach ($items as $info) {
          //details for evry item
          $item_card_data = Inv_Itemcard::select('uom_id', 'retail_uom_quntToParent', 'retail_uom_id', 'does_has_retailunit')->where(['com_code' => $com_code, 'item_code' => $info->item_code])->first();
          if (!empty($item_card_data)) {

            //quantity of item in all barachs or stores
            $quantityBeforeMove = get_sum_where(new Inv_Itemcard_Batch(), 'quantity', array('item_code' => $info->item_code, 'com_code' => $com_code));
            //quantity of item in this brach or store
            $quantityBeforeMoveCurrentStore = get_sum_where(new Inv_Itemcard_Batch(), 'quantity', array('com_code' => $com_code, 'item_code' => $info->item_code, 'store_id' => $data['store_id']));

            $MainUomName = get_field_value(new Inv_Uom(), 'name', array('com_code' => $com_code, 'id' => $item_card_data['uom_id']));


            //if is parent uom
            if ($info->isparentuom == 1) {
              $quantity = $info->deliverd_quantity;
              $unit_price = $info->unit_price;
            } else {
              //if it isn't parent uom
              $quantity = ($info->deliverd_quantity / $item_card_data['retail_uom_quntToParent']);
              $unit_price = $info->unit_price * $item_card_data['retail_uom_quntToParent'];
            }


            if ($info->item_card_type == 2) {
              $dataInsertBatch['store_id'] = $data['store_id'];
              $dataInsertBatch['item_code'] = $info->item_code;
              $dataInsertBatch['production_date'] = $info->production_date;
              $dataInsertBatch['expired_date'] = $info->expire_date;
              $dataInsertBatch['unit_cost_price'] = $unit_price;
              $dataInsertBatch['inv_uoms_id'] = $item_card_data['uom_id'];
            } else {
              $dataInsertBatch['store_id'] = $data['store_id'];
              $dataInsertBatch['item_code'] = $info->item_code;
              $dataInsertBatch['unit_cost_price'] = $unit_price;
              $dataInsertBatch['inv_uoms_id'] = $item_card_data['uom_id'];
            }
            $oldBatchExists = get_cols_where_row(new Inv_Itemcard_Batch(), array("quantity", 'id', 'unit_cost_price'), $dataInsertBatch);
            if (!empty($oldBatchExists)) {
              //update current batch
              $dataUpdataOldBatch['quantity'] = $oldBatchExists['quantity'] + $quantity;
              $dataUpdataOldBatch['total_cost_price'] = $oldBatchExists['unit_cost_price'] * $dataUpdataOldBatch['quantity'];
              $dataUpdataOldBatch['created_at'] = date('Y-m-d H:i:s');
              $dataUpdataOldBatch['added_by'] = auth()->user()->id;
              update(new Inv_Itemcard_Batch(), $dataUpdataOldBatch, array('id' => $oldBatchExists['id'], 'com_code' => $com_code));
            } else {
              //insert new batch
              $dataInsertBatch['quantity'] = $quantity;
              $dataInsertBatch['total_cost_price'] = $info->total_price;
              $dataInsertBatch['created_at'] = date("Y-m-d H:i:s");
              $dataInsertBatch['added_by'] = auth()->user()->id;
              $dataInsertBatch['com_code'] = $com_code;
              $row = SupplierWithOrder::select('auto_serial')->where(['com_code' => $com_code])->orderby('id', 'DESC')->first();
              if (!empty($row)) {
                $dataInsertBatch['auto_serial'] = $row['auto_serial'] + 1;
              } else {
                $dataInsertBatch['auto_serial'] = 1;
              }
              insert(new Inv_Itemcard_Batch(), $dataInsertBatch);
            }
            // كمية الصنف بكل المخازن بعد اتمام حركة الباتشات وترحيلها 
            $quantityAfterMove = Inv_Itemcard_Batch::where(['item_code' => $info->item_code, 'com_code' => $com_code])->sum('quantity');
            //كمية الصنف بمخزن فاتورة الشراء بعد اتمام حركة الباتشات و ترحيلها
            $quantityAfterMoveCurrentStore = Inv_Itemcard_Batch::where(['com_code' => $com_code, 'item_code' => $info->item_code, 'store_id' => $data['store_id']])->sum('quantity');
            $dataInsert_inv_itemcard_movements['inv_itemcard_movements_categories'] = 1;
            $dataInsert_inv_itemcard_movements['items_movements_types'] = 1;
            $dataInsert_inv_itemcard_movements['item_code'] = $info->item_code;
            //كود الفاتورة الاب
            $dataInsert_inv_itemcard_movements['FK_table'] = $auto_serial;
            //كود صتف الابن في تفاصيل الفاتورة 
            $dataInsert_inv_itemcard_movements['FK_table_details'] = $info->id;
            $dataInsert_inv_itemcard_movements['byan'] = "For Purchases from supplier " . " " . $supplierName . " " . "Bill number : " . $auto_serial;
            //كمية الصنف بكل المخازن قبل الخركة 
            $dataInsert_inv_itemcard_movements['quantity_befor_movement'] = "Number : " . " " . ($quantityBeforeMove * 1) . " " . $MainUomName;
            //كمية الصنف بكل المخازن بعد الحركة
            $dataInsert_inv_itemcard_movements['quantity_after_move'] = "Number :" . " " . ($quantityAfterMove * 1) . " " . $MainUomName;
            //كمية الصنف المخزن الخالي فبل الحركة
            $dataInsert_inv_itemcard_movements['quantity_befor_move_store'] = "Number : " . ($quantityBeforeMoveCurrentStore * 1) . " " . $MainUomName;
            $dataInsert_inv_itemcard_movements['quantity_after_move_store'] = "Number : " . ($quantityAfterMoveCurrentStore * 1) . " " . $MainUomName;
            $dataInsert_inv_itemcard_movements['store_id'] = $data['store_id'];

            $dataInsert_inv_itemcard_movements['created_at'] = date("Y-m-d H:i:s");
            $dataInsert_inv_itemcard_movements['added_by'] = auth()->user()->id;
            $dataInsert_inv_itemcard_movements['date'] = date('Y-m-d');
            $dataInsert_inv_itemcard_movements['com_code'] = $com_code;
            insert(new Inv_Itemcard_Movement(), $dataInsert_inv_itemcard_movements);
          }
          //update last cost price
          if ($info->isparentuom == 1) {
            $dataUpdateItemCardCosts['cost_price'] = $info->unit_price;
            if ($item_card_data['does_has_retailunit'] == 1) {
              $dataUpdateItemCardCosts['cost_price_retail'] = $info->unit_price / $item_card_data['retail_uom_quntToParent'];
            }
          } else {
            //لو كان الشراء بوحدة تجزءى
            $dataUpdateItemCardCosts['cost_price_retail'] = $info->unit_price;
            $dataUpdateItemCardCosts['cost_price'] = $info->unit_Price * $item_card_data['retail_uom_quntToParent'];
          }
          update(new Inv_Itemcard(), $dataUpdateItemCardCosts, array('com_code' => $com_code, 'item_code' => $info->item_code));
          //update itemcard Quantity mirror
          do_update_itemCardQuantity(new Inv_Itemcard(), $info->item_code, new Inv_Itemcard_Batch(), $item_card_data['does_has_retailunit'], $item_card_data['retail_uom_quntToParent']);
        }
      }
    }
    return redirect()->route('admin.supplierswithorders.show', $data['id'])->with(['success' => 'The bill has been approved and transferd successfully..']);
  }
  public function ajax_search(Request $request)
  {
    if($request->ajax())
    {
    $searchbyradio=$request->searchbyradio;
    $supplier_code=$request->supplier_code;
    $search_by_text=$request->search_by_text;
    $store_id=$request->store_id;
    $order_date_from=$request->order_date_from;
    $order_date_to=$request->order_date_to;
    if($supplier_code=='all'){
      $field1="id";
      $operator1=">";
      $value1=0;
    }else{
      $field1="supplier_code";
      $operator1="=";
      $value1=$supplier_code;
    }

    if($store_id=='all'){
      $field2="id";
      $operator2=">";
      $value2=0;
    }else{
      $field2="store_id";
      $operator2='=';
      $value2=$store_id;
    }

    if($order_date_from==''){
      $field3="id";
      $operator3=">";
      $value3=0;
    }else{
      $field3="order_date";
      $operator3=">=";
      $value3=$order_date_from;
    }
     
    if($order_date_to==''){
      $field4="id";
      $operator4=">";
      $value4=0;
    }else{
      $field4="order_date";
      $operator4="<=";
      $value4=$order_date_to;
    }

    if($search_by_text!=''){
      if($searchbyradio=='auto_serial'){
        $field5='auto_serial';
        $operator5='=';
        $value5=$search_by_text;
      }else{
        $field5='doc_num';
        $operator5="=";
        $value5=$search_by_text;
      }
    }else{
      $field5="id";
      $operator5=">";
      $value5=0;
    }
    $data=SupplierWithOrder::where($field1,$operator1,$value1)->where($field2,$operator2,$value2)->where($field3,$operator3,$value3)->where($field4,$operator4,$value4)->where($field5,$operator5,$value5)->orderby('id','DESC')->paginate(PAGINATION_COUNT);
    if(!empty($data))
    {
      foreach ($data as $info) {
        $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');
        $info->supplier_name=Supplier::where('supplier_code',$info->supplier_code)->value('name');
        $info->store_name=Store::where('id',$info->store_id)->value('name');
        if($info->updated_by>0 and $info->updated!=null)
        {
          $info->updated_by_admin=Admin::where('id',$info->updated_by)->value('name');
        }
      }
    }
    return view('admin.suppliers_with_orders.ajax_search',['data'=>$data]);
    }
  }
}
