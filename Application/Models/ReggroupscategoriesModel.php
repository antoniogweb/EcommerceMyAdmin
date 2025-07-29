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

class ReggroupscategoriesModel extends GenericModel {

	public function __construct() {
		$this->_tables='reggroups_categories';
		$this->_idFields='id_gc';
		
		$this->orderBy = 'id_order desc';
		
		$this->_lang = 'It';
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function insert()
	{
		$clean["id_c"] = (int)$this->values["id_c"];
		$clean["id_group"] = (int)$this->values["id_group"];
		
		$u = new ReggroupsModel();
		
		$ng = $u->select("*")->where(array("n!reggroups.id_group"=>$clean["id_group"]))->rowNumber();
		
		if ($ng > 0)
		{
			$res3 = $this->clear()->where(array("id_group"=>$clean["id_group"],"id_c"=>$clean["id_c"]))->send();
			
			if (count($res3) > 0)
			{
				$this->notice = "<div class='alert alert-danger'>".gtext("Questo gruppo è già stato associato a questa categoria")."</div>";
			}
			else
			{
				$c = new CategoriesModel();
				$allowedGroups = $c->allowedGroups($clean["id_c"]);
				$allowedIds = array_keys($allowedGroups);
				
				if (in_array($clean["id_group"], $allowedIds))
				{
					$res = parent::insert();
					
					if ($res)
					{
						$this->updatePageAccessibility($clean["id_c"]);
					}
					
					return $res;
				}
				else
				{
					$this->notice = "<div class='alert alert-danger'>".gtext("Non puoi associare questo gruppo")."</div>";
				}
			}
		}
		else
		{
			$this->notice = "<div class='alert alert-danger'>".gtext("Questo elemento non esiste")."</div>";
		}
	}
	
	public function update($id = null, $where = null)
	{
		$res = parent::update($id, $where);
		
		$clean["id_c"] = (int)$this->values["id_c"];
		
		if ($res)
		{
			$this->updatePageAccessibility($clean["id_c"]);
		}

		return $res;
	}
	
	public function del($id = null, $where = null)
	{
		$clean["id"] = (int)$id;
		
		$record = $this->selectId($clean["id"]);
		
		$res = false;
		
		if (count($record) > 0)
		{
			$res = parent::del($id, $where);
			
			if ($res)
			{
				$this->updatePageAccessibility($record["id_c"]);
			}
		}
		
		return $res;
	}
	
	public function updatePageAccessibility($id_c)
	{
		$clean["id_c"] = (int)$id_c;
		
		$p = new PagesModel();
		$c = new CategoriesModel();
		
		$children = $c->children($clean['id_c'], true);
		$catWhere = "in(".implode(",",$children).")";
		
		$pages = $p->clear()->where(array(
			"in" => array("-id_c" => $children),
		))->toList("id_page")->send();
		
		foreach ($pages as $id_page)
		{
			$gruppi = $p->accessibility($id_page);
			
			$access = count($gruppi) > 0 ? "(".implode("),(",$gruppi).")" : "--free--";
			
			$p->values = array("gruppi" => $access);
			$p->sanitize();
			$p->pUpdate((int)$id_page);
		}
	}
	
}
