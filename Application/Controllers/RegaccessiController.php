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

// Helper_List::$filtersFormLayout["filters"]["titolo"]["attributes"]["placeholder"] = "Titolo documento ..";

Helper_List::$filtersFormLayout["filters"]["utente"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"Utente ..",
	),
);

class RegaccessiController extends BaseController {
	
	public $filters = array("dal","al","utente");
	
	public $argKeys = array(
		'dal:sanitizeAll'=>'tutti',
		'al:sanitizeAll'=>'tutti',
		'utente:sanitizeAll'=>'tutti',
	);
	
	public $sezionePannello = "utenti";
	
	public $tabella = "statistiche download documenti";
	
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_sezione_accessi_utenti"))
			$this->responseCode(403);
	}
	
	public function main()
	{
		$this->shift();
		
		$this->addBulkActions = false;
		$this->colProperties = array();
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		
		$this->mainFields = array("cleanDateAccessi","regaccesses.username","aggregate.numero_accessi");
		$this->mainHead = "Data,Email,Numero accessi";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>300, 'mainMenu'=>'esporta_xls');
		
		$this->m[$this->modelName]->select("regaccesses.*,count(*) as numero_accessi")->aWhere(array(
				"lk" => array("regaccesses.username" => $this->viewArgs["utente"]),
			))
			->groupBy('date_format(regaccesses.data_creazione,"%Y-%m-%d"),regaccesses.username')
			->orderBy('date_format(regaccesses.data_creazione,"%Y-%m-%d") desc')->convert();
		
		$this->m[$this->modelName]->setDalAlWhereClause($this->viewArgs['dal'], $this->viewArgs['al']);
		
		$this->getTabViewFields("main");
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
}
