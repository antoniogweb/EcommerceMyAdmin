$(document).ready(function(){
	$(".scaffold_form [name='id_fornitore']").change(function(){
		
		var id_fornitore = $(this).val();
		
		var url = baseUrl + "/" + applicationName + controllerName + "/" + actionName + "/insert/0?id_form_fornitore=" + id_fornitore;
		
		location.href = url;
	});
	
	$("body").on("click", ".save_articoli", function(e){
		
		e.preventDefault();
		
		var that = $(this);
		
		that.find("i").removeClass("fa-refresh").addClass("fa-spinner").addClass("fa-spin");
		
		var valori = [];
		
		$("table tr.listRow").each(function(){
			
			var id_articolo = $(this).find("[name='codice']").attr("id-articolo");
			var codice = $(this).find("[name='codice']").val();
			var gtin = $(this).find("[name='gtin']").val();
			
			var temp = {
				id_articolo: id_articolo,
				codice: codice,
				gtin: gtin
			};
			
			valori.push(temp);
		});
		
		// console.log(valori);
		
		$.ajaxQueue({
			url: baseUrl + "/" + applicationName + controllerName + "/salva",
			cache:false,
			async: true,
			dataType: "json",
			type: "POST",
			data: {
				csrf: csrf_token,
				valori: JSON.stringify(valori)
			},
			success: function(content){
				
				that.find("i").removeClass("fa-spin").removeClass("fa-spinner").addClass("fa-refresh");
				
				$(".class_combinazione").css("background-color", "#FFF").css("color", "#555");
				
				// Coloro la riga
				// if (that.hasClass("save_combinazioni_listino"))
				// 	$("table tr.listRow").addClass("classe_riga_listino_modificato");
				
				if (content.length > 0)
				{
					alert("ATTENZIONE: le righe evidenziate in rosso non sono state aggiornate perché il salvataggio è andato in errore");
					
					for (var i = 0; i < content.length; i++)
					{
						$(".class_combinazione_" + content[i]).css("background-color", "red").css("color", "#FFF");
						
						// $(".class_combinazione_" + content[i]).closest("tr.listRow").removeClass("classe_riga_listino_modificato");
					}
				}
			}
		});
		
	});
});
