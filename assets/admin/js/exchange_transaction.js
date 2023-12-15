$(document).ready(function () {
    
    $(document).on('click','#do_exchange_now_btn', function () {
        var move_date=$('#move_date').val();
        if(move_date==''){
            alert('Please enter date !');
            $('#move_date').focus();
            return false;
        }

        var account_number=$('#account_number').val();
        if(account_number=='')
        {
            alert('Please enter account number !');
            $('#account_number').focus();
            return false;
        }
        var mov_type=$('#mov_type').val();
        if(mov_type=='')
        {
            alert('Please enter transaction type !!');
            $('#mov_type').focus();
            return false;
        }

        var treasuries_id=$('#treasuries_id').val();
        if(treasuries_id=='')
        {
           alert('Please select treasury');
           $('#treasuries_id').focus();
           return false;
        }

        var money=$('#money').val();
         if(money=='' || money<=0)
         {
            alert('Please enter value of collected amount !');
            $('#money').focus();
            return false;
         }

         var treasuries_balance=$('#treasuries_balance').val();
         if(treasuries_balance=='' || treasuries_balance==0){
            alert('Sorry , there is not enough balance in the treasury !!!');
            $('#money').focus();
            return false;
         }

         if(parseFloat(money)>parseFloat(treasuries_balance)){
            alert('Sorry , there is not enough balance in the treasury !!!');
            $('#money').focus();
            return false;

         }

         var byan=$('#byan').val();
         if(byan==''){
            alert('Please enter description clearly !');
            $('#byan').focus();
            return false;
         }
        
    });

    $(document).on('change',"#account_number", function () {
      var account_number=$(this).val();
      if(account_number==""){
          $('#mov_type').val("");
      }else{
          var account_type=$('#account_number option:selected').data('type');
          if(account_type==2)
          {
              //if is supplier
              $('#mov_type').val(9);
          }else if(account_type==3)
          {
              //if is customer
             $('#mov_type').val(6);
          }else if(account_type==6)
          {
              //if is bank
              $('#mov_type').val(18);

          }else{
              //general
              $('#mov_type').val(3);
          }
      }
  });

  $(document).on('change',"#mov_type", function () {
    var account_number=$("#account_number").val();
    if(account_number=="")
    {
        alert("Please select account first");
        $("#mov_type").val("");
        return false;
    }
    var account_type=$("#account_number option:selected").data("type");
    if(account_type==2){
        //if is supplier
        $("#mov_type").val(9);
    }else if(account_type==3){
        //if is customer
        $("#mov_type").val(9);
    }else if(account_type==6)
    {
        //if is bank
        $("#mov_type").val(18);
    }else{
        //if is general
        $("#mov_type").val(3);
    }
    
  });
  $(document).on('change','#account_number', function () {
    if($(this).val()!=""){
        var token_search=$("#token_search").val();
        var url=$('#ajax_url_get_account_balance').val();
        $.ajax({
            type: "post",
            url: url,
            data: {'_token':token_search,account_number:$(this).val()},
            dataType: "html",
            success: function (data) {
             $("#get_account_balanceDiv").html(data);
             $('#get_account_balanceDiv').show();   
            }
        });



    }
  });

 

  $(document).on('click','#ajax_pagination_in_search a', function (e) {
    e.preventDefault();
    var token_search=$('#token_search').val();
    var searchbyradio=$('input[type=radio][name=searchbyradio] : checked').val();
    var search_by_text=$('#search_by_text').val();
    var account_number=$('#account_number_search').val();
    var mov_type=$('#mov_type_search').val();
    var treasuries=$('#treasuries_search').val();
    var admins=$('#admins_search').val();
    var from_date=$('#from_date_search').val();
    var to_date=$('#to_date_search').val();
    var url=$(this).attr('href');
    $.ajax({
        type: "post",
        url: url,
        data: {"_token":token_search,searchbyradio:searchbyradio,search_by_text:search_by_text,account_number:account_number,
        mov_type:mov_type,treasuries:treasuries,admins:admins,from_date:from_date,to_date:to_date},
        dataType: "html",
        success: function (data) {
            $('#ajax_response_searchdiv').html(data);
        }
    });
    
  });

  function make_search(){
    var token=$("#token_search").val();
    var account_number=$('#account_number_search').val();
    var mov_type=$('#mov_type_search').val();
    var treasuries=$('#treasuries_search').val();
    var admins=$('#admins_search').val();
    var from_date=$('#from_date_search').val();
    var to_date=$('#to_date_search').val();
    var search_by_text=$('#search_by_text').val();
    var searchbyradio=$("input[type=radio][name=searchbyradio]:checked").val();
var url=$('#get_ajax_search').val();
$.ajax({
    type: "post",
    url: url,
    data: {'_token':token,account_number:account_number,mov_type:mov_type,treasuries:treasuries,admins:admins,from_date:from_date,to_date:to_date,search_by_text:search_by_text,searchbyradio:searchbyradio},
    dataType: "html",
    success: function (data) {
     $('#ajax_response_searchdiv').html(data);   
    },
    error:function()
    {
        // alert("Something went wrong!");
    }
});

}

$('input[type=radio][name=searchbyradio]').change(function () { 
 make_search();        
});
$(document).on('input','#search_by_text', function () {
    make_search();
});
$(document).on('change','#account_number_search', function () {
    make_search();

});
$(document).on('change','#mov_type_search', function () {
  make_search();  
});
$(document).on('change','#treasuries_search', function () {
    make_search();
});
$(document).on('change','#from_date_search', function () {
   make_search(); 
});
$(document).on('change','#to_date_search', function () {
    make_search();
});
$(document).on('change','#admins_search', function () {
    make_search();
});

$(document).on('click','#ajax_pagination_in_search a', function (e) {
    e.preventDefault();
    var token=$("#token_search").val();
    var account_number=$('#account_number_search').val();
    var mov_type=$('#mov_type_search').val();
    var treasuries=$('#treasuries_search').val();
    var admins=$('#admins_search').val();
    var from_date=$('#from_date_search').val();
    var to_date=$('#to_date_search').val();
    var search_by_text=$('#search_by_text').val();
    var searchbyradio=$('input[type=radio][name=searchbyradio]:checked').val();
    var url=$(this).attr("href");
    $.ajax({
        type: "post",
        url: url,
        data: {'_token':token,account_number:account_number,mov_type:mov_type,treasuries:treasuries,admins:admins,from_date:from_date,to_date:to_date,search_by_text:search_by_text,searchbyradio:searchbyradio},
        dataType: "html",
        success: function (data) {
            $('#ajax_response_searchdiv').html(data);   

        }
    });
    
});
});