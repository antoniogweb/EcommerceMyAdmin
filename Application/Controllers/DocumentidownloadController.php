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

Helper_List::$filtersFormLayout["filters"]["titolo"]["attributes"]["placeholder"] = "Titolo documento ..";

Helper_List::$filtersFormLayout["filters"]["utente"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"Utente ..",
	),
);

class DocumentidownloadController extends BaseController {
	
	public $filters = array("titolo","utente");
	
	public $argKeys = array('titolo:sanitizeAll'=>'tutti', 'utente:sanitizeAll'=>'tutti');
	
	public $useEditor = true;
	
	public $sezionePannello = "ecommerce";
	
	public $tabella = "statistiche download documenti";
	
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_sezione_download_documenti"))
			$this->responseCode(403);
	}
	
	public function main()
	{
		$this->shift();
		
		$this->addBulkActions = false;
		$this->colProperties = array();
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		
		$this->mainFields = array("smartDate|documenti_download.data_creazione","cleanDateTimeDocumento","documenti.titolo","utenteCrud","filename", "numeroCrud");
		$this->mainHead = "Data scaricamento,Data ora caricamento,Titolo,Utente,File,Numero scaricamenti";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>'');
		
		$this->m[$this->modelName]->select("documenti_download.*,documenti.*,regusers.*,count(*) as numero_scaricamenti")->inner(array("documento"))->left(array("user"))->aWhere(array(
				"lk" => array("documenti.titolo" => $this->viewArgs["titolo"]),
			))
			->groupBy('date_format(documenti_download.data_creazione,"%Y-%m-%d"),documenti_download.id_doc,documenti_download.id_user')
			->orderBy('date_format(documenti_download.data_creazione,"%Y-%m-%d") desc')->convert();
		
		if ($this->viewArgs["utente"] != "tutti")
		{
			$this->m[$this->modelName]->aWhere(array(
				"    AND"	=>	RegusersModel::getWhereClauseRicercaLibera($this->viewArgs['utente'], "regusers."),
			));
		}
		
		$this->getTabViewFields("main");
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
}
