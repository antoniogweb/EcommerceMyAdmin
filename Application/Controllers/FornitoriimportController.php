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

class FornitoriimportController extends BaseController
{
	// public $filters = array("ragione_sociale");
	
	// public $orderBy = "ragione_sociale";
	
	public $argKeys = array(
		'id_fornitore_insert:sanitizeAll'=>'tutti'
	);
	
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
		$this->shift(2);
		
		$this->_posizioni['main'] = 'class="active"';
		
		$fields = 'filename';
		
		if ($queryType == "update")
			$fields .= ",foglio,colonna_descrizione,colonna_codice_sku,colonna_codice_ean_gtin,colonna_codice_mpn_barcode";
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if ($queryType == "insert" && $this->viewArgs["id_fornitore_insert"] != "tutti")
			$this->m[$this->modelName]->setValue("id_fornitore", (int)$this->viewArgs["id_fornitore_insert"]);
		
		parent::form($queryType, $id);
	}
	
	public function documento($field = "", $id = 0)
	{
		parent::documento($field, $id);
	}
}
