$ = jQuery;

function updateForm()
{
	var tipo_cliente = $(".radio_cliente:checked").val();
	
	if (tipo_cliente == "privato")
	{
		$(".tr_ragione_sociale").css("display","none");
		$(".tr_p_iva").css("display","none");
		$(".tr_nome").css("display","table-row");
		$(".tr_cognome").css("display","table-row");
		$(".blocco_fatturazione_elettronica").css("display","none");
	}
	else if (tipo_cliente == "libero_professionista")
	{
		$(".tr_ragione_sociale").css("display","none");
		$(".tr_p_iva").css("display","table-row");
		$(".tr_nome").css("display","table-row");
		$(".tr_cognome").css("display","table-row");
		$(".blocco_fatturazione_elettronica").css("display","block");
	}
	else
	{
		$(".tr_ragione_sociale").css("display","table-row");
		$(".tr_p_iva").css("display","table-row");
		$(".tr_nome").css("display","none");
		$(".tr_cognome").css("display","none");
		$(".blocco_fatturazione_elettronica").css("display","block");
	}
	
	var registrato = $(".radio_registrato:checked").val();
	
	if (registrato == "Y")
	{
		$(".table_password").css("display","table");
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
		
		if ($("#form .alert").length == 0)
		{
			$("[name='indirizzo_spedizione']").val("");
			$("[name='cap_spedizione']").val("");
			$("[name='citta_spedizione']").val("");
			$("[name='provincia_spedizione']").val("");
			$("[name='telefono_spedizione']").val("");
			$("[name='nazione_spedizione']").val("IT");
		}
		
		sistemaTendinaProvinciaSpedizione($("[name='nazione_spedizione']").val());
	}
	else
	{
		$(".link_indirizzo_come_fatturazione").css("display","none");
		$(".blocco_tendina_scelta_indirizzo").css("display","block");
		impostaCampiSpedizione($(".tendina_scelta_indirizzo").val());
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

function impostaSpeseSpedizione()
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
	
	var id_corriere = $("[name='id_corriere']:checked").val();
	
	console.log(nazione);
	
	$.ajaxQueue({
		url: baseUrl + "/ordini/totale",
		cache:false,
		async: true,
		dataType: "html",
		method: "POST",
		data: {
			id_corriere: id_corriere,
			nazione_spedizione: nazione
		},
		success: function(content){
			
			if (content)
			{
				$(".blocco_totale_merce").html(content);
			}
		}
	});
}

function impostaCampiSpedizione(id_spedizione)
{
	$.ajaxQueue({
		url: baseUrl + "/indirizzo-di-spedizione/" + id_spedizione,
		cache:false,
		async: true,
		dataType: "json",
		success: function(content){
			
			if (content)
			{
				$("[name='indirizzo_spedizione']").val(content.indirizzo_spedizione);
				$("[name='cap_spedizione']").val(content.cap_spedizione);
				$("[name='citta_spedizione']").val(content.citta_spedizione);
				$("[name='provincia_spedizione']").val(content.provincia_spedizione);
				$("[name='telefono_spedizione']").val(content.telefono_spedizione);
				$("[name='nazione_spedizione']").val(content.nazione_spedizione);
				
				sistemaTendinaProvinciaSpedizione($("[name='nazione_spedizione']").val());
			}
		}
	});
}

function impostaLabelPagamento(obj)
{
	$(".payment_box").css("display","none");
	
	if (obj != undefined)
		obj.parents(".payment_method_paypal").find(".payment_box").css("display","block");
}

function sistemaTendinaProvincia(val)
{
	if (val == "IT")
	{
		$("[name='dprovincia']").css("display","none");
		$("[name='provincia']").css("display","block");
	}
	else
	{
		$("[name='dprovincia']").css("display","block");
		$("[name='provincia']").css("display","none");
	}
}

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


$(document).ready(function(){
	
	$( "body" ).on( "click", ".disabled", function(e) {
		
		e.preventDefault();
		
	});
	
	updateForm();

	$('input').on('ifChanged', function(event){
		
		updateForm();
		
	});
	
	$('.radio_registrato').on('ifChanged', function(event){
		
		updateForm();
		
	});
	
	$(".show_form_login_checkout").click(function(){
			
		$(".form_login_checkout").css("display","block");
		
		return false;
	});
	
	$(".evidenzia").each(function(){
		t_tag = $(this).text();
		$("."+t_tag).css("border","1px solid #ed144b");
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
	
// 	$("body").on("click", ".imposta_spedizione_come_fatturazione", function(e){
// 		
// 		e.preventDefault();
// 		
// 		$("[name='indirizzo_spedizione']").val($("[name='indirizzo']").val());
// 		$("[name='cap_spedizione']").val($("[name='cap']").val());
// 		$("[name='citta_spedizione']").val($("[name='citta']").val());
// 		$("[name='provincia_spedizione']").val($("[name='provincia']").val());
// 		$("[name='telefono_spedizione']").val($("[name='telefono']").val());
// 	});
	
	if ($("[name='spedisci_dati_fatturazione']:checked").length > 0)
		impostaSpedizioneNonLoggato($("[name='spedisci_dati_fatturazione']:checked"));
	
	$("body").on("ifChanged", "[name='spedisci_dati_fatturazione']", function(e){
		
		if ($(this).is(":checked"))
		{
			impostaSpedizioneNonLoggato($(this));
			impostaSpeseSpedizione();
		}
	});
	
	if ($("[name='aggiungi_nuovo_indirizzo']:checked").length > 0)
		impostaTipoSpedizione($("[name='aggiungi_nuovo_indirizzo']:checked"));
	
	$("body").on("ifClicked", "[name='aggiungi_nuovo_indirizzo']", function(e){
		
// 		console.log($(this).val());
		
		impostaTipoSpedizione($(this));
		
	});
	
	$("body").on("change", ".tendina_scelta_indirizzo", function(e){
		
		impostaCampiSpedizione($(this).val());
		
	});
	
	if ($("[name='id_corriere']:checked").length > 0)
		impostaSpeseSpedizione();
	
	$("body").on("ifChanged", "[name='id_corriere']", function(e){
		
		if ($(this).is(":checked"))
			impostaSpeseSpedizione();
		
	});
	
	$("body").on("change", "[name='nazione'],[name='nazione_spedizione']", function(e){
		impostaSpeseSpedizione();
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
	
	$("body").on("click", ".site-header-account > a", function(e){
		
		e.preventDefault();
		
		if ($(this).parent().hasClass("account_open"))
		{
			$(this).parent().removeClass("account_open");
			$(this).parent().find(".dropdown-backdrop").remove();
		}
		else
		{
			$(this).parent().addClass("account_open");
			$(this).parent().append('<div class="dropdown-backdrop"></div>');
			
		}
	});
	
	$("body").on("click", ".dropdown-backdrop", function(e){
		
		e.preventDefault();
		
		$(".link_account").trigger("click");
	});
	
	
	$("body").on("click", ".select-selected", function(e){
		
		e.preventDefault();
		
		if ($(this).hasClass("select-arrow-active"))
			$(this).removeClass("select-arrow-active");
		else
			$(this).addClass("select-arrow-active");
		
		if ($(this).parent().find(".select-items").hasClass("select-hide"))
			$(this).parent().find(".select-items").removeClass("select-hide");
		else
			$(this).parent().find(".select-items").addClass("select-hide");
		
	});
	
	$("body").on("click", ".select_ordinamento", function(e){
		
		var o = $(this).attr("o");
		
		$(".tendina_ordinamento").val(o);
		$(".woocommerce-ordering").submit();
	});
	
	$("body").on("click", ".open_close_sub", function(e){
		
		e.preventDefault();
		
		if ($(this).hasClass("fa-chevron-down"))
		{
			$(this).removeClass("fa-chevron-down");
			$(this).addClass("fa-chevron-up");
		}
		else
		{
			$(this).addClass("fa-chevron-down");
			$(this).removeClass("fa-chevron-up");
		}
		
		if ($(this).parent().hasClass("open"))
		{
			$(this).parent().removeClass("open");
			$(this).parent().find(".children").slideUp();
		}
		else
		{
			$(this).parent().addClass("open");
			$(this).parent().find(".children").slideDown();
		}
	});
	
	$(".product-categories li").each(function(){
		
		if ($(this).hasClass("current-cat") && $(this).hasClass("cat-child"))
		{
			$(this).parents(".cat-parent").find(".open_close_sub").trigger("click");
		}
		
	});
});


	$(document).ready(function(){
		$('input').iCheck({
		    checkboxClass: 'icheckbox_minimal',
		    radioClass: 'iradio_minimal',
		    increaseArea: '20%' // optional
		});
	

		$('#nav-icon3').click(function(){
		    $(this).toggleClass('open');
		    $("#overlay, .menu-mobile").fadeToggle();
		    $("html").toggleClass("overflowy");
		 });
	});
