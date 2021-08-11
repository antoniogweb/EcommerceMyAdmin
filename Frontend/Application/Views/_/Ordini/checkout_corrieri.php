<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_spedizione")) { ?>
<div class="uk-container uk-margin-medium">
	<?php if (count($corrieri) > 1) { ?>
		<div class="box_corrieri">
		<h3><?php echo gtext("Seleziona il corriere");?></h3>
		
		<?php foreach ($corrieri as $corriere) { ?>
		<div class="radio_corriere corriere_<?php echo $corriere["id_corriere"];?>">
		<?php echo Html_Form::radio("id_corriere",$values["id_corriere"],$corriere["id_corriere"],"imposta_corriere","none");?> <?php echo $corriere["titolo"];?>
		</div>
		<?php } ?>
		</div>
		
	<?php } else if (count($corrieri) === 1) { ?>
		<?php foreach ($corrieri as $corriere) { ?>
		<?php echo Html_Form::hidden("id_corriere",$values["id_corriere"]);?>
		<?php } ?>
	<?php } ?>
</div>
<?php } ?>
