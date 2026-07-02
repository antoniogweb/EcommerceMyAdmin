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
			var mpn = $(this).find("[name='mpn']").val();
			
			var temp = {
				id_articolo: id_articolo,
				codice: codice,
				gtin: gtin,
				mpn: mpn
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
		var that = $(this);
		
		$.ajaxQueue({
			url: baseUrl + "/" + urlCombinazione + "/1?esporta_json&formato_json=select2&acquistabile=tutti&id_articolo_comb=" + idArticolo,
			cache:false,
			async: true,
			dataType: "json",
			success: function(content){
				
				var selectCombinazione = that.closest("form").find(".select_combinazione_ordine_acquisto");
				
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
					
					$(".save_righe_ordini_acquisto").trigger("click");
					
				}
			});
		}
		else
			alert("Attenzione, si prega di selezionare un articolo");
	});
	
	$( "body" ).on( "change", ".select_ordine_acquisto_da_ricevere", function(e){
		
		var idOrdine = $(this).val();
		var urlRigaOrdineAcquisto = $(this).attr("url-riga-ordine-acquisto");
		var that = $(this);
		
		$.ajaxQueue({
			url: baseUrl + "/" + urlRigaOrdineAcquisto + "/1?esporta_json&formato_json=select2&da_ricevere=D&id_oar=" + idOrdine,
			cache:false,
			async: true,
			dataType: "json",
			success: function(content){
				
				var selectCombinazione = that.closest("form").find(".select_riga_ordine_acquisto_da_ricevere");
				
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
	
	$( "body" ).on( "click", ".aggiungi_riga_a_ordine_acquisto_ricezione", function(e){
		
		e.preventDefault();
		
		var id_riga = $(".select_riga_ordine_acquisto_da_ricevere").val();
		var urlAggiungi = $(this).attr("url-aggiungi");
		
		if (id_riga != 0 && id_riga != "")
		{
			makeSpinner($(this));
			
			var idOrdine = $(".form_inserisci_riga").attr("id-ordine"); 
			
			$.ajaxQueue({
				url: baseUrl + "/" + urlAggiungi + "?id_ordine_acquisto_ricezione=" + idOrdine,
				cache:false,
				async: true,
				dataType: "html",
				type: "POST",
				data: {
					bulkActionValues: id_riga,
					bulkAction: "aggiungiaricezione",
					ajax_no_return_html: "Y"
				},
				success: function(content){
					
					$(".save_righe_ordini_acquisto_ricezione").trigger("click");
					
				}
			});
		}
		else
			alert("Attenzione, si prega di selezionare una riga");
	});
	
	$( "body" ).on( "click", ".aggiungi_ordine_acquisto_a_ordine_acquisto_ricezione", function(e){
		
		e.preventDefault();
		
		var id_ordine = $(".select_ordine_acquisto_da_ricevere").val();
		var urlAggiungi = $(this).attr("url-aggiungi");
		
		if (id_ordine != 0 && id_ordine != "")
		{
			makeSpinner($(this));
			
			var idOrdine = $(".form_inserisci_riga").attr("id-ordine"); 
			
			$.ajaxQueue({
				url: baseUrl + "/" + urlAggiungi + "?id_ordine_acquisto_ricezione=" + idOrdine,
				cache:false,
				async: true,
				dataType: "html",
				type: "POST",
				data: {
					bulkActionValues: id_ordine,
					bulkAction: "aggiungiaricezione",
					ajax_no_return_html: "Y"
				},
				success: function(content){
					
					$(".save_righe_ordini_acquisto_ricezione").trigger("click");
					
				}
			});
		}
		else
			alert("Attenzione, si prega di selezionare una riga");
	});
	
	$("body").on("click", ".save_righe_ordini_acquisto_ricezione", function(e){
		
		e.preventDefault();
		
		var idOrdine = $(this).attr("id-ordine");
		var urlSalva = $(this).attr("url-salva");
		
		var that = $(this);
		
		that.find("i").removeClass("fa-save").addClass("fa-spinner").addClass("fa-spin");
		
		var valori = [];
		
		$("table tr.listRow").each(function() {
			
			var id_ordine_acquisto_ricezione_riga = $(this).find("[name='ordini_acquisto_ricezioni_righe_id_ordine_acquisto_ricezione_riga']").attr("data-primary-key");
			
			var quantita = 1;
			
			if ($(this).find("[name='quantita']").length > 0)
				quantita = $(this).find("[name='quantita']").val();
			
			var temp = {
				id_ordine_acquisto_ricezione_riga: id_ordine_acquisto_ricezione_riga,
				quantita: quantita,
			};
			
			valori.push(temp);
		});
		
// 		console.log(valori);
		
		$.ajaxQueue({
			url: baseUrl + "/" + urlSalva,
			cache:false,
			async: true,
			dataType: "html",
			type: "POST",
			data: {
				csrf: csrf_token,
				valori: JSON.stringify(valori)
			},
			success: function(content) {
				
				aggiornaParziale(applicationControllerAction + "/" + idOrdine + viewStatus + "&ajax_partial_load");
				
			}
		});
		
	});
	
	$("body").on("click", ".collega_righe_ordini_acquisto", function(e){
		
		e.preventDefault();
		
		var url = baseUrl + "/" + applicationName + controllerName + "/collega";
		
		var that = $(this);
		
		that.find("i").removeClass("fa-save").addClass("fa-spinner").addClass("fa-spin");
		
		var valori = [];
		
		$("table tr.listRow").each(function() {
			
			if ($(this).find(".form_associa").length > 0)
			{
				var id_riga = $(this).find(".form_associa").attr("id-riga");
				var id_articolo = $(this).find(".select_combinazione_ordine_acquisto").val();
				
				var temp = {
					id_riga: id_riga,
					id_articolo: id_articolo
				};
				
				valori.push(temp);
			}
		});
		
		$.ajaxQueue({
			url: url,
			cache:false,
			async: true,
			dataType: "html",
			type: "POST",
			data: {
				csrf: csrf_token,
				valori: JSON.stringify(valori)
			},
			success: function(content){
				
				aggiornaParziale(applicationControllerAction + viewStatus + "&ajax_partial_load");
				
			}
		});
		
	});
	
	$("body").on("click", ".save_righe_ordini_acquisto", function(e){
		
		e.preventDefault();
		
		var idOrdine = $(this).attr("id-ordine");
		var urlSalva = $(this).attr("url-salva");
		
		var that = $(this);
		
		that.find("i").removeClass("fa-save").addClass("fa-spinner").addClass("fa-spin");
		
		var valori = [];
		
		$("table tr.listRow").each(function() {
			
			var id_ordine_acquisto_riga = $(this).find("[name='ordini_acquisto_righe_id_ordine_acquisto_riga']").attr("data-primary-key");
			
			var quantita = 1;
			var prezzo = 0;
			var titolo = "";
			var id_articolo = 0;
			var codice = "";
			var sconto_1 = 0;
			var sconto_2 = 0;
			var omaggio = 0;
			var id_r = 0;
			
			if ($(this).find("[name='quantita']").length > 0)
				quantita = $(this).find("[name='quantita']").val();
			
			if ($(this).find("[name='prezzo']").length > 0)
				prezzo = $(this).find("[name='prezzo']").val();
			
			if ($(this).find("[name='titolo']").length > 0)
				titolo = $(this).find("[name='titolo']").val();
			
			if ($(this).find("[name='id_articolo']").length > 0)
				id_articolo = $(this).find("[name='id_articolo']").val();
			
			if ($(this).find("[name='codice']").length > 0)
				codice = $(this).find("[name='codice']").val();
			
			if ($(this).find("[name='sconto_1']").length > 0)
				sconto_1 = $(this).find("[name='sconto_1']").val();
			
			if ($(this).find("[name='sconto_2']").length > 0)
				sconto_2 = $(this).find("[name='sconto_2']").val();
			
			if ($(this).find("[name='omaggio']").length > 0)
				omaggio = $(this).find("[name='omaggio']").is(":checked") ? 1 : 0;
			
			if ($(this).find("[name='id_r']").length > 0)
				id_r = $(this).find("[name='id_r']").val();
			
			var temp = {
				id_ordine_acquisto_riga: id_ordine_acquisto_riga,
				quantita: quantita,
				prezzo: prezzo,
				titolo: titolo,
				id_articolo: id_articolo,
				codice: codice,
				sconto_1: sconto_1,
				sconto_2: sconto_2,
				omaggio: omaggio,
				id_r: id_r
			};
			
			valori.push(temp);
		});
		
// 		console.log(valori);
		
		$.ajaxQueue({
			url: baseUrl + "/" + urlSalva,
			cache:false,
			async: true,
			dataType: "html",
			type: "POST",
			data: {
				csrf: csrf_token,
				valori: JSON.stringify(valori)
			},
			success: function(content) {
				
				aggiornaParziale(applicationControllerAction + "/" + idOrdine + viewStatus + "&ajax_partial_load");
				
			}
		});
		
	});
	
	$( "body" ).on( "change", ".select_attributo_ordine_acquisto_offline, .select_id_r_riga_acquisto", function(e){
		
		$(".save_righe_ordini_acquisto").trigger("click");
		
	});
	
	$( "body" ).on( "click", ".aggiungi_riga_tipologia_ordine_acquisto", function(e){
		
		e.preventDefault();
		
		var idOrdine = $(this).attr("id-ordine"); 
		var idRigaTipologia = $(this).attr("id-riga-tipologia"); 
		var url = $(this).attr("href"); 
		
		$.ajaxQueue({
			url: url,
			cache:false,
			async: true,
			dataType: "html",
			type: "POST",
			data: {
				id_ordine_acquisto: idOrdine,
				id_ordine_acquisto_riga_tipologia: idRigaTipologia,
				insertAction: "Y"
			},
			success: function(content){
				
				$(".save_righe_ordini_acquisto").trigger("click");
				
			}
		});
	});
});