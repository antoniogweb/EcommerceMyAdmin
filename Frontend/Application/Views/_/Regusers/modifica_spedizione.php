<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if ($id === 0)
{
	$breadcrumb = array(
		gtext("Home") 		=> $this->baseUrl,
		gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
		gtext("Indirizzi di spedizione") => $this->baseUrl."/riservata/indirizzi",
		gtext("Aggiungi un indirizzo di spedizione") => "",
	);
	
	$titoloPagina = gtext("Aggiungi un indirizzo di spedizione");
}
else
{
	$breadcrumb = array(
		gtext("Home") 		=> $this->baseUrl,
		gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
		gtext("Indirizzi di spedizione") => $this->baseUrl."/riservata/indirizzi",
		gtext("Modifica l'indirizzo di spedizione") => "",
	);
	
	$titoloPagina = gtext("Modifica l'indirizzo di spedizione");
}

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "indirizzi";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>

<div class="uk-text-center">
	<?php echo $notice; ?>
</div>

<form action="<?php echo $this->baseUrl.$action;?>" method="POST">
	
	<?php include(tpf("Regusers/form_dati_spedizione.php"));?>

	<div class="uk-margin">
		<div class="uk-button uk-button-secondary spinner uk-hidden" uk-spinner="ratio: .70"></div>
		<?php if ($id === 0) { ?>
		<input class="uk-button uk-button-secondary btn_submit_form" type="submit" name="insertAction" value="<?php echo gtext("Salva", false);?>" />
		<?php } else { ?>
		<input class="uk-button uk-button-secondary btn_submit_form" type="submit" name="updateAction" value="<?php echo gtext("Salva", false);?>" />
		<?php } ?>
	</div>
	
</form>

<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
