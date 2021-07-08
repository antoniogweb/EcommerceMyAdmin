<?php if (!defined('EG')) die('Direct access not allowed!');

if (!isset($id_categoria))
	$id_categoria = 0;

if (!isset($idMarchio))
	$idMarchio = 0;

if (!isset($idTag))
	$idTag = 0;

$nazioneAlias = RegioniModel::$nAlias;
$regioneAlias = RegioniModel::$rAlias;

$filtriUrlTuttiAltri = CaratteristicheModel::getUrlCaratteristicheTutti();
$filtriUrlLocTuttiAltri = RegioniModel::getUrlCaratteristicheTutti();
$filtriUrlAltriTuttiAltri = Filtri::getUrlCaratteristicheTutti();
