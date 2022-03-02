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
		$titoloNotice = isset($_SESSION["conferma_utente"]) ? "Conferma account" : "Account creato";
		
		$breadcrumb = array(
			gtext("Home") 		=> $this->baseUrl,
			gtext($titoloNotice)	=>	"",
		);
		
		$titoloPagina = gtext($titoloNotice);
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
		<?php include(tpf("/Elementi/Registrazione/vai_alla_home.php")); ?>
	<?php } else if (strcmp($_SESSION['result'],'error') === 0) { ?>
		<p><?php echo gtext("Si è verificato un errore durante il processo, riprovi più tardi o contatti l'amministratore del sito");?>.</p>
		<?php include(tpf("/Elementi/Registrazione/vai_alla_home.php")); ?>
	<?php } else if (strcmp($_SESSION['result'],'invalid_token') === 0) { ?>
		<p><?php echo gtext("Il link è scaduto");?>.</p>
		<?php include(tpf("/Elementi/Registrazione/vai_alla_home.php")); ?>
	<?php } else if (strcmp($_SESSION['result'],'password_cambiata') === 0) { ?>
		<p><?php echo gtext("La password è stata correttamente impostata");?>.</p>
		<p><?php echo gtext("Vai al");?> <a href="<?php echo $this->baseUrl."/regusers/login";?>">login</a></p>
	<?php } else if (strcmp($_SESSION['result'],'account_confermato') === 0) { ?>
		<?php include(tpf("/Elementi/Registrazione/Resoconto/account_confermato.php")); ?>
	<?php } else if (strcmp($_SESSION['result'],'utente_creato') === 0) { ?>
		<?php if (!v("conferma_registrazione") && !v("gruppi_inseriti_da_approvare_alla_registrazione")) { ?>
			<?php include(tpf("/Elementi/Registrazione/Resoconto/account_creato.php")); ?>
		<?php } else { ?>
			<?php if (v("conferma_registrazione")) { ?>
				<?php include(tpf("/Elementi/Registrazione/Resoconto/account_creato_da_confermare.php")); ?>
			<?php } else if (v("gruppi_inseriti_da_approvare_alla_registrazione")) { ?>
				<?php include(tpf("/Elementi/Registrazione/Resoconto/account_creato_da_approvare.php")); ?>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<?php } else { ?>
	<?php include(tpf("/Elementi/Registrazione/vai_alla_home.php")); ?>
<?php } ?>

<?php
if ( isset($_SESSION['result']) ) unset($_SESSION['result']);
?>

<?php
include(tpf("/Elementi/Pagine/page_bottom.php"));
