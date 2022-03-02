<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Gentile cliente, la ringraziamo per la sua iscrizione al nostro sito web.",false);?></p>

<p><b><?php echo gtext("Le ricordiamo che il suo account non è ancora attivo.");?></b></p>

<p><?php echo gtext("Per attivarlo segua il seguente link, che sarà attivo per ".v("ore_durata_link_conferma")." ore.", false);?><br /><b><a href="[LINK_CONFERMA]"><?php echo gtext("Attiva l'account")?></a></b></p>

<p><?php echo gtext("Cordiali saluti", false);?>.</p>
