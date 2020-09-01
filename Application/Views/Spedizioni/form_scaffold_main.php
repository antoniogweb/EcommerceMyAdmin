<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script>

function sistemaTendinaProvinciaSpedizione(val)
{
	if (val == "IT")
	{
		$(".dprovincia_spedizione").css("display","none");
		$(".provincia_spedizione").css("display","block");
	}
	else
	{
		$(".dprovincia_spedizione").css("display","block");
		$(".provincia_spedizione").css("display","none");
	}
}

$(document).ready(function(){

	if ($("[name='nazione_spedizione']").length > 0)
		sistemaTendinaProvinciaSpedizione($("[name='nazione_spedizione']").val());
	
	$("body").on("change", "[name='nazione_spedizione']", function(e){
		
		sistemaTendinaProvinciaSpedizione($(this).val());
		
	});

});
</script>

<?php echo $main;?>
