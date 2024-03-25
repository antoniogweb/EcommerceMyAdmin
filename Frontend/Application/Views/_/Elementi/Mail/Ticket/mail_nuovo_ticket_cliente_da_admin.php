<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Gentile cliente,",false);?><br />
<?php echo gtext("le è stato creato un nuovo ticket di assistenza avente oggetto: ")?> <b>[OGGETTO_TICKET]</b>
</p>

<?php echo gtext("Descrizione del problema da lei indicato"); ?>:<br />
<div style="padding:10px;background-color:#EEE;">
	[TESTO_TICKET]
</div>

<br /><p><?php echo gtext("Per vedere i dettagli del ticket ed eventualmente aggiungere un messaggio, segua", false);?> <a href="[URL_TICKET]"><?php echo gtext("questo link");?></a></p>

<p><?php echo gtext("Potrà avere accesso a tutti i suoi ticket dalla sua area riservata visitando il seguente",false);?> <a href="[LINK_SITO]/area-riservata"><?php echo gtext("indirizzo web",false);?></a>, <?php echo gtext("all'interno della sezione")?> <b><?php echo gtext("assistenza")?></b>.
</p>

<p><?php echo gtext("Di seguito le credenziali per l'accesso alla sua area riservata nel nostro sito web",false);?>:</p>

<p>
	<?php echo gtext("Username", false);?>: <b>[EMAIL_CLIENTE]</b>
	<br />
	<?php echo gtext("Password", false);?>: <?php echo gtext("Utilizzi la sua password o richieda una nuova password come indicato sotto.")?>
</p>

<p><?php echo gtext("Se non ricorda o non conosce la password di accesso all'area riservata, può richiedere una nuova password al seguente ", false);?> <a href="[LINK_SITO]/password-dimenticata"><?php echo gtext("indirizzo web",false);?></a></p>

<p><?php echo gtext("Cordiali saluti", false);?>.</p> 
