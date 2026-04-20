function scorriChatInBasso()
{
	var contenitoreMessaggi = $(".assistente_virtuale_messages");

	if (!contenitoreMessaggi.length)
		return;

	if ($(".chat_message_bubble_user").length > 0)
	{
		var ultimoMessaggioUtente = $(".chat_message_bubble_user").last();
		var offsetMessaggio = ultimoMessaggioUtente.offset();
		var offsetContenitore = contenitoreMessaggi.offset();
		// var altezzaContenitore = contenitoreMessaggi.innerHeight();

		if (!offsetMessaggio || !offsetContenitore)
			return;

		var scrollDestinazione = contenitoreMessaggi.scrollTop() + (offsetMessaggio.top - offsetContenitore.top) - 16;
		// var topRelativoMessaggio = contenitoreMessaggi.scrollTop() + (offsetMessaggio.top - offsetContenitore.top);
		// var bottomRelativoMessaggio = topRelativoMessaggio + ultimoMessaggioUtente.outerHeight(true);
		// var scrollDestinazione = bottomRelativoMessaggio - altezzaContenitore + 16;

		contenitoreMessaggi.stop(true).animate({
			scrollTop: Math.max(scrollDestinazione, 0)
		}, 500);
		return;
	}

	contenitoreMessaggi.stop(true).animate({
		scrollTop: contenitoreMessaggi.get(0).scrollHeight
	}, 500);
}

function aggiornaChat()
{
	$.ajaxQueue({
		url: baseUrl + "/virtual-assistant/messages/",
		cache:false,
		async: true,
		dataType: "html",
		success: function(content){
			$(".chat_messages").html(content);
			
			setTimeout(function() {
				scorriChatInBasso();
			}, 100);
		}
	});
}

function initAssistenteVirtualeWidget()
{
	var widget = $(".assistente_virtuale_widget");

	if (!widget.length)
		return;

	var body = $("body");
	var toggle = widget.find(".assistente_virtuale_widget_toggle");
	var close = widget.find(".assistente_virtuale_widget_close");
	var overlay = widget.find(".assistente_virtuale_widget_overlay");
	var panel = widget.find(".assistente_virtuale_widget_panel");
	var iframe = widget.find(".assistente_virtuale_widget_iframe");
	var chatUrl = widget.data("chat-url");

	function caricaIframe()
	{
		if (!iframe.attr("src") && chatUrl)
			iframe.attr("src", chatUrl);
	}

	function impostaStato(aperto)
	{
		widget.toggleClass("assistente_virtuale_widget_open", aperto);
		body.toggleClass("assistente_virtuale_widget_opened", aperto);
		toggle.attr("aria-expanded", aperto ? "true" : "false");
		panel.attr("aria-hidden", aperto ? "false" : "true");

		if (aperto)
			caricaIframe();
	}

	toggle.on("click", function() {
		impostaStato(!widget.hasClass("assistente_virtuale_widget_open"));
	});

	close.on("click", function() {
		impostaStato(false);
	});

	overlay.on("click", function() {
		impostaStato(false);
	});

	$(document).on("keydown", function(e) {
		if (e.key === "Escape" && widget.hasClass("assistente_virtuale_widget_open"))
			impostaStato(false);
	});
}

$(document).ready(function(){
	scorriChatInBasso();
	initAssistenteVirtualeWidget();
	
	$("body").on("keypress",".request_message", function(e){
		if (e.which == 13) {
			e.preventDefault();
			
			$(".send_request_to_va").trigger("click");
		}
	});
	
	$( "body" ).on( "click", ".send_request_to_va", function(e) {
		
		e.preventDefault();

		var bottone = $(this);
		var loader = bottone.find(".send_request_to_va_loader");
		var testo = bottone.find(".send_request_to_va_text");
		
		var messaggio = $(".request_message").val();

		if (!messaggio.length || bottone.prop("disabled"))
			return;

		bottone.prop("disabled", true);
		testo.addClass("uk-hidden");
		loader.removeClass("uk-hidden");
		
		$.ajaxQueue({
			url: baseUrl + "/virtual-assistant/request/",
			async: true,
			cache: false,
			type: "POST",
			data: {
				messaggio: messaggio
			},
			dataType: "html",
			success: function(content){
				
				$(".request_message").val("");
				aggiornaChat();
				
			},
			complete: function() {
				bottone.prop("disabled", false);
				testo.removeClass("uk-hidden");
				loader.addClass("uk-hidden");
			}
		});
	});
	
});
