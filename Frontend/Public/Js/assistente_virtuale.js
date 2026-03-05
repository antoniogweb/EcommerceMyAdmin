function scorriChatInBasso()
{
	$('html, body').animate({
		scrollTop: $(document).height()
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
			scorriChatInBasso();

		}
	});
}

$(document).ready(function(){
	scorriChatInBasso();
	
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
