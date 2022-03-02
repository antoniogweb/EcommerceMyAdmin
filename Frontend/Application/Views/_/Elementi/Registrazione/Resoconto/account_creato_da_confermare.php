<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p>
	<?php echo gtext("La registrazione è avventuta correttamente, ma il suo account non è ancora attivo.");?>
	<br />
	<span class="uk-text-emphasis"><?php echo gtext("Le è stata inviata una mail con un link per confermare la registrazione."); ?></span><br />
	<?php echo gtext("Segua tale link per attivare l'account."); ?> <?php echo gtext("Il link avrà una validità di ".v("ore_durata_link_conferma")." ore"); ?><br />
</p>

<?php if (isset($_SESSION['token_reinvio'])) { ?>
<?php echo flash("notice_reinvio");?>

<p class="uk-margin-large">
	<span class="uk-text-emphasis"><?php echo gtext("Non ti è arrivata la mail con il link di conferma?");?></span>
	<br />
	<a class="uk-button uk-button-primary" href="<?php echo $this->baseUrl."/regusers/reinviamailconferma";?>"><span uk-icon="mail"></span> <?php echo gtext("Manda nuovamente il link di conferma")?></a>
</p>
<?php } ?>

<?php include(tpf("/Elementi/Registrazione/vai_alla_home.php")); ?>
