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

$ = jQuery;

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
	}
	else if (tipo_cliente == "libero_professionista")
	{
		$(".tr_ragione_sociale").addClass("uk-hidden");
		$(".tr_p_iva").removeClass("uk-hidden");
		$(".tr_nome").removeClass("uk-hidden");
		$(".tr_cognome").removeClass("uk-hidden");
		$(".blocco_fatturazione_elettronica").removeClass("uk-hidden");
	}
	else
	{
		$(".tr_ragione_sociale").removeClass("uk-hidden");
		$(".tr_p_iva").removeClass("uk-hidden");
		$(".tr_nome").addClass("uk-hidden");
		$(".tr_cognome").addClass("uk-hidden");
		$(".blocco_fatturazione_elettronica").removeClass("uk-hidden");
	}
	
	sistemaPIva($("[name='nazione']").val());
	
	impostaCorrieriESpeseSpedizione();
}

function updateFormRegistrato()
{
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

function impostaTipoSpedizione(obj)
{
	if (obj.val() == "Y")
	{
		$(".link_indirizzo_come_fatturazione").css("display","block");
		$(".blocco_tendina_scelta_indirizzo").css("display","none");
		
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
		$(".blocco_tendina_scelta_indirizzo").css("display","block");
		impostaCampiSpedizione($(".tendina_scelta_indirizzo").val());
	}
}

if (typeof svuotaCampiSpedizione !== 'function')
{
	function svuotaCampiSpedizione()
	{
		$("[name='indirizzo_spedizione']").val("");
		$("[name='cap_spedizione']").val("");
		$("[name='citta_spedizione']").val("");
		$("[name='provincia_spedizione']").val("");
		$("[name='telefono_spedizione']").val("");
		$("[name='nazione_spedizione']").val("IT");
	}
}

if (typeof riempiCampiSpedizione !== 'function')
{
	function riempiCampiSpedizione(content)
	{
		$("[name='indirizzo_spedizione']").val(content.indirizzo_spedizione);
		$("[name='cap_spedizione']").val(content.cap_spedizione);
		$("[name='citta_spedizione']").val(content.citta_spedizione);
		$("[name='provincia_spedizione']").val(content.provincia_spedizione);
		$("[name='dprovincia_spedizione']").val(content.dprovincia_spedizione);
		$("[name='telefono_spedizione']").val(content.telefono_spedizione);
		$("[name='nazione_spedizione']").val(content.nazione_spedizione);
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
	if (obj.val() == "Y")
	{
		$(".blocco_spedizione_non_loggato").css("display","none");
	}
	else
	{
		$(".blocco_spedizione_non_loggato").css("display","block");
	}
}

function impostaSpeseSpedizione(id_corriere, nazione)
{
	var tipo_cliente = getTipoCliente();
	
	$.ajaxQueue({
		url: baseUrl + "/ordini/totale",
		cache:false,
		async: true,
		dataType: "html",
		method: "POST",
		data: {
			id_corriere: id_corriere,
			nazione_spedizione: nazione,
			tipo_cliente: tipo_cliente
		},
		success: function(content){
			
			if (content)
			{
				$(".blocco_totale_merce").html(content);
			}
		}
	});
}

function getNazione()
{
	if ($("[name='spedisci_dati_fatturazione']").length > 0)
	{
		if ($("[name='spedisci_dati_fatturazione']:checked").val() == "Y")
			var nazione = $("[name='nazione']").val();
		else
			var nazione = $("[name='nazione_spedizione']").val();
	}
	else
		var nazione = $("[name='nazione_spedizione']").val();
	
	return nazione;
}

var cercaSpeseSpedizione = true;

function impostaCorrieriESpeseSpedizione()
{
	if (!cercaSpeseSpedizione)
		return;
	
	var nazione = getNazione();
	
	if (nazione == "" || nazione == undefined)
		return;
	
	if ($(".box_corrieri").length > 0)
	{
		var id_corriere = $("[name='id_corriere']:checked").val();
		
		$.ajaxQueue({
			url: baseUrl + "/ordini/corrieri/" + nazione,
			cache:false,
			async: true,
			dataType: "json",
			success: function(content){
				
				if (content.indexOf(id_corriere) == -1)
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

function impostaLabelPagamento(obj)
{
	$(".payment_box").css("display","none");
	
	if (obj != undefined)
		obj.parents(".payment_method_paypal").find(".payment_box").css("display","block");
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
	function sistemaTendinaProvincia(val)
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
	function sistemaTendinaProvinciaSpedizione(val)
	{
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
	function evidenziaErrore(selettore)
	{
		if (input_error_style == "")
			$(selettore).css(input_error_css);
		
		if (input_error_style != "")
			$(selettore).attr("style", input_error_style);
	}
}



$(document).ready(function(){
	
	$( "body" ).on( "click", ".disabled", function(e) {
		
		e.preventDefault();
		
	});
	
	updateFormRegistrato();
	updateFormTipoCliente();
	
	$('[name="tipo_cliente"]').on('ifChanged', function(event){
		
		if ($(this).is(":checked"))
			updateFormTipoCliente();
		
	});
	
	$('.radio_registrato').on('ifChanged', function(event){
		
		updateFormRegistrato();
		
	});
	
	$(".show_form_login_checkout").click(function(){
			
		$(".form_login_checkout").css("display","block");
		
		return false;
	});
	
	$(".evidenzia").each(function(){
		t_tag = $(this).text();
		
		evidenziaErrore("."+t_tag);
	});
	
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
	
	if ($("[name='spedisci_dati_fatturazione']:checked").length > 0)
		impostaSpedizioneNonLoggato($("[name='spedisci_dati_fatturazione']:checked"));
	
	$("body").on("ifChanged", "[name='spedisci_dati_fatturazione']", function(e){
		
		if ($(this).is(":checked"))
		{
			impostaSpedizioneNonLoggato($(this));
			impostaCorrieriESpeseSpedizione();
		}
	});
	
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
	
	if ($("[name='id_corriere']:checked").length > 0)
		impostaCorrieriESpeseSpedizione();
	else if ($("[name='id_corriere'][type='hidden']").length > 0)
		impostaCorrieriESpeseSpedizione();
	
	$("body").on("ifChanged", "[name='id_corriere']", function(e){
		
		if ($(this).is(":checked"))
			impostaCorrieriESpeseSpedizione();
		
	});
	
	$("body").on("change", "[name='nazione'],[name='nazione_spedizione']", function(e){
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
		
	});
	
	if ($("[name='nazione_spedizione']").length > 0)
		sistemaTendinaProvinciaSpedizione($("[name='nazione_spedizione']").val());
	
	$("body").on("change", "[name='nazione_spedizione']", function(e){
		
		sistemaTendinaProvinciaSpedizione($(this).val());
		
	});
	
	$("body").on("click", ".btn_completa_acquisto", function(e){
		
		$(this).addClass("uk-hidden").parent().find(".spinner").removeClass("uk-hidden");
		
	});
	
	$('input').iCheck(icheckOptions);
	
	$(".image-picker").imagepicker();
});
