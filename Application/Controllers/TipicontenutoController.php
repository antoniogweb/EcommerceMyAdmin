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

class TipicontenutoController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $orderBy = "id_order";
	
	public $tabella = "tipologie fasce";
	
	public $sezionePannello = "utenti";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		$this->argKeys = array(
			'titolo:sanitizeAll'=>'tutti',
			'tipo:sanitizeAll'=>'tutti',
			'id_group:sanitizeAll'=>'tutti',
		);
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
	}

	public function main()
	{
		$this->shift();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>100, 'mainMenu'=>'add');
		$this->mainFields = array("tipi_contenuto.titolo", "gtext|tipi_contenuto.tipo", "tipi_contenuto.section");
		$this->mainHead = "Titolo,Tipo,Sezione";
		$this->filters = array(null, "titolo", array("tipo",null,array("tutti"=>gtext("Tipo")) + gtextDeep(TipicontenutoModel::$tipi)));
		
		$this->m[$this->modelName]->clear()
				->where(array(
					"lk" => array('titolo' => $this->viewArgs['titolo']),
					"tipo"	=>	$this->viewArgs['tipo'],
				))
				->orderBy("id_order");
		
		if ($this->viewArgs["id_group"] != "tutti")
		{
			if (!v("attiva_reggroups_tipi"))
				die();
			
			$this->mainButtons = "";
			
			$this->bulkQueryActions = "aggiungiagruppo";
			
			$this->bulkActions = array(
				"checkbox_tipi_contenuto_id_tipo"	=>	array("aggiungiagruppo","Aggiungi al gruppo"),
			);
			
			$this->m[$this->modelName]->sWhere(array("tipi_contenuto.id_tipo not in (select id_tipo from reggroups_tipi where tipo='CO' and id_group = ?)",array((int)$this->viewArgs["id_group"])));
		}
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$this->m[$this->modelName]->setValuesFromPost('titolo,tipo,section,campi,descrizione');
		
		parent::form($queryType, $id);
	}
	
	public function ordina()
	{
		parent::ordina();
	}
}
