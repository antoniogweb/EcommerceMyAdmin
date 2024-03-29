<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p>
	<?php if (!isset($_SESSION["conferma_utente"])) { ?>
	<?php echo gtext("La registrazione al sito è avventuta correttamente, ma il suo account non è ancora attivo.");?>
	<br />
	<?php } ?>
	<span class="uk-text-emphasis"><?php echo gtext("Le è stata inviata una mail con un link per confermare il suo indirizzo e-mail."); ?></span><br />
	<?php echo gtext("Segua tale link per attivare l'account."); ?> <?php echo gtext("Il link avrà una validità di ".v("ore_durata_link_conferma")." ore"); ?><br />
</p>

<?php if (isset($_SESSION['token_reinvio'])) { ?>
<?php echo flash("notice_reinvio");?>

<p class="uk-margin-medium">
	<span class="uk-text-emphasis"><?php echo gtext("Non ti è arrivata la mail con il link di conferma?");?></span>
	
	<div>
		<div class="uk-button uk-button-primary spinner uk-hidden" uk-spinner="ratio: .70"></div>
		<a class="uk-button uk-button-primary btn_submit_form" href="<?php echo $this->baseUrl."/send-confirmation";?>"><span uk-icon="mail"></span> <?php echo gtext("Invia nuovamente il link di conferma")?></a>
	</div>
</p>
<?php } ?>

<?php include(tpf("/Elementi/Registrazione/vai_alla_home.php")); ?>
