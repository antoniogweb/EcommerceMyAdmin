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

<div class="uk-child-width-1-3@m uk-text-center" uk-grid>
    <div></div>
    <div>
		<form action="<?php echo $this->baseUrl."/password-dimenticata";?>" method="POST">
			<div class="t">
				<input  class="text_input" type="text" name="tessera" value=""/>
			</div>
			
			<fieldset class="uk-fieldset">
				<div class="uk-margin">
					<label class="uk-form-label uk-text-bold"><?php echo gtext("Indirizzo e-mail");?> *</label>
					<div class="uk-form-controls">
						<input class="uk-input uk-width-1-2@s uk-width-1-1@m class_username" autocomplete="new-password" name="username" type="text" placeholder="<?php echo gtext("Scrivi la tua e-mail", false)?>" />
					</div>
				</div>
				
				<input class="uk-button uk-button-secondary uk-width-1-2@s uk-width-1-1@m" type="submit" name="invia" value="<?php echo gtext("Richiesta nuova password");?>" title="<?php echo gtext("Richiesta nuova password");?>" />
			</fieldset>
		</form>
	</div>
	<div></div>
</div>

<?php
include(tpf("/Elementi/Pagine/page_bottom.php"));
