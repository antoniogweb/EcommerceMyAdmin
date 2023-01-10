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

class PagescategoriesModel extends GenericModel {

	public function __construct() {
		$this->_tables='pages_categories';
		$this->_idFields='id_page_category';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'pages_categories.id_order';
		$this->_lang = 'It';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'pagina' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE","Si prega di selezionare il tag"),
			'categoria' => array("BELONGS_TO", 'CategoriesModel', 'id_c',null,"CASCADE","Si prega di selezionare il tag"),
        );
    }
    
    public function titoloCrud($record)
    {
		return CategoriesModel::g(false)->indent($record["categories"]["id_c"]);
    }
    
    public function categoriaPresente($idC, $idPage)
    {
		return $this->clear()->where(array(
			"id_c"		=>	(int)$idC,
			"id_page"	=>	(int)$idPage,
		))->rowNumber();
    }
    
    public function insert()
    {
		if ($this->categoriaPresente((int)$this->values["id_c"], (int)$this->values["id_page"]))
		{
			$this->notice = "<div class='alert alert-danger'>La categoria è già stata inserita</div>";
			return false;
		}
		
		if (parent::insert())
		{
			if (isset($this->values["id_c"]) && isset($this->values["id_page"]))
			{
				$cModel = new CategoriesModel();
				
				//ottengo i genitori
				$parents = $cModel->parents((int)$this->values["id_c"], false, true, null, "id_c", 2);
				
				foreach ($parents as $p)
				{
					if (!$this->categoriaPresente((int)$p["categories"]["id_c"], (int)$this->values["id_page"]))
					{
						$this->sValues(array(
							"id_c"		=>	(int)$p["categories"]["id_c"],
							"id_page"	=>	(int)$this->values["id_page"],
							"genitore"	=>	1,
						));
						
						$this->pInsert();
					}
				}
			}
			
			return true;
		}
		
		return false;
    }
}
