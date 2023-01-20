<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$stringaCache = '$idTag = '.(int)$idTag.';';
$stringaCache .= '$idMarchio = '.(int)$idMarchio.';';
$stringaCache .= '$id_categoria = '.(int)$id_categoria.';';

foreach (CategoriesModel::$arrayIdsPagineFiltrate as $elemento => $arrayIdFiltrati)
{
	$stringaCache .= 'CategoriesModel::$arrayIdsPagineFiltrate["'.$elemento.'"] = '.json_encode($arrayIdFiltrati).';';
}

$stringaCache .= '$filtriUrlTuttiAltri = '.json_encode($filtriUrlTuttiAltri).';';
$stringaCache .= '$filtriUrlLocTuttiAltri = '.json_encode($filtriUrlLocTuttiAltri).';';
$stringaCache .= '$filtriUrlAltriFiltri = '.json_encode($filtriUrlAltriFiltri).';';
?>
