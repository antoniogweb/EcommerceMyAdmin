$(document).ready(function(){
	$(".scaffold_form [name='id_fornitore']").change(function(){
		
		var id_fornitore = $(this).val();
		
		var url = baseUrl + "/" + applicationName + controllerName + "/" + actionName + "/insert/0?id_form_fornitore=" + id_fornitore;
		
		location.href = url;
	});
});
