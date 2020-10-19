<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
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

class TipicontenutoController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "utenti";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		$this->argKeys = array(
			'titolo:sanitizeAll'=>'tutti',
			'tipo:sanitizeAll'=>'tutti',
		);
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
	}

	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("tipi_contenuto.titolo", "tipi_contenuto.tipo");
		$this->mainHead = "Titolo,Tipo";
		$this->filters = array(null, "titolo", array("tipo",null,array("tutti"=>"Tipo") + TipicontenutoModel::$tipi));
		
		$this->m[$this->modelName]->clear()
				->where(array(
					"lk" => array('titolo' => $this->viewArgs['titolo']),
					"tipo"	=>	$this->viewArgs['tipo'],
				))
				->orderBy("titolo")->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$this->m[$this->modelName]->setValuesFromPost('titolo,tipo,descrizione');
		
		parent::form($queryType, $id);
	}
}
