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

class PagesregioniModel extends GenericModel {

	public function __construct() {
		$this->_tables='pages_regioni';
		$this->_idFields='id_page_regione';
		
		$this->_idOrder='id_order';
		
		$this->_lang = 'It';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'page' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
			'regione' => array("BELONGS_TO", 'RegioniModel', 'id_regione',null,"CASCADE"),
			'nazione' => array("BELONGS_TO", 'NazioniModel', 'id_nazione',null,"CASCADE"),
        );
    }
    
    public function pInsert()
    {
		if (isset($this->values["id_nazione"]))
		{
			$n = new NazioniModel();
			
			$this->values["alias_nazione"] = $n->clear()->where(array(
				"id_nazione"	=>	(int)$this->values["id_nazione"],
			))->field("iso_country_code");
		}
		
		if (isset($this->values["id_regione"]))
		{
			$r = new RegioniModel();
			
			$this->values["alias_regione"] = $r->clear()->where(array(
				"id_regione"	=>	(int)$this->values["id_regione"],
			))->field("alias");
		}
		
		return parent::pInsert();
    }
    
    public function filtriNazioni()
    {
		return $this->clear()->select("nazioni.*")->left(array("nazione"))->inner(array("page"))->addWhereAttivo()->groupBy("nazioni.id_nazione")->orderBy("nazioni.titolo")->send();
    }
	
	public function filtriRegioni()
    {
		$this->clear()->select("regioni.*")->inner(array("regione"))->inner(array("page"))->addWhereAttivo()->groupBy("regioni.id_regione")->orderBy("regioni.titolo");
		
		$valoriNazione = RegioniModel::getValoriCaratteristica(RegioniModel::$nAlias);
		
		if (count($valoriNazione) > 0)
		{
			$aWhere = array(
				"in"	=>	array(
					"regioni.nazione"	=>	$valoriNazione,
				),
			);
			
			$this->aWhere($aWhere);
		}
		
		return $this->send();
    }
}
