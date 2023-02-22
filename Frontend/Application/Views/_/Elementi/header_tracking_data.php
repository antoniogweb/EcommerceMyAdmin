<?php if (!defined('EG')) die('Direct access not allowed!');

$nomePaginaPerTracking = "";
$codicePerTracking = 0;
$codiceConversioneGoogle = "";

if (!isset($idPaginaPerTracking))
	$idPaginaPerTracking = isset($isPage) ? (int)PagesModel::$currentIdPage : 0;

if (isset($isPage)) {
	if (!isset($pagesMeta))
		$pagesMeta = PagesModel::getDataPerMeta($idPaginaPerTracking, PagesModel::$IdCombinazione);
		
	foreach ($pagesMeta as $p) {
		$nomePaginaPerTracking = htmlentitydecode($p["pages"]["title"]);
		$idPaginaPerTracking = $p["pages"]["id_page"];
		$codicePerTracking = $p["pages"]["codice"];
		$codiceConversioneGoogle = htmlentitydecode($p["pages"]["codice_js"]);
	}
}
