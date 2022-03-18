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
			'utenti' => array("HAS_MANY", 'HelpuserModel', 'id_help', null, "CASCADE"),
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
			
			if (partial())
				$this->aWhere(array(
					"help_item.anche_vista_parziale"	=>	"Y",
				));
			
			if ($soloMaiVisti)
				$this->left("help_user")->on("help.id_help = help_user.id_help and help_user.id_user = ".User::$id)->sWhere("help_user.id_user is null");
			
			$res = $this->send();
			
// 			echo $this->getQuery();die();
			
			$arrayFInale = array();
			
			foreach ($res as $r)
			{
				// Controllo che sia attivo quel modulo
				$variabile = $r["help_item"]["variabile"];
				
				if ($variabile && !v($variabile))
					continue;
				
				$arrayFInale[] = $r;
			}
			
			return $arrayFInale;
		}
		
		return null;
    }
}
