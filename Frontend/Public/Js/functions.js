if (typeof icheckOptions == "undefined")
	var icheckOptions = {
		checkboxClass: 'icheckbox_minimal',
		radioClass: 'iradio_minimal',
		increaseArea: '20%' // optional
	}

if (typeof input_error_css == "undefined")
	var input_error_css = {"border":"1px solid #ed144b"}

if (typeof input_error_style == "undefined")
	var input_error_style = "";

if (typeof gtm_analytics == "undefined")
	var gtm_analytics = false;

if (typeof attiva_spedizione == "undefined")
	var attiva_spedizione = true;

if (typeof coupon_ajax == "undefined")
	var coupon_ajax = false;

if (typeof codice_fiscale_obbligatorio_solo_se_fattura == "undefined")
	var codice_fiscale_obbligatorio_solo_se_fattura = false;

if (typeof filtro_prezzo_slider == "undefined")
	var filtro_prezzo_slider = false;

if (typeof spesa_pagamento_possibile == "undefined")
	var spesa_pagamento_possibile = true;

if (typeof attiva_icheck == "undefined")
	var attiva_icheck = true;

if (typeof ricarica_pagamenti_quando_cambi_nazione == "undefined")
	var ricarica_pagamenti_quando_cambi_nazione = false;


$ = jQuery;

function isInArray(elemento,haystack)
{
    var count=haystack.length;
    for(var i=0;i<count;i++)
    {
        if(haystack[i]==elemento){return true;}
    }
    return false;
}

function getTipoCliente()
{
	if ($(".radio_cliente").length > 0)
		var tipo_cliente = $(".radio_cliente:checked").val();
	else
		var tipo_cliente = $("[name='tipo_cliente']").val();
	
	return tipo_cliente;
}

function updateFormTipoCliente()
{
	tipo_cliente = getTipoCliente();
	
	if (tipo_cliente == "privato")
	{
		$(".tr_ragione_sociale").addClass("uk-hidden");
		$(".tr_p_iva").addClass("uk-hidden");
		$(".tr_nome").removeClass("uk-hidden");
		$(".tr_cognome").removeClass("uk-hidden");
		$(".blocco_fatturazione_elettronica").addClass("uk-hidden");
		$(".campo_check_fattura").removeClass("uk-hidden");
	}
	else if (tipo_cliente == "libero_professionista")
	{
		$(".tr_ragione_sociale").addClass("uk-hidden");
		$(".tr_p_iva").removeClass("uk-hidden");
		$(".tr_nome").removeClass("uk-hidden");
		$(".tr_cognome").removeClass("uk-hidden");
		$(".blocco_fatturazione_elettronica").removeClass("uk-hidden");
		$(".campo_check_fattura").addClass("uk-hidden");
	}
	else
	{
		$(".tr_ragione_sociale").removeClass("uk-hidden");
		$(".tr_p_iva").removeClass("uk-hidden");
		$(".tr_nome").addClass("uk-hidden");
		$(".tr_cognome").addClass("uk-hidden");
		$(".blocco_fatturazione_elettronica").removeClass("uk-hidden");
		$(".campo_check_fattura").addClass("uk-hidden");
	}
	
	sistemaPIva($("[name='nazione']").val());
	
	controllaCheckFattura();
	
	impostaCorrieriESpeseSpedizione();
}

function updateFormRegistrato()
{
	if ($(".checkbox_registrato").length > 0)
		var registrato = $("[name='registrato']").val();
	else
		var registrato = $(".radio_registrato:checked").val();
	
	if (registrato == "Y")
	{
		$(".table_password").css("display","block");
	}
	else
	{
		$(".table_password").css("display","none");
	}
}

function getIdSpedizione()
{
	if ($(".tendina_scelta_indirizzo").length > 0)
		return $(".tendina_scelta_indirizzo").val();
	else if ($(".radio_spedizione").length > 0)
		return $("[name='id_spedizione']").val();
}

function impostaTipoSpedizione(obj)
{
	if (obj.val() == "Y")
	{
		$(".link_indirizzo_come_fatturazione").css("display","block");
		
		if ($(".blocco_tendina_scelta_indirizzo").length > 0)
			$(".blocco_tendina_scelta_indirizzo").css("display","none");
		
		if ($(".campi_nuovo_indirizzo").length > 0)
			$(".campi_nuovo_indirizzo").css("display","block");
		
		if ($("[name='post_error']").length == 0)
		{
			svuotaCampiSpedizione();
		}
		
		sistemaTendinaProvinciaSpedizione($("[name='nazione_spedizione']").val());
		impostaCorrieriESpeseSpedizione();
	}
	else
	{
		$(".link_indirizzo_come_fatturazione").css("display","none");
		
		if ($(".blocco_tendina_scelta_indirizzo").length > 0)
			$(".blocco_tendina_scelta_indirizzo").css("display","block");
		
		if ($(".campi_nuovo_indirizzo").length > 0 && !$(".campi_nuovo_indirizzo").hasClass("errori_campo_indirizzo"))
			$(".campi_nuovo_indirizzo").css("display","none");
		
		impostaCampiSpedizione(getIdSpedizione());
	}
}

if (typeof svuotaCampiSpedizione !== 'function')
{
	window.svuotaCampiSpedizione = function()
	{
		$("[name='indirizzo_spedizione']").val("");
		$("[name='cap_spedizione']").val("");
		$("[name='citta_spedizione']").val("");
		$("[name='provincia_spedizione']").val("");
		$("[name='dprovincia_spedizione']").val("");
		$("[name='telefono_spedizione']").val("");
		
		if ($("[name='nazione']").length > 0)
			$("[name='nazione_spedizione']").val($("[name='nazione']").val());
		else
			$("[name='nazione_spedizione']").val("IT");
		
		if ($("[name='destinatario_spedizione']").length > 0)
			$("[name='destinatario_spedizione']").val("");
	}
}

if (typeof riempiCampiSpedizione !== 'function')
{
	window.riempiCampiSpedizione = function(content)
	{
		$("[name='indirizzo_spedizione']").val(content.indirizzo_spedizione);
		$("[name='cap_spedizione']").val(content.cap_spedizione);
		$("[name='citta_spedizione']").val(content.citta_spedizione);
		$("[name='provincia_spedizione']").val(content.provincia_spedizione);
		$("[name='dprovincia_spedizione']").val(content.dprovincia_spedizione);
		$("[name='telefono_spedizione']").val(content.telefono_spedizione);
		$("[name='nazione_spedizione']").val(content.nazione_spedizione);
		
		if ($("[name='destinatario_spedizione']").length > 0)
			$("[name='destinatario_spedizione']").val(content.destinatario_spedizione);
	}
}

function impostaCampiSpedizione(id_spedizione)
{
	if ($("[name='post_error']").length > 0)
	{
		sistemaTendinaProvinciaSpedizione($("[name='nazione_spedizione']").val());
		impostaCorrieriESpeseSpedizione();
	}
	else
	{
		$.ajaxQueue({
			url: baseUrl + "/indirizzo-di-spedizione/" + id_spedizione,
			cache:false,
			async: true,
			dataType: "json",
			success: function(content){
				
				if (content)
				{
					riempiCampiSpedizione(content);
					
					sistemaTendinaProvinciaSpedizione($("[name='nazione_spedizione']").val());
					impostaCorrieriESpeseSpedizione();
				}
			}
		});
	}
}

function impostaSpedizioneNonLoggato(obj)
{
	if (!attiva_spedizione)
		return;
	
	if (obj.val() == "Y")
	{
		$(".blocco_spedizione_non_loggato").css("display","none");
	}
	else
	{
		$(".blocco_spedizione_non_loggato").css("display","block");
	}
}

function checkCouponAttivo()
{
	if ($("#ha-coupon-attivo").length > 0 && $("[name='il_coupon']").length > 0)
		$(".box_coupon").html("");
	else if ($("#ha-coupon-attivo").length <= 0 && $("[name='il_coupon']").length <= 0)
	{
		$.ajaxQueue({
			url: baseUrl + "/ordini/couponattivo",
			cache:false,
			async: true,
			dataType: "html",
			success: function(content){
				$(".box_coupon").html(content);
			}
		});
	}
}

function impostaSpeseSpedizione(id_corriere, nazione)
{
// 	if (!attiva_spedizione)
// 		return;
	
	var tipo_cliente = getTipoCliente();
	
	var email = "";
	
	if ($("[name='email']").length > 0)
		email = $("[name='email']").val();
	
	var pagamento = "bonifico";
	
	if ($("[name='pagamento']").length > 0)
		pagamento = $("[name='pagamento']:checked").val();
	
	$.ajaxQueue({
		url: baseUrl + "/ordini/totale",
		cache:false,
		async: true,
		dataType: "html",
		method: "POST",
		data: {
			id_corriere: id_corriere,
			nazione_spedizione: nazione,
			tipo_cliente: tipo_cliente,
			email: email,
			pagamento: pagamento
		},
		success: function(content){
			
			if (content)
			{
				$(".blocco_totale_merce").html(content);
				
				if ($(".prezzo_bottom").length > 0 && $(".totale_ordine").length > 0)
				{
					$(".prezzo_bottom").text($(".totale_ordine").text());
				}
			}
			
			checkCouponAttivo();
		}
	});
}

function spedizisciFatturazione()
{
	if ($("[name='spedizione_come_fatturazione']").length > 0)
		return $("[name='spedisci_dati_fatturazione']").val();
	else
		return $("[name='spedisci_dati_fatturazione']:checked").val();
}

function getNazione()
{
	// Sempre nella nazione di fatturazione
	if (!attiva_spedizione)
		return $("[name='nazione']").val();
	
	if ($("[name='spedisci_dati_fatturazione']").length > 0)
	{
		if (spedizisciFatturazione() == "Y")
			var nazione = $("[name='nazione']").val();
		else
			var nazione = $("[name='nazione_spedizione']").val();
	}
	else
		var nazione = $("[name='nazione_spedizione']").val();
	
// 	console.log(nazione);
	
	return nazione;
}

var cercaSpeseSpedizione = true;

function impostaCorriereESpese(nazione)
{
	if (attiva_spedizione)
	{
		if ($(".box_corrieri").length > 0)
		{
			var id_corriere = $("[name='id_corriere']:checked").val();
			
			$.ajaxQueue({
				url: baseUrl + "/ordini/corrieri/" + nazione,
				cache:false,
				async: true,
				dataType: "json",
				success: function(content){
					if (!isInArray(id_corriere, content))
						id_corriere = content[0];
					
					if (content.length == 0)
					{
						$(".radio_corriere").each(function(){
							$(this).find("input").iCheck('uncheck');
						});
						
						$(".box_corrieri").css("display","none");
					}
					else
						$(".box_corrieri").css("display","block");
					
					$(".radio_corriere").css("display","none");
					
					for (var i=0;i<content.length;i++)
					{
						$(".radio_corriere.corriere_"+content[i]).css("display","block");
						
						if (id_corriere == content[i])
						{
							cercaSpeseSpedizione = false;
							$(".radio_corriere.corriere_"+content[i]).find("input").iCheck('check');
							cercaSpeseSpedizione = true;
						}
					}
					
					if (id_corriere == "" || id_corriere == undefined)
						id_corriere = 0;
					
					impostaSpeseSpedizione(id_corriere, nazione);
				}
			});
		}
		else
		{
			var nazione = getNazione();
			var id_corriere = $("[name='id_corriere']").val();
			
			impostaSpeseSpedizione(id_corriere, nazione);
		}
	}
	else
	{
		var nazione = getNazione();
		impostaSpeseSpedizione(0, nazione);
	}
}

function impostaCorrieriESpeseSpedizione(ricarica_pagamenti)
{
// 	if (!attiva_spedizione)
// 		return;
	
	if (!cercaSpeseSpedizione)
		return;
	
	var nazione = getNazione();
	
	if (nazione == "" || nazione == undefined)
		return;
	
	if (ricarica_pagamenti_quando_cambi_nazione && ricarica_pagamenti === undefined && $(".bx_pagamenti").length > 0)
	{
		var pagamento = $("[name='pagamento']:checked").val();
		
		$.ajaxQueue({
			url: baseUrl + "/ordini/pagamenti/" + nazione,
			cache:false,
			async: true,
			dataType: "json",
			success: function(content){
				
				// if (!isInArray(pagamento, content))
				// 	pagamento = content[0];
				
				$(".radio_pagamento").each(function(){
					$(this).find("input").iCheck('uncheck');
				});
				
				if (content.length == 0)
					$(".bx_pagamenti").css("display","none");
				else
					$(".bx_pagamenti").css("display","block");
				
				$(".radio_pagamento").css("display","none");
				
				for (var i=0;i<content.length;i++)
				{
					$(".radio_pagamento.radio_pagamento_"+content[i]).css("display","block");
					
					if (pagamento == content[i])
					{
						cercaSpeseSpedizione = false;
						$(".radio_pagamento.radio_pagamento_"+content[i]).find("input").iCheck('check');
						cercaSpeseSpedizione = true;
					}
				}
// 				
// 				if (id_corriere == "" || id_corriere == undefined)
// 					id_corriere = 0;
				
				impostaCorriereESpese(nazione);
			}
		});
	}
	else
		impostaCorriereESpese(nazione);
	
// 	if (attiva_spedizione)
// 	{
// 		if ($(".box_corrieri").length > 0)
// 		{
// 			var id_corriere = $("[name='id_corriere']:checked").val();
// 			
// 			$.ajaxQueue({
// 				url: baseUrl + "/ordini/corrieri/" + nazione,
// 				cache:false,
// 				async: true,
// 				dataType: "json",
// 				success: function(content){
// 					if (!isInArray(id_corriere, content))
// 						id_corriere = content[0];
// 					
// 					if (content.length == 0)
// 					{
// 						$(".radio_corriere").each(function(){
// 							$(this).find("input").iCheck('uncheck');
// 						});
// 						
// 						$(".box_corrieri").css("display","none");
// 					}
// 					else
// 						$(".box_corrieri").css("display","block");
// 					
// 					$(".radio_corriere").css("display","none");
// 					
// 					for (var i=0;i<content.length;i++)
// 					{
// 						$(".radio_corriere.corriere_"+content[i]).css("display","block");
// 						
// 						if (id_corriere == content[i])
// 						{
// 							cercaSpeseSpedizione = false;
// 							$(".radio_corriere.corriere_"+content[i]).find("input").iCheck('check');
// 							cercaSpeseSpedizione = true;
// 						}
// 					}
// 					
// 					if (id_corriere == "" || id_corriere == undefined)
// 						id_corriere = 0;
// 					
// 					impostaSpeseSpedizione(id_corriere, nazione);
// 				}
// 			});
// 		}
// 		else
// 		{
// 			var nazione = getNazione();
// 			var id_corriere = $("[name='id_corriere']").val();
// 			
// 			impostaSpeseSpedizione(id_corriere, nazione);
// 		}
// 	}
// 	else
// 	{
// 		var nazione = getNazione();
// 		impostaSpeseSpedizione(0, nazione);
// 	}
}

function impostaLabelPagamento(obj)
{
	$(".payment_box").css("display","none");
	
	if (obj != undefined)
		obj.parents(".payment_method_box").find(".payment_box").css("display","block");
}

function sistemaPIva(nazione)
{
	if (nazioniConVat.indexOf(nazione) != -1 && getTipoCliente() != "privato")
		$(".box_p_iva").css("display","table-row");
	else
	{
		$(".box_p_iva").css("display","none");
		$("[name='p_iva']").val("");
	}
	
	// Fattura elettronica
	if (nazione == "IT" && getTipoCliente() != "privato")
		$(".blocco_fatturazione_elettronica").css("display","block");
	else
		$(".blocco_fatturazione_elettronica").css("display","none");
}

if (typeof sistemaTendinaProvincia !== 'function')
{
	window.sistemaTendinaProvincia = function(val)
	{
		if (val == "IT")
		{
			$("[name='dprovincia']").css("display","none");
			$("[name='provincia']").css("display","block");
			$(".nascondi_fuori_italia").css("display","table-row");
		}
		else
		{
			$("[name='dprovincia']").css("display","block");
			$("[name='provincia']").css("display","none");
			$(".nascondi_fuori_italia").css("display","none");
			$("[name='codice_fiscale']").val("");
		}
		
		sistemaPIva(val);
	}
}

if (typeof sistemaTendinaProvinciaSpedizione !== 'function')
{
	window.sistemaTendinaProvinciaSpedizione = function(val)
	{
		if (!attiva_spedizione)
			return;
		
		if (val == "IT")
		{
			$("[name='dprovincia_spedizione']").css("display","none");
			$("[name='provincia_spedizione']").css("display","block");
		}
		else
		{
			$("[name='dprovincia_spedizione']").css("display","block");
			$("[name='provincia_spedizione']").css("display","none");
		}
	}
}

if (typeof evidenziaErrore !== 'function')
{
	window.evidenziaErrore = function(selettore, form)
	{
		var nomeCampo = selettore.substring(7);
		
		if ($(".evidenzia").closest(".box_form_evidenzia").length > 0)
		{
// 			$(selettore).closest(".box_form_evidenzia").remove();
			
			var boxErrori = (typeof form == "undefined") ? $(".evidenzia").closest(".box_form_evidenzia") : form.closest(".box_form_evidenzia");
			
			if (boxErrori.find(selettore).length > 0)
				boxErrori.find(selettore).addClass("uk-form-danger");
			else
				boxErrori.find("[name='" + nomeCampo + "']").addClass("uk-form-danger");
		}
		else
		{
			if ($(selettore).length > 0)
				$(selettore).addClass("uk-form-danger");
			else
				$("[name='" + nomeCampo + "']").addClass("uk-form-danger");
		}
	}
}

function nascondiErrori(form)
{
	var boxErrori = (typeof form == "undefined") ? $(".uk-form-danger") : form.find(".uk-form-danger");
	
	boxErrori.each(function(){
		$(this).removeClass("uk-form-danger");
	});
}

function evidenziaErrori(ajaxSubmit, form)
{
	if (typeof ajaxSubmit != "undefined")
		nascondiErrori(form);
	
	var evidenziaTag = (typeof form == "undefined") ? $(".evidenzia") : form.closest(".box_form_evidenzia").find(".evidenzia");
	
	evidenziaTag.each(function(){
		t_tag = $(this).text();
		
		evidenziaErrore("."+t_tag, form);
	});
}

function controllaCheckFattura()
{
	if (!codice_fiscale_obbligatorio_solo_se_fattura)
		return;
	
	if ((getTipoCliente() != "privato" || $("[name='fattura']").is(":checked")) && $("[name='nazione']").val() == "IT")
		$(".campo_codice_fiscale").css("display","table-row");
	else
		$(".campo_codice_fiscale").css("display","none");
}

function mostraSpinner(obj)
{
	obj.addClass("uk-hidden").parent().find(".spinner").removeClass("uk-hidden");
}

function mostraLabelColore()
{
	$(".label_variante_colore").each(function(){
		var that = $(this);
		
		var testoVariante = $(this).closest(".box_attributo_immagine_colore").find(".form_select_attributo option:selected").attr("data-img-title");
		
		that.text(testoVariante);
	});
}

function triggeraRadioSpedizione(obj)
{
	if (obj.val() == 0)
		$('[name="aggiungi_nuovo_indirizzo"]').val("Y");
	else
		$('[name="aggiungi_nuovo_indirizzo"]').val("N");
	
	impostaTipoSpedizione($('[name="aggiungi_nuovo_indirizzo"]'));
	
}

function setBoxSpedizioneSelezionata(obj)
{
	if (obj.closest(".blocco_checkout").find(".spedizione_selezionata").length > 0)
		obj.closest(".blocco_checkout").find(".spedizione_selezionata").removeClass("spedizione_selezionata");
	
	obj.addClass("spedizione_selezionata");
}

function setCampiDaNascondere()
{
	$("[name='nome'],[name='cognome'],[name='email']").closest(".box_entry_dati").addClass("uk-hidden");
}

function mostraNascondiBloccoRegalo()
{
	if ($('[name="regalo"]').val() == 1)
		$(".box_regalo").removeClass("uk-hidden");
	else
		$(".box_regalo").addClass("uk-hidden");
}

function mostraNascondiBloccoNote()
{
	if ($('[name="con_note"]').val() == 1)
		$(".box_note").removeClass("uk-hidden");
	else
		$(".box_note").addClass("uk-hidden");
}

function mostraPosizioneStepCheckout(posizione)
{
	if ($( "[pos='"+posizione+"']" ).length > 0)
	{
		$( "[pos='"+posizione+"']" ).find(".checkout-step").removeClass("uk_badge_meta").addClass("uk-light uk-background-primary");
		$(".nome_step").addClass("uk-hidden");
		$( "[pos='"+posizione+"']" ).find(".nome_step").removeClass("uk-hidden");
	}
}

function nascondiPosizioneStepCheckout(posizione)
{
	$( "[pos='"+posizione+"']" ).find(".checkout-step").removeClass("uk-light uk-background-primary").addClass("uk_badge_meta");
}

function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};

$(document).ready(function(){
	
	$( "body" ).on( "click", ".disabled", function(e) {
		
		e.preventDefault();
		
	});
	
	updateFormRegistrato();
	updateFormTipoCliente();
	
	if ($(".mostra_solo_dati_incompleti").length > 0)
		setCampiDaNascondere();
	
	$('[name="tipo_cliente"]').on('ifChanged', function(event){
		
		if ($(this).is(":checked"))
			updateFormTipoCliente();
		
	});
	
	$('.radio_registrato').on('ifChanged', function(event){
		
		updateFormRegistrato();
		
	});
	
	$("body").on("ifChanged", ".checkbox_registrato", function(e){
		
		if ($(this).is(":checked"))
			$("[name='registrato']").val("N");
		else
			$("[name='registrato']").val("Y");
		
		updateFormRegistrato();
		
	});
	
	$("body").on("click", ".forza_acquisto_anonimo", function(e){
		
		e.preventDefault();
		
		if ($(".checkbox_registrato").length > 0)
			$('.checkbox_registrato').iCheck('check');
		else
			$('.radio_registrato[value="N"]').iCheck('check');
		
		$(".btn_completa_acquisto").trigger("click");
		
		mostraSpinner($(this));
		
	});
	
	$(".show_form_login_checkout").click(function(){
			
		$(".form_login_checkout").css("display","block");
		
		return false;
	});
	
	evidenziaErrori();
	
	$(".not_active_link").click(function(){
	
		return false;
	
	});
	
	$(".close_lightbox,.black_overlay").click(function(){
		
		$(".black_overlay").css("display","none");
		$(".white_content").css("display","none");
		
		return false;
	});
	
	$(".stampa_pagina").click(function(){
		
		window.print();
		
		return false;
	});
	
	if ($("[name='regalo_check']").length > 0)
	{
		mostraNascondiBloccoRegalo();
		
		$("body").on("ifChanged", "[name='regalo_check']", function(e){
			
			if ($(this).is(":checked"))
				$("[name='regalo']").val(1);
			else
				$("[name='regalo']").val(0);
			
			mostraNascondiBloccoRegalo();
			
		});
	}
	
	if ($("[name='con_note_check']").length > 0)
	{
		mostraNascondiBloccoNote();
		
		$("body").on("ifChanged", "[name='con_note_check']", function(e){
			
			if ($(this).is(":checked"))
				$("[name='con_note']").val(1);
			else
				$("[name='con_note']").val(0);
			
			mostraNascondiBloccoNote();
			
		});
	}
	
	
	if ($("[name='spedizione_come_fatturazione']").length > 0)
	{
		if ($("[name='spedisci_dati_fatturazione']").length > 0)
			impostaSpedizioneNonLoggato($("[name='spedisci_dati_fatturazione']"));
		
		$("body").on("ifChanged", "[name='spedizione_come_fatturazione']", function(e){
			
			if ($(this).is(":checked"))
				$("[name='spedisci_dati_fatturazione']").val("Y");
			else
				$("[name='spedisci_dati_fatturazione']").val("N");
			
			impostaSpedizioneNonLoggato($("[name='spedisci_dati_fatturazione']"));
			impostaCorrieriESpeseSpedizione();
		});
	}
	else
	{
		if ($("[name='spedisci_dati_fatturazione']:checked").length > 0)
			impostaSpedizioneNonLoggato($("[name='spedisci_dati_fatturazione']:checked"));
		
		$("body").on("ifChanged", "[name='spedisci_dati_fatturazione']", function(e){
			
			if ($(this).is(":checked"))
			{
				impostaSpedizioneNonLoggato($(this));
				impostaCorrieriESpeseSpedizione();
			}
		});
	}
	
	$("body").on("click", ".radio_corriere_select, .radio_pagamento_select", function(e){
		
		$(this).find("input").iCheck('check');
		
	});
	
	$("body").on("ifChanged", "[name='id_corriere']", function(e){
		
		if ($(this).is(":checked"))
		{
			setBoxSpedizioneSelezionata($(this).closest(".radio_corriere"));
		}
	});
	
	$("body").on("ifChanged", "[name='pagamento']", function(e){
		
		if ($(this).is(":checked"))
		{
			setBoxSpedizioneSelezionata($(this).closest(".radio_pagamento"));
		}
	});
	
	if ($(".radio_spedizione").length > 0)
	{
		triggeraRadioSpedizione($("[name='id_spedizione']"));
		
		$("body").on("ifChanged", ".radio_spedizione", function(e){
			
			if ($(this).is(":checked"))
			{
				$("[name='post_error']").remove();
				
				$("[name='id_spedizione']").val($(this).val());
				
				setBoxSpedizioneSelezionata($(this).closest(".spedizione_box_select"));
				
				triggeraRadioSpedizione($(this));
			}
		});
		
		$("body").on("click", ".spedizione_box_select", function(e){
			$(this).find("input").iCheck('check');
		});
	}
	else
	{
		if ($("[name='aggiungi_nuovo_indirizzo']:checked").length > 0)
			impostaTipoSpedizione($("[name='aggiungi_nuovo_indirizzo']:checked"));
		
		$("body").on("ifClicked", "[name='aggiungi_nuovo_indirizzo']", function(e){
			
			$("[name='post_error']").remove();
			
			impostaTipoSpedizione($(this));
			
		});
		
		$("body").on("change", ".tendina_scelta_indirizzo", function(e){
			
			$("[name='post_error']").remove();
			
			impostaCampiSpedizione($(this).val());
		});
	}
	
	if ($("[name='id_corriere']:checked").length > 0)
		impostaCorrieriESpeseSpedizione();
	else if ($("[name='id_corriere'][type='hidden']").length > 0)
		impostaCorrieriESpeseSpedizione();
	
	$("body").on("ifChanged", "[name='id_corriere']", function(e){
		
		if ($(this).is(":checked"))
			impostaCorrieriESpeseSpedizione();
		
	});
	
	$("body").on("ifChanged", "[name='pagamento']", function(e){
		
		if (spesa_pagamento_possibile && $(this).is(":checked"))
			impostaCorrieriESpeseSpedizione(false);
		
	});
	
	$("body").on("change", "[name='nazione'],[name='nazione_spedizione'],[name='email']", function(e){
		impostaCorrieriESpeseSpedizione();
	});
	
	impostaLabelPagamento($("[name='pagamento']:checked"));
	
	$("body").on("ifChanged", "[name='pagamento']", function(e){
		
		impostaLabelPagamento($(this));
		
	});
	
	$("body").on("change", ".tendina_ordinamento", function(e){
		
		$(this).parents("form").submit();
		
	});
	
	if ($("[name='nazione']").length > 0)
		sistemaTendinaProvincia($("[name='nazione']").val());
	
	$("body").on("change", "[name='nazione']", function(e){
		
		sistemaTendinaProvincia($(this).val());
		controllaCheckFattura();
		
	});
	
	if ($("[name='nazione_spedizione']").length > 0)
		sistemaTendinaProvinciaSpedizione($("[name='nazione_spedizione']").val());
	
	$("body").on("change", "[name='nazione_spedizione']", function(e){
		
		sistemaTendinaProvinciaSpedizione($(this).val());
		
	});
	
	controllaCheckFattura();
	
	if ($("[name='fattura']").length > 0)
	{
		$("body").on("ifChanged", "[name='fattura']", function(e){
			
			controllaCheckFattura();
		});
	}
	
	$("body").on("click", ".btn_completa_acquisto", function(e){
		
		mostraSpinner($(this));
		
// 		if (gtm_analytics && typeof checkout_items !== "undefined")
// 		{
// 			if (debug_js)
// 				console.log(checkout_items);
// 			
// 			gtag('event', 'checkout_progress', checkout_items);
// 		}
		
	});
	
	$("body").on("click", ".btn_submit_form", function(e){
		
		mostraSpinner($(this));
		
	});
	
	if (attiva_icheck)
		$('input:not(.no_icheck_input)').iCheck(icheckOptions);
	
	mostraLabelColore();
	
	if ($(".image-picker").length > 0)
	{
		$(".image-picker").imagepicker({
			selected: function(select, picker_option, event){
				
				mostraLabelColore();
			}
		});
	}
	
	$("body").on("change", ".select_follow_url", function(e){
		
		e.preventDefault();
		
		var url = $(this).val();
		
		location.href = url;
		
	});
	
	$("body").on("click", ".elimina_coupon", function(e){
		
		e.preventDefault();
		
		var randomCoupon = $(this).attr("data-random");
		
		$.ajaxQueue({
			url: baseUrl + "/carrello/vedi",
			cache:false,
			async: true,
			dataType: "html",
			method: "POST",
			data: {
				invia_coupon: "invia_coupon",
				il_coupon: randomCoupon
			},
			success: function(content){
				
				if ($(".btn_completa_acquisto").length > 0)
					impostaCorrieriESpeseSpedizione();
				else
					aggiornaCarrello(undefined, true);
			}
		});
	});
	
	$("body").on("click", "[name='invia_coupon']", function(e){
		
		if (coupon_ajax && $(".cart_container").length == 0)
		{
			e.preventDefault();
			
			var url = $(this).closest("form").attr("action");
			var il_coupon = $(this).closest("form").find("[name='il_coupon']").val();
			
			var that = $(this);
			
			that.addClass("uk-hidden").parent().find(".spinner").removeClass("uk-hidden");
			
			$.ajaxQueue({
				url: url,
				cache:false,
				async: true,
				dataType: "html",
				method: "POST",
				data: {
					invia_coupon: "invia_coupon",
					il_coupon: il_coupon
				},
				success: function(content){
					
					setTimeout(function(){
						that.removeClass("uk-hidden").parent().find(".spinner").addClass("uk-hidden");
					}, 500);
					
					impostaCorrieriESpeseSpedizione();
					// checkCouponAttivo();
					
				}
			});
		}
	});
	
	$("body").on("submit", "form", function(e){
		
		var form = $(this);
		
		if (form.find("[name='ajaxsubmit']").length > 0)
		{
			var boxEvidenzia = form.closest(".box_form_evidenzia");
			
			if (boxEvidenzia.length > 0 && boxEvidenzia.find(".box_notice").length > 0)
			{
				e.preventDefault();
				
				var url = form.attr("action");
				
				$.ajaxQueue({
					url: url,
					cache:false,
					async: true,
					dataType: "json",
					method: "POST",
					data: form.serialize(),
					success: function(content){
						if ($(".btn_submit_form").length > 0)
						{
							setTimeout(function(){
								$(".btn_submit_form").removeClass("uk-hidden").parent().find(".spinner").addClass("uk-hidden");
							}, 500);
						}
						
						if (content.Body.Esito == "OK")
							boxEvidenzia.find(".box_notice").html(content.Body.Notice);
						else
						{
							if (boxEvidenzia.find(".errori_compilazione_form").length > 0)
								boxEvidenzia.find(".errori_compilazione_form").remove();
							
							boxEvidenzia.find("form").prepend("<div class='errori_compilazione_form'>" + content.Body.Notice + "</div>");
						}
						
						if (content.Body.Esito == "OK")
						{
							form.remove();
						}
						
						evidenziaErrori(true, form);
					}
				});
			}
		}
		
	});
	
	$("body").on("click", ".disattiva_acquisto_lista", function(e){
		e.preventDefault();
		
		$.ajaxQueue({
			url: baseUrl + "/carrello/eliminacookielista",
			cache:false,
			async: true,
			dataType: "html",
			success: function(content){
				if ($(".btn_completa_acquisto").length > 0)
					location.href = baseUrl + "/checkout";
				else
					aggiornaCarrello(undefined, true);
			}
		});
	});
	
	$(".showcoupon").click(function(e){
		e.preventDefault();
		
		$("#coupon").slideToggle();
	});
	
	$(".showlogin").click(function(e){
		e.preventDefault();
		
		$("#login").slideToggle();
	});
	
	if (filtro_prezzo_slider)
	{
		$('.nstSlider').nstSlider({
			"left_grip_selector": ".leftGrip",
			"right_grip_selector": ".rightGrip",
			"value_bar_selector": ".bar",
			"value_changed_callback": function(cause, leftValue, rightValue) {
				leftValue += "€";
				rightValue += "€";
				$(this).parent().find('.leftLabel').text(leftValue);
				$(this).parent().find('.rightLabel').text(rightValue);
			},
			"user_mouseup_callback": function(leftValue, rightValue, left_grip_moved) {
				
				var urlSlider = $(".url_slider_prezzo").text();
				
				urlSlider = urlSlider.replace("[DA]", leftValue);
				urlSlider = urlSlider.replace("[A]", rightValue);
				
				location.href = urlSlider;
			}
		});
		
	}
	
	if ($(".checkout_bottom_bar").length > 0)
	{
		$(window).scroll(function() {
			var offset = $("#fragment-checkout-fatturazione").offset();
			var offset2 = $("#fragment-checkout-conferma").offset();
			
			if($(window).scrollTop() > offset.top && $(window).scrollTop() < (offset2.top - 400)) {
				$( ".checkout_bottom_bar" ).css("bottom", "0px");
			} else {
				$( ".checkout_bottom_bar" ).css("bottom", "-200px");
			}
			
		});
	}
	
	if ($(".checkout-steps-mobile").length > 0)
	{
		$(window).scroll(function() {
			var offset = $("#fragment-checkout-fatturazione").offset();
			
			if ($("#fragment-checkout-spedizione").length > 0)
				var offset2 = $("#fragment-checkout-spedizione").offset();
			
			var offset3 = $("#fragment-checkout-pagamento").offset();
			
			if ($("#fragment-checkout-consegna").length > 0)
				var offset35 = $("#fragment-checkout-consegna").offset();
			
			var offset4 = $("#fragment-checkout-carrello").offset();
			var offset5 = $("#fragment-checkout-conferma").offset();
			
			var y = $(window).scrollTop();
			
			if(y >= 0) {
				mostraPosizioneStepCheckout("fatturazione")
			} else {
				nascondiPosizioneStepCheckout("fatturazione");
			}
			
			if ($("#fragment-checkout-spedizione").length > 0)
			{
				if(y > (offset2.top - 200)) {
					mostraPosizioneStepCheckout("spedizione")
				} else {
					nascondiPosizioneStepCheckout("spedizione");
				}
			}
			
			if ($("#fragment-checkout-pagamento").length > 0)
			{
				if(y > (offset3.top - 200)) {
					mostraPosizioneStepCheckout("pagamento")
				} else {
					nascondiPosizioneStepCheckout("pagamento");
				}
			}
			
			if ($("#fragment-checkout-consegna").length > 0)
			{
				if(y > (offset35.top - 200)) {
					mostraPosizioneStepCheckout("consegna")
				} else {
					nascondiPosizioneStepCheckout("consegna");
				}
			}
			
			if(y > (offset4.top - 200)) {
				mostraPosizioneStepCheckout("carrello")
			} else {
				nascondiPosizioneStepCheckout("carrello");
			}
			
			if(y > (offset5.top - 200)) {
				mostraPosizioneStepCheckout("conferma")
			} else {
				nascondiPosizioneStepCheckout("conferma");
			}
		});
	}

	$( "body" ).on( "click", ".mostra_nascondi_password", function(e){
		e.preventDefault();  //prevent form from submitting

		var box = $(this).parent();

		if ($(this).hasClass("mostra_password"))
		{
			box.find("input[type='password']").attr("type", "text");
			box.find(".mostra_password").addClass("uk-hidden");
			box.find(".nascondi_password").removeClass("uk-hidden");
		}
		else
		{
			box.find("input[type='text']").attr("type", "password");
			box.find(".mostra_password").removeClass("uk-hidden");
			box.find(".nascondi_password").addClass("uk-hidden");
		}
	});
});
