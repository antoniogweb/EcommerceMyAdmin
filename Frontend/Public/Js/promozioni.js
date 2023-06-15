function aggiornaListaInvii()
{
	var id_p = $("#id_promo").text();
	
	var url = baseUrl + "/promozioni/elencoinvii/" + id_p;
	
	$.ajaxQueue({
		url: url,
		async: true,
		cache:false,
		dataType: "html",
		success: function(content){
			
			$(".box_elenco_codici_inviati").html(content);
			
			nascondiTendinaCaricamento();
		}
	});
}

$(document).ready(function(){
	
	$( "body" ).on( "click", ".tab_lista a", function(e) {
		
		e.preventDefault();
		
		var id = $(this).attr("href");
		
		$(".tab_lista > li").removeClass("uk-active");
		$(".tab_lista_box > div").addClass("uk-hidden");
		$(id).removeClass("uk-hidden");
		$(this).parent().addClass("uk-active");
	});
	
	$(".link_ordini").trigger("click");
	
	$( "body" ).on( "submit", ".form_invia_link", function(e) {
		
		e.preventDefault();
		
		var url = $(this).attr("action");
		var valori = $(this).serialize();
		
		var that = $(this);
		
		$.ajaxQueue({
			url: url,
			async: true,
			cache:false,
			type: "POST",
			data: valori,
			dataType: "json",
			success: function(content){
				
				$(".invia_link_notice").html(content.errore);
				
				evidenziaErrori(true, that);
				
				if (content.result == "OK")
				{
					$(".form_invia_link [name='nome']").val("");
					$(".form_invia_link [name='cognome']").val("");
					$(".form_invia_link [name='email']").val("");
					
					aggiornaListaInvii();
				}
				
				setTimeout(function(){
					$(".btn_submit_form").removeClass("uk-hidden").parent().find(".spinner").addClass("uk-hidden");
				}, 300);
			}
		});
	});
	
	$( "body" ).on( "click", ".invia_nuovamente_codice", function(e) {
		
		e.preventDefault();
		
		var url = $(this).attr("href");
		
		$.ajaxQueue({
			url: url,
			async: true,
			cache:false,
			dataType: "json",
			success: function(content){
				
				alert(content.errore);
				
				aggiornaListaInvii();
				
				setTimeout(function(){
					$(".btn_submit_form").removeClass("uk-hidden").parent().find(".spinner").addClass("uk-hidden");
				}, 300);
			}
		});
		
	});
});
