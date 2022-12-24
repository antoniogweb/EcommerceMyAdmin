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

class ControllersController extends BaseController
{
	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array(
		'id_group:sanitizeAll'=>'tutti',
	);
	
	public $sezionePannello = "ecommerce";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		if (!v("attiva_gruppi_admin"))
			$this->responseCode(403);
	}

	public function main()
	{
		$this->m[$this->modelName]->sistemaVisibilita();
		
		$this->shift();
		
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		
		if ($this->viewArgs["id_group"] == "tutti")
		{
			$this->addBulkActions = false;
			$this->colProperties = array();
		}
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>200, 'mainMenu'=>"");
		
		$this->mainFields = array("controllers.titolo", "controllers.codice", "controllers.pannello");
		$this->mainHead = "Titolo,Codice,Sezione";
		
		if ($this->viewArgs["id_group"] != "tutti")
		{
			$this->mainFields[] = "bulkaggiungiagruppo";
			$this->mainHead .= ",Aggiungi";
		}
		
		$this->m[$this->modelName]->clear()->where(array(
			"visibile"	=>	1,
			"codice_padre"	=>	"",
		))->orderBy("pannello,id_order")->convert();
		
		if ($this->viewArgs["id_group"] != "tutti")
		{
			$this->mainButtons = "";
			
			$this->bulkQueryActions = "aggiungiagruppo";
			
			$this->bulkActions = array(
				"checkbox_controllers_id_controller"	=>	array("aggiungiagruppo","Aggiungi al gruppo"),
			);
			
			$this->m[$this->modelName]->sWhere(array("controllers.id_controller not in (select id_controller from admingroups_controllers where id_controller is not null and id_group = ? )",array((int)$this->viewArgs["id_group"])));
		}
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
}
