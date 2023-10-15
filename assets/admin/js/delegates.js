$(document).ready(function () {
   

    $(document).on('click','.show_more_details', function () {
        var token_search=$("#token_search").val();
        var url=$("#ajax_search_show").val();
        var id=$(this).data("id");
        $.ajax({
            type: "post",
            url: url,
            data: {'_token':token_search,id:id},
            dataType: "html",
            success: function (data) {
                $('#MoreDetailsModalBody').html(data);
                $('#MoreDetailsModal').modal('show');
            }
        });
        
    });
    $(document).on('input','#search_by_text', function () {
        make_search();
    });
    $('input[type=radio][name=searchbyradio]').change(function (e) { 
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

    //functions
    function make_search(){
        var token_search=$('#token_search').val();
        var ajax_search_url=$('#ajax_search_url').val();
        var search_by_text=$('#search_by_text').val();
        var searchbyradio=$("input[type=radio][name=searchbyradio]:checked").val();
        $.ajax({
            type: "post",
            url: ajax_search_url,
            data: {'_token':token_search,search_by_text:search_by_text,searchbyradio:searchbyradio},
            dataType: "html",
            success: function (data) {
                $('#ajax_response_searchdiv').html(data);
                
            }
        });
    }
});