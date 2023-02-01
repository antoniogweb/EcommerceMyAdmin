<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (strcmp($this->action,"add") === 0)
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

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>

<div class="uk-text-center">
	<?php echo $notice; ?>
</div>

<form class="form_registrazione" action="<?php echo $this->baseUrl.$action;?>#main" method="POST" autocomplete="new-password">
	<?php include(tpf("Regusers/form_dati_cliente.php"));?>
	
	<?php if (strcmp($this->action,"add") === 0) { ?>
	
	<br />
	<?php
	include(tpf(ElementitemaModel::p("CHECKOUT_NEWSLETTER","", array(
		"titolo"	=>	"Check iscrizione newsletter",
		"percorso"	=>	"Elementi/Ordini/IscrizioneNewsletter",
	))));
	?>
			
	<div class="condizioni_privacy uk-margin uk-text-muted uk-text-small">
		<?php echo gtext("Ho letto e accettato le");?>
		<?php $idPrivacy = PagineModel::gTipoPagina("PRIVACY"); ?>
		<?php if ($idPrivacy) { ?>
		<a class="uk-text-secondary" href="<?php echo $this->baseUrl."/".getUrlAlias($idPrivacy);?>"><?php echo gtext("condizioni di privacy");?></a>
		<?php } ?>
	</div>

	<div class="class_accetto">
		<?php echo Html_Form::radio("accetto",$values['accetto'],array("<span style='margin-left:8px;'></span><span class='radio_2_testo'>".gtext("NON ACCETTO")."</span><span style='margin-right:20px;'></span>" => "non_accetto", "<span style='margin-left:8px;'></span><span class='radio_2_testo'>".gtext("ACCETTO")."</span>" => "accetto"),"radio_2");?>
	</div>
	<?php } ?>
	
	<div class="uk-margin">
		<div class="<?php echo v("classe_pulsanti_submit");?> uk-width-1-1 uk-width-auto@m spinner uk-hidden" uk-spinner="ratio: .70"></div>
		<?php if (strcmp($this->action,"add") === 0) { ?>
		<input class="<?php echo v("classe_pulsanti_submit");?> btn_submit_form uk-width-1-1 uk-width-auto@m" type="submit" name="updateAction" value="<?php echo gtext("Completa registrazione", false);?>" />
		<?php } else { ?>
		<input class="<?php echo v("classe_pulsanti_submit");?> btn_submit_form uk-width-1-1 uk-width-auto@m" type="submit" name="updateAction" value="<?php echo gtext("Modifica dati", false);?>" />
		<?php } ?>
	</div>
</form>

<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
