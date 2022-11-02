<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_spedizione")) { ?>
	<?php if (count($corrieri) > 1) { ?>
		<div class="uk-container">
			<div class="box_corrieri">
				<h2 class="<?php echo v("classi_titoli_checkout");?>">
					<span uk-icon="icon:list;ratio:1.2" class="uk-margin-right uk-hidden@m"></span><?php echo gtext("Tipo di spedizione");?>
				</h2>
				
				<?php foreach ($corrieri as $corriere) { ?>
				<div class="uk-margin-small radio_corriere corriere_<?php echo $corriere["id_corriere"];?>">
					<label><?php echo Html_Form::radio("id_corriere",$values["id_corriere"],$corriere["id_corriere"],"imposta_corriere","none");?><span class="uk-margin-left"><?php echo $corriere["titolo"];?></span></label>
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
