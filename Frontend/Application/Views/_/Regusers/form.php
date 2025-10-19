<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (strcmp($this->action,"modify") !== 0)
{
	$breadcrumb = array(
		gtext("Home") 		=> $this->baseUrl,
		gtext("Crea un account")	=>	"",
	);
	
	$titoloPagina = gtext("Crea un account");
}
else
{
	$breadcrumb = array(
		gtext("Home") 		=> $this->baseUrl,
		gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
		gtext("Modifica account")	=>	"",
	);
	
	$titoloPagina = gtext("Modifica account");
}

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "account";

if (!isset($classeBoxPulsanteRegistrazione))
	$classeBoxPulsanteRegistrazione = "uk-margin";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>

<?php include(tpf("Elementi/Registrazione/Form/top.php"));?>

<form class="form_registrazione box_form_evidenzia" action="<?php echo $this->baseUrl.$action;?>#main" method="POST" autocomplete="new-password">
	<div class="uk-text-center">
		<?php echo $notice; ?>
	</div>

	<?php include(tpf("Regusers/form_dati_cliente.php"));?>
	
	<?php if (strcmp($this->action,"modify") !== 0) { ?>
		<br />
		<?php
		include(tpf(ElementitemaModel::p("CHECKOUT_NEWSLETTER","", array(
			"titolo"	=>	"Check iscrizione newsletter",
			"percorso"	=>	"Elementi/Ordini/IscrizioneNewsletter",
		))));
		?>
		<?php include (tpf("Elementi/Pagine/campo-captcha-registrazione.php"));?>
		
		<?php include(tpf("Regusers/form_accetto.php"));?>
	<?php } ?>
	
	<div class="<?php echo $classeBoxPulsanteRegistrazione;?>">
		<div class="<?php echo v("classe_pulsanti_submit");?> uk-width-1-1 uk-width-auto@m spinner uk-hidden" uk-spinner="ratio: .70"></div>
		<?php if (strcmp($this->action,"modify") !== 0 || (v("attiva_gestiobe_ticket") && RegusersModel::getRedirect() == "ticket")) { ?>
			<input class="<?php echo v("classe_pulsanti_submit");?> btn_submit_form uk-width-1-1 uk-width-auto@m" type="submit" name="updateAction" value="<?php echo gtext("Completa registrazione", false);?>" />
		<?php } else { ?>
			<?php if (v("permetti_modifica_account")) { ?>
				<input class="<?php echo v("classe_pulsanti_submit");?> btn_submit_form uk-width-1-1 uk-width-auto@m" type="submit" name="updateAction" value="<?php echo gtext("Modifica dati", false);?>" />
			<?php } ?>
		<?php } ?>
	</div>
</form>

<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
