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

ini_set("display_errors","Off");

class FacebookLogin extends ExternalLogin
{
	private $params = "";
	private $client;
	private $helper;
	private $instagramApiUrl = "https://api.instagram.com";
	private $instagramGraphUrl = "https://graph.instagram.com";
	
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
		return 'titolo,attivo,app_id,secret_key,app_version,access_token,instagram_app_id,instagram_secret_key,instagram_access_token,instagram_user_id';
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
	
	public function getInstagramAutorizeUrl($redirectUri)
	{
		return $this->instagramApiUrl.'/oauth/authorize?client_id='.$this->params["instagram_app_id"].'&redirect_uri='.$redirectUri.'&scope=user_profile,user_media&response_type=code';
	}
	
	public function ottieniInstagramAccessToken($code, $redirectUri)
	{
		$pars=array(
			'client_id' => $this->params["instagram_app_id"],
			'client_secret' => $this->params["instagram_secret_key"],
			'grant_type' => 'authorization_code',
			'redirect_uri' => $redirectUri,
			'code' => $code,
		);

		//step1
		$curlSES=curl_init(); 
		//step2
		curl_setopt($curlSES,CURLOPT_URL,$this->instagramApiUrl."/oauth/access_token");
		curl_setopt($curlSES,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curlSES,CURLOPT_HEADER, false); 
		curl_setopt($curlSES, CURLOPT_POST, true);
		curl_setopt($curlSES, CURLOPT_POSTFIELDS,http_build_query($pars));
		curl_setopt($curlSES, CURLOPT_CONNECTTIMEOUT,10);
		curl_setopt($curlSES, CURLOPT_TIMEOUT,30);
		//step3
		$result=curl_exec($curlSES);
		//step4
		curl_close($curlSES);
		//step5
		
		$resultArray = json_decode($result, true);
		
		if (isset($resultArray["access_token"]))
		{
			$urlExchange = "https://graph.instagram.com/access_token?grant_type=ig_exchange_token&client_secret=".$this->params["instagram_secret_key"]."&access_token=".$resultArray["access_token"];
			
			$resultExchange = file_get_contents($urlExchange);
			
			$resultExchangeArray = json_decode($resultExchange, true);
			
			if (isset($resultExchangeArray["access_token"]))
			{
				$resultArray["access_token"] = $resultExchangeArray["access_token"];
				
				return $resultArray;
			}
		}
		
		return array();
	}
	
	// Rinnova l'access token
	public function refreshInstagramAccessToken()
	{
		if ($this->params["instagram_access_token"])
		{
			$urlRenew = $this->instagramGraphUrl."/refresh_access_token?grant_type=ig_refresh_token&access_token=".$this->params["instagram_access_token"];
			
			$resultRenew = file_get_contents($urlRenew);
			
			return json_decode($resultRenew, true);
		}
	}
	
	private function setErrore($codice, $messaggio)
	{
		$this->infoUtente["codice_errore"] = $codice;
		$this->infoUtente["stringa_errore"] = $messaggio;
		$this->infoUtente["result"] = 0;
		
		if (isset($_SESSION["access_token"]))
			unset($_SESSION["access_token"]);
	}
	
	public function resetSessionVariables()
	{
		if (isset($_SESSION["test_login_effettuato"]))
			unset($_SESSION["test_login_effettuato"]);
		
		if (isset($_SESSION["access_token"]))
			unset($_SESSION["access_token"]);
	}
	
	public function getInfoOrGoToLogin($redirectQueryString = "", $redirectUrl = "")
	{
		if (isset($_GET["error"]) || isset($_GET["error_description"]) || isset($_GET["error_code"]))
		{
			$this->infoUtente["codice_errore"] = sanitizeAll($_GET["error"]);
			$this->infoUtente["stringa_errore"] = isset($_GET["error_description"]) ? sanitizeAll($_GET["error_description"]) : "";
			$this->infoUtente["codice_errore_piattaforma"] = isset($_GET["error_code"]) ? sanitizeAll($_GET["error_code"]) : "";
			$this->infoUtente["result"] = 0;
			
			return;
		}
		
		$this->getClient();

		$permissions = ['email']; // optional

		try {
			
			if (isset($_SESSION["access_token"]))
				$accessToken = $_SESSION["access_token"];
			else
			{
				$accessToken = $this->helper->getAccessToken();
// 				$_SESSION["access_token"] = $accessToken;
			}
		
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			
			$this->setErrore("GRAPH", $e->getMessage());
			
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			
			$this->setErrore("SDK", $e->getMessage());
			
		}

		if (isset($accessToken)) {
			
			if (isset($_SESSION["access_token"]))
				$this->client->setDefaultAccessToken((string)$_SESSION["access_token"]);
			else
			{
				// getting short-lived access token

				$_SESSION['access_token'] = (string) $accessToken;

				// OAuth 2.0 client handler

				$oAuth2Client = $this->client->getOAuth2Client();

				// Exchanges a short-lived access token for a long-lived one

				$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['access_token']);

				$_SESSION['access_token'] = (string) $longLivedAccessToken;

				// setting default access token to be used in script

				$this->client->setDefaultAccessToken($_SESSION['access_token']);
			}
			
			try {

				$profile_request = $this->client->get('/me?fields=name,first_name,last_name,email');

				$profile = $profile_request->getGraphUser();

// 				$fbid = $profile->getProperty('id');           // To Get Facebook ID
// 				$fbfullname = $profile->getProperty('name');   // To Get Facebook full name
// 				$fbemail = $profile->getProperty('email');    //  To Get Facebook email
				
				$fbid = $profile->getId();
				$fbfullname = $profile->getName();
				$fbemail = $profile->getEmail();
				
				$this->infoUtente["redirect"] = true;
				$this->infoUtente["dati_utente"] = array(
					"external_id"		=>	(string)$fbid,
					"external_full_name"=>	(string)$fbfullname,
					"external_email"	=>	(string)$fbemail,
				);
				$this->infoUtente["result"] = 1;
				$this->infoUtente["utente_loggato"] = 1;
				$this->infoUtente["access_token"] = (string)$accessToken;

			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				
				$this->setErrore("GRAPH", $e->getMessage());

			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				
				$this->setErrore("SDK", $e->getMessage());

			}

		} else {
			if (!isset($_SESSION["test_login_effettuato"]))
			{
				if (!$redirectUrl)
					$redirectUrl = Url::getRoot()."regusers/loginapp/".$this->params["codice"].$redirectQueryString;
				
				$loginUrl = $this->helper->getLoginUrl($redirectUrl, $permissions);
				$this->infoUtente["redirect"] = 1;
				$this->infoUtente["login_redirect"] = $loginUrl;
				$this->infoUtente["result"] = 1;
				$_SESSION["test_login_effettuato"] = 1;
			}
			else
			{
				$this->infoUtente["test_login_effettuato"] = 1;
				$this->setErrore("TEST_LOGIN_EFFETTUATO", "test login effettuato");
			}
		}
	}
	
	public function deleteAccountCallback($userModel, $urlOutput)
	{
		header('Content-Type: application/json');
		
		$data = array(
			'url' => Url::getRoot(),
			'confirmation_code' => "USER NOT FOUND",
		);
		
		if (isset($_POST['signed_request']))
		{
			$signed_request = $_POST['signed_request'];
			$data = $this->parseSignedRequest($signed_request);
			$user_id = isset($data['user_id']) ? $data['user_id'] : null;
			
			if (isset($user_id))
			{
				$idUserCms = $userModel->getIdUtenteDaIdApp($this->params["codice"], $user_id);
				
				if ($idUserCms)
				{
					// Start data deletion
					$tokenEliminazione = $userModel->deleteAccount($idUserCms, $this->params["codice"]);
					
					$data = array(
						'url' => Url::getRoot().$urlOutput.$tokenEliminazione,
						'confirmation_code' => $tokenEliminazione,
					);
				}
			}
		}
		
		echo json_encode($data);
	}
	
	private function parseSignedRequest($signed_request) {
		list($encoded_sig, $payload) = explode('.', $signed_request, 2);

		$secret = $this->params["secret_key"]; // Use your app secret here

		// decode the data
		$sig = $this->base64UrlDecode($encoded_sig);
		$data = json_decode($this->base64UrlDecode($payload), true);

		// confirm the signature
		$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
		if ($sig !== $expected_sig) {
			error_log('Bad Signed JSON signature!');
			return null;
		}

		return $data;
	}
	
	private function base64UrlDecode($input) {
		return base64_decode(strtr($input, '-_', '+/'));
	}
}
