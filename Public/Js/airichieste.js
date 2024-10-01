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
			dataType: "json",
			type: "POST",
			data: {
				messaggio: messaggio
			},
			success: function(content){

				removeSpinner(that);

				// that.find("i").removeClass("fa-spin").removeClass("fa-spinner").addClass("fa-refresh");
    //
				// $(".valore_attributo_combinazione").css("background-color", "#FFF").css("color", "#555");
    //
				// if (content.length > 0)
				// {
				// 	alert("ATTENZIONE: le righe evidenziate in rosso non sono state aggiornate perché tali combinazioni sono già presenti.");
    //
				// 	for (var i = 0; i < content.length; i++)
				// 	{
				// 		$(".valore_attributo_combinazione_" + content[i]).css("background-color", "red").css("color", "#FFF");
				// 	}
				// }
			}
		});

	});

});
