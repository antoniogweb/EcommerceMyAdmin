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

class ContenutitradottiModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'contenuti_tradotti';
		$this->_idFields = 'id_ct';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'page' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
			'category' => array("BELONGS_TO", 'CategoriesModel', 'id_c',null,"CASCADE"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'url'		=>	array(
					'labelString'=>	'Link libero',
				),
				'description'		=>	array(
					'type'		 =>	'Textarea',
					'labelString'=>	'Descrizione',
					'className'		=>	'text_input form-control dettagli',
				),
				'descrizione'		=>	array(
					'type'		 =>	'Textarea',
					'labelString'=>	'Descrizione',
					'className'		=>	'text_input form-control dettagli',
				),
				'descrizione_2'		=>	array(
					'type'		 =>	'Textarea',
					'className'		=>	'text_input form-control dettagli',
				),
				'descrizione_3'		=>	array(
					'type'		 =>	'Textarea',
					'className'		=>	'text_input form-control dettagli',
				),
				'testo_link'	=>	array(
					'labelString'	=>	'Testo pulsante',
				),
				'editor_visuale'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Usa editor visuale",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
		);
		
		if ($this->formStructAggiuntivoEntries)
			$this->formStruct["entries"] = $this->formStruct["entries"] + $this->formStructAggiuntivoEntries;
	}
	
	public function titolo($id)
	{
		$clean["id"] = (int)$id;
		
		$record = $this->selectId($clean["id"]);
		
		if (isset($record["title"]))
		{
			return $record["title"] ? $record["title"] : $record["titolo"];
		}
		
		return "";
	}
	
	public function alias($id = null)
	{
		$clean["id"] = (int)$id;
		
		if (!isset($this->values["alias"]) || !trim($this->values["alias"]))
		{
			if (isset($this->values["title"]))
				$this->values["alias"] = sanitizeDb(encodeUrl($this->values["title"]));
			else if (isset($this->values["titolo"]))
				$this->values["alias"] = sanitizeDb(encodeUrl($this->values["titolo"]));
		}
		
		$record = $this->selectId($clean["id"]);
		
		if (!isset($id))
		{
			$idPage = isset($this->values["id_page"]) ? $this->values["id_page"] : 0;
			$idC = isset($this->values["id_c"]) ? $this->values["id_c"] :0;
			$idMarchio = isset($this->values["id_marchio"]) ? $this->values["id_marchio"] : 0;
			$idTag = isset($this->values["id_tag"]) ? $this->values["id_tag"] : 0;
			$idCar = isset($this->values["id_car"]) ? $this->values["id_car"] : 0;
			$idCv = isset($this->values["id_cv"]) ? $this->values["id_cv"] : 0;
			$idFascia = isset($this->values["id_fascia_prezzo"]) ? $this->values["id_fascia_prezzo"] : 0;
			$idAv = isset($this->values["id_av"]) ? $this->values["id_av"] : 0;
		}
		else
		{
			$idPage = $record["id_page"];
			$idC = $record["id_c"];
			$idMarchio = $record["id_marchio"];
			$idTag = $record["id_tag"];
			$idCar = $record["id_car"];
			$idCv = $record["id_cv"];
			$idFascia = $record["id_fascia_prezzo"];
			$idAv = $record["id_av"];
		}
		
		$bindedValues = array($this->values["alias"]);
		
		if ($idPage)
		{
			$bindedValues[] = (int)$idPage;
			$whereClause = "id_page != ?";
			$tabella = "pages";
		}
		else if ($idC)
		{
			$bindedValues[] = (int)$idC;
			$whereClause = "id_c != ?";
			$tabella = "categories";
		}
		else if ($idMarchio)
		{
			$bindedValues[] = (int)$idMarchio;
			$whereClause = "id_marchio != ?";
			$tabella = "marchi";
		}
		else if ($idTag)
		{
			$bindedValues[] = (int)$idTag;
			$whereClause = "id_tag != ?";
			$tabella = "tag";
		}
		else if ($idCar)
		{
			$bindedValues[] = (int)$idCar;
			$whereClause = "id_car != ?";
			$tabella = "caratteristiche";
		}
		else if ($idCv)
		{
			$bindedValues[] = (int)$idCv;
			$whereClause = "id_cv != ?";
			$tabella = "caratteristiche_valori";
		}
		else if ($idFascia)
		{
			$bindedValues[] = (int)$idFascia;
			$whereClause = "id_fascia_prezzo != ?";
			$tabella = "fasce_prezzo";
		}
		else if ($idAv)
		{
			$bindedValues[] = (int)$idAv;
			$whereClause = "id_av != ?";
			$tabella = "attributi_valori";
		}
		
		$idBelow = $bindedValues[1];
		
		// Controllo che non sia una di quelle tabelle che può avere un duplicato. Ex: attributi_valori
		$tabelleConAliasDuplicato = explode(",", v("tabelle_con_possibile_alias_duplicato"));
		
		if (!in_array($tabella, $tabelleConAliasDuplicato))
		{
			if (!isset($id))
				$res = $this->query(array("select alias from ".$this->_tables." where alias = ? and ".$whereClause,$bindedValues));
			else
			{
				$bindedValues[] = $clean["id"];
				$res = $this->query(array("select alias from ".$this->_tables." where alias = ? and $whereClause and ".$this->_idFields."!=?",$bindedValues));
			}
		}
		else
		{
			$bindedValues[] = $tabella;
			$res = $this->query(array("select alias from ".$this->_tables." where alias = ? and ".$whereClause." and sezione != ?",$bindedValues));
		}
		
		$this->addTokenAlias($res);
		
		if (isset($id))
		{
			$arrayUnion = $bindedValues = array();
			
			foreach (GenericModel::$tabelleConAlias as $table)
			{
				if ($table == $tabella)
				{
					$bindedValues[] = $idBelow;
					$bindedValues[] = sanitizeDb($this->values["alias"]);
					$arrayUnion[] = "select alias from $table where $whereClause and alias = ?";
				}
				else
				{
					$bindedValues[] = sanitizeDb($this->values["alias"]);
					$arrayUnion[] = "select alias from $table where alias = ?";
				}
			}
			
			$sql = implode(" UNION ", $arrayUnion);
			
			$res = $this->query(array($sql, $bindedValues));
			
			$this->addTokenAlias($res);
		}
	}
	
	public function sAlias($record, $id = null)
	{
		if ($record["id_page"] || $record["id_c"] || $record["id_marchio"] || $record["id_tag"] || $record["id_car"] || $record["id_cv"] || $record["id_fascia_prezzo"] || $record["id_av"])
			$this->alias($id);
	}
	
	public function update($id = NULL, $whereClause = NULL)
	{
		$record = $this->selectId((int)$id);
		
		$this->sAlias($record, $id);
		
		$res = parent::update($id, $whereClause);
		
		if ($res && $record["id_av"])
		{
			$c = new CombinazioniModel();
			
			$c->aggiornaAlias(0,0,$record["id_av"]);
		}
		
		return $res;
	}
	
	public function insert()
	{
		$this->sAlias($this->values);
		
		return parent::insert();
	}
	
	public static function getTraduzioni($field, $id, $fields = null)
	{
		$ct = new ContenutitradottiModel();
		
		$ct->clear()->where(array(
			$field	=>	(int)$id
		));
		
		if ($fields)
			$ct->select($fields);
		
		return $ct->send(false);
	}
}
