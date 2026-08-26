<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtextPlain("Un cliente ha inserito un ticket di assistenza.",false);?><br />
<?php echo gtextPlain("Ecco i dettagli del ticket.",false);?><br />
</p>
<div>
	<b><?php echo gtextPlain("ID Ticket"); ?>:</b> [ID_TICKET]<br />
	<b><?php echo gtextPlain("Cliente"); ?>:</b> [NOMINATIVO_CLIENTE]<br />
	<b><?php echo gtextPlain("Email cliente"); ?>:</b> [EMAIL_CLIENTE]<br />
	<b><?php echo gtext("Oggetto"); ?>:</b> [OGGETTO_TICKET]<br />
	<b><?php echo gtextPlain("Descrizione del problema"); ?></b><br />
	<div style="padding:10px;background-color:#EEE;">[TESTO_TICKET]</div>
</div>
<br />
<p>
<?php echo gtextPlain("Tutte le informazioni sono presenti all'interno della pagina di dettaglio del ticket, nella sezione");?> <b><?php echo gtextPlain("E-commerce");?></b> > <b><?php echo gtextPlain("Assistenza");?></b> <?php echo gtextPlain("del pannello admin dell'ecommerce.",false);?>
</p>
