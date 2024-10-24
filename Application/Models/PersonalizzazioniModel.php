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

class PersonalizzazioniModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'personalizzazioni';
		$this->_idFields = 'id_pers';
		
		$this->traduzione = true;
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'pages' => array("HAS_MANY", 'PagespersonalizzazioniModel', 'id_pers', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_pers', null, "CASCADE"),
        );
    }
    
    public function getStringa($struct, $char = "<br />", $json = false, $backend = false)
	{
		if (is_array($struct))
		{
			$stringArray = array();
			$jsonArray = array();
			
			foreach ($struct as $str)
			{
				if (isset($str["id"]) && isset($str["val"]))
				{
					$personalizzazione = $this->clear()->where(array(
						"id_pers"	=>	(int)$str["id"],
					))->addJoinTraduzione()->first();
					
					if (!empty($personalizzazione))
					{
						$str["val"] = mb_substr($str["val"], 0, $personalizzazione["personalizzazioni"]["numero_caratteri"]);
						
						$template = v("template_personalizzazione");
						
						if ($template)
						{
							$testo = str_replace("[NOME]",persfield($personalizzazione, "titolo"),$template);
							$testo = str_replace("[VALORE]",sanitizeHtml($str["val"]),$testo);
						}
						else
							$testo = "<span class='stringa_personalizzazioni_title'>".persfield($personalizzazione, "titolo").": </span><span class='stringa_personalizzazioni_value'><b>".sanitizeHtml($str["val"])."</b></span> ";
						
						$stringArray[] = $testo;
				
						$jsonArray[] = array(
							"col"	=>	(int)$str["id"],
							"val"	=>	sanitizeHtml($str["val"]),
						);
					}
				}
			}
			
			if ($json)
				return json_encode($jsonArray);
			else
				return implode($char, $stringArray);
		}
		
		if ($json)
			return json_encode(array());
		else
			return "";
	}
}
