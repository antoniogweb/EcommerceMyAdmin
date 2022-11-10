<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script type="text/javascript">

function updateForm()
{
	var tipo_cliente = $(".radio_cliente option:selected").attr("value");
	
	if (tipo_cliente == "privato")
	{
		$(".ragione_sociale").css("display","none");
		$(".p_iva").css("display","none");
		$(".nome").css("display","block");
		$(".cognome").css("display","block");
	}
	else if (tipo_cliente == "libero_professionista")
	{
		$(".ragione_sociale").css("display","none");
		$(".p_iva").css("display","block");
		$(".nome").css("display","block");
		$(".cognome").css("display","block");
	}
	else
	{
		$(".ragione_sociale").css("display","block");
		$(".p_iva").css("display","block");
		<?php if (!v("nome_cognome_anche_azienda")) { ?>
		$(".nome").css("display","none");
		$(".cognome").css("display","none");
		<?php } ?>
	}
}

function sistemaTendinaProvincia(val)
{
	if (val == "IT")
	{
		$(".box_dprovincia").css("display","none");
		$(".box_provincia").css("display","block");
	}
	else
	{
		$(".box_dprovincia").css("display","block");
		$(".box_provincia").css("display","none");
	}
}

$(document).ready(function(){

	updateForm();
	
	$(".radio_cliente").change(function(){
		
		updateForm();
	
	});
	
	if ($('[name="id_user"]').length > 0 && $('[name="id_spedizione"]').length > 0)
		$('[name="id_user"],[name="id_spedizione"]').on('select2:select', function (e) {
			reloadPage();
		});
	
	if ($("[name='nazione']").length > 0)
		sistemaTendinaProvincia($("[name='nazione']").val());
	
	$("body").on("change", "[name='nazione']", function(e){
		
		sistemaTendinaProvincia($(this).val());
		
	});
});

</script>
