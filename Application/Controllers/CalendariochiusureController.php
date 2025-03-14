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

class CalendariochiusureController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "utenti";
	
	public $tabella = "giorni chiusura";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_calendario_chiusure"))
			$this->responseCode(403);
	}

	public function main()
	{
		$this->shift();
		
		if (isset($_POST["aggiungi"]))
		{
			$dal = $this->request->post("dal", "");
			$al = $this->request->post("al", "");
			
			$this->m("CalendariochiusureModel")->aggiungiDate($dal, $al);
			
			if ($this->m("CalendariochiusureModel")->errore)
				flash("notice","<div class='alert alert-danger'>".$this->m("CalendariochiusureModel")->errore."</div>");
			
			$this->redirect($this->applicationUrl.$this->controller."/main".$this->viewStatus);
		}
		
		$this->mainButtons = 'ldel';
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000, 'mainMenu'=>'');
		
		$this->mainFields = array("smartDate|calendario_chiusure.data_chiusura");
		$this->mainHead = "Data chiusura";
		
		$this->m[$this->modelName]->clear()->where(array(
			"gte"	=>	array(
				"data_chiusura"	=>	date("Y-m-d"),
			)
		))->orderBy("id_order")->orderBy("data_chiusura")->convert()->save();
		
		parent::main();
	}
}
