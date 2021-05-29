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

class NewsController extends BaseController {
	
	public $mainFields = array("[[ledit]];news.titolo;","news.data_news","getYesNo|news.attivo");
	
	public $mainHead = "TITOLO NOTIZIA,DATA,PUBLICATA?";
	
	public $filters = array(array("attivo","",array("tutti"=>"Filtro visibilità","Y"=>"Pubblicate","N"=>"Non pubblicate")),"titolo");
	
	public $formValuesToDb = 'titolo,alias,data_news,attivo,keywords,meta_description,descrizione,immagine,documento';
	
	public $orderBy = "id_order desc";
	
	public $argKeys = array('titolo:sanitizeAll'=>'tutti');
	
	public $useEditor = true;
	
// 	function __construct($model, $controller, $queryString) {
// 		parent::__construct($model, $controller, $queryString);
// 
// 		$this->useEditor = true;
// 		
// 		$this->s['admin']->check();
// 	}
	
	public function main()
	{
		$this->shift();
		
		$this->m[$this->modelName]->where(array(
				"lk" => array("titolo" => $this->viewArgs["titolo"]),
				"attivo"	=>	$this->viewArgs["attivo"],
			))->orderBy($this->orderBy)->convert()->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		parent::form($queryType, $id);
	}
	
	public function documento($field = "", $id = 0)
	{
		parent::documento($field, $id);
	}
	
	public function thumb($field = "", $id = 0)
	{
		parent::thumb($field, $id);
	}
}
