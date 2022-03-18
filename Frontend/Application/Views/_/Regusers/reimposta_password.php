<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Accedi")	=>	$this->baseUrl."/regusers/login",
	gtext("Richiesta nuova password")	=>	$this->baseUrl."/password-dimenticata",
	gtext("Imposta la password") => "",
);

$titoloPagina = gtext("Imposta la password");

include(tpf("/Elementi/Pagine/page_top.php"));
?>
<?php echo $notice;?>

<div class="uk-child-width-1-3@m uk-text-center" uk-grid>
    <div></div>
    <div>
		<form action="<?php echo $this->baseUrl."/reimposta-password/$forgot_token";?>" method="POST">
			<fieldset class="uk-fieldset">
				<div class="uk-margin">
					<label class="uk-form-label uk-text-bold"><?php echo gtext("Password");?></label>
					<div class="uk-form-controls">
						<?php echo Html_Form::password("password",$values['password'],"uk-input uk-width-1-2@s uk-width-1-1@m class_password",null,"placeholder='".gtext("Scrivi la nuova password", false)."'");?>
					</div>
				</div>
				
				<div class="uk-margin">
					<label class="uk-form-label uk-text-bold"><?php echo gtext("Conferma password");?></label>
					<div class="uk-form-controls">
						<?php echo Html_Form::password("confirmation",$values['confirmation'],"uk-input uk-width-1-2@s uk-width-1-1@m class_confirmation",null,"placeholder='".gtext("Conferma la nuova password", false)."'");?>
					</div>
				</div>
				
				<input class="uk-button uk-button-secondary uk-width-1-2@s uk-width-1-1@m" type="submit" name="invia" value="<?php echo gtext("Imposta la password");?>" title="<?php echo gtext("Imposta la password");?>" />
			</fieldset>
		</form>
	</div>
	<div></div>
</div>
<?php
include(tpf("/Elementi/Pagine/page_bottom.php"));
