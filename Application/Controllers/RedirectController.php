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

class RedirectController extends BaseController {
	
	public $mainFields = array("[[ledit]];redirect.vecchio_url;","redirect.nuovo_url", "redirect.codice_redirect");
	
	public $mainHead = "Vecchio URL,Nuovo URL,Codice";
	
	public $filters = array("vecchio_url", "nuovo_url");
	
	public $formValuesToDb = 'vecchio_url,nuovo_url,attivo';
	
	public $orderBy = "vecchio_url";
	
	public $argKeys = array('vecchio_url:sanitizeAll'=>'tutti', 'nuovo_url:sanitizeAll'=>'tutti');
	
	public $useEditor = true;
	
	public $sezionePannello = "utenti";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_redirect"))
			die();
	}
	
	public function main()
	{
		$this->shift();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>'add,genera_redirect');
		
		$this->m[$this->modelName]->where(array(
				"lk" => array("vecchio_url" => $this->viewArgs["vecchio_url"]),
				" lk" => array("nuovo_url" => $this->viewArgs["nuovo_url"]),
// 				"attivo"	=>	$this->viewArgs["attivo"],
			))->orderBy($this->orderBy)->convert()->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		parent::form($queryType, $id);
	}
	
	public function rigenera()
	{
		$this->clean();
		
		RedirectModel::generaRedirectFile();
	}
}
