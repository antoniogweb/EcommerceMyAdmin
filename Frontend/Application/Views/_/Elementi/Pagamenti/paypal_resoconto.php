<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (strcmp($tipoOutput,"web") === 0) { ?>
	<?php if(!isset($actionFromAdmin) && isset($pulsantePaypal)) { ?>
		<?php if (PagamentiModel::gateway(array(), false, "paypal")->isPaypalCheckout()) { ?>
			<br /><?php echo $pulsantePaypal;?>
		<?php } else { ?>
			<div class="pulsante_paypal"><br /><?php echo $pulsantePaypal;?></div>
		<?php } ?>
	<?php } else { ?>
		<h2 class="uk-heading-bullet"><?php echo gtext("Dettagli pagamento:");?></h2>
		<p><?php echo gtext("Pagamento tramite paypal ancora da eseguire");?></p>
	<?php } ?>
<?php } ?>
