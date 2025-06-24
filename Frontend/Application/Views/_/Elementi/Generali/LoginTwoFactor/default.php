<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<h4 class=""><?php echo gtext("Per completare l'accesso, digita nel campo sottostante il codice a ".v("autenticazione_due_fattori_numero_cifre_front")." cifre che ti è stato inviato via mail all'indirizzo");?> <b><?php echo partiallyHideEmail($user["username"]);?></b></h4>
<br />
<div class="uk-child-width-1-3@m uk-text-center uk-flex uk-flex-center">
    <div>
		<?php
		if (!isset($classePulsanteTwoFactor))
			$classePulsanteTwoFactor = v("classe_pulsanti_submit");
		?>
		<form class="uk-margin" action = '<?php echo $action;?>' method = 'POST'>
			<?php $flash = flash("notice");?>
			<?php echo $flash;?>
			<fieldset class="uk-fieldset">
				<div class="uk-margin">
					<label class="uk-form-label uk-text-bold"><?php echo gtext("Codice inviato via e-mail");?> *</label>
					<div class="uk-form-controls">
						<input class="uk-input uk-width-1-2@s uk-width-1-1@m" autocomplete="new-password" name="codice" type="text" placeholder="<?php echo !isset($nascondiPlaceholder) ? gtext("Scrivi qui il codice..", false) : "";?>" />
					</div>
				</div>
				
				<div>
					<div class="<?php echo $classePulsanteTwoFactor;?> uk-width-1-1 uk-width-1-2@s spinner uk-hidden" uk-spinner="ratio: .70"></div>
					<input autocomplete="new-password" class="<?php echo $classePulsanteTwoFactor;?> uk-width-1-1 uk-width-1-2@s btn_submit_form" type="submit" name="login" value="<?php echo gtext("Invia");?>" />
				</div>
				<div class="uk-margin-top">
					<a class="uk-button uk-button-default uk-width-1-1 uk-width-1-2@s" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/login";?>"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/arrow-left.svg");?></span> <?php echo gtext("Torna");?></a>
				</div>
			</fieldset>
		</form>
		
		<?php if ($sessioneTwo["numero_invii_codice"] < (int)v("autenticazione_due_fattori_numero_massimo_invii_codice_front")) { ?>
		<br /><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/twofactorsendmail/".RegusersModel::$redirectQueryString;?>"><?php echo gtext("Invia nuovamente il codice a ".v("autenticazione_due_fattori_numero_cifre_front")." cifre");?> <span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/mail.svg");?></span></a>
		<?php } ?>
	</div>
</div>
