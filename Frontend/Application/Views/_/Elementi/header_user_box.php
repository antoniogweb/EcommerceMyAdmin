<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (!isset($ukdropdown))
	$ukdropdown = "pos: bottom-right; offset: -10; delay-hide: 200;";

if (!isset($divStyle))
	$divStyle = "min-width: 250px;";
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
				<a class="uk-text-secondary uk-text-bold" href="<?php echo $this->baseUrl."/crea-account";?>"><?php echo gtext("Crea un account")?></a>
			<?php } else { ?>
				<span class="uk-text-secondary uk-text-bold"><?php echo gtext("Esegui il login")?></span>
			<?php } ?>
			<hr />
		</div>
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
		<br />
		<a class="uk-text-small uk-text-secondary" href="<?php echo $this->baseUrl."/password-dimenticata";?>"><?php echo gtext("Hai dimenticato la password?");?></a>
	</div>
	<?php } ?>
</div>
