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

class TicketpagesModel extends GenericModel
{
	use CrudModel;
	
	public function __construct() {
		$this->_tables = 'ticket_pages';
		$this->_idFields = 'id_ticket_page';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'ticket' => array("BELONGS_TO", 'TicketModel', 'id_ticket',null,"RESTRICT","Si prega di selezionare un ticket di assistenza"),
			'pagina' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"RESTRICT","Si prega di selezionare un prodotto"),
        );
    }
    
    public function aggiungiProdotto($idPage, $idTicket, $ticketUid, $numero_seriale = "")
    {
		$tModel = new TicketModel();
		
		if ($tModel->check($idTicket, $ticketUid))
		{
			if (PagesModel::getPageDetails((int)$idPage))
			{
				$this->sValues(array(
					"id_ticket"	=>	(int)$idTicket,
					"id_page"	=>	(int)$idPage,
					"numero_seriale"	=>	$numero_seriale,
				));
				
				$this->insert();
			}
		}
    }
    
    public function rimuoviProdotto($idPage, $idTicket, $ticketUid)
    {
		$tModel = new TicketModel();
		
		if ($tModel->check($idTicket, $ticketUid))
		{
			if ($this->clear()->where(array(
				"id_ticket"	=>	(int)$idTicket,
				"id_page"	=>	(int)$idPage,
			))->rowNumber())
			{
				$this->del(null, array(
					"id_ticket"	=>	(int)$idTicket,
					"id_page"	=>	(int)$idPage,
				));
			}
		}
    }
    
    public function numeroProdotti($idTicket)
    {
		return $this->clear()->where(array(
			"id_ticket"	=>	(int)$idTicket,
		))->rowNumber();
    }
    
    public function getProdottiInseriti($idTicket)
    {
		$pModel = new PagesModel();
		
		return $pModel->clear()
			->select("pages.id_page,pages.title,contenuti_tradotti.title,pages.immagine,ticket_pages.numero_seriale")
			->addJoinTraduzionePagina()
			->inner("ticket_pages")->on("pages.id_page = ticket_pages.id_page")
			->where(array(
				"ticket_pages.id_ticket"	=>	(int)$idTicket,
			))
			->addWhereCategoria((int)CategoriesModel::getIdCategoriaDaSezione("prodotti"))
			->orderBy("coalesce(contenuti_tradotti.title,pages.title)")
			->send();
    }
}
