<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

Un cliente si è registrato alla newsletter.<br />
<?php if (isset($pagina) && !empty($pagina)) { ?>
Pagina di registrazine: <a href="<?php echo $this->getCurrentUrl();?>"><?php echo $pagina["title"];?></a><br /><br />
<?php } ?>
Ecco i suoi dati:<br /><br />

Email: <?php echo $this->m['ContattiModel']->values["email"];?><br />
