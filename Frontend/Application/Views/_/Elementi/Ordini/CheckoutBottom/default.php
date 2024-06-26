<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
include(tpf(ElementitemaModel::p("CHECKOUT_REGALO","", array(
	"titolo"	=>	"Blocco regalo: dedica e firma",
	"percorso"	=>	"Elementi/Ordini/Regalo",
))));
?>

<?php include(tpf("Ordini/note_acquisto.php"));?>

<?php
include(tpf(ElementitemaModel::p("CHECKOUT_NEWSLETTER","", array(
	"titolo"	=>	"Check iscrizione newsletter",
	"percorso"	=>	"Elementi/Ordini/IscrizioneNewsletter",
))));
?>

<?php
if (!v("disattiva_antispam_checkout")) { 
	include (tpf("Elementi/Pagine/campo-captcha-registrazione.php"));
}
?>

<div class="uk-margin">
	<?php $idCondizioni = PagineModel::gTipoPagina("CONDIZIONI"); ?>
	<?php if ($idCondizioni) { ?>
	<div class="condizioni_privacy uk-margin uk-text-muted uk-text-small"><?php echo gtext("Ho letto e accettato i");?> <a target="_blank" href="<?php echo $this->baseUrl."/".getUrlAlias($idCondizioni);?>"><?php echo gtext("termini e condizioni di vendita");?></a></div>
	<?php } else { ?>
	<div class="uk-alert uk-alert-danger"><?php echo gtext("Attenzione, definire le condizioni di vendita");?></div>
	<?php } ?>
	
	<div class="class_accetto">
		<?php echo Html_Form::radio("accetto",$values['accetto'],array("<span style='margin-left:8px;'></span><span class='radio_2_testo'>".gtext("NON ACCETTO")."</span><span style='margin-right:20px;'></span>" => "non_accetto", "<span style='margin-left:8px;'></span><span class='radio_2_testo'>".gtext("ACCETTO")."</span>" => "accetto"),"radio_2");?>
	</div>
</div>

<?php if (v("piattaforma_di_demo")) { ?>
<div class="uk-text-center uk-alert-danger uk-margin-remove" uk-alert>
	<?php echo gtext("Attenzione, questa è una piattaforma di demo e non è possibile completare l'acquisto.");?>
	<button class="uk-alert-close" type="button" uk-close></button>
</div>
<?php } else {
	include(tpf(ElementitemaModel::p("CHECKOUT_PULSANTE_ACQUISTA","", array(
		"titolo"	=>	"Pulsante completa acquisto",
		"percorso"	=>	"Elementi/Ordini/PulsanteCompletaAcquisto",
	))));
} ?> 
