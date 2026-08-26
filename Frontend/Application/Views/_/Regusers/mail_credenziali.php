<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtextPlain("Gentile cliente, di seguito le credenziali per l'accesso alla sua area riservata nel nostro sito web",false);?></p>

<p>
	<?php echo gtextPlain("Username", false);?>: <?php echo isset($clean["username"]) ? $clean["username"] : $username;?>
	<br />
	<?php if (v("genera_e_invia_password")) { ?>
		<?php echo gtextPlain("Password", false);?>:  <?php echo $password;?>
	<?php } else { ?>
		<?php echo gtextPlain("Utilizzi la password specificata in fase di registrazione.")?>
	<?php } ?>
</p>

<?php if (isset($variabili["agente"]) && $variabili["agente"]) { ?>
<p>
<?php echo gtextPlain("La sua richiesta di diventare un agente per il nostro ecommerce verrà valutata quanto prima.");?><br />
<?php echo gtextPlain("Le verrà data notifica via mail.");?><br />
<?php echo gtextPlain("Nel caso la sua richiesta venga approvata, le saranno assegnati uno o più codici coupon da usare nel nostro negozio che potrà condividere con i suoi clienti.");?><br />
</p>
<?php } ?>

<?php if (v("conferma_registrazione") || v("gruppi_inseriti_da_approvare_alla_registrazione")) { ?>
	<p><b><?php echo gtextPlain("Le ricordiamo che il suo account non è ancora attivo.");?></b></p>
	
	<?php if (v("conferma_registrazione")) { ?>
		<p><?php echo gtextPlain("Per attivarlo inserisca il codice a 6 cifre indicato sotto");?></p>
		<h2 style="text-align:center;"><?php echo $codiceVerifica;?></h2>
		<p><?php echo gtextPlain("Dopo aver attivato l'account inserendo il codice di verifica indicato, potrà accedere alla propria area riservata visitando il seguente",false);?> <a href="<?php echo Url::getRoot()."area-riservata";?>"><?php echo gtextPlain("indirizzo web",false);?></a>.</p>
	<?php } else { ?>
		<p><?php echo gtextPlain("La sua richiesta di iscrizione è stata inoltrata con successo."); ?><br />
		<?php echo gtext("Quanto prima ci prenderemo cura della richiesta e le invieremo un feedback."); ?></p>
		<p><?php echo gtextPlain("Una volta che la sua iscrizione sarà approvata potrà accedere alla propria area riservata visitando il seguente",false);?> <a href="<?php echo Url::getRoot()."area-riservata";?>"><?php echo gtextPlain("indirizzo web",false);?></a>.</p>
	<?php } ?>
<?php } else { ?>
	<p><?php echo gtextPlain("Potrà accedere alla propria area riservata visitando il seguente",false);?> <a href="<?php echo Url::getRoot()."area-riservata";?>"><?php echo gtextPlain("indirizzo web",false);?></a>.
	</p>
<?php } ?>

<p><?php echo gtextPlain("Cordiali saluti", false);?>.</p>
