<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Un cliente ha inserito un ticket di assistenza.",false);?><br />
<?php echo gtext("Ecco i dettagli del ticket.",false);?><br />
</p>
<div>
	<b><?php echo gtext("ID Ticket"); ?>:</b> [ID_TICKET]<br />
	<b><?php echo gtext("Cliente"); ?>:</b> [NOMINATIVO_CLIENTE]<br />
	<b><?php echo gtext("Email cliente"); ?>:</b> [EMAIL_CLIENTE]<br />
	<b><?php echo gtext("Oggetto"); ?>:</b> [OGGETTO_TICKET]<br />
	<b><?php echo gtext("Descrizione del problema"); ?></b><br />
	<div style="padding:10px;background-color:#EEE;">[TESTO_TICKET]</div>
</div>
<br />
<p>
<?php echo gtext("Tutte le informazioni sono presenti all'interno della pagina di dettaglio del ticket, nella sezione E-commerce > Assistenza del pannello admin dell'ecommerce.",false);?>
</p>
