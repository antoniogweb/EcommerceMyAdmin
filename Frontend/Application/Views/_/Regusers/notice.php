<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array();

if (isset($_SESSION['result']))
{
	if (strcmp($_SESSION['result'],'send_mail_to_change_password') === 0)
	{
		$breadcrumb = array(
			gtextPlain("Home") 		=> $this->baseUrl,
			gtextPlain("Accedi")	=>	$this->baseUrl."/regusers/login",
			gtextPlain("Richiesta nuova password")	=>	$this->baseUrl."/password-dimenticata",
			gtextPlain("Invio mail per cambio password")	=>	"",
		);
		
		$titoloPagina = gtextPlain("Impostazione nuova password");
	}
	else if (strcmp($_SESSION['result'],'password_cambiata') === 0)
	{
		$breadcrumb = array(
			gtextPlain("Home") 		=> $this->baseUrl,
			gtextPlain("Accedi")	=>	$this->baseUrl."/regusers/login",
			gtextPlain("Richiesta nuova password")	=>	$this->baseUrl."/password-dimenticata",
			gtextPlain("Password cambiata")	=>	"",
		);
		
		$titoloPagina = gtextPlain("Password cambiata");
	}
	else if (strcmp($_SESSION['result'],'utente_creato') === 0 || strcmp($_SESSION['result'],'agente_creato') === 0)
	{
		$titoloNotice = isset($_SESSION["conferma_utente"]) ? "Conferma account" : "Account creato";
		
		$breadcrumb = array(
			gtextPlain("Home") 		=> $this->baseUrl,
			gtextPlain($titoloNotice)	=>	"",
		);
		
		$titoloPagina = gtextPlain($titoloNotice);
	}
	else if (strcmp($_SESSION['result'],'account_confermato') === 0)
	{
		$breadcrumb = array(
			gtextPlain("Home") 		=> $this->baseUrl,
			gtextPlain("Account verificato")	=>	"",
		);
		
		$titoloPagina = gtextPlain("Account verificato");
	}
	else if (strcmp($_SESSION['result'],'invalid_token') === 0)
	{
		$breadcrumb = array(
			gtextPlain("Home") 		=> $this->baseUrl,
			gtextPlain("Link scaduto")	=>	"",
		);
		
		$titoloPagina = gtextPlain("Link scaduto");
	}
	else if (strcmp($_SESSION['result'],'error') === 0)
	{
		$breadcrumb = array(
			gtextPlain("Home") 		=> $this->baseUrl,
			gtextPlain("Errore")	=>	"",
		);
		
		$titoloPagina = gtextPlain("Errore");
	}
	else if (strcmp($_SESSION['result'],'account_rinnovato') === 0)
	{
		$breadcrumb = array(
			gtextPlain("Home") 		=> $this->baseUrl,
			gtextPlain("Account rinnovato")	=>	"",
		);
		
		$titoloPagina = gtextPlain("Account rinnovato");
	}
	else if (strcmp($_SESSION['result'],'pausa_LOGIN') === 0 || strcmp($_SESSION['result'],'pausa_RECUPERO_PASSWORD') === 0)
	{
		$breadcrumb = array(
			gtextPlain("Home") 		=> $this->baseUrl,
			gtextPlain("Accesso piattaforma in pausa")	=>	"",
		);
		
		$titoloPagina = gtextPlain("Accesso piattaforma in pausa");
	}
}
else
{
	$breadcrumb = array(
		gtextPlain("Home") 		=> $this->baseUrl,
		gtextPlain("Notifiche")	=>	"",
	);
	
	$titoloPagina = gtextPlain("Notifiche");
}

include(tpf("/Elementi/Pagine/page_top.php"));
?>
<div class="notice_box">
	<?php if (isset($_SESSION['result'])) { ?>
		<?php if (strcmp($_SESSION['result'],'send_mail_to_change_password') === 0) { ?>
			<p><?php echo gtextPlain("Le è stata inviata una mail con un link. Segua tale link se vuole impostare una nuova password");?>.</p>
			<?php include(tpf("/Elementi/Registrazione/vai_alla_home.php")); ?>
		<?php } else if (strcmp($_SESSION['result'],'error') === 0) { ?>
			<p><?php echo gtextPlain("Si è verificato un errore durante il processo, riprovi più tardi o contatti l'amministratore del sito");?>.</p>
			<?php include(tpf("/Elementi/Registrazione/vai_alla_home.php")); ?>
		<?php } else if (strcmp($_SESSION['result'],'invalid_token') === 0) { ?>
			<p><?php echo gtextPlain("Il link è scaduto");?>.</p>
			<?php include(tpf("/Elementi/Registrazione/vai_alla_home.php")); ?>
		<?php } else if (strcmp($_SESSION['result'],'password_cambiata') === 0) { ?>
			<?php include(tpf("/Elementi/Registrazione/Resoconto/password_cambiata.php")); ?>
		<?php } else if (strcmp($_SESSION['result'],'account_confermato') === 0) { ?>
			<?php include(tpf("/Elementi/Registrazione/Resoconto/account_confermato.php")); ?>
		<?php } else if (strcmp($_SESSION['result'],'account_rinnovato') === 0) { ?>
			<?php include(tpf("/Elementi/Registrazione/Resoconto/account_rinnovato.php")); ?>
		<?php } else if (strcmp($_SESSION['result'],'pausa_LOGIN') === 0) { ?>
			<?php include(tpf("/Elementi/Registrazione/Resoconto/account_pausa_LOGIN.php")); ?>
			<?php include(tpf("/Elementi/Registrazione/vai_alla_home.php")); ?>
		<?php } else if (strcmp($_SESSION['result'],'pausa_RECUPERO_PASSWORD') === 0) { ?>
			<?php include(tpf("/Elementi/Registrazione/Resoconto/account_pausa_RECUPERO_PASSWORD.php")); ?>
			<?php include(tpf("/Elementi/Registrazione/vai_alla_home.php")); ?>
		<?php } else if (strcmp($_SESSION['result'],'utente_creato') === 0 || strcmp($_SESSION['result'],'agente_creato') === 0) { ?>
			<?php if (!v("conferma_registrazione") && !v("gruppi_inseriti_da_approvare_alla_registrazione")) { ?>
				<?php if ($_SESSION['result'] == "agente_creato") { ?>
					<?php include(tpf("/Elementi/Registrazione/Resoconto/account_agente_creato.php")); ?>
				<?php } else { ?>
					<?php include(tpf("/Elementi/Registrazione/Resoconto/account_creato.php")); ?>
				<?php } ?>
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
</div>

<?php
if ( isset($_SESSION['result']) ) unset($_SESSION['result']);
?>

<?php
include(tpf("/Elementi/Pagine/page_bottom.php"));
