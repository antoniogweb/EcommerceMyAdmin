<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
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

require_once(LIBRARY."/Application/Modules/Feed.php");

class MotoreRicerca
{
	use Modulo;
	
	public function ottieniOggetti($idPage = 0, $model = null)
	{
		$strutturaProdotti = FeedModel::getModuloPadre()->strutturaFeedProdotti($model, (int)$idPage, 0, false);
		
		return $strutturaProdotti;
	}
	
	protected function getNomeCampoId()
	{
		return "objectID";
	}
	
	protected function getLogPath()
	{
		return $this->cacheAbsolutePath."/".trim($this->params["codice"])."/motori_ricerca_".trim($this->params["codice"])."_last_sent.log";
	}
	
	protected function leggiDatiInviati()
	{
		$path = $this->getLogPath();
		
		if (@is_file($path))
			return unserialize(file_get_contents($path));
		
		return array();
	}
	
	protected function salvaDatiInviati($data)
	{
		$path = $this->getLogPath();
		
		FilePutContentsAtomic($path, serialize($data));
	}
	
	protected function elaboraOutput($search, $res)
	{
		$searchStruct = array();
		
		$arrayCicli = array(
			array("marchio"),
			array("categorie"),
			array("titolo"),
			array("marchio_categorie"),
			array("marchio_titolo"),
		);
		
		$arrayDiParoleInserite = array();
		
		if (isset($res["hits"]))
		{
			foreach ($arrayCicli as $elementiCiclo)
			{
				foreach ($res["hits"] as $r)
				{
					$marchioTrovato = false;
					$numeroMatch = 0;
					$numeroParole = 0;
					
					$tempLabel = $tempValue = "";
					
					foreach ($r["_highlightResult"] as $field => $results)
					{
						if (in_array($field, $elementiCiclo) && isset($results["matchLevel"]) && ($results["matchLevel"] == "full" || $results["matchLevel"] == "partial"))
						{
							if (count($elementiCiclo) === 1)
								$marchioTrovato = true;
							
							$numeroMatch++;
							
							$tempLabel .= " ".$results["value"];
							$tempValue .= " ".$results["value"];
							$numeroParole += (isset($results["matchedWords"]) && is_array($results["matchedWords"])) ? count($results["matchedWords"]) : 0;
						}
					}
					
					$label = $this->vitalizeTesto($this->sanitizeTesto($tempLabel));
					$value = strtolower(strip_tags($tempValue));
					
					if (trim($tempValue) && (int)$numeroMatch === (int)count($elementiCiclo) && !in_array($value, $arrayDiParoleInserite))
					{
						$searchStruct[] = array(
							"label"	=>	$label,
							"value"	=>	$value,
							"numero_parole"	=>	$numeroParole,
						);
						
						$arrayDiParoleInserite[] = $value;
					}
				}
			}
		}
		
		$finalStruct = array();
		
		$searchArray = explode(" ", $search);
		
		foreach ($searchStruct as $element)
		{
			if ((int)count($searchArray) === (int)$element["numero_parole"])
				$finalStruct[] = $element;
		}
		
		return $finalStruct;
	}
	
	protected function applicaCleanFunction($cleanFunction, $valore)
	{
		return call_user_func(array($this, $cleanFunction),$valore);
	}
	
	protected function none($valore)
	{
		return $valore;
	}
	
	protected function getOggettiDaInviareEdEliminare($idPage = 0, $cleanFunction = "none")
	{
		$ultimiDatiInviati = $this->leggiDatiInviati();
		
		$oggetti = $this->ottieniOggetti($idPage);
		
		$nomeCampoId = $this->getNomeCampoId();
		
		$struct = $structInviati = $idsNuoviTotali = array();
		
		foreach ($oggetti as $o)
		{
			$idPage = $o["id_page"];
			
			$idsNuoviTotali[] = $idPage;
			
			$catString = count($o["categorie"]) > 0 ? $this->applicaCleanFunction($cleanFunction, implode(" ",$o["categorie"][0])) : "";
			$marchio = $this->applicaCleanFunction($cleanFunction, $o["marchio"]);
			$titolo = $this->applicaCleanFunction($cleanFunction, $o["titolo"]);
			
// 			$catString = count($o["categorie"]) > 0 ? $this->pulisciXss(implode(" ",$o["categorie"][0])) : "";
// 			$marchio = $this->pulisciXss($o["marchio"]);
// 			$titolo = $this->pulisciXss($o["titolo"]);
			
			$hash = PagesricercaModel::generaHashOggettoRicerca($marchio, $catString, $titolo);
			
			if (!isset($ultimiDatiInviati[$idPage]) || (string)$ultimiDatiInviati[$idPage] !== $hash)
			{
				$structInviati[$idPage] = $hash;
				
				$temp = PagesricercaModel::creaStrutturaOggettoRicerca($marchio, $catString, $titolo);
				$temp[$nomeCampoId] = $idPage;
				
				$struct[] = $temp;
			}
		}
		
		$daEliminare = array();
		
		foreach ($ultimiDatiInviati as $idP => $hash)
		{
			if (!in_array($idP,$idsNuoviTotali))
				$daEliminare[] = $idP;
		}
		
		foreach ($ultimiDatiInviati as $idP => $h)
		{
			if (!isset($structInviati[$idP]) && !in_array($idP,$daEliminare))
				$structInviati[$idP] = $h;
		}
		
		$this->salvaDatiInviati($structInviati);
		
		$log = "DA ELIMINARE: ".count($daEliminare)."\n";
		$log .= print_r($daEliminare, true);
		$log .= "DA AGGIUNGERE / AGGIORNARE: ".count($struct)."\n";
		$log .= print_r($struct, true);
		
		return array($struct, $daEliminare, $log);
	}
	
	protected function sanitizeTesto($value)
	{
		$value = preg_replace('/(\<i\>)(.*?)(\<\/i\>)/s', '[b]${2}[/b]',$value);
		$value = preg_replace('/(\<em\>)(.*?)(\<\/em\>)/s', '[b]${2}[/b]',$value);
		$value = preg_replace('/(\<b\>)(.*?)(\<\/b\>)/s', '[b]${2}[/b]',$value);
		
		$value = strip_tags($value);
		
		return $value;
	}
	
	protected function vitalizeTesto($string)
	{
		$string = strip_tags($string);
		
		$string = preg_replace('/(\[b\])(.*?)(\[\/b\])/s', '<b>${2}</b>',$string);
		
		return $string;
	}
	
	protected function pulisciXss($string)
	{
		return sanitizeHtmlLight(strip_tags(htmlentitydecode($string)));
	}
}
