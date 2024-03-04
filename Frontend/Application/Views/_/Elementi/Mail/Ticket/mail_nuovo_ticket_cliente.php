<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Gentile cliente, ecco il ticket di assistenza da lei inserito.",false);?></p>

<div>
	<b><?php echo gtext("Oggetto"); ?>:</b> [OGGETTO_TICKET]<br />
	<b><?php echo gtext("Descrizione del problema"); ?>:</b><br />[TESTO_TICKET]<br />
</div>
<br />
<p><?php echo gtext("Le verrà notificato via mail quando un amministratore del negozio risponderà al suo ticket.", false);?>.<br /><?php echo gtext("Potrà inoltre accedere al dettaglio del suo ticket di assistenza dall'area riservata del nostro sito web.", false);?></p>

<p><?php echo gtext("Cordiali saluti", false);?>.</p> 
