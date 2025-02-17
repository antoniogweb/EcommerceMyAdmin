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

class RegusersgroupstempController extends BaseController
{
// 	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "ecommerce";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		$this->tabella = gtext("gruppi da approvare",true);
	}

	public function main()
	{
		$this->shift();
		
		$this->queryActions = "del";
		$this->mainButtons = "ldel";
// 		$this->addBulkActions = false;
		
// 		$this->colProperties = array();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>'');
		
		$this->mainFields = array("edit","nome","gruppidaapprovare","approvacrud","approvasoloaccountcrud");
		$this->mainHead = "Email,Nome,Permessi da approvare,Approva tutto,Approva solo attivazione account";
// 		$this->filters = array(array("attivo",null,$this->filtroAttivo),"cerca");
		
		$this->bulkQueryActions = "approvagruppi,approvasoloaccountgruppi";
		
		$this->bulkActions = array(
			"checkbox_regusers_groups_temp_id_ugt"	=>	array("approvagruppi","APPROVA TUTTO"),
			"+checkbox_regusers_groups_temp_id_ugt"	=>	array("approvasoloaccountgruppi","APPROVA SOLO ACCOUNT"),
		);
		
		$this->m[$this->modelName]->clear()->select("*")->inner(array("cliente"))->groupBy("regusers_groups_temp.id_user")->orderBy("regusers.id_user")->convert()->save();
		
		parent::main();
	}
}
