<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array();

if (isset($_SESSION['result']))
{
	if (strcmp($_SESSION['result'],'send_mail_to_change_password') === 0)
	{
		$breadcrumb = array(
			gtext("Home") 		=> $this->baseUrl,
			gtext("Accedi")	=>	$this->baseUrl."/regusers/login",
			gtext("Richiesta nuova password")	=>	$this->baseUrl."/password-dimenticata",
			gtext("Invio mail per cambio password")	=>	"",
		);
		
		$titoloPagina = gtext("Impostazione nuova password");
	}
	else if (strcmp($_SESSION['result'],'password_cambiata') === 0)
	{
		$breadcrumb = array(
			gtext("Home") 		=> $this->baseUrl,
			gtext("Accedi")	=>	$this->baseUrl."/regusers/login",
			gtext("Richiesta nuova password")	=>	$this->baseUrl."/password-dimenticata",
			gtext("Password cambiata")	=>	"",
		);
		
		$titoloPagina = gtext("Password cambiata");
	}
	else if (strcmp($_SESSION['result'],'utente_creato') === 0)
	{
		$breadcrumb = array(
			gtext("Home") 		=> $this->baseUrl,
			gtext("Account creato")	=>	"",
		);
		
		$titoloPagina = gtext("Account creato");
	}
	else if (strcmp($_SESSION['result'],'account_confermato') === 0)
	{
		$breadcrumb = array(
			gtext("Home") 		=> $this->baseUrl,
			gtext("Account confermato")	=>	"",
		);
		
		$titoloPagina = gtext("Account attivato");
	}
	else if (strcmp($_SESSION['result'],'invalid_token') === 0)
	{
		$breadcrumb = array(
			gtext("Home") 		=> $this->baseUrl,
			gtext("Link scaduto")	=>	"",
		);
		
		$titoloPagina = gtext("Link scaduto");
	}
	else if (strcmp($_SESSION['result'],'error') === 0)
	{
		$breadcrumb = array(
			gtext("Home") 		=> $this->baseUrl,
			gtext("Errore")	=>	"",
		);
		
		$titoloPagina = gtext("Errore");
	}
}
else
{
	$breadcrumb = array(
		gtext("Home") 		=> $this->baseUrl,
		gtext("Notifiche")	=>	"",
	);
	
	$titoloPagina = gtext("Notifiche");
}

include(tpf("/Elementi/Pagine/page_top.php"));
?>
<?php if (isset($_SESSION['result'])) { ?>
	<?php if (strcmp($_SESSION['result'],'send_mail_to_change_password') === 0) { ?>
		<p><?php echo gtext("Le è stata inviata una mail con un link. Segua tale link se vuole impostare una nuova password");?>.</p>
		<?php if (!isset($_GET["eFromApp"])) { ?>
		<p><?php echo gtext("Torna alla");?> <a href="<?php echo $this->baseUrl;?>">home</a></p>
		<?php } ?>
	<?php } else if (strcmp($_SESSION['result'],'error') === 0) { ?>
		<p><?php echo gtext("Si è verificato un errore durante il processo, riprovi più tardi o contatti l'amministratore del sito");?>.</p>
		<?php if (!isset($_GET["eFromApp"])) { ?>
		<p><?php echo gtext("Torna alla");?> <a href="<?php echo $this->baseUrl;?>">home</a></p>
		<?php } ?>
	<?php } else if (strcmp($_SESSION['result'],'invalid_token') === 0) { ?>
		<p><br /><?php echo gtext("Il link è scaduto");?>.</p>
		<?php if (!isset($_GET["eFromApp"])) { ?>
		<p><?php echo gtext("Torna alla");?> <a href="<?php echo $this->baseUrl;?>">home</a></p>
		<?php } ?>
	<?php } else if (strcmp($_SESSION['result'],'password_cambiata') === 0) { ?>
		<?php if (!isset($_GET["eFromApp"])) { ?>
		<p><?php echo gtext("La password è stata correttamente impostata");?>.</p>
		<p><?php echo gtext("Vai al");?> <a href="<?php echo $this->baseUrl."/regusers/login";?>">login</a></p>
		<?php } else { ?>
		<br />
		<p><?php echo gtext("La password è stata correttamente impostata");?>.</p>
		<p><?php echo gtext("Può continuare gli acquisti tramite la APP utilizzando la password che ha appena impostato.");?></p>
		<p><?php echo gtext("Cordiali saluti<br />");?></p>
		<?php } ?>
	<?php } else if (strcmp($_SESSION['result'],'account_confermato') === 0) { ?>
		<?php if (!isset($_GET["eFromApp"])) { ?>
		<p><?php echo gtext("Il suo account è stato confermato");?>.</p>
		<p><?php echo gtext("Vai al");?> <a href="<?php echo $this->baseUrl."/regusers/login";?>">login</a></p>
		<?php } else { ?>
		<br />
		<p><?php echo gtext("Il suo account è stato confermato");?>.</p>
		<p><?php echo gtext("Può continuare gli acquisti tramite la APP utilizzando l'account che ha appena confermato.");?></p>
		<p><?php echo gtext("Cordiali saluti<br />");?></p>
		<?php } ?>
	<?php } else if (strcmp($_SESSION['result'],'utente_creato') === 0) { ?>
		<?php if (!v("conferma_registrazione")) { ?>
			<p><?php echo gtext("L'account è stato creato correttamente. Le è stata inviata una mail con le credenziali d'accesso che ha scelto");?>.</p>
			
			<?php if (!isset($_GET["eFromApp"])) { ?>
			<p><?php echo gtext("Vai all'");?> <a href="<?php echo $this->baseUrl."/area-riservata";?>"><?php echo strtolower(gtext("Area riservata"));?></a></p>
			<?php } ?>
		<?php } else { ?>
			<p>
				<?php echo gtext("La registrazione è avventuta correttamente, ma il suo account non è ancora attivo.");?>
				<br />
				<span class="uk-text-bold"><?php echo gtext("Le è stata inviata una mail con un link per confermare la registrazione."); ?></span><br />
				<?php echo gtext("Segua tale link per attivare l'account"); ?>
				<br />
				<?php echo gtext("Il link avrà una validità di ".v("ore_durata_link_conferma")." ore"); ?><br />
			</p>
			
			<?php if (!isset($_GET["eFromApp"])) { ?>
			<p><?php echo gtext("Torna alla");?> <a href="<?php echo $this->baseUrl;?>"><?php echo gtext("home");?></a></p>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php } else { ?>
	<?php if (!isset($_GET["eFromApp"])) { ?>
	<p><?php echo gtext("Torna alla");?> <a href="<?php echo $this->baseUrl;?>"><?php echo gtext("home");?></a></p>
	<?php } ?>
<?php } ?>

<?php if ( isset($_SESSION['result']) ) unset($_SESSION['result']); ?>

<?php
include(tpf("/Elementi/Pagine/page_bottom.php"));
