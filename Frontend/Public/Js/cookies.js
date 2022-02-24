$ = jQuery;

$(document).ready(function(){
	
	$("body").on("click", ".preferenze_cookies", function(e){
		
		e.preventDefault();
		
		$(".accetta_approfondisci").addClass("uk-hidden");
		$(".form_scelta").removeClass("uk-hidden");
		
	});
	
	$("body").on("click", ".submit_preferenze", function(e){
		
		e.preventDefault();
		
		var url = $(this).closest("form").attr("action");
		var datastring = $(this).closest("form").serialize();
		
		$.ajaxQueue({
			url: url + "?" + datastring,
			cache:false,
			async: true,
			dataType: "html",
			method: "POST",
			data: datastring,
			success: function(content){
				location.reload();
			}
		});
		
	});
});
