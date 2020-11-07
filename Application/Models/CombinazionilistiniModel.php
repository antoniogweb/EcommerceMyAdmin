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

class CombinazionilistiniModel extends GenericModel {

	public function __construct() {
		$this->_tables='combinazioni_listini';
		$this->_idFields='id_combinazione_listino';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'combinazione' => array("BELONGS_TO", 'CombinazioniModel', 'id_c',null,"CASCADE","Si prega di selezionare la comnbinazione"),
        );
    }
    
	public static function elencoListini()
	{
		$cl = new CombinazionilistiniModel();
		
		return $cl->clear()->select("distinct nazione")->toList("nazione")->send();
	}
	
	public function getPrezzoListino($idC, $nazione)
	{
		$listino = $this->clear()->where(array(
			"nazione"	=>	sanitizeAll($nazione),
			"id_c"		=>	(int)$idC,
		))->record();
		
		if (empty($listino))
		{
			$c = new CombinazioniModel();
			
			$combinazione = $c->selectId((int)$idC);
			
			if (!empty($idC))
			{
				$this->setValues(array(
					"nazione"	=>	$nazione,
					"id_c"		=>	$idC,
					"price"		=>	$combinazione["price"],
				));
				
				if ($this->insert())
					return array($this->lId,$combinazione["price"]);
			}
		}
		else
			return array($listino["id_combinazione_listino"],$listino["price"]);
		
		return array(0,null);
	}
}
