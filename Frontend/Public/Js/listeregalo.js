if (typeof stringa_errore_lista_non_selezionata == "undefined")
	var stringa_errore_lista_non_selezionata = "Si prega di selezionare una lista regalo";

function sistemaCampiFormLista()
{
	$(".form_lista_regalo").find(".btn_submit_form").attr("name","gAction");
	$(".form_lista_regalo").submit();
}

function attivaPulsanteAggiungiAllaLista()
{
	$(".pulsante_lista").addClass("aggiungi_alla_lista").removeClass("disabled");
}

function disattivaPulsanteAggiungiAllaLista()
{
	$(".pulsante_lista").removeClass("aggiungi_alla_lista").addClass("disabled");
}

function beforeAggiungiAllaLista()
{
	$(".pulsante_lista").addClass("uk-hidden").parent().find(".spinner").removeClass("uk-hidden");
}

function aftereAggiungiAllaLista()
{
	setTimeout(function(){
		$(".pulsante_lista").removeClass("uk-hidden").parent().find(".spinner").addClass("uk-hidden");
	}, 500);
}

$(document).ready(function(){
	
	$( "body" ).on( "change", "[name='id_lista_tipo']", function(e) {
		
		sistemaCampiFormLista();
		
	});
	
	$( "body" ).on( "click", ".pulsante_lista", function(e) {
		
		e.preventDefault();
		
	});
	
	$( "body" ).on( "click", ".aggiungi_alla_lista", function(e) {
		
		e.preventDefault();
		
		var id_lista = $("[name='id_lista']").val();
		var id_page = $(this).attr("id-page");
		var id_c = $(this).attr("id-c");
		var quantity = 1;
		
		if ($(".quantita_input").length > 0)
			quantity = $(".quantita_input").val();

		if (quantity == "")
			quantity = 1;
		
		if ($(".accessorio_principale .id_combinazione").length > 0)
			id_c = $(".accessorio_principale .id_combinazione").text();
		
		var url = baseUrl + "/listeregalo/aggiungi/" + id_lista + "/" + id_page + "/" + id_c + "/" + quantity;
		
		if (id_lista != 0)
		{
			beforeAggiungiAllaLista();
			
			$.ajaxQueue({
				url: url,
				async: true,
				cache:false,
				dataType: "json",
				success: function(content){
					
					if (jQuery.trim(content.result) == "OK")
					{
						UIkit.modal("#modale-aggiunto-alla-lista").show();
					}
					else
					{
						alert(content.errore);
					}
					
					aftereAggiungiAllaLista();
				}
			});
		}
		else
		{
			alert(stringa_errore_lista_non_selezionata);
		}
	});
	
});
