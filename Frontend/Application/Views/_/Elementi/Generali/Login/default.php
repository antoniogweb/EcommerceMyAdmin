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
				
				<div>
					<div class="uk-button uk-button-secondary uk-width-1-2@s uk-width-1-1@m spinner uk-hidden" uk-spinner="ratio: .70"></div>
					<input autocomplete="new-password" class="uk-button uk-button-secondary uk-width-1-2@s uk-width-1-1@m btn_submit_form" type="submit" name="login" value="<?php echo gtext("Accedi");?>" />
				</div>
			</fieldset>
		</form>
		
		<?php
		if (!VariabiliModel::confermaUtenteRichiesta() && v("abilita_login_tramite_app"))
			include(tpf(ElementitemaModel::p("LOGIN_APP","", array(
				"titolo"	=>	"Pulsanti di login app esterne",
				"percorso"	=>	"Elementi/Generali/LoginApp",
			))));
		?>
		
		<?php
		if (!isset($noLoginRegistrati))
			include(tpf(ElementitemaModel::p("LOGIN_REGISTRAZIONE","", array(
				"titolo"	=>	"Link alla registrazione",
				"percorso"	=>	"Elementi/Generali/LoginRegistrazione",
			))));
		?>
		
		<?php
		include(tpf(ElementitemaModel::p("LOGIN_PASSWORD","", array(
			"titolo"	=>	"Link al recupero password",
			"percorso"	=>	"Elementi/Generali/LoginPassword",
		))));
		?>
	</div>
</div>
