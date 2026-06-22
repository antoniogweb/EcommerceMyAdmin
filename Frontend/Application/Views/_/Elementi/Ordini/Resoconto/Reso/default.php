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
<?php if (isset($pReso)) { ?>
<div><?php echo htmlentitydecode(field($pReso, "description"));?></div>
<?php } ?>

<?php
// if (isset($isFromAreariservata))
	include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
?> 

