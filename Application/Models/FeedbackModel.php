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

class FeedbackModel extends GenericModel {
	
	public $campoTitolo = "autore";
	
	public function __construct() {
		$this->_tables='feedback';
		$this->_idFields='id_feedback';
		
		$this->_idOrder='id_order';
		
		parent::__construct();
	}
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'attivo'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Pubblicato',
					'options'	=>	array('1' => 'sì','0' => 'no'),
					"reverse"	=>	"yes",
				),
			),
		);
	}
	
	public function relations() {
        return array(
			'pagina' => array("BELONGS_TO", 'PagineModel', 'id_page',null,"CASCADE","Si prega di selezionare la pagina"),
        );
    }
	
	public function dataora($record)
	{
		return date("d/m/Y H:i", strtotime($record["feedback"]["data_creazione"]));
	}
	
	public function attivo($record)
	{
		return $record["feedback"]["attivo"] ? "Sì" : "No";
	}
	
	public function edit($record)
	{
		return "<a class='iframe action_iframe' href='".Url::getRoot()."feedback/form/update/".$record["feedback"]["id_feedback"]."?partial=Y&nobuttons=Y'>".$record["feedback"]["autore"]."</a>";
	}
	
}
