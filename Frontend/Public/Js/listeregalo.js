function sistemaCampiFormLista()
{
	$(".form_lista_regalo").find(".btn_submit_form").attr("name","gAction");
// 	$(".form_lista_regalo").find(".btn_submit_form").attr("name","gAction");
	$(".form_lista_regalo").submit();
}

$(document).ready(function(){
	
// 	sistemaCampiFormLista();
	
	$( "body" ).on( "change", "[name='id_lista_tipo']", function(e) {
		
		sistemaCampiFormLista();
		
	});
	
});
