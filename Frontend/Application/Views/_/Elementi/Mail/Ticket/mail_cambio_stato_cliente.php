<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Gentile cliente, il suo ticket avente oggetto",false);?> <b>[OGGETTO_TICKET]</b> <?php echo gtext("è stato impostato allo stato",false);?> <b>[STATO_TICKET]</b></p>

<p><?php echo gtextPlain("Può vedere tutti i dettagli del ticket a", false);?> <a href="[URL_TICKET]"><?php echo gtextPlain("questo link");?></a>

<p><?php echo gtextPlain("Cordiali saluti", false);?>.</p> 
