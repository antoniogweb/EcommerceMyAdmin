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

class BasePublicPagesModel extends PagesModel
{
	public function checkUtente($action = "insert", $idPage = 0)
	{
		if ($action == "insert")
			return true;
		else
		{
			$record = $this->selectId((int)$idPage);
			
			if (!empty($record) && (int)$record["id_user"] === (int)User::$id)
				return true;
		}
		
		return false;
	}
	
	public function insert()
	{
		$this->setAliasAndCategory();
		
		$this->values["id_user"] = (int)User::$id;
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
		$this->setAliasAndCategory();
		
		$this->values["temp"] = 0;
		
		return parent::update($id, $where);
	}
	
	public function check
	
	public function deletable($id)
	{
		$record = $this->selectId((int)$id);
		
		if (empty($record) || $record["cestino"])
			return false;
		
		return $this->checkUtente("del", $id);
	}
	
	public function manageable($id)
	{
		return $this->checkUtente("update", $id);
	}
	
	public function del($id = null, $where = null)
	{
		if ($this->checkUtente("update", $id))
		{
			$this->setValues(array(
				"attivo"	=>	"N",
				"cestino"	=>	1,
			));
			
			$res = $this->pUpdate($id);
			
			return $res;
		}
		
		return false;
	}
	
	protected function setConditions()
	{
		$this->addStrongCondition("update",'checkNotEmpty',"title");
	}
	
	public function addTemporaneo()
	{
		$record = $this->clear()->where(array(
			"id_user"	=>	User::$id,
			"temp"		=>	1,
		))->record();
		
		if (empty($record))
		{
			$this->setValues(array(
				"title"	=>	"",
				"alias"	=>	"",
				"attivo"=>	"Y",
				"temp"	=>	1,
			));
			
			if ($this->insert())
				return $this->lastId();
		}
		else
			return $record["id_page"];
		
		return 0;
	}
}
