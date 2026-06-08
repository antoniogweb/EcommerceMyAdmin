$(document).ready(function(){
	$(".scaffold_form [name='id_fornitore']").change(function(){
		
		var id_fornitore = $(this).val();
		
		var url = baseUrl + "/" + applicationName + controllerName + "/" + actionName + "/insert/0?id_form_fornitore=" + id_fornitore;
		
		location.href = url;
	});
	
	$("body").on("click", ".save_articoli", function(e){
		
		e.preventDefault();
		
		var that = $(this);
		
		that.find("i").removeClass("fa-refresh").addClass("fa-spinner").addClass("fa-spin");
		
		var valori = [];
		
		$("table tr.listRow").each(function(){
			
			var id_articolo = $(this).find("[name='codice']").attr("id-articolo");
			var codice = $(this).find("[name='codice']").val();
			var gtin = $(this).find("[name='gtin']").val();
			
			var temp = {
				id_articolo: id_articolo,
				codice: codice,
				gtin: gtin
			};
			
			valori.push(temp);
		});
		
		// console.log(valori);
		
		$.ajaxQueue({
			url: baseUrl + "/" + applicationName + controllerName + "/salva",
			cache:false,
			async: true,
			dataType: "json",
			type: "POST",
			data: {
				csrf: csrf_token,
				valori: JSON.stringify(valori)
			},
			success: function(content){
				
				that.find("i").removeClass("fa-spin").removeClass("fa-spinner").addClass("fa-refresh");
				
				$(".class_combinazione").css("background-color", "#FFF").css("color", "#555");
				
				if (content.length > 0)
				{
					alert("ATTENZIONE: le righe evidenziate in rosso non sono state aggiornate perché il salvataggio è andato in errore");
					
					for (var i = 0; i < content.length; i++)
					{
						$(".class_combinazione_" + content[i]).css("background-color", "red").css("color", "#FFF");
						
						// $(".class_combinazione_" + content[i]).closest("tr.listRow").removeClass("classe_riga_listino_modificato");
					}
				}
			}
		});
		
	});
	
	$( "body" ).on( "change", ".select_articolo_ordine_acquisto", function(e){
		
		var idArticolo = $(this).val();
		var urlCombinazione = $(this).attr("url-combinazione");
		
		$.ajaxQueue({
			url: baseUrl + "/" + urlCombinazione + "/1?esporta_json&formato_json=select2&acquistabile=tutti&id_articolo_comb=" + idArticolo,
			cache:false,
			async: true,
			dataType: "json",
			success: function(content){
				
				var selectCombinazione = $(".select_combinazione_ordine_acquisto");
				
				selectCombinazione.find('option').remove();
				
				var res = content.results;
				
				for (var i =0; i < res.length; i++)
				{
					selectCombinazione.append("<option value='" + res[i].id + "'>" + res[i].text + "</option>");
				}
				
				selectCombinazione.select2("destroy");
				selectCombinazione.select2();
			}
		});
	});
	
	$( "body" ).on( "click", ".aggiungi_articolo_a_ordine_acquisto", function(e){
		
		e.preventDefault();
		
		var id_articolo = $(".select_combinazione_ordine_acquisto").val();
		var urlAggiungi = $(this).attr("url-aggiungi");
		
		if (id_articolo != 0 && id_articolo != "")
		{
			makeSpinner($(this));
			
			var idOrdine = $(".form_inserisci_articolo").attr("id-ordine"); 
			
			$.ajaxQueue({
				url: baseUrl + "/" + urlAggiungi + "?id_ordine_acquisto=" + idOrdine,
				cache:false,
				async: true,
				dataType: "html",
				type: "POST",
				data: {
					bulkActionValues: id_articolo,
					bulkAction: "aggiungiaordine",
					ajax_no_return_html: "Y"
				},
				success: function(content){
					
					// $(".save_righe_ordini").trigger("click");
// 					aggiornaParziale(applicationControllerAction + "/" + idOrdine + "?ajax_partial_load");
// 					reloadPage();
					
				}
			});
		}
		else
			alert("Attenzione, si prega di selezionare un articolo");
	});
});
