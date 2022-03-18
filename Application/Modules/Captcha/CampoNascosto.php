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

class CampoNascosto extends Captcha
{
	public function check()
	{
		$r = new Request();
		$campoCaptcha = $r->post($this->params["campo_nascosto"],'');
		
		return strcmp($campoCaptcha,'') === 0 ? true : false;
	}
	
	public function checkRegistrazione()
	{
		$r = new Request();
		$campoCaptcha = $r->post($this->params["campo_nascosto_registrazione"],'');
		
		return strcmp($campoCaptcha,'') === 0 ? true : false;
	}
	
	public function getHiddenFieldIncludeFile()
	{
		return "/Elementi/Captcha/campo-nascosto.php";
	}
	
	public function getHiddenFieldRegistrazioneIncludeFile()
	{
		return "/Elementi/Captcha/campo-nascosto-registrazione.php";
	}
	
	public function gCampiForm()
	{
		return 'titolo,attivo,campo_nascosto,campo_nascosto_registrazione';
	}
}
