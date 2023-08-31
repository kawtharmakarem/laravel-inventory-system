$(document).ready(function () {
    $(document).on('change','#does_has_retailunit', function (e) {
    var uom_id=$("#uom_id").val();
    if(uom_id==''){
    alert('Select basic unit first !');
    $('#does_has_retailunit').val("");
    return false;
}
if($(this).val()==1)
    {
     $("#retail_uom_idDiv").show();
     var retail_uom_id=$('#retail_uom_id').val();
     if(retail_uom_id!=null){
        $('.related_retail_counter').show();

     }else{
        $('.related_retail_counter').show();

     }
           
        }else
        {
            $('.related_retail_counter').hide();
            $('#retail_uom_idDiv').hide();
        }
        $("#retail_uom_idDiv").val("");

    });

    $(document).on('change','#uom_id', function (e) {
       if($(this).val()!=null){
        //اذهب الى الخيار المختار واجلب فيمته
        var name=$('#uom_id option:selected').text();
        $(".parentuomname").text(name);
        var does_has_retailunit=$("#does_has_retailunit").val();
        if($(does_has_retailunit)==1){
         var retail_uom_id=("#retail_uom_id").val();
          if(retail_uom_id!=''){
            $('.related_retail_counter').show(); 

          }else{
            $('.related_retail_counter').hide(); 

          }
          
        
        }else{
            $('.related_retail_counter').hide();
            $("#retail_uom_idDiv").hide();

        }

        $(".related_parent_counter").show();

       }else{
        $(".parentuomname").text('');
        $(".related_retail_counter").hide();
        $(".related_parent_counter").hide();
        $("#retail_uom_idDiv").hide();

       }
        
    });

    $(document).on('change','#retail_uom_id', function () {
        if($(this).val()!=''){
         //اذهب الى الخيار المختار واجلب فيمته
         var name=$('#retail_uom_id option:selected').text();
         $(".childuomname").text(name);
         $(".retailed_retail_counter").show();


        }else{
         $(".childuomname").text('');
         $(".related_retail_counter").hide();
 
        }
         
     });

     $(document).on('click','#do_add_item_card', function (e) {
      var name=$('#name').val();
      if(name=='')
      {
        alert('Please enter Category name');
        $('#name').focus();
        return false;
      }
      var item_type=$('#item_type').val();
      if(item_type==''){
        alert('Please enter expenses type');
        $('#item_type').focus();
        return false;
      }
      var inv_itemcard_categories_id=$('#inv_itemcard_categories_id').val();
      if(inv_itemcard_categories_id==''){
        alert('Please select main category');
        $('#inv_itemcard_categories_id').focus();

        return false;
      }
      //وحدة القياس الاساسية
      var uom_id=$('#uom_id').val();
      if(uom_id==''){
        alert('Please select basic unit for period');
        $('#uom_id').focus();

        return false;
      }
      //هل هناك وحدة فياس فرعية
      var does_has_retailunit=$('#does_has_retailunit').val();
      if(does_has_retailunit==''){
        alert('Please tell us if there is fragment unit .');
        $('#does_has_retailunit').focus();

        return false;
      }
      if(does_has_retailunit==1){
        var retail_uom_id=$('#retail_uom_id').val();
        var retail_uom_quntToParent=$('#retail_uom_quntToParent').val();
        
        if(retail_uom_id=='')
        {
          alert('please select fargment unit .');
          $('#retail_uom_id').focus();
          return false;
        }
        if(retail_uom_quntToParent=='' || retail_uom_quntToParent==0)
        {
         alert('Please enter basicUnit/fragmentUnit=? .');
         $('#retail_uom_quntToParent').focus();

         return false;
        }
        
      }
      var price=$('#price').val();
      if(price=='')
      {
        alert('Please enter price reference to basic unit');
        $('#price').focus();

        return false;
      }
      //todo : delete this code
      var nos_gomla_price=$('#nos_gomla_price').val();
      if(nos_gomla_price==''){
        alert('please enter required price');
        $('#nos_gomla_price').focus();

        return false;
      }
      //todo :delete this code
      var gomla_price=$('#gomla_price').val();
      if(gomla_price==''){
        alert('Please enter required price ');
        $('#gomla_price').focus();

        return false;
      }
      var cost_price=$('#cost_price').val();
      if(cost_price==''){
        alert('please enter cost_price');
        $('#cost_price').focus();

        return false;
      }
      if(does_has_retailunit==1){

       //todo :delete this field
       var price_retail=$('#price_retail').val();
       if(price_retail=='')
       {
        alert('Please enter required price');
        $('#price_retail').focus();
        return false;
       }
       //todo :delete this field
       var nos_gomla_price_retail=$('#nos_gomla_price_retail').val();
       if(nos_gomla_price_retail==''){
        alert('Please required price');
        $('#nos_gomla_price_retail').focus();

        return false;
      }
      //todo:delete this field
      var gomla_price_retail=$('#gomla_price_retail').val();
      if(gomla_price_retail=='')
      {
        alert('Please enter required price');
        $('#gomla_price_retail').focus();
        return false;
      }
      //todo :delete this field
      var cost_price_retail=$('#cost_price_retail').val();
      if(cost_price_retail==''){
        alert('Please enter required price');
        $('#cost_price_retail').focus();
        return false;
      }

       
      }
      var has_fixed_price=$('#has_fixed_price').val();
      if(has_fixed_price==''){
        alert('Please tell us if it is fixed price or not .');
        $('#has_fixed_price').focus();
        return false;
      }
      var active=$('#active').val();
      if(active==''){
        alert('please status is required . ');
        $('#active').focus();
        return false;
      }
      
     });


     
     $(document).on('click','#do_edit_item_cardd', function (e) {
      var barcode=$("#barcode").val();
      if(barcode==""){
        alert("category code is required");
        $("#barcode").focus();
        return false;
      }
      
      var name=$('#name').val();
      if(name=='')
      {
        alert('Please enter Category name');
        $('#name').focus();
        return false;
      }
      var item_type=$('#item_type').val();
      if(item_type==''){
        alert('Please enter expenses type');
        $('#item_type').focus();
        return false;
      }
      var inv_itemcard_categories_id=$('#inv_itemcard_categories_id').val();
      if(inv_itemcard_categories_id==''){
        alert('Please select main category');
        $('#inv_itemcard_categories_id').focus();

        return false;
      }
      //وحدة القياس الاساسية
      var uom_id=$('#uom_id').val();
      if(uom_id==''){
        alert('Please select basic unit for period');
        $('#uom_id').focus();

        return false;
      }
      //هل هناك وحدة فياس فرعية
      var does_has_retailunit=$('#does_has_retailunit').val();
      if(does_has_retailunit==''){
        alert('Please tell us if there is fragment unit .');
        $('#does_has_retailunit').focus();

        return false;
      }
      if(does_has_retailunit==1){
        var retail_uom_id=$('#retail_uom_id').val();
        var retail_uom_quntToParent=$('#retail_uom_quntToParent').val();
        
        if(retail_uom_id=='')
        {
          alert('please select fargment unit .');
          $('#retail_uom_id').focus();
          return false;
        }
        if(retail_uom_quntToParent=='' || retail_uom_quntToParent==0)
        {
         alert('Please enter basicUnit/fragmentUnit=? .');
         $('#retail_uom_quntToParent').focus();

         return false;
        }
        
      }
      var price=$('#price').val();
      if(price=='')
      {
        alert('Please enter price reference to basic unit');
        $('#price').focus();

        return false;
      }
      //todo : delete this code
      var nos_gomla_price=$('#nos_gomla_price').val();
      if(nos_gomla_price==''){
        alert('please enter required price');
        $('#nos_gomla_price').focus();

        return false;
      }
      //todo :delete this code
      var gomla_price=$('#gomla_price').val();
      if(gomla_price==''){
        alert('Please enter required price ');
        $('#gomla_price').focus();

        return false;
      }
      var cost_price=$('#cost_price').val();
      if(cost_price==''){
        alert('please enter cost_price');
        $('#cost_price').focus();

        return false;
      }
      if(does_has_retailunit==1){

       //todo :delete this field
       var price_retail=$('#price_retail').val();
       if(price_retail=='')
       {
        alert('Please enter required price');
        $('#price_retail').focus();
        return false;
       }
       //todo :delete this field
       var nos_gomla_price_retail=$('#nos_gomla_price_retail').val();
       if(nos_gomla_price_retail==''){
        alert('Please required price');
        $('#nos_gomla_price_retail').focus();

        return false;
      }
      //todo:delete this field
      var gomla_price_retail=$('#gomla_price_retail').val();
      if(gomla_price_retail=='')
      {
        alert('Please enter required price');
        $('#gomla_price_retail').focus();
        return false;
      }
      //todo :delete this field
      var cost_price_retail=$('#cost_price_retail').val();
      if(cost_price_retail==''){
        alert('Please enter required price');
        $('#cost_price_retail').focus();
        return false;
      }

       
      }
      var has_fixed_price=$('#has_fixed_price').val();
      if(has_fixed_price==''){
        alert('Please tell us if it is fixed price or not .');
        $('#has_fixed_price').focus();
        return false;
      }
      var active=$('#active').val();
      if(active==''){
        alert('please status is required . ');
        $('#active').focus();
        return false;
      }
      
     });


     $(document).on('input','#search_by_text', function () {
      make_search();
   });

     $(document).on('change','#item_type_search', function (e) {
      make_search();
   });

   $(document).on('change','#inv_itemcard_categories_id_search', function (e) {
    make_search();
 });

 $('input[type=radio][name=searchbyradio]').change(function (e) { 
  make_search();

 });



  function make_search()
  {
 var searchbyradio=$("input[type=radio][name=searchbyradio]:checked").val();

      var search_by_text=$('#search_by_text').val();
      var item_type_search=$('#item_type_search').val();
      var inv_itemcard_categories_id_search=$('#inv_itemcard_categories_id_search').val();
      var token_search=$('#token_search').val();
      var ajax_search_url=$('#ajax_search_url').val();

      $.ajax({
          type: "post",
          url: ajax_search_url,
          data: {search_by_text:search_by_text,item_type_search:item_type_search,inv_itemcard_categories_id_search:inv_itemcard_categories_id_search,'_token':token_search,searchbyradio:searchbyradio},
          dataType: "html",
          cache:false,
          success: function (data) {
              
              $('#ajax_response_searchdiv').html(data);
          },
          error:function(){

          }

      }); 
  }

  $(document).on('click','#ajax_pagination_in_search a', function (e) {
    e.preventDefault();
    var searchbyradio=$("input[type=radio][name=searchbyradio]:checked").val();

    var search_by_text=$('#search_by_text').val();
    var item_type_search=$('#item_type_search').val();
    var inv_itemcard_categories_id_search=$('#inv_itemcard_categories_id_search').val();



    var url=$(this).attr("href");
    var token_search=$('#token_search').val();
    $.ajax({
        type: "post",
        url: url,
        cache:false,
        data: {search_by_text:search_by_text,item_type_search:item_type_search,inv_itemcard_categories_id_search:inv_itemcard_categories_id_search,'_token':token_search,searchbyradio:searchbyradio},
        dataType: "html",
        success: function (data) {
            $('#ajax_response_searchdiv').html(data);

        }
    });

});

});


