<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$widthPulsante = "uk-width-1-1 uk-width-2-3@s";
?>
<div class="uk-child-width-expand@s uk-text-left uk-grid-divider uk-grid uk-grid-column-large" uk-grid>
	<div class="uk-width-1-2@m uk-text-left">
		<div class="uk-margin-medium-top uk-margin-medium-bottom">
			<?php
			include(tpf(ElementitemaModel::p("LOGIN_SX_TOP","", array(
				"titolo"	=>	"Form login, avviso SX Top",
				"percorso"	=>	"Elementi/Generali/LoginFormSxTop",
			))));
			?>
			
			<?php
// 			$nascondiPlaceholder = true;
			include(tpf(ElementitemaModel::p("LOGIN_FORM","", array(
				"titolo"	=>	"Form login",
				"percorso"	=>	"Elementi/Generali/LoginForm",
			))));
			?>
			
			<?php
			if (!VariabiliModel::confermaUtenteRichiesta() && v("abilita_login_tramite_app"))
				include(tpf(ElementitemaModel::p("LOGIN_APP","", array(
					"titolo"	=>	"Pulsanti di login app esterne",
					"percorso"	=>	"Elementi/Generali/LoginApp",
				))));
			?>
		</div>
	</div>
	<div class="uk-width-1-2@m uk-text-left">
		<div class="uk-margin-medium-top uk-margin-medium-bottom">
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
</div>
