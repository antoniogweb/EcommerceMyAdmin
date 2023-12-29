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

class Mailchimp extends Newsletter
{
	private $params = "";
	
	public function __construct($record)
	{
		$this->params = $record;
	}
	
	public function getParams()
	{
		return $this->params;
	}
	
	public function setParam($nome, $valore)
	{
		$this->params[$nome] = $valore;
	}
	
	public function iscrivi($valori)
	{
		$dataMailChimp = array(
			"email"	=>	$valori["email"],
			"status"=>	"subscribed",
		);
		
		if (isset($valori["nome"]) && trim($valori["nome"]))
			$dataMailChimp["firstname"] = $valori["nome"];
		
		if (isset($valori["cognome"]) && trim($valori["cognome"]))
			$dataMailChimp["lastname"] = $valori["cognome"];
		
		if (isset($valori["azienda"]) && trim($valori["azienda"]))
			$dataMailChimp["company"] = $valori["azienda"];
		
		$dataMailChimp["mergeFields"] = [
            'FNAME'     => isset($dataMailChimp['firstname']) ? $dataMailChimp['firstname'] : "",
            'LNAME'     => isset($dataMailChimp['lastname']) ? $dataMailChimp['lastname'] : "",
        ];
		
		if ($this->params["campo_company"] && isset($dataMailChimp['company']))
			$dataMailChimp["mergeFields"][$this->params["campo_company"]] = $dataMailChimp['company'];
		
		$dataMailChimp["mergeFields"] = IntegrazioninewslettervariabiliModel::mergeCampiAggiuntivi($dataMailChimp["mergeFields"], $valori, $this->params["codice"]);
		
		syncMailchimpKeys($dataMailChimp, $this->params["secret_1"], $this->params["codice_lista"]);
	}
	
	public function gCampiForm()
	{
		return 'titolo,attivo,secret_1,codice_lista,campo_company';
	}
	
	public function isAttiva()
	{
		if (trim($this->params["secret_1"]) && trim($this->params["codice_lista"]))
			return true;
		
		return false;
	}
	
	public function gSecret1Label()
	{
		return "Api key";
	}
	
}
