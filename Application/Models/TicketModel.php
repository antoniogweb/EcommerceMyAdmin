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

class TicketModel extends GenericModel
{
	use CrudModel;
	
	public function __construct() {
		$this->_tables = 'ticket';
		$this->_idFields = 'id_ticket';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'tipologia' => array("BELONGS_TO", 'TickettipologieModel', 'id_ticket_tipologia',null,"RESTRICT","Si prega di selezionare una tipologia del ticket di assistenza"),
        );
    }
    
    protected function whereUser()
    {
		if (App::$isFrontend)
			return array(
				"id_user"	=>	User::$id,
			);
		else
			return array(
				"id_admin"	=>	User::$id,
			);
    }
    
    public function check($idTicket, $ticketUid)
    {
		return $this->clear()->where(array(
			"id_ticket"		=>	(int)$idTicket,
			"ticket_uid"	=>	sanitizeAll($ticketUid)
		))->rowNumber();
    }
    
    public function add()
    {
		$this->clear()->where(array(
			"stato"	=>	"B"
		));
		
		$this->aWhere($this->whereUser());
		
		$ticket = $this->record();
		
		if (empty($ticket))
		{
			$values = $this->whereUser();
			
			$values["ticket_uid"] = randomToken();
			
			$this->sValues($values);
			
			if ($this->insert())
				$ticket = $this->selectId($this->lId);
		}
		
		return $ticket;
    }
}
