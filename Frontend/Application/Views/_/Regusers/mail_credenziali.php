<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Gentile cliente, di seguito le credenziali per l'accesso alla sua area riservata nel nostro sito web",false);?></p>

<p><?php echo gtext("Username", false);?>: <?php echo isset($clean["username"]) ? $clean["username"] : $username;?><br /><?php echo gtext("Password", false);?>: <?php echo $password;?></p>

<?php if (v("conferma_registrazione")) { ?>
<p><b><?php echo gtext("Le ricordiamo che il suo account non è ancora attivo.");?></b><br />
<?php echo gtext("Per attivarlo segua il seguente link, che sarà attivo per ".v("ore_durata_link_conferma")." ore.", false);?><br /><b><a href="<?php echo Url::getRoot()."conferma-account/$tokenConferma";?>"><?php echo gtext("Attiva l'account")?></a></b></p>
<?php } ?>

<p><?php echo gtext("Potrà accedere alla propria area riservata visitando il seguente",false);?> <a href="<?php echo Url::getRoot()."area-riservata";?>"><?php echo gtext("indirizzo web",false);?></a>.
</p>

<p><?php echo gtext("Cordiali saluti", false);?>.</p>
