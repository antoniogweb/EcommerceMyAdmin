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

require_once(LIBRARY."/Application/Modules/Captcha/Random.php");

class hCaptcha extends Captcha
{	
	public function check()
	{
		if (User::$logged)
			return true;
		
		if( !session_id() )
			session_start();
		
		if (isset($_SESSION["ok_captcha"]))
			return true;
		
		$random = new Random(array(
			"campo_nascosto"	=>	"codice_random",
		));
		
		if ($random->check())
			return true;
		
		$r = new Request();
		$campoCaptcha = $r->post($this->params["campo_nascosto"],'');
		
		$secret   = $this->params["secret_server"];
		
		$data = array(
            'secret' => $secret,
            'response' => $campoCaptcha
        );
        
		$verify = curl_init();
		curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
		curl_setopt($verify, CURLOPT_POST, true);
		curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($verify);
		// var_dump($response);
		$responseData = json_decode($response);
		
		if($responseData->success)
		{
			$_SESSION["ok_captcha"] = 1;
			
			return true;
		}
		else
			return false;
	}
	
	public function checkRegistrazione()
	{
		return $this->check();
	}
	
	public function getHiddenFieldIncludeFile()
	{
		return "/Elementi/Captcha/Html/hcaptcha.php";
	}
	
	public function getHiddenFieldRegistrazioneIncludeFile()
	{
		return "/Elementi/Captcha/Html/hcaptcha.php";
	}
	
	public function gCampiForm()
	{
		return 'titolo,attivo,secret_client,secret_server';
	}
	
	public function getErrorIncludeFile()
	{
		return "/Elementi/Captcha/Errore/notice-hcaptcha.php";
	}
}
