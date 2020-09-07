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

class TagModel extends GenericModel {

	public function __construct() {
		$this->_tables='tag';
		$this->_idFields='id_tag';
		
		$this->traduzione = true;
		
		$this->_lang = 'It';
		
		$this->_idOrder = 'id_order';
		
		$this->traduzione = true;
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		parent::__construct();
	}
	
// 	public function relations() {
//         return array(
// 			'prezzi' => array("HAS_MANY", 'PagesModel', 'id_marchio', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
// 			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_marchio', null, "CASCADE"),
//         );
//     }
    
    public function setFormStruct()
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'alias'	=>	array(
					'labelString'=>	'Alias per URL',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>Indicazione del tag nell'URL (se lasciato vuoto viene creato in automatico)</div>"
					),
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
    public function update($id = NULL, $whereClause = NULL)
	{
		if ($this->upload("update"))
		{
			$this->alias($id);
			
			return parent::update($id, $whereClause);
		}
	}
	
	public function insert()
	{
		if ($this->upload("insert"))
		{
			$this->alias();
			
			return parent::insert();
		}
	}
	
// 	public function getUrlAlias($id)
// 	{
// 		$c = new CategoriesModel;
// 		
// 		$idShop = $c->getShopCategoryId();
// 		
// 		$marchio = $this->clear()->where(array(
// 			"id_marchio"	=>	(int)$id,
// 		))->addJoinTraduzione()->send();
// 		
// // 		$marchio = $this->selectId((int)$id);
// 		
// 		if (count($marchio) > 0)
// 		{
// 			return mfield($marchio[0],"alias")."/".getCategoryUrlAlias($idShop);
// 		}
// 		
// 		return "";
// 	}
}
