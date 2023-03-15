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

class ScaglioniModel extends GenericModel {

	public function __construct() {
		$this->_tables='scaglioni';
		$this->_idFields='id_scaglione';
		
		$this->_lang = 'It';

		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'quantita'		=>	array(
					'labelString'=>	'Quantità',
				),
				'sconto'		=>	array(
					'labelString'=>	'Sconto (%)',
				),
			),
		);
		
		$this->addStrongCondition("both",'checkIsNotStrings|0',"quantita,sconto");
		
		parent::__construct();
	}
	
	public function getSconto($idPage, $qty)
	{
		$res = $this->clear()->select("sconto")->where(array(
			"id_page"	=>	(int)$idPage,
			"lte"	=>	array("quantita" => (int)$qty)
		))->orderBy("quantita desc")->limit(1)->toList("sconto")->send();
		
		if (count($res))
			return (float)$res[0];
		
		return 0;
	}
	
// 	//duplica gli scaglioni
// 	public function duplica($from_id, $to_id)
// 	{
// 		$clean["from_id"] = (int)$from_id;
// 		$clean["to_id"] = (int)$to_id;
// 		
// 		$res = $this->clear()->where(array("id_page"=>$clean["from_id"]))->send(false);
// 		
// 		foreach ($res as $r)
// 		{
// 			$this->setValues($r, "sanitizeDb");
// 			$this->setValue("id_page", $to_id);
// 			
// 			unset($this->values["id_scaglione"]);
// 			
// 			$this->insert();
// 		}
// 	}
	
}
