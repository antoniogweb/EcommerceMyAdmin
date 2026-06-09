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

class OrdiniacquistorigheController extends BaseController
{
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_modulo_acquisti"))
			$this->responseCode(403);
	}
	
	public function salva()
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
// 		Params::$automaticConversionToDbFormat = false;
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->beginTransaction();
		
		$this->clean();
		
		$valori = $this->request->post("valori","[]");
		
		$valori = json_decode($valori, true);
		
		$arrayIdPage = array();
		
		if (count($valori) > 0 && isset($valori[0]["id_ordine_acquisto_riga"]))
		{
			$idOrdine = OrdiniacquistorigheModel::g()->whereId((int)$valori[0]["id_ordine_acquisto_riga"])->field("id_ordine_acquisto");
			
			if (!OrdiniacquistoModel::g()->isBozza($idOrdine))
			{
				if (v("usa_transactions"))
					$this->m[$this->modelName]->db->commit();
				
				return;
			}
		}
		
		$combModel = new CombinazioniModel();
		
		foreach ($valori as $v)
		{
			if ($v["quantita"] > 0)
			{
				$recordRiga = $this->m[$this->modelName]->selectId($v["id_ordine_acquisto_riga"]);
				
				if (!empty($recordRiga))
				{
					// $giacenza = (int)$this->m("CombinazioniModel")->whereId((int)$recordRiga["id_c"])->field("giacenza");
					
					$recordArticolo = MagazzinoarticoliModel::g()->selectId((int)$v["id_articolo"]);
					$recordWeb = MagazzinoarticolicombinazioniModel::getDatiWeb((int)$v["id_articolo"]);
					
					$rigaTipologia = $this->m("RighetipologieModel")->clear()->selectId((int)$recordRiga["id_ordine_acquisto_riga_tipologia"]);
					
					$moltiplicatore = 1;
					
					// if (!empty($rigaTipologia))
					// 	$moltiplicatore = (int)$rigaTipologia["moltiplicatore"];
					
					$price = abs((float)setPrice($v["prezzo"])) * $moltiplicatore;
					
					$sconto1 = $v["sconto_1"] ?? 0;
					$sconto2 = $v["sconto_2"] ?? 0;
					
					// if (!empty($rigaTipologia))
					// {
					// 	$price = $prezzo_intero;
					// 	$sconto = 0;
					// }
					
					$this->m[$this->modelName]->setValues(array(
						"id_articolo"		=>	(int)$v["id_articolo"],
						"quantita"			=>	$v["quantita"],
						"prezzo"			=>	$price,
						"sconto_1"			=>	$sconto1,
						"sconto_2"			=>	$sconto2,
						"titolo"			=>	$v["titolo"],
						"id_c"				=>	$recordWeb["id_c"] ?? 0,
						"id_page"			=>	$recordWeb["id_page"] ?? 0,
						"codice"			=>	isset($recordWeb["id_c"]) ? ($recordArticolo["codice"] ?? $v["codice"]) : $v["codice"],
						"attributi"			=>	isset($recordWeb["id_c"]) ? $combModel->getStringa($recordWeb["id_c"], "<br />") : "",
					));
					
					// print_r($this->m[$this->modelName]->values);
					
					$this->m[$this->modelName]->update($v["id_ordine_acquisto_riga"]);
				}
			}
			else
				$this->m[$this->modelName]->del($v["id_ordine_acquisto_riga"]);
		}
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->commit();
	}
}
