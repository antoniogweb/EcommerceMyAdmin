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

Helper_List::$filtersFormLayout["filters"]["parola_tradotta_da_correggere"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"Testo da tradurre..",
	),
);

class TraduzionicorrezioniController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array(
		'parola_tradotta_da_correggere:sanitizeAll'=>'tutti',
		'lingua:sanitizeAll'=>'tutti'
	);
	
	public $sezionePannello = "utenti";
	
	public $tabella = "correzioni alle traduzioni";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_gestione_traduttori"))
			$this->responseCode(403);
		
		$this->filters = array("parola_tradotta_da_correggere",array("lingua",null,array("tutti"	=>	gtext("Lingua")) + LingueModel::getSelectLingueNonPrincipali()));
	}

	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("traduzioni_correzioni.parola_tradotta_da_correggere", "traduzioni_correzioni.parola_tradotta_corretta", "tipoCrud", "LingueModel::getTitoloDaCodice|traduzioni_correzioni.lingua");
		$this->mainHead = "Testo da tradurre,Testo tradotto,Tipo,Lingua";
		
		$this->m[$this->modelName]->clear()->where(array(
				"lk" => array("parola_tradotta_da_correggere" => $this->viewArgs["parola_tradotta_da_correggere"]),
				"lingua"	=>	$this->viewArgs["lingua"],
				"in"	=>	array(
					"successivo"	=>	array(0,2),
				)
			))->orderBy("lingua,parola_tradotta_da_correggere")->convert()->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$fields = 'parola_tradotta_da_correggere,parola_tradotta_corretta,successivo,lingua';
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
	}
}
