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

class SpedizioninegozioController extends BaseController {
	
	public $argKeys = array('id_o:sanitizeAll'=>'tutti');
	
	public $sezionePannello = "ecommerce";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		if (!v("attiva_gestione_spedizioni"))
			$this->responseCode(403);
		
		$this->tabella = gtext("spedizioni negozio",true);
	}
	
	public function main()
	{
		$this->shift();
		
		$this->addBulkActions = false;
		$this->colProperties = array();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>"");
		
		$this->mainFields = array("spedizioni_negozio.id_spedizione_negozio", "cleanDateTime", "spedizionieri.titolo");
		$this->mainHead = "ID,Data spedizione,Spedizioniere";
		
		$this->m[$this->modelName]->clear()
				->select("*")
				->left(array("spedizioniere"))
				->where(array(
					
				))
				->orderBy("data_spedizione desc")->convert()->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
// 		$this->m[$this->modelName]->addStrongCondition("both",'checkIsNotStrings|0',"id_spedizioniere|".gtext("Si prega di selezionare lo spedizioniere"));
		
		if ($queryType == "insert")
		{
			if ($this->viewArgs["id_o"] == "tutti" || !OrdiniModel::g(false)->whereId((int)$this->viewArgs["id_o"])->rowNumber())
				$this->responseCode(403);
			
			$fields = "data_spedizione,id_spedizioniere";
		}
		else
			$fields = "data_spedizione,id_spedizioniere";
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
	}
}
