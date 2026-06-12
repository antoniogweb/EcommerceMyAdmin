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

Helper_List::$filtersFormLayout["filters"]["ragione_sociale"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"Cerca..",
	),
);

class FornitoriController extends BaseController
{
	public $filters = array("ragione_sociale");
	
	public $orderBy = "ragione_sociale";
	
	public $argKeys = array('ragione_sociale:sanitizeAll'=>'tutti');
	
	public $useEditor = true;
	
	public $sezionePannello = "acquisti";
	
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_modulo_acquisti"))
			$this->responseCode(403);
	}
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("[[ledit]];fornitori.ragione_sociale;","fornitori.telefono","fornitori.email");
		$this->mainHead = "Ragione sociale,Telefono,Email";
		
		$this->m[$this->modelName]->orderBy($this->orderBy)->convert();
		
		if ($this->viewArgs["ragione_sociale"] != "tutti")
		{
			$this->m[$this->modelName]->aWhere(array(
				"  AND"	=>	FornitoriModel::getWhereClauseRicercaLibera($this->viewArgs['ragione_sociale']),
			));
		}
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$fields = 'ragione_sociale,email,email_amministrativa,pec,codice_fiscale,p_iva,telefono,telefono_2,indirizzo,numero_civico,nazione,provincia,comune,cap,localita,referente,telefono_referente,cellulare_referente,email_referente';
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
	}
}
