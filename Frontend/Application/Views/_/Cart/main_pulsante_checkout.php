<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (!isset($testoProcediAdAcquisto))
	$testoProcediAdAcquisto = "PROCEDI ALL'ACQUISTO";
?>
<div class="uk-margin">
	<div class="<?php echo v("classe_pulsanti_submit");?> uk-width-1-1 spinner uk-hidden" uk-spinner="ratio: .70"></div>
	<?php if ($this->controller == "cart" && $numeroGiftCardInCarrello > 0) { ?>
	<div class="vai_la_checkout btn_submit_form <?php echo v("classe_pulsanti_submit");?> uk-width-1-1"><?php echo gtext($testoProcediAdAcquisto);?></div>
	<?php } else { ?>
	<a class="btn_submit_form <?php echo v("classe_pulsanti_submit");?> uk-width-1-1" href="<?php echo $this->baseUrl."/".VariabiliModel::paginaAutenticazione();?>"><?php echo gtext($testoProcediAdAcquisto);?></a>
	<?php } ?>
</div>
