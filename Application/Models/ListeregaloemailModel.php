<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
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

class ListeregaloemailModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'liste_regalo_email';
		$this->_idFields = 'id_lista_regalo_email';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'lista' => array("BELONGS_TO", 'ListeregaloModel', 'id_lista_regalo',null,"CASCADE"),
        );
    }
    
    public function aggiungiDaOrdine($ordine)
    {
		$attivo = OrdiniModel::isPagato($ordine["id_o"]) ? true : false;
		
		$listaRegalo = ListeregaloModel::g()->selectId((int)$ordine["id_lista_regalo"]);
		
		if ($attivo && !empty($listaRegalo) && ListeregaloModel::attiva((int)$ordine["id_lista_regalo"]) && checkMail($listaRegalo["email"]) && trim($ordine["dedica"]) && trim($ordine["firma"]))
		{
			$email = $this->clear()->where(array(
				"id_o"	=>	(int)$ordine["id_o"],
			))->send(false);
			
			if (empty($email))
			{
				$this->sValues(array(
					"email"	=>	$listaRegalo["email"],
					"titolo_lista_regalo"	=>	$listaRegalo["titolo"],
					"nominativo"	=>	ListeregaloModel::g()->getNominativoLista($ordine["id_lista_regalo"]),
					"firma"	=>	$ordine["firma"],
					"dedica"	=>	$ordine["dedica"],
					"id_lista_regalo"	=>	$ordine["id_lista_regalo"],
					"id_user"	=>	$ordine["id_user"],
					"nome"	=>	OrdiniModel::getNominativo($ordine),
					"id_o"	=>	$ordine["id_o"],
					"creation_time"	=>	time(),
				), "sanitizeDb");
				
				if (!App::$isFrontend)
					$this->setValue("id_admin", User::$id);
				
				if ($this->insert())
					$this->processaEventiListaRegalo($this->lId);
			}
		}
    }
    
    public function processaEventiListaRegalo($idListaEmail)
	{
		$record = $this->selectId((int)$idListaEmail);
		
		if (!empty($record) && isset($record["email"]) && $record["email"] && checkMail($record["email"]) && $record["dedica"] && ListeregaloModel::attiva($record["id_lista_regalo"]) )
			EventiretargetingModel::processaLista($idListaEmail);
	}
	
	public function dettagliElementoCrud($record)
	{
		$record["orders"]["id_o"] = $record["liste_regalo_email"]["id_o"];
		
		return OrdiniModel::g()->vedi($record);
	}
}
