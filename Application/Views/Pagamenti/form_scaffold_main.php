<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script type="text/javascript" src="<?php echo $this->baseUrlSrc?>/Public/Js/tiny_mce/jquery.tinymce.js"></script>

<script type="text/javascript">
$().ready(function() {
	$('textarea.editor_visuale').tinymce(tiny_editor_config);
});
</script>

<div class='row'>
	<form class="formClass" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>" enctype="multipart/form-data">
		<div class='col-md-8'>
			<?php echo $form["titolo"];?>
			<?php echo $form["attivo"];?>
			<?php echo $form["codice"];?>
			<?php echo $form[PagamentiController::$campoPrezzo];?>
			
			<?php echo $form["utilizzo"] ?? null;?>
			
			<?php if (v("attiva_collegamento_gestionali")) { ?>
			<div class='row'>
				<div class='col-md-6'>
					<?php echo $form["codice_gestionale"];?>
				</div>
				<div class='col-md-6'>
					<?php echo $form["codice_pagamento_pa"];?>
				</div>
			</div>
			<?php } ?>
			
			<?php echo $form["immagine"];?>
			
			<?php echo $form["descrizione"];?>
			
			<?php echo isset($form["istruzioni_pagamento"]) ? $form["istruzioni_pagamento"] : "";?>
			
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
			
			<?php if (isset($record["codice"]) && OrdiniModel::conPagamentoOnline(array("pagamento"=>$record["codice"]))) { ?>
			<div class="panel panel-info">
				<div class="panel-heading">
					<?php echo gtext("Parametri carta");?>
				</div>
				<div class="panel-body">
					<?php echo $form["gateway_pagamento"] ?? "";?>
					
					<?php echo $form["test"];?>
					
					<?php echo $form["alias_account"];?>
					
					<?php echo $form["chiave_segreta"] ?? "";?>
					
					<?php echo $form["public_key"] ?? "";?>
					
					<?php echo $form["private_key"] ?? "";?>
				</div>
			</div>
			<?php } ?>
		</div>
	</form>
</div>
