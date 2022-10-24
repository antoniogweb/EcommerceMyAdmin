var ok_aggiorna_prodotti_lista = true;

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

function aggiornaListaProdotti()
{
	var id_lista = $("#id_lista_regalo").text();
	
	var url = baseUrl + "/listeregalo/elencoprodotti/" + id_lista;
	
	$.ajaxQueue({
		url: url,
		async: true,
		cache:false,
		dataType: "html",
		success: function(content){
			
			$(".prodotti-lista-box").html(content);
			
		}
	});
}

function aggiornaQuantitaLista()
{
	ok_aggiorna_prodotti_lista = false;
	
	var products_list = "";
	var curr_item = "";
	var curr_quantity = "";
	
	$(".box_righe_prodotti_lista .lista-riga").each(function(){
		
		curr_item = $(this).attr("id-lista-riga")
		curr_quantity = $(this).find(".item_quantity").val();
		
		products_list += curr_item + ":" + curr_quantity + "|";
	});
	
	var post_data = "products_list="+encodeURIComponent(products_list);
	
	console.log(post_data);
	
	var url  = baseUrl + "/listeregalo/aggiornaprodotti";
	
	$.ajaxQueue({
		url: url,
		type: "POST",
		data: post_data,
		async: true,
		cache:false,
		dataType: "json",
		success: function(content){
			
			if (content.result == "OK")
				aggiornaListaProdotti();
			
			ok_aggiorna_prodotti_lista = true;
		}
	});
}

$(document).ready(function(){
	
	$( "body" ).on( "change", "[name='id_lista_tipo']", function(e) {
		
		sistemaCampiFormLista();
		
	});
	
	$( "body" ).on( "click", ".lista_item_delete_link", function(e) {
		
		e.preventDefault();
		
		var idRiga = $(this).parents(".lista-riga").attr("id-lista-riga");
		
		var url = baseUrl + "/listeregalo/elimina/" + idRiga;
	
		$.ajaxQueue({
			url: url,
			async: true,
			cache:false,
			dataType: "html",
			success: function(content){
				
				aggiornaListaProdotti();
				
			}
		});
		
	});
	
	$( "body" ).on( "change", ".prodotti_lista_item_mobile", function(e) {
		
		aggiornaQuantitaLista();
		
	});
	
	$( "body" ).on( "click", ".prodotti_lista_item_quantity_increase", function(e) {
		
		e.preventDefault();
		
		if (ok_aggiorna_prodotti_lista)
		{
			var t_input = $(this).parents(".box_quantity").find(".item_quantity");
			
			var new_quantity = parseInt(t_input.val()) + 1;
			
			t_input.val(new_quantity);
			
			aggiornaQuantitaLista();
		}
	});
	
	$( "body" ).on( "click", ".prodotti_lista_item_quantity_decrease", function(e) {
		
		e.preventDefault();
		
		if (ok_aggiorna_prodotti_lista)
		{
			var t_input = $(this).parents(".box_quantity").find(".item_quantity");
			
			var t_current_quantity = parseInt(t_input.val());
			
			if (t_current_quantity > 1)
			{
				t_input.val( t_current_quantity - 1) ;
				
				aggiornaQuantitaLista();
			}
		}
	});
	
	$( "body" ).on( "click", ".aggiungi_alla_lista", function(e) {
		
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
	
	$( "body" ).on( "click", ".tab_lista a", function(e) {
		
		e.preventDefault();
		
		var id = $(this).attr("href");
		
		$(".tab_lista > li").removeClass("uk-active");
		$(".tab_lista_box > div").addClass("uk-hidden");
		$(id).removeClass("uk-hidden");
		$(this).parent().addClass("uk-active");
	});
	
	
	$( "body" ).on( "click", ".aggiungi_al_carrello_lista", function(e) {
		
		e.preventDefault();
		
		actionAggiungiAlCarrello($(this));
	});
	
});
