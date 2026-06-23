<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php
$pReso = PagesModel::g()->addJoinTraduzionePagina()->whereId((int)PagesModel::gTipoPagina("RICHIEDI_RESO", false))->first();

if ($islogged)
{
	$breadcrumb = array(
		gtext("Home") 		=> $this->baseUrl,
		gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
		gtext("Ordini effettuati")	=>	$this->baseUrl."/ordini-effettuati",
		gtext("Reso Ordine") => "",
	);
}
else
{
	$breadcrumb = array(
		gtext("Home") 		=> $this->baseUrl,
		gtext("Reso Ordine") => "",
	);
}

$titoloPagina = gtext("Reso dell'ordine")." #".$ordine["id_o"];

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "ordini";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<?php if ($islogged) { $isFromAreariservata = true;}?>

<?php
if (!isset($baseUrl))
	$baseUrl = $this->baseUrl."/";
?>

<?php if ($periodo["richiesta"]) { ?>
	<div class="uk-alert uk-alert-success">
	<?php echo gtext("In data")." <b>".smartDate($periodo["data_richiesta"], v("default_date_format")." H:i");?></b>
	<?php echo gtext("hai eseguito un richiesta di reso per l'ordine")." <b>".$ordine["id_o"]; ?></b>
	<?php if ($periodo["id_spedizione_negozio"]) { echo " - ". gtext("merce consegnata il")." ".smartDate($periodo["data_inizio"], v("default_date_format"));} ?>
	</div>
<?php } else { ?>
	<?php if (isset($pReso)) { ?>
	<div><?php echo htmlentitydecode(field($pReso, "description"));?></div>
	<?php } ?>
	
	<?php if (OrdiniperiodiresoModel::g(false)->inPeriodoReso($periodo["id_o_periodo_reso"])) { ?>
	<form action="<?php echo OrdiniperiodiresoModel::g(false)->getUrlRichiediReso($periodo["id_o_periodo_reso"]);?>" method="POST">
		<button type="submit" class="uk-button uk-button-primary">
			<?php echo gtext("Conferma la richiesta di reso dell'ordine", false)." ".$ordine["id_o"]; ?>
			<?php if ($periodo["id_spedizione_negozio"]) { echo " - ". gtext("merce consegnata il")." ".smartDate($periodo["data_inizio"], v("default_date_format"));} ?>
			<span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/arrow-right.svg");?></span>
		</button>
		<?php include (tpf("Elementi/Pagine/campo-csrf.php"));?>
		<input type="hidden" name="invia" value="invia" />
	</form>
	<?php if (false && $periodo["id_spedizione_negozio"]) { ?>
	<br />
	<span class="uk-text-small"><?php echo gtext("Il reso può essere richiesto nel seguente periodo:");?> <?php echo smartDate($periodo["data_inizio"], v("default_date_format"));?> - <b><?php echo smartDate($periodo["data_fine"], v("default_date_format"));?></b><br /></span>
	<?php } ?>
	<?php } ?>
<?php } ?>

<?php
// if (isset($isFromAreariservata))
	include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
?>

