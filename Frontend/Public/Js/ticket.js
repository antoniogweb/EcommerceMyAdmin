if (typeof stringa_errore_prodotto_non_selezionata == "undefined")
	var stringa_errore_prodotto_non_selezionata = "Si prega di selezionare un prodotto";

if (typeof stringa_errore_file_non_selezionata == "undefined")
	var stringa_errore_file_non_selezionata = "Si prega di selezionare un file";

function reloadFormTicket()
{
	if ($("#tendina_caricamento").length > 0)
		$("#tendina_caricamento").removeClass("uk-hidden");
	
	$(".hidden_ticket_submit_action").attr("name","gAction");
	$(".form_ticket").submit();
}

function salvaBozza()
{
	var idTicket = $(".form_ticket").attr("id-ticket");
	var ticketUid = $(".form_ticket").attr("ticket-uid");
	
	var url = baseUrl + "/ticket/salvabozza/" + idTicket + "/" + ticketUid;
	
	$.ajaxQueue({
		url: url,
		async: true,
		cache:false,
		dataType: "html",
		type: "POST",
		data: $('.form_ticket').serialize(),
		success: function(content){

		}
	});
}

function reloadProdotti()
{
	if ($("#tendina_caricamento").length > 0)
		$("#tendina_caricamento").removeClass("uk-hidden");
	
	var idTicket = $(".form_ticket").attr("id-ticket");
	var ticketUid = $(".form_ticket").attr("ticket-uid");
	
	var url = baseUrl + "/ticket/view/" + idTicket + "/" + ticketUid + "?partial_prodotti";
	
	var id_o = $("[name='id_o']").length > 0 ? $("[name='id_o']").val() : 0;
	var id_lista_regalo = $("[name='id_lista_regalo']").length > 0 ? $("[name='id_lista_regalo']").val() : 0;
	var id_ticket_tipologia = $("[name='id_ticket_tipologia']").val();
	
	$.ajaxQueue({
		url: url,
		async: true,
		cache:false,
		dataType: "html",
		type: "POST",
		data: {
			id_ticket_tipologia: id_ticket_tipologia,
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

function reloadTicket()
{
	if ($("#tendina_caricamento").length > 0)
		$("#tendina_caricamento").removeClass("uk-hidden");
	
	var url = baseUrl + "/ticket/view/" + idTicket + "/" + ticketUid + "?partial_view";
	
	$.ajaxQueue({
		url: url,
		async: true,
		cache:false,
		dataType: "html",
		success: function(content){
			
			$(".view_partial").html(content);
			
			if ($("#tendina_caricamento").length > 0)
				$("#tendina_caricamento").addClass("uk-hidden");
		}
	});
}

function reloadImmagini(tipo)
{
	if ($("#tendina_caricamento").length > 0)
		$("#tendina_caricamento").removeClass("uk-hidden");
	
	var url = baseUrl + "/ticket/immagini/" + idTicket +  "/" + ticketUid + "/" + tipo;
	
	$.ajaxQueue({
		url: url,
		async: true,
		cache:false,
		dataType: "html",
		success: function(content){
			
			$(".box_immagini_" + tipo).html(content);
			
			if ($("#tendina_caricamento").length > 0)
				$("#tendina_caricamento").addClass("uk-hidden");
		}
	});
}

$(document).ready(function(){
	$( "body" ).on( "change", "[name='id_ticket_tipologia'],[name='id_o'],[name='id_lista_regalo']", function(e) {
		reloadFormTicket();
	});
	
	$( "body" ).on( "change", "[name='id_ticket_tipologia'],[name='id_o'],[name='id_lista_regalo'],[name='oggetto'],[name='descrizione']", function(e) {
		salvaBozza();
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
	
	$( "body" ).on( "click", ".aggiungi_messaggio_al_ticket", function(e) {
		
		e.preventDefault();
		
		var url = $(".form_messaggio_ticket").attr("action");
		
		var that = $(this);
		
		$.ajaxQueue({
			url: url,
			async: true,
			cache:false,
			dataType: "html",
			type: "POST",
			data:  new FormData($(".form_messaggio_ticket")[0]),
			processData:false,
			contentType: false, 
// 			data:  $(".form_messaggio_ticket").serialize(),
			success: function(content){
				
				if (content != "OK")
				{
					$(".notice_messaggio").html(content);
					evidenziaErrori(true, $(".form_messaggio_ticket"));
					
					that.removeClass("uk-hidden").parent().find(".spinner").addClass("uk-hidden");
				}
				else
					reloadTicket();
			},
			error: function(){
				$(".notice_messaggio").html("Errore, si prega di riprovare");
				evidenziaErrori(true, $(".form_messaggio_ticket"));
				
				that.removeClass("uk-hidden").parent().find(".spinner").addClass("uk-hidden");
			}
		});
	});
	
	$( "body" ).on( "click", ".upload_immagine_ticket", function(e) {
		
		e.preventDefault();
		
		var that = $(this);
		that.addClass("uk-hidden").parent().find(".spinner").removeClass("uk-hidden");
		
		var box = $(this).closest(".upload_ticket_box");
		var fileObj = box.find("[type='file']");
		
		var tipo = fileObj.attr("name");
		var fileName = fileObj[0].files[0]; 
		
		var progressBar = box.find(".js-progressbar")
		var boxLabelElaborazione = box.find(".box-label-elaborazione")
		
		if (typeof fileName != 'undefined')
		{
			var url = baseUrl + "/ticket/upload/" + idTicket + "/" + ticketUid + "/" + tipo;
			
			var fd = new FormData(); 
			fd.append("filename", fileName); 
			
			$.ajaxQueue({
				url: url,
				async: true,
				cache:false,
				dataType: "html",
				type: "POST",
				data: fd, 
				contentType: false, 
				processData: false,
				beforeSend: function() {
					box.find(".upload_ticket_alert").html("");
					
					progressBar.removeClass("uk-hidden");
					progressBar.attr("value", 0);
					
					boxLabelElaborazione.addClass("uk-hidden");
				},
				xhr: function() {
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener("progress", function(evt) {
						if (evt.lengthComputable) {
							var percentComplete = ((evt.loaded / evt.total) * 100);
							
							progressBar.attr("value", percentComplete);
							
							if (percentComplete >= 100)
								boxLabelElaborazione.removeClass("uk-hidden");
						}
					}, false);
					return xhr;
				},
				success: function(content){
					
					if (content != "OK")
					{
						box.find(".upload_ticket_alert").html(content);
						
						that.removeClass("uk-hidden").parent().find(".spinner").addClass("uk-hidden");
					}
					else
						reloadImmagini(tipo);
					
					boxLabelElaborazione.addClass("uk-hidden");
				},
				error: function(){
					box.find(".upload_ticket_alert").html("Errore, si prega di riprovare");
						
					that.removeClass("uk-hidden").parent().find(".spinner").addClass("uk-hidden");
					
					boxLabelElaborazione.addClass("uk-hidden");
				}
			});
		}
		else
		{
			alert(stringa_errore_file_non_selezionata);
			
			that.removeClass("uk-hidden").parent().find(".spinner").addClass("uk-hidden");
		}
	});
	
	$( "body" ).on( "click", ".elimina_immagine_ticket", function(e) {
		
		e.preventDefault();
		
		var url = $(this).attr("href");
		var tipo = $(this).attr("tipo");
		
		$.ajaxQueue({
			url: url,
			async: true,
			cache:false,
			dataType: "html",
			success: function(content){
				reloadImmagini(tipo);
			}
		});
	});
});
