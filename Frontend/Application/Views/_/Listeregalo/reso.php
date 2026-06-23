<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Liste nascita / regalo") => $this->baseUrl."/liste-regalo/",
	$lista["titolo"]	=>	"",
);

$titoloPagina = gtext("Richiesta di reso - lista")." ".$lista["titolo"];

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "listeregalo";

$pReso = PagesModel::g()->addJoinTraduzionePagina()->whereId((int)PagesModel::gTipoPagina("RICHIEDI_RESO_LISTA", false))->first();

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<div style="display:none;" id="id_lista_regalo"><?php echo $lista["id_lista_regalo"];?></div>

<div class="top">
	<?php if ($periodo["richiesta"]) { ?>
		<div class="uk-alert uk-alert-success">
		<?php echo gtext("In data")." <b>".smartDate($periodo["data_richiesta"], v("default_date_format")." H:i");?></b>
		<?php echo gtext("hai eseguito una richiesta di resto per i prodotti della lista consegnati il")." <b>".smartDate($periodo["data_inizio"], v("default_date_format")); ?></b>
		</div>
	<?php } else { ?>
		<?php if (isset($pReso)) { ?>
		<div><?php echo htmlentitydecode(field($pReso, "description"));?></div>
		<?php } ?>
		
		<?php if (OrdiniperiodiresoModel::g(false)->inPeriodoReso($periodo["id_o_periodo_reso"])) { ?>
		<form action="<?php echo OrdiniperiodiresoModel::g(false)->getUrlRichiediResoLista($periodo["id_o_periodo_reso"]);?>" method="POST">
			<button type="submit" class="uk-button uk-button-primary">
				<?php echo gtext("Conferma la richiesta di reso dei prodotti della lista consegnati il", false)." ".smartDate($periodo["data_inizio"], v("default_date_format")); ?>
				<span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/arrow-right.svg");?></span>
			</button>
			<?php include (tpf("Elementi/Pagine/campo-csrf.php"));?>
			<input type="hidden" name="invia" value="invia" />
		</form>
		<?php } ?>
	<?php } ?>
</div>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
