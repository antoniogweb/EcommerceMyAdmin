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
			<?php echo $form["codice"];?>
			<?php echo $form["classe"];?>
			<?php echo $form["pagato"];?>
			<?php echo $form["da_spedire"] ?? "";?>
			<?php echo $form["in_spedizione"] ?? "";?>
			<?php echo $form["spedito"] ?? "";?>
			<?php echo $form["manda_mail_al_cambio_stato"];?>
			<?php echo $form["descrizione"];?>
			
			<?php if ($type === "update") { ?>
			<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_n">
			<?php } ?>
			
			<?php include($this->viewPath("form_submit_button"));?>
		</div>
		<div class='col-md-4'>
			<?php if (isset($contenutiTradotti) && count($contenutiTradotti) > 0 && count(BaseController::$traduzioni) > 0) { ?>
			<div class="panel panel-info">
				<div class="panel-heading">
					<?php echo gtext("Traduzioni");?>
				</div>
				<div class="panel-body">
					<?php
					$section = "pagamenti";
					$nascondiLink = $nascondiAlias = true;
					include($this->viewPath("pages_traduzioni"));?>
				</div>
			</div>
			<?php } ?>
		</div>
	</form>
</div>
