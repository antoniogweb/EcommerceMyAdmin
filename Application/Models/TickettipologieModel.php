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

class TickettipologieModel extends GenericModel
{
	use CrudModel;
	
	public function __construct() {
		$this->_tables = 'ticket_tipologie';
		$this->_idFields = 'id_ticket_tipologia';
		
		$this->_idOrder = 'id_order';
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'liste' => array("HAS_MANY", 'TicketModel', 'id_ticket_tipologia', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$spiegazioneListe = v("attiva_liste_regalo") ? "<br />".gtext("Con la tipologia LISTA REGALO, il cliente dovrà selezionare la lista regalo per cui richiede assistenza e poi uno o più prodotti di quella lista.") : "";
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'tipo'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Tipo",
					"options"	=>	self::getSelectTipi(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Con la tipologia ORDINE, il cliente dovrà selezionare l'ordine per cui richiede assistenza e poi uno o più prodotti di quell'ordine.")."<br />".gtext("Con la tipologia PRODOTTO, il cliente dovrà selezionare un prodotto per cui richiede assistenza.").$spiegazioneListe."<br />".gtext("Con la tipologia GENERICO, il cliente nondovrà selezionare alcun prodotto, ordine o lista regalo.")."</div>"
					),
				),
				'attivo'	=>	self::$entryAttivo,
			),
		);
	}
	
	public function getSelectTipi()
	{
		$tipi = array(
			"ORDINE"	=>	"ORDINE",
			"PRODOTTO"	=>	"PRODOTTO",
			"GENERICO"	=>	"GENERICO",
		);
		
		if (v("attiva_liste_regalo"))
			$tipi["LISTA REGALO"] = "LISTA REGALO";
		
		return $tipi;
	}
}
