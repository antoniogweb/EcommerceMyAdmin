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

<form class="" action="<?php echo $this->baseUrl."/modifica-password";?>" method="POST">
	<div class="uk-margin">
		<label class="uk-form-label"><?php echo gtext("Vecchia password");?></label>
		<div class="uk-form-controls">
			<?php echo Html_Form::password("old",$values['old'],"uk-input  class_old");?>
		</div>
	</div>
	
	<div class="uk-margin">
		<label class="uk-form-label"><?php echo gtext("Password");?></label>
		<div class="uk-form-controls">
			<?php echo Html_Form::password("password",$values['password'],"uk-input  class_password");?>
		</div>
	</div>
	
	<div class="uk-margin">
		<label class="uk-form-label"><?php echo gtext("Conferma password");?></label>
		<div class="uk-form-controls">
			<?php echo Html_Form::password("confirmation",$values['confirmation'],"uk-input  class_confirmation");?>
		</div>
	</div>
	
	<?php include (tpf("Elementi/Pagine/campo-captcha-registrazione.php"));?>
	
	<input class="uk-button uk-button-secondary" type="submit" name="updateAction" value="<?php echo gtext("Modifica password", false);?>" title="<?php echo gtext("Modifica password", false);?>" />
</form>

<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
