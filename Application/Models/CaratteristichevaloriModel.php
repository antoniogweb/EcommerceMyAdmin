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

class CaratteristichevaloriModel extends GenericModel {

	public $lId = 0;
	
	public static $names = array();
	
	public function __construct() {
		$this->_tables='caratteristiche_valori';
		$this->_idFields='id_cv';
		
		$this->_idOrder = 'id_order';
		
		$this->traduzione = true;
		
		$this->orderBy = 'caratteristiche_valori.id_order';
		$this->_lang = 'It';
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo|Si prega di specificare il valore della caratteristica");
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_cv', null, "CASCADE"),
			'caratteristica' => array("BELONGS_TO", 'CaratteristicheModel', 'id_car',null,"CASCADE"),
        );
    }
    
	//get the name of the attribute from the id
	public function getName($id_av)
	{
		$clean["id_av"] = (int)$id_av;
		
		if (isset(self::$names[$clean["id_av"]]))
		{
			return self::$names[$clean["id_av"]];
		}
		else
		{
			$res = $this->clear()->where(array("id_av"=>$clean["id_av"]))->send();
			
			if (count($res) > 0)
			{
				self::$names[$clean["id_av"]] = $res[0]["caratteristiche_valori"]["titolo"];
				return $res[0]["caratteristiche_valori"]["titolo"];
			}
		}
		return "";
	}
	
	public function edit($record)
	{
		if ($record["caratteristiche_valori"]["id_cv"])
			return "<a class='iframe action_iframe' href='".Url::getRoot()."caratteristichevalori/form/update/".$record["caratteristiche_valori"]["id_cv"]."?partial=Y&nobuttons=N'>".$record["caratteristiche_valori"]["titolo"]."</a>";
	}
	
	public function del($id = null, $whereClause = null)
	{
		$clean['id'] = (int)$id;

		$pcv = new PagescarvalModel();
		
		$res = $pcv->clear()->select("distinct pages.id_page,pages.*")->inner("pages")->using("id_page")->where(array("id_cv"=>$clean['id']))->send();

		if (count($res) > 0)
		{
			$this->notice = "<div class='alert'>Questa caratteristica è associata a uno o più prodotti e non può quindi essere cancellata se prima non si provvede a dissociarla da tali prodotti.<br/>Elenco prodotti che usano tale valore:<ul>";
			foreach ($res as $r)
			{
				$this->notice .= "<li><a style='color:#000;' target='_blank' href='http://".DOMAIN_NAME."/prodotti/caratteristiche/".$r["pages"]["id_page"]."'>".$r["pages"]["title"]."</a></li>";
			}
			$this->notice .= "</ul></div>";
			$this->result = false;
		}
		else
		{
			return parent::del($clean["id"]);
		}
	}
	
	public function insert()
	{
		$res = parent::insert();
		
		$this->lId = $this->lastId();
		
		if ($res)
			$this->controllaLingua($this->lId);
		
		return $res;
	}
	
	public function update($id = null, $where = null)
	{
		$res = parent::update($id, $where);
		
		if ($res)
			$this->controllalingua($id, "id_cv");
		
		return $res;
	}
	
	// Controllo che la lingua esista
	public function controllaLingua($id)
	{
		$this->controllaLinguaGeneric($id, "id_cv", "-cv-");
	}
	
	public function linklingua($record, $lingua)
	{
		return $this->linklinguaGeneric($record["caratteristiche_valori"]["id_cv"], $lingua, "id_cv");
	}
}
