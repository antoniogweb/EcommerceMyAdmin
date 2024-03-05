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
		
		$data["arrayLingue"] = $this->creaArrayLingueNazioni("/ticket");
		
		$clean["id_ticket"] = $this->request->get("del",0,"forceInt");
		
		if ($clean["id_ticket"] > 0)
			$this->m("TicketModel")->del(null, array("id_ticket = ? AND id_user = ? AND stato = 'B'",array($clean["id_ticket"], User::$id)));
		
		$data["ticket"] = $this->m('TicketModel')->clear()
			->select("*")
			->inner(array("tipologia"))
			->where(array(
				"id_user"	=>	(int)User::$id,
			))->orderBy("ticket.data_creazione desc")->send();
		
		$data['tipologie'] = $this->m('TickettipologieModel')->selectTipologie();
		
		$data['numeroAperti'] = $this->m("TicketModel")->numeroAperti();
		
		$this->append($data);
		
		$this->load('main');
	}
	
	// Crea ticket
	public function add()
	{
		$this->clean();
		
		$this->s['registered']->check(null,0);
		
		$numeroAperti = $this->m("TicketModel")->numeroAperti();
		
		if ($numeroAperti >= v("numero_massimo_ticket_aperti"))
			$this->redirect("ticket/");
		
		$tiket = $this->m("TicketModel")->add();
		
		if (!empty($tiket))
			$this->redirect("ticket/view/".$tiket["id_ticket"]."/".$tiket["ticket_uid"]);
		
		die();
	}
	
	// Aggiungi un prodotto al ticket
	public function aggiungiprodotto($idTicket = 0, $ticketUid = "")
	{
		$this->clean();
		
		$this->s['registered']->check(null,0);
		
		if (!$this->m("TicketModel")->check($idTicket, $ticketUid))
			$this->responseCode(403);
		
		$clean["id_page"] = $this->request->post("id_page",0,"forceInt");
		$numero_seriale = $this->request->post("numero_seriale","","none");
		
		$numero = $this->m('TicketpagesModel')->numeroProdotti((int)$idTicket);
		
		if ($numero < v("numero_massimo_prodotti_ticket") && $this->m("TicketModel")->isBozza((int)$idTicket))
			$this->m('TicketpagesModel')->aggiungiProdotto($clean["id_page"], $idTicket, $ticketUid, $numero_seriale);
	}
	
	// Aggiungi un prodotto al ticket
	public function rimuoviprodotto($idTicket = 0, $ticketUid = "")
	{
		$this->clean();
		
		$this->s['registered']->check(null,0);
		
		if (!$this->m("TicketModel")->check($idTicket, $ticketUid))
			$this->responseCode(403);
		
		$clean["id_page"] = $this->request->post("id_page",0,"forceInt");
		
		if ($this->m("TicketModel")->isBozza((int)$idTicket))
			$this->m('TicketpagesModel')->rimuoviProdotto($clean["id_page"], $idTicket, $ticketUid);
	}
	
	// Aggiungi un prodotto al ticket
	public function aggiungimessaggio($idTicket = 0, $ticketUid = "")
	{
		$this->clean();
		
		if (!$this->m("TicketModel")->check($idTicket, $ticketUid))
			$this->responseCode(403);
		
		if (!$this->m('TicketmessaggiModel')->okInvioNuovoMessaggio((int)$idTicket))
			die();
		
		if ($this->m("TicketModel")->isChiuso((int)$idTicket))
			die();
		
		$fields = "descrizione,accetto";
		$this->m('TicketmessaggiModel')->setFields($fields,'strip_tags');
		$this->m('TicketmessaggiModel')->sanitize("sanitizeAll");
		$this->m('TicketmessaggiModel')->setvalue("id_ticket", (int)$idTicket);
		
		$this->m('TicketmessaggiModel')->setConditions();
		
		$this->m('TicketmessaggiModel')->updateTable('insert', 0);
		
		if ($this->m('TicketmessaggiModel')->queryResult)
			echo "OK";
		else
			echo "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi segnati in rosso")."</div>".$this->m('TicketmessaggiModel')->notice;
		
// 		print_r($this->m('TicketmessaggiModel')->errors);
	}
	
	protected function gestisciDettaglio($idTicket)
	{
		$clean["idTicket"] = (int)$idTicket;
		
		$data["messaggi"] = $this->m('TicketmessaggiModel')->clear()->select("*")->left(array("admin"))->where(array(
			"id_ticket"	=>	(int)$idTicket,
		))->orderBy("id_ticket_messaggio")->send();
		
		$this->append($data);
	}
	
	protected function gestisciBozza($idTicket)
	{
		$clean["idTicket"] = $data["idTicket"] = (int)$idTicket;
		$ticket = $data["ticket"] = $this->m('TicketModel')->selectId($clean["idTicket"]);
		
		$idTipologia = isset($_POST["id_ticket_tipologia"]) ? (int)$_POST["id_ticket_tipologia"] : (int)$ticket["id_ticket_tipologia"];
		$idO = $idLista = 0;
		
		$tipologia = $data["tipologia"] = $this->m('TickettipologieModel')->selectId((int)$idTipologia);
		
		if (empty($tipologia))
		{
			$this->redirect("");
			die();
		}
		
		$data["arrayLingue"] = $this->creaArrayLingueNazioni("/ticket/view/".(int)$idTicket."/".$ticket["ticket_uid"]);
		
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
		
		$this->m('TicketModel')->setFields($fields,'strip_tags');
		$this->m('TicketModel')->sanitize("sanitizeAll");
		
		if (isset($_POST["gAction"]))
			$this->m('TicketModel')->result = false;
		
		if (isset($_POST['updateAction']))
		{
			$this->m('TicketModel')->setConditions((int)$ticket["id_o"]);
			
			if ($this->m('TicketModel')->checkConditions('update', $clean["idTicket"]))
			{
				$this->m('TicketModel')->updateTable('update',$clean["idTicket"]);
				
				if ($this->m('TicketModel')->queryResult)
				{
					$this->redirect("ticket/view/".$clean["idTicket"]."/".$ticket["ticket_uid"]);
				}
			}
			else
			{
				$this->m('TicketModel')->notice = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi segnati in rosso")."</div>".$this->m('TicketModel')->notice;
			}
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
		
		$this->append($data);
	}
	
	// Dettaglio ticket
	public function view($idTicket = 0, $ticketUid = "")
	{
		$clean["idTicket"] = $data["idTicket"] = (int)$idTicket;
		$clean["ticketUid"] = $data["ticketUid"] = sanitizeAll($ticketUid);
		
		$this->check($idTicket, $ticketUid);
		
		$ticket = $this->m('TicketModel')
			->select("*")
			->inner(array("tipologia"))
			->left(array("cliente"))
			->where(array(
				"id_ticket"	=>	$clean["idTicket"]
			))->first();
		
// 		print_r($ticket);
		
		if ($ticket["ticket"]["stato"] == "B")
			$this->gestisciBozza($idTicket);
		else
		{
			$data["ticket"] = $ticket["ticket"];
			$data["tipologia"] = $ticket["ticket_tipologie"];
			$data["cliente"] = $ticket["regusers"];
		
			$this->gestisciDettaglio($idTicket);
		}
		$data["prodottiInseriti"] = $this->m('TicketpagesModel')->getProdottiInseriti($clean["idTicket"]);
		
		$data['numeroProdotti'] = $this->m('TicketpagesModel')->numeroProdotti($clean["idTicket"]);
		
// 		print_r($data['prodottiInseriti']);
		
		$data['okInvioNuovoMessaggio'] = $this->m('TicketmessaggiModel')->okInvioNuovoMessaggio((int)$idTicket);
		
		$data['isChiuso'] = $this->m("TicketModel")->isChiuso((int)$idTicket);
		
		$this->append($data);
		
		if (isset($_GET["partial_prodotti"]))
		{
			$this->clean();
			$this->load('prodotti');
		}
		else if (isset($_GET["partial_view"]))
		{
			$this->clean();
			$this->load('view_partial');
		}
		else
		{
			$this->load('view');
		}
	}
	
	// Esegui l'upload del file
	public function upload($idTicket = 0, $ticketUid = "", $tipo = "immagine")
	{
		$tipo = (string)sanitizeAll(strtolower($tipo));
		
		$this->clean();
		
		$this->check($idTicket, $ticketUid);
		
		// Controllo che sia un ticket in BOZZA
		if (!$this->m("TicketModel")->isBozza((int)$idTicket))
			$this->responseCode(403);
		
		Files_Log::$logFolder = ROOT."/Logs";
		$log = Files_Log::getInstance("log_upload_ticket");
		
		if (!in_array($tipo, TicketfileModel::$tipi))
			$this->responseCode(403);
		
		$this->m('TicketfileModel')->setUploadFields();
		
		$this->m('TicketfileModel')->setFields("filename",'sanitizeAll');
		
		$result = "OK";
		
		if ($this->m("TicketfileModel")->upload("insert"))
		{
			$filePath = $this->m("TicketfileModel")->files->getBase()."/".$this->m("TicketfileModel")->files->fileName;
			
			$this->m("TicketfileModel")->setValue("id_ticket", (int)$idTicket);
			$this->m("TicketfileModel")->setValue("tipo", strtoupper($tipo));
			$this->m("TicketfileModel")->setValue("estensione", $this->m("TicketfileModel")->files->ext);
			$this->m("TicketfileModel")->setValue("mime_type", $this->m("TicketfileModel")->files->getContentType($filePath));
			
			if (!$this->m("TicketfileModel")->insert())
				$result = $this->m("TicketfileModel")->notice;
		}
		else
			$result = $this->m("TicketfileModel")->notice;
		
		echo $result;
	}
}
