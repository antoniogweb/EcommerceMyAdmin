<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Gentile cliente, il negozio ha risposto al suo ticket avente oggetto:",false);?> <b>[OGGETTO_TICKET]</b></p>
<p><?php echo gtextPlain("Ecco la risposta del negozio:",false);?>:</p>

<div style="padding:10px;background-color:#EEE;">[MESSAGGIO_TICKET]</div>

<br />
<p><?php echo gtextPlain("Per rispondere al ticket, segua", false);?> <a href="[URL_TICKET]"><?php echo gtextPlain("questo link");?></a></p>

<p><?php echo gtextPlain("Cordiali saluti", false);?>.</p> 
