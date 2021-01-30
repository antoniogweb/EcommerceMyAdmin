<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if ($islogged)
{
	$breadcrumb = array(
		gtext("Home") 		=> $this->baseUrl,
		gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
		gtext("Ordini effettuati")	=>	$this->baseUrl."/ordini-effettuati",
		gtext("Resoconto Ordine") => "",
	);
}
else
{
	$breadcrumb = array(
		gtext("Home") 		=> $this->baseUrl,
		gtext("Resoconto Ordine") => "",
	);
}

$titoloPagina = gtext("Resoconto dell'ordine");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "ordini";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<?php if ($islogged) { $isFromAreariservata = true;}?>

