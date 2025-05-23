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

class PromozionipagineModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='promozioni_pages';
		$this->_idFields='id_pp';
		
		$this->orderBy = 'id_order desc';
		
		$this->_lang = 'It';
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'pagina' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
        );
    }
    
	public function insert()
	{
		$clean["id_p"] = (int)$this->values["id_p"];
		$clean["id_page"] = (int)$this->values["id_page"];
		
		$u = new PagesModel();
		
		$cat = $u->selectId($clean["id_page"]);
		
		if (!empty($cat))
		{
			$res3 = $this->clear()->where(array("id_page"=>$clean["id_page"],"id_p"=>$clean["id_p"]))->send();
			
			if (count($res3) > 0)
			{
				$this->notice = "<div class='alert alert-danger'>".gtext("Questo elemento è già stato associato")."</div>";
			}
			else
			{
				$includi = isset($this->values["includi"]) ? (int)$this->values["includi"] : 0;
				
				$includiComplementare = $includi ? 0 : 1;
				
				$fraseErrore = $includi ? "Hai già alcuni prodotti in esclusione, non puoi aggiungerne in inclusione" : "Hai già alcuni prodotti in inclusione, non puoi aggiungerne in esclusione";
				
				$numero = $this->clear()->where(array(
					"id_p"		=>	$clean["id_p"],
					"includi"	=>	$includiComplementare,
				))->rowNumber();
				
				if ((int)$numero === 0)
					return parent::insert();
				else
					$this->notice = "<div class='alert alert-danger'>".gtext($fraseErrore)."</div>";
			}
		}
		else
		{
			$this->notice = "<div class='alert alert-danger'>".gtext("Questo elemento non esiste")."</div>";
		}
		
		return false;
	}
	
	public function inclusoCrud($record)
	{
		if ($record["promozioni_pages"]["includi"])
			return "<i class='fa fa-check text-success'></i>";
		else
			return "<i class='fa fa-ban text-danger'></i>";
	}
}
