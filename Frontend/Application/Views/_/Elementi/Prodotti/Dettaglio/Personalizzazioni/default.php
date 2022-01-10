<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (!isset($classePersonalizzazioniBox))
	$classePersonalizzazioniBox = "";

if (!isset($classePersonalizzazioni))
	$classePersonalizzazioni = "uk-margin-small uk-width-2-3@m";
?>
<?php if (isset($personalizzazioni) && count($personalizzazioni) > 0) { ?>
<div class="lista_personalizzazioni_prodotto <?php echo $classePersonalizzazioniBox;?>">
	<?php foreach ($personalizzazioni as $pers) { ?>
	<div class="<?php echo $classePersonalizzazioni;?>">
		<?php
		$maxLength = $pers["personalizzazioni"]["numero_caratteri"] ? 'maxlength="'.$pers["personalizzazioni"]["numero_caratteri"].'"' : "";
		echo Html_Form::input($pers["personalizzazioni"]["id_pers"],getPersonalizzazioneDaCarrello($pers["personalizzazioni"]["id_pers"]),"uk-input form_input_personalizzazione",null,"$maxLength rel='".persfield($pers, "titolo")."'".' placeholder="'.persfield($pers, "titolo").'"');?>
	</div>
	<?php } ?>
</div>
<?php } ?>
