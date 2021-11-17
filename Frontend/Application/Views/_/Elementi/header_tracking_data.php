<?php if (!defined('EG')) die('Direct access not allowed!');

$nomePaginaPerTracking = "";
$idPaginaPerTracking = 0;
$codicePerTracking = "";
$codiceConversioneGoogle = "";

if (isset($isPage)) {
	foreach ($pages as $p) {
		$nomePaginaPerTracking = htmlentitydecode($p["pages"]["title"]);
		$idPaginaPerTracking = $p["pages"]["id_page"];
		$codicePerTracking = $p["pages"]["codice"];
		$codiceConversioneGoogle = htmlentitydecode($p["pages"]["codice_js"]);
	}
}
