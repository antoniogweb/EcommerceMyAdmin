<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (v("attiva_modifica_massiva_codici") && isset($_GET["id_page"]) && PagesModel::isAttiva($_GET["id_page"])) { ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<?php echo gtext("Modifica di gruppo");?>
	</div>
	<div class="panel-body">
		<form class="form-inline form-modifica-massiva">
			<?php echo Html_Form::input("codice","","form-control",null,"placeholder='Codice'");?>
			<?php echo Html_Form::input("price","","form-control",null,"placeholder='Prezzo'");?>
			<?php if (v("gestisci_sconti_combinazioni_separatamente")) { ?>
			<?php echo Html_Form::input("price_scontato","","form-control",null,"placeholder='Prezzo scontato'");?>
			<?php } ?>
			<?php echo Html_Form::input("peso","","form-control",null,"placeholder='Peso'");?>
			<?php echo Html_Form::input("giacenza","","form-control",null,"placeholder='Giacenza'");?>
			<button class="btn btn-success btn-imposta-gruppo"><?php echo gtext("Imposta");?></button>
		</form>
	</div>
</div>

<script>
$(document).ready(function(){

	$("body").on("click", ".btn-imposta-gruppo", function(e){
		
		e.preventDefault();
		
		$(".form-modifica-massiva input").each(function(){
			var valore = $(this).val();
			var name = $(this).attr("name");
			
			if ($.trim(valore) != "" && $(".table-scaffolding").find("[name='"+name+"']").length > 0)
			{
				$(".table-scaffolding").find("[name='"+name+"']").val(valore);
			}
		});
		
	});
	
});
</script>
<?php } ?>

<?php echo $filtri;?>
