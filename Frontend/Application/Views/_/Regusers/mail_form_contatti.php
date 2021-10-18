<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

Un cliente ha richiesto informazioni.<br />
<?php if (isset($pagina) && !empty($pagina)) { ?>
Pagina: <a href="<?php echo $this->getCurrentUrl();?>"><?php echo $pagina["title"];?></a><br /><br />
<?php } ?>
Ecco i suoi dati:<br /><br />

Nome: <?php echo $valoriEmail["nome"];?><br />
Email: <?php echo $valoriEmail["email"];?><br />
Messaggio: <?php echo $valoriEmail["messaggio"];?>
