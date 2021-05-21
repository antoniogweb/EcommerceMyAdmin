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

class CorrierispeseController extends BaseController {
	
	public $tabella = "scaglioni corrieri";
	
	public $argKeys = array('id_corriere:sanitizeAll'=>'tutti', 'nazione:sanitizeAll'=>'tutti', 'procedi:sanitizeAll'=>0);
	
	public function form($queryType = 'insert', $id = 0)
	{
		if (isset($_POST["procedi"]) && $_POST["procedi"] == 1)
		{
			$_GET["procedi"] = 1;
			
			if (isset($_POST["nazione"]))
				$_GET["nazione"] = $_POST["nazione"];
		}
		
		$this->shift(2);
		
		$this->formDefaultValues = $this->viewArgs;
		
		if ($queryType == "insert" && $this->viewArgs["nazione"] == "tutti")
			$fields = "nazione";
		else
		{
			$fields = "peso";
			
			if (v("prezzi_ivati_in_prodotti") && $this->viewArgs["nazione"] == "IT")
			{
				$fields .= ",prezzo_ivato";
				
				$this->m[$this->modelName]->addStrongCondition("both",'checkNotEmpty',"prezzo_ivato");
			}
			else
			{
				$fields .= ",prezzo";
				
				$this->m[$this->modelName]->addStrongCondition("both",'checkNotEmpty',"prezzo");
			}
		}
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if ($this->viewArgs["id_corriere"] != "tutti")
			$this->m[$this->modelName]->setValue("id_corriere", $this->viewArgs["id_corriere"]);
		
		if ($this->viewArgs["nazione"] != "tutti")
			$this->m[$this->modelName]->setValue("nazione", $this->viewArgs["nazione"]);
		
		parent::form($queryType, $id);
	}
}
