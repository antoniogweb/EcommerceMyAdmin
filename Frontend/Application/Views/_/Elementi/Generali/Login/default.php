<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-child-width-1-3@m uk-text-center uk-flex uk-flex-center">
    <div>
		<?php
		$classePulsanteLogin = "uk-button uk-button-secondary uk-width-1-2@s uk-width-1-1@m";
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
