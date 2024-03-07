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

class TicketmessaggiController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array(
		'id_ticket:forceInt'	=>	0,
	);
	
	public $orderBy = "id_order";
	
	public $tabella = "messaggi ticket";
	
	public $sezionePannello = "ecommerce";
	
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_gestiobe_ticket"))
			$this->responseCode(403);
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		if ((int)$this->viewArgs["id_ticket"] === 0 && !$this->m("TicketModel")->clear()->whereId($this->viewArgs["id_ticket"])->rowNumber())
			$this->responseCode(403);
		
		$this->m[$this->modelName]->addStrongCondition("insert",'checkNotEmpty',"descrizione");
		$this->m[$this->modelName]->setValuesFromPost('descrizione,filename');
		$this->m[$this->modelName]->setValue("id_admin", User::$id);
		$this->m[$this->modelName]->setValue("id_ticket", $this->viewArgs["id_ticket"]);
		
		parent::form($queryType, $id);
	}
}
