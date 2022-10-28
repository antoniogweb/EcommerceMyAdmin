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
			<?php echo $form["attivo"];?>
			<?php echo $form["prezzo_ivato"];?>
			
			<?php echo $form["descrizione"];?>
			
			<?php echo isset($form["istruzioni_pagamento"]) ? $form["istruzioni_pagamento"] : "";?>
			
			<?php if ($type === "update") { ?>
			<input class="varchar_input form-control" type="hidden" value="<?php echo $id;?>" name="id_n">
			<?php } ?>
			
			<div class="submit_entry">
				<span class="submit_entry_Salva">
					<button id="<?php echo $type;?>Action" class="btn btn-success" name="<?php echo $type;?>Action" type="submit">Salva</button>
					<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
				</span>
			</div>
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
			
			<?php if (isset($record["codice"]) && $record["codice"] == "carta_di_credito") { ?>
			<div class="panel panel-info">
				<div class="panel-heading">
					<?php echo gtext("Parametri carta");?>
				</div>
				<div class="panel-body">
					<?php echo $form["gateway_pagamento"];?>
					
					<?php echo $form["test"];?>
					
					<?php echo $form["alias_account"];?>
					
					<?php echo $form["chiave_segreta"];?>
				</div>
			</div>
			<?php } ?>
		</div>
	</form>
</div>
