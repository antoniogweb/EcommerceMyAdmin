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

class FeedController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "utenti";
	
	public $tabella = "feed XML";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		if (!v("attiva_gestione_feed"))
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
		
		$this->mainFields = array("edit","feed.codice","attivo");
		$this->mainHead = "Titolo,Codice,Attivo";
		
		$this->m[$this->modelName]->clear()->orderBy("titolo")->convert()->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		if ($queryType != "update")
			$this->responseCode(403);
		
		$record = $data["record"] = $this->m[$this->modelName]->selectId((int)$id);
		
		if (empty($record))
			$this->responseCode(403);
		
		$this->m[$this->modelName]->setTokenSicurezza($id);
		
		$fields = FeedModel::getModulo($record["codice"])->gCampiForm();
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		$this->menuLinks = "back,save,vedi_feed";
		
		parent::form($queryType, $id);
		
		$this->append($data);
	}
	
	protected function aggiungiUrlmenuScaffold($id)
	{
		$record = $this->m[$this->modelName]->selectId((int)$id);
		
		$this->scaffold->mainMenu->links['vedi_feed']['absolute_url'] = Domain::$publicUrl."/it".F::getNazioneUrl(null)."/feed/prodotti/".strtolower($record["codice"])."/".$record["token_sicurezza"];
	}
}
