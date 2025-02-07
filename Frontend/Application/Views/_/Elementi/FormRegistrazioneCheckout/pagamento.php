<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (OpzioniModel::isAttiva("CAMPI_SALVATAGGIO_UTENTE", "pagamento")) { ?>
<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
	<label class="uk-form-label"><?php echo gtext("Metodo di pagamento");?> *</label>
	<div class="uk-form-controls">
		<?php echo Html_Form::select("pagamento",$values['pagamento'],OrdiniModel::$pagamenti,"uk-select class_pagamento",null,"yes");?>
	</div>
</div>
<?php } ?>
