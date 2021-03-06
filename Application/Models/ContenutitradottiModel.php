<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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
    
    public function setFormStruct()
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
			$this->values["alias"] = sanitizeDb(encodeUrl($this->values["title"]));
		}
		
		if (!isset($id))
		{
			$idPage = isset($this->values["id_page"]) ? $this->values["id_page"] : 0;
			$idC = isset($this->values["id_c"]) ? $this->values["id_c"] :0;
			
			$whereClause = $idPage ? "id_page != ".(int)$idPage : "id_c != ".(int)$idC;
			
			$res = $this->query("select alias from ".$this->_tables." where alias = '".$this->values["alias"]."' and ".$whereClause);
		}
		else
		{
			$idPage = $record["id_page"];
			$idC = $record["id_c"];
			
			$whereClause = $idPage ? "id_page != ".(int)$idPage : "id_c != ".(int)$idC;
			
			$res = $this->query("select alias from ".$this->_tables." where alias = '".$this->values["alias"]."' and $whereClause and ".$this->_idFields."!=".$clean["id"]);
			
// 			echo $this->getQUery();die();
		}
		
		if (count($res) > 0)
		{
			$this->values["alias"] = $this->values["alias"] . "-" . generateString(4,"123456789");
		}
		else
		{
			$idPage = isset($this->values["id_page"]) ? $this->values["id_page"] : $record["id_page"];
			$idC = isset($this->values["id_c"]) ? $this->values["id_c"] : $record["id_c"];
			
			$res = $this->query("select alias from categories where alias = '".$this->values["alias"]."' and categories.id_c != '".(int)$idC."' union select alias from pages where alias = '".$this->values["alias"]."' and pages.id_page != '".(int)$idPage."'");
			
			if (count($res) > 0)
			{
				$this->values["alias"] = $this->values["alias"] . "-".generateString(4,"123456789");
			}
		}
	}
	
	public function sAlias($record, $id = null)
	{
		if ($record["id_marchio"] || $record["id_tag"])
		{
			if (!$this->values["alias"])
				$this->values["alias"] = sanitizeDb(encodeUrl($this->values["titolo"]));
		}
		else if ($record["id_page"] || $record["id_c"])
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
