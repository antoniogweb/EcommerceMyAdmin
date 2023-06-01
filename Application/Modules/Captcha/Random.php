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

class Random extends Captcha
{
	public function check()
	{
		if (User::$logged)
			return true;
		
		if( !session_id() )
			session_start();
			
		$r = new Request();
		$campoCaptcha = $r->post($this->params["campo_nascosto"],'');
		
		if (isset($_SESSION["ok_captcha"]))
			return true;
		
		if (!isset($_SESSION["captchaString"]))
			return false;
		
		$res = (string)$campoCaptcha === (string)$_SESSION["captchaString"] ? true : false;
		
		if ($res)
			$_SESSION["ok_captcha"] = 1;
		
		return $res;
	}
	
	public function checkRegistrazione()
	{
		return $this->check();
	}
	
	public function getHiddenFieldIncludeFile()
	{
		return "/Elementi/Captcha/campo-codice-random.php";
	}
	
	public function getHiddenFieldRegistrazioneIncludeFile()
	{
		return "/Elementi/Captcha/campo-codice-random.php";
	}
	
	public function gCampiForm()
	{
		return 'titolo,attivo';
	}
	
	public function getErrorIncludeFile()
	{
		return "/Elementi/Captcha/Errore/notice-random.php";
	}
}
