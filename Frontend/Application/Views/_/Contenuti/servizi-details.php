<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php foreach ($pages as $p) {
$standardPage = false;
$urlAlias = getUrlAlias($p["pages"]["id_page"]);
$urlAliasCategoria = getCategoryUrlAlias($p["categories"]["id_c"]);

$titoloPagina = field($p, "title");

include(tpf(ElementitemaModel::p("SERVIZI_DETAILS_TOP","", array(
	"titolo"	=>	"Parte superiore servizi",
	"percorso"	=>	"Elementi/Sezioni/Servizi/Dettaglio/Top",
))));
?>

<?php echo $fasce;?>

<?php include(tpf("/Elementi/Pagine/page_bottom.php"));?>
<?php } ?>
