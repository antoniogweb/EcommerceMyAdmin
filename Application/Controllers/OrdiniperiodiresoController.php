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

class OrdiniperiodiresoController extends BaseController
{
	public $argKeys = array(
		'id_o:sanitizeAll'=>'tutti',
		'id_lista_insert:sanitizeAll'=>'tutti',
	);
	
	public $sezionePannello = "ecommerce";
	
	public $tabella = "periodi di reso";
	
	public function main()
	{
		$this->shift();
		
		$this->addBulkActions = false;
		
		$this->mainButtons = 'ldel';
		$this->mainFields = array("orders_periodi_reso.id_o");
		$this->mainHead = "Ordine";
		
		$this->m[$this->modelName]->clear()->where(array(
			"richiesta"	=>	1,
		))->orderBy("id_order")->convert()->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$fields = 'data_inizio';
		
		if ($queryType == "update")
		{
			$record = $this->m[$this->modelName]->selectId((int)$id);
			
			if (!empty($record) && $record["richiesta"])
			{
				$_GET["report"] = "Y";
				
				$this->formQueryActions = "";
				
				$fields = 'data_inizio,data_fine,data_richiesta';
			}
		}
		else
		{
			$this->formDefaultValues = array(
				"data_inizio"	=>	date("d-m-Y"),
			);
		}
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		$this->m[$this->modelName]->setValue("manuale", 1);
		
		if ($queryType == "insert" && $this->viewArgs["id_o"] != "tutti")
			$this->m[$this->modelName]->setValue("id_o", $this->viewArgs["id_o"]);
		
		if ($queryType == "insert" && $this->viewArgs["id_lista_insert"] != "tutti")
			$this->m[$this->modelName]->setValue("id_lista_regalo", $this->viewArgs["id_lista_insert"]);
		
		parent::form($queryType, $id);
	}
}
