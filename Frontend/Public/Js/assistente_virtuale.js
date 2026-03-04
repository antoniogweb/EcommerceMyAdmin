function aggiornaChat()
{
	$.ajaxQueue({
		url: baseUrl + "/virtual-assistant/messages/",
		cache:false,
		async: true,
		dataType: "html",
		success: function(content){

			$(".chat_messages").html(content);

		}
	});
}

$(document).ready(function(){
	
	$( "body" ).on( "click", ".send_request_to_va", function(e) {
		
		e.preventDefault();
		
		var messaggio = $(".request_message").val();
		
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
				
				aggiornaChat();
				
			}
		});
	});
	
});