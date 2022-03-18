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

class AttributiModel extends GenericModel {

	public $lId = 0;
	
	public function __construct() {
		$this->_tables='attributi';
		$this->_idFields='id_a';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'attributi.titolo';
		$this->_lang = 'It';
		
		$this->traduzione = true;
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_a', null, "CASCADE"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'tipo'		=>	array(
					'type'		=>	'Select',
					'options'	=>	"TENDINA,RADIO,IMMAGINE",
					'reverse' => 'yes',
					
				),
			),
		);
	}
	
	public function del($id = null, $whereClause = null)
	{
		$clean["id"] = (int)$id;
		
		$a = new AttributivaloriModel();
		$res = $a->clear()->where(array("id_a"=>$clean["id"]))->send();
		
		if (count($res) > 0)
		{
			$this->notice = "<div class='alert'>Questo attributo ha deli valori associati, si prega di cancellare prima tali valori</div>";
			$this->result = false;
		}
		else
		{
			$attr = new PagesattributiModel();
			$res2 = $attr->clear()->select("distinct pages_attributi.id_page,pages.*")->inner("pages")->using("id_page")->where(array("id_a"=>$clean["id"]))->send();
			
			if (count($res2) > 0)
			{
				$this->notice = "<div class='alert'>Questo attributo è usato da alcuni prodotti e non può quindi essere cancellato se prima non viene dissociato da tali prodotti.<br />Elenco prodotti che usano tale attributo:<ul>";
				foreach ($res2 as $r)
				{
					$this->notice .= "<li><a target='_blank' href='http://".DOMAIN_NAME."/pages/attributi/".$r["pages"]["id_page"]."'>".$r["pages"]["title"]."</a></li>";
				}
				$this->notice .= "</ul></div>";
				
				$this->result = false;
			}
			else
			{
				return parent::del($clean["id"]);
			}
		}
	}
	
	public static function getTipo($idA)
	{
		$a = new AttributiModel();
		
		return $a->where(array(
			"id_a"	=>	(int)$idA,
		))->field("tipo");
	}
}
