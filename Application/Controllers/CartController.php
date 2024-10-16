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

class CartController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array('dal:sanitizeAll'=>'tutti', 'al:sanitizeAll'=>'tutti', 'tipo_carrello:sanitizeAll'=>'tutti');
	
	public $sezionePannello = "marketing";
	
	public $tabella = "carrelli abbandonati";
	
	public function main()
	{
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		$this->addBulkActions = false;
		
		$this->colProperties = array(
			array(
				'width'	=>	'80px',
			),
		);
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>50, 'mainMenu'=>'esporta');
		
		$this->shift();
		
		$campoPrezzoSuffisso = v("prezzi_ivati_in_carrello") ? "_ivato" : "";
		$labelPrezzo = v("prezzi_ivati_in_carrello") ? "inclusa" : "esclusa";
		
		$mainFiels = array("thumb", "cleanDateTime", "titolocompleto", "categories.title", "cart.quantity", "cart.prezzo_intero$campoPrezzoSuffisso", "cart.price$campoPrezzoSuffisso", "datiutente");
		$mainHead = "Immagine,Data creazione,Prodotto,Categoria,Quantità,Prezzo intero IVA $labelPrezzo (€),Prezzo finale IVA $labelPrezzo (€),Email";
		
		if (v("traccia_sorgente_utente"))
		{
			$mainFiels[] = "cart.sorgente";
			$mainHead .= ",Sorgente";
		}
		
		$this->mainFields = $mainFiels;
		$this->mainHead = $mainHead;
		
		$filtroTipo = array(
			"tutti"		=>	"Tipo carrello",
			"anonimo"	=>	"Carrello anonimo",
			"contatto"	=>	"Email conosciuta",
			"utente"	=>	"Utente registrato",
		);
		
		$filtri = array("dal","al",array("tipo_carrello",null,$filtroTipo));
		$this->filters = $filtri;
		
		$this->m[$this->modelName]->clear()
				->select("*")
				->inner(array("pagina"))
				->left("regusers")->on("regusers.id_user = cart.id_user")
				->left("categories")->on("pages.id_c = categories.id_c")
				->orderBy("cart.email desc, cart.data_creazione desc")->convert();
		
		$this->m[$this->modelName]->setDalAlWhereClause($this->viewArgs['dal'], $this->viewArgs['al']);
		
		if ($this->viewArgs['tipo_carrello'] == "anonimo")
			$this->m[$this->modelName]->sWhere("cart.email = ''");
		else if ($this->viewArgs['tipo_carrello'] == "contatto")
			$this->m[$this->modelName]->sWhere("cart.email != ''");
		else if ($this->viewArgs['tipo_carrello'] == "utente")
			$this->m[$this->modelName]->sWhere("cart.id_user != 0");
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
}
