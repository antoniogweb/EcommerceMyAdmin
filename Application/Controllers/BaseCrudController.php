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

trait BaseCrudController
{
	public static $azioniPermesse = array("insert","update");
	public static $traduzioni = array();
	
	public $menuLinks = "back,save";
	public $menuLinksInsert = "back,save";
	public $formAction = null;
	public $formFields = null;
	public $formDefaultValues = array();
	public $functionsIfFromDb = array();
	public $formView = "form";
	public $mainView = "main";
	public $insertRedirect = true;
	public $updateRedirect = false;
	public $updateRedirectUrl = null;
	
	protected function getStringaErroreValidazione()
	{
		return "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>";
	}
	
	protected function redirectAfterInsertUpdate($queryType = 'insert', $id = 0, $frontend = false, $queryString = "")
	{
		$clean["id"] = (int)$id;
		
		$notice = $this->m[$this->modelName]->notice;
		
		if ($frontend && !$this->m[$this->modelName]->queryResult)
			$notice = $this->getStringaErroreValidazione().$notice;
		
		if (strcmp($queryType,'insert') === 0 and $this->m[$this->modelName]->queryResult and $this->insertRedirect)
		{
			if ((isset($this->viewArgs["cl_on_sv"]) && $this->viewArgs["cl_on_sv"] != "Y") || $frontend)
			{
				$lId = $this->m[$this->modelName]->lId;
				
				flash("notice",$notice);
				
				if (isset($this->insertRedirectUrl))
					$this->redirect($this->insertRedirectUrl);
				else
					$this->redirect($this->applicationUrl.$this->controller.'/form/update/'.$lId.$this->viewStatus.$queryString);
			}
		}
		
		if (strcmp($queryType,'update') === 0 and $this->m[$this->modelName]->queryResult)
		{
			flash("notice",$notice);
			
			$queryStringOk = !$frontend ? "&insert=ok" : "";
			
			if (($this->updateRedirect or isset($_POST["redirectToList"])) && !$frontend)
				$this->redirect($this->controller.'/main/'.$this->viewStatus);
			else if ($this->updateRedirectUrl)
				$this->redirect($this->updateRedirectUrl);
			else
				$this->redirect($this->applicationUrl.$this->controller.'/'.$this->action.'/update/'.$clean["id"].$this->viewStatus.$queryStringOk);
		}
	}
	
	protected function basePublicForm($queryType = 'insert', $id = 0)
	{
		if (in_array($queryType,CrudController::$azioniPermesse))
		{
			$clean["id"] = $data["id"] = (int)$id;
			
			// Controllo l'accesso
			$this->checkAccessoPagina($queryType, $clean["id"]);
			
			$this->m[$this->modelName]->updateTable('insert,update',$clean["id"]);
			
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
			
			$data['scaffold'] = $this->scaffold->render();
			
			$data['azioni'] = $this->scaffold->html['menu'];
			$data['main'] = $mainContent = $this->scaffold->html['main'];
			$data['notice'] = $this->scaffold->model->notice;
			
			if (!empty($_POST) && !$this->m[$this->modelName]->queryResult)
				$data['notice'] .= $this->getStringaErroreValidazione();
			
			$this->append($data);
			$this->load($this->formView);
		}
		else
			$this->redirect("");
	}
}
