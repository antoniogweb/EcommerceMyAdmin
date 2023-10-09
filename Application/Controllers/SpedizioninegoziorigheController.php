<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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

class SpedizioninegoziorigheController extends BaseController {
	
	public $sezionePannello = "ecommerce";
	
	protected $campoDaModificare = "quantity";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		if (!v("attiva_gestione_spedizioni"))
			$this->responseCode(403);
		
		$this->tabella = gtext("spedizioni negozio",true);
	}
	
	public function salva()
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
		Params::$automaticConversionToDbFormat = false;
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->beginTransaction();
		
		$this->clean();
		
		$valori = $this->request->post("valori","[]");
		
		$valori = json_decode($valori, true);
		
		$arrayIdPage = array();
		
		if (count($valori) > 0 && isset($valori[0]["id_riga"]))
		{
			$idSpedizione = $this->m[$this->modelName]->whereId((int)$valori[0]["id_riga"])->field("id_spedizione_negozio");
			
			if (!SpedizioninegozioModel::g()->deletable($idSpedizione))
			{
				if (v("usa_transactions"))
					$this->m[$this->modelName]->db->commit();
				
				return;
			}
		}
		
		foreach ($valori as $v)
		{
			if ($v["quantity"] > 0)
			{
				$recordRiga = $this->m[$this->modelName]->selectId($v["id_riga"]);
				
				if (!empty($recordRiga))
				{
					$this->m[$this->modelName]->setValues(array(
						"".$this->campoDaModificare.""			=>	$v["quantity"],
					));
					
					
					$this->m[$this->modelName]->update($v["id_riga"]);
				}
			}
			else
				$this->m[$this->modelName]->del($v["id_riga"]);
		}
		
		if (v("usa_transactions"))
			$this->m[$this->modelName]->db->commit();
	}
}
