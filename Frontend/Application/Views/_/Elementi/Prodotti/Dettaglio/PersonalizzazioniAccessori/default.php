<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (isset($personalizzazioni_acc) && count($personalizzazioni_acc) > 0) { ?>
<div class="lista_personalizzazioni_prodotto">
	<?php foreach ($personalizzazioni_acc as $pers) { ?>
	<div class="uk-margin uk-width-2-3@m">
		<?php
		$maxLength = $pers["personalizzazioni"]["numero_caratteri"] ? 'maxlength="'.$pers["personalizzazioni"]["numero_caratteri"].'"' : "";
		echo Html_Form::input($pers["personalizzazioni"]["id_pers"],getPersonalizzazioneDaCarrello($pers["personalizzazioni"]["id_pers"],$acc["pages"]["id_page"] ),"uk-input form_input_personalizzazione",null,"style='width:100%;' $maxLength rel='".persfield($pers, "titolo")."'".' placeholder="'.persfield($pers, "titolo").'"');?>
	</div>
	<?php } ?>
</div>
<?php } ?> 
