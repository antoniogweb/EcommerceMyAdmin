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

class UsersgroupsModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='adminusers_groups';
		$this->_idFields='id_ug';
		
		$this->orderBy = 'id_order desc';
		
		$this->_lang = 'It';
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'gruppo' => array("BELONGS_TO", 'GroupsModel', 'id_group',null,"CASCADE"),
			'user' => array("BELONGS_TO", 'UsersModel', 'id_user',null,"CASCADE"),
		);
    }
	
	public function insert()
	{
		$clean["id_user"] = (int)$this->values["id_user"];
		$clean["id_group"] = (int)$this->values["id_group"];
		
		$u = new GroupsModel();
		
		$ng = $u->clear()->select("*")->where(array("n!admingroups.id_group"=>$clean["id_group"]))->rowNumber();
		
		if ($ng > 0)
		{
			$res3 = $this->clear()->where(array("id_group"=>$clean["id_group"],"id_user"=>$clean["id_user"]))->send();
			
			if (count($res3) > 0)
			{
				$this->notice = "<div class='alert'>Questo utente è già stato associato a questo gruppo</div>";
			}
			else
			{
				$ngu = $this->select("*")->where(array("id_user"=>$clean["id_user"]))->rowNumber();
				
// 				if ($ngu === 0)
// 				{
					return parent::insert();
// 				}
// 				else
// 				{
// 					$this->notice = "<div class='alert'>Un utente non può essere associato a più di un gruppo.</div>";
// 				}
			}
		}
		else
		{
			$this->notice = "<div class='alert'>Questo elemento non esiste</div>";
		}
	}
	
}
