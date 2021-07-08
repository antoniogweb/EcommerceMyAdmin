<?php if (!defined('EG')) die('Direct access not allowed!');

foreach (CaratteristicheModel::$filtriUrl as $car => $carVs) {
	foreach ($carVs as $carV) {
		$filtroSelezionatoUrlTutti = CaratteristicheModel::getUrlCaratteristicheTutti($car);
		$filtroSelezionatoUrl = $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, "", $filtroSelezionatoUrlTutti, $filtriUrlLocTuttiAltri,$filtriUrlAltriTuttiAltri);
		include(tpf("/Elementi/Categorie/filtro_attivo.php"));
	}
}

foreach (RegioniModel::$filtriUrl as $car => $carVs) {
	foreach ($carVs as $carV) {
		$filtroSelezionatoUrlTutti = RegioniModel::getUrlCaratteristicheTutti($car);
		$filtroSelezionatoUrl = $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, "", $filtriUrlTuttiAltri, $filtroSelezionatoUrlTutti,$filtriUrlAltriTuttiAltri);
		include(tpf("/Elementi/Categorie/filtro_attivo.php"));
	}
}

foreach (Filtri::$filtriUrl as $car => $carV) {
	$filtroSelezionatoUrlTutti = Filtri::getUrlCaratteristicheTutti($car);
	$filtroSelezionatoUrl = $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, "", $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri,$filtroSelezionatoUrlTutti);
	include(tpf("/Elementi/Categorie/filtro_attivo.php"));
}
