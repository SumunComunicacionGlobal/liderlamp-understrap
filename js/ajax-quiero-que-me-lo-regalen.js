(function($) {
	$(document).on( 'click', '#tab-button-regalo', function( event ) {
		event.preventDefault();

        $.ajax({
            // method: 'POST',
            // dataType: 'json',
            url: ajaxquieroquemeloregalen.ajaxurl,
            type: 'post',
            data: {
                action: 'ajax_quiero_que_me_lo_regalen'
            },
            success: function( result ) {
                $( '#tab-regalo > .card-body' ).html( result );
            },
            error: function () {
                alert("error");
            }
        })
    
	})
})(jQuery);