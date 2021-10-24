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

class EventiretargetingController extends BaseController
{
	public $orderBy = "id_order";
	
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "marketing";
	
	public $tabella = "retargeting";
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("eventi_retargeting.titolo","tipo","dopoquanto","<b>OGGETTO</b>: ;pages.title;","attivo");
		$this->mainHead = "Titolo evento,Quale evento scatterà?,Dopo quanto?,Quale email verrà inviata?,Evento attivo?";
		
		$this->m[$this->modelName]->clear()
				->select("*")
				->inner(array("email"))
				->orderBy("eventi_retargeting.id_order")->convert()->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$this->m[$this->modelName]->setValuesFromPost('titolo,attivo,tipo,scatta_dopo_ore,id_page');
		
		parent::form($queryType, $id);
	}
}
