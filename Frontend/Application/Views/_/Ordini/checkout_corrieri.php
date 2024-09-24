<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_spedizione")) { ?>
	<?php if (count($corrieri) > 1) { ?>
		<hr class="uk-divider-icon uk-margin-medium-bottom uk-hidden@m">
		<div class="uk-container blocco_checkout">
			<div class="box_corrieri">
				<h2 class="<?php echo v("classi_titoli_checkout");?>">
					<span uk-icon="icon:clock;ratio:1" class="uk-margin-right uk-hidden@m"></span><?php echo gtext("Modalità di consegna");?>
				</h2>
				
				<?php foreach ($corrieri as $corriere) { ?>
				<div class="uk-padding-small <?php if ($values["id_corriere"] == $corriere["id_corriere"]) { ?>spedizione_selezionata<?php } ?> radio_corriere_select radio_corriere corriere_<?php echo $corriere["id_corriere"];?>">
					<?php echo Html_Form::radio("id_corriere",$values["id_corriere"],$corriere["id_corriere"],"imposta_corriere","none");?><span class="uk-margin-left"><?php echo gtext($corriere["titolo"]);?></span>
				</div>
				<?php } ?>
			</div>
		</div>
		
	<?php } else if (count($corrieri) === 1) { ?>
		<?php foreach ($corrieri as $corriere) { ?>
		<?php echo Html_Form::hidden("id_corriere",$values["id_corriere"]);?>
		<?php } ?>
	<?php } ?>
<?php } ?>
