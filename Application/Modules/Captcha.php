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

if (!defined('EG')) die('Direct access not allowed!');

class Captcha
{
	protected $IDCaptcha = 1;
	
	protected $usato = false;
	
	protected $params = "";
	
	public function __construct($recordCaptcha)
	{
		if( !session_id() )
			session_start();
		
		$this->params = $recordCaptcha;
	}
	
	public function getParams()
	{
		return $this->params;
	}
	
	public function setUsato()
	{
		$this->usato = true;
	}
	
	public function inPage()
	{
		return $this->usato;
	}
	
	public function pathJs()
	{
		if (isset($this->params["modulo"]) && file_exists(tpf("/Elementi/Captcha/Js/".strtolower($this->params["modulo"]).".php")))
			return tpf("/Elementi/Captcha/Js/".strtolower($this->params["modulo"]).".php");
		
		return "";
	}
	
	public function getErrorIncludeFile()
	{
		return "/Elementi/Captcha/Errore/notice.php";
	}
	
	public function getIDCaptcha()
	{
		return $this->IDCaptcha;
	}
	
	public function incrementaIDCaptcha()
	{
		return $this->IDCaptcha++;
	}
}
