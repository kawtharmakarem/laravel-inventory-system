$(document).ready(function () {

  $(document).on('change', '#item_code', function (e) {
    //نجلب اولا الوحدات للصنف
    get_item_uoms();

  });

  $(document).on('change', '#sales_item_type', function (e) {
    get_item_unit_price();

  });
  $(document).on('input', '#item_quantity', function (e) {
    recalculate_itemTotlaRow();

  });
  $(document).on('input', '#item_price', function (e) {
    recalculate_itemTotlaRow();

  });

$(document).on('change','invoice_date', function () {
  recalcualte();
});
$(document).on('change','sales_material_type_id', function () {
  recalcualte();
});
$(document).on('change','is_has_customer', function () {
  recalcualte();
});
$(document).on('change','customer_code', function () {
  recalcualte();
});
$(document).on('change','delegate_code', function () {
  recalcualte();
});

  $(document).on('change', '#uom_id', function (e) {
    get_inv_itemcard_batches();
  });

  $(document).on('change', '#store_id', function (e) {
    get_inv_itemcard_batches();
  });


  $(document).on('click', '#LoadModalAddBtnMirror', function (e) {
    var token_search = $("#token_search").val();
    var url = $("#ajax_load_modal_add_mirror").val();
    jQuery.ajax({
      url: url,
      type: 'post',
      dataType: 'html',
      cache: false,
      data: { "_token": token_search },
      success: function (data) {

        $("#updateInvoiceModalActiveInvoiceBody").html("");
        $("#updateInvoiceModalActiveInvoice").modal("hide");

        $("#AddNewInvoiceModalMirrorBody").html(data);
        $("#AddNewInvoiceModalMirror").modal("show");

      },
      error: function () {
        alert("حدث خطاما");
      }
    });
  });


  $(document).on('click', '#LoadModalAddBtnActive', function (e) {
    var token = $("#token_search").val();
    var url = $("#ajax_load_modal_add_active").val();
    jQuery.ajax({
      url: url,
      type: 'post',
      dataType: 'html',
      cache: false,
      data: { "_token": token },
      success: function (data) {
        $("#AddNewInvoiceModalActiveBody").html(data);
        $("#AddNewInvoiceModalActive").modal("show");
      },
      error: function () {
        alert("حدث خطاما");
      }
    });
  });

  $(document).on('click','.remove_active_row_item', function () {
    var url=$("#ajax_get_remove_active_row_item").val();
    var auto_serial=$('#invoiceautoserial').val();
    var token=$('#token_search').val();
    var id=$(this).data("id");
    $.ajax({
      type: "post",
      url: url,
      data: {"_token":token,auto_serial:auto_serial,id:id},
      dataType: "html",
      success: function (data) {
        reload_items_in_invoice();
        recalcualte();
      }
    });
  });

  function recalculate_itemTotlaRow() {
    var item_quantity = $("#item_quantity").val();
    if (item_quantity == "") item_quantity = 0;
    var item_price = $("#item_price").val();
    if (item_price == "") item_price = 0;
    $("#item_total").val((parseFloat(item_quantity) * parseFloat(item_price)) * 1);

  }

  $(document).on('click', '#AddItemToIvoiceDetailsRow', function (e) {
    var store_id = $("#store_id").val();
    if (store_id == "") {
      alert("Please select branch!!!");
      $("#store_id").focus();
      return false;
    }

    var sales_item_type = $("#sales_item_type").val();
    if (sales_item_type == "") {
      alert("Please select sales item type");
      $("#sales_item_type").focus();
      return false;
    }

    var item_code = $("#item_code").val();
    if (item_code == "") {
      alert("Please select item !!!");
      $("#item_code").focus();
      return false;
    }

    var uom_id = $("#uom_id").val();
    if (uom_id == "") {
      alert("Please select unit!!");
      $("#uom_id").focus();
      return false;
    }
    var inv_itemcard_batches_autoserial = $("#inv_itemcard_batches_autoserial").val();
    if (inv_itemcard_batches_autoserial == "") {
      alert("Please select batch!!");
      $("#inv_itemcard_batches_autoserial").focus();
      return false;
    }
    var item_quantity = $("#item_quantity").val();
    if (item_quantity == "") {
      alert("Please enter quantity!!!");
      $("#item_quantity").focus();
      return false;
    }
    var BatchQuantity=$("#inv_itemcard_batches_autoserial option:selected").data("qunatity");
 
    if (parseFloat(item_quantity) > parseFloat(BatchQuantity)) {
      alert("Sorry ! required quantity is greater than that in the batch!!!");
      return false;
    }
    var item_price = $("#item_price").val();
    if (item_price == "") {
      alert("Please enter price!!");
      $("#item_price").focus();
      return false;
    }

    var is_normal_orOther = $("#is_normal_orOther").val();
    if (is_normal_orOther == "") {
      alert("Please select is normal sale?");
      $("#is_normal_orOther").focus();
      return false;
    }

    var item_total = $("#item_total").val();
    if (item_total == "") {
      alert("Please enter total !!!");
      $("#item_total").focus();
      return false;
    }

    var store_name = $("#store_id option:selected").text();
    var uom_id_name = $("#uom_id option:selected").text();
    var item_code_name = $("#item_code option:selected").text();
    var sales_item_type_name = $("#sales_item_type option:selected").text();
    var is_normal_orOther_name = $("#is_normal_orOther option:selected").text();
    var isparentuom = $("#uom_id option:selected").data("isparentuom");

    var token_search = $("#token_search").val();
    var url = $("#ajax_get_Add_new_item_row").val();
    jQuery.ajax({
      url: url,
      type: 'post',
      dataType: 'html',
      cache: false,
      data: {
        "_token": token_search, store_id: store_id, sales_item_type: sales_item_type, item_code: item_code,
        uom_id: uom_id, inv_itemcard_batches_autoserial: inv_itemcard_batches_autoserial, item_quantity: item_quantity, item_price: item_price,
        is_normal_orOther: is_normal_orOther, item_total: item_total, store_name: store_name, uom_id_name: uom_id_name,
        sales_item_type_name: sales_item_type_name, is_normal_orOther_name: is_normal_orOther_name, isparentuom: isparentuom, item_code_name: item_code_name
      },
      success: function (data) {

        $("#itemsrowtableContainerbody").append(data);

        recalcualte();
      },
      error: function () {


        // alert("Sorry! Something went wrong");
      }
    });

  });


  $(document).on('click', '.remove_current_row', function (e) {
 e.preventDefault();
 $(this).closest('tr').remove();
 recalcualte();
  
});

$(document).on('change', '#notes', function (e) {
  recalcualte();
});
$(document).on('change', '#pill_type', function (e) {
  var pill_type = $("#pill_type").val();
  var total_cost = $("#total_cost").val();

  if (pill_type == 1) {
    //cash
    $("#what_paid").val(total_cost * 1);
    $("#what_remain").val(0);
    $("#what_paid").attr("readonly", true);
    
    recalcualte();

  } else {
    //agel
    $("#what_paid").val(0);
    $("#what_remain").val(total_cost * 1);
    $("#what_paid").attr("readonly", false);
    recalcualte();
  }

});

$(document).on('input', '#what_paid', function (e) {

  total_cost = parseFloat($("#total_cost").val());
  treasuries_balance = parseFloat($("#treasuries_balance").val());

  total_cost = parseFloat(total_cost);


  what_paid = $(this).val();
  if (what_paid == "") { what_paid = 0; }
  what_paid = parseFloat(what_paid);
  var pill_type = $("#pill_type").val();
  if (pill_type == 1) {
    //cash
    if (what_paid < total_cost) {
      alert("عفوا يجب ان يكون المبلغ كاملا مدفوع في حالة ان الفاتورة كاش");
      $("#what_paid").val(total_cost);
    }


  } else {
    if (what_paid >= total_cost) {
      alert("عفوا يجب ان لايكون كل المبلغ  مدفوع في حالة ان الفاتورة اجل");
      $("#what_paid").val(0);
    }
  }



  if (what_paid > total_cost) {
    alert("عفوا لايمكن ان يكون المبلغ المدفوع اكبر من  اجمالي الفاتورة");
    $("#what_paid").val(0);
  }
  



  recalcualte();
});


$(document).on('change', '#discount_type', function (e) {
  discount_type = $(this).val();
  if (discount_type == "") {
    $("#discount_percent").val(0);
    $("#discount_value").val(0);

    $("#discount_percent").attr("readonly", true);


  } else {
    $("#discount_percent").attr("readonly", false);

  }
  var discount_percent = $("#discount_percent").val();
  if (discount_percent == "") { discount_percent = 0; }

  if (discount_type == 1) {
    if (discount_percent > 100) {
      alert("عفوا لايمكن ان يكون نسبة الخصم المئوية اكبر من 100 % !!!");
      $("#discount_percent").val(0);
    }

  }


  recalcualte();
});

$(document).on('input', '#discount_percent', function (e) {
  var discount_percent = $(this).val();
  if (discount_percent == "") { discount_percent = 0; }
  var discount_type = $("#discount_type").val();
  if (discount_type == 1) {
    if (discount_percent > 100) {
      alert("عفوا لايمكن ان يكون نسبة الخصم المئوية اكبر من 100 % !!!");
      $("#discount_percent").val(0);
    }

  }

  recalcualte();
});
$(document).on('input', '#tax_percent', function (e) {
  var tax_percent = $(this).val();
  if (tax_percent == "") { tax_percent = 0; }
  if (tax_percent > 100) {
    alert("عفوا لايمكن ان يكون نسبة الضريبة اكبر من 100 % !!!");
    $("#tax_percent").val(0);
  }
  recalcualte();
});
$(document).on('click', '#Do_Add_new_active_invoice', function (e) {
var invoice_date=$("#invoice_date_activeAdd").val();

if(invoice_date==""){
  alert("من فضلك ادخل تاريخ الفاتورة");
  $("#invoice_date_activeAdd").focus();
  return false;
}
var sales_material_type_id=$("#sales_material_type_id_activeAdd").val();
if(sales_material_type_id==""){
  alert("من فضلك اختر فئة الفاتورة");
  $("#sales_material_type_id_activeAdd").focus();
  return false;
}


var customer_code=$("#customer_code_activeAdd").val();

var is_has_customer=$("#is_has_customer_activeAdd").val();
if(is_has_customer==1){
if(customer_code==""){
  alert("من فضلك  اختر العميل");
  $("#customer_code_activeAdd").focus();
  return false;

}
}
var delegate_code=$("#delegate_code_activeAdd").val();
if(delegate_code==""){
  alert("من فضلك  اختر المندوب ");
  $("#delegate_code_activeAdd").focus();
  return false;
}
var bill_type=$('#bill_type_activeAdd').val();

  var token = $("#token_search").val();
    var url = $("#ajax_get_store").val();
    jQuery.ajax({
      url: url,
      type: 'post',
      dataType: 'json',
      cache: false,
      data: {invoice_date:invoice_date,
        customer_code:customer_code,
      is_has_customer:is_has_customer,
      delegate_code:delegate_code,
      bill_type:bill_type,
      sales_material_type_id:sales_material_type_id,
      "_token": token},
      success: function (auto_serial) {
        load_invoice_update_modal(auto_serial);

      },
      
    });

});

$(document).on('change', '#is_has_customer', function (e) {

  $("#customer_code").val("");
if($(this).val()==1){

  $("#customer_codeDiv").show();
}else{
  $("#customer_codeDiv").hide();
  

}

});

$(document).on('change','#is_has_customer_activeAdd', function () {
  $('#customer_code_activeAdd').val("");
  if($(this).val()==1){
    $('#customer_codeDiv').show();
  }else{
    $('#customer_codeDiv').hide();
  }
});

function load_invoice_update_modal(auto_serial){
  var token = $("#token_search").val();
  var url = $("#ajax_get_load_invoice_update_modal").val();
  jQuery.ajax({
    url: url,
    type: 'post',
    dataType: 'html',
    cache: false,
    data: { "_token": token,auto_serial:auto_serial },
    success: function (data) {
      $("#AddNewInvoiceModalActiveInvoiceBody").html("");
      $("#AddNewInvoiceModalActiveInvoice").modal("hide");

      $("#updateInvoiceModalActiveInvoiceBody").html(data);
      $("#updateInvoiceModalActiveInvoice").modal("show");
    },
    error: function () {
      alert("حدث خطاما");
    }
  });
}
$(document).on('click', '.load_invoice_update_modal', function (e) {
  var auto_serial=$(this).data("autoserial");
  load_invoice_update_modal(auto_serial);
});


$(document).on('mouseenter', '#AddItemToIvoiceDetailsActive', function (e) {
  if($('#inv_itemcard_batches_autoserial').length){
    var batchSerial=$('#inv_itemcard_batches_autoserial').val();
  }else{
    var batchSerial=null;
  }
get_inv_itemcard_batches(batchSerial);
});




$(document).on('click', '#AddItemToIvoiceDetailsActive', function (e) {
 
var store_id = $("#store_id").val();
  if (store_id == "") {
    alert("Please select branch");
    $("#store_id").focus();
    return false;
  }

  var sales_item_type = $("#sales_item_type").val();
  if (sales_item_type == "") {
    alert("Please select sales item type");
    $("#sales_item_type").focus();
    return false;
  }

  var item_code = $("#item_code").val();
  if (item_code == "") {
    alert("Please select item");
    $("#item_code").focus();
    return false;
  }

  var uom_id = $("#uom_id").val();
  if (uom_id == "") {
    alert("Please select unit ");
    $("#uom_id").focus();
    return false;
  }
  var inv_itemcard_batches_autoserial = $("#inv_itemcard_batches_autoserial").val();
  if (inv_itemcard_batches_autoserial == "") {
    alert("Please select batch");
    $("#inv_itemcard_batches_autoserial").focus();
    return false;
  }
  var item_quantity = $("#item_quantity").val();
  if (item_quantity == "") {
    alert("Please enter quantity ");
    $("#item_quantity").focus();
    return false;
  }
  var BatchQuantity=$("#inv_itemcard_batches_autoserial option:selected").data("qunatity");
 
  if (parseFloat(item_quantity) > parseFloat(BatchQuantity)) {
    alert("Sorry !required quantity is greater than that one in the branch");
    return false;
  }
  var item_price = $("#item_price").val();
  if (item_price == "") {
    alert("من فضلك ادخل  السعر ");
    $("#item_price").focus();
    return false;
  }

  var is_normal_orOther = $("#is_normal_orOther").val();
  if (is_normal_orOther == "") {
    alert("Please select if normal sales");
    $("#is_normal_orOther").focus();
    return false;
  }

  var item_total = $("#item_total").val();
  if (item_total == "") {
    alert("Please enter total");
    $("#item_total").focus();
    return false;
  }

  var invoice_date=$('#invoice_date').val();
  var sales_material_type_id=$('#sales_material_type_id').val();
  if(sales_material_type_id==""){
    alert('Please select material type!!!');
    $('#sales_material_type_id').focus();
    return false;
  }
  var is_has_customer=$('#is_has_customer').val();
  if(is_has_customer==1){
    var customer_code=$('#customer_code').val();
    if(customer_code==""){
      alert('Please select customer!!!');
      $('#customer_code').focus();
      return false;
    }
  }
  var delegate_code=$('#delegate_code').val();
  if(delegate_code==""){
    alert('Please select delegate!!!');
    $('#delegate_code').focus();
    return false;
  }

  var isparentuom = $("#uom_id option:selected").data("isparentuom");
  var invoiceautoserial=$("#invoiceautoserial").val();
  var token_search = $("#token_search").val();
  var url = $("#ajax_get_Add_item_to_invoice").val();
  jQuery.ajax({
    url: url,
    type: 'post',
    dataType: 'json',
    cache: false,
    data: {
      "_token": token_search, store_id: store_id,
       sales_item_type: sales_item_type, item_code: item_code,
      uom_id: uom_id, inv_itemcard_batches_autoserial: inv_itemcard_batches_autoserial,
       item_quantity: item_quantity, 
      item_price: item_price,
      is_normal_orOther: is_normal_orOther,
       item_total: item_total, 
       isparentuom: isparentuom,
      invoiceautoserial:invoiceautoserial,
      
    },
    success: function (data) {
   reload_items_in_invoice();
  //  recalcualte();

    },
    error: function () {


      alert("حدث خطاما");
    }
  });

});


$(document).on('click','#DoApproveInvoiceFinally', function () {
  var sales_material_type_id=$('#sales_material_type_id').val();
  if(sales_material_type_id==""){
    alert("Please select material type");
    $('#sales_material_type_id').focus();
    return false;
  }

  var is_has_customer=$('#is_has_customer').val();
  if(is_has_customer==1){
    var customer_code=$('#customer_code').val();
    if(customer_code==""){
      alert("Please select customer");
      $('#customer_code').focus();
      return false;
    }

  }

  var delegate_code=$('#delegate_code').val();
  if(delegate_code==""){
    alert("Please select delegate");
    $('#delegate_code').focus();
    return false;
  }

if(!$('.item_total_array').length){
  alert('You have to add one item at least to the bill');
  return false;
}
var tax_percent=$('#tax_percent').val();
if(tax_percent==""){
  alert("Please enter tax percent");
  return false;
}
var tax_value=$('#tax_value').val();
if(tax_value==""){
  alert('Please enter tax value');
  return false;
}
var total_before_discount=$('#total_before_discount').val();
if(total_before_discount==""){
  alert('Please total before discount is required');
  return false;
}
var discount_type=$('#discount_type').val();
if(discount_type==1){
  var discount_percent=$('#discount_percent').val();
  if(discount_percent>100){
    alert("discount percent must be less than 100");
    $('#discount_percent').focus();
    return false;
  }
}else if(discount_type==2){
  var discount_value=$('#discount_value').val();
  if(discount_value>total_before_discount){
    alert("discount value must be less than total before discount");
    $('#discount_value').focus();
    return false;
  }
}else{
  var discount_value=$('#discount_value').val();
  if(discount_value>0){
    alert("sorry ! no discount");
    $('#discount_value').focus();
    return false;
  }
}
var discount_value=$('#discount_value').val();
if(discount_value==""){
  alert("discount value is required");
  return false;
}
var total_cost=$('#total_cost').val();
if(total_cost==""){
  alert("total cost is required");
  return false;
}
var treasuries_balance=$('#treasuries_balance').val();

var bill_type=$('#bill_type').val();
if(bill_type==""){
  alert("Please select bill type");
  return false;
}
var what_paid=$('#what_paid').val();
var what_remain=$('#what_remain').val();

if(what_paid==""){
  alert("Please enter paid amount");
  return false;

}
if(what_paid>total_cost){
  alert("paid amount must not be greater than total cost");
  return false;
}
if(bill_type==1){
  if(parseFloat(what_paid)<parseFloat(total_cost)){
    alert("amount must be paid all");
    return false;
  }
}else{
  if(parseFloat(what_paid)==parseFloat(total_cost)){
    alert("amount must not paid all");
    return false;
  }
}
var what_remain=$('#what_remain').val();
if(what_remain==""){
  alert("please enter remain amount");
  return false;
}
if(bill_type==1){
  if(what_remain>0){
    alert("Sorry remain amount must not be greater than zero");
    return false;
  }
}
if(what_paid>0){
  var treasuries_id=$('#treasuries_id').val();
   if(treasuries_id==""){
    alert("Please enter treasury");
    return false
   }
}
var token=$('#token_search').val();
var url=$('#ajax_DoApproveInvoiceFinally').val();
var auto_serial=$('#invoiceautoserial').val();
var treasuries_id=$('#invoiceautoserial').val();
$.ajax({
  type: "post",
  url: url,
  data: {'_token':token,auto_serial:auto_serial,treasuries_id:treasuries_id,what_paid:what_paid,what_remain:what_remain},
  dataType: "json",
  success: function (data) {
    location.reload();
  },error:function(){
  }
});
});


$(document).on('mouseenter','#DoApproveInvoiceFinally', function () {
  var token_search=$('#token_search').val();
  var ajax_load_usershiftDiv=$('#ajax_load_usershiftDiv').val();
  $.ajax({
    type: "post",
    url: ajax_load_usershiftDiv,
    data: {'_token':token_search},
    dataType: "html",
    success: function (data) {
      $('#shiftDiv').html(data);
    }
  });

});

$(document).on('click','.load_invoice_details_modal', function () {
  var token=$('#token_search').val();
  var url=$('#ajax_load_invoice_details_modal').val();
  var auto_serial=$(this).data("autoserial");
  $.ajax({
    type: "post",
    url: url,
    data: {'_token':token,'auto_serial':auto_serial},
    dataType: "html",
    success: function (data) {
      $('#InvoiceModalActiveDetailsBody').html(data);
      $('#InvoiceModalActiveDetails').modal("show");
    },error:function(){
      alert('Sorry!Something went wrong');
    }
  });
  
});

function reload_items_in_invoice(){
  var token = $("#token_search").val();
  var url = $("#ajax_get_reload_items_in_invoice").val();
  var auto_serial = $("#invoiceautoserial").val();

  jQuery.ajax({
    url: url,
    type: 'post',
    dataType: 'html',
    cache: false,
    data: { "_token": token,auto_serial:auto_serial },
    success: function (data) {
      $("#activeItemisInInvoiceDiv").html(data);
      recalcualte();
    },
    error: function () {
      alert("حدث خطاما");
    }
  });

}

$(document).on('change','#bill_type', function () {
  var bill_type=$('#bill_type').val();
  var total_cost=$('#total_cost').val();
  if(bill_type==1){
    //cash
    $('#what_paid').val(total_cost*1);
    $('#what_remain').val(0);
    $('#what_paid').attr("readonly",true);
    recalcualte();
  }else{
    $('#what_paid').val(0);
    $('#what_remain').val(total_cost*1);
    $('#what_paid').attr("readonly",false);
    recalcualte();
  }
});

$('input[type=radio][name=searchbyradio]').change(function (e) { 
make_search();  
});
$(document).on('input','#search_by_text', function () {
  make_search();
});
$(document).on('change','#customer_code_search', function () {
  make_search();
});
$(document).on('change','#delegates_code_search', function () {
  make_search();
});
$(document).on('change','#sales_material_types_search', function () {
  make_search();
});
$(document).on('change','#bill_type_search', function () {
  make_search();
});
$(document).on('change','#discount_type_search', function () {
  make_search();
});
$(document).on('change','#is_approved_search', function () {
  make_search();
});
$(document).on('change','#invoice_date_from', function () {
  make_search();
});
$(document).on('change','#invoice_date_to', function () {
  
});


function make_search(){
  var token=$('#token_search').val();
  var customer_code=$('#customer_code_search').val();
  var delegates_code=$('#delegates_code_search').val();
  var sales_material_types=$('#sales_material_types_search').val();
  var bill_type=$('#bill_type_search').val();
  var discount_type=$('#discount_type_search').val();
  var is_approved=$('#is_approved_search').val();
  var invoice_date_from=$('#invoice_date_from').val();
  var invoice_date_to=$('#invoice_date_to').val();
  var search_by_text=$('#search_by_text').val();
  var seachbyradio=$('input[type=radio][name=searchbyradio]:checked').val();
  var url=$('#ajax_ajax_search').val();
  $.ajax({
    type: "post",
    url: url,
    data: {'_token':token,customer_code:customer_code,delegates_code:delegates_code,
    sales_material_types:sales_material_types,bill_type:bill_type,discount_type:discount_type,
    is_approved:is_approved,invoice_date_from:invoice_date_from,invoice_date_to:invoice_date_to,
    search_by_text:search_by_text,seachbyradio:seachbyradio
  },
    dataType: "html",
    success: function (data) {
      $('#ajax_response_searchdiv').html(data);
    },error:function(){
      alert('sorry!something went wrong');
    }
  });
}
function recalcualte() {
  var total_cost_items = 0;
  $(".item_total_array").each(function(){
    total_cost_items+=parseFloat($(this).val());
  });
 
  if (total_cost_items == "") { total_cost_items = 0; }
  total_cost_items = parseFloat(total_cost_items);
  $("#total_cost_items").val(total_cost_items);
  var tax_percent = $("#tax_percent").val();
  if (tax_percent == "") { tax_percent = 0 };
  tax_percent = parseFloat(tax_percent);

  var tax_value = total_cost_items * tax_percent / 100;
  tax_value = parseFloat(tax_value);
  $("#tax_value").val(tax_value * 1);
  var total_before_discount = total_cost_items + tax_value;
  $("#total_before_discount").val(total_before_discount);
  var discount_type = $("#discount_type").val();
  if (discount_type != "") {
    if (discount_type == 1) {
      var discount_percent = $("#discount_percent").val();
      if (discount_percent == "") { discount_percent = 0; }
      discount_percent = parseFloat(discount_percent);
      var discount_value = total_before_discount * discount_percent / 100;
      $("#discount_value").val(discount_value * 1);
      var total_cost = total_before_discount - discount_value;
      $("#total_cost").val(total_cost * 1);

    } else {
      var discount_percent = $("#discount_percent").val();
      if (discount_percent == "") { discount_percent = 0; }
      discount_percent = parseFloat(discount_percent);
      $("#discount_value").val(discount_percent * 1);
      var total_cost = total_before_discount - discount_percent;
      $("#total_cost").val(total_cost * 1);
    }



  } else {
    $("#discount_value").val(0);
    var total_cost = total_before_discount;
    $("#total_cost").val(total_cost);

  }
  what_paid = $("#what_paid").val();
  if (what_paid == "") what_paid = 0;
  what_paid = parseFloat(what_paid);
  total_cost = parseFloat(total_cost);
  $what_remain = total_cost - what_paid;
  $("#what_remain").val($what_remain * 1);
  
  var bill_type=$('#bill_type').val();
  if(bill_type==1){
    $('#what_paid').val(total_cost);
    $('#what_remain').val(0);
  }
 

  var token = $("#token_search").val();
  var url = $("#ajax_get_recalclate_parent_invoice").val();
  var auto_serial = $("#invoiceautoserial").val();
   var total_cost_items=$("#total_cost_items").val();
   var tax_percent=$("#tax_percent").val();
   var tax_value=$("#tax_value").val();
   var total_before_discount=$("#total_before_discount").val();
   var discount_type=$("#discount_type").val();
   var discount_percent=$("#discount_percent").val();
   var discount_value=$("#discount_value").val();
   var total_cost=$("#total_cost").val();
   var notes=$("#notes").val();
   var invoice_date=$('#invoice_date').val();
   var is_has_customer=$('#is_has_customer').val();
   var customer_code=$('#customer_code').val();
   var delegate_code=$('#delegate_code').val();
   var sales_material_type_id=$('#sales_material_type_id').val();
  jQuery.ajax({
    url: url,
    type: 'post',
    dataType: 'json',
    cache: false,
    data: { "_token": token,auto_serial:auto_serial,
    total_cost_items:total_cost_items,tax_percent:tax_percent,tax_value:tax_value,total_before_discount:total_before_discount,
    discount_type:discount_type,discount_percent:discount_percent,discount_value:discount_value,total_cost:total_cost,
    notes:notes,invoice_date:invoice_date,is_has_customer:is_has_customer,customer_code:customer_code,delegate_code:delegate_code,
    sales_material_type_id:sales_material_type_id,bill_type:bill_type
  },
    success: function (data) {
 
   
    },
    error: function () {
      alert("حدث خطاما");
    }
  });

 


  




}


function get_item_unit_price() {
  var item_code = $("#item_code").val();
  var uom_id = $("#uom_id").val();
  var sales_item_type = $("#sales_item_type").val();
  var token = $("#token_search").val();
  var url = $("#ajax_get_item_unit_price").val();
  jQuery.ajax({
    url: url,
    type: 'post',
    dataType: 'json',
    cache: false,
    data: { item_code: item_code, uom_id: uom_id, sales_item_type: sales_item_type, "_token": token },
    success: function (data) {
      $("#item_price").val(data * 1);
      recalculate_itemTotlaRow();
    },
    error: function () {
      $("#item_price").val("");

  
    }
  });

}


function get_item_uoms() {
  var item_code = $("#item_code").val();
  if (item_code != "") {
    var token_search = $("#token_search").val();
    var ajax_get_item_uoms_url = $("#ajax_get_item_uoms").val();
    jQuery.ajax({
      url: ajax_get_item_uoms_url,
      type: 'post',
      dataType: 'html',
      cache: false,
      data: { item_code: item_code, "_token": token_search },
      success: function (data) {
        $("#UomDiv").html(data);
        $("#UomDiv").show();
        //ثانيا  الكميات بالباتشات للصنف

        get_inv_itemcard_batches();



      },
      error: function () {
        $("#UomDiv").hide();

        alert("حدث خطاما");
      }
    });

  } else {
    $("#UomDiv").html("");
    $("#UomDiv").hide();
    $("#inv_itemcard_batchesDiv").html("");
    $("#inv_itemcard_batchesDiv").hide();
  }
}

//جلب كميات الصنف من المخزن بالباتشات وترتيبهم حسب نوع الصنف
function get_inv_itemcard_batches(oldBatchId=null) {
  var item_code = $("#item_code").val();
  var uom_id = $("#uom_id").val();

  var store_id = $("#store_id").val();

  if (item_code != "" && uom_id != "" && store_id != "") {
    var token_search = $("#token_search").val();
    var url = $("#ajax_get_item_batches").val();
    jQuery.ajax({
      url: url,
      type: 'post',
      dataType: 'html',
      cache: false,
      data: { item_code: item_code, uom_id: uom_id, store_id: store_id, "_token": token_search },
      success: function (data) {
        $("#inv_itemcard_batchesDiv").html(data);
        $("#inv_itemcard_batchesDiv").show();
        if(oldBatchId!=null){
          $("#inv_itemcard_batches_autoserial").val(oldBatchId);
        }
        get_item_unit_price();

      },
      error: function () {
        $("#inv_itemcard_batchesDiv").hide();


      }
    });



  } else {
    $("#UomDiv").hide();
    $("#inv_itemcard_batchesDiv").hide();

  }


}



});
