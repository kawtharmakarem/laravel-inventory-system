<?php

use App\Http\Controllers\Admin\Account_typesController;
use App\Http\Controllers\Admin\AccountsController;
use App\Http\Controllers\Admin\Admin_panel_settingsController;
use App\Http\Controllers\Admin\Admin_ShiftController;
use App\Http\Controllers\Admin\Admincontroller;
use App\Http\Controllers\Admin\CollectController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DelegatesController;
use App\Http\Controllers\Admin\ExchangeController;
use App\Http\Controllers\Admin\Inv_itemcardController;
use App\Http\Controllers\Admin\InvItemCategoriesController;
use App\Http\Controllers\Admin\InvUomsController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\SaleInvoiceController;
use App\Http\Controllers\Admin\Sales_material_typesController;
use App\Http\Controllers\Admin\StoresController;
use App\Http\Controllers\Admin\SuppliersCategoriesController;
use App\Http\Controllers\Admin\SuppliersController;
use App\Http\Controllers\Admin\SuppliersWithOrdersController;
use App\Http\Controllers\Admin\TreasuriesController;
use App\Models\Admin_panel_setting;
use App\Models\Supplier;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
define('PAGINATION_COUNT',11);


Route::group(['prefix'=>'admin','middleware'=>'auth:admin'],function(){
Route::get('/',[DashboardController::class,'index'])->name('admin.dashboard');
Route::get('logout',[LoginController::class,'logout'])->name('admin.logout');
Route::get('/adminpanelsetting/index',[Admin_panel_settingsController::class,'index'])->name('admin.adminpanelsetting.index');
Route::get('/adminpanelsetting/edit',[Admin_panel_settingsController::class,'edit'])->name('admin.adminpanelsetting.edit');
Route::post('/adminpanelsetting/update',[Admin_panel_settingsController::class,'update'])->name('admin.adminpanelsetting.update');
/*    start treasuries    */
Route::get('/treasuries/index',[TreasuriesController::class,'index'])->name('admin.treasuries.index');
Route::get('/treasuries/create',[TreasuriesController::class,'create'])->name('admin.treasuries.create');
Route::post('/treasuries/store',[TreasuriesController::class,'store'])->name('admin.treasuries.store');
Route::get('/treasuries/edit/{id}',[TreasuriesController::class,'edit'])->name('admin.treasuries.edit');
Route::post('/treasuries/update/{id}',[TreasuriesController::class,'update'])->name('admin.treasuries.update');
Route::post('/treasuries/ajax_search/',[TreasuriesController::class,'ajax_search'])->name('admin.treasuries.ajax_search');
Route::get('/treasuries/details/{id}',[TreasuriesController::class,'details'])->name('admin.treasuries.details');
Route::get('/treasuries/add_treasuries_delivery/{id}',[TreasuriesController::class,'add_treasuries_delivery'])->name('admin.treasuries.add_treasuries_delivery');
Route::post('/treasuries/store_treasuries_delivery/{id}',[TreasuriesController::class,'store_treasuries_delivery'])->name('admin.treasuries.store_treasuries_delivery');
Route::get('/treasuries/delete_treasuries_delivery/{id}',[TreasuriesController::class,'delete_treasuries_delivery'])->name('admin.treasuries.delete_treasuries_delivery');

/* end treasuries    */
/*------------start sales_material_types------------------- */
Route::get('/sales_material_types/index',[Sales_material_typesController::class,'index'])->name('admin.sales_material_types.index');
Route::get('/sales_material_types/create',[Sales_material_typesController::class,'create'])->name('admin.sales_material_types.create');
Route::post('/sales_material_types/store',[Sales_material_typesController::class,'store'])->name('admin.sales_material_types.store');
Route::get('/sales_material_types/edit/{id}',[Sales_material_typesController::class,'edit'])->name('admin.sales_material_types.edit');
Route::post('/sales_material_types/update/{id}',[Sales_material_typesController::class,'update'])->name('admin.sales_material_types.update');
Route::get('/sales_material_types/delete/{id}',[Sales_material_typesController::class,'delete'])->name('admin.sales_material_types.delete');

/*------------end sales_material_types-------------------- */
/*------------start of stores --------------------------- */
Route::get('/stores/index',[StoresController::class,'index'])->name('admin.stores.index');
Route::get('/stores/create',[StoresController::class,'create'])->name('admin.stores.create');
Route::post('/stores/store',[StoresController::class,'store'])->name('admin.stores.store');
Route::get('/stores/edit/{id}',[StoresController::class,'edit'])->name('admin.stores.edit');
Route::post('/stores/update/{id}',[StoresController::class,'update'])->name('admin.stores.update');
Route::get('/stores/delete/{id}',[StoresController::class,'delete'])->name('admin.stores.delete');

/*------------end of stores------------------------------*/
/*-------------start of inv_uoms------------------------*/
Route::get('/uoms/index',[InvUomsController::class,'index'])->name('admin.uoms.index');
Route::get('/uoms/create',[InvUomsController::class,'create'])->name('admin.uoms.create');
Route::post('/uoms/store',[InvUomsController::class,'store'])->name('admin.uoms.store');
Route::get('/uoms/edit/{id}',[InvUomsController::class,'edit'])->name('admin.uoms.edit');
Route::post('/uoms/update/{id}',[InvUomsController::class,'update'])->name('admin.uoms.update');
Route::get('/uoms/delete/{id}',[InvUomsController::class,'delete'])->name('admin.uoms.delete');
Route::post('/uoms/ajax_search/',[InvUomsController::class,'ajax_search'])->name('admin.uoms.ajax_search');

/*-------------end of inv_uoms-------------------------*/
/*------------start inv_itemcard_categories--------------- */
Route::get('/inv_itemcard_categories/delete/{id}',[InvItemCategoriesController::class,'delete'])->name('inv_itemcard_categories.delete');
Route::resource('/inv_itemcard_categories',InvItemCategoriesController::class);

/*------------end inv_itemcard_categories--------------- */
/*------------start inv_itemcard--------------- */
Route::get('/inv_itemcard/index',[Inv_itemcardController::class,'index'])->name('admin.inv_itemcard.index');
Route::get('/inv_itemcard/create',[Inv_itemcardController::class,'create'])->name('admin.inv_itemcard.create');
Route::post('/inv_itemcard/store',[Inv_itemcardController::class,'store'])->name('admin.inv_itemcard.store');
Route::get('/inv_itemcard/edit/{id}',[Inv_itemcardController::class,'edit'])->name('admin.inv_itemcard.edit');
Route::post('/inv_itemcard/update/{id}',[Inv_itemcardController::class,'update'])->name('admin.inv_itemcard.update');
Route::get('/inv_itemcard/delete/{id}',[Inv_itemcardController::class,'delete'])->name('admin.inv_itemcard.delete');
Route::post('/inv_itemcard/ajax_search/',[Inv_itemcardController::class,'ajax_search'])->name('admin.inv_itemcard.ajax_search');
Route::get('/inv_itemcard/show/{id}',[Inv_itemcardController::class,'show'])->name('admin.inv_itemcard.show');

/*------------end inv_itemcard--------------- */
/*------------start account types--------------- */
Route::get('/accounttypes/index',[Account_typesController::class,'index'])->name('admin.accounttypes.index');
/*------------end account types--------------- */
/*------------start accounts--------------- */
Route::get('/accounts/index',[AccountsController::class,'index'])->name('admin.accounts.index');
Route::get('/accounts/create',[AccountsController::class,'create'])->name('admin.accounts.create');
Route::post('/accounts/store',[AccountsController::class,'store'])->name('admin.accounts.store');
Route::get('/accounts/edit/{id}',[AccountsController::class,'edit'])->name('admin.accounts.edit');
Route::post('/accounts/update/{id}',[AccountsController::class,'update'])->name('admin.accounts.update');
Route::get('/accounts/delete/{id}',[AccountsController::class,'delete'])->name('admin.accounts.delete');
Route::post('/accounts/ajax_search/',[AccountsController::class,'ajax_search'])->name('admin.accounts.ajax_search');
Route::get('/accounts/show/{id}',[AccountsController::class,'show'])->name('admin.accounts.show');

/*------------end accounts--------------- */
/*------------strart customers--------------- */
Route::get('/customers/index',[CustomersController::class,'index'])->name('admin.customers.index');
Route::get('/customers/create',[CustomersController::class,'create'])->name('admin.customers.create');
Route::post('/customers/store',[CustomersController::class,'store'])->name('admin.customers.store');
Route::get('/customers/edit/{id}',[CustomersController::class,'edit'])->name('admin.customers.edit');
Route::post('/customers/update/{id}',[CustomersController::class,'update'])->name('admin.customers.update');
Route::get('/customers/delete/{id}',[CustomersController::class,'delete'])->name('admin.customers.delete');
Route::post('/customers/ajax_search/',[CustomersController::class,'ajax_search'])->name('admin.customers.ajax_search');
Route::get('/customers/show/{id}',[CustomersController::class,'show'])->name('admin.customers.show');

/*------------end customers--------------- */
/*------------strart suppliers_categories--------------- */
Route::get('/suppliers_categories/index',[SuppliersCategoriesController::class,'index'])->name('admin.suppliers_categories.index');
Route::get('/suppliers_categories/create',[SuppliersCategoriesController::class,'create'])->name('admin.suppliers_categories.create');
Route::post('/suppliers_categories/store',[SuppliersCategoriesController::class,'store'])->name('admin.suppliers_categories.store');
Route::get('/suppliers_categories/edit/{id}',[SuppliersCategoriesController::class,'edit'])->name('admin.suppliers_categories.edit');
Route::post('/suppliers_categories/update/{id}',[SuppliersCategoriesController::class,'update'])->name('admin.suppliers_categories.update');
Route::get('/suppliers_categories/delete/{id}',[SuppliersCategoriesController::class,'delete'])->name('admin.suppliers_categories.delete');
/*------------end suppliers_categories--------------- */
/*------------start suppliers------------------------ */
Route::get('/suppliers/index',[SuppliersController::class,'index'])->name('admin.suppliers.index');
Route::get('/suppliers/create',[SuppliersController::class,'create'])->name('admin.suppliers.create');
Route::post('/suppliers/store',[SuppliersController::class,'store'])->name('admin.suppliers.store');
Route::get('/suppliers/edit/{id}',[SuppliersController::class,'edit'])->name('admin.suppliers.edit');
Route::post('/suppliers/update/{id}',[SuppliersController::class,'update'])->name('admin.suppliers.update');
Route::get('/suppliers/delete/{id}',[SuppliersController::class,'delete'])->name('admin.suppliers.delete');
Route::post('/suppliers/ajax_search/',[SuppliersController::class,'ajax_search'])->name('admin.suppliers.ajax_search');
Route::get('/suppliers/show/{id}',[SuppliersController::class,'show'])->name('admin.suppliers.show');

/*------------end suppliers------------------------ */

/*------------start supplierswithorders------------------------ */
Route::get('/supplierswithorders/index',[SuppliersWithOrdersController::class,'index'])->name('admin.supplierswithorders.index');
Route::get('/supplierswithorders/create',[SuppliersWithOrdersController::class,'create'])->name('admin.supplierswithorders.create');
Route::post('/supplierswithorders/store',[SuppliersWithOrdersController::class,'store'])->name('admin.supplierswithorders.store');
Route::get('/supplierswithorders/edit/{id}',[SuppliersWithOrdersController::class,'edit'])->name('admin.supplierswithorders.edit');
Route::post('/supplierswithorders/update/{id}',[SuppliersWithOrdersController::class,'update'])->name('admin.supplierswithorders.update');
Route::get('/supplierswithorders/delete/{id}',[SuppliersWithOrdersController::class,'delete'])->name('admin.supplierswithorders.delete');
Route::post('/supplierswithorders/ajax_search/',[SuppliersWithOrdersController::class,'ajax_search'])->name('admin.supplierswithorders.ajax_search');
Route::get('/supplierswithorders/show/{id}',[SuppliersWithOrdersController::class,'show'])->name('admin.supplierswithorders.show');
Route::post('/supplierswithorders/get_item_uoms/',[SuppliersWithOrdersController::class,'get_item_uoms'])->name('admin.supplierswithorders.get_item_uoms');
Route::post('/supplierswithorders/add_new_details/',[SuppliersWithOrdersController::class,'add_new_details'])->name('admin.supplierswithorders.add_new_details');
Route::post('/supplierswithorders/load_modal_add_details/',[SuppliersWithOrdersController::class,'load_modal_add_details'])->name('admin.supplierswithorders.load_modal_add_details');

Route::post('/supplierswithorders/reload_itemsdetails/',[SuppliersWithOrdersController::class,'reload_itemsdetails'])->name('admin.supplierswithorders.reload_itemsdetails');
Route::post('/supplierswithorders/reload_parent_bill/',[SuppliersWithOrdersController::class,'reload_parent_bill'])->name('admin.supplierswithorders.reload_parent_bill');
Route::post('/supplierswithorders/load_edit_item_details/',[SuppliersWithOrdersController::class,'load_edit_item_details'])->name('admin.supplierswithorders.load_edit_item_details');
Route::post('/supplierswithorders/edit_item_details',[SuppliersWithOrdersController::class,'edit_item_details'])->name('admin.supplierswithorders.edit_item_details');
Route::get('/supplierswithorders/delete_details/{id}/{id_parent}',[SuppliersWithOrdersController::class,'delete_details'])->name('admin.supplierswithorders.delete_details');
Route::post('/supplierswithorders/do_approve/{id}',[SuppliersWithOrdersController::class,'do_approve'])->name('admin.supplierswithorders.do_approve');
Route::post('/supplierswithorders/load_modal_approve_invoice',[SuppliersWithOrdersController::class,'load_modal_approve_invoice'])->name('admin.supplierswithorders.load_modal_approve_invoice');
Route::post('/supplierswithorders/load_usershiftDiv',[SuppliersWithOrdersController::class,'load_usershiftDiv'])->name('admin.supplierswithorders.load_usershiftDiv');

/*-------------end supplierswithorders------------------------ */
/*-------------start of admins------------------------ */
Route::get('/admins_accounts/index',[AdminController::class,'index'])->name('admin.admins_accounts.index');
Route::get('/admins_accounts/create',[AdminController::class,'create'])->name('admin.admins_accounts.create');
Route::post('/admins_accounts/store',[AdminController::class,'store'])->name('admin.admins_accounts.store');
Route::get('/admins_accounts/edit/{id}',[AdminController::class,'edit'])->name('admin.admins_accounts.edit');
Route::post('/admins_accounts/update/{id}',[AdminController::class,'update'])->name('admin.admins_accounts.update');
Route::post('/admins_accounts/ajax_search/',[AdminController::class,'ajax_search'])->name('admin.admins_accounts.ajax_search');
Route::get('/admins_accounts/details/{id}',[AdminController::class,'details'])->name('admin.admins_accounts.details');

Route::get('/admins_accounts/Add_treasuries_delivery/{id}',[AdminController::class,'Add_treasuries_delivery'])->name('admin.admins_accounts.Add_treasuries_delivery');
Route::post('/admins_accounts/Add_treasuries_To_Admin/{id}',[AdminController::class,'Add_treasuries_To_Admin'])->name('admin.admins_accounts.Add_treasuries_To_Admin');

/*-------------end of admins------------------------ */
/*-------------start admins_shifts------------------------ */
Route::get('/admin_shift/index',[Admin_ShiftController::class,'index'])->name('admin.admin_shift.index');
Route::get('/admin_shift/create',[Admin_ShiftController::class,'create'])->name('admin.admin_shift.create');
Route::post('/admin_shift/store',[Admin_ShiftController::class,'store'])->name('admin.admin_shift.store');
/*-------------end of admins_shifts------------------------ */
/*-------------start of collection transaction------------------------ */
Route::get('/collect_transaction/index',[CollectController::class,'index'])->name('admin.collect_transaction.index');
Route::get('/collect_transaction/create',[CollectController::class,'create'])->name('admin.collect_transaction.create');
Route::post('/collect_transaction/store',[CollectController::class,'store'])->name('admin.collect_transaction.store');
Route::post('/collect_transaction/get_account_balance',[CollectController::class,'get_account_balance'])->name('admin.collect_transaction.get_account_balance');
Route::post('/collect_transaction/ajax_search',[CollectController::class,'ajax_search'])->name('admin.collect_transaction.ajax_search');
/*-------------end of collection transaction------------------------ */
/*-------------start of Exchange transaction------------------------ */
Route::get('/exchange_transaction/index',[ExchangeController::class,'index'])->name('admin.exchange_transaction.index');
Route::get('/exchange_transaction/create',[ExchangeController::class,'create'])->name('admin.exchange_transaction.create');
Route::post('/exchange_transaction/store',[ExchangeController::class,'store'])->name('admin.exchange_transaction.store');
Route::post('exchange_transaction/get_account_balance',[ExchangeController::class,'get_account_balance'])->name('admin.exchange_transaction.get_account_balance');
Route::post('exchange_transaction/ajax_search',[ExchangeController::class,'ajax_search'])->name('admin.exchange_transaction.ajax_search');
/*-------------end of Exchange transaction------------------------ */

/*-------------start of sales invoice------------------------ */
Route::get('/sales_invoices/index',[SaleInvoiceController::class,'index'])->name('admin.sales_invoices.index');
Route::get('/sales_invoices/create',[SaleInvoiceController::class,'create'])->name('admin.sales_invoices.create');
Route::post('/sales_invoices/store',[SaleInvoiceController::class,'store'])->name('admin.sales_invoices.store');
Route::get('/sales_invoices/edit/{id}',[SaleInvoiceController::class,'edit'])->name('admin.sales_invoices.edit');
Route::post('/sales_invoices/update/{id}',[SaleInvoiceController::class,'update'])->name('admin.sales_invoices.update');
Route::get('/sales_invoices/delete/{id}',[SaleInvoiceController::class,'delete'])->name('admin.sales_invoices.delete');
Route::post('/sales_invoices/ajax_search/',[SaleInvoiceController::class,'ajax_search'])->name('admin.sales_invoices.ajax_search');
Route::get('/sales_invoices/show/{id}',[SaleInvoiceController::class,'show'])->name('admin.sales_invoices.show');
Route::post('/sales_invoices/get_item_uoms/',[SaleInvoiceController::class,'get_item_uoms'])->name('admin.sales_invoices.get_item_uoms');
Route::post('/sales_invoices/get_item_batches/',[SaleInvoiceController::class,'get_item_batches'])->name('admin.sales_invoices.get_item_batches');
Route::post('/sales_invoices/get_item_unit_price/',[SaleInvoiceController::class,'get_item_unit_price'])->name('admin.sales_invoices.get_item_unit_price');
Route::post('/sales_invoices/load_modal_add_mirror/',[SaleInvoiceController::class,'load_modal_add_mirror'])->name('admin.sales_invoices.load_modal_add_mirror');
Route::post('/sales_invoices/load_modal_add_active/',[SaleInvoiceController::class,'load_modal_add_active'])->name('admin.sales_invoices.load_modal_add_active');
Route::post('/sales_invoices/get_Add_new_item_row/',[SaleInvoiceController::class,'get_Add_new_item_row'])->name('admin.sales_invoices.get_Add_new_item_row');

Route::post('/sales_invoices/load_invoice_update_modal/',[SaleInvoiceController::class,'load_invoice_update_modal'])->name('admin.sales_invoices.load_invoice_update_modal');
Route::post('/sales_invoices/Add_item_to_invoice/',[SaleInvoiceController::class,'Add_item_to_invoice'])->name('admin.sales_invoices.Add_item_to_invoice');
Route::post('/sales_invoices/reload_items_in_invoice/',[SaleInvoiceController::class,'reload_items_in_invoice'])->name('admin.sales_invoices.reload_items_in_invoice');
Route::post('/sales_invoices/recalclate_parent_invoice/',[SaleInvoiceController::class,'recalclate_parent_invoice'])->name('admin.sales_invoices.recalclate_parent_invoice');
Route::post('/sales_invoices/remove_active_row_item/',[SaleInvoiceController::class,'remove_active_row_item'])->name('admin.sales_invoices.remove_active_row_item');
Route::post('/sales_invoices/DoApproveInvoiceFinally/',[SaleInvoiceController::class,'DoApproveInvoiceFinally'])->name('admin.sales_invoices.DoApproveInvoiceFinally');
Route::post('/sales_invoices/load_usershiftDiv/',[SaleInvoiceController::class,'load_usershiftDiv'])->name('admin.sales_invoices.load_usershiftDiv');
Route::post('/sales_invoices/load_invoice_details_modal/',[SaleInvoiceController::class,'load_invoice_details_modal'])->name('admin.sales_invoices.load_invoice_details_modal');
Route::post('/sales_invoices/ajax_search/',[SaleInvoiceController::class,'ajax_search'])->name('admin.sales_invoices.ajax_search');



/*-------------end of sales invoice------------------------ */
/*-------------start of delegates------------------------ */
Route::get('/delegates/index',[DelegatesController::class,'index'])->name('admin.delegates.index');
Route::get('/delegates/create',[DelegatesController::class,'create'])->name('admin.delegates.create');
Route::post('/delegates/store',[DelegatesController::class,'store'])->name('admin.delegates.store');
Route::get('/delegates/edit/{id}',[DelegatesController::class,'edit'])->name('admin.delegates.edit');
Route::post('/delegates/update/{id}',[DelegatesController::class,'update'])->name('admin.delegates.update');
Route::get('/delegates/delete/{id}',[DelegatesController::class,'delete'])->name('admin.delegates.delete');
Route::post('/delegates/ajax_search/',[DelegatesController::class,'ajax_search'])->name('admin.delegates.ajax_search');
Route::post('/delegates/show',[DelegatesController::class,'show'])->name('admin.delegates.show');
/*-------------end of delegates------------------------ */





});


Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>'guest:admin'],function(){
Route::get('login',[LoginController::class,'show_login_view'])->name('admin.showlogin');
Route::post('login',[LoginController::class,'login'])->name('admin.login');

});

