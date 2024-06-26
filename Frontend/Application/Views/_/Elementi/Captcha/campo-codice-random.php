<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$captchaModule = CaptchaModel::getModulo();
$params = $captchaModule->getParams();
?>
<?php if (!User::$logged && !isset($_SESSION["ok_captcha"])) { ?>
<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
	<label class="uk-form-label"><?php echo gtext("Inserisci il codice antispam mostrrato nell'immagine");?> *</label>
	<div class="uk-form-controls">
		<div class="uk-margin-bottom">
			<img src="<?php echo $this->baseUrlSrc."/captcha/index/".$captchaModule->getIDCaptcha();?>" />
		</div>
		<?php echo Html_Form::input($params["campo_nascosto"],"","uk-input ".$params["campo_nascosto"],null,"placeholder='".gtext("Inserisci il codice antispam (CAPTCHA) "."*")."'");?>
	</div>
</div>
<?php $captchaModule->incrementaIDCaptcha(); } ?>

