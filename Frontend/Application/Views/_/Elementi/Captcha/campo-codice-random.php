<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$captchaModule = CaptchaModel::getModulo();
$params = $captchaModule->getParams();
?>
<?php if (!User::$logged && !isset($_SESSION["ok_captcha"])) { ?>
<div class="box_entry_dati uk-margin uk-margin-remove-bottom">
	<?php $idCaptchaRandom = "captcha-random-".encodeUrl($params["campo_nascosto"]);?>
	<label for="<?php echo $idCaptchaRandom;?>" class="uk-form-label"><?php echo gtextPlain("Inserisci il codice antispam mostrato nell'immagine");?> <span aria-hidden="true">*</span></label>
	<div class="uk-form-controls">
		<div class="uk-margin-bottom">
			<img src="<?php echo $this->baseUrlSrc."/captcha/index/".$captchaModule->getIDCaptcha();?>" alt="<?php echo gtextAttr("Immagine con codice antispam", false);?>" />
		</div>
		<?php echo Html_Form::input($params["campo_nascosto"],"","uk-input ".$params["campo_nascosto"],$idCaptchaRandom,"aria-required='true' placeholder='".gtextAttr("Inserisci il codice antispam (CAPTCHA) "."*")."'");?>
	</div>
</div>
<?php $captchaModule->incrementaIDCaptcha(); } ?>

