function aggiornaChat(id)
{
	$.ajaxQueue({
		url: baseUrl + "/airichieste/listamessaggi/" + id,
		cache:false,
		async: true,
		dataType: "html",
		success: function(content){

			$(".ai_chat_box").html(content);

		}
	});
}

$(document).ready(function(){

	$("body").on("click", ".invia_nuovo_messaggio_ai", function(e){

		var that = $(this);

		var messaggio = $(".testo_nuovo_messaggio_ai").val();
		var id = $(this).attr("id-richiesta");

		makeSpinner(that);

		$.ajaxQueue({
			url: baseUrl + "/airichieste/messaggio/" + id,
			cache:false,
			async: true,
			dataType: "html",
			type: "POST",
			data: {
				messaggio: messaggio
			},
			success: function(content){

				aggiornaChat(id);

			}
		});

	});

});
