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

class RegioniController extends BaseController {
	
	public $orderBy = "id_order desc";
	
	public $argKeys = array(
		'id_nazione:sanitizeAll'	=>	'tutti',
		'id_page:sanitizeAll'		=>	'tutti',
		'titolo:sanitizeAll'		=>	'tutti',
	);
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("regioni.titolo");
		$this->mainHead = "Regione";
		
		$this->m[$this->modelName]->clear()->select("*")
				->inner(array("nazione"))
				->where(array(
					"lk" => array('titolo' => $this->viewArgs['titolo']),
					"id_nazione"	=>	$this->viewArgs['id_nazione'],
				))
				->orderBy("regioni.titolo");
		
		if ($this->viewArgs["id_page"] != "tutti")
		{
			$filtroNazione = array("tutti" => "Nazione") + $this->m[$this->modelName]->selectNazioneId();
			
			$this->filters = array(array("id_nazione", null, $filtroNazione), "titolo");
			
			array_unshift($this->mainFields, "nazioni.titolo");
			$this->mainHead = "Nazioni,".$this->mainHead;
			
			$this->mainButtons = "";
			
			$this->bulkQueryActions = "aggiungiaprodotto";
			
			$this->bulkActions = array(
				"checkbox_regioni_id_regione"	=>	array("aggiungiaprodotto","Aggiungi al prodotto"),
			);
			
			$this->m[$this->modelName]->sWhere(array("regioni.id_regione not in (select id_regione from pages_regioni where id_regione is not null and id_page = ?)",array((int)$this->viewArgs["id_page"])));
		}
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$fields = "titolo,alias";
		
		if ($this->viewArgs["id_page"] != "tutti")
			$fields = "id_nazione,".$fields;
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if ($this->viewArgs["id_nazione"] != "tutti")
			$this->m[$this->modelName]->setValue("id_nazione", $this->viewArgs["id_nazione"]);
		
		parent::form($queryType, $id);
	}
}
