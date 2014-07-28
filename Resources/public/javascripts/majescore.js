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
	},
	TableToExcel: function(){
		var csv = $(".table").table2CSV({delivery:'value'});
      	window.location.href = 'data:text/csv;charset=UTF-8,'
                            + encodeURIComponent(csv);
	},
	collectionHolder: function(){
		// get the div holding the collection
    	var collectionHolder = $('ul#usertype_userAddresses');

    	// add a link « add an address »
    	var $addTagLink = $('<a href="#" class="btn btn-primary add_link">Add address</a>');
   		var $newLinkLi = $('<li  class="list-group-item text-right"></li>').append($addTagLink);
   		collectionHolder.append($newLinkLi);
		// addTagFormFirst(collectionHolder , $newLinkLi);
    	jQuery(document).ready(function() {
		    
		    // collectionHolder.find('li').each(function() {
		    // 	addTagFormDeleteLink($(this));
	    	// });

		    // ajoute l'ancre « ajouter un tag » et li à la balise ul
		    collectionHolder.append($newLinkLi);

		    

		    $addTagLink.on('click', function(e) {
		        // empêche le lien de créer un « # » dans l'URL
		        e.preventDefault();
				
		        // ajoute un nouveau formulaire tag (voir le prochain bloc de code)
		        addTagForm(collectionHolder , $newLinkLi);

		    });
		});
    	function addTagFormFirst(collectionHolder, $newLinkLi) {
		    // Récupère l'élément ayant l'attribut data-prototype comme expliqué plus tôt
		    var prototype = collectionHolder.attr('data-prototype');

		    // Remplace '__name__' dans le HTML du prototype par un nombre basé sur
		    // la longueur de la collection courante
		    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);
		    var newForm = newForm.replace(/label__/g, '');

		    // Affiche le formulaire dans la page dans un li, avant le lien "ajouter un tag"
		    var $newFormLi = $('<li  class="list-group-item text-right"></li>').append(newForm);
		    $newLinkLi.before($newFormLi);
		    
	    }
	    function addTagForm(collectionHolder, $newLinkLi) {
		    // Récupère l'élément ayant l'attribut data-prototype comme expliqué plus tôt
		    var prototype = collectionHolder.attr('data-prototype');

		    // Remplace '__name__' dans le HTML du prototype par un nombre basé sur
		    // la longueur de la collection courante
		    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);
		    var newForm = newForm.replace(/label__/g, '');

		    // Affiche le formulaire dans la page dans un li, avant le lien "ajouter un tag"
		    var $newFormLi = $('<li  class="list-group-item text-right"></li>').append(newForm);
		    $newLinkLi.before($newFormLi);
		    addTagFormDeleteLink($newFormLi);
		}
		function addTagFormDeleteLink($tagFormLi) {
		    var $removeFormA = $('<a href="#" class="btn btn-default-outline remove_link">Remove address</a>');
		    $tagFormLi.append($removeFormA);

		    $removeFormA.on('click', function(e) {
		        // empêche le lien de créer un « # » dans l'URL
		        e.preventDefault();

		        // supprime l'élément li pour le formulaire de tag
		        $tagFormLi.remove();

		    });
	    }
	},

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