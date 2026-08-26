<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (strcmp($tipoOutput,"web") === 0) { ?>
	<?php if(!isset($actionFromAdmin) && isset($pulsantePaga)) { ?>
		<div class="uk-margin">
			<?php echo $pulsantePaga;?>
		</div>
	<?php } else { ?>
		<h2 class="<?php echo v("classi_titoli_resoconto_ordine");?>"><?php echo gtextPlain("Dettagli pagamento:");?></h2>
		<p><?php echo gtextPlain("Pagamento tramite Satispay ancora da eseguire");?></p>
	<?php } ?>
<?php } ?>
