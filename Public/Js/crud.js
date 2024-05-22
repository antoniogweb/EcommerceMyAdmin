if (typeof doYouConfirmString == "undefined")
	var doYouConfirmString = "Confermi l'azione: ";

if (typeof noSelectedString == "undefined")
	var noSelectedString = "Si prega di selezionare alcune righe";

if (typeof stringaConfermiEliminazione == "undefined")
	var stringaConfermiEliminazione = "Confermi l'eliminazione dell'elemento?";

$(document).ready(function(){

	//events binded to the select of bulk actions
// 	$(".bulk_actions_select").change(function() {
	$("body").on("change", ".bulk_actions_select", function(){
		
		var that = $(this);
		
		var current_URL = that.attr("data-url");
		
		var t_action_readable;
		
		var t_action = $(this).val();
		
		if (t_action != 0)
		{
			var selected_option = $(this).find('option:selected');
			
			var t_class = selected_option.attr('data-class');
			t_action_readable = selected_option.text();
			
			var bulk_values_array = [];
			
			$("." + t_class).each(function(){
			
				var t_type = $(this).attr("type");
				
				if (t_type == "text")
				{
					bulk_values_array.push($(this).attr("data-primary-key") + ":" + $(this).val());
				}
				else if (t_type == "checkbox")
				{
					if ($(this).val() == "")
					{
						if ($(this).prop('checked'))
						{
							bulk_values_array.push($(this).attr("data-primary-key"));
						}
					}
					else
					{
						if ($(this).prop('checked'))
						{
							bulk_values_array.push($(this).attr("data-primary-key") + ":" + $(this).val());
						}
						else
						{
							bulk_values_array.push($(this).attr("data-primary-key") + ":NC");
						}
					}
				}
			});
			
			if (bulk_values_array.length > 0)
			{
				var bulk_values_string = bulk_values_array.join('|');

				bulk_values_string = bulk_values_string.replace("'","&apos;");

				var formHtml = "<form class='bulk_actions_form' action='"+current_URL+"' method='POST'><input type='hidden' name='bulkActionValues' value='"+bulk_values_string+"' /><input type='hidden' name='bulkAction' value='"+t_action+"' /></form>";
				$("body").append(formHtml);
				
				var submit = false;
				
				if (selected_option.attr("data-confirm") == "Y")
				{
					setTimeout(function(){
						
						if (window.confirm(doYouConfirmString + t_action_readable + "?")) {
							$(".bulk_actions_form").submit();
						}
						
					}, 100);
				}
				else
					$(".bulk_actions_form").submit();
			}
			else
			{
				setTimeout(function(){
					
					alert(noSelectedString);
					
				}, 100);
			}
		}
	
		$(".bulk_actions_select option").filter(function() {
			return $(this).val() == "0"; 
		}).prop('selected', true);

	});
	
	$(".list_filter_form select").change(function(){
		
		$(this).parents(".list_filter_form").submit();
		
	});
	
	$("body").on("click", "td.delForm form,.del_row, td.ldel a", function() {
// 	$("td.delForm form,.del_row, td.ldel a").click(function () {
		var that = $(this);
		
		if (window.confirm(stringaConfermiEliminazione)) {
			
			if (that.find("i").length > 0)
				that.find("i").attr("class", "fa fa-spinner fa-spin");
			
			if ($(".btn_trigger_click").length > 0)
			{
				$(".btn_trigger_click").trigger("click");
				
				setTimeout(function(){
					window.location = that.attr("href");
				}, 500);
			}
			else
				return true;
		}

		return false;
	});
	
	$(".table-scaffolding tr td").click(function(e){
		if(e.target != this) return;
		
		if ($(this).closest("tr").find("a.action_edit").length > 0)
		{
			var url = $(this).closest("tr").find("a.action_edit").attr("href");
			location.href = url;
		}
		else if ($(this).closest("tr").find("a.action_iframe").length > 0)
		{
			$(this).closest("tr").find("a.action_iframe").trigger("click");
		}
	});
});
