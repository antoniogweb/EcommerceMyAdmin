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
		
		if ($res)
			$this->mandaMail($this->lId);
		
		return $res;
	}
	
	public function mandaMail($idMessaggio)
    {
		$ticket = $this->clear()->select("*")
			->inner(array("ticket"))->whereId((int)$idMessaggio)
			->inner("regusers")->on("regusers.id_user = ticket.id_user")
			->first();
		
		if (!empty($ticket))
		{
			$idTicket = (int)$ticket["ticket"]["id_ticket"];
			
			$lingua = $ticket["regusers"]["lingua"] ? $ticket["regusers"]["lingua"] : v("lingua_default_frontend");
			
			$oggetto = "Nuovo messaggio al ticket ID ".(int)$idTicket;
			
			if ($ticket["ticket_messaggi"]["id_admin"])
			{
				$email = $ticket["regusers"]["username"];
				$template = "Elementi/Mail/Ticket/mail_nuovo_messaggio_cliente.php";
				$messaggioTicket = htmlentitydecode($ticket["ticket_messaggi"]["descrizione"]);
			}
			else
			{
				$email = (v("email_ticket_negozio") && checkMail(v("email_ticket_negozio"))) ? v("email_ticket_negozio") : Parametri::$mailInvioOrdine;
				$template = "Elementi/Mail/Ticket/mail_nuovo_messaggio_negozio.php";
				$messaggioTicket = $ticket["ticket_messaggi"]["descrizione"];
			}
			
			if (checkMail($email))
			{
				$nazione = $ticket["regusers"]["nazione_navigazione"] ? strtolower($ticket["regusers"]["nazione_navigazione"]) : strtolower(v("nazione_default"));
				$linguaUrl = v("attiva_nazione_nell_url") ? $lingua."_".$nazione : $lingua;
				
				$valoriMail = array(
					"emails"	=>	array($email),
					"oggetto"	=>	$oggetto,
					"tipologia"	=>	"NUOVO_MESSAGGIO_TICKET",
					"lingua"	=>	$lingua,
					"testo_path"=>	$template,
					"tabella"	=>	"ticket",
					"id_elemento"	=>	(int)$idTicket,
					"array_variabili_tema"	=>	array(
						"ID_TICKET"			=>	$ticket["ticket"]["id_ticket"],
						"EMAIL_CLIENTE"		=>	$ticket["regusers"]["username"],
						"OGGETTO_TICKET"	=>	$ticket["ticket"]["oggetto"],
						"MESSAGGIO_TICKET"	=>	$messaggioTicket,
						"URL_TICKET"		=>	Domain::$publicUrl."/".$linguaUrl."/ticket/view/$idTicket/".$ticket["ticket"]["ticket_uid"],
						"NOMINATIVO_CLIENTE"=>	self::getNominativo($ticket["regusers"]),
					),
				);
				
				MailordiniModel::inviaMail($valoriMail);
			}
		}
    }
}
