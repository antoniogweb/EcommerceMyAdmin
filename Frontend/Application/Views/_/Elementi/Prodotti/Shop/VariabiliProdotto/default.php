<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (isset($corr))
{
	PagesModel::clearIdCombinazione();
	$p = $corr;
}

$idPr = !v("attiva_multi_categoria") ? $p["pages"]["id_page"] : getPrincipale(field($p, "id_page"));

if (v("permetti_acquisto_da_categoria_se_ha_una_combinazione"))
{
	$numeroCombinazioniAttive = Cache_Functions::getInstance()->load(new PagesModel())->numeroCombinazioniAttive($idPr);
	$numeroPersonalizzazioni = Cache_Functions::getInstance()->load(new PagesModel())->numeroPersonalizzazioni($idPr);
	
	if ((int)$numeroCombinazioniAttive === 1)
		$numeroCombinazioniAttive--;
	
	$hasCombinations = $numeroPersonalizzazioni ? $numeroPersonalizzazioni : $numeroCombinazioniAttive;
	$hasSoloCombinations = $numeroCombinazioniAttive;
}
else
{
	$hasCombinations = hasCombinations($idPr);
	$hasSoloCombinations = hasCombinations($idPr, false);
}

$urlAlias = getUrlAlias($p["pages"]["id_page"]);

$prezzoMinimo = (isset($p["combinazioni"]["price"]) && !User::$nazione) ? $p["combinazioni"]["price"] : prezzoMinimo($idPr);
$stringaDa = (!$hasSoloCombinations || VariabiliModel::combinazioniLinkVeri()) ? "" : gtext("da");
$prezzoPienoIvato = calcolaPrezzoIvato($p["pages"]["id_page"], $prezzoMinimo);
$prezzoFinaleIvato = calcolaPrezzoFinale($p["pages"]["id_page"], $prezzoMinimo);
$percentualeSconto = getPercSconto($prezzoPienoIvato, $prezzoFinaleIvato);
$percSconto = getPercScontoF($prezzoPienoIvato, $prezzoFinaleIvato);
$isProdotto = !v("attiva_multi_categoria") ? true : isProdotto($idPr);

if (isset($corr))
	PagesModel::restoreIdCombinazione();
