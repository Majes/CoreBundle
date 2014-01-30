CoreAdmin = {
	init: function(){
		$(document).ajaxStart(function( event, jqxhr, settings ) {
		  	CoreAdmin.Loader.start();
		});

		$(document).ajaxStop(function( event, jqxhr, settings ) {
		  	CoreAdmin.Loader.stop();
		});

		$("a#emptycache").click(function(e){
			e.preventDefault();
			var self = $(this);
			var href = self.attr('href');

			$.ajax({

				url: href,
				dataType: 'json',
				success: function(response){
					CoreAdmin.Alert.show(response.message);
				}

			});
		});

		
			}
		};

CoreAdmin.Common = {
	confirmDelete: function(msg){
		if(!msg) msg = 'Are you sure you want to do this?';
		return confirm(msg);
	}
}

CoreAdmin.Loader = {
	loader: '<span id="loader"></span>',
	start: function(){
		$('body').prepend(CoreAdmin.Loader.loader);
	},
	stop: function(){
		$('body > #loader').remove();
	}
}

CoreAdmin.Alert = {
	timer: null,
    show: function(message) {
    	clearTimeout(CoreAdmin.Alert.timer);
        $("#flashMessageHtml").html(message);
        $('#flashMessage').removeClass('alert-danger').addClass('alert-success').addClass('show');
        CoreAdmin.Alert.timer = setTimeout(function() {
            $('#flashMessage').removeClass('show');
        }, 5000);
    },
    showError: function(message) {
    	clearTimeout(CoreAdmin.Alert.timer);
        $("#flashMessageHtml").html(message);
        $('#flashMessage').removeClass('alert-success').addClass('alert-danger').addClass('show');
        CoreAdmin.Alert.timer = setTimeout(function() {
            $('#flashMessage').removeClass('show');
        }, 5000);
    },
    navigator: function(message) {

        $("body").prepend('<div id="flashmessageIE" class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button><span class="center">' + message + '</span></div>');
        $('#flashmessageIE').hide().slideDown();
    }
}

$(document).ready(function(){
	CoreAdmin.init();
});