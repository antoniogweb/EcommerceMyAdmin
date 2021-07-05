<?php
if (!defined('EG')) die('Direct access not allowed!');

trait Filtri
{
	public static $altriFiltri = array();
	public static $filtriUrl = array();
	
	public static $altriFiltriTipi = array();
	
	// Restituisce i filtri nell'URL ma impostando la caratteristica $aliasCar impostata a $aliasCarVal
	public static function getFiltriCaratteristica($aliasCar, $aliasCarVal)
	{
		$temp = self::$filtriUrl;
		
		$temp[$aliasCar] = array(
			$aliasCarVal
		);
		
		return $temp;
	}
	
	// Restituisce un array per URL con filtri a partire dalla caratteristica $aliasCar impostata a $aliasCarVal
	public static function getArrayUrlCaratteristiche($aliasCar, $aliasCarVal)
	{
		$filtri = self::getFiltriCaratteristica($aliasCar, $aliasCarVal);
		
		return self::getArrayFiltri($filtri);
	}
	
	public static function getArrayUrlAll()
	{
		return self::getArrayFiltri(self::$filtriUrl);
	}
	
	public static function getArrayFiltri($filtri)
	{
		$urlFiltriArray = array();
		
		if (count($filtri) > 0 && __CLASS__ != "Filtri")
			$urlFiltriArray[] = v("divisorio_filtri_url");
		
		foreach ($filtri as $car => $carVals)
		{
			$urlFiltriArray[] = $car;
			
			if (is_array($carVals))
			{
				foreach ($carVals as $carVal)
				{
					$urlFiltriArray[] = $carVal;
				}
			}
			else
				$urlFiltriArray[] = $carVals;
		}
		
		return $urlFiltriArray;
	}
	
	// Restituisce un array per URL con filtri togliendo la caratteristica $aliasCar impostata a $aliasCarVal
	public static function getUrlCaratteristicheTutti($aliasCar = null)
	{
		$temp = self::$filtriUrl;
		
		if ($aliasCar && isset($temp[$aliasCar]))
			unset($temp[$aliasCar]);
		
		return self::getArrayFiltri($temp);
	}
	
	public static function filtroSelezionato($aliasCar, $aliasCarVal)
	{
		if (isset(self::$filtriUrl[$aliasCar]))
		{
			if (is_array(self::$filtriUrl[$aliasCar]))
			{
				foreach (self::$filtriUrl[$aliasCar] as $alVal)
				{
					if ($alVal == $aliasCarVal)
						return true;
				}
			}
			else if (self::$filtriUrl[$aliasCar] == $aliasCarVal)
				return true;
		}
		
		return false;
	}
	
	public static function filtroTuttiSelezionato($aliasCar)
	{
		if (!isset(self::$filtriUrl[$aliasCar]))
			return true;
		
		return false;
	}
	
	public static function getValoriCaratteristica($aliasCar)
	{
		if (isset(self::$filtriUrl[$aliasCar]))
			return self::$filtriUrl[$aliasCar];
		
		return array();
	}
}