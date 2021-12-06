<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php include(tpf(ElementitemaModel::p("SHOP_TOP")));?>

<?php echo $fasce ?? null;?>

<?php include(tpf("/Elementi/gtm_impressioni_lista.php"));?>
<?php include(tpf("/Elementi/Pagine/page_bottom.php"));?>
