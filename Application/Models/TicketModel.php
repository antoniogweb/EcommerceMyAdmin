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
	
	public $campoTitolo = "oggetto";
	
	public function __construct() {
		$this->_tables = 'ticket';
		$this->_idFields = 'id_ticket';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'pagine' => array("HAS_MANY", 'TicketpagesModel', 'id_ticket', null, "CASCADE"),
			'tipologia' => array("BELONGS_TO", 'TickettipologieModel', 'id_ticket_tipologia',null,"RESTRICT","Si prega di selezionare una tipologia del ticket di assistenza"),
			'cliente' => array("BELONGS_TO", 'RegusersModel', 'id_user' ,null, "CASCADE"),
			'admin' => array("BELONGS_TO", 'UsersModel', 'id_admin' ,null, "CASCADE"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$record = $this->selectId((int)$id);
		
		$idUser = (!empty($record)) ? $record["id_user"] : 0;
		$idTipologia = (!empty($record)) ? $record["id_ticket_tipologia"] : 0;
		
		if (isset($_POST["id_user"]))
			$idUser = (int)$_POST["id_user"];
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'id_user'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Cliente",
					"options"	=>	$this->selectUtenti($idUser, v("utilizza_ricerca_ajax_su_select_2_clienti")),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'entryAttributes'	=>	array(
						"select2"	=>	VariabiliModel::getUrlAjaxClienti(),
					),
					'wrap'	=>	array(null,null,"<div>","</div>"),
				),
				'id_ticket_tipologia'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Tipologia",
					"options"	=>	TickettipologieModel::g()->selectTipologie($idTipologia),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'id_o'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Ordine",
					"options"	=>	array(0	=>	gtext("Seleziona")) + $this->getTendinaOrdini($idUser),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'id_lista_regalo'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Lista regalo",
					"options"	=>	array(0	=>	gtext("Seleziona")) + $this->getTendinaListe($idUser),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'descrizione'	=>	array(
					'type'		=>	'Textarea',
					'className'		=>	'form-control testo_feedback',
				),
			),
		);
	}
	
    public function setConditions($idTicket = 0)
	{
		$this->addStrongCondition("update",'checkNotEmpty',"oggetto,descrizione,accetto");
		
		if (isset($_POST["id_o"]))
			$this->addStrongCondition("update",'checkIsNotStrings|0',"id_o|".gtext("Si prega di selezionare un ordine").'<div class="evidenzia">class_id_o</div>');
		
		if (isset($_POST["id_lista_regalo"]))
			$this->addStrongCondition("update",'checkIsNotStrings|0',"id_lista_regalo|".gtext("Si prega di selezionare una lista regalo").'<div class="evidenzia">class_id_lista_regalo</div>');
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
    
    public function stato($idTicket)
    {
		return $this->clear()->whereId((int)$idTicket)->field("stato");
    }
    
    public function isBozza($idTicket)
    {
		$stato = $this->stato($idTicket);
		
		return ($stato == "B") ? true : false;
    }
    
    public function numeroAperti()
    {
		return $this->clear()->where(array(
			"id_user"	=>	(int)User::$id,
			"ne"	=>	array(
				"stato"	=>	"C",
			),
		))->rowNumber();
    }
    
    public function checkNumeroProdotti($id)
    {
		if (!App::$isFrontend)
			return true;
		
		if (isset($_POST["id_ticket_tipologia"]))
		{
			$idTipologia = (int)$_POST["id_ticket_tipologia"];
			$tt = new TickettipologieModel();
			$tp = new TicketpagesModel();
			
			$tipologia = $tt->selectId($idTipologia);
			
			if (!empty($tipologia) && $tipologia["tipo"] != "GENERICO" && !$tp->numeroProdotti((int)$id))
			{
				$this->result = false;
				$this->notice = "<div class='".v("alert_error_class")."'>".gtext("Si prega di indicare per quale prodotto si sta chiedendo assistenza.")."</div>".'<div class="evidenzia">box_prodotti_inner</div>';
				
				return false;
			}
		}
		
		return true;
    }
    
    public function update($id = null, $where = null)
    {
		if (isset($this->values["id_o"]) && $this->values["id_o"])
			$this->values["id_lista_regalo"] = 0;
		
		if (isset($this->values["id_lista_regalo"]) && $this->values["id_lista_regalo"])
			$this->values["id_o"] = 0;
		
		$record = $this->selectId((int)$id);
		$inBozza = false;
		
		if ($record["stato"] == "B")
		{
			$inBozza = true;
			$this->values["stato"] = "A";
			$this->values["data_invio"] = date("Y-m-d H:i:s");
		}
		
		if ($this->checkNumeroProdotti($id))
		{
			$res = parent::update($id, $where);
			
			if ($res && $inBozza)
			{
				$this->mandaMail($id); // Invia la mail al cliente e al negozio
				
				$this->aggiungiNotifica($id); // Aggiungi la notifica nel pannello admin
			}
			
			return $res;
		}
		
		return false;
    }
    
    public function aggiungiNotifica($idTicket)
	{
		if (App::$isFrontend)
		{
			$record = $this->selectId((int)$idTicket);
			
			if (!empty($record) && $record["id_user"])
			{
				$n = new NotificheModel();
				
				$n->setValues(array(
					"titolo"	=>	"Nuovo ticket, ID: ".(int)$idTicket,
					"contesto"	=>	"TICKET",
					"url"		=>	"ticket/form/update/".(int)$idTicket,
					"classe"	=>	"text-yellow",
					"icona"		=>	"fa-ticket",
					"condizioni"=>	"attiva_gestiobe_ticket=1",
				));
				
				$n->insert();
			}
		}
	}
    
    public function add($whereUser = null)
    {
		$this->clear()->where(array(
			"stato"	=>	"B"
		));
		
		if (!isset($whereUser))
			$whereUser = $this->whereUser();
		
		$this->aWhere($whereUser);
		
		$ticket = $this->record();
		
		if (empty($ticket))
		{
			$ttModel = new TickettipologieModel();
			
			$values = $whereUser;
			
			$values["ticket_uid"] = randomToken();
			$values["id_ticket_tipologia"] = $ttModel->getFirstIdTipologiaAttiva();
			
			$this->sValues($values);
			
			if ($this->insert())
				$ticket = $this->selectId($this->lId);
		}
		
		return $ticket;
    }
    
    public function getTendinaOrdini($idUser)
    {
		$oModel = new OrdiniModel();
		
		$res = $oModel->clear()->select("id_o,data_creazione")->where(array(
			"id_user"	=>	(int)$idUser,
		))->orderBy("data_creazione desc")->send(false);
		
		$select = [];
		
		foreach ($res as $r)
		{
			$select[$r["id_o"]] = gtext("Ordine")." #".$r["id_o"]." ".gtext("del")." ".date("d-m-Y", strtotime($r["data_creazione"]));
		}
		
		return $select;
    }
    
    public function getTendinaListe($idUser)
    {
		$res = ListeregaloModel::listeUtenteAttiveModel((int)$idUser)->send(false);
		
		$select = [];
		
		foreach ($res as $r)
		{
			$select[$r["id_lista_regalo"]] = gtext("Lista")." ".$r["titolo"]." (".gtext("codice")." ".$r["codice"].") ".gtext("del")." ".date("d-m-Y", strtotime($r["data_creazione"]));
		}
		
		return $select;
    }
    
    public function getTendinaProdotti($idUser, $idO = 0, $idLista = 0, $lingua = null)
    {
		$pModel = new PagesModel();
		
		$pModel->clear()->addJoinTraduzionePagina($lingua)->addWhereCategoria((int)CategoriesModel::getIdCategoriaDaSezione("prodotti"))->orderBy("coalesce(contenuti_tradotti.title,pages.title)");
		
		if ($idO)
		{
			$pModel->inner("righe")->on("righe.id_page = pages.id_page")->inner("orders")->on("orders.id_o = righe.id_o")->where(array(
					"orders.id_o"		=>	(int)$idO,
					"orders.id_user"	=>	$idUser,
				));
		}
		else if ($idLista)
		{
			$pModel->inner("liste_regalo_pages")->on("liste_regalo_pages.id_page = pages.id_page")->inner("liste_regalo")->on("liste_regalo.id_lista_regalo = liste_regalo_pages.id_lista_regalo")->where(array(
					"liste_regalo.id_lista_regalo"	=>	(int)$idLista,
					"liste_regalo.id_user"			=>	$idUser,
				));
		}
		else
			$pModel->select("pages.id_page,pages.title,contenuti_tradotti.title")->addWhereAttivo();
		
		$res = $pModel->send();
		
		$select = [];
		
		foreach ($res as $p)
		{
			$select[$p["pages"]["id_page"]] = field($p, "title");
		}
		
		return $select;
    }
    
    public static function getStile($stato)
    {
		return TicketstatiModel::getCampoG($stato, "stile");
    }
    
    public static function getTitoloStato($stato)
    {
		return TicketstatiModel::getCampoG($stato, "titolo");
    }
    
    public static function getLabelLista($idListaRegalo)
    {
		$lr = new ListeregaloModel();
		
		$r = $lr->clear()->whereId((int)$idListaRegalo)->record();
		
		if (!empty($r))
			return gtext("Lista")." ".$r["titolo"]." (".gtext("codice")." ".$r["codice"].") ".gtext("del")." ".date("d-m-Y", strtotime($r["data_creazione"]));
		
		return "";
    }
    
    public function cleanDateTime($record)
    {
		$formato = "d-m-Y H:i";
		
		if (isset($record[$this->_tables]["data_invio"]) && $record[$this->_tables]["data_invio"])
			return date($formato,strtotime($record[$this->_tables]["data_invio"]));
		
		return "";
    }
    
    public function statoCrud($record)
    {
		return "<span class='label' style='".self::getStile($record["ticket"]["stato"])."'>".self::getTitoloStato($record["ticket"]["stato"])."</span>";
    }
    
    public function deletable($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && $record["stato"] == "B")
			return true;
		
		return false;
    }
    
    public function campanellaCrud($record)
    {
		$htmlCampanella = "<i title='".gtext("Da gestire")."' class='fa fa-bell text text-warning'></i>";
		
		if ($record["ticket"]["stato"] == "A")
			return $htmlCampanella;
		
		$tmModel = new TicketmessaggiModel();
		
		$ultimoMessaggio = $tmModel->clear()->where(array(
			"id_ticket"	=>	(int)$record["ticket"]["id_ticket"],
		))->orderBy("id_ticket_messaggio desc")->limit(1)->record();
		
		if (!empty($ultimoMessaggio) && $ultimoMessaggio["id_user"])
			return $htmlCampanella;
		
		return "";
    }
    
    public function creatoDaCrud($record)
    {
		if ($record["ticket"]["id_admin"])
			return $record["adminusers"]["username"];
		
		return "";
    }
    
    public function fonteCrud($record)
    {
		$label = "Web";
		
		if ($record["ticket"]["id_admin"])
			$label = "Backend";
		
		return $label;
    }
    
    public function mandaMail($idTicket)
    {
		$ticket = $this->clear()->select("*")->inner(array("cliente"))->whereId((int)$idTicket)->first();
		
		if (!empty($ticket))
		{
			$lingua = $ticket["regusers"]["lingua"] ? $ticket["regusers"]["lingua"] : v("lingua_default_frontend");
			
			$oggetto = "Nuovo ticket ID ".(int)$idTicket;
			
			if (checkMail($ticket["regusers"]["username"]))
			{
				$valoriMail = array(
					"emails"	=>	array($ticket["regusers"]["username"]),
					"oggetto"	=>	$oggetto,
					"tipologia"	=>	"NUOVO_TICKET",
					"lingua"	=>	$lingua,
					"testo_path"=>	"Elementi/Mail/Ticket/mail_nuovo_ticket_cliente.php",
					"tabella"	=>	"ticket",
					"id_elemento"	=>	(int)$idTicket,
					"array_variabili_tema"	=>	array(
						"ID_TICKET"			=>	$ticket["ticket"]["id_ticket"],
						"EMAIL_CLIENTE"		=>	$ticket["regusers"]["username"],
						"OGGETTO_TICKET"	=>	$ticket["ticket"]["oggetto"],
						"TESTO_TICKET"		=>	$ticket["ticket"]["descrizione"],
						"NOMINATIVO_CLIENTE"=>	self::getNominativo($ticket["regusers"]),
					),
				);
				
				MailordiniModel::inviaMail($valoriMail);
			}
			
			$emailNegozio = (v("email_ticket_negozio") && checkMail(v("email_ticket_negozio"))) ? v("email_ticket_negozio") : Parametri::$mailInvioOrdine;
			
			$valoriMail["email"] = array($emailNegozio);
			$valoriMail["tipologia"] = "NUOVO_TICKET_NEGOZIO";
			$valoriMail["testo_path"] = "Elementi/Mail/Ticket/mail_nuovo_ticket_negozio.php";
			
			MailordiniModel::inviaMail($valoriMail);
		}
    }
}
