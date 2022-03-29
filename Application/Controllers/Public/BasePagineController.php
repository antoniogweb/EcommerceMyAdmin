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

class BasePagineController extends BaseController
{
	public $menuVariable = "azioni";
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("permetti_agli_utenti_di_aggiungere_pagine"))
			$this->redirect("");
		
		if (Output::$html)
		{
			$this->load('header');
			$this->load('footer','last');
		}
		
		$data["arrayLingue"] = array();
		
		$this->s['registered']->check(null,0);
		
		if (class_exists($model))
			$this->model($model);
		
		BaseController::$traduzioni = $data['elencoTraduzioniAttive'] = LingueModel::getLingueNonPrincipali();
		
		$this->append($data);
	}
	
	protected function form($queryType = 'insert', $id = 0)
	{
		if ((string)$queryType === "insert")
		{
			$idPage = $this->m[$this->modelName]->addTemporaneo();
			
			if ($idPage)
				$this->redirect($this->applicationUrl.$this->controller.'/form/update/'.$idPage.$this->viewStatus);
			else
			{
				$_SESSION['result'] = "error";
				$this->redirect("avvisi");
			}
		}
		
		$this->basePublicForm($queryType, $id);
	}
	
	protected function main()
	{
		$this->m[$this->modelName]->clear()->restore(true)->where(array(
			"temp"	=>	0,
		))->save();
		
		$this->baseMain();
	}
}
