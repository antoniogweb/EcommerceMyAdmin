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

class TemplateemailModel extends BasicsectionModel {
	
	public $hModelName = "TemplateemailcatModel";
	
	public function overrideFormStruct()
	{
		$this->formStruct["entries"]['title'] = array(
			'labelString'=>	'Oggetto email',
			'entryClass'	=>	'form_input_text help_titolo',
		);
		
		$this->formStruct["entries"]['description'] = array(
			'type'		 =>	'Textarea',
			'entryClass'	=>	'form_textarea help_descrizione',
			'labelString'=>	'Corpo mail',
			'className'		=>	'dettagli',
		);
		
		$this->formStruct["entries"]['attivo'] = array(
			'type'		=>	'Select',
			'labelString'=>	'Attivo?',
			'entryClass'	=>	'form_input_text help_attivo',
			'options'	=>	array('sì'=>'Y','no'=>'N'),
			'wrap'		=>	array(
				null,
				null,
				"<div class='form_notice'>".gtext("Se può essere utilizzata dal sistema")."</div>"
			),
		);
	}
}