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

class PageslinkModel extends GenericModel {

	public $parentRootFolder;
	
	public function __construct() {
		$this->_tables='pages_link';
		$this->_idFields='id_page_link';
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo,url_link");
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'page' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
        );
    }
    
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'titolo'	=>	array(
					"labelString"	=>	"Testo link",
				),
			),
		);
	}
	
	public function titoloLink($record)
	{
		return "<a class='iframe action_iframe' href='".Url::getRoot()."pageslink/form/update/".$record["pages_link"]["id_page_link"]."?partial=Y'>".$record["pages_link"]["titolo"]."</a>";
	}
	
}
