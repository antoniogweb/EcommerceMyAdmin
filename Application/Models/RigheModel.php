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

class RigheModel extends GenericModel {

	public function __construct() {
		$this->_tables='righe';
		$this->_idFields='id_r';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'righe.id_order';
		$this->_lang = 'It';
		
		parent::__construct();
	}
	
	public function insert()
	{
		$res = parent::insert();
		
		if ($res && v("attiva_giacenza") && isset($this->values["quantity"]) && isset($this->values["id_c"]))
		{
			$c = new CombinazioniModel();
			
			$combinazione = $c->selectId((int)$this->values["id_c"]);
			
			if (!empty($combinazione))
			{
				$c->setValues(array(
					"giacenza"	=>	((int)$combinazione["giacenza"] - (int)$this->values["quantity"]),
				));
				
				$c->pUpdate($combinazione["id_c"]);
				
				// Aggiorno la combinazione della pagina
				$c->aggiornaGiacenzaPagina($combinazione["id_c"]);
			}
		}
		
		return $res;
	}
	
// 	//get the total from the cart
// 	public function total($id_o)
// 	{
// 		$clean["id_o"] = (int)$id_o;
// 		
// 		$res = $this->clear()->where(array("id_o"=>$clean["id_o"]))->send();
// 		
// 		$total = 0;
// 		
// 		if (count($res) > 0)
// 		{
// 			foreach ($res as $r)
// 			{
// 				$total = $total + ($r[$this->_tables]["price"] * $r[$this->_tables]["quantity"]);
// 			}
// 		}
// 		
// 		return $total;
// 	}
// 	
// 	public function getPesoTotale($id_o)
// 	{
// 		$clean["id_o"] = (int)$id_o;
// 		
// 		$res = $this->clear()->where(array("id_o"=>$clean["id_o"]))->send();
// 		
// 		$total = 0;
// 		
// 		if (count($res) > 0)
// 		{
// 			foreach ($res as $r)
// 			{
// 				$total = $total + ($r[$this->_tables]["peso"] * $r[$this->_tables]["quantity"]);
// 			}
// 		}
// 		
// 		return $total;
// 	}
// 	
// 	//numero prodotti nel carrello
// 	public function numberOfItems($id_o)
// 	{
// 		$clean["id_o"] = (int)$id_o;
// 		
// 		$res = $this->clear()->select("sum(quantity) as q")->where(array("id_o"=>$clean["id_o"]))->groupBy("id_o")->send();
// 		
// 		if (count($res) > 0)
// 		{
// 			return $res[0]["aggregate"]["q"];
// 		}
// 
// 		return "0";
// 	}
}
