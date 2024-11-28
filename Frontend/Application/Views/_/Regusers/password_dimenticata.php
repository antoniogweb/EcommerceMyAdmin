<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Accedi")	=>	$this->baseUrl."/regusers/login",
	gtext("Richiesta nuova password") => "",
);

$titoloPagina = gtext("Richiesta nuova password");

include(tpf("/Elementi/Pagine/page_top.php"));
?>
<p class="uk-text-muted"><?php echo gtext("Inserisci l'indirizzo e-mail con il quale ti sei registrato al sito,<br />ti invieremo una mail attraverso la quale potrai ottenere una nuova password");?></p>

<?php echo $notice;?>

<div class="uk-child-width-1-3@m uk-text-center uk-flex uk-flex-center">
	<form class="form_richiedi_nuova_password" action="<?php echo $this->baseUrl."/password-dimenticata";?>" method="POST">
		<div class="uk-margin">
			<label class="uk-form-label uk-text-bold"><?php echo gtext("Indirizzo e-mail");?> *</label>
			<div class="uk-form-controls">
				<input class="uk-input uk-width-1-2@s uk-width-1-1@m class_username" autocomplete="new-password" name="username" type="text" placeholder="<?php echo gtext("Scrivi la tua e-mail", false)?>" />
			</div>
			
			<?php include (tpf("Elementi/Pagine/campo-captcha-registrazione.php"));?>
		</div>
		
		<input class="<?php echo v("classe_pulsanti_submit");?> uk-width-1-2@s uk-width-1-1@m" type="submit" name="invia" value="<?php echo gtext("Richiesta nuova password");?>" title="<?php echo gtext("Richiesta nuova password");?>" />
	</form>
</div>

<?php
include(tpf("/Elementi/Pagine/page_bottom.php"));
