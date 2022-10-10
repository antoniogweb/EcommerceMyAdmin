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
	
	public function getClient()
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
	
	public function getInfoOrGoToLogin()
	{
		$this->getClient();

		$permissions = ['email']; // optional

		try {

			if (isset($_SESSION['fb_acc_tok'])) {

				$accessToken = $_SESSION['fb_acc_tok'];

			} else {

				$accessToken = $this->helper->getAccessToken();

			}

		} catch(Facebook\Exceptions\facebookResponseException $e) {

			// When Graph returns an error

			echo 'Graph returned an error: ' . $e->getMessage();

			exit;

		} catch(Facebook\Exceptions\FacebookSDKException $e) {

			// When validation fails or other local issues

			echo 'Facebook SDK returned an error: ' . $e->getMessage();

			exit;

		}

		if (isset($accessToken)) {

			if (isset($_SESSION['fb_acc_tok'])) {

				$this->client->setDefaultAccessToken($_SESSION['fb_acc_tok']);

			} else {

				$_SESSION['fb_acc_tok'] = (string) $accessToken;
				
				$authClient = $this->client->getOAuth2Client();

				$accessTokenPermanente = $authClient->getLongLivedAccessToken($_SESSION['fb_acc_tok']);

				$_SESSION['fb_acc_tok'] = (string) $accessTokenPermanente;

				$this->client->setDefaultAccessToken($_SESSION['fb_acc_tok']);

			}

			try {

				$profile_request = $this->client->get('/me?fields=name,first_name,last_name,email');

				$requestPicture = $this->client->get('/me/picture?redirect=false&height=200'); //getting user picture

				$picture = $requestPicture->getGraphUser();

				$profile = $profile_request->getGraphUser();

				$fbid = $profile->getProperty('id');           // To Get Facebook ID

				$fbfullname = $profile->getProperty('name');   // To Get Facebook full name

				$fbemail = $profile->getProperty('email');    //  To Get Facebook email
			

			} catch(Facebook\Exceptions\FacebookResponseException $e) {

				// When Graph returns an error

				echo 'Graph returned an error: ' . $e->getMessage();

				session_destroy();

				// redirecting user back to app login page

				header("Location: ./");

				exit;

			} catch(Facebook\Exceptions\FacebookSDKException $e) {

				// When validation fails or other local issues

				echo 'Facebook SDK returned an error: ' . $e->getMessage();

				exit;

			}

		} else {

			// replace your website URL same as added in the developers.Facebook.com/apps e.g. if you used http instead of https and you used            

			$loginUrl = $this->helper->getLoginUrl('http://lachiocciola/', $permissions);
			echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';

		}
	}
}
