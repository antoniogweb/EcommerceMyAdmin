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

class IntegrazioniloginController extends BaseController
{
	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "utenti";
	
	public $tabella = "login tramite APP";
	
// 	public $useEditor = true;
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		if (!v("abilita_login_tramite_app"))
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
		
		$record = $this->m[$this->modelName]->selectId((int)$id);
		
		if (empty($record))
			die();
		
		IntegrazioniloginModel::getApp($record["codice"])->resetSessionVariables();
		
		$fields = IntegrazioniloginModel::getApp($record["codice"])->gCampiForm();
		
		$fields .= ",colore_background_in_esadecimale,html_icona";
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		$this->menuLinks = "back,save,ottieni_access_token";
		
		parent::form($queryType, $id);
	}
	
	public function ottieniaccesstoken($codice = "")
	{
		$clean["codice"] = sanitizeAll($codice);
		
		$this->clean();
		
		if (!trim($codice) || !IntegrazioniloginModel::getApp($clean["codice"])->isAttiva())
			$this->responseCode(403);
		
		if( !session_id() )
			session_start();
		
		IntegrazioniloginModel::getApp($clean["codice"])->getInfoOrGoToLogin("", Url::getRoot()."integrazionilogin/ottieniaccesstoken/".$clean["codice"]);
		
		$infoUtente = IntegrazioniloginModel::getApp($clean["codice"])->getInfoUtente();
		
		if (!$infoUtente["result"])
		{
			$this->redirect("regusers/login");
		}
		else if ($infoUtente["redirect"] && $infoUtente["login_redirect"])
		{
			header('Location: '.$infoUtente["login_redirect"]);
			die();
		}
		else
		{
			print_r($infoUtente);
		}
	}
	
	protected function aggiungiUrlmenuScaffold($id)
	{
		$record = $this->m[$this->modelName]->selectId((int)$id);
		
		$this->scaffold->mainMenu->links['ottieni_access_token']['absolute_url'] = Url::getRoot()."integrazionilogin/ottieniaccesstoken/".$record["codice"];
	}
}
