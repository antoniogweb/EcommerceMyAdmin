<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Il cliente",false);?> <b>[NOMINATIVO_CLIENTE]</b> <?php echo gtext("ha risposto al ticket",false);?> <b>[ID_TICKET]</b> <?php echo gtext("avente oggetto");?> <b>[OGGETTO_TICKET]</b></p>
<p><?php echo gtext("Ecco la risposta del cliente:",false);?>:</p>

<div style="padding:10px;background-color:#EEE;">[MESSAGGIO_TICKET]</div>

<br />
<p><?php echo gtext("Per rispondere al ticket usa il form nella pagina di dettaglio del ticket, nella sezione E-commerce > Assistenza del pannello admin dell'ecommerce.", false);?>
