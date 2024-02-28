if (typeof stringa_errore_prodotto_non_selezionata == "undefined")
	var stringa_errore_prodotto_non_selezionata = "Si prega di selezionare un prodotto";

function reloadTicket()
{
	if ($("#tendina_caricamento").length > 0)
		$("#tendina_caricamento").removeClass("uk-hidden");
	
	$(".hidden_ticket_submit_action").attr("name","gAction");
	$(".form_ticket").submit();
}

$(document).ready(function(){
	$( "body" ).on( "change", "[name='id_ticket_tipologia'],[name='id_o'],[name='id_lista_regalo']", function(e) {
		
		reloadTicket();
		
	});
	
	$( "body" ).on( "click", ".aggiungi_al_ticket", function(e) {
		
		e.preventDefault();
		
		var id_page = $("[name='id_page']").val();
		
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
					id_page: id_page
				},
				success: function(content){
					
					reloadTicket();
					
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
				
				reloadTicket();
				
			}
		});
		
	});
});
