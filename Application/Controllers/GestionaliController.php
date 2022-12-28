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

class GestionaliController extends BaseController
{
	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "utenti";
	
	public $tabella = "integrazioni con gestionali";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		if (!v("attiva_collegamento_gestionali"))
			$this->responseCode(403);
	}

	public function main()
	{
		$this->shift();
		
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "ledit";
		$this->addBulkActions = false;
		
		$this->colProperties = array();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>'');
		
		$this->mainFields = array("edit","attivo");
		$this->mainHead = "Titolo,Attivo";
		
		$this->m[$this->modelName]->clear()->orderBy("id_order")->convert()->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		if ($queryType != "update")
			die();
		
		$this->_posizioni['main'] = 'class="active"';
		
		$record = $this->m[$this->modelName]->selectId((int)$id);
		
		if (empty($record))
			die();
		
		$fields = GestionaliModel::getModulo($record["codice"])->gCampiForm();
		
		$data["fields"] = explode(",", $fields);
		$data["record"] = $record;
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
		
		$this->append($data);
	}
	
	public function infoaccount()
	{
		$this->clean();
		
		if (GestionaliModel::getModulo()->isAttiva())
		{
			$gestionale = $this->m("GestionaliModel")->where(array(
				"attivo"	=>	1,
			))->record();
			
			if (!empty($gestionale))
			{
				$jsonAccount = GestionaliModel::getModulo()->info();
				
				if (trim($jsonAccount))
				{
					$this->m("GestionaliModel")->sValues(array(
						"info_account"	=>	$jsonAccount,
					));
					
					$this->m("GestionaliModel")->update($gestionale["id_gestionale"]);
				}
			}
		}
	}
	
	public function infocontidisaldo()
	{
		$this->clean();
		
		if (GestionaliModel::getModulo()->isAttiva())
		{
			$json = GestionaliModel::getModulo()->infoContiDiSaldo();
			
			echo $json;
		}
	}
	
	public function invia($elemento = "ordine", $id_elemento = 0)
	{
		$this->clean();
		
		GestionaliModel::invia($elemento, $id_elemento);
	}
	
	protected function pMain()
	{
		parent::main();
	}
}
