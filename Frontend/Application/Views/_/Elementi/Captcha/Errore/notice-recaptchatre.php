<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php echo "<div class='".v("alert_error_class")."'>".gtext("Sembra che ci sia un problema con la verifica dell'antispam (CAPTCHA).")."<br />".gtext("Per favore inserisci il codice mostrato nell'immagine sottostante e invia nuovamente il form");?></div>
<div class="uk-margin-bottom uk-background-muted uk-padding">
	<div class="uk-text-left box_entry_dati uk-margin uk-margin-remove-bottom">
		<label class="uk-form-label"><?php echo gtext("Inserisci il codice antispam mostrato nell'immagine");?> *</label>
		<div class="uk-form-controls">
			<div class="uk-margin-bottom">
				<img src="<?php echo $this->baseUrlSrc."/captcha/index"?>" />
			</div>
			<div class="uk-margin-top uk-width-1-2@m">
				<?php echo Html_Form::input("codice_random","","uk-input codice_random codice_random_secondo_livello",null,"placeholder='".gtext("Inserisci il codice antispam (CAPTCHA) "."*")."'");?>
			</div>
		</div>
	</div>
</div>
