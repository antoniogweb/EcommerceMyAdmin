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
			'pagine' => array("HAS_MANY", 'TicketpagesModel', 'id_ticket', null, "CASCADE"),
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
    
    public function update($id = null, $where = null)
    {
		if (isset($this->values["id_o"]) && $this->values["id_o"])
			$this->values["id_lista_regalo"] = 0;
		
		if (isset($this->values["id_lista_regalo"]) && $this->values["id_lista_regalo"])
			$this->values["id_o"] = 0;
		
		return parent::update($id, $where);
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
			$ttModel = new TickettipologieModel();
			
			$values = $this->whereUser();
			
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
}
