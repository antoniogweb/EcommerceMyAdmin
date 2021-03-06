<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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

class RegusersModel extends FormModel {
	
	public $applySoftConditionsOnPost = true;
	
	public function __construct() {
		$this->_tables='regusers';
		$this->_idFields='id_user';
		
		$this->orderBy = 'regusers.id_user desc';
		$this->_lang = 'It';

		$this->_popupItemNames = array(
			'has_confirmed'	=>	'has_confirmed',
			'tipo_cliente'	=>	'tipo_cliente',
		);

		$this->_popupLabels = array(
			'has_confirmed'	=>	'ATTIVO?',
			'tipo_cliente'	=>	'TIPO CLIENTE',
		);

		$this->_popupFunctions = array(
			'has_confirmed'	=>	'getYesNoUtenti',
		);
		
		parent::__construct();
		
		if (!$this->usingApi)
		{
			$this->addStrongCondition("both",'checkMail',"username|Si prega di ricontrollare il campo Email<div rel='hidden_alert_notice' style='display:none;'>username</div>");
			$this->addStrongCondition("insert",'checkNotEmpty',"password,confirmation");
			$this->addSoftCondition("both",'checkEqual',"password,confirmation|Le due password non coincidono<div rel='hidden_alert_notice' style='display:none;'>password</div><div rel='hidden_alert_notice' style='display:none;'>confirmation</div>");

			$this->addDatabaseCondition("both",'checkUnique',"username|Il valore del campo Email è già usato da un altro cliente, si prega di sceglierne un altro<div rel='hidden_alert_notice' style='display:none;'>username</div>");
		}
	}
	
	public function relations() {
        return array(
			'ordini' => array("HAS_MANY", 'OrdiniModel', 'id_user', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
			'sedi' => array("HAS_MANY", 'SpedizioniModel', 'id_user', null, "CASCADE"),
			'groups' => array("MANY_TO_MANY", 'ReggroupsModel', 'id_group', array("RegusersgroupsModel","id_user","id_group"), "CASCADE"),
			'tipo_azienda' => array("BELONGS_TO", 'TipiaziendaModel', 'id_tipo_azienda',null,"CASCADE"),
			'ruolo' => array("BELONGS_TO", 'RuoliModel', 'id_ruolo',null,"CASCADE"),
        );
    }
	
	public function update($id = null, $where = null)
	{
		$clean['id'] = (int)$id;
		
		if (strcmp($this->values['password'],sha1('')) === 0)
		{
			$this->delFields('password');
		}
		parent::update($clean['id']);
	}

// 	public function del($id = null, $whereClause = null)
// 	{
// 		$clean['id'] = (int)$id;
// 		
// 		if ($this->checkOnDeleteIntegrity($id, $whereClause))
// 		{
// 			//cancello tutti i gruppi a cui è associato
// 			$ug = new RegusersgroupsModel();
// 			$ug->del(null,"id_user=".$clean['id']);
// 		}
// 		
// 		return parent::del($clean['id']);
// 	}
	
	//restituisci il codice fiscale o la partita iva
	public function getCFoPIva($row)
	{
		if (strcmp($row["regusers"]["tipo_cliente"],"privato") === 0)
		{
			return "CF: ".$row["regusers"]["codice_fiscale"];
		}
		else
		{
			return "P.IVA: ".$row["regusers"]["p_iva"];
		}
	}
	
	//restituisci il nome del cliente dell'ordine (nome cognome o ragione sociale)
	public function nome($row)
	{
		if (strcmp($row["regusers"]["tipo_cliente"],"privato") === 0)
		{
			return $row["regusers"]["nome"]." ".$row["regusers"]["cognome"];
		}
		else
		{
			return $row["regusers"]["ragione_sociale"];
		}
	}
	
	public function listaGruppi($id)
	{
		$clean["id"] = (int)$id;
		
		$ug = new RegusersgroupsModel();
		
		$gruppi = $ug->clear()->select("reggroups.name")->inner("reggroups")->using("id_group")->where(array("id_user"=>$clean["id"]))->toList("reggroups.name")->send();
		
		if (count($gruppi) > 0)
		{
			return implode("<br />",$gruppi);
		}
		
		return "- -";
	}
	
	public function titolo($id)
	{
		$clean["id"] = (int)$id;
		
		$record = $this->selectId($clean["id"]);
		
		if (isset($record["username"]))
		{
			return $record["username"];
		}
		
		return "";
	}
}
