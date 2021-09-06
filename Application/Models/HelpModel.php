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

class HelpModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'help';
		$this->_idFields = 'id_help';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'elementi' => array("HAS_MANY", 'HelpitemModel', 'id_help', null, "CASCADE"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'mostra'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Mostra',
					'options'	=>	array(0=>'No',1=>'Sì'),
					'reverse' => 'yes',
				),
			),
			
			'enctype'	=>	'',
		);
	}
	
	public function daVedere($soloMaiVisti = true)
    {
		if (v("attiva_help_wizard"))
		{
			$in = array(sanitizeAll($this->controller."/".$this->action));
			
			if (count($this->_queryString) > 0)
				$in[] = sanitizeAll($this->controller."/".$this->action."/".$this->_queryString[0]);
			
			$this->clear()->select("*")->where(array(
				"in"	=>	array(
					"controlleraction"	=>	$in,
				),
			))->inner(array("elementi"))->orderBy("help_item.id_order");
			
			if ($soloMaiVisti)
				$this->aWhere(array(
					"mostra"	=>	1,
				));
			
			$res = $this->send();
			
// 			echo $this->getQuery();die();
			
			return $res;
		}
		
		return null;
    }
}
