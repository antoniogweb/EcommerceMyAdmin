<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if ($islogged)
{
	$breadcrumb = array(
		gtextPlain("Home") 		=> $this->baseUrl,
		gtextPlain("Area riservata")	=>	$this->baseUrl."/area-riservata",
		gtextPlain("Ordini effettuati")	=>	$this->baseUrl."/ordini-effettuati",
		gtextPlain("Resoconto Ordine") => "",
	);
}
else
{
	$breadcrumb = array(
		gtextPlain("Home") 		=> $this->baseUrl,
		gtextPlain("Resoconto Ordine") => "",
	);
}

$titoloPagina = gtextPlain("Resoconto dell'ordine");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "ordini";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<?php if ($islogged) { $isFromAreariservata = true;}?>

