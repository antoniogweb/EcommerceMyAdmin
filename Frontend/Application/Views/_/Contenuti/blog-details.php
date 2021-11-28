<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php foreach ($pages as $p) {
$standardPage = false;
$urlAlias = getUrlAlias($p["pages"]["id_page"]);
$urlAliasCategoria = getCategoryUrlAlias($p["categories"]["id_c"]);

$titoloPagina = field($p, "title");

include(tpf(ElementitemaModel::p("BLOG_DETAILG_TOP")));
?>

<?php echo $fasce;?>

<?php include(tpf("/Elementi/Pagine/page_bottom.php"));?>
<?php } ?>
