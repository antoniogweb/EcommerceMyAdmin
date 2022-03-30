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
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
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
}
