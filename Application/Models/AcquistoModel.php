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

trait AcquistoModel
{
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'numero_civico'	=>	array(
					'labelString'	=>	'N°',
				),
				'p_iva'	=>	array(
					'labelString'	=>	'P. IVA',
				),
				'nazione'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectNazione(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
		);
	}
	
	public static function getWhereClauseRicercaLibera($search, $table = "")
	{
		$tokens = explode(" ", $search);
		$andArray = array();
		$iCerca = 8;
		
		foreach ($tokens as $token)
		{
			$andArray[str_repeat(" ", $iCerca)."lk"] = array(
				"n!concat(".$table."ragione_sociale,' ',".$table."email,' ',".$table."p_iva,' ',".$table."telefono,' ',".$table."referente)"	=>	sanitizeAll(htmlentitydecode($token)),
			);
			
			$iCerca++;
		}
		
		return $andArray;
	}
}
