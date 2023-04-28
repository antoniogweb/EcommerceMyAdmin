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

require_once(LIBRARY."/Application/Modules/Captcha/Random.php");

class ReCaptchaTre extends Captcha
{	
	public function check()
	{
		if (User::$logged)
			return true;
		
		$random = new Random(array(
			"campo_nascosto"	=>	"codice_random",
		));
		
		if ($random->check())
			return true;
		
		$r = new Request();
		$campoCaptcha = $r->post($this->params["campo_nascosto"],'');
		
		$secret   = $this->params["secret_server"];
		$response = file_get_contents(
			"https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $campoCaptcha,
		);
		
		$response = json_decode($response, true);
		
	// 	print_r($response);
		return $response["success"] ? true : false;
	}
	
	public function checkRegistrazione()
	{
		return $this->check();
	}
	
	public function getHiddenFieldIncludeFile()
	{
		return "/Elementi/Captcha/Html/recaptchatre.php";
	}
	
	public function getHiddenFieldRegistrazioneIncludeFile()
	{
		return "/Elementi/Captcha/Html/recaptchatre.php";
	}
	
	public function gCampiForm()
	{
		return 'titolo,attivo,secret_client,secret_server';
	}
	
	public function getErrorIncludeFile()
	{
		return "/Elementi/Captcha/Errore/notice-recaptchatre.php";
	}
}
