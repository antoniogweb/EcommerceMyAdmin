<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (isset($_SESSION["test_login_effettuato"]))
	unset($_SESSION["test_login_effettuato"]);

$widthPulsante = "uk-width-1-1 uk-width-2-3@s";
?>
<div class="uk-child-width-expand@s uk-text-left uk-grid-divider uk-grid uk-grid-column-large" uk-grid>
	<div class="uk-width-1-2@m uk-text-left">
		<h3><?php echo gtext("Login");?></h3>
		<div class="uk-text-meta"><?php echo gtext("Inserisci Username e Password per continuare come utente loggato.");?></div>
		
		<form class="uk-margin" action = '<?php echo $action;?>' method = 'POST'>
			<?php
			if (!isset($noLoginNotice))
				echo $notice;
			?>
			<fieldset class="uk-fieldset">
				<div class="uk-margin">
					<label class="uk-form-label uk-text-bold"><?php echo gtext("Indirizzo e-mail");?> *</label>
					<div class="uk-form-controls">
						<input class="uk-input uk-width-1-2@s uk-width-1-1@m" autocomplete="new-password" name="username" type="text" />
					</div>
				</div>
				
				<div class="uk-margin">
					<label class="uk-form-label uk-text-bold"><?php echo gtext("Password");?> *</label>
					<div class="uk-form-controls">
						<input class="uk-input uk-width-1-2@s uk-width-1-1@m" autocomplete="new-password" name="password" type="password" />
					</div>
				</div>
				
				<input autocomplete="new-password" class="uk-button uk-button-secondary uk-width-1-1 uk-width-1-3@s" type="submit" name="login" value="<?php echo gtext("Accedi");?>" />
			</fieldset>
		</form>
		
		<?php
		if (!VariabiliModel::confermaUtenteRichiesta() && v("abilita_login_tramite_app"))
			include(tpf(ElementitemaModel::p("LOGIN_APP","", array(
				"titolo"	=>	"Pulsanti di login app esterne",
				"percorso"	=>	"Elementi/Generali/LoginApp",
			))));
		?>
	</div>
	<div class="uk-width-1-2@m uk-text-left">
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
