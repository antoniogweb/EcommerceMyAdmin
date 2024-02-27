$(document).ready(function(){
	$( "body" ).on( "change", "[name='id_ticket_tipologia']", function(e) {
		
		if ($("#tendina_caricamento").length > 0)
			$("#tendina_caricamento").removeClass("uk-hidden");
		
		$(".hidden_ticket_submit_action").attr("name","gAction");
		$(".form_ticket").submit();
		
	});
});
