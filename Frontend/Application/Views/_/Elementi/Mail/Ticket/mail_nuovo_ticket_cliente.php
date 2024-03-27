<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p>
	<?php echo gtext("Gentile cliente,.",false);?><br />
	<?php echo gtext("ecco il ticket di assistenza da lei inserito.");?>
</p>

<div>
	<b><?php echo gtext("Oggetto"); ?>:</b> [OGGETTO_TICKET]<br />
	<b><?php echo gtext("Descrizione del problema"); ?>:</b><br />
	<div style="padding:10px;background-color:#EEE;">[TESTO_TICKET]</div>
</div>
<br />
<p><?php echo gtext("Le verrà notificato via mail quando un amministratore del negozio risponderà al suo ticket.", false);?></p>

<p><?php echo gtext("Può vedere l'avanzamento del suo ticket di assistenza in ogni momento tramite il seguente", false);?> <a href="[URL_TICKET]"><?php echo gtext("indirizzo web");?></a>.</p>

<p><?php echo gtext("Potrà inoltre accedere al dettaglio del suo ticket di assistenza dall'area riservata del nostro sito web.", false);?></p>

<p><?php echo gtext("Cordiali saluti", false);?>.</p> 
