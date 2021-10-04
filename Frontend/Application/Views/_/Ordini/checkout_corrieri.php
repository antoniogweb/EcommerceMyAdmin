<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_spedizione")) { ?>
	<?php if (count($corrieri) > 1) { ?>
		<div class="uk-container uk-margin-medium">
			<div class="box_corrieri">
				<h2 class="uk-margin-bottom uk-text-emphasis uk-text-large"><?php echo gtext("Seleziona il corriere");?></h2>
				
				<?php foreach ($corrieri as $corriere) { ?>
				<div class="radio_corriere corriere_<?php echo $corriere["id_corriere"];?>">
				<?php echo Html_Form::radio("id_corriere",$values["id_corriere"],$corriere["id_corriere"],"imposta_corriere","none");?> <?php echo $corriere["titolo"];?>
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
