<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2025  Antonio Gallo (info@laboratoriolibero.com)
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

class RegusersgroupsModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='regusers_groups';
		$this->_idFields='id_ug';
		
		$this->orderBy = 'id_order desc';
		
		$this->_lang = 'It';
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function insert()
	{
		$clean["id_user"] = (int)$this->values["id_user"];
		$clean["id_group"] = (int)$this->values["id_group"];
		
		$u = new ReggroupsModel();
		
		$ng = $u->selectId($clean["id_group"]);
		
		if (!empty($ng))
		{
			$res3 = $this->clear()->where(array("id_group"=>$clean["id_group"],"id_user"=>$clean["id_user"]))->send();
			
			if (count($res3) > 0)
			{
				$this->notice = "<div class='alert alert-danger'>".gtext("Questo utente è già stato associato a questo gruppo")."</div>";
			}
			else
			{
				$res = parent::insert();
				
				if ($res && v("permetti_di_collegare_gruppi_utenti_a_newsletter") && IntegrazioninewsletterModel::integrazioneAttiva() && $ng["sincronizza_newsletter"])
				{
					$regModel = new RegusersModel();
					
					$cliente = $regModel->selectId($clean["id_user"]);
					
					$cliente["email"] = $cliente["username"];
					
					IntegrazioninewsletterModel::getModulo()->setParam("codice_fonte", $ng["name"]);
					
					IntegrazioninewsletterModel::getModulo()->iscrivi(IntegrazioninewsletterModel::elaboraDati(htmlentitydecodeDeep($cliente)));
				}
				
				return $res;
			}
		}
		else
		{
			$this->notice = "<div class='alert alert-danger'>".gtext("Questo elemento non esiste")."</div>";
		}
	}
	
	public function disiscrivi($idGruppo, $idUser)
	{
		if (v("permetti_di_collegare_gruppi_utenti_a_newsletter") && IntegrazioninewsletterModel::integrazioneAttiva())
		{
			$gruppo = ReggroupsModel::g()->selectId($idGruppo);
			
			if (!empty($gruppo) && $gruppo["sincronizza_newsletter"])
			{
				$regModel = new RegusersModel();
				
				$cliente = htmlentitydecodeDeep($regModel->selectId((int)$idUser));
				
				IntegrazioninewsletterModel::getModulo()->disiscrivi($cliente["username"]);
			}
		}
	}
	
	public function del($id = null, $where = null)
	{
		$record = $this->selectId((int)$id);
		
		$res = parent::del($id, $where);
		
		if ($res && !empty($record))
			$this->disiscrivi($record["id_group"], $record["id_user"]);
		
		return $res;
	}
	
}
