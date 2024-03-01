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

class TicketmessaggiModel extends GenericModel
{
	use CrudModel;
	
	public function __construct() {
		$this->_tables = 'ticket_messaggi';
		$this->_idFields = 'id_ticket_messaggio';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'ticket' => array("BELONGS_TO", 'TicketModel', 'id_ticket',null,"RESTRICT","Si prega di selezionare un ticket di assistenza"),
			'admin' => array("BELONGS_TO", 'UsersModel', 'id_admin',null,"CASCADE"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'descrizione'	=>	array(
					'type'		=>	'Textarea',
					'className'		=>	'form-control testo_feedback',
				),
			),
		);
	}
    
    public function setConditions()
	{
		$this->addStrongCondition("insert",'checkNotEmpty',"descrizione,accetto");
	}
	
	public function insert()
	{
		if (App::$isFrontend)
			$this->values["id_user"] = (int)User::$id;
		else
			$this->values["id_admin"] = (int)User::$id;
		
		$res = parent::insert();
		
		if ($res && !App::$isFrontend && isset($this->values["id_admin"]) && $this->values["id_admin"])
		{
			$idTicketMessaggio = $this->lId;
			
			$ticket = $this->clear()->select("ticket.stato,ticket.id_ticket")->inner(array("ticket"))->where(array(
				"id_ticket_messaggio"	=>	(int)$idTicketMessaggio,
			))->first();
			
			if (!empty($ticket) && $ticket["ticket"]["stato"] == "A")
			{
				$tModel = new TicketModel();
				
				$tModel->sValues(array(
					"stato"	=>	"L",
				));
				
				$tModel->pUpdate($ticket["ticket"]["id_ticket"]);
			}
		}
		
		return $res;
	}
}
