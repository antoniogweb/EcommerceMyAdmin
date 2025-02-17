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

class SpedizioninegoziorigheModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='spedizioni_negozio_righe';
		$this->_idFields='id_spedizione_negozio_riga';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'riga' => array("BELONGS_TO", 'RigheModel', 'id_r',null,"CASCADE"),
			'spedizione' => array("BELONGS_TO", 'SpedizioninegozioModel', 'id_spedizione_negozio',null,"CASCADE"),
        );
    }
    
    public function quantitaCrud($record)
	{
		if (SpedizioninegozioModel::g()->deletable($record["spedizioni_negozio_righe"]["id_spedizione_negozio"]))
			return "<input id-riga='".$record["spedizioni_negozio_righe"]["id_spedizione_negozio_riga"]."' style='max-width:60px;' class='form-control' name='quantity' value='".$record["spedizioni_negozio_righe"]["quantity"]."' />";
		else
			return $record["spedizioni_negozio_righe"]["quantity"];
	}
	
	public function deletable($id)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record))
			return SpedizioninegozioModel::g()->deletable($record["id_spedizione_negozio"]);
		
		return false;
	}
	
	public function insert()
	{
		if (isset($this->values["id_r"]) && isset($this->values["id_spedizione_negozio"]))
		{
			$qtaDaSpedire = RigheModel::qtaDaSpedire((int)$this->values["id_r"]);
			
			if ($qtaDaSpedire > 0)
			{
				$record = $this->clear()->where(array(
					"id_r"	=>	(int)$this->values["id_r"],
					"id_spedizione_negozio"	=>	(int)$this->values["id_spedizione_negozio"],
				))->send(false);
				
				if ((int)count($record) === 0)
				{
					$this->values["quantity"] = (int)$qtaDaSpedire;
					
					return parent::insert();
				}
				else
				{
					$this->values["quantity"] = (int)$record[0]["quantity"] + (int)$qtaDaSpedire;
					
					return parent::update((int)$record[0]["id_spedizione_negozio_riga"]);
				}
			}
		}
		
		return false;
	}
	
	public function del($id = null, $where = null)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && parent::del($id, $where))
			SpedizioninegozioModel::g()->ricalcolaContrassegno($record["id_spedizione_negozio"]);
		
		return true;
	}
	
	public function getCodiciProdottiSpedizione(array $idS)
	{
		return $this->clear()->select("righe.codice,sum(spedizioni_negozio_righe.quantity) as qta")->inner(array("riga"))->where(array(
			"in"	=>	array(
				"id_spedizione_negozio"	=>	forceIntDeep($idS),
			),
		))->groupBy("righe.codice")->orderBy("id_spedizione_negozio_riga")->toList("righe.codice", "aggregate.qta")->send();
	}
}
