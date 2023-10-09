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

class StatiordineController extends BaseController
{
	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "ecommerce";
	
	public $tabella = "stati ordine";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		if (!v("attiva_gestione_stati_ordine"))
			die();
	}

	public function main()
	{
		$this->shift();
		
		$this->addBulkActions = false;
		
		$this->colProperties = array();
		
		$mainMenu = "add";
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>$mainMenu);
		
		$this->mainFields = array("edit","stati_ordine.codice", "pagatoCrud");
		$this->mainHead = "Titolo,Codice,Pagato";
		
		if (v("attiva_gestione_spedizioni"))
		{
			$this->mainFields[] = 'daSpedireCrud';
			$this->mainFields[] = 'inSpedizioneCrud';
			$this->mainFields[] = 'speditoCrud';
			
			$this->mainHead .= ',Da spedire';
			$this->mainHead .= ',In spedizione';
			$this->mainHead .= ',Spedito';
		}
		
		$this->m[$this->modelName]->clear()->orderBy("id_order")->convert()->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$fields = 'titolo,classe,codice,pagato,manda_mail_al_cambio_stato,descrizione';
		
		if (v("attiva_gestione_spedizioni"))
			$fields .= ",da_spedire,in_spedizione,spedito";
		
		$record = $data["record"] = $this->m[$this->modelName]->selectId((int)$id);
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if ($queryType == "insert")
			$this->m[$this->modelName]->setValue("tipo", "U");
		
		if (isset($record["tipo"]) && $record["tipo"] == "S")
		{
			$this->disabledFields = "codice";
			$this->m[$this->modelName]->delFields("codice");
		}
		else
		{
			$this->m[$this->modelName]->addStrongCondition("both",'checkNotEmpty',"codice");
			$this->m[$this->modelName]->addDatabaseCondition("both",'checkUnique',"codice");
		}
		
		parent::form($queryType, $id);
		
		$this->append($data);
	}
	
	public function ordina()
	{
		$this->orderBy = "id_order";
		
		parent::ordina();
	}
}
