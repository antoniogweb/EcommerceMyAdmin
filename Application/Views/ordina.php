<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script type="text/javascript">
<?php if (strstr($orderBy, "id_order")) { ?>

function aggiornaOrdinamento()
{
	var id_page = "";
	var order = "";
	
	$("input[data-primary-key]").each(function(){
	
		var id_page = $(this).attr("data-primary-key");
	
		order += id_page + ",";
	
	});
	
	var post_data = "order="+order+"&ordinaPagine=Y";
	
	$.ajax({
		type: "POST",
		data: post_data,
		url: "<?php echo $this->baseUrl.'/'.$this->applicationUrl.$this->controller.'/'.$ordinaAction.'/';?>",
		async: false,
		cache:false,
		success: function(html){
			
		}
	});
}

$(function() {
	$( ".table tbody" ).sortable({
		items: "tr:not(.listFilters,.listHead,.bulk_actions_tr)",
		stop: function( event, ui ) {
			aggiornaOrdinamento();
		}
	});
});
<?php } ?>
</script>
