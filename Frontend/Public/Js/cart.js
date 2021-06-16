var colore_carrello_non_vuoto = "green";
var classe_wish_presente = "in_wishlist";
var classe_wish_non_presente = "not_in_wishlist";
var box_pulsante_wishlist = "blocco_wishlist";
var vai_al_checkout = false;

if (typeof variante_non_esistente == "undefined")
	var variante_non_esistente = "Non esiste il prodotto con la combinazioni di varianti selezionate";

if (typeof errore_combinazione == "undefined")
	var errore_combinazione = "Si prega di selezionare la variante:";

if (typeof errore_quantita_minore_zero == "undefined")
	var errore_quantita_minore_zero = "Si prega di indicare una quantità maggiore di zero";

if (typeof errore_selezionare_variante == "undefined")
	var errore_selezionare_variante = "Si prega di selezionare la variante del prodotto";

if (typeof stringa_errore_giacenza_carrello == "undefined")
	var stringa_errore_giacenza_carrello = "Attenzione, controllare la quantità delle righe evidenziate";

if (typeof back_cart_error == "undefined")
	var back_cart_error = "red";

if (typeof input_border_color == "undefined")
	var input_border_color = "#e5e5e5";

if (typeof pixel == "undefined")
	var pixel = false;

if (typeof gtm_analytics == "undefined")
	var gtm_analytics = false;

var time;
var arrayAccessori = [];

function reloadCart()
{
	$.ajax({
		url: baseUrl + "/carrello/partial",
		async: false,
		cache:false,
		dataType: "html",
		success: function(content){
			
			$(".cart_container").html(content);
			
			reloadCartSecondario();
		}
	});
}

function reloadCartSecondario()
{
	$.ajax({
		url: baseUrl + "/carrello/semplificato",
		async: true,
		cache:false,
		dataType: "html",
		success: function(content){
			
			$(".carrello_secondario").html(content);

			var num_prod = $(".carrello_secondario").find(".ajax_cart_num_prod").text();
			
			if (num_prod > 0)
			{
				$(".link_carrello_num_prod").text(num_prod);
				$(".link_carrello_num_prod").removeClass("uk-hidden");
			}
			else
			{
				$(".link_carrello_num_prod").text("");
				$(".link_carrello_num_prod").addClass("uk-hidden");
			}
			
// 			if (num_prod > 0)
// 			{
// 				$(".link_carrello_num_prod").addClass("count");/*css({"color":colore_carrello_non_vuoto});*/
// 			}
// 			else
// 			{
// 				$(".link_carrello_num_prod").removeClass("count");/*css({"color":"#000"});*/
// 			}
		}
	});
}

function reloadWishlistSecondario()
{
	$.ajax({
		url: baseUrl + "/wishlist/semplificato",
		async: true,
		cache:false,
		dataType: "html",
		success: function(content){
			
			if ($(".in-pagina-wishlist").length > 0)
				window.location.reload();
			else
			{
				if (content > 0)
				{
					$(".link_wishlist_num_prod").text(content);
					$(".link_wishlist_num_prod").removeClass("uk-hidden");
				}
				else
				{
					$(".link_wishlist_num_prod").text("");
					$(".link_wishlist_num_prod").addClass("uk-hidden");
				}
			}
		}
	});
}

function mostraNascondiPrezzoCombinazione(obj)
{
	if (obj.hasClass("accessorio_principale"))
		$(".blocco-prezzo").css("display", "none");
	else
		obj.find(".blocco-prezzo-accessorio").css("display", "none");
}

function checkCarrello()
{
	var okProcedi = true;
	
	$(".box_accessorio").each(function(){
		
		if ($(this).find(".input_attivo:checked").length > 0 || $(this).hasClass("accessorio_principale"))
		{
			if (!checkCombinazione($(this)))
				okProcedi = false;
			else if (!checkPersonalizzazione($(this)))
				okProcedi = false;
		}
		else
			$(this).find(".errore_combinazione").html("");
	});
	
	return okProcedi;
}

function attivaDisattivaCarrello()
{
	$(".aggiungi_al_carrello").unbind();
	$(".aggiungi_al_carrello_checkout").unbind();
	
	var okProcedi = checkCarrello();
	
	if (okProcedi)
	{
		$(".pulsante_carrello").addClass("aggiungi_al_carrello").removeClass("disabled");
		$(".acquista_prodotto").addClass("aggiungi_al_carrello_checkout").removeClass("disabled");
	}
		
	else
	{
		$(".pulsante_carrello").removeClass("aggiungi_al_carrello").addClass("disabled");
		$(".acquista_prodotto").removeClass("aggiungi_al_carrello_checkout").addClass("disabled");
	}
	
	if (okProcedi)
		aggiungialcarrello();
	
	return okProcedi;
}

function checkPersonalizzazione(obj)
{
	var ok_procedi = true;
	var errore_non_selez_array = [];
	var errore_non_selez_stringa = "";
	
	if (obj.find(".lista_personalizzazioni_prodotto").length > 0)
	{
		obj.find(".lista_personalizzazioni_prodotto").find(".form_input_personalizzazione").each(function(){
			
			var t_val = $(this).val();
			var t_nome_attributo = $(this).attr("rel");
			
			if ($.trim(t_val) == "")
			{
				ok_procedi = false;
				errore_non_selez_array.push(t_nome_attributo);
				$(this).css("border-color", "red");
			}
			else
				$(this).css("border-color", input_border_color);
		});
		
		errore_non_selez_stringa = errore_combinazione + " <span class='variante_non_presente'>" + errore_non_selez_array.join(", ") + "</span>";
	}
	
	if (!ok_procedi)
		obj.find(".errore_combinazione").html(errore_non_selez_stringa);
	else
		obj.find(".errore_combinazione").html("");
	
	return ok_procedi;
}

function checkCombinazione(obj)
{
	var ok_procedi = true;
	var errore_non_selez_array = [];
	var errore_non_selez_stringa = "";
	
	if (obj.find(".lista_attributi_prodotto").length > 0)
	{
		obj.find(".lista_attributi_prodotto").find(".form_select_attributo").each(function(){
			
			var t_val = $(this).val();
			var t_nome_attributo = $(this).attr("rel");
			
			if (t_val == 0)
			{
				ok_procedi = false;
				errore_non_selez_array.push(t_nome_attributo);
			}
		});
		
		var arrayRadioName = [];
		var arrayRadioRel = [];
		
		obj.find(".lista_attributi_prodotto").find(".form_radio_attributo").each(function(){
			
			if (arrayRadioName.indexOf($(this).attr("col")) == -1)
			{
				arrayRadioName.push($(this).attr("col"));
				arrayRadioRel.push($(this).attr("rel"));
			}
		});
		
		for (var i=0; i<arrayRadioName.length; i++)
		{
			if (obj.find(".lista_attributi_prodotto").find("[col='"+arrayRadioName[i]+"']:checked").length == 0)
			{
				errore_non_selez_array.push(arrayRadioRel[i]);
				ok_procedi = false;
			}
		}
		
// 		console.log(arrayRadioName);
		
		errore_non_selez_stringa = errore_combinazione + " <span class='variante_non_presente'>" + errore_non_selez_array.join(", ") + "</span>";
		
		if (ok_procedi && obj.find(".id_combinazione").text() == 0)
		{
			ok_procedi = false;
			errore_non_selez_stringa = variante_non_esistente;
		}
	}
	
	if (!ok_procedi)
		obj.find(".errore_combinazione").html(errore_non_selez_stringa);
	else
		obj.find(".errore_combinazione").html("");
	
	return ok_procedi;
}

//aggiorna immagine, prezzo e codice prodotto
function aggiornaDatiVariante(obj)
{
	if (obj.find(".lista_attributi_prodotto").length > 0)
	{
		var t_peso = obj.find(".peso_combinazione").text();
		var t_codice = obj.find(".codice_combinazione").text();
		var t_prezzo = obj.find(".prezzo_combinazione").text();
		var t_immagine = obj.find(".immagine_combinazione").text();
		var t_prezzo_pieno = obj.find(".prezzo_pieno_combinazione").text();
		var t_giacenza = obj.find(".giacenza_combinazione").text();
		
		if (obj.hasClass("accessorio_principale"))
		{
			$(".main_image").attr("src",baseUrl + "/thumb/dettaglio/" + t_immagine);
			$(".codice_value").text(t_codice);
			$(".price_value").text(t_prezzo);
			$(".blocco-prezzo").css("display", "block");
			$(".peso_value").text(t_peso);
			
			if (t_giacenza == 1)
			{
				$(".giacenza .sng").css("display", "inline");
				$(".giacenza .plu").css("display", "none");
			}
			else
			{
				$(".giacenza .sng").css("display", "none");
				$(".giacenza .plu").css("display", "inline");
			}
			
			$(".giacenza").css("display", "block");
			
			$(".valore_giacenza").text(t_giacenza);
			
			if (t_prezzo_pieno != "")
				$(".price_full").text(t_prezzo_pieno);
			
			if (t_immagine != "" && t_immagine != undefined)
			{
				var indice = 0;
				
				$(".slide_prodotto li").each(function() {
					
					var imm = $(this).attr("data-image");
					
					if (imm == t_immagine)
						UIkit.slideshow("#slide_singolo_prodotto").show(indice);
					else
						indice++;
				});
			}
		}
		else
		{
			obj.find(".price_value_accessorio").text(t_prezzo);
			obj.find(".blocco-prezzo-accessorio").css("display", "block");
			
			if (t_prezzo_pieno != "")
				obj.find(".price_full_accessorio").text(t_prezzo_pieno);
			
			if (t_giacenza == 1)
			{
				obj.find(".sng").css("display", "inline");
				obj.find(".plu").css("display", "none");
			}
			else
			{
				obj.find(".sng").css("display", "none");
				obj.find(".plu").css("display", "inline");
			}
			
			obj.find(".giacenza_acc").css("display", "block");
			
			obj.find(".valore_giacenza_acc").text(t_giacenza);
		}
	}
}

//lanciata ogni volta che seleziono una combinazione diversa
function combinazione(obj)
{
	if (obj.find(".lista_attributi_prodotto").length > 0)
	{
		$(".pulsante_carrello").unbind();
		
		var ok_procedi = true;
		var id_page = 0;
		
		if ($(".pulsante_carrello").length > 0 && obj.hasClass("accessorio_principale"))
			id_page = $(".pulsante_carrello").attr("rel");
		else
			id_page = obj.attr("id-page");
		
		var comb = [];
		var errore_array = [];
		
		obj.find(".lista_attributi_prodotto").find(".form_select_attributo").each(function(){
			
			var t_col = $(this).attr("col");
			var t_val = $(this).val();
			var t_valore_attributo = $(this).find("option:selected").text();
			var t_nome_attributo = $(this).attr("rel");
			
			if (t_val == 0)
			{
				ok_procedi = false;
			}
			
			errore_array.push( t_nome_attributo + " " + t_valore_attributo);
			
			comb.push(t_col+":"+t_val);
			
		});
		
		var arrayRadioName = [];
		var arrayRadioRel = [];
		
		obj.find(".lista_attributi_prodotto").find(".form_radio_attributo").each(function(){
			
			if (arrayRadioName.indexOf($(this).attr("col")) == -1)
			{
				arrayRadioName.push($(this).attr("col"));
				arrayRadioRel.push($(this).attr("rel"));
			}
		});
		
		for (var i=0; i<arrayRadioName.length; i++)
		{
			if (obj.find(".lista_attributi_prodotto").find("[col='"+arrayRadioName[i]+"']:checked").length == 0)
			{
				ok_procedi = false;
			}
			
			obj.find(".lista_attributi_prodotto").find("[col='"+arrayRadioName[i]+"']:checked").each(function(){
				
				var t_col = $(this).attr("col");
				var t_val = $(this).val();
				var t_valore_attributo = "";
				var t_nome_attributo = $(this).attr("rel");
				
				errore_array.push( t_nome_attributo + " " + t_valore_attributo);
				
				comb.push(t_col+":"+t_val);
			});
		}
		
		var str_combinazione = comb.join("|");
		var errore_stringa = variante_non_esistente;
		
// 		console.log(ok_procedi);
		
		if (!ok_procedi)
		{
			obj.find(".peso_combinazione").text(obj.find(".main_peso").text());
			obj.find(".codice_combinazione").text(obj.find(".main_codice").text());
			obj.find(".prezzo_combinazione").text(obj.find(".main_price").text());
			obj.find(".immagine_combinazione").text(obj.find(".main_immagine").text());
			obj.find(".prezzo_pieno_combinazione").text(obj.find(".main_price_pieno").text());
			aggiornaDatiVariante(obj);
		}
		
		mostraNascondiPrezzoCombinazione(obj);
		attivaDisattivaCarrello();
		
		var post_data = "id_page="+id_page+"&strcomb="+str_combinazione;
		
		if (ok_procedi)
		{
			$.ajaxQueue({
				type: "POST",
				url: baseUrl + "/controlla/combinazione",
				data: post_data,
				async: true,
				cache:false,
				dataType: "html",
				success: function(content){
					
					if (jQuery.trim(content) != "KO")
					{
						if (obj.find(".errore_combinazione").length > 0){
							obj.find(".errore_combinazione").html("");
						}
						
						if (obj.find(".dati_variante").length > 0){
							obj.find(".dati_variante").html(content);
							
// 							$(".pulsante_carrello").addClass("aggiungi_al_carrello").removeClass("disabled");
							
							aggiornaDatiVariante(obj);
							attivaDisattivaCarrello();
						}
					}
					else
					{
						if (obj.find(".dati_variante").length > 0){
							obj.find(".id_combinazione").text("0");
							
							obj.find(".peso_combinazione").text(obj.find(".main_peso").text());
							obj.find(".codice_combinazione").text(obj.find(".main_codice").text());
							obj.find(".prezzo_combinazione").text(obj.find(".main_price").text());
							obj.find(".immagine_combinazione").text(obj.find(".main_immagine").text());
							obj.find(".prezzo_pieno_combinazione").text(obj.find(".main_price_pieno").text());
							obj.find(".giacenza_combinazione").text(0);
							
							aggiornaDatiVariante(obj);
							mostraNascondiPrezzoCombinazione(obj);
							attivaDisattivaCarrello();
						}
						
						if (obj.find(".errore_combinazione").length > 0){
							obj.find(".errore_combinazione").html(errore_stringa);
						}
					}
				}
			});
		}
	}
	else
	{
		aggiungialcarrello();
	}
}

//click event upon the add-to-cart link
function aggiungialcarrello()
{
	$(".aggiungi_al_carrello").click(function(e) {
		
		e.preventDefault();
		
		actionAggiungiAlCarrello($(this));
	});
	
	$(".aggiungi_al_carrello_checkout").click(function(e) {
		
		vai_al_checkout = true;
		
		e.preventDefault();
		
		$(this).addClass("uk-hidden").parent().find(".spinner").removeClass("uk-hidden");
		
		actionAggiungiAlCarrello($(".aggiungi_al_carrello"));
	});
}

function haAccessori()
{
	if ($('.box_accessorio .input_attivo:checked').length > 0)
		return true;
	
	return false;
}

function aggiungiAccessori()
{
	$('.box_accessorio .input_attivo:checked').each(function(){
		
		arrayAccessori.push($(this).parents(".box_accessorio"));
		
	});
	
	for (var i=0; i < arrayAccessori.length; i++)
	{
		actionAggiungiAlCarrello($(".aggiungi_al_carrello"), arrayAccessori[i]);
	}
}

function togliSpinner(that)
{
	setTimeout(function(){ 
		
// 		if (that.parents(".product-block").length > 0)
// 		{
// 			
// 		}
// 		else
			that.removeClass("uk-hidden").parent().find(".spinner").addClass("uk-hidden");
			
			if ($(".acquista_prodotto").length > 0)
				$(".acquista_prodotto").removeClass("uk-hidden").parent().find(".spinner").addClass("uk-hidden");
			
	}, 500);
}

function impostaIdParent(content)
{
	// Imposto il parent nel box dell'accessorio
	$(".box_accessorio_figlio").each(function(){
		
		$(this).attr("id-p", content.idCart);
		
	});
}

function beforeAggiungiCarrello(principale, accessorio)
{
	if (accessorio == undefined)
		principale.addClass("uk-hidden").parent().find(".spinner").removeClass("uk-hidden");
}

function prodottiAggiunti(principale, content, id_cart)
{
	var titoloProdo = "";
	var urlThumb = principale.attr("img-thumb");
	
	if (principale.parents(".product-block").length > 0)
		titoloProdo = principale.parents(".product-block").find(".woocommerce-loop-product__title a").text();
	
	setTimeout(function(){ 
		
		principale.removeClass("uk-hidden").parent().find(".spinner").addClass("uk-hidden");
		
	}, 500);
	
	
	
	time = setTimeout(function(){ 
		
	}, 5000);
	
	var id_cart = principale.attr('id-cart');
	
	if (typeof id_cart !== typeof undefined && id_cart !== false && id_cart > 0)
	{
		location.href = baseUrl + "/carrello/vedi";
	}
	else if (vai_al_checkout)
	{
		location.href = baseUrl + "/checkout";
	}
	else
	{
		togliSpinner(principale);
		UIkit.offcanvas("#cart-offcanvas").show();
	}
}

function actionAggiungiAlCarrello(principale, accessorio)
{
	var id_p = 0;
	var id_page = principale.attr("rel");
	var id_c = 0;
	var id_cart = 0;
	var json_pers = "";
	
	if (accessorio == undefined)
	{
		var id_cart_pulsante = principale.attr('id-cart');
		
		if (typeof id_cart_pulsante !== typeof undefined && id_cart_pulsante !== false)
			id_cart = id_cart_pulsante;
	}
	
	if (!principale.hasClass("aggiungi_al_carrello_semplice") && $(".accessorio_principale .id_combinazione").length > 0)
		id_c = $(".accessorio_principale .id_combinazione").text();
	
	if (accessorio != undefined)
	{
		id_p = accessorio.attr("id-p");
		id_page = accessorio.attr("id-page");
		
		if (accessorio.find(".id_combinazione").length > 0)
			id_c = accessorio.find(".id_combinazione").text();
		else
			id_c = 0;
		
		if (id_p == 0)
			return;
	}
	
	var quantity = 1;
	
	if ($(".quantita_input").length > 0)
		quantity = $(".quantita_input").val();

	if (quantity == "")
		quantity = 0;
	
	// Invia le personalizzazioni
	var elemento = $(".accessorio_principale");
	
	if (accessorio != undefined)
		elemento = accessorio;
	
	if (elemento.find(".form_input_personalizzazione").length > 0)
	{
		var jsonPersArray = [];
		elemento.find(".form_input_personalizzazione").each(function(){
			jsonPersArray.push({
				"id"	:	$(this).attr("name"),
				"val"	:	$(this).val()
			})
		});
		
		if (jsonPersArray.length > 0)
			json_pers = JSON.stringify(jsonPersArray);
	}
	
	beforeAggiungiCarrello(principale, accessorio);
	
	var url = baseUrl + "/carrello/aggiungi/" + id_page + "/" + quantity + "/" + id_c + "/" + id_p + "/" + id_cart;
	
	$.ajaxQueue({
		url: url,
		async: true,
		cache:false,
		dataType: "json",
		type: "POST",
		data: {
			json_pers: json_pers
		},
		success: function(content){
			clearTimeout(time);
			
			if (jQuery.trim(content.result) == "OK")
			{
				if (pixel && content.contens_fbk != undefined && content.contens_fbk != "")
				{
					if (debug_js)
						console.log(content.contens_fbk);
					
					fbq('track', 'AddToCart', content.contens_fbk);
				}
				
				if (gtm_analytics && content.contens_gtm != undefined && content.contens_gtm != "")
				{
					if (debug_js)
						console.log(content.contens_gtm);
					
					gtag('event', 'add_to_cart', {"items": content.contens_gtm});
				}
				
				if (!haAccessori() || principale.hasClass("aggiungi_al_carrello_semplice"))
				{
					prodottiAggiunti(principale, content);
					reloadCartSecondario();
				}
				else
				{
					if (accessorio == undefined)
					{
						impostaIdParent(content);
						aggiungiAccessori();
					}
				}
			}
			else
			{
				alert(content.errore);
				togliSpinner(principale);
			}
			
			if (haAccessori() && !principale.hasClass("aggiungi_al_carrello_semplice"))
			{
				if (accessorio != undefined)
				{
					if (arrayAccessori.length > 0)
						arrayAccessori.pop();
					
					if (arrayAccessori.length <= 0)
					{
						prodottiAggiunti(principale, content);
						reloadCartSecondario();
					}
				}
			}
		}
	});
}

function apriChiudiBoxAccessorio(obj)
{
	if (obj.is(':checked'))
		obj.parents(".box_accessorio").find(".box_accessorio_inner").css("display", "block");
	else
		obj.parents(".box_accessorio").find(".box_accessorio_inner").css("display", "none");
}

function aggiornaCarrello()
{
	var products_list = "";
	var curr_item = "";
	var curr_quantity = "";
	
	$(".main_cart .cart_item_row").each(function(){
		
		curr_item = $(this).attr("rel");
		curr_quantity = $(this).find(".item_quantity").val();
		
		products_list += curr_item + ":" + curr_quantity + "|";
	});
	
	var post_data = "products_list="+encodeURIComponent(products_list);
// 	console.log(post_data);
	$.ajaxQueue({
		type: "POST",
		url: baseUrl + "/carrello/aggiorna",
		data: post_data,
		async: true,
		cache:false,
		dataType: "json",
		success: function(content){
			
			$(".item_quantity").css("background-color","#FFF");
			
			for (var i=0; i<content.length; i++)
			{
				$(".item_quantity[rel='"+content[i]+"']").css("background-color",back_cart_error);
			}
			
			if (content.length == 0)
				reloadCart();
			else
				alert(stringa_errore_giacenza_carrello);
		}
	});
}

$(document).ready(function(){
	
	$(".box_accessorio").each(function(){
		
		combinazione($(this));
		
	});
	
	attivaDisattivaCarrello();
	
	$('.box_accessorio .input_attivo').each(function(){
		
		apriChiudiBoxAccessorio($(this))
		
	});
	
	$('.box_accessorio .input_attivo').on('ifChanged', function(event){
		
		attivaDisattivaCarrello();
		
		apriChiudiBoxAccessorio($(this))
		
	});
	
	$(".form_select_attributo").change(function(){
	
		combinazione($(this).parents(".box_accessorio"));
	
	});
	
	// Change personalizzazione
	$('.box_accessorio .form_input_personalizzazione').on('change', function(event){
		
		attivaDisattivaCarrello();
		
	});
	
	// Change personalizzazione
	$('.box_accessorio .form_input_personalizzazione').on('keyup', function(event){
		
		attivaDisattivaCarrello();
		
	});
	
	$('.form_radio_attributo').on('ifChanged', function(event){
	
		combinazione($(this).parents(".box_accessorio"));
	
	});
	
	$( "body" ).on( "click", ".aggiungi_al_carrello_semplice", function(e) {
		
		e.preventDefault();
		
		actionAggiungiAlCarrello($(this));
	});
	
	$( "body" ).on( "click", ".cart_item_quantity_increase", function(e) {
		
		e.preventDefault();
		
		var t_input = $(this).parent().find(".item_quantity");
		
		var new_quantity = parseInt(t_input.val()) + 1;
		
		t_input.val(new_quantity) ;
	});
	
	$( "body" ).on( "click", ".cart_item_quantity_decrease", function(e) {
		
		e.preventDefault();
		
		var t_input = $(this).parent().find(".item_quantity");
		
		var t_current_quantity = parseInt(t_input.val());
		
		if (t_current_quantity > 0)
		{
			t_input.val( t_current_quantity - 1) ;
		}
	});
	
	$( "body" ).on( "change", ".cart_item_row_mobile", function(e) {
		
		console.log("aa");
		aggiornaCarrello();
		
	});
	
	$( "body" ).on( "click", ".cart_item_delete_link", function(e) {
		
		e.preventDefault();
		
		var t_tr = $(this).closest(".cart_item_row");
		var id_page = t_tr.attr("rel");
		
		$.ajaxQueue({
			url: baseUrl + "/carrello/elimina/" + id_page,
			async: true,
			cache:false,
			dataType: "html",
			success: function(content){
				reloadCart();
			}
		});
	});
	
	$( "body" ).on( "click", ".cart_button_aggiorna_carrello", function(e) {
		
		e.preventDefault();
		
		aggiornaCarrello();
		
	});
	
	$( "body" ).on( "click", ".azione_wishlist", function(e) {
		
		e.preventDefault();
		
		var that = $(this);
		
		var url = $(this).attr("href");
		
// 		that.parent().find("img").css("visibility","visible");
		
		$.ajaxQueue({
			url: url,
			async: true,
			cache:false,
			dataType: "json",
			success: function(content){
				
				if (jQuery.trim(content.result) == "OK")
				{
					if (pixel && content.contens_fbk != undefined && content.contens_fbk != "")
					{
						if (debug_js)
							console.log(content.contens_fbk);
						
						fbq('track', 'AddToWishlist', content.contens_fbk);
					}
					
					if (that.parent().hasClass(classe_wish_presente))
					{
						that.closest("."+box_pulsante_wishlist).find("."+classe_wish_presente).css("display","none");
						that.closest("."+box_pulsante_wishlist).find("."+classe_wish_non_presente).css("display","block");
					}
					else
					{
						that.closest("."+box_pulsante_wishlist).find("."+classe_wish_presente).css("display","block");
						that.closest("."+box_pulsante_wishlist).find("."+classe_wish_non_presente).css("display","none");
					}
				}
				
				reloadWishlistSecondario();
				
// 				that.parent().find("img").css("visibility","hidden");
			}
		});
	});
	
	$(".link_carrello").hover(function(){
		$("#carrello_secondario").css("display","block");
	},function(){
		$("#carrello_secondario").css("display","none");
	});
})
