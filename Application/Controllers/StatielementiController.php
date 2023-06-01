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

class StatielementiController extends BaseController {
	
	public $orderBy = "id_stato_elemento";
	
	public $tabella = "stato elemento";
	
	public $argKeys = array(
		'tabella:sanitizeAll'=>'tutti',
		'id_tabella:sanitizeAll'=>'tutti',
	);
	
	public $useEditor = true;
	
	public $sezionePannello = "ecommerce";
	
	public function main()
	{
		$this->shift();
		
		$this->queryActions = $this->bulkQueryActions = "";
		$this->addBulkActions = false;
		$this->colProperties = array();
		
		$this->mainFields = array("labelStatoCrud", "adminusers.username", "cleanDateTime");
		$this->mainHead = "Stato,Impostato da,Data ora";
		
		$this->m[$this->modelName]->select("*")->inner(array("utente"))->orderBy($this->orderBy)->convert();
		
		if (in_array($this->viewArgs["tabella"], StatielementiModel::$elencoTabellePermesse) && $this->viewArgs["id_tabella"] != "tutti")
		{
			$this->m[$this->modelName]->aWhere(array(
				"tabella_rif"	=>	$this->viewArgs["tabella"],
				"id_rif"		=>	$this->viewArgs["id_tabella"],
			));
		}
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$fields = 'id_stato';
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if ($queryType == "insert")
		{
			if (in_array($this->viewArgs["tabella"], StatielementiModel::$elencoTabellePermesse) && $this->viewArgs["id_tabella"] != "tutti")
			{
				$this->m[$this->modelName]->setValue("tabella_rif", $this->viewArgs["tabella"]);
				$this->m[$this->modelName]->setValue("id_rif", (int)$this->viewArgs["id_tabella"]);
			}
		}
		
		parent::form($queryType, $id);
	}
}
