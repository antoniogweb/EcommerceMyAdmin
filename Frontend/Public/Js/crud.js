$(document).ready(function(){
	
	//events binded to the checkbox for bulk selection
	$("body").on("ifChanged", ".bulk_select_checkbox", function(e){
		
		var bulk_select_class = $(this).attr("data-class");
		
		if ($(this).is(":checked"))
		{
			$("." + bulk_select_class).iCheck('check');
		}
		else
		{
			$("." + bulk_select_class).iCheck('uncheck');
		}
		
	});
	
});
