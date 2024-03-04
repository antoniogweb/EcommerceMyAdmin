<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Gentile cliente, il negozio ha risposto al suo ticket avente oggetto:",false);?> <b>[OGGETTO_TICKET]</b></p>
<p><?php echo gtext("Ecco la risposta del negozio:",false);?>:</p>

<div style="padding:10px;background-color:#EEE;">[MESSAGGIO_TICKET]</div>

<br />
<p><?php echo gtext("Per rispondere al ticket, segua", false);?> <a href="[URL_TICKET]"><?php echo gtext("questo link");?></a>

<p><?php echo gtext("Cordiali saluti", false);?>.</p> 
