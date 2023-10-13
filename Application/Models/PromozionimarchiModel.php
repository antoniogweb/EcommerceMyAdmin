<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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

class PromozionimarchiModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='promozioni_marchi';
		$this->_idFields='id_pm';
		
		$this->orderBy = 'id_order desc';
		
		$this->_lang = 'It';
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'marchio' => array("BELONGS_TO", 'MarchiModel', 'id_marchio',null,"CASCADE"),
			'promozione' => array("BELONGS_TO", 'PromozioniModel', 'id_p',null,"CASCADE"),
        );
    }
    
	public function insert()
	{
		$clean["id_p"] = (int)$this->values["id_p"];
		$clean["id_marchio"] = (int)$this->values["id_marchio"];
		
		$u = new MarchiModel();
		
		$cat = $u->selectId($clean["id_marchio"]);
		
		if (!empty($cat))
		{
			$res3 = $this->clear()->where(array("id_marchio"=>$clean["id_marchio"],"id_p"=>$clean["id_p"]))->send();
			
			if (count($res3) > 0)
			{
				$this->notice = "<div class='alert alert-danger'>".gtext("Questo elemento è già stato associato")."</div>";
			}
			else
			{
				$includi = isset($this->values["includi"]) ? (int)$this->values["includi"] : 0;
				
				$includiComplementare = $includi ? 0 : 1;
				
				$fraseErrore = $includi ? "Hai già alcuni marchi in esclusione, non puoi aggiungerne in inclusione" : "Hai già alcuni marchi in inclusione, non puoi aggiungerne in esclusione";
				
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
		if ($record["promozioni_marchi"]["includi"])
			return "<i class='fa fa-check text-success'></i>";
		else
			return "<i class='fa fa-ban text-danger'></i>";
	}
}
