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

class BaseTicketController extends BaseController
{
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_gestiobe_ticket"))
			$this->redirect("");
		
		$this->load('header');
		$this->load('footer','last');
		
		$data["arrayLingue"] = array();
		
		$data["isAreaRiservata"] = true;
		
		$this->append($data);
	}
	
	// Controlla ticket
	protected function check($idTicket, $ticketUid)
	{
		if (!$this->m("TicketModel")->check($idTicket, $ticketUid))
		{
			$this->redirect("");
			die();
		}
	}
	
	// Elenco ticket
	public function index()
	{
		$this->s['registered']->check(null,0);
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/ticket/";
		}
		
		$data["ticket"] = array();
		
		$this->append($data);
		
		$this->load('main');
	}
	
	// Crea ticket
	public function add()
	{
		$this->clean();
		
		$this->s['registered']->check(null,0);
		
		$tiket = $this->m("TicketModel")->add();
		
		if (!empty($tiket))
			$this->redirect("ticket/view/".$tiket["id_ticket"]."/".$tiket["ticket_uid"]);
		
		die();
	}
	
	// Dettaglio ticket
	public function view($idTicket = 0, $ticketUid = "")
	{
		$clean["idTicket"] = (int)$idTicket;
		$clean["ticketUid"] = sanitizeAll($ticketUid);
		
		$this->check($idTicket, $ticketUid);
		
		$ticket = $data["ticket"] = $this->m('TicketModel')->selectId($clean["idTicket"]);
		
		$idTipologia = isset($_POST["id_ticket_tipologia"]) ? (int)$_POST["id_ticket_tipologia"] : $ticket["id_ticket_tipologia"];
		
		$tipologia = $data["tipologia"] = $this->m('TickettipologieModel')->selectId((int)$idTipologia);
		
		if (empty($tipologia))
		{
			$this->redirect("");
			die();
		}
		
		$data["arrayLingue"] = $this->creaArrayLingueNazioni("/ticket/view/".(int)$idTicket."/".$clean["ticketUid"]);
		
		$fields = "id_ticket_tipologia,oggetto,descrizione,accetto";
		
		if ($tipologia["tipo"] == "ORDINE")
			$fields .= ",id_o";
		else if ($tipologia["tipo"] == "LISTA REGALO")
			$fields .= ",id_lista_regalo";
			
		$this->m('TicketModel')->setFields($fields,'sanitizeAll');
		
		if (isset($_POST["gAction"]))
			$this->m('TicketModel')->result = false;
		
		if (isset($_POST['updateAction']))
		{
			$this->m('TicketModel')->updateTable('update',$clean["idTicket"]);
		}
		
		$data['notice'] = $this->m('TicketModel')->notice;
		
		$data['values'] = $this->m('TicketModel')->getFormValues('update','sanitizeHtml',$clean["idTicket"]);
		
		$data['tipologie'] = $this->m('TickettipologieModel')->selectTipologie($clean["idTicket"]);
		
		$data['ordini'] = $this->m('TicketModel')->getTendinaOrdini($ticket["id_user"]);
		$data['listeRegalo'] = $this->m('TicketModel')->getTendinaListe($ticket["id_user"]);
		
// 		print_r($data['tipologie']);
		
		$this->append($data);
		
		$this->load('form');
	}
}
