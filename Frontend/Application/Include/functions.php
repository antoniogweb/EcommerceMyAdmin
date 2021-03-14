<?php

if (!defined('EG')) die('Direct access not allowed!');

function getAllineamentoLayer($layer)
{
	$arrayDimensioni = array(
		"", "xs", "s", "m", "l", "xl"
	);
	
	$attributo = "";
	
	foreach ($arrayDimensioni as $dimensione)
	{
		$keyDim = $dimensione ? "_$dimensione" : "";
		
		$posizione = $layer["contenuti"]["posizione".$keyDim];
		
		if (!$dimensione)
			$posizione = str_replace("eg-","uk-",$posizione);
		
		$atDimensione = $dimensione ? '@'.$dimensione : '';
		
		$attributo .= $posizione ? " ".$posizione.$atDimensione." " : "";
		
		$arrayPosizioni = array("left", "right", "center");
		
		foreach ($arrayPosizioni as $p)
		{
			if (strstr($posizione, $p))
			{
				$prefisso = $atDimensione ? "eg" : "uk";
				
				$attributo .= ' '.$prefisso.'-text-'.$p.$atDimensione.' ';
					break;
			}
		}
		
		if ($dimensione && !$layer["contenuti"]["visibile".$keyDim])
			$attributo .= ' eg-hidden'.$atDimensione.' ';
	}
	
	return $attributo;
}

function getAnimazioneLayer($layer, $valore)
{
	switch ($layer["contenuti"]["animazione"])
	{
		case "-x":
			return "uk-slideshow-parallax='x: -$valore,$valore'";
		case "x":
			return "uk-slideshow-parallax='x: $valore,-$valore'";
		case "-y":
			return "uk-slideshow-parallax='y: $valore,-$valore'";
		case "y":
			return "uk-slideshow-parallax='y: -$valore,$valore'";
	}
	
	return "";
}

