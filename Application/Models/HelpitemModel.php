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

class HelpitemModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'help_item';
		$this->_idFields = 'id_help_item';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'help' => array("BELONGS_TO", 'HelpModel', 'id_help',null,"CASCADE"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'descrizione'		=>	array(
					'type'		 =>	'Textarea',
					'labelString'=>	'Descrizione',
					'className'		=>	'form-control editor_textarea',
				),
				'posizione'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Posizione',
					'options'	=>	array('top'=>'Sopra','bottom'=>'Sotto','left'=>'Sinistra','right'=>'Destra'),
					'reverse' => 'yes',
				),
			),
			
			'enctype'	=>	'',
		);
	}
	
    public function edit($record)
	{
		return "<a class='iframe action_iframe' href='".Url::getRoot()."helpitem/form/update/".$record["help_item"]["id_help_item"]."?partial=Y&nobuttons=N'>".$record["help_item"]["titolo"]."</a>";
	}
    
}
