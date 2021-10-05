<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (strcmp($tipoOutput,"web") === 0) { ?>
	<?php if(!isset($actionFromAdmin)) { ?>
		<div class="uk-margin">
			<div><a class="uk-button uk-button-secondary" href='<?php echo $urlPagamento;?>'><span uk-icon="credit-card"></span> <?php echo gtext("Paga adesso");?></a></div>
		</div>
	<?php } else { ?>
		<h2 class="uk-heading-bullet"><?php echo gtext("Dettagli pagamento:");?></h2>
		<p><?php echo gtext("Pagamento tramite carta di credito ancora da eseguire");?></p>
	<?php } ?>
<?php } ?>
