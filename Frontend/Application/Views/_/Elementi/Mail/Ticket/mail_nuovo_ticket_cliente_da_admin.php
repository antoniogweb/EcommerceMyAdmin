<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtextPlain("Gentile cliente,",false);?><br />
<?php echo gtext("le è stato creato un nuovo ticket di assistenza avente oggetto: ")?> <b>[OGGETTO_TICKET]</b>
</p>

<?php echo gtextPlain("Descrizione del problema da lei indicato"); ?>:<br />
<div style="padding:10px;background-color:#EEE;">
	[TESTO_TICKET]
</div>

<br /><p><?php echo gtextPlain("Per vedere i dettagli del ticket ed eventualmente aggiungere un messaggio, segua", false);?> <a href="[URL_TICKET]"><?php echo gtextPlain("questo link");?></a>.</p>

<p><?php echo gtextPlain("Potrà avere accesso a tutti i suoi ticket dalla sua area riservata visitando il seguente",false);?> <a href="[LINK_SITO]/area-riservata"><?php echo gtextPlain("indirizzo web",false);?></a>, <?php echo gtextPlain("all'interno della sezione")?> <b><?php echo gtextPlain("assistenza")?></b>.
</p>

<p><?php echo gtextPlain("Di seguito le credenziali per l'accesso alla sua area riservata nel nostro sito web",false);?>:</p>

<p>
	<?php echo gtextPlain("Username", false);?>: <b>[EMAIL_CLIENTE]</b>
	<br />
	<?php echo gtextPlain("Password", false);?>: <?php echo gtextPlain("Utilizzi la sua password o richieda una nuova password come indicato sotto.")?>
</p>

<p><?php echo gtextPlain("Se non ricorda o non conosce la password di accesso all'area riservata, può richiedere una nuova password al seguente ", false);?> <a href="[LINK_SITO]/password-dimenticata"><?php echo gtextPlain("indirizzo web",false);?></a></p>

<p><?php echo gtextPlain("Cordiali saluti", false);?>.</p> 
