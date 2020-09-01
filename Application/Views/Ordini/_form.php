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
	else
	{
		$(".ragione_sociale").css("display","block");
		$(".p_iva").css("display","block");
		$(".nome").css("display","none");
		$(".cognome").css("display","none");
	}

}

$(document).ready(function(){

	updateForm();
	
	$(".radio_cliente").change(function(){
		
		updateForm();
	
	});
	
});

</script>

<section class="content-header">
	<h1>Gestione ordini</h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border main">

					<?php echo $scaffold;?>
                </div>
			</div>
		</div>
	</div>
</section>