<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script type="text/javascript" src="<?php echo $this->baseUrlSrc?>/Public/Js/tiny_mce/jquery.tinymce.js"></script>

<script type="text/javascript">
$().ready(function() {
	$('textarea').tinymce(tiny_editor_config);
});
</script>

<div class='row'>
	<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data">
		<div class='col-md-8'>
			<?php echo $form["titolo"];?>
			<?php echo $form["alias"];?>
			<div class='row'>
				<div class='col-md-6'>
					<?php echo $form["attivo"];?>
				</div>
				<div class='col-md-6'>
					<?php echo $form["codice"];?>
				</div>
			</div>
			<?php echo $form["sottotitolo"];?>
			
			<?php echo isset($form["nazione"]) ? $form["nazione"] : "";?>
			<?php echo isset($form["in_evidenza"]) ? $form["in_evidenza"] : "";?>
			<?php echo isset($form["nuovo"]) ? $form["nuovo"] : "";?>
			
			<?php echo $form["descrizione"];?>
			<?php echo $form["immagine"];?>
			<?php echo $form["immagine_2x"];?>
			
			<?php if ($type === "update") { ?>
			<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_n">
			<?php } ?>
			
			<div class="submit_entry">
				<span class="submit_entry_Salva">
					<button id="<?php echo $type;?>Action" class="btn btn-success make_spinner" name="<?php echo $type;?>Action" type="submit"><i class="fa fa-save"></i> <?php echo gtext("Salva");?></button>
					<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
				</span>
			</div>
		</div>
		<div class='col-md-4'>
			<?php if (isset($contenutiTradotti) && count($contenutiTradotti) > 0 && count(BaseController::$traduzioni) > 0) { ?>
			<div class="panel panel-info">
				<div class="panel-heading">
					Traduzioni
				</div>
				<div class="panel-body">
					<?php
					$section = "-car-";
					$nascondiLink = true;
					include($this->viewPath("pages_traduzioni"));?>
				</div>
			</div>
			<?php } ?>

			<?php if (v("attiva_richieste_ai")) { ?>
				<?php
				$labelIdEl = "id_marchio";
				include($this->viewPath("box_assistente_ai"));?>
			<?php } ?>
		</div>
	</form>
</div>
