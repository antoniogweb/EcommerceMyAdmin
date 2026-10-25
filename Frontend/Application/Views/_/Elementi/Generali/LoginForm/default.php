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
			<label for="login_username" class="uk-form-label uk-text-bold"><?php echo gtextPlain("Indirizzo e-mail");?> <span aria-hidden="true">*</span></label>
			<div class="uk-form-controls">
				<input id="login_username" class="uk-input uk-width-1-2@s uk-width-1-1@m class_login_username" autocomplete="new-password" name="username" type="text" placeholder="<?php echo !isset($nascondiPlaceholder) ? gtextAttr("Scrivi la tua e-mail", false) : "";?>" aria-required="true" />
			</div>
		</div>
		
		<div class="uk-margin">
			<label for="login_password" class="uk-form-label uk-text-bold"><?php echo gtextPlain("Password");?> <span aria-hidden="true">*</span></label>
			<div class="uk-form-controls uk-position-relative">
				<input id="login_password" class="uk-input uk-width-1-2@s uk-width-1-1@m" autocomplete="new-password" name="password" type="password" placeholder="<?php echo !isset($nascondiPlaceholder) ? gtextAttr("Scrivi la tua password", false) : "";?>" aria-required="true" />
				<?php include tpf("Elementi/mostra_nascondi_password.php");?>
			</div>
		</div>
		
		<?php include(tpf("Elementi/Pagine/campo-csrf.php")); ?>
		
		<div>
			<div class="<?php echo $classePulsanteLogin;?> spinner uk-hidden" uk-spinner="ratio: .70"></div>
			<input autocomplete="new-password" class="<?php echo $classePulsanteLogin;?> btn_submit_form" type="submit" name="login" value="<?php echo gtextAttr("Accedi");?>" />
		</div>
	</fieldset>
</form> 
