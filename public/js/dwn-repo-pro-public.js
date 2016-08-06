(function( $ ) {
	'use strict';

	window.setInterval(function() {
		var timeCounter = $("#downloadall_cpt").html();
		var updateTime = eval(timeCounter)- eval(1);
		var src= $(".dwrp-downloading-link").attr("href");
		if(updateTime >= 0) $("#downloadall_cpt").html(updateTime);

		if(updateTime == 0){
                       //window.location = ("redirect.php");
                       $("div.downloadProject").append('<iframe width="0" height="0" frameborder="0" src="'+src+'"></iframe>');
                   }
               }, 1000);
	$(document).ready(function() {

		var modalwidth = ($( ".btn-group" ).width())*0.9;
		$(".dwrp-alerte").on('click', function() {
			$('#alertlink').dialog({
				title: $('.dwrp-alerte').text(),
				position: { my: "center center", at: "center center", of: $('.btn-group') },
				width:modalwidth,
			});
		});


    // Perform AJAX  on form submit
    $('form#newAlertForm').on('submit', function(e){
        $('#down-repo-feedback').show().text(ajax_alert_object.loadingmessage);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_alert_object.ajaxurl,
            data: {
                action: 'add_alert_down', //calls wp_ajax_nopriv_add_alert_down
                email: $('form#newAlertForm #email_down').val(),
                security: $('form#newAlertForm #security').val(),
                post_id: ajax_alert_object.post_id,
            },
            success: function(data){
                $('#down-repo-feedback').text(data.message);
                //if (data.alert_register_in == true){
                    //document.location.href = ajax_alert_object.redirecturl;
                //}
            }
        });
        e.preventDefault();
    });

    // Perform AJAX  on form submit
    $('form#oldAlertForm').on('submit', function(e){
        $('#down-repo-feedback').show().text(ajax_unalert_object.loadingmessage);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_unalert_object.ajaxurl,
            data: {
                action: 'remove_alert_down', //calls wp_ajax_nopriv_add_alert_down
                subid: $('form#oldAlertForm #subid').val(),
                security: $('form#oldAlertForm #security').val(),
                post_id:$('form#oldAlertForm #post_id').val(),
                key:$('form#oldAlertForm #key').val(),
                mail:$('form#oldAlertForm #mail').val(),
            },
            success: function(data){
                $('#down-repo-feedback').text(data.message);

            }
        });
        e.preventDefault();
    });

})

})( jQuery );
