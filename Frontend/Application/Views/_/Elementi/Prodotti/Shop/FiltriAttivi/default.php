<?php if (!defined('EG')) die('Direct access not allowed!');

$viewStatusTogliFiltro = $this->getViewStatusUsingVariables(array("p"=>1));

if (($id_categoria && $id_categoria != $idShop) || $idTag && isset($aliasTagCorrente) || ($idMarchio && isset($aliasMarchioCorrente)) || !empty(CaratteristicheModel::$filtriUrl) || !empty(CaratteristicheModel::$filtriUrl) || !empty(RegioniModel::$filtriUrl) || !empty(AltriFiltri::$filtriUrl))
echo gtext("Filtri attivi").": ";

if ($id_categoria && $id_categoria != $idShop)
{
	$filtroSelezionatoUrl = $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($id_categoria, $idMarchio, $idShop, $viewStatusTogliFiltro, $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri,$filtriUrlAltriTuttiAltri);
	$carV = cfield($datiCategoria, "alias");
	include(tpf("/Elementi/Categorie/filtro_attivo.php"));
}

if ($idTag && isset($aliasTagCorrente))
{
	$filtroSelezionatoUrl = $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio(0, $idMarchio, $id_categoria, $viewStatusTogliFiltro, $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri,$filtriUrlAltriTuttiAltri);
	$carV = $aliasTagCorrente;
	include(tpf("/Elementi/Categorie/filtro_attivo.php"));
}

if ($idMarchio && isset($aliasMarchioCorrente))
{
	$filtroSelezionatoUrl = $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, 0, $id_categoria, $viewStatusTogliFiltro, $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri,$filtriUrlAltriTuttiAltri);
	$carV = $aliasMarchioCorrente;
	include(tpf("/Elementi/Categorie/filtro_attivo.php"));
}

foreach (CaratteristicheModel::$filtriUrl as $car => $carVs) {
	foreach ($carVs as $carV) {
		$filtroSelezionatoUrlTutti = CaratteristicheModel::getUrlCaratteristicheTutti($car);
		$filtroSelezionatoUrl = $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, $viewStatusTogliFiltro, $filtroSelezionatoUrlTutti, $filtriUrlLocTuttiAltri,$filtriUrlAltriTuttiAltri);
		include(tpf("/Elementi/Categorie/filtro_attivo.php"));
	}
}

foreach (RegioniModel::$filtriUrl as $car => $carVs) {
	foreach ($carVs as $carV) {
		$filtroSelezionatoUrlTutti = RegioniModel::getUrlCaratteristicheTutti($car);
		$filtroSelezionatoUrl = $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, $viewStatusTogliFiltro, $filtriUrlTuttiAltri, $filtroSelezionatoUrlTutti,$filtriUrlAltriTuttiAltri);
		include(tpf("/Elementi/Categorie/filtro_attivo.php"));
	}
}

foreach (AltriFiltri::$filtriUrl as $car => $carV) {
	$filtroSelezionatoUrlTutti = AltriFiltri::getUrlCaratteristicheTutti($car);
	$filtroSelezionatoUrl = $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $id_categoria, $viewStatusTogliFiltro, $filtriUrlTuttiAltri, $filtriUrlLocTuttiAltri,$filtroSelezionatoUrlTutti);
	include(tpf("/Elementi/Categorie/filtro_attivo.php"));
}
 
