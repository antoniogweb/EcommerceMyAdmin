<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (strcmp($tipoOutput,"web") === 0) { ?>
	<?php if(!isset($actionFromAdmin)) { ?>
		<div class="uk-margin">
			<?php echo $pulsantePaga;?>
		</div>
	<?php } else { ?>
		<h2 class="<?php echo v("classi_titoli_resoconto_ordine");?>"><?php echo gtext("Dettagli pagamento:");?></h2>
		<p><?php echo gtext("Pagamento tramite carta di credito ancora da eseguire");?></p>
	<?php } ?>
<?php } ?>
