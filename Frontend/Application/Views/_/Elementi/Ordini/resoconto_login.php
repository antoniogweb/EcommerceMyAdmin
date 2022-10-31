<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!$islogged) { ?>
<div class="">
	<div class="uk-margin">
		<div class="uk-text-small">
			<?php echo gtext("Hai già un account?");?> <a class="showlogin show_form_login_checkout" href="#"><?php echo gtext("Clicca qui per accedere");?></a><br />
			<?php echo gtext("Altrimenti continua pure inserendo i tuoi dati.");?>
		</div>
	</div>
	
	<div id="login" style="display:none;">
		<?php
		ElementitemaModel::$percorsi["FORM_LOGIN"]["nome_file"] = "default";
		ElementitemaModel::$percorsi["LOGIN_PASSWORD"]["nome_file"] = "default";
		include(tpf(ElementitemaModel::p("CHECKOUT_LOGIN","", array(
			"titolo"	=>	"Form login al checkout",
			"percorso"	=>	"Elementi/Ordini/Login",
		))));
		?>
		<br />
	</div>
</div>
<?php } ?>
