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

class IntegrazioniController extends BaseController
{
	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "utenti";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		if (!v("attiva_gestione_integrazioni"))
			die();
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
		
		$fields = 'titolo,attivo,secret_1,secret_2,api_endpoint';
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
	}
	
	public function invia($tipo = "ORDINE", $idelemento = 0)
	{
		$this->clean();
		
		$this->model("IntegrazionisezioniModel");
		
		$res = $this->m["IntegrazionisezioniModel"]->clear()->select("*")->inner(array("integrazione"))->where(array(
			"sezione"	=>	$tipo,
			"integrazioni.attivo"	=>	1,
		))->findAll();
		
		if (count($res) > 0)
		{
			$integrazione = $res[0]["integrazioni"];
			$integrazioneSezione = $res[0]["integrazioni_sezioni"];
			
// 			print_r($integrazione);
			$i = IntegrazioniModel::getModulo($integrazioneSezione["id_integrazione"]);
			
			if (call_user_func(array($i, "configurato"), $integrazione))
			{
				$result = call_user_func(array($i, $integrazioneSezione["metodo"]), (int)$idelemento);
			
				print_r($result);
			}
		}
	}
}
