<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtextPlain("Gentile cliente, ha richiesto di poter impostare una nuova password per il suo account");?>.</p>

<p><?php echo gtextPlain("Le sarà possibile impostare una nuova password al seguente");?> <a href="<?php echo Url::getRoot()."reimposta-password/$forgot_token"; ?>"><?php echo gtextPlain("indirizzo web");?></a>.</p>

<p><?php echo gtextPlain("Se ha ricevuto questa e-mail per errore le chiediamo gentilmente di cancellarla");?>.</p>
