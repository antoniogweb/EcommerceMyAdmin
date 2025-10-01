<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Modifica password") => "",
);

$titoloPagina = gtext("Modifica password");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "password";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>

<div class="uk-text-center">
	<?php echo $notice; ?>
</div>

<form class="form_cambio_password" action="<?php echo $this->baseUrl."/modifica-password";?>" method="POST">
	<div class="uk-margin form_cambio_password_box" style="z-index:3;">
		<label class="uk-form-label"><?php echo gtext("Vecchia password");?></label>
		<div class="uk-form-controls uk-position-relative">
			<?php echo Html_Form::password("old",$values['old'],"uk-input class_old");?>
			<?php include tpf("Elementi/mostra_nascondi_password.php")?>
		</div>
	</div>
	
	<div class="uk-margin form_cambio_password_box" style="z-index:2;">
		<label class="uk-form-label"><?php echo gtext("Password");?></label>
		<div class="uk-form-controls uk-position-relative">
			<?php echo Html_Form::password("password",$values['password'],"uk-input class_password ".VariabiliModel::classeHelpWizardPassword());?>
			<?php include tpf("Elementi/mostra_nascondi_password.php")?>
		</div>
	</div>
	
	<div class="uk-margin form_cambio_password_box" style="z-index:1;">
		<label class="uk-form-label"><?php echo gtext("Conferma password");?></label>
		<div class="uk-form-controls uk-position-relative">
			<?php echo Html_Form::password("confirmation",$values['confirmation'],"uk-input class_confirmation ".VariabiliModel::classeHelpWizardPassword());?>
			<?php include tpf("Elementi/mostra_nascondi_password.php")?>
		</div>
	</div>
	
	<?php /*include (tpf("Elementi/Pagine/campo-captcha-registrazione.php"));*/?>
	
	<input class="uk-button uk-button-secondary" type="submit" name="updateAction" value="<?php echo gtext("Modifica password", false);?>" title="<?php echo gtext("Modifica password", false);?>" />
</form>

<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
