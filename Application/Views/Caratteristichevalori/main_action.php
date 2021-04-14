<script>

$(document).ready(function(){
	$(".checkbox_caratteristiche_valori_id_cv").click(function(e){
		
		if ($(this).prop("checked"))
		{
			$(this).parents('table').find('.bulk_actions_select').val("aggiungiaprodotto").trigger('change');
		}
		
	});

});

</script> 
