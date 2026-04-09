<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (!isset($ukdropdown))
	$ukdropdown = "pos: bottom-right; offset: -10; delay-hide: 200;";

if (!isset($divStyle))
	$divStyle = "min-width: 250px;";

if (!isset($creaAccountLink))
	$creaAccountLink = Url::routeToUrl("crea-account");
?>
<div class="form_login_dropdown uk-padding-small uk-margin-remove uk-dropdown" uk-dropdown="<?php echo $ukdropdown;?>" style="<?php echo $divStyle;?>">
	<?php if ($islogged) { ?>
	<?php
	include(tpf(ElementitemaModel::p("HEADER_USER_BOX_LOGGED","", array(
		"titolo"	=>	"Box dropdown utente loggato",
		"percorso"	=>	"Elementi/Generali/HeaderUserBoxLogged",
	))));
	?>
	<?php } else { ?>
	<div class="uk-dropdown-nav">
		<div class="uk-text-small uk-text-right header_login_popup">
			<?php if (v("permetti_registrazione")) { ?>
				<a class="uk-text-secondary uk-text-bold" href="<?php echo $this->baseUrl."/".$creaAccountLink;?>"><?php echo gtext("Crea un account")?></a>
			<?php } else { ?>
				<span class="uk-text-secondary uk-text-bold"><?php echo gtext("Esegui il login")?></span>
			<?php } ?>
			<hr />
		</div>
		<?php
		include(tpf(ElementitemaModel::p("HEADER_USER_BOX_FORM","", array(
			"titolo"	=>	"Form login nell'header",
			"percorso"	=>	"Elementi/Generali/HeaderUserBoxForm",
		))));
		?>
		<br />
		<a class="uk-text-small uk-text-secondary" href="<?php echo $this->baseUrl."/password-dimenticata";?>"><?php echo gtext("Hai dimenticato la password?");?></a>
	</div>
	<?php } ?>
</div>
