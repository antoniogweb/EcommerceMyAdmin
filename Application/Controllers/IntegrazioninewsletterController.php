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

class IntegrazioninewsletterController extends BaseController
{
	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "utenti";
	
	public $tabella = "integrazioni con sistemi di newsletter";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		if (!v("mostra_gestione_newsletter"))
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
		
		$this->_posizioni['main'] = 'class="active"';
		
		$record = $this->m[$this->modelName]->selectId((int)$id);
		
		if (empty($record))
			die();
		
		$fields = IntegrazioninewsletterModel::getModulo($record["codice"])->gCampiForm();
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
	}
	
	public function campi($id = 0)
	{
		$this->_posizioni['campi'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_integrazione_newsletter";
		
		$record = $this->m("IntegrazioninewsletterModel")->selectId((int)$id);
		
		if (empty($record))
			$this->responseCode(404);
		
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		$this->addBulkActions = false;
		$this->colProperties = array();
		
		$this->modelName = "IntegrazioninewslettervariabiliModel";
		
		$this->mainFields = array("integrazioni_newsletter_variabili.codice_campo","integrazioni_newsletter_variabili.nome_campo");
		$this->mainHead = "Codice campo ".$record["titolo"].",Nome campo interno";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"campi/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m($this->modelName)->orderBy("id_order")->where(array(
			"codice_integrazione_newsletter_variabile"	=>	sanitizeAll($record["codice"]),
		))->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["IntegrazioninewsletterModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
}
