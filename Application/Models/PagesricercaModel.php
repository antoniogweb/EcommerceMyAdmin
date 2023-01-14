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

class PagesricercaModel extends GenericModel {

	public function __construct() {
		$this->_tables='pages_ricerca';
		$this->_idFields='id_page_ricerca';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'page' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
        );
    }
    
    public static function inserisci($idPage, $values, $lingua = "it")
    {
		$pRicercaModel = new PagesricercaModel();
		
		$pRicercaModel->del(null, array(
			"id_page"	=>	(int)$idPage,
		));
		
		if (v("usa_transactions"))
			$pRicercaModel->db->beginTransaction();
		
		foreach ($values as $key => $value)
		{
			$pRicercaModel->sValues(array(
				"titolo"	=>	$key,
				"valore"	=>	$value,
				"lingua"	=>	$lingua,
				"id_page"	=>	$idPage,
			));
			
			$pRicercaModel->insert();
		}
		
		if (v("usa_transactions"))
			$pRicercaModel->db->commit();
    }
    
    public function getStructFromIdsOfPages($ids)
    {
		$res = $this->clear()->where(array(
			"in"	=>	array(
				"id_page"	=>	forceIntDeep($ids),
			),
		))->orderBy("id_page,id_order")->send(false);
		
		$struttura = array();
		
		foreach ($res as $r)
		{
			$struttura[$r["id_page"]][$r["titolo"]] = $r["valore"];
		}
		
		return $struttura;
    }
}
