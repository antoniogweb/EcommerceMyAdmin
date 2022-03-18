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

class ClassiscontoController extends BaseController {
	
	public $mainFields = array("[[ledit]];classi_sconto.titolo;","classi_sconto.sconto");
	
	public $mainHead = "Titolo,Sconto (%)";
	
	public $filters = array("titolo");
	
	public $formValuesToDb = 'titolo,sconto';
	
	public $orderBy = "sconto";
	
	public $argKeys = array('titolo:sanitizeAll'=>'tutti');
	
	public $useEditor = true;
	
	public $tabella = "classi sconto";
	
	public $sezionePannello = "ecommerce";
	
// 	function __construct($model, $controller, $queryString) {
// 		parent::__construct($model, $controller, $queryString);
// 
// 		$data["sezionePannello"] = "ecommerce";
// 		
// 		$this->append($data);
// 	}
	
	public function main()
	{
		$this->shift();
		
		$this->m[$this->modelName]->where(array(
				"lk" => array("titolo" => $this->viewArgs["titolo"]),
// 				"attivo"	=>	$this->viewArgs["attivo"],
			))->orderBy($this->orderBy)->convert()->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		parent::form($queryType, $id);
	}
}
