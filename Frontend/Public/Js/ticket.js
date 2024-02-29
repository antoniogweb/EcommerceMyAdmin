if (typeof stringa_errore_prodotto_non_selezionata == "undefined")
	var stringa_errore_prodotto_non_selezionata = "Si prega di selezionare un prodotto";

function reloadTicket()
{
	if ($("#tendina_caricamento").length > 0)
		$("#tendina_caricamento").removeClass("uk-hidden");
	
	$(".hidden_ticket_submit_action").attr("name","gAction");
	$(".form_ticket").submit();
}

function reloadProdotti()
{
	if ($("#tendina_caricamento").length > 0)
		$("#tendina_caricamento").removeClass("uk-hidden");
	
	var idTicket = $(".form_ticket").attr("id-ticket");
	var ticketUid = $(".form_ticket").attr("ticket-uid");
	
	var url = baseUrl + "/ticket/view/" + idTicket + "/" + ticketUid + "?partial_prodotti"
	
	var id_o = $("[name='id_o']").length > 0 ? $("[name='id_o']").val() : 0;
	var id_lista_regalo = $("[name='id_lista_regalo']").length > 0 ? $("[name='id_lista_regalo']").val() : 0;
	
	$.ajaxQueue({
		url: url,
		async: true,
		cache:false,
		dataType: "html",
		type: "POST",
		data: {
			id_o: id_o,
			id_lista_regalo: id_lista_regalo
		},
		success: function(content){
			
			$(".box_prodotti").html(content);
			
			if ($("#tendina_caricamento").length > 0)
				$("#tendina_caricamento").addClass("uk-hidden");
			
			$(".uk-alert-danger").remove();
		}
	});
}

$(document).ready(function(){
	$( "body" ).on( "change", "[name='id_ticket_tipologia'],[name='id_o'],[name='id_lista_regalo']", function(e) {
		
		reloadTicket();
		
	});
	
	$( "body" ).on( "click", ".aggiungi_al_ticket", function(e) {
		
		e.preventDefault();
		
		var id_page = $("[name='id_page']").val();
		var numero_seriale = $("[name='numero_seriale']").val();
		
		var url = $(this).attr("href");
		
		if (id_page != 0)
		{
			mostraSpinner($(this));
			
			$.ajaxQueue({
				url: url,
				async: true,
				cache:false,
				dataType: "html",
				type: "POST",
				data: {
					id_page: id_page,
					numero_seriale: numero_seriale
				},
				success: function(content){
					
					reloadProdotti();
					
				}
			});
		}
		else
		{
			alert(stringa_errore_prodotto_non_selezionata);
		}
	});
	
	$( "body" ).on( "click", ".elimina_dal_tiket", function(e) {
		
		e.preventDefault();
		
		var url = $(this).attr("href");
		var id_page = $(this).attr("id-page");
		
		$.ajaxQueue({
			url: url,
			async: true,
			cache:false,
			dataType: "html",
			type: "POST",
			data: {
				id_page: id_page
			},
			success: function(content){
				
				reloadProdotti();
				
			}
		});
		
	});
});
