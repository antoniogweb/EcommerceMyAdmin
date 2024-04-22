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

ini_set("display_errors", "Off");

class Sendinblue extends Newsletter
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
		require_once(LIBRARY . '/External/libs/vendor/autoload.php');
		
		if (class_exists("Brevo\Client\Configuration"))
		{
			$config = Brevo\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->params["secret_1"]);
			
			// Uncomment below line to configure authorization using: partner-key
			// $config = Brevo\Client\Configuration::getDefaultConfiguration()->setApiKey('partner-key', 'YOUR_API_KEY');

			$apiInstance = new Brevo\Client\Api\ContactsApi(
				// If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
				// This is optional, `GuzzleHttp\Client` will be used as default.
				new GuzzleHttp\Client(),
				$config
			);
			$createContact = new \Brevo\Client\Model\CreateContact(); // \Brevo\Client\Model\CreateContact | Values to create a contact

			$createContact['email'] = $valori["email"];
			$createContact['listIds'] = array((int)$this->params["codice_lista"]);
			$createContact['emailBlacklisted'] = false;
			$createContact['smsBlacklisted'] = false;
			$createContact['updateEnabled'] = false;

			try {
				$result = $apiInstance->createContact($createContact);
// 				print_r($result);
			} catch (Exception $e) {
// 				echo 'Exception when calling ContactsApi->createContact: ', $e->getMessage(), PHP_EOL;
			}
		}
		
		return null;
	}
	
	public function gCampiForm()
	{
		return 'titolo,attivo,secret_1,codice_lista';
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
