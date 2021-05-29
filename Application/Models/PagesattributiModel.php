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

class PagesattributiModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='pages_attributi';
		$this->_idFields='id_pa';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'pages_attributi.id_order';
		$this->_lang = 'It';
		
		$this->addStrongCondition("both",'checkIsNotStrings|0',"id_a|Si prega di specificare l'attributo da aggiungere");
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'attributo' => array("BELONGS_TO", 'AttributiModel', 'id_a',null,"CASCADE","Si prega di selezionare il corso"),
        );
    }
    
	public function getColonna($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$colonne = $this->clear()->where(array("id_page"=>$clean["id_page"]))->orderBy("id_order")->toList("colonna")->send();
		
		$allowedCols = array("col_1","col_2","col_3","col_4","col_5","col_6","col_7","col_8");
		
		foreach ($allowedCols as $col)
		{
			if (!in_array($col,$colonne))
			{
				return $col;
			}
		}
		return 0;
	}
	
	public function getNomiColonne($id_page, $lingua = null)
	{
		$clean["id_page"] = (int)$id_page;
		
		if (!isset($lingua))
			$lingua = Params::$lang;
		
		$res = $this->clear()
				->select("pages_attributi.colonna,attributi.titolo,contenuti_tradotti.titolo")
				->inner("attributi")->on("attributi.id_a = pages_attributi.id_a")
				->left("contenuti_tradotti")->on("contenuti_tradotti.id_a = attributi.id_a and contenuti_tradotti.lingua = '".sanitizeDb($lingua)."'")
				->where(array("id_page"=>$clean["id_page"]))
				->orderBy("pages_attributi.id_order")
				->send();
		
		$arrayColonne = array();
		
		foreach ($res as $r)
		{
			$arrayColonne[$r["pages_attributi"]["colonna"]] = afield($r, "titolo");
		}
		
		return $arrayColonne;
	}
	
	public function insert()
	{
		$clean["id_page"] = (int)$this->values["id_page"];
		$clean["id_a"] = (int)$this->values["id_a"];
		
		$res = $this->clear()->where(array("id_page"=>$clean["id_page"],"id_a"=>$clean["id_a"]))->send();
		
		//controllo che l'attributo non sia già stato associato
		if (count($res) > 0)
		{
			$this->notice = "<div class='alert'>Questo attributo è già stato associato</div>";
		}
		else
		{
			//controllo di non associare un attributo che non abbia valori
			$a = new AttributivaloriModel();
			$res2 = $a->clear()->where(array("id_a"=>$clean["id_a"]))->send();
			
			if (count($res2) > 0)
			{
				//controllo che non abbia associato più di due attributi
				$res3 = $this->clear()->where(array("id_page"=>$clean["id_page"]))->send();
				if (count($res3) >= 3)
				{
					$this->notice = "<div class='alert'>Non è possibile associare più di due attributi ad un singolo prodotto</div>";
				}
				else
				{
					$colonna = $this->getColonna($clean["id_page"]);
					$this->values["colonna"] = $colonna;
					
					if ($colonna !== 0)
					{
						parent::insert();
					}
				}
			}
			else
			{
				$this->notice = "<div class='alert'>Questo attributo non può essere associato perché non contiene alcun valore</div>";
			}
		}
	}
	
	public function titoloConNota($record)
	{
		if ($record["attributi"]["nota_interna"])
			return $record["attributi"]["titolo"]." (".$record["attributi"]["nota_interna"].")";
		
		return $record["attributi"]["titolo"];
	}
}
