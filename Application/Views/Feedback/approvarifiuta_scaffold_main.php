<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<script type="text/javascript" src="<?php echo $this->baseUrl?>/Public/Js/tiny_mce/jquery.tinymce.js"></script>

<script type="text/javascript">
$().ready(function() {
	$("[name='commento_negozio']").tinymce(tiny_editor_config);
});
</script>

<form class="formClass form_class_contenuto" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/".$this->action."/$type/$id".$this->viewStatus;?>">
	<div class='row'>
		<div class='col-md-6'>
			<h2><?php echo gtext("Feedback cliente");?></h2>
			
			<?php if (!$feedback["da_approvare"]) { ?>
				<?php if ($feedback["approvato"]) { ?>
				<div class="alert alert-success">
					<?php echo gtext("Il feedback del cliente è stato approvato ed è visibile nella pagina pubblica del prodotto.");?>
				</div>
				<?php } else { ?>
				<div class="alert alert-danger">
					<?php echo gtext("Il feedback del cliente è stato rifiutato e non è visibile nella pagina pubblica del prodotto.");?>
				</div>
				<?php } ?>
			<?php } else { ?>
			<div class="alert alert-info">
				<?php echo gtext("Il feedback del cliente non è ancora stato gestito.");?>
			</div>
			<?php } ?>
			
			<?php echo $form["autore"];?>
			
			<?php echo $form["email"];?>
			
			<?php echo $form["data_feedback"];?>
			
			<?php echo $form["testo"];?>
			
			<?php echo $form["voto"];?>
		</div>
		<div class='col-md-6'>
			<h2><?php echo gtext("Aggiungi commento / Approva / Rifiuta");?></h2>
			
			<?php echo $form["commento_negozio"];?>
			
			<?php if ($feedback["da_approvare"] || $feedback["approvato"]) { ?>
			<button id="updateAction" class="btn btn-danger pull-right" name="updateAction" type="submit" value="rifiutaFeedback"><i class="fa fa-check"></i> <?php echo gtext("Rifiuta");?></button>
			<?php } ?>
			
			<?php if ($feedback["da_approvare"] || !$feedback["approvato"]) { ?>
			<button id="updateAction" class="btn btn-success" name="updateAction" type="submit" value="approvaFeedback"><i class="fa fa-check"></i> <?php echo gtext("Approva");?></button>
			<?php } ?>
		</div>
	</div>
</form>
