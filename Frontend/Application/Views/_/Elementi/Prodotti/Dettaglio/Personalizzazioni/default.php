<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($personalizzazioni) && count($personalizzazioni) > 0) { ?>
<div class="lista_personalizzazioni_prodotto">
	<?php foreach ($personalizzazioni as $pers) { ?>
	<div class="uk-margin uk-width-1-2@m">
		<?php
		$maxLength = $pers["personalizzazioni"]["numero_caratteri"] ? 'maxlength="'.$pers["personalizzazioni"]["numero_caratteri"].'"' : "";
		echo Html_Form::input($pers["personalizzazioni"]["id_pers"],getPersonalizzazioneDaCarrello($pers["personalizzazioni"]["id_pers"]),"uk-input form_input_personalizzazione",null,"$maxLength rel='".persfield($pers, "titolo")."'".' placeholder="'.persfield($pers, "titolo").'"');?>
	</div>
	<?php } ?>
</div>
<?php } ?>
