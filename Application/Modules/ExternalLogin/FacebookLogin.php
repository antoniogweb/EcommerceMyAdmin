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

class FacebookLogin extends ExternalLogin
{
	private $params = "";
	private $client;
	private $helper;
	
	public function __construct($record)
	{
		$this->params = $record;
	}
	
	public function getParams()
	{
		return $this->params;
	}
		
	public function isAttiva()
	{
		if (trim($this->params["app_id"]) && trim($this->params["secret_key"]))
			return true;
		
		return false;
	}
	
	public function gCampiForm()
	{
		return 'titolo,attivo,app_id,secret_key,app_version';
	}
	
	private function getClient()
	{
		if( !session_id() )
			session_start();
		
		require_once(LIBRARY . '/External/libs/vendor/autoload.php');
		
		$this->client = new \Facebook\Facebook([
			'app_id' => $this->params["app_id"],
			'app_secret' => $this->params["secret_key"],
			'default_graph_version' => $this->params["app_version"],
			//'default_access_token' => '{access-token}', // optional
		]);
		
		$this->helper = $this->client->getRedirectLoginHelper();
	}
	
	private function setErrore($codice, $messaggio)
	{
		$this->infoUtente["codice_errore"] = $codice;
		$this->infoUtente["stringa_errore"] = $messaggio;
		
		if (isset($_SESSION["fat"]))
			unset($_SESSION["fat"]);
	}
	
	public function getInfoOrGoToLogin($redirectQueryString = "")
	{
		if (isset($_GET["error"]))
		{
			$this->infoUtente["codice_errore"] = sanitizeAll($_GET["error"]);
			$this->infoUtente["stringa_errore"] = isset($_GET["error_description"]) ? sanitizeAll($_GET["error_description"]) : "";
			$this->infoUtente["codice_errore_piattaforma"] = isset($_GET["error_code"]) ? sanitizeAll($_GET["error_code"]) : "";
			
			return;
		}
		
		$this->getClient();

		$permissions = ['email']; // optional

		try {
			
			if (isset($_SESSION["fat"]))
				$accessToken = $_SESSION["fat"];
			else
			{
				$accessToken = $this->helper->getAccessToken();
				$_SESSION["fat"] = $accessToken;
			}
		
		} catch(Facebook\Exceptions\facebookResponseException $e) {
			
			$this->setErrore("GRAPH", $e->getMessage());
			
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			
			$this->setErrore("SDK", $e->getMessage());
			
		}

		if (isset($accessToken)) {
			
			$this->client->setDefaultAccessToken((string)$accessToken);

			try {

				$profile_request = $this->client->get('/me?fields=name,first_name,last_name,email');

				$profile = $profile_request->getGraphUser();

				$fbid = $profile->getProperty('id');           // To Get Facebook ID

				$fbfullname = $profile->getProperty('name');   // To Get Facebook full name

				$fbemail = $profile->getProperty('email');    //  To Get Facebook email
				
				$this->infoUtente["redirect"] = true;
				$this->infoUtente["dati_utente"] = array(
					"external_id"		=>	(string)$fbid,
					"external_full_name"=>	(string)$fbfullname,
					"external_email"	=>	(string)$fbemail,
				);
				$this->infoUtente["result"] = 1;
				$this->infoUtente["utente_loggato"] = 1;

			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				
				$this->setErrore("GRAPH", $e->getMessage());

			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				
				$this->setErrore("SDK", $e->getMessage());

			}

		} else {
			if (!isset($_SESSION["test_login_effettuato"]))
			{
				$loginUrl = $this->helper->getLoginUrl(Url::getRoot()."regusers/loginapp/".$this->params["codice"].$redirectQueryString, $permissions);
				$this->infoUtente["redirect"] = 1;
				$this->infoUtente["login_redirect"] = $loginUrl;
				$this->infoUtente["result"] = 1;
				$_SESSION["test_login_effettuato"] = 1;
			}
			else
			{
				$this->infoUtente["test_login_effettuato"] = 1;
			}
		}
	}
}
