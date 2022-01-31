<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
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

class RegusersgroupstempModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='regusers_groups_temp';
		$this->_idFields='id_ugt';
		
		$this->orderBy = 'id_order desc';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'cliente' => array("BELONGS_TO", 'RegusersModel', 'id_user',null,"CASCADE"),
			'gruppo' => array("BELONGS_TO", 'ReggroupsModel', 'id_group',null,"CASCADE"),
        );
    }
    
    public function edit($record)
	{
		if ($record[$this->_tables]["id_user"])
			return "<a class='iframe action_iframe' href='".Url::getRoot()."regusers/form/update/".$record[$this->_tables]["id_user"]."?partial=Y&nobuttons=N'>".$record["regusers"]["username"]."</a>";
		
		return "";
	}
	
	public function gruppidaapprovare($record)
	{
		$gruppi = $this->clear()->select("if(reggroups.name != '',concat('Aggiunta a gruppo: <b>',reggroups.name,'</b>'),'<b>Attivazione account</b>') as nome_gruppo")->left(array("gruppo"))->where(array("id_user"=>(int)$record[$this->_tables]["id_user"]))->toList("aggregate.nome_gruppo")->send();
		
		if (count($gruppi) > 0)
			return implode("<br />",$gruppi);
		
		return "- -";
	}
	
	public function approvacrud($record)
    {
		return "<i data-azione='approvagruppi' title='".gtext("Approva tutto")."' class='bulk_trigger fa fa-thumbs-up text text-success'></i>";
    }
    
    public function approvasoloaccountcrud($record)
    {
		return "<i data-azione='approvasoloaccountgruppi' title='".gtext("Approva solo attivazione account")."' class='bulk_trigger fa fa-thumbs-up text text-info'></i>";
    }
    
	public function approvasoloaccountgruppi($id)
    {
		$this->approvagruppi($id, true);
	} 
    
    public function approvagruppi($id, $soloaccount = false)
    {
		$rg = new ReggroupsModel();
		$ru = new RegusersModel();
		$rug = new RegusersgroupsModel();
		
		$record = $this->selectId($id);
		
		if (!empty($record))
		{
			$idUser = (int)$record["id_user"];
			
			$user = $ru->selectId($idUser);
			
			if (empty($user))
				return;
			
			$ru->setValues(array(
				Users_CheckAdmin::$statusFieldName	=>	Users_CheckAdmin::$statusFieldActiveValue,
			));
			
			$ru->pUpdate($idUser);
			
			$righeDaCopiare = $this->clear()->where(array(
				"id_user"	=>	(int)$idUser,
			))->send(false);
			
			foreach ($righeDaCopiare as $r)
			{
				$idGroup = (int)$r["id_group"];
				
				$gruppo = $rg->selectId($idGroup);
				
				if (!empty($gruppo) && !$soloaccount)
				{
					$rug->setValues(array(
						"id_user"	=>	$idUser,
						"id_group"	=>	$idGroup,
					));
					
					$rug->insert();
				}
				
				$this->del($r["id_ugt"]);
			}
			
			$this->mandaMailApprovazione($idUser, !$soloaccount);
		}
    }
    
    public function mandaMailApprovazione($idUser, $approvato)
	{
		$ru = new RegusersModel();
		$record = $ru->selectId((int)$idUser);
		
		if (empty($record))
			return;
		
		if ($approvato)
			$oggetto = "la sua iscrizione al sito è stata approvata";
		else
			$oggetto = "la sua iscrizione al sito è stata approvata";
		
		if ($approvato)
			$testoPath = "Elementi/Mail/mail_approvazione_gruppi_utente.php";
		else
			$testoPath = "Elementi/Mail/mail_approvazione_solo_account_utente.php";
		
		$nome = $this->nome(array("regusers"=>$record));
		
		$res = MailordiniModel::inviaMail(array(
			"emails"	=>	array($record["username"]),
			"oggetto"	=>	$oggetto,
			"tipologia"	=>	"APPROVAZIONE_CLIENTE",
			"id_user"	=>	(int)$idUser,
			"lingua"	=>	$record["lingua"],
			"testo_path"	=>	$testoPath,
			"array_variabili_tema"	=>	array(
				"NOME_CLIENTE"	=>	$nome,
			),
		));
	}
	
	public static function numerodaapprovare()
	{
		$rugt = new RegusersgroupstempModel();
		
		return $rugt->clear()->groupBy("id_user")->rowNumber();
	}
	
	public function del($id = null, $where = null)
	{
		$record = $this->selectId($id);
		
		if (!empty($record))
			return parent::del(null, "id_user = ".(int)$record["id_user"]);
	}
}
