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

class SpedizioninegozioModel extends GenericModel {
	
	const TIPOLOGIA_PORTO_FRANCO = 'PORTO_FRANCO';
	const TIPOLOGIA_PORTO_FRANCO_CONTRASSEGNO = 'PORTO_FRANCO_CONTRASSEGNO';
	
	public function __construct() {
		$this->_tables='spedizioni_negozio';
		$this->_idFields='id_spedizione_negozio';
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'spedizioniere' => array("BELONGS_TO", 'SpedizionieriModel', 'id_spedizioniere',null,"RESTRICT","Si prega di selezionare lo spedizioniere".'<div style="display:none;" rel="hidden_alert_notice">id_spedizioniere</div>'),
		);
    }
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'nazione_spedizione'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectNazione(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'provincia_spedizione'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectProvince(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'entryClass'	=>	'provincia_spedizione form_input_text',
				),
				'dprovincia_spedizione'	=>	array(
					"labelString"	=>	"Provincia spedizione",
					'entryClass'	=>	'dprovincia_spedizione form_input_text',
				),
				'id_spedizioniere'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Spedizioniere (GLS / BRT / ...)',
					'options'	=>	SpedizionieriModel::g(false)->selectTendina(),
					'reverse' => 'yes',
				),
			),
		);
	}
	
	public function update($id = null, $where = null)
	{
		$this->setProvinciaFatturazione();
		
		$res = parent::update($id, $where);
		
		return $res;
	}
	
	public function insert()
	{
		if (isset($_GET["id_o"]))
		{
			$ordine = OrdiniModel::g(false)->whereId((int)$_GET["id_o"])->record();
			
			if (!empty($ordine))
			{
				$this->setValue("id_user", $ordine["id_user"]);
				$this->setValue("id_spedizione", $ordine["id_spedizione"]);
				$this->setValue("ragione_sociale", OrdiniModel::getNominativo($ordine), "sanitizeDb");
				$this->setValue("ragione_sociale_2", $ordine["destinatario_spedizione"], "sanitizeDb");
				$this->setValue("indirizzo", $ordine["indirizzo"], "sanitizeDb");
				$this->setValue("cap", $ordine["cap"], "sanitizeDb");
				$this->setValue("citta", $ordine["citta"], "sanitizeDb");
				$this->setValue("provincia", $ordine["provincia"], "sanitizeDb");
				$this->setValue("dprovincia", $ordine["dprovincia"], "sanitizeDb");
				$this->setValue("nazione", $ordine["nazione"], "sanitizeDb");
				$this->setValue("telefono", $ordine["telefono"], "sanitizeDb");
				$this->setValue("email", $ordine["email"], "sanitizeDb");
				$this->setValue("note", $ordine["note"], "sanitizeDb");
				$this->setValue("note_interne", $ordine["note_interne"], "sanitizeDb");
				
				$tipologia = ($ordine["pagamento"] == "contrassegno") ? self::TIPOLOGIA_PORTO_FRANCO_CONTRASSEGNO : self::TIPOLOGIA_PORTO_FRANCO;
				
				$this->setValue("tipologia", $tipologia);
			}
		}
		
		$this->setProvinciaFatturazione();
		
		$res = parent::insert();
		
		return $res;
	}
	
	public function titolo($id)
	{
		$clean["id"] = (int)$id;
		
		$record = $this->selectId($clean["id"]);
		
		if (isset($record["id_spedizione_negozio"]))
		{
			return "N°".$record["id_spedizione_negozio"];
		}
		
		return "";
	}
	
	public function nazione($record)
	{
		return nomeNazione($record["spedizioni"]["nazione_spedizione"]);
	}
	
	public function provincia($record)
	{
		return ($record["spedizioni"]["nazione_spedizione"] == "IT") ? $record["spedizioni"]["provincia_spedizione"] : $record["spedizioni"]["dprovincia_spedizione"];
	}
}
