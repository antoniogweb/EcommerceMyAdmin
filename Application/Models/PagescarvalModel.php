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

class PagescarvalModel extends GenericModel {
	
	use CrudModel;
	
	public function __construct() {
		$this->_tables='pages_caratteristiche_valori';
		$this->_idFields='id_pcv';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'pages_caratteristiche_valori.id_order';
		$this->_lang = 'It';
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'caratteristica_valore' => array("BELONGS_TO", 'CaratteristichevaloriModel', 'id_cv',null,"CASCADE"),
        );
    }
	
	public function insert()
	{
		$clean["id_page"] = (int)$this->values["id_page"];
		$clean["id_cv"] = (int)$this->values["id_cv"];
		
		if (isset($this->values["id_car"]) && isset($this->values["titolo"]))
		{
			$clean["id_car"] = (int)$this->values["id_car"];
			$clean["titolo"] = trim($this->values["titolo"]);
			$this->delFields("titolo");
			$this->delFields("id_car");
		}
		
		$cv = new CaratteristichevaloriModel();
		
		if (isset($clean["titolo"]) && strcmp($clean["titolo"],"" ) !== 0)
		{
			$cv->values = array(
				"titolo"	=>	$clean["titolo"],
				"id_car"	=>	$clean["id_car"],
			);
			
			$res4 = $cv->clear()->where(array("titolo"=>$clean["titolo"],"id_car"=>$clean["id_car"]))->send();
			
			if (count($res4) > 0)
			{
				$clean["id_cv"] = (int)$res4[0]["caratteristiche_valori"]["id_cv"];
			}
			else
			{
				//controllare che la caratteristica esista
				$car = new CaratteristicheModel();
				
				$res3 = $car->clear()->where(array("id_car" => $clean["id_car"]))->send();
				
				if (count($res3) > 0)
				{
					if ($cv->insert())
					{
						$clean["id_cv"] = (int)$cv->lastId();
					}
				}
			}
			
		}
		
		//controllo di non associare un valore che non esiste
		$res2 = $cv->clear()->where(array("id_cv"=>$clean["id_cv"]))->send();
		
		if (count($res2) > 0)
		{
			$res = $this->clear()->where(array("id_page"=>$clean["id_page"],"id_cv"=>$clean["id_cv"]))->send();
			
			if (count($res) > 0)
			{
				$this->notice = "<div class='alert'>Questo valore è già stato inserito</div>";
			}
			else
			{
				$this->values["id_cv"] = $clean["id_cv"];

				return parent::insert();
			}
		}
		else
		{
			$this->notice = "<div class='alert'>Si prega di selezionare un valore</div>";
		}
	}
	
	public function edit($record)
	{
		if ($record["caratteristiche_valori"]["id_cv"])
			return "<a class='iframe action_iframe' href='".Url::getRoot()."caratteristichevalori/form/update/".$record["caratteristiche_valori"]["id_cv"]."?partial=Y&nobuttons=N'>".$record["caratteristiche_valori"]["titolo"]."</a>";
	}
	
	public function thumb($record)
	{
		$cv = new CaratteristichevaloriModel();
		
		return $cv->thumb($record);
	}
	
	public static function getFiltriCaratteristiche()
	{
		$pcv = new PagescarvalModel();
		
		$pcv->clear()->select("count(caratteristiche_valori.id_cv) as numero_prodotti,caratteristiche.titolo,caratteristiche.alias,caratteristiche.id_car,caratteristiche_valori.titolo,caratteristiche_valori.alias,caratteristiche_valori.id_cv,caratteristiche_tradotte.titolo,caratteristiche_tradotte.alias,caratteristiche_valori_tradotte.titolo,caratteristiche_valori_tradotte.alias")
			->inner(array("caratteristica_valore"))
			->inner("caratteristiche")->on(array("caratteristiche_valori.id_car = caratteristiche.id_car and filtro = ?",array("Y")))
			->left("contenuti_tradotti as caratteristiche_tradotte")->on(array("caratteristiche_tradotte.id_car = caratteristiche.id_car and caratteristiche_tradotte.lingua = ?", array(sanitizeDb(Params::$lang))))
			->left("contenuti_tradotti as caratteristiche_valori_tradotte")->on(array("caratteristiche_valori_tradotte.id_cv = caratteristiche_valori.id_cv and caratteristiche_valori_tradotte.lingua = ?",array(sanitizeDb(Params::$lang))))
			->inner("pages")->on("pages.id_page = pages_caratteristiche_valori.id_page")
			->addWhereAttivo()
			->orderBy("caratteristiche.id_order,caratteristiche_valori.id_order")
			->groupBy("caratteristiche_valori.id_cv");
		
		if (CategoriesModel::$currentIdCategory)
			$pcv->inner("categories")->on("categories.id_c = pages.id_c")->aWhere(array(
				"categories.id_c"	=>	CategoriesModel::$currentIdCategory,
			));
		
		if (v("attiva_filtri_caratteristiche_separati_per_categoria") && CategoriesModel::$currentIdCategory)
		{
			$pcv->inner("categories_caratteristiche")->on("caratteristiche.id_car = categories_caratteristiche.id_car")->sWhere("categories_caratteristiche.id_c = ".(int)CategoriesModel::$currentIdCategory)->orderBy("categories_caratteristiche.id_order,caratteristiche_valori.id_order");
		}
		
		return $pcv->send();
	}
}
