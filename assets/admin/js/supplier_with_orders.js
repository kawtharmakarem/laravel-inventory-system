$(document).ready(function () {

  $(document).on('change','#item_code_add', function (e) {
    var item_code=$(this).val();
    if(item_code!='')
    {
      var token_search=$('#token_search').val();
      var ajax_get_item_uoms_url=$('#ajax_get_item_uoms_url').val();
      $.ajax({
        type: "post",
        url: ajax_get_item_uoms_url,
        cache:false,
        data: {item_code:item_code,'_token':token_search},
        dataType: "html",
        success: function (data) {
          $('#UomDivAdd').html(data);
          var type=$('#item_code_add').children('option:selected').data("type");

          $('.related_to_itemCard').show();
          if(type==2){
          $('.related_to_date').show();
          }else{
          $('.related_to_date').hide();
          }
          
        },
        error:function(){
          $('.related_to_itemCard').hide();
          $('#UomDivAdd').html('');
          $('.related_to_date').hide();
        }
      });
    }else{
      $('#UomDivAdd').html('');
      $('.related_to_itemCard').hide();
      $('.related_to_date').hide();


    }
    
  });

  $(document).on('input','quantity_add', function () {
    recalculate_Add();
  });
  $(document).on('input','#price_add', function () {
    recalculate_Add();
  });
   

  $(document).on('click','#add_to_bill', function () {
    var item_code_add=$('#item_code_add').val();
    if(item_code_add==''){
      alert('Please select item');
      $('#item_code_add').focus();
      return false;
    }
    var uom_id_Add=$('#uom_id_Add').val();
    if(uom_id_Add==''){
      alert('Please select the unit .');
      $('#uom_id_Add').focus();
      return false;
    }

    var isparentuom = $("#uom_id_Add").children('option:selected').data("isparentuom");

  var quantity_add=$('#quantity_add').val();
  if(quantity_add=='' || quantity_add==0 ){
    alert('Please enter received quantity');
    $('#quantity_add').focus();
    return false;
  }
  var price_add=$('#price_add').val();
  if(price_add==''){
    alert('Please enter price');
    $('#price_add').focus();
    return false;
  }
  var type=$('#item_code_add').children('option:selected').data("type");
  if(type==2){
    var production_date=$('#production_date').val();
    if(production_date==''){
      alert('please enter production or start date');
      $('#production_date').focus();
      return false;
    }
    var expire_date=$('#expire_date').val();
    if(expire_date==''){
      alert('Please enter expire date');
      $('#expire_date').focus();
      return false;
    }
    if(expire_date<production_date){
     alert('Expire date must be bigger than production date .');
     $('#expire_date').focus();
     return false;
    }else
    {
     var production_date=$('#production_date').val();
     var expire_date=$('#expire_date').val();
    }
  }else
  {
   var production_date=$('#production_date').val();
   var expire_date=$('#expire_date').val();
  }
    var total_add=$('#total_add').val();
    if(total_add==''){
      alert('Please enter total.');
      $('#total_add').focus();
      return false;
    }

    var autoserialparent=$('#autoserialparent').val();
    var token_search=$('#token_search').val();
    var ajax_add_new_details=$('#ajax_add_new_details').val();
    $.ajax({
      type: "post",
      url: ajax_add_new_details,
      data: {autoserialparent:autoserialparent,'_token':token_search,item_code_add:item_code_add,uom_id_Add:uom_id_Add,
      isparentuom:isparentuom,quantity_add:quantity_add,price_add:price_add,production_date:production_date,expire_date:expire_date,total_add:total_add,type:type},
      dataType: "json",
      cache:false,
      success: function (data) {
        alert('Added done successfully');
        reload_parent_bill();
        reload_itemsdetails();

        
      }
    });
  });

  function recalculate_Add()
  {
    var quantity_add=$('#quantity_add').val();
    var price_add=$('#price_add').val();
    if(price_add=='') price_add=0;
    if(quantity_add=='') quantity_add=0;

    $('#total_add').val(parseFloat(quantity_add)*parseFloat(price_add));

  }
  function reload_itemsdetails()
  {
    var autoserialparent=$('#autoserialparent').val();
    var token_search=$('#token_search').val();
    var ajax_search_url=$('#ajax_reload_itemsdetails').val();
    $.ajax({
      type: "post",
      url:ajax_search_url ,
      data: {autoserialparent:autoserialparent,'_token':token_search},
      dataType: "html",
      cache:false,
      success: function (data) {
        $('#ajax_response_searchDivDetails').html(data);
      }
    });
  }
  function reload_parent_bill()
  {
    var autoserialparent=$('#autoserialparent').val();
    var token_search=$('#token_search').val();
    var ajax_search_url=$('#ajax_reload_parent_bill').val();
    $.ajax({
      type: "post",
      cache:false,
      url: ajax_search_url,
      data: {autoserialparent:autoserialparent,'_token':token_search},
      dataType: "html",
      success: function (data) {
        $('#ajax_response_searchDivParentBill').html(data);

      }
    });

  }

  $(document).on('click','.load_edit_item_details', function () {
    var id=$(this).data(id); //id for child
    var autoserialparent=$('#autoserialparent').val();
    var token_search=$('#token_search').val();
    var ajax_load_edit_item_details=$('#ajax_load_edit_item_details').val();
    $.ajax({
      type: "post",
      url:ajax_load_edit_item_details ,
      data: {autoserialparent:autoserialparent,id:id,'_token':token_search},
      dataType: "html",
      cache:false,
      success: function (data) {
        $('#edit_item_modal_body').html(data);
        $('#edit_item_modal').modal('show');
        $('#add_item_modal_body').html("");
        $('#add_item_modal').modal('hide');


      }
    });
    
  });
   
  $(document).on('click','#EditDetailsItem', function () {
    var id=$(this).data('id');
    var item_code_add=$('#item_code_add').val();
    if(item_code_add=='')
    {
      alert('Please select an item');
      $('#item_code_add').focus();
      return false;
    }
    var uom_id_Add=$('#uom_id_Add').val();
    if(uom_id_Add=='')
    {
      alert('Please select unit');
      $('#uom_id_Add').focus();
      return false;
    }
    var isparentuom=$('#uom_id_Add').children('option:selected').data('isparentuom');
   var quantity_add=$('#quantity_add').val();
   if(quantity_add=='' || quantity_add==0)
   {
    alert('Please enter required quantity !!');
    $('#quantity_add').focus();
    return false;
   }
   var price_add=$('#price_add').val();
   if(price_add=='')
   {
    alert('Please enter price !!');
    $('#price_add').focus();
    return false;
   }
   var type=$('#item_code_add').children('option:selected').data('type');
   if(type==2)
   {
    var production_date=$('#production_date').val();
    if(production_date=='')
    {
      alert('Please enter production date ');
      $('#production_date').focus();
      return false;
    }
    var expire_date=$('#expire_date').val();
    if(expire_date=='')
    {
      alert('Please enter expire date!');
      $('#expire_date').focus();
      return false;
    }
    if(expire_date<production_date)
    {
      alert('Sorry! expire date must be greater than production date ');
      $('#expire_date').focus();
      return false;
    }
    
    
    
   }else{
    var production_date=$('#production_date').val();
    var expire_date=$('#expire_date').val();
   }
   var total_add=$('#total_add').val();
   if(total_add==''){
    alert('please this field is required');
    $('#total_add').focus();
    return false;
   }
   var autoserialparent=$('#autoserialparent').val();
   var token_search=$('#token_search').val();
   var ajax_url=$('#ajax_edit_item_details').val();
   $.ajax({
    type: "post",
    url: ajax_url,
    data: {autoserialparent:autoserialparent,'_token':token_search,id:id,item_code_add:item_code_add,uom_id_Add:uom_id_Add,
    isparentuom:isparentuom,quantity_add:quantity_add,price_add:price_add,production_date:production_date,expire_date:expire_date,total_add:total_add,type:type},
    cache:false,
    dataType: "json",
    success: function (data) {
      alert('Data is updated successfully.');
      reload_parent_bill();
      reload_itemsdetails();
      
    }
   });
    
  });
 
  $(document).on('click','#load_modal_add_detailsBtn', function () {
    var id=$(this).data("id");
    var autoserialparent=$('#autoserialparent').val();
     var token_search=$('#token_search').val();
   var ajax_load_modal_add_details=$('#ajax_load_modal_add_details').val();
    $.ajax({
      type: "post",
      url: ajax_load_modal_add_details,
      cache:false,
      data: {autoserialparent:autoserialparent,'_token':token_search,id:id},
      dataType: "html",
      success: function (data) {
        $('#add_item_modal_body').html(data);
        $('#add_item_modal').modal('show');
        $('#edit_item_modal_body').html('');
        $('#edit_item_modal').modal('hide');

      }
    });
  });

  $(document).on('click',"#load_close_approve_invoice", function () {
    var autoserialparent=$("#autoserialparent").val();
    var token_search=$("#token_search").val();
    var ajax_search_url=$("#ajax_load_modal_approve_invoice").val();
    $.ajax({
      type: "post",
      url: ajax_search_url,
      data: {autoserialparent:autoserialparent,'_token':token_search},
      cache:false,
      dataType: "html",
      success: function (data) {
        $("#ModalApproveInvoice_body").html(data);
        $("#ModalApproveInvoice").modal("show");

      }
    });
  });

  $(document).on('input','#tax_percent', function () {
    var tax_percent=$(this).val();
    if(tax_percent==""){tax_percent=0;}
    if(tax_percent>100){
      alert("Tax percent must be less then 100 !!!");
      $('#tax_percent').val(0);
    }
    recalculate();
  });

  $(document).on('input','#discount_percent', function () {
    var discount_percent=$(this).val();
    if(discount_percent==""){discount_percent=0;}
    var discount_type=$('#discount_type').val();
    if(discount_type==1){
      if(discount_percent>100){
        alert("Discount percent must be less then 100 !!!");
        $('#discount_percent').val(0);
      }
    }
    recalculate();
  });
  $(document).on('change','#discount_type', function () {
  var discount_type=$(this).val();
  if(discount_type==""){
    $('#discount_percent').val(0);
    $('#discount_value').val(0);
    $('#discount_percent').attr("readonly",true);
  }else{
    $('#discount_percent').attr("readonly",false);
  }
  var discount_percent=$('#discount_percent').val();
  if(discount_percent==""){discount_percent=0;}
  if(discount_type==1){
    if(discount_percent>100){
      alert("Discount percent must be less then 100 !!!");
      $('#discount_percent').val(0);
    }
  }
    recalculate();
  });


  $(document).on('input','#what_paid', function () {
    total_cost=parseFloat($("#total_cost").val());
    treasuries_balance=parseFloat($("#treasuries_balance").val());
    what_paid=$(this).val();
    if(what_paid==""){
      what_paid=0;
    }
    what_paid=parseFloat(what_paid);
    var bill_type=$("#bill_type").val();
    if(bill_type==1){
      //cash
      if(what_paid<total_cost){
        alert("Sorry ,all amount must be paid in cash bill !!");
        $("#what_paid").val(total_cost);
      }
    }else{
      if(what_paid==total_cost)
      {
        alert('Sorry ,not all amount must be paid in Deferre');
        $('#what_paid').val(0);
      }
    }
    if(what_paid>total_cost){
      alert('Sorry ,paid amount must be less than total of the bill !!!');
      $('#what_paid').val(0);
    }
    if(what_paid>treasuries_balance){
      alert("Sorry , there is no enough balance in treasury !!");
      $('#what_paid').val(0);
    }
    recalculate();
  });


  $(document).on('change','#bill_type', function () {
    var bill_type=$('#bill_type').val();
    var total_cost=$('#total_cost').val();
    if(bill_type==1){
      //cash
      $("#what_paid").val(total_cost*1);
      $("#what_remain").val(0);
      $("#what_paid").attr("readonly",true);
      recalculate();
    }else{
      $("#what_paid").val(0);
      $("#what_remain").val(total_cost*1);
      $("#what_paid").attr("readonly",false);
      recalculate();
    }
  });

  function recalculate()
  {
    var total_cost_items=$('#total_cost_items').val();
    if(total_cost_items==""){
      total_cost_items=0;
    }
    total_cost_items=parseFloat(total_cost_items);
    var tax_percent=$('#tax_percent').val();
    if(tax_percent==""){
      tax_percent=0;
    }
    var tax_value=total_cost_items*tax_percent/100;
    tax_value=parseFloat(tax_value);
    $('#tax_value').val(tax_value*1);

    var total_before_discount=total_cost_items+tax_value;
    $('#total_before_discount').val(total_before_discount);
    var discount_type=$('#discount_type').val();
    if(discount_type!=''){
      if(discount_type==1){
        var discount_percent=$("#discount_percent").val();
        if(discount_percent==""){discount_percent=0;}
        discount_percent=parseFloat(discount_percent);
        var discount_value=total_before_discount*discount_percent/100;
        $('#discount_value').val(discount_value*1);
        var total_cost=total_before_discount-discount_value;
        $('#total_cost').val(total_cost*1);
      }else{
        var discount_percent=$('#discount_percent').val();
        if(discount_percent==""){discount_percent=0;}
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
    what_paid=$('#what_paid').val();
    if(what_paid=='') what_paid=0;
    what_paid=parseFloat(what_paid);
    total_cost=parseFloat(total_cost);
     var what_remain = total_cost-what_paid;
    $('#what_remain').val(what_remain*1);
  }
  $(document).on('mouseenter','#do_close_invoice_now', function () {
    var token_search=$('#token_search').val();
    var ajax_search_url=$('#ajax_load_usershiftDiv').val();
    $.ajax({
      type: "post",
      url: ajax_search_url,
      cashe:false,
      data: {'_token':token_search},
      dataType: "html",
      success: function (data) {
        $('#shiftDiv').html(data);
        
      }
    });
    
  });
  $(document).on('click',"#do_close_invoice_now", function () {
    var total_cost_items=$('#total_cost_items').val();
    if(total_cost_items==""){
      alert('Please inter total for items !!');
      return false;
    }
    var tax_percent=$('#tax_percent').val();
    if(tax_percent==""){
      alert('Please enter tax percent');
      $('#tax_percent').focus();
      return false;
    }
    var tax_value=$('#tax_value').val();
    if(tax_value==""){
      alert("Please enter tax value !!!");
      return false;
    }
     var total_before_discount=$("#total_before_discount").val();
     if(total_before_discount==""){
      alert("Please enter total before discount !!!");
      return false;
     } 
     var discount_type=$('#discount_type').val();
     if(discount_type==1){
      var discount_percent=$('#discount_percent').val();
      if(discount_percent>100){
        alert('Discount percent must be less then 100 !!!');
        $('#discount_percent').focus();
        return false;
      }
     }else if(discount_type==2){
      var discount_value=$('#discount_value').val();
      if(discount_value>total_before_discount){
        alert("Sorry ,discount value must not be greater than total before discount value !!");
        $('#discount_value').focus();
        return false;
      }
     } else{
      var discount_value=$('#discount_value').val();
      if(discount_value>0){
        alert("Sorry discount value must not be greater than zero if there isn't selected discount type !!! ");
        $('#discount_value').focus();
        return false;
      }
     } 
     var discount_value=$('#discount_value').val();
     if(discount_value==""){
      alert('enter discount value !!!');
      return false;
     }
     var what_paid=$('#what_paid').val();
      if(what_paid==""){
        alert('Please enter paid amount !!!');
        return false;
      }
      if(what_paid>total_cost)
      {
        alert('Sorry ,paid amount must not be greater than total of the bill !!!');
        return false;
      }
      if(bill_type==1)
      {
        if(what_paid<total_cost)
        {
          alert("Sorry ,all amount must be paid in cash bill !!!");
          return false;
        }
      }else{
        if(what_paid==total_cost){
          alert('Sorry ,paid amount is not equal to total amount in Deferred bill !!!');
          return false;
        }
      }
      var what_remain=$('#what_remain').val();
      if(what_remain=="")
      {
        alert("Please enter remained amount !!!");
        return false;
      }
      if(bill_type==1){
        if(what_remain>0){
          alert("Sorry ,there isn't remained amount in cash bill !!!!");
          return false;
        }
      }
      if(what_paid>0)
      {
        var treasuries_id=$('#treasuries_id').val();
        if(treasuries_id==""){
          alert('Please select treasury !!');
          return false;
        }
        var treasuries_balance=$('#treasuries_balance').val();
        if(treasuries_balance==""){
          alert("Please enter treasury balance !!!");
          return false;
        }
        if(parseFloat(what_paid)>parseFloat(treasuries_balance)){
          alert("Please there isn't enough balance in the treasury !!!");
          return false;
        }
      }
  });
  $(document).on('change','#supplier_code_search', function () {
    make_search();
  });
  $(document).on('input','#search_by_text', function () {
    make_search();
  });
  $(document).on('change','#store_id_search', function () {
    make_search();
  });
  $(document).on('change','#order_date_from', function () {
    make_search();
  });
  $(document).on('change','#order_date_to', function () {
    make_search();
  });
  $('input[type=radio][name=searchbyradio]').change(function (e) { 
    e.preventDefault();
    
  });
  
  function make_search()
  {
    var token_search=$("#token_search").val();
    var ajax_search_url=$("#ajax_search_url").val();
    var searchbyradio=$("input[type=radio][name=searchbyradio]:checked").val();
    var supplier_code=$("#supplier_code_search").val();
    var search_by_text=$("#search_by_text").val();
    var store_id=$("#store_id_search").val();
    var order_date_from=$("#order_date_from").val();
    var order_date_to=$("#order_date_to").val();
    $.ajax({
      type: "post",
      url:ajax_search_url ,
      data: {searchbyradio:searchbyradio,supplier_code:supplier_code,search_by_text:search_by_text,store_id:store_id,order_date_from:order_date_from,order_date_to:order_date_to,'_token':token_search},
      dataType: "html",
      cashe:false,

      success: function (data) {
        $("#ajax_response_searchdiv").html(data);
      }
    });
  }

  $(document).on('click','#ajax_pagination_in_search a', function (e) {
    e.preventDefault();
    var token_search=$("#token_search").val();
    var searchbyradio=$("input[type=radio][name=searchbyradio]:checked").val();
    var supplier_code=$("#supplier_code_search").val();
    var search_by_text=$("#search_by_text").val();
    var store_id=$("#store_id_search").val();
    var order_date_from=$("#order_date_from").val();
    var order_date_to=$("#order_date_to").val();
    var url=$(this).attr("href");
    $.ajax({
      type: "post",
      url: url,
      data: {searchbyradio:searchbyradio,supplier_code:supplier_code,search_by_text:search_by_text,store_id:store_id,order_date_from:order_date_from,order_date_to:order_date_to,'_token':token_search},
      dataType: "html",
      cache:false,
      success: function (data) {
        $("#ajax_response_searchdiv").html(data);

      }
    });
    
  });

});