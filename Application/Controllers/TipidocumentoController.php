<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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

class TipidocumentoController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "utenti";
	
	public $tabella = "tipi documenti";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
	}

	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("tipi_documento.titolo");
		$this->mainHead = "Titolo";
		
		if (v("riconoscimento_tipo_documento_automatico"))
		{
			$this->mainFields[] = "tipi_documento.estensione";
			$this->mainHead .= ",Estensione";
		}
		
		$this->m[$this->modelName]->clear()
				->where(array(
// 					"lk" => array('titolo' => $this->viewArgs['cerca']),
				))
				->orderBy("titolo")->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$fields = "titolo";
		
		if (v("riconoscimento_tipo_documento_automatico"))
			$fields .= ",estensione";
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
	}
}
