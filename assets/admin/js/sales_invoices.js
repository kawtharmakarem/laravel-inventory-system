$(document).ready(function () {
    $(document).on('change','#item_code', function () {
        get_item_uoms();
        
    });
    $(document).on('change','#sales_item_type', function () {
      get_item_unit_price();
    });
    $(document).on('input','#item_quantity', function () {
      recalculate_itemTotalRow();
    });
    $(document).on('input','#item_price', function () {
      recalculate_itemTotalRow();
    });
    $(document).on('input','#what_paid', function () {
      total_cost=parseFloat($('#total_cost').val());
      treasuries_balance=parseFloat($('#treasuries_balance').val());
      var what_paid=$(this).val();
      if(what_paid==''){what_paid=0;}
      what_paid=parseFloat(what_paid);
      var bill_type=$('#bill_type').val();
      if(bill_type==1){
       if(what_paid<total_cost){
        alert('Sorry ! all amount must be paid !!!');
        $('#what_paid').val(total_cost);
       }
      }else{
       if(what_paid>=total_cost){
        alert('Sorry , you cannot pay all amount in this bill type!!!');
        $('#what_paid').val(0);
       }
      }
      if(what_paid>total_cost){
        alert('Sorry ,the paid amount must not be greater than total cost');
        $('#what_paid').val(0);
      }
      recalcualte();

    });
    $(document).on('input','#discount_percent', function () {
      var discount_percent=$(this).val();
      if(discount_percent==''){
        discount_percent=0;
      }
      var discount_type=$('#discount_type').val();
      if(discount_type==1){
        if(discount_percent>100){
          alert('Sorry ,percentage must be less than 100 !!!');
          $('#discount_percent').val(0);
        }
      }
      recalcualte();
    });
    $(document).on('input','#tax_percent', function () {
      var tax_percent=$('#tax_percent').val();
      if(tax_percent==''){tax_percent=0;}
      if(tax_percent>100){
        alert('Sorry ,percentage must be less than 100 !!!');
        $('#tax_percent').val();
      }
      recalcualte();
    });
    $(document).on('click','#AddItemToIvoiceDetailsRow', function () {
      var store_id=$('#store_id').val();
      if(store_id=='')
      {
        alert('Please select branch ');
        $('#store_id').focus();
        return false;
      }
       var sales_item_type=$('#sales_item_type').val();
       if(sales_item_type==""){
        alert('please select sales_item_type');
        $('#sales_item_type').focus();
        return false;
       }
       var item_code=$('#item_code').val();
       if(item_code==""){
        alert('Please select item');
        $('#item_code').focus();
        return false;
       }
       var uom_id=$('#uom_id').val();
       if(uom_id==''){
        alert('Please select unit');
        $('#uom_id').focus();
        return false;
       }
       var inv_itemcard_batches_autoserial=$('#inv_itemcard_batches_autoserial').val();
       if(inv_itemcard_batches_autoserial==""){
        alert('Please select batch!!!');
        $('#inv_itemcard_batches_autoserial').focus();
        return false;
       }
       var item_quantity=$('#item_quantity').val();
       if(item_quantity==''){
        alert('Please enter quantity !!');
        $('#item_quantity').focus();
        return false;
       }
       var BatchQuantity=$('#inv_itemcard_batches_autoserial option:selected').data('quantity');
       if(parseFloat(item_quantity)>parseFloat(BatchQuantity)){
        alert('Sorry ,required quantity is greater than that in batches!!');
        return false;
       }
       var item_price=$('#item_price').val();
       if(item_price==''){
        alert('Please enter price!!');
        $('#item_price').focus();
        return false;
       }
       var is_normal_orOther=$('#is_normal_orOther').val();
       if(is_normal_orOther==""){
        alert('Please select if normal sales or not !!');
        $('#is_normal_orOther').focus();
        return false;
       }
       var item_total=$('#item_total').val();
       if(item_total==''){
        alert('Please total is required !!!');
        $('#item_total').focus();
        return false;
       }
       var store_name=$('#store_id option:selected').text();
       var uom_id_name=$('#uom_id option:selected').text();
       var item_code_name=$('#item_code option:selected').text();
       var sales_item_type_name=$('#sales_item_type option:selected').text();
       var is_normal_orOther_name=$('#is_normal_orOther option:selected').text();
       var isparentuom=$('#uom_id').data('isparentuom');
       var token_search=$('#token_search').val();
       var url=$('#ajax_get_Add_new_item_row').val();
       $.ajax({
        type: "post",
        url: url,
        data: {'_token':token_search,store_id:store_id,sales_item_type:sales_item_type,item_code:item_code,uom_id:uom_id,
        inv_itemcard_batches_autoserial:inv_itemcard_batches_autoserial,item_quantity:item_quantity,item_price:item_price,is_normal_orOther:is_normal_orOther,
        item_total,store_name:store_name,uom_id_name:uom_id_name,item_code_name:item_code_name,sales_item_type_name:sales_item_type_name,is_normal_orOther_name:is_normal_orOther_name,
        isparentuom:isparentuom },
        dataType: "html",
        success: function (data) {
          $('#itemsrowtableContainerbody').append(data);
          recalcualte();
        }
       });
    });
    $(document).on('change','#bill_type', function () {
      var bill_type=$('#bill_type').val();
      var total_cost=$('#total_cost').val();
      if(bill_type==1){
        //cash
        $('#what_paid').val(total_cost*1);
        $('#what_remain').val(0);
        $('#what_paid').attr('readonly',true);
        recalcualte();
      }else{
        //def
        $('#what_paid').val(0);
        $('#what_remain').val(total_cost*1);
        $('#what_paid').attr('readonly',false);
        recalcualte();
      }
    });
    
$(document).on('change','#discount_type', function () {
 var discount_type=$(this).val();
 if(discount_type==''){
  $('#discount_percent').val(0);
  $('#discount_value').val();
  $('#discount_percent').attr('readonly',true);
 }else{
  $('#discount_percent').attr('readonly',false);
 }
 var discount_percent=$('#discount_percent').val();
 if(discount_percent==''){discount_percent=0;}
 if(discount_type==1){
  if(discount_percent>100){
    alert('Sorry , percentage must be less than 100 !!!');
    $('#discount_percent').val(0);
  }
 }
 recalcualte();

});
 $(document).on('change','#uom_id', function () {
    get_inv_itemcard_batches();
 });
 $(document).on('change','#store_id', function () {
        get_inv_itemcard_batches();

 });
 
 $(document).on('click','#LoadModalAddBtnMirror', function (e) {
    var token_search=$("#token_search").val();
    var url=$("#ajax_load_modal_add_mirror").val();
    $.ajax({
        type: "post",
        url: url,
        data: {'_token':token_search},
        dataType: "html",
        cache:false,
        success: function (data) {
          $('#updateInvoiceModalActiveInvoiceBody').html("");
          $('#updateInvoiceModalActiveInvoice').modal('hide');
            $('#AddNewInvoiceModalMirrorBody').html(data);
            $('#AddNewInvoiceModalMirror').modal('show');
        }
    });
 });
 $(document).on('click','#LoadModalAddBtnActiveInvoice', function () {
  var token_search=$("#token_search").val();
  var url=$('#ajax_load_modal_add_active').val();
  $.ajax({
    type: "post",
    url: url,
    data: {'_token':token_search},
    dataType: "html",
    success: function (data) {
      $('#AddNewInvoiceModalActiveBody').html(data);
      $('#AddNewInvoiceModalActive').modal("show");
    }
  });
  
 }); 
$(document).on('click','.remove_current_row', function (e) {
  e.preventDefault();
  $(this).closest('tr').remove();
  recalcualte();
});

$(document).on('click','#Do_Add_new_active_invoice', function () {
  var invoice_date=$('#invoice_date').val();
  if(invoice_date==''){
    $('#invoice_date').focus();
    return false;
  }
  var sales_material_type_id=$('#sales_material_type_id').val();
  if(sales_material_type_id==''){
    alert('Please select invoice category!!!');
    $('#sales_material_type_id').focus();
    return false;
  }
  var customer_code=$('#customer_code').val();
  var is_has_customer=$('#is_has_customer').val();
  if(is_has_customer==1){
    if(customer_code==''){
      alert('Please select customer!!!');
      $('#customer_code').focus();
      return false;
    }
  }
  var delegate_code=$('#delegate_code').val();
  if(delegate_code==''){
    alert('Please select delegate!!!');
    $('#delegate_code').focus();
  }
  var token=$('#token_search').val();
  var url=$('#ajax_get_store').val();
  $.ajax({
    type: "post",
    url: url,
    cache:false,
    data: {invoice_date:invoice_date,customer_code:customer_code,
      is_has_customer:is_has_customer,delegate_code:delegate_code,sales_material_type_id:sales_material_type_id,'_token':token},
    dataType: "json",
    success: function (auto_serial) {
      load_invoice_update_modal(auto_serial);
    },
    error:function()
    {
      alert('Something went wrong!!!');
    }
  });
});

$(document).on('change','#is_has_customer', function () {
  $('#customer_code').val("");
  if($(this).val()==1){
    $('#customer_codeDiv').show();
  }else{
    $('#customer_codeDiv').hide();
  }
});
$(document).on('click','.load_invoice_update_modal', function () {
  var auto_serial=$(this).data("autoserial");
  load_invoice_update_modal(auto_serial);
});

$(document).on('mouseenter','#AddItemToIvoiceDetailsActive', function () {
  get_inv_itemcard_batches();
});

$(document).on('click','#AddItemToIvoiceDetailsActive', function () {
  var store_id=$('#store_id').val();
  if(store_id==""){
    alert('Please select branch');
    $('#store_id').focus();
    return false;
  }
  var sales_item_type=$('#sales_item_type').val();
  if(sales_item_type==""){
    alert('Please select sales type!!!');
    $('#sales_item_type').focus();
    return false;
  }
  var item_code=$('#item_code').val();
  if(item_code==""){
    alert('Please select Item !!!');
    return false;
  }
  var uom_id=$('#uom_id').val();
  if(uom_id==""){
    alert('Please select sales unit!!!');
    $('#uom_id').focus();
    return false;
  }
  var inv_itemcard_batches_autoserial=$('#inv_itemcard_batches_autoserial').val();
  if(inv_itemcard_batches_autoserial==""){
    alert('Please select the batch!!!');
    $('#inv_itemcard_batches_autoserial').focus();
    return false;
  }
  var item_quantity=$("#item_quantity").val();
  if(item_quantity==""){
    alert('Please enter quantity!!!');
    $('#item_quantity').focus();
    return false;
  }
  var BatchQuantity=$("#inv_itemcard_batches_autoserial option:selected").data("quantity");
  if(parseFloat(item_quantity) > parseFloat(BatchQuantity)){
    alert('Sorry ! the required quantity is greater than the QuantityBatch in the branch!!!');
    return false;
  }
  var item_price=$('#item_price').val();
  if(item_price==""){
    alert('Please enter price!!!');
    $('#item_price').focus();
    return false;
  }
  var is_normal_orOther=$('#is_normal_orOther').val();
  if(is_normal_orOther==""){
    alert("Please select is normal sale?");
    $('#is_normal_orOther').focus();
    return false;
  }
  var item_total=$('#item_total').val();
  if(item_total==""){
    alert('Please total is required!!!');
    $('#item_total').focus();
    return false;
  }
  var isparentuom=$('#uom_id option:selected').data("isparentuom");
  var invoiceautoserial=$('#invoiceautoserial').val();
  var token_search=$('#token_search').val();
  var url=$('#ajax_get_Add_item_to_invoice').val();
  $.ajax({
    type: "post",
    url: url,
    cache:false,
    data: {
      '_token':token_search,
      invoiceautoserial:invoiceautoserial,
      isparentuom:isparentuom,
      item_total:item_total,
      is_normal_orOther:is_normal_orOther,
      item_price:item_price,
      item_quantity:item_quantity,
      inv_itemcard_batches_autoserial:inv_itemcard_batches_autoserial,
      uom_id:uom_id,
      item_code:item_code,
      sales_item_type:sales_item_type,
      store_id:store_id
    },
    cache:false,
    dataType: "json",
    success: function (data) {
      get_inv_itemcard_batches();
      alert('data is added successfully......');
    }
    
  });
});

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

       get_inv_itemcard_batches();


            
            },
            error: function () {
                $("#UomDiv").hide();
    
              alert("Sorry ,Something went wrong!!");
            }
          });
    
        } else {
          $("#UomDiv").html("");
            $("#UomDiv").hide();
            $("#inv_itemcard_batchesDiv").html("");
            $("#inv_itemcard_batchesDiv").hide();
        }
}

function get_inv_itemcard_batches(){
var item_code = $("#item_code").val();
var uom_id = $("#uom_id").val();

var store_id=$("#store_id").val();

if (item_code != "" && uom_id!=""&& store_id!="") {
var token_search = $("#token_search").val();
var url = $("#ajax_get_item_batches").val();
jQuery.ajax({
url: url,
type: 'post',
dataType: 'html',
cache: false,
data: { item_code: item_code,uom_id:uom_id,store_id:store_id, "_token": token_search },
success: function (data) {
$("#inv_itemcard_batchesDiv").html(data);
$("#inv_itemcard_batchesDiv").show();
get_item_unit_price();

},
error: function () {
  $("#inv_itemcard_batchesDiv").hide();


}
});



}else{
$("#UomDiv").hide(); 
$("#inv_itemcard_batchesDiv").hide();

}


}
function recalculate_itemTotalRow(){
var item_quantity=$('#item_quantity').val();
if(item_quantity=='') item_quantity=0;
var item_price=$('#item_price').val();
if(item_price=='') item_price=0;
$('#item_total').val((parseFloat(item_quantity)*parseFloat(item_price))*1); 
}
function get_item_unit_price(){
var item_code=$('#item_code').val();
var uom_id=$('#uom_id').val();
var sales_item_type=$('#sales_item_type').val();
var token=$('#token_search').val();
var url=$('#ajax_get_item_unit_price').val();
$.ajax({
type: "post",
url: url,
data: {item_code:item_code,uom_id:uom_id,sales_item_type:sales_item_type,'_token':token},
cache:false,
dataType: "json",
success: function (data) {
$('#item_price').val(data*1);
recalculate_itemTotalRow();
},
error:function(){
$('#item_price').val("");
alert('Sorry ,Something went wrong!!!');
}
});
}
function recalcualte(){
var total_cost_items=0;

$(".item_total_array").each(function () {
total_cost_items+=parseFloat($(this).val());
});

if(total_cost_items==""){total_cost_items=0;}
total_cost_items=parseFloat(total_cost_items);
$('#total_cost_items').val(total_cost_items);

//tax_percent value
var tax_percent=$('#tax_percent').val();
if(tax_percent==''){tax_percent=0}
tax_percent=parseFloat(tax_percent);

//tax_value
var tax_value=total_cost_items*tax_percent/100;
tax_value=parseFloat(tax_value);
$('#tax_value').val(tax_value*1);

//total_before_discount
var total_before_discount=total_cost_items+tax_value;
$('#total_before_discount').val(total_before_discount);

//discount_type
var discount_type=$('#discount_type').val();
if(discount_type!=''){
if(discount_type==1){
var discount_percent=$('#discount_percent').val();
if(discount_percent==''){discount_percent=0;}
discount_percent=parseFloat(discount_percent);
var discount_value=total_before_discount*discount_percent/100;
$('#discount_value').val(discount_value*1);
var total_cost=total_before_discount-discount_value;
$('#total_cost').val(total_cost*1);
}else{
var discount_percent=$('#discount_percent').val();
if(discount_percent==''){discount_percent=0;}
discount_percent=parseFloat(discount_percent);
$('#discount_value').val(discount_percent*1);
var total_cost=total_before_discount-discount_percent;
$('#total_cost').val(total_cost*1);
}
}else{
$('#discount_value').val(0);
var total_cost=total_before_discount;
$('#total_cost').val(total_cost);
}
var what_paid=$('#what_paid').val();
if(what_paid==''){what_paid=0;}
what_paid=parseFloat(what_paid);
total_cost=parseFloat(total_cost);
var what_remain=total_cost-what_paid;
$('#what_remain').val(what_remain*1);
}
function load_invoice_update_modal(auto_serial){
  var token=$('#token_search').val();
  var url=$('#ajax_get_load_invoice_update_modal').val();
  $.ajax({
    type: "post",
    url: url,
    data: {'_token':token,auto_serial:auto_serial},
    dataType: "html",
    success: function (data) {
      $('#AddNewInvoiceModalActiveBody').html("");
      $('#AddNewInvoiceModalActive').modal("hide");

      $('#updateInvoiceModalActiveInvoiceBody').html(data);
      $('#updateInvoiceModalActiveInvoice').modal('show');
      
    },
    error:function()
    {
      alert('Something went wrong !!!!');
    }
  });
}

});