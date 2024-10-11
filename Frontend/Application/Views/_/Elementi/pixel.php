<?php if (!defined('EG')) die('Direct access not allowed!');

$arrayScriptHead = PixelModel::applicaMetodoATuttiIModuli("getHeadScript");

foreach ($arrayScriptHead as $scriptHead)
{
	echo $scriptHead."\n";
}

if (isset($idOrdineGtm))
{
	$arrayPixelTracciamento = PixelModel::applicaMetodoATuttiIModuli("getPurchaseScript", array($idOrdineGtm, ImpostazioniModel::$valori));

	foreach ($arrayPixelTracciamento as $scriptPixel)
	{
		echo $scriptPixel."\n";
	}
}
