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

class BaseMotoriricercaController extends BaseController
{
	protected $estratiDatiGenerali = false;

	public function cerca($modulo = "")
	{
		if (!v("attiva_gestione_motori_ricerca"))
			$this->responseCode(403);
		
		$modulo = strtoupper((string)$modulo);
		
		$search = $this->request->get("term","","strip_tags");

		if (trim((string)$search) && trim($modulo) && MotoriricercaModel::g()->checkModulo($modulo, ""))
		{
			IpcheckModel::check("CERCA $modulo");
			
			if (MotoriricercaModel::getModulo($modulo)->isAttivo())
			{
				User::setPostCountryFromUrl();

				$jsonArray = MotoriricercaModel::getModulo($modulo)->cerca("prodotti_".Params::$lang, $search, Params::$lang);
				
				// Salva la ricerca
				if (v("salva_ricerche"))
				{
					$this->m("RicercheModel")->sValues(array(
						"termini"	=>	(string)$search,
						"cart_uid"	=>	User::$cart_uid,
					));
					
					$this->m("RicercheModel")->insert();
				}
				
// 				print_r($jsonArray);die();
				
				header('Content-type: application/json; charset=utf-8');
				
				echo json_encode($jsonArray);
			}
			else
				$this->responseCode(403);
		}
		else
			$this->responseCode(403);
	}
	
	public function ricercasemantica()
	{
		ini_set("memory_limit",v("ricerca_semantica_memory_limit"));
		
		if (!v("attiva_ricerca_semantica"))
			$this->responseCode(403);
		
		$search = $this->request->get("term","","strip_tags");
		
		if (trim((string)$search))
		{
			$searchArray = explode(" ", preg_quote($search, "/"));
			
			// Ordino in ordine decrescente
			usort($searchArray, function($a, $b) {
				return strlen($b) - strlen($a); // Decrescente: $b - $a
			});
			
// 			$searchArrayFinale = array();
// 			
// 			foreach ($searchArray as $sa)
// 			{
// 				if (strlen($sa) >= 3 || is_numeric($sa))
// 					$searchArrayFinale[] = $sa;
// 			}
			$pattern = implode("|", $searchArray);
		
			IpcheckModel::check("CERCA SEMANTICO");
			
			$result = EmbeddingsModel::ricercaSemantica($search, null, Params::$lang, 8);
			
			$idPages = $result["pages"];
			$estratti = $result["estratti"];
			
			$jsonArray = array();
			
			// Salva la ricerca
// 			if (v("salva_ricerche_semantiche"))
// 			{
// 				$this->m("RicercheModel")->sValues(array(
// 					"termini"	=>	(string)$search,
// 					"cart_uid"	=>	User::$cart_uid,
// 				));
// 				
// 				$this->m("RicercheModel")->insert();
// 			}
			
			if (count($idPages) > 0)
			{
				$p = PagesModel::g(false)->where(array(
					"   in"	=>	array(
						"id_page"	=>	forceIntDeep($idPages),
					)
				));
				
				TraduzioniModel::sLingua(Params::$lang, "front");
				$contents = MotoriricercaModel::getModuloPadre()->strutturaFeedProdotti($p, 0, 0, false, 0, 0);
				TraduzioniModel::rLingua();
				
				foreach ($contents as $c)
				{
					$idPage = $c["id_page"];
					
					$estratto = (isset($estratti[$idPage]) && $estratti[$idPage]) ? sanitizeHtmlLight(stripTagsDecode($estratti[$idPage])) : "";
					
					$titolo = preg_replace("/($pattern)/i","<b>$1</b>",$c["titolo"], 10);
					$estratto = preg_replace("/($pattern)/i","<b>$1</b>",$estratto, 10);
					
					$jsonArray[] = array(
						"label"	=>	$titolo,
						"value"	=>	Url::getRoot().getUrlAlias($idPage),
						"estratto"	=>	$estratto,
						"numero_parole"	=>	1,
						"immagine"	=>	$c["immagine_principale"],
					);
				}
			}
			
			header('Content-type: application/json; charset=utf-8');
			
			echo json_encode($jsonArray);
		}
		else
			$this->responseCode(403);
	}
}
