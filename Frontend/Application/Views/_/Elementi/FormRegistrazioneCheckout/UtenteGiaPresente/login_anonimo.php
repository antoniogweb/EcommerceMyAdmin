<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$alertAnonimo = v("permetti_acquisto_anonimo") ? gtext("oppure decidere di completare l'acquisto come utente ospite.", false) : "";?>

<div>
	<?php echo gtext("Il suo indirizzo email è già presente nel nostro sito web.",false)?><br />
	<?php echo gtext("Può eseguire il login",false);?> <?php echo $alertAnonimo;?>
</div>
</div>
<div class="uk-margin-top uk-grid" uk-grid>
	<div class="uk-width-1-1 uk-width-1-2@s uk-text-left">
		<a href="<?php Url::getRoot();?>regusers/login?redirect=/checkout" class="uk-width-1-1 uk-width-auto@s uk-button uk-button-primary">
			<span class="uk-margin-small-right" uk-icon="icon: user; ratio: 0.9"></span><?php echo gtext("Esegui il login");?>
		</a>
		<div class="uk-margin-small uk-text-small">
			<a class="uk-text-meta" href="<?php echo Url::getRoot()."password-dimenticata";?>"><?php echo gtext("Hai dimenticato la password?");?></a>
		</div>
	</div>
	<?php if (v("permetti_acquisto_anonimo")) { ?>
	<div class="uk-width-1-1 uk-width-1-2@s uk-text-right">
		<a href="" class="uk-width-1-1 uk-width-auto@s uk-button uk-button-default">
			<?php echo gtext("Continua come utente ospite");?><span class="uk-margin-small-left" uk-icon="icon: check; ratio: 0.9"></span>
		</a>
	</div>
	<?php } ?>
</div>
<span class='evidenzia'>class_username</span>
<div class='evidenzia'>class_email</div>
<div class='evidenzia'>class_conferma_email</div>
<div class="uk-margin-medium-bottom">
