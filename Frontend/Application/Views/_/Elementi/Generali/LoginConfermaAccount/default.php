<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<h4 class=""><?php echo gtext("Per completare la verifica digita nel campo sottostante il codice a ".v("conferma_registrazione_numero_cifre_codice_verifica")." cifre");?><br /><?php echo gtext("che è stato inviato all'indirizzo");?><b> <?php echo partiallyHideEmail($user["username"]);?></b></h4>
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
			</fieldset>
		</form>
		
		<?php if (isset($_SESSION['token_reinvio'])) { ?>
		<br /><a href="<?php echo $this->baseUrl."/send-confirmation";?>"><?php echo gtext("Invia nuovamente il codice a ".v("conferma_registrazione_numero_cifre_codice_verifica")." cifre");?> <span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/mail.svg");?></span></a>
		<?php } ?>
	</div>
</div>
