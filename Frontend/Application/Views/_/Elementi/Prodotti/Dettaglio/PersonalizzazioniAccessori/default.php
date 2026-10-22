<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (!isset($classePersonalizzazioni))
	$classePersonalizzazioni = "uk-margin-small uk-width-2-3@m";
?>
<?php if (isset($personalizzazioni_acc) && count($personalizzazioni_acc) > 0) { ?>
<div class="lista_personalizzazioni_prodotto">
	<?php foreach ($personalizzazioni_acc as $pers) { ?>
	<div class="<?php echo $classePersonalizzazioni;?>">
		<?php
		$idPersonalizzazione = "personalizzazione-accessorio-".(int)$acc["pages"]["id_page"]."-".(int)$pers["personalizzazioni"]["id_pers"];
		$maxLength = $pers["personalizzazioni"]["numero_caratteri"] ? 'maxlength="'.$pers["personalizzazioni"]["numero_caratteri"].'"' : "";
		?>
		<label for="<?php echo $idPersonalizzazione;?>" class="accessibility-visually-hidden"><?php echo persfield($pers, "titolo");?></label>
		<?php echo Html_Form::input($pers["personalizzazioni"]["id_pers"],getPersonalizzazioneDaCarrello($pers["personalizzazioni"]["id_pers"],$acc["pages"]["id_page"] ),"uk-input form_input_personalizzazione",$idPersonalizzazione,"style='width:100%;' $maxLength rel='".persfield($pers, "titolo")."'".' placeholder="'.persfield($pers, "titolo").'"');?>
	</div>
	<?php } ?>
</div>
<?php } ?> 
