<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-margin-large-top">
	<h3><?php echo gtext("Hai dimenticato la password?");?></h3>
	<div class="uk-text-meta"><?php echo gtext("Scrivi il tuo indirizzo e-mail e premi invia per richiedere una nuova password.");?></div>

	<form class="uk-margin" action="<?php echo $this->baseUrl."/password-dimenticata";?>" method="POST">
		<fieldset class="uk-fieldset">
			<div class="uk-margin">
				<label class="uk-form-label uk-text-bold"><?php echo gtext("Indirizzo e-mail");?> *</label>
				<div class="uk-form-controls">
					<input class="uk-input uk-width-1-2@s uk-width-1-1@m class_username" autocomplete="new-password" name="username" type="text" />
				</div>
				
				<?php include (tpf("Elementi/Pagine/campo-captcha-registrazione.php"));?>
			</div>
			
			<div>
				<div class="uk-button uk-button-secondary uk-width-1-1 uk-width-1-3@s spinner uk-hidden" uk-spinner="ratio: .70"></div>
				<input class="uk-button uk-button-secondary uk-width-1-1 uk-width-1-3@s btn_submit_form" type="submit" name="invia" value="<?php echo gtext("Invia");?>" title="<?php echo gtext("Richiedi una nuova password");?>" />
			</div>
		</fieldset>
	</form>
</div>
