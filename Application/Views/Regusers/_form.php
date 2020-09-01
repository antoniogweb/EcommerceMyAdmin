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
	<h1><?php if (strcmp($type,"update") === 0) { echo "Gestione utente sito web: ".$titoloPagina; } else { echo "Inserimento nuovo utente sito web";}?></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-header with-border main">

					<div class="pulsante_stampa_box for_print">
						<span class="glyphicon glyphicon-print"></span> <input class="btn btn-link" onClick="window.print()" type="submit" name="Stampa" value="Stampa" />
					</div>

					<!-- show the top menù -->
					<div class='mainMenu'>
						<?php echo $menu;?>
					</div>

					<?php include(ROOT."/Application/Views/Regusers/steps.php");?>

					<?php echo $notice;?>

					<!-- show the table -->
					<div class='scaffold_form'>
						<?php echo $main;?>
					</div>
                </div>
			</div>
		</div>
	</div>
</section>
