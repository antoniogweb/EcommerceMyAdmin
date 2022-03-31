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

Cache::removeTablesFromCache(array("categories", "pages", "contenuti_tradotti"));

class BasePublicCrudController extends BaseController
{
	public $baseArgsKeys = array(
		'page:forceInt'=>1,
		'attivo:sanitizeAll'=>'tutti',
		'cestino:sanitizeAll'=>0,
	);
	
	public $menuVariable = "azioni";
	
	public $modelAssociati = array(); // Da caricare
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		App::$isUsingCrud = true;
		
		if (!v("permetti_agli_utenti_di_aggiungere_pagine"))
			$this->redirect("");
		
		$this->mainButtons = 'ledit,ldel';
		
		$this->load('header');
		$this->load('footer','last');
		
		$data["arrayLingue"] = array();
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/".$this->controller."/".$this->action;
		}
		
		$this->s['registered']->check(null,0);
		
		$this->setStatusVariables();
		
		if (class_exists($model))
			$this->model($model);
		
		BaseController::$traduzioni = $data['elencoTraduzioniAttive'] = LingueModel::getLingueNonPrincipali();
		
		$this->inverseColProperties = array(
			array(
				"style"	=>	"width:20px;",
				"class"	=>	"ldel",
			),
			array(
				"style"	=>	"width:20px;",
			),
		);
		
		$this->append($data);
	}
	
	protected function basePublicForm($queryType = 'insert', $id = 0)
	{
		$table = $this->m[$this->modelName]->table();
		
		$this->shift(2);
		
		$clean["id"] = $data["id"] = (int)$id;
		
		// Controllo l'accesso
		$this->checkAccessoPagina($queryType, $clean["id"]);
		
		if (in_array($queryType,CrudController::$azioniPermesse))
		{
			$this->m[$this->modelName]->updateTable('insert,update',$clean["id"]);
			
			$data["titoloRecord"] = gtext("inserimento nuovo elemento");
			
			if (strcmp($queryType,'update') === 0)
				$data["titoloRecord"] = $this->m[$this->modelName]->titolo($clean["id"]);
			
			$this->redirectAfterInsertUpdate($queryType, $clean["id"], true);
			
			$this->m[$this->modelName]->setFormStruct($clean["id"]);
			
			if (strcmp($queryType,'insert') === 0)
				$this->menuLinks = $this->menuLinksInsert;
			
			$params = array(
				'formMenu'=>$this->menuLinks,
			);
			
			$formAction = isset($this->formAction) ? $this->formAction : $this->applicationUrl.$this->controller."/".$this->action."/$queryType/".$clean["id"];
			
			$this->loadScaffold('form',$params);
			$this->scaffold->loadForm($queryType,$formAction);
			
			$this->scaffold->model->fields = isset($this->formFields) ? $this->formFields : $this->scaffold->model->fields;
			
			$this->scaffold->getFormValues('sanitizeHtml',$clean["id"],$this->formDefaultValues, $this->functionsIfFromDb);
			
			$data["form"] = array();
			
			foreach ($this->scaffold->values as $key => $value)
			{
				$data["form"][$key] = $this->scaffold->model->form->entry[$key]->render($value);
			}
			
			$this->scaffold->mainMenu->links['copia']['url'] = 'form/copia/'.$clean['id'];
			
			$data['scaffold'] = $this->scaffold->render();
			
			$data['azioni'] = $this->scaffold->html['menu'];
			$data['main'] = $mainContent = $this->scaffold->html['main'];
			$data['notice'] = $this->scaffold->model->notice;
			
			if (!empty($_POST) && !$this->m[$this->modelName]->queryResult)
				$data['notice'] .= $this->getStringaErroreValidazione();
			
			$this->append($data);
			$this->load($this->formView);
		}
		else if ($table == "pages" && (string)$queryType === "copia")
		{
			$this->duplicaPagina($clean['id'], v("alert_error_class"));
			
// 			$this->clean();
// 			
// 			$lId = $this->m[$this->modelName]->duplicaPagina($clean['id'], $this->modelAssociati);
// 			
// 			if ($lId)
// 			{
// 				flash("notice",$this->m[$this->modelName]->notice);
// 				
// 				$this->redirect($this->applicationUrl.$this->controller."/form/update/".$lId.$this->viewStatus);
// 			}
// 			else
// 			{
// 				flash("notice","<div class='alert alert-danger'>Attenzione, si è verificato un errore. Si prega di riprovare.</div>");
// 				
// 				$this->redirect($this->applicationUrl.$this->controller."/form/update/".$clean['id'].$this->viewStatus);
// 			}
		}
		else
			$this->redirect("");
	}
}
