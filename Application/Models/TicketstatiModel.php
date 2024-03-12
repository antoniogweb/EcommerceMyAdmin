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

class TicketstatiModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'ticket_stati';
		$this->_idFields = 'id_ticket_stato';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function selectTendina($mostraOpzioneVuota = true)
	{
		$opzioneVuota = $mostraOpzioneVuota ? array(0 => "Seleziona") : [];
		
		return $opzioneVuota + $this->orderBy("id_order")->where(array(
			"ne"	=>	array(
				"codice"	=>	"B",
			),
		))->toList("codice","titolo")->send();
	}
	
	public function mandaMail($idTicket)
    {
		$tModel = new TicketModel();
		
		$ticket = $tModel->clear()->select("*")->inner(array("cliente"))->whereId((int)$idTicket)->first();
		
		if (!empty($ticket))
		{
			$lingua = $ticket["regusers"]["lingua"] ? $ticket["regusers"]["lingua"] : v("lingua_default_frontend");
			
			$email = $ticket["regusers"]["username"];
			
			$stato = TicketModel::getTitoloStato($ticket["ticket"]["stato"]);
			
			$oggetto = "Ticket ID ".(int)$idTicket." impostato allo stato ".$stato;
			
			if (checkMail($email))
			{
				$nazione = $ticket["regusers"]["nazione_navigazione"] ? strtolower($ticket["regusers"]["nazione_navigazione"]) : strtolower(v("nazione_default"));
				$linguaUrl = v("attiva_nazione_nell_url") ? $lingua."_".$nazione : $lingua;
				
				$valoriMail = array(
					"emails"	=>	array($email),
					"oggetto"	=>	$oggetto,
					"tipologia"	=>	"NUOVO_STATOO_TICKET",
					"lingua"	=>	$lingua,
					"testo_path"=>	"Elementi/Mail/Ticket/mail_cambio_stato_cliente.php",
					"tabella"	=>	"ticket",
					"id_elemento"	=>	(int)$idTicket,
					"array_variabili_tema"	=>	array(
						"OGGETTO_TICKET"	=>	$ticket["ticket"]["oggetto"],
						"URL_TICKET"		=>	Domain::$publicUrl."/".$linguaUrl."/ticket/view/$idTicket/".$ticket["ticket"]["ticket_uid"],
						"STATO_TICKET"		=>	$stato,
					),
				);
				
				MailordiniModel::inviaMail($valoriMail);
			}
		}
    }
}
