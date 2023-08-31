$(document).ready(function () {
    $(document).on('input','#search_by_text', function () {
        var search_by_text=$(this).val();

        var token_search=$('#token_search').val();
        var ajax_search_url=$('#ajax_search_url').val();

        $.ajax({
            type: "post",
            url: ajax_search_url,
            data: {search_by_text:search_by_text,'_token':token_search},
            dataType: "html",
            cache:false,
            success: function (data) {
                
                $('#ajax_response_searchdiv').html(data);
            },
            error:function(){

            }

        });
    });

    $(document).on('click','#ajax_pagination_in_search a', function (e) {
        e.preventDefault();
        var search_by_text=$('#search_by_text').val();
        var url=$(this).attr("href");
        var token_search=$('#token_search').val();
        $.ajax({
            type: "post",
            url: url,
            cache:false,
            data: {search_by_text:search_by_text,'_token':token_search},
            dataType: "html",
            success: function (data) {
                $('#ajax_response_searchdiv').html(data);
  
            }
        });

    });
});