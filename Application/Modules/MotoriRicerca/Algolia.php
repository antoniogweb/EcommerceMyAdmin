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

use Algolia\AlgoliaSearch\SearchClient;

class Algolia extends MotoreRicerca
{
	public function gCampiForm()
	{
		return 'titolo,attivo,account_id,api_key,api_key_public';
	}
	
	public function editFormStruct($model, $record)
	{
		$model->formStruct["entries"]["api_key"]["labelString"] = "Admin API KEY";
		
		$model->formStruct["entries"]["api_key_public"]["labelString"] = "Search-Only API KEY";
	}
	
	public function isAttivo()
	{
		if (trim($this->params["account_id"]) && trim($this->params["api_key"]) && trim($this->params["api_key_public"]))
			return true;
		
		return false;
	}
	
	private function getClient($tipo = "leggi")
	{
		require_once(LIBRARY . '/External/libs/vendor/autoload.php');
		
		$apiKey = ($tipo == "leggi") ? "api_key_public" : "api_key";
		
		$client = SearchClient::create($this->params["account_id"], $this->params[$apiKey]);
		
		return $client;
	}
	
	public function inviaProdotti($idPage = 0, $indice = "prodotti_it")
	{
		$oggetti = $this->ottieniOggetti($idPage);
		
		$nomeCampoId = $this->getNomeCampoId();
		
		$struct = array();
		
		foreach ($oggetti as $o)
		{
			$catString = count($o["categorie"]) > 0 ? implode(" ",$o["categorie"][0]) : "";
			
			$struct[] = array(
				"marchio"		=>	$o["marchio"],
				"categorie"		=>	$catString,
				"titolo"		=>	htmlentitydecode($o["titolo"]),
				"marchio_categorie"		=>	$o["marchio"]." ".$catString,
				"marchio_titolo"		=>	$o["marchio"]." ".htmlentitydecode($o["titolo"]),
				$nomeCampoId	=>	"'".$o["id_page"]."'",
			);
		}
		
		$client = $this->getClient("scrivi");
		
		$index = $client->initIndex($indice);
		
		return $index->saveObjects($struct)->wait();
	}
	
	public function svuotaProdotti($indice = "prodotti_it")
	{
		$client = $this->getClient("scrivi");
		
		$index = $client->initIndex($indice);
		
		return $index->clearObjects();
	}
	
	public function cerca($indice, $search)
	{
		$client = $this->getClient();
		
		$index = $client->initIndex($indice);
		
		$search = trim($search);
		
		$res = $index->search($search);
		
// 		print_r($res);
		
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
// 							echo "INSIDE - F:".$field." V:".$tempValue."\n";
						}
					}
					
					$label = $this->vitalizeTesto($this->sanitizeTesto($tempLabel));
					$value = sanitizeHtml(strtolower(strip_tags($tempValue)));
					
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
		
// 		print_r($searchStruct);
		
		$finalStruct = array();
		
		$searchArray = explode(" ", $search);
		
		foreach ($searchStruct as $element)
		{
			if ((int)count($searchArray) === (int)$element["numero_parole"])
				$finalStruct[] = $element;
		}
		
// 		print_r($finalStruct);die();
		
		return $finalStruct;
	}
	
	public function sanitizeTesto($value)
	{
// 		$value = preg_replace('/(\<p\>)(.*?)(\<\/p\>)/s', '[p]${2}[/p]',$value);
// 		$value = preg_replace('/(\<b\>)(.*?)(\<\/b\>)/s', '[b]${2}[/b]',$value);
// 		$value = preg_replace('/(\<strong\>)(.*?)(\<\/strong\>)/s', '[b]${2}[/b]',$value);
		$value = preg_replace('/(\<i\>)(.*?)(\<\/i\>)/s', '[b]${2}[/b]',$value);
		$value = preg_replace('/(\<em\>)(.*?)(\<\/em\>)/s', '[b]${2}[/b]',$value);
// 		$value = preg_replace('/(\<u\>)(.*?)(\<\/u\>)/s', '[i]${2}[/i]',$value);
// 		$value = preg_replace('/\<br \/\>/s', '[br]',$value);
		
// 		$value = preg_replace('/(\<span style=\"text-decoration\: underline\;\"\>)(.*?)(\<\/span\>)/s', '[u]${2}[/u]',$value);
		
// 		$value = preg_replace('/(\<a(.*?)href=\"(.*?)\"(.*?)\>)(.*?)(\<\/a\>)/s', '[a]${3}|${5}[/a]',$value);
		
		
		$value = strip_tags($value);
		
		return sanitizeAll($value);
	}
	
	public function vitalizeTesto($string)
	{
		$string = htmlentitydecode($string);
		$string = strip_tags($string);
		
// 		$string = preg_replace('/(\[p\])(.*?)(\[\/p\])/s', '<p>${2}</p>',$string);
		$string = preg_replace('/(\[b\])(.*?)(\[\/b\])/s', '<b>${2}</b>',$string);
		
// 		$string = preg_replace('/(\[em\])(.*?)(\[\/em\])/s', '<u>${2}</u>',$string);
		
// 		$string = preg_replace('/(\[i\])(.*?)(\[\/i\])/s', '<i>${2}</i>',$string);
		
// 		$string = preg_replace('/(\[br\])/s', '<br />',$string);
		
		return $string;
	}
}
