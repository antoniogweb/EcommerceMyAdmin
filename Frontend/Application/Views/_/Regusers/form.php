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

<form class="form_registrazione" action="<?php echo $this->baseUrl.$action;?>#main" method="POST">
	<?php include(tpf("Regusers/form_dati_cliente.php"));?>
	
	<?php if (strcmp($this->action,"add") === 0) { ?>
	
	<?php if (!$islogged && ImpostazioniModel::$valori["mailchimp_api_key"] && ImpostazioniModel::$valori["mailchimp_list_id"]) { ?>
	<div class="newsletter_checkbox"><?php echo Html_Form::checkbox("newsletter",$values['newsletter'],"Y");?> <?php echo gtext("Voglio essere iscritto alla newsletter per conoscere le promozioni e le novità del negozio");?></div> 
	<?php } ?>
			
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
		<?php if (strcmp($this->action,"add") === 0) { ?>
		<input class="uk-button uk-button-secondary" type="submit" name="updateAction" value="<?php echo gtext("Completa registrazione", false);?>" />
		<?php } else { ?>
		<input class="uk-button uk-button-secondary" type="submit" name="updateAction" value="<?php echo gtext("Modifica dati", false);?>" />
		<?php } ?>
	</div>
</form>

<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
