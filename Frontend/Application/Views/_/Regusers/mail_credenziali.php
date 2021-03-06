<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p>
	<?php if (!isset($_GET["fromApp"])) { ?>
	<?php echo gtext("Gentile cliente, di seguito le credenziali per l'accesso alla sua area riservata nel nostro sito web",false);?>
	<?php } else { ?>
	<?php echo gtext("Gentile cliente, di seguito le credenziali dell'account creato dalla nostra APP",false);?>
	<?php } ?>
</p>

<p><?php echo gtext("Username", false);?>: <?php echo $clean["username"];?><br /><?php echo gtext("Password", false);?>: <?php echo $password;?></p>

<?php if (v("conferma_registrazione")) { ?>
<p><b><?php echo gtext("Le ricordiamo che il suo account non è ancora attivo.");?></b><br />
<?php echo gtext("Per attivarlo segua il seguente link, che sarà attivo per ".v("ore_durata_link_conferma")." ore.", false);?><br /><b><a href="<?php echo Url::getRoot()."conferma-account/$tokenConferma";?>"><?php echo gtext("Attiva l'account")?></a></b></p>
<?php } ?>

<p>
	<?php if (!isset($_GET["fromApp"])) { ?>
	<?php echo gtext("Potrà accedere alla propria area riservata visitando il seguente",false);?> <a href="<?php echo Url::getRoot()."area-riservata";?>"><?php echo gtext("indirizzo web",false);?></a>.
	<?php } else { ?>
	<?php echo gtext("Potrà utilizzare tali credenziali per eseguire gli acquisti desiderati dalla nostra APP", false);?>.
	<?php } ?>
</p>

<p><?php echo gtext("Cordiali saluti", false);?>.</p>
