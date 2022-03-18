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
		
		$record = $this->selectId($clean["id"]);
		
		if (!isset($this->values["alias"]) || !trim($this->values["alias"]))
		{
			if (isset($this->values["title"]))
				$this->values["alias"] = sanitizeDb(encodeUrl($this->values["title"]));
			else if (isset($this->values["titolo"]))
				$this->values["alias"] = sanitizeDb(encodeUrl($this->values["titolo"]));
		}
		
		if (!isset($id))
		{
			$idPage = isset($this->values["id_page"]) ? $this->values["id_page"] : 0;
			$idC = isset($this->values["id_c"]) ? $this->values["id_c"] :0;
			$idMarchio = isset($this->values["id_marchio"]) ? $this->values["id_marchio"] : 0;
			$idTag = isset($this->values["id_tag"]) ? $this->values["id_tag"] : 0;
			$idCar = isset($this->values["id_car"]) ? $this->values["id_car"] : 0;
			$idCv = isset($this->values["id_cv"]) ? $this->values["id_cv"] : 0;
			$idFascia = isset($this->values["id_fascia_prezzo"]) ? $this->values["id_fascia_prezzo"] : 0;
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
		}
		
		if ($idPage)
		{
			$whereClause = "id_page != ".(int)$idPage;
			$tabella = "pages";
		}
		else if ($idC)
		{
			$whereClause = "id_c != ".(int)$idC;
			$tabella = "categories";
		}
		else if ($idMarchio)
		{
			$whereClause = "id_marchio != ".(int)$idMarchio;
			$tabella = "marchi";
		}
		else if ($idTag)
		{
			$whereClause = "id_tag != ".(int)$idTag;
			$tabella = "tag";
		}
		else if ($idCar)
		{
			$whereClause = "id_car != ".(int)$idCar;
			$tabella = "caratteristiche";
		}
		else if ($idCv)
		{
			$whereClause = "id_cv != ".(int)$idCv;
			$tabella = "caratteristiche_valori";
		}
		else if ($idFascia)
		{
			$whereClause = "id_fascia_prezzo != ".(int)$idFascia;
			$tabella = "fasce_prezzo";
		}
		
		if (!isset($id))
			$res = $this->query("select alias from ".$this->_tables." where alias = '".$this->values["alias"]."' and ".$whereClause);
		else
			$res = $this->query("select alias from ".$this->_tables." where alias = '".$this->values["alias"]."' and $whereClause and ".$this->_idFields."!=".$clean["id"]);
		
		$this->addTokenAlias($res);
		
		if (isset($id))
		{
			$arrayUnion = array();
			
			foreach (GenericModel::$tabelleConAlias as $table)
			{
				if ($table == $tabella)
					$arrayUnion[] = "select alias from $table where $whereClause and alias = '".sanitizeDb($this->values["alias"])."'";
				else
					$arrayUnion[] = "select alias from $table where alias = '".sanitizeDb($this->values["alias"])."'";
			}
			
			$sql = implode(" UNION ", $arrayUnion);
			
			$res = $this->query($sql);
			
			$this->addTokenAlias($res);
		}
	}
	
	public function sAlias($record, $id = null)
	{
		if ($record["id_page"] || $record["id_c"] || $record["id_marchio"] || $record["id_tag"] || $record["id_car"] || $record["id_cv"] || $record["id_fascia_prezzo"])
			$this->alias($id);
	}
	
	public function update($id = NULL, $whereClause = NULL)
	{
		$record = $this->selectId((int)$id);
		
		$this->sAlias($record, $id);
		
		return parent::update($id, $whereClause);
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
