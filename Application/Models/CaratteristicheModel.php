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

class CaratteristicheModel extends GenericModel {

	public $lId = 0;
	
	public function __construct() {
		$this->_tables='caratteristiche';
		$this->_idFields='id_car';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'caratteristiche.titolo';
		$this->_lang = 'It';
		
		$this->traduzione = true;
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'titolo'		=>	array(
					'labelString'=>	'Titolo caratteristica',
				),
				'id_car'	=>	array(
					'type'		=>	'Hidden'
				),
			),
		);
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo|Si prega di compilare il campo <i>Titolo caratteristica</i>");
		
		parent::__construct();

	}
	
	public function relations() {
        return array(
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_car', null, "CASCADE"),
        );
    }
    
    public function setFormStruct()
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'titolo'		=>	array(
					'labelString'=>	'Titolo caratteristica',
				),
				'id_car'	=>	array(
					'type'		=>	'Hidden'
				),
				'tipo'	=>	array(
					"type"	=>	"Select",
					"options"	=>	array(
						""			=>	"--",
						"MATERIALE"	=>	"Materiale",
					),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
		);
	}
	
	public function insert()
	{
		$res = parent::insert();
		
		if ($res)
			$this->controllaLingua($this->lId, "id_car");
		
		return $res;
	}
	
	public function del($id = null, $whereClause = null)
	{
		$clean["id"] = (int)$id;
		
		$a = new CaratteristichevaloriModel();
		$res = $a->clear()->where(array("id_car"=>$clean["id"]))->send();
		
		if (count($res) > 0)
		{
			$this->notice = "<div class='alert'>Questa caratteristica ha deli valori associati, si prega di cancellare prima tali valori</div>";
			$this->result = false;
		}
		else
		{
			return parent::del($clean["id"]);
		}
	}
	
	public function update($id = null, $where = null)
	{
		$res = parent::update($id, $where);
		
		if ($res)
			$this->controllalingua($id, "id_car");
		
		return $res;
	}
	
	// Controllo che la lingua esista
	public function controllaLingua($id)
	{
		$this->controllaLinguaGeneric($id, "id_car", "-car-");
	}
	
	public function linklingua($record, $lingua)
	{
		return $this->linklinguaGeneric($record["caratteristiche"]["id_car"], $lingua, "id_car");
	}
}
