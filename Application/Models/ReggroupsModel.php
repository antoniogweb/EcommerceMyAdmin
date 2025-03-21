<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2025  Antonio Gallo (info@laboratoriolibero.com)
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

class ReggroupsModel extends GenericModel {
	
	public $campoTitolo = "name";
	
	public function __construct() {
		$this->_tables='reggroups';
		$this->_idFields='id_group';
		
		$this->_lang = 'It';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'clienti' => array("HAS_MANY", 'RegusersgroupsModel', 'id_group', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
			'categorie' => array("HAS_MANY", 'ReggroupscategoriesModel', 'id_group', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
			'contenuti' => array("HAS_MANY", 'ReggroupscontenutiModel', 'id_group', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
			'documenti' => array("HAS_MANY", 'ReggroupsdocumentiModel', 'id_group', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
			'tipi' => array("HAS_MANY", 'ReggroupstipiModel', 'id_group', null, "CASCADE"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'sincronizza_newsletter'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Sincronizza con la newsletter?",
					"options"	=>	self::$attivoNoSi,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'entryClass'  => 'form_input_text',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se impostato su sì, ogni volta che il cliente verrà aggiunto o rimosso dal gruppo, verrà contemporaneamente iscritto o disiscritto dalla newsletter.")."<br />".gtext("Importante: solo se il collegamento con la newsletter lo permette.")."</div>"
					),
				),
			),
		);
		
// 		$this->formStruct["entries"] = $this->formStruct["entries"] + $this->getLinkEntries();
	}
	
}
