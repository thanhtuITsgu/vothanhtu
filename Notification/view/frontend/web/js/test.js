require(
    [ 'jquery','mage/url','jquery/ui'], function($,url){

    $(document).on('click','#Mark', function() {
        var currentRow=$(this).closest("tr");
        var idnotification=currentRow.find("#entity_id").text();
        var URL = url.build('notification/viewed/viewed');

        $.ajax({
            showLoader: true,
            type: "POST",
            url: URL,
            data: {Id: idnotification},
            cache: false,
            success: function () {
                var idMark=currentRow.find("#Mark").text();
                if(idMark!='') {
                    var count = $('#count').text() - 1;
                    $.cookie('Count', count);
                    var abc = $.cookie('Count');
                }
                location.reload();
            }, error: function () {
                alert('YOU HAVE ERROR');
            }
        });
    });

    $(document).on('click','#Delete', function() {
        var currentRow=$(this).closest("tr");
        var idnotification=currentRow.find("#entity_id").text();
        var URL = url.build('notification/delete/delete');
        $.ajax({
            showLoader: true,
            type: "POST",
            url: URL,
            data: {Id: idnotification},
            cache: false,
            success: function () {
                var idMark=currentRow.find("#Mark").text();
                if(idMark!='') {
                    var count = $('#count').text() - 1;
                    $.cookie('Count', count);
                    var abc = $.cookie('Count');
                }
                location.reload();
            }, error: function () {
                alert('YOU HAVE ERROR');
            }
        });
    });


   /* $('a').each(function (index) {
        var count = $.cookie('Count');
        if($(this).text() === "My Notification")
        {
            $(this).html('My Notification'+" "+"<span style='background:#CBBEBE;border-radius:50%;display:inline-block;width:16px;text-align:center'>"+count+"</span>");
        }
    });*/

    $('strong').each(function () {
        if($(this).text() === "My Notification")
        {
            $(this).html('My Notification'+" "+"<span style='background:#CBBEBE;border-radius:50%;display:inline-block;width:16px;text-align:center'>"+$('#count').text()+"</span>");
        }
    });
    $('.my_custom_link').html('My Notification'+" "+"<span style='background:#CBBEBE;border-radius:50%;display:inline-block;width:16px;text-align:center'>"+$.cookie('Count')+"</span>");

    /*
    $('strong').html('My Notification'+" "+"<span style='background:#CBBEBE;border-radius:50%;display:inline-block;width:16px;text-align:center'>"+$('#count').text()+"</span>");
*/

});

/*require(['jquery','jquery/ui','mage/ui'],function ($) {
    $(document).on('click','#viewed',function () {
        $.ajax({})
    })
});*/
