<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Gentile cliente, ha richiesto di poter impostare una nuova password per il suo account");?>.</p>

<p><?php echo gtext("Le sarà possibile impostare una nuova password al seguente");?> <a href="<?php echo Url::getRoot()."reimposta-password/$forgot_token"; ?><?php if (Output::$json) { echo "?eFromApp&ecommerce=Y";}?>"><?php echo gtext("indirizzo web");?></a>.</p>

<p><?php echo gtext("Se ha ricevuto questa e-mail per errore le chiediamo gentilmente di cancellarla");?>.</p>
