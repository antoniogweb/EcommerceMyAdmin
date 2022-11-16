<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (!isset($classePulsanteLogin))
	$classePulsanteLogin = v("classe_pulsanti_submit")." uk-width-1-1 uk-width-1-3@s";
?>
<form class="uk-margin" action = '<?php echo $action;?>' method = 'POST'>
	<?php
	if (!isset($noLoginNotice))
		echo $notice;
	?>
	<fieldset class="uk-fieldset">
		<div class="uk-margin">
			<label class="uk-form-label uk-text-bold"><?php echo gtext("Indirizzo e-mail");?> *</label>
			<div class="uk-form-controls">
				<input class="uk-input uk-width-1-2@s uk-width-1-1@m" autocomplete="new-password" name="username" type="text" placeholder="<?php echo !isset($nascondiPlaceholder) ? gtext("Scrivi la tua e-mail", false) : "";?>" />
			</div>
		</div>
		
		<div class="uk-margin">
			<label class="uk-form-label uk-text-bold"><?php echo gtext("Password");?> *</label>
			<div class="uk-form-controls">
				<input class="uk-input uk-width-1-2@s uk-width-1-1@m" autocomplete="new-password" name="password" type="password" placeholder="<?php echo !isset($nascondiPlaceholder) ? gtext("Scrivi la tua password", false) : "";?>" />
			</div>
		</div>
		
		<div>
			<div class="<?php echo $classePulsanteLogin;?> spinner uk-hidden" uk-spinner="ratio: .70"></div>
			<input autocomplete="new-password" class="<?php echo $classePulsanteLogin;?> btn_submit_form" type="submit" name="login" value="<?php echo gtext("Accedi");?>" />
		</div>
	</fieldset>
</form> 
