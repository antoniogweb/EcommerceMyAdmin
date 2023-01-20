<?php if (!defined('EG')) die('Direct access not allowed!');

if (isset($idOrdineGtm))
{
	$arrayPixelTracciamentoNoScript = PixelModel::applicaMetodoATuttiIModuli("getPurchaseNoScript", array($idOrdineGtm, ImpostazioniModel::$valori));
	
	foreach ($arrayPixelTracciamentoNoScript as $noScriptPixel)
	{
		echo $noScriptPixel."\n";
	}
}
