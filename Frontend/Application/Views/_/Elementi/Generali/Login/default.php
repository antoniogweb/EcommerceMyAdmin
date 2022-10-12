<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-child-width-1-3@m uk-text-center uk-flex uk-flex-center">
    <div>
		<form class="" action = '<?php echo $action;?>' method = 'POST'>
			<?php
			if (!isset($noLoginNotice))
				echo $notice;
			?>
			<fieldset class="uk-fieldset">
				<div class="uk-margin">
					<label class="uk-form-label uk-text-bold"><?php echo gtext("Indirizzo e-mail");?> *</label>
					<div class="uk-form-controls">
						<input class="uk-input uk-width-1-2@s uk-width-1-1@m" autocomplete="new-password" name="username" type="text" placeholder="<?php echo gtext("Scrivi la tua e-mail", false)?>" />
					</div>
				</div>
				
				<div class="uk-margin">
					<label class="uk-form-label uk-text-bold"><?php echo gtext("Password");?> *</label>
					<div class="uk-form-controls">
						<input class="uk-input uk-width-1-2@s uk-width-1-1@m" autocomplete="new-password" name="password" type="password" placeholder="<?php echo gtext("Scrivi la tua password", false)?>" />
					</div>
				</div>
				
				<input autocomplete="new-password" class="uk-button uk-button-secondary uk-width-1-2@s uk-width-1-1@m" type="submit" name="login" value="<?php echo gtext("Accedi");?>" />
			</fieldset>
		</form>
		
		<?php
		if (!VariabiliModel::confermaUtenteRichiesta() && v("abilita_login_tramite_app"))
			include(tpf(ElementitemaModel::p("LOGIN_APP","", array(
				"titolo"	=>	"Pulsanti di login app esterne",
				"percorso"	=>	"Elementi/Generali/LoginApp",
			))));
		?>
		
		<?php if (!isset($noLoginRegistrati)) { ?>
		<div class="uk-margin uk-margin-large-top uk-text-small box_info_registrazione">
			<?php echo gtext("Vuoi creare un nuovo account?")?> <a class="uk-text-meta" href="<?php echo $this->baseUrl."/crea-account".$redirectQueryString;?>" class=""><?php echo gtext("Registrati");?></a>
		</div>
		<?php } ?>

		<div class="uk-margin uk-text-small box_info_registrazione">
			<a class="uk-text-meta" href="<?php echo $this->baseUrl."/password-dimenticata";?>"><?php echo gtext("Hai dimenticato la password?");?></a>
		</div>
	</div>
</div>
