$(document).ready(function () {

 
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

 
  
  $('input[type=radio][name=searchbyradio]:checked').change(function (e) { 
    make_search();
    
  });

  $(document).on('click','#ajax_pagination_in_search a', function (e) {
   e.preventDefault();
   var search_by_text=$('#search_by_text').val();
   var searchbyradio=$('input[type=radio][name=searchbyradio]:checked').val(); 
 var token_search=$('#token_search').val();
 var url=$(this).attr("href");
 $.ajax({
  type: "post",
  url: url,
  data: {search_by_text:search_by_text,searchbyradio:searchbyradio,'_token':token_search},
  dataType: "html",
  success: function (data) {
    $('#ajax_response_searchdiv').html(data);

  }
 });
  });

  function make_search()
  {
    var search_by_text=$('#search_by_text').val();
    var searchbyradio=$('input[type=radio][name=searchbyradio]:checked').val();
    var ajax_search_url=$('#ajax_search_url').val();
    var token_search=$('#token_search').val();

  $.ajax({
    type: "post",
    url: ajax_search_url,
    data: {search_by_text:search_by_text,searchbyradio:searchbyradio,'_token':token_search},
    dataType: "html",
    cache:false,
    success: function (data) {
      $('#ajax_response_searchdiv').html(data);
    },
    error:function(){}
  });

  }

});