$(document).ready(function () {
    
    $(document).on('click','#do_collect_now_btn', function () {
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
                $('#mov_type').val(10);
            }else if(account_type==3)
            {
                //if is customer
               $('#mov_type').val(5);
            }else if(account_type==6)
            {
                //if is bank
                $('#mov_type').val(25);

            }else{
                //general
                $('#mov_type').val(4);
            }
        }
    });

    $(document).on('change','#mov_type', function () {
        var account_number=$('#account_number').val();
        if(account_number=="")
        {
            alert('Please select account number first !');
            $("#mov_type").val("");
            return false;
        }
        var account_type=$("#account_number option:selected").data("type");
        if(account_type==2){
            //if is supplier
            $("#mov_type").val(10);
        }else if(account_type==3){
            //if is customer
            $("#mov_type").val(5);
        }else if(account_type==6){
            //if is bank
            $("#mov_type").val(25);
        }else{
          $("#mov_type").val(4);
        }
    });
});