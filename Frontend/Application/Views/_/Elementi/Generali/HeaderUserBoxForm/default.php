<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<form action="<?php echo $this->baseUrl."/regusers/login";?>" data-toggle="validator" method="POST">
	<fieldset class="uk-fieldset">
		<div class="uk-margin">
			<label for="header_user_username" class="uk-form-label"><?php echo gtextPlain("e-mail")?> <span aria-hidden="true">*</span></label>
			<div class="uk-form-controls">
				<input id="header_user_username" class="uk-input " autocomplete="username" name="username" type="text" placeholder="<?php echo gtextAttr("Indirizzo e-mail", false)?>" aria-required="true" />
			</div>
		</div>
		<div class="uk-margin">
			<label for="header_user_password" class="uk-form-label"><?php echo gtextPlain("password")?> <span aria-hidden="true">*</span></label>
			<div class="uk-form-controls">
				<input id="header_user_password" class="uk-input " autocomplete="current-password" name="password" type="password" placeholder="<?php echo gtextAttr("Password", false)?>" aria-required="true" />
			</div>
		</div>
		
		<?php include(tpf("Elementi/Pagine/campo-csrf.php")); ?>
		
		<input class="<?php echo v("classe_pulsanti_submit");?> uk-width-1-1" type="submit" name="" value="<?php echo gtextAttr("Accedi");?>" />
	</fieldset>
</form>
