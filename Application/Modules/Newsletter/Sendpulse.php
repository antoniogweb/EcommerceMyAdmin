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

use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;

Files_Log::$logFolder = LIBRARY."/Logs";
Files_Log::$logPermission = 0644;

class Sendpulse extends Newsletter
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
		Files_Log::getInstance("sendpulse");
		
		require_once(LIBRARY . '/External/libs/vendor/autoload.php');
		
		// API credentials from https://login.sendpulse.com/settings/#api
// 		define('API_USER_ID', $this->params["secret_1"]);
// 		define('API_SECRET', $this->params["secret_2"]);
// 		define('PATH_TO_ATTACH_FILE', Files_Log::$logFolder);
		
		if (class_exists("Sendpulse\RestApi\ApiClient"))
		{
			$SPApiClient = new ApiClient($this->params["secret_1"], $this->params["secret_2"], new FileStorage(Files_Log::$logFolder));
			
			$variables = array(
				'name' => $valori["nome"]." ".$valori["cognome"],
				'origin' => $this->params["codice_fonte"],
			);
			
			if ($this->params["riempi_company"])
				$variables["company"] = $variables["name"];
			
			$bookID = $this->params["codice_lista"];
			$emails = array(
				array(
					'email' => $valori["email"],
					'variables' => $variables,
				)
			);
			
			return $SPApiClient->addEmails($bookID, $emails);
		}
		
		return null;
	}
	
	public function disiscrivi($email)
	{
		Files_Log::getInstance("sendpulse");
		
		require_once(LIBRARY . '/External/libs/vendor/autoload.php');
		
		if (class_exists("Sendpulse\RestApi\ApiClient"))
		{
			$SPApiClient = new ApiClient($this->params["secret_1"], $this->params["secret_2"], new FileStorage(Files_Log::$logFolder));
			
			return $SPApiClient->removeEmailFromAllBooks($email);
		}
		
		return null;
	}
	
	public function gCampiForm()
	{
		return 'titolo,attivo,secret_1,secret_2,codice_lista,codice_fonte,riempi_company';
	}
	
	public function isAttiva()
	{
		if (trim($this->params["secret_1"]) && trim($this->params["secret_2"]) && trim($this->params["codice_lista"]))
			return true;
		
		return false;
	}
	
	public function gSecret1Label()
	{
		return "ID";
	}
	
	public function gSecret2Label()
	{
		return "Secret";
	}
}
