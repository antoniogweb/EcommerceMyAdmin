<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php foreach ($pages as $p) {
	$titoloPagina = field($p, "title");
	$sottotitoloPagina = trim(field($p, "sottotitolo")) ? field($p, "sottotitolo") : "";
	$noNumeroProdotti = true;
	$standardPage = false;
	include(tpf("/Elementi/Pagine/page_top.php"));
?>
	<?php include(tpf(ElementitemaModel::p("FASCIA_TESTO_DESCRIZIONE")));?>

	<?php echo $fasce;

	include(tpf("/Elementi/Pagine/page_bottom.php"));
}
