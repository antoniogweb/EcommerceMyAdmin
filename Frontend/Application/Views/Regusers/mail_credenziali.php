<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p>
<?php if (!isset($_GET["fromApp"])) { ?>
<?php echo gtext("Gentile cliente, di seguito le credenziali per l'accesso alla sua area riservata nel nostro sito web",false);?>
<?php } else { ?>
<?php echo gtext("Gentile cliente, di seguito le credenziali dell'account creato dalla nostra APP",false);?>
<?php } ?>
</p>

<p><?php echo gtext("Username", false);?>: <?php echo $clean["username"];?><br /><?php echo gtext("Password", false);?>: <?php echo $password;?></p>

<p>
<?php if (!isset($_GET["fromApp"])) { ?>
<?php echo gtext("Potrà accedere alla propria area riservata visitando il seguente",false);?> <a href="<?php echo Url::getRoot()."area-riservata";?>"><?php echo gtext("indirizzo web",false);?></a>.
<?php } else { ?>
<?php echo gtext("Potrà utilizzare tali credenziali per eseguire gli acquisti desiderati dalla nostra APP", false);?>.
<?php } ?>
</p>
<p><?php echo gtext("Cordiali saluti", false);?>.</p>
