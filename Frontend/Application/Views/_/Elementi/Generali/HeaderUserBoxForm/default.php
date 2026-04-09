<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<form autocomplete="new-password" action="<?php echo $this->baseUrl."/regusers/login";?>" data-toggle="validator" method="POST">
	<fieldset class="uk-fieldset">
		<div class="uk-margin">
			<label class="uk-form-label"><?php echo gtext("e-mail")?> *</label>
			<div class="uk-form-controls">
				<input class="uk-input " autocomplete="new-password" name="username" type="text" placeholder="<?php echo gtext("Indirizzo e-mail", false)?>" />
			</div>
		</div>
		<div class="uk-margin">
			<label class="uk-form-label"><?php echo gtext("password")?> *</label>
			<div class="uk-form-controls">
				<input class="uk-input " autocomplete="new-password" name="password" type="password" placeholder="<?php echo gtext("Password", false)?>" />
			</div>
		</div>
		
		<input autocomplete="new-password" class="<?php echo v("classe_pulsanti_submit");?> uk-width-1-1" type="submit" name="" value="<?php echo gtext("Accedi");?>" />
	</fieldset>
</form> 
