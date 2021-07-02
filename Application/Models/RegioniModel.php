<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
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

class RegioniModel extends GenericModel {

	public function __construct() {
		$this->_tables='regioni';
		$this->_idFields='id_regione';
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'nazione' => array("BELONGS_TO", 'NazioniModel', 'id_nazione',null,"CASCADE"),
			'pagine' => array("HAS_MANY", 'PagesregioniModel', 'id_regione', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
        );
    }
    
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'nazione'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectNazione(true),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'id_nazione'	=>	array(
					"type"	=>	"Select",
					'labelString'=>	'Nazione',
					"options"	=>	$this->selectNazioneId(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
		);
	}
	
	public function tipo($record)
	{
		if ($record["regioni"]["tipo"] == "CUS")
			return gtext("Custom");
		
		return gtext("Di sistema");
	}
	
	public function impostaCodiceNazione()
	{
		if (isset($this->values["id_nazione"]))
		{
			$n = new NazioniModel();
			
			$record = $n->selectId((int)$this->values["id_nazione"]);
			
			if (!empty($record))
				$this->values["nazione"] = $record["iso_country_code"];
		}
	}
	
	public function update($id = NULL, $whereClause = NULL)
	{
		if (isset($this->values["alias"]))
			$this->checkAliasAll($id, true);
		
		return parent::update($id, $whereClause);
	}
	
	public function insert()
	{
		$this->impostaCodiceNazione();
		
		if (isset($this->values["alias"]))
			$this->checkAliasAll(0, true);
			
		$this->values["tipo"] = "CUS";
		
		$res = parent::insert();
		
		if ($res)
		{
			// Aggiungo direttamente dal prodotto
			if ($_GET["id_page"] && isset($this->values["id_nazione"]))
				$this->aggiungiaprodotto($this->lId);
		}
		
		return $res;
	}
	
	public function edit($record)
	{
		return "<a class='iframe action_iframe' href='".Url::getRoot()."/regioni/form/update/".$record["regioni"]["id_regione"]."?partial=Y&nobuttons=Y'>".$record["regioni"]["titolo"]."</a>";
	}
	
    public function aggiungiaprodotto($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && isset($_GET["id_page"]))
		{
			$pr = new PagesregioniModel();
			
			$pr->setValues(array(
				"id_page"		=>	(int)$_GET["id_page"],
				"id_regione"	=>	(int)$id,
				"id_nazione"	=>	$record["id_nazione"],
			), "sanitizeDb");
			
			$pr->pInsert();
		}
    }
}
