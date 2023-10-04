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

class SpedizionieriletterevetturaController extends BaseController
{
	public $argKeys = array(
		'id_spedizioniere:sanitizeAll'	=>	'tutti',
	);
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);

		if (!v("attiva_gestione_spedizionieri") && !v("attiva_gestione_spedizioni"))
			$this->responseCode(403);
		
		$this->tabella = "template lettera di vettura";
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->menuLinks = $this->menuLinksInsert = "save";
		
		$this->shift(2);
		
		$fields = "titolo,attivo,modulo,filename";

		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if ($queryType == "insert" && $this->viewArgs["id_spedizioniere"] != "tutti")
			$this->m[$this->modelName]->setValue("id_spedizioniere", $this->viewArgs["id_spedizioniere"]);
		
		parent::form($queryType, $id);
	}
	
	public function documento($field = "", $id = 0)
	{
		parent::documento($field, $id);
	}
}
