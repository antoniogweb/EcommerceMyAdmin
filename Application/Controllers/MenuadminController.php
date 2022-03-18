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

class MenuadminController extends BaseController {
	
	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "ecommerce";
	
	public $tabella = "menu admin";
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("titoloElenco", "menu_admin.contesto", "menu_admin.tipo", "menu_admin.controller","menu_admin.action", "menu_admin.icona", "menu_admin.condizioni", "menu_admin.url", "menu_admin.classe");
		$this->mainHead = "Titolo,Contesto,Tipo,Controller,Action,Icona,Condizioni,Url,Classe";
		
		$this->m[$this->modelName]->clear()
				->where(array(
					"ne" => array("id_menu_admin" => "1"),
				))
				->orderBy("lft asc")->convert()->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$this->m[$this->modelName]->setValuesFromPost('titolo,id_p,contesto,icona,controller,action,condizioni,tipo,url,classe');
		
		parent::form($queryType, $id);
	}
	
// 	public function main()
// 	{
// 		parent::main();
// 		
// 		$data["titoloMenu"] = "GESTIONE MENU (".strtoupper(MenuModel::$lingua).")";
// 		
// 		$this->_topMenuClasses['menu1'] = array("active","in");
// 		$data['tm'] = $this->_topMenuClasses;
// 		
// 		$this->append($data);
// 	}
// 	
// 	public function form($queryType = 'insert',$id = 0)
// 	{
// 		$this->m[$this->modelName]->addStrongCondition("both",'checkNotEmpty',"title");
// 		
// 		parent::form($queryType, $id);
// 		
// 		$this->_topMenuClasses['menu1'] = array("active","in");
// 		$data['tm'] = $this->_topMenuClasses;
// 		
// 		$this->append($data);
// 	}
}
