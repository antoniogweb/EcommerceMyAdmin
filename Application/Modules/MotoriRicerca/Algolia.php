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
		if ($this->params["attivo"] && trim($this->params["account_id"]) && trim($this->params["api_key"]) && trim($this->params["api_key_public"]))
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
		$ultimiDatiInviati = $this->leggiDatiInviati();
		
// 		print_r($ultimiDatiInviati);
		
		$oggetti = $this->ottieniOggetti($idPage);
		
		$nomeCampoId = $this->getNomeCampoId();
		
		$struct = $structInviati = $idsNuoviTotali = array();
		
		foreach ($oggetti as $o)
		{
			$idPage = $o["id_page"];
			
			$idsNuoviTotali[] = $idPage;
			
			$catString = count($o["categorie"]) > 0 ? $this->pulisciXss(implode(" ",$o["categorie"][0])) : "";
			$marchio = $this->pulisciXss($o["marchio"]);
			$titolo = $this->pulisciXss($o["titolo"]);
			
			$hash = md5($marchio.$catString.$titolo.$marchio." ".$catString.$marchio." ".$titolo);
			
			if (!isset($ultimiDatiInviati[$idPage]) || (string)$ultimiDatiInviati[$idPage] !== $hash)
			{
				$structInviati[$idPage] = $hash;
				
				$struct[] = array(
					"marchio"		=>	$marchio,
					"categorie"		=>	$catString,
					"titolo"		=>	$titolo,
					"marchio_categorie"		=>	$marchio." ".$catString,
					"marchio_titolo"		=>	$marchio." ".$titolo,
					$nomeCampoId	=>	$idPage,
				);
			}
		}
		
		$daEliminare = array();
		
// 		echo "STRUCT ARRAY:\n";
// 		print_r($idsNuoviTotali);
		
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
		
		$client = $this->getClient("scrivi");
		
		$index = $client->initIndex($indice);
		
		$this->salvaDatiInviati($structInviati);
		
		$log = "DA ELIMINARE: ".count($daEliminare)."\n";
		$log .= print_r($daEliminare, true);
		$log .= "DA AGGIUNGERE / AGGIORNARE ".count($struct)."\n";
		$log .= print_r($struct, true);
		
		if (count($daEliminare) > 0)
			print_r($index->deleteObjects($daEliminare));
		
		if (count($struct) > 0)
			print_r($index->saveObjects($struct)->wait());
		
		return $log;
	}
	
	public function svuotaProdotti($indice = "prodotti_it")
	{
		$client = $this->getClient("scrivi");
		
		$index = $client->initIndex($indice);
		
		$index->clearObjects();
		
		$this->salvaDatiInviati(array());
		
		return "";
	}
	
	public function cerca($indice, $search)
	{
		$client = $this->getClient();
		
		$index = $client->initIndex($indice);
		
		$search = trim($search);
		
		$res = $index->search($search);
		
		return $this->elaboraOutput($search, $res);
	}
}
