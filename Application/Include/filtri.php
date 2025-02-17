<?php
// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2025  Antonio Gallo (info@laboratoriolibero.com)
// See COPYRIGHT.txt and LICENSE.txt.
//
// This file is part of EcommerceMyAdmin
//
// EcommerceMyAdmin is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// EcommerceMyAdmin is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with EcommerceMyAdmin.  If not, see <http://www.gnu.org/licenses/>.

if (!defined('EG')) die('Direct access not allowed!');

trait Filtri
{
	public static $altriFiltri = array();
	public static $filtriUrl = array();
	
	public static $altriFiltriTipi = array();
	
	public static $aliasValoreTipoPromo = "";
	public static $aliasValoreTipoNuovo = "";
	public static $aliasValoreTipoInEvidenza = "";
	
	// Restituisce i filtri nell'URL ma impostando la caratteristica $aliasCar impostata a $aliasCarVal
	public static function getFiltriCaratteristica($aliasCar, $aliasCarVal)
	{
		$temp = self::$filtriUrl;
		
		if (get_called_class() != "AltriFiltri")
			$temp[$aliasCar] = array(
				$aliasCarVal
			);
		else
			$temp[$aliasCar] = $aliasCarVal;
		
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
		
		if (count($filtri) > 0 && get_called_class() != "AltriFiltri")
			$urlFiltriArray[] = v("divisorio_filtri_url");
		
		if (!empty(self::$altriFiltri))
		{
			$tempAltriFiltri = array_reverse(self::$altriFiltri);
			
			foreach ($tempAltriFiltri as $filtro)
			{
				if (isset($filtri[$filtro]))
				{
					$urlFiltriArray[] = $filtro;
					$urlFiltriArray[] = $filtri[$filtro];
				}
			}
		}
		else
		{
			foreach ($filtri as $car => $carVals)
			{
				foreach ($carVals as $carVal)
				{
					$urlFiltriArray[] = $car;
					$urlFiltriArray[] = $carVal;
				}
			}
		}
		
		return $urlFiltriArray;
	}
	
	// Restituisce un array per URL con filtri togliendo la caratteristica $aliasCar impostata a $aliasCarVal
	public static function getUrlCaratteristicheTutti($aliasCar = null, $aliasCarV = null)
	{
		$temp = self::$filtriUrl;
		
		if ($aliasCar && isset($temp[$aliasCar]))
		{
			if (isset($aliasCarV) && count($temp[$aliasCar]) > 1)
			{
				$key = array_search($aliasCarV, $temp[$aliasCar]);
				
				if ($key !== false)
					unset($temp[$aliasCar][$key]);
			}
			else
				unset($temp[$aliasCar]);
		}
		
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
