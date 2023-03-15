<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
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

class RicerchesinonimiController extends BaseController
{
	public $tabella = "Sinonimi";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array('titolo:sanitizeAll'=>'tutti');
	
	public $sezionePannello = "utenti";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		if (!v("attiva_gestione_motori_ricerca"))
			$this->responseCode(403);
	}

	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("ricerche_sinonimi.titolo", "ricerche_sinonimi.sinonimi");
		$this->mainHead = "Titolo,Sinonimi";
		$this->filters = array("titolo");
		
		$this->m[$this->modelName]->clear()
				->where(array(
					"OR"	=>	array(
						"lk" => array('titolo' => $this->viewArgs['titolo']),
						" lk" => array('sinonimi' => $this->viewArgs['titolo']),
					),
				))
				->orderBy("titolo")->convert()->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$this->m[$this->modelName]->setValuesFromPost('titolo,sinonimi');
		
		parent::form($queryType, $id);
	}
}
