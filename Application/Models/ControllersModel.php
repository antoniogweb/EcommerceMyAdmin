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

class ControllersModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'controllers';
		$this->_idFields = 'id_controller';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'gruppi' => array("HAS_MANY", 'GroupscontrollersModel', 'id_controller', null, "CASCADE"),
		);
    }
	
	public function sistemaVisibilita()
	{
		$res = $this->clear()->where(array(
			"codice_padre"	=>	"",
		))->send();
		
		foreach ($res as $record)
		{
			if ($record["controllers"]["condizioni"])
			{
				$visibile = $record["controllers"]["visibile"];
				
				$statoCorretto = VariabiliModel::verificaCondizioni($record["controllers"]["condizioni"]) ? 1 : 0;
				
				if ((int)$visibile !== (int)$statoCorretto)
				{
					$this->sValues(array(
						"visibile"	=>	$statoCorretto,
					));
					
					$this->update($record["controllers"]["id_controller"]);
				}
			}
		}
		
		$this->notice = "";
	}
	
	public function bulkaggiungiagruppo($record)
    {
		return "<i data-azione='aggiungiagruppo' title='".gtext("Aggiungi al gruppo")."' class='bulk_trigger help_trigger_aggiungi_al_gruppo fa fa-plus-circle text text-primary'></i>";
    }
    
    public function aggiungiagruppo($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && isset($_GET["id_group"]))
		{
			$gc = new GroupscontrollersModel();
			
			$gc->sValues(array(
				"id_group"		=>	(int)$_GET["id_group"],
				"id_controller"	=>	(int)$id,
			), "sanitizeDb");
			
			$gc->insert();
		}
    }
}
