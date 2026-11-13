<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$captchaModule = CaptchaModel::getModulo();
echo "<div class='".v("alert_error_class")."'>".gtext("Sembra che ci sia un problema con la verifica dell'antispam (CAPTCHA).")."<br />".gtext("Per favore inserisci il codice mostrato nell'immagine sottostante e invia nuovamente il form");?></div>
<div class="uk-margin-bottom uk-background-muted uk-padding">
	<div class="uk-text-left box_entry_dati uk-margin uk-margin-remove-bottom">
		<label for="captcha-random-secondo-livello" class="uk-form-label"><?php echo gtextPlain("Inserisci il codice antispam mostrato nell'immagine");?> <span aria-hidden="true">*</span></label>
		<div class="uk-form-controls">
			<div class="uk-margin-bottom">
				<img src="<?php echo $this->baseUrlSrc."/captcha/index/".$captchaModule->getIDCaptcha();?>" alt="<?php echo gtextAttr("Immagine con codice antispam", false);?>" />
			</div>
			<div class="uk-margin-top uk-width-1-2@m">
				<?php echo Html_Form::input("codice_random","","uk-input codice_random codice_random_secondo_livello","captcha-random-secondo-livello","aria-required='true' placeholder='".gtextAttr("Inserisci il codice antispam (CAPTCHA) "."*")."'");?>
			</div>
		</div>
	</div>
</div>
<?php $captchaModule->incrementaIDCaptcha(); ?>
