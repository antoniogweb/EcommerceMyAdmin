<?php if (!defined('EG')) die('Direct access not allowed!');

if (isset($idOrdineGtm))
{
	$arrayPixelTracciamento = PixelModel::applicaMetodoATuttiIModuli("getPurchaseScript", array($idOrdineGtm, ImpostazioniModel::$valori));
	
	foreach ($arrayPixelTracciamento as $scriptPixel)
	{
		echo $scriptPixel."\n";
	}
}
