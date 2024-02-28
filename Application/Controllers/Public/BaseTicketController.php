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
		
		$data['tipologie'] = $this->m('TickettipologieModel')->selectTipologie();
		
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
	
	// Aggiungi un prodotto al ticket
	public function aggiungiprodotto($idTicket = 0, $ticketUid = "")
	{
		$this->clean();
		
		if (!$this->m("TicketModel")->check($idTicket, $ticketUid))
			$this->responseCode(403);
		
		$clean["id_page"] = $this->request->post("id_page",0,"forceInt");
		
		$numero = $this->m('TicketpagesModel')->numeroProdotti((int)$idTicket);
		
		if ($numero < v("numero_massimo_prodotti_ticket"))
			$this->m('TicketpagesModel')->aggiungiProdotto($clean["id_page"], $idTicket, $ticketUid);
	}
	
	// Aggiungi un prodotto al ticket
	public function rimuoviprodotto($idTicket = 0, $ticketUid = "")
	{
		$this->clean();
		
		if (!$this->m("TicketModel")->check($idTicket, $ticketUid))
			$this->responseCode(403);
		
		$clean["id_page"] = $this->request->post("id_page",0,"forceInt");
		
		$this->m('TicketpagesModel')->rimuoviProdotto($clean["id_page"], $idTicket, $ticketUid);
	}
	
	// Dettaglio ticket
	public function view($idTicket = 0, $ticketUid = "")
	{
		$clean["idTicket"] = $data["idTicket"] = (int)$idTicket;
		$clean["ticketUid"] = $data["ticketUid"] = sanitizeAll($ticketUid);
		
		$this->check($idTicket, $ticketUid);
		
		$ticket = $data["ticket"] = $this->m('TicketModel')->selectId($clean["idTicket"]);
		
		$idTipologia = isset($_POST["id_ticket_tipologia"]) ? (int)$_POST["id_ticket_tipologia"] : (int)$ticket["id_ticket_tipologia"];
		$idO = $idLista = 0;
		
		$tipologia = $data["tipologia"] = $this->m('TickettipologieModel')->selectId((int)$idTipologia);
		
		if (empty($tipologia))
		{
			$this->redirect("");
			die();
		}
		
		$data["arrayLingue"] = $this->creaArrayLingueNazioni("/ticket/view/".(int)$idTicket."/".$clean["ticketUid"]);
		
		$fields = "id_ticket_tipologia,oggetto,descrizione,accetto";
		
		if ($tipologia["tipo"] == "ORDINE")
		{
			$fields .= ",id_o";
			
			$idO = isset($_POST["id_o"]) ? (int)$_POST["id_o"] : (int)$ticket["id_o"];
			$idLista = 0;
		}
		else if ($tipologia["tipo"] == "LISTA REGALO")
		{
			$fields .= ",id_lista_regalo";
			
			$idLista = isset($_POST["id_lista_regalo"]) ? (int)$_POST["id_lista_regalo"] : (int)$ticket["id_lista_regalo"];
			$idO = 0;
		}
		
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
		
		$data['ordini'] = array(0 => gtext("Seleziona")) + $this->m('TicketModel')->getTendinaOrdini($ticket["id_user"]);
		$data['listeRegalo'] = array(0 => gtext("Seleziona")) + $this->m('TicketModel')->getTendinaListe($ticket["id_user"]);
		
		$data['mostra_tendina_prodotti'] = false;
		
		if (
			($tipologia["tipo"] == "ORDINE" && $idO) || 
			($tipologia["tipo"] == "LISTA REGALO" && $idLista) || 
			($tipologia["tipo"] == "PRODOTTO")
		)
		{
			$data['mostra_tendina_prodotti'] = true;
			$data['prodotti'] = array(0 => gtext("Seleziona")) + $this->m('TicketModel')->getTendinaProdotti($ticket["id_user"], $idO, $idLista);
		}
		else
			$data['prodotti'] = array(0 => gtext("Seleziona"));
		
		$data["prodottiInseriti"] = $this->m('TicketpagesModel')->getProdottiInseriti($clean["idTicket"]);
		
		$data['numeroProdotti'] = $this->m('TicketpagesModel')->numeroProdotti($clean["idTicket"]);
		
// 		print_r($data['prodottiInseriti']);
		
		$this->append($data);
		
		$this->load('form');
	}
}
