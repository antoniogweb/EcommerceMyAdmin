<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (isset($corr))
	$p = $corr;

$idPr = getPrincipale(field($p, "id_page"));
$hasCombinations = hasCombinations($idPr);
$hasSoloCombinations = hasCombinations($idPr, false);
$urlAlias = getUrlAlias($p["pages"]["id_page"]);
$prezzoMinimo = prezzoMinimo($idPr);
$stringaDa = (!$hasSoloCombinations || VariabiliModel::combinazioniLinkVeri()) ? "" : gtext("da");
$prezzoPienoIvato = calcolaPrezzoIvato($p["pages"]["id_page"], $prezzoMinimo);
$prezzoFinaleIvato = calcolaPrezzoFinale($p["pages"]["id_page"], $prezzoMinimo);
$percentualeSconto = getPercSconto($prezzoPienoIvato, $prezzoFinaleIvato);
$percSconto = getPercScontoF($prezzoPienoIvato, $prezzoFinaleIvato);
$isProdotto = isProdotto($idPr);
