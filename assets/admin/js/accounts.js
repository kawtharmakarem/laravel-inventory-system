$(document).ready(function () {

  $(document).on('change','#is_parent', function (e) {
    if($(this).val()==0)
    {
      $('#parentDiv').show();
    }else
    {
      $('#parentDiv').hide();

    }
    
  });

  $(document).on('input','#start_balance_status', function () {
  if($(this).val()==""){
    $('#start_balance').val("");


  }else{
   if($(this).val()==3)
   {
    $('#start_balance').val(0);
   } 
  }
  
  });

  $(document).on('input','#start_balance', function () {
    var start_balance_status=$('#start_balance_status').val();
    if(start_balance_status=='')
    {
      alert('Please select balance status first .');
      $(this).val('');
      return false;
    }
    if($(this).val()==0 && start_balance_status!=3 ){
      alert('you must enter an amount greater than zero .');
      $(this).val("");
      return false;
    }
    
  });

  $(document).on('input','#search_by_text', function () {
    make_search();
  });

  $(document).on('input','#account_type_search', function () {
    make_search();
  });

  $(document).on('input','#is_parent_search', function () {
    make_search();
  });
  $(document).on('input','#active_search', function () {
    make_search();
    
  });
  
  $('input[type=radio][name=searchbyradio]:checked').change(function (e) { 
    make_search();
    
  });

  $(document).on('click','#ajax_pagination_in_search a', function (e) {
   e.preventDefault();
   var search_by_text=$('#search_by_text').val();
   var account_type=$('#account_type_search').val();
    var is_parent=$('#is_parent_search').val();
    var active=$('#active_search').val();
   var searchbyradio=$('input[type=radio][name=searchbyradio]:checked').val(); 
 var token_search=$('#token_search').val();
 var url=$(this).attr("href");
 $.ajax({
  type: "post",
  url: url,
  data: {search_by_text:search_by_text,searchbyradio:searchbyradio,'_token':token_search,account_type:account_type,is_parent:is_parent,active:active},
  dataType: "html",
  success: function (data) {
    $('#ajax_response_searchdiv').html(data);

  }
 });
  });

  function make_search()
  {
    var search_by_text=$('#search_by_text').val();
    var account_type=$('#account_type_search').val();
    var is_parent=$('#is_parent_search').val();
    var active=$('#active_search').val();
    var searchbyradio=$('input[type=radio][name=searchbyradio]:checked').val();
    var ajax_search_url=$('#ajax_search_url').val();
    var token_search=$('#token_search').val();

  $.ajax({
    type: "post",
    url: ajax_search_url,
    data: {search_by_text:search_by_text,searchbyradio:searchbyradio,'_token':token_search,account_type:account_type,is_parent:is_parent,active:active},
    dataType: "html",
    cache:false,
    success: function (data) {
      $('#ajax_response_searchdiv').html(data);
    },
    error:function(){}
  });

  }

});