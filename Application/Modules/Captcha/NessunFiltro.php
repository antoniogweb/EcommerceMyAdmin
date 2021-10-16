<?php

class NessunFiltro
{
	public function __construct($recordCaptcha)
	{
		
	}
	
	public function check()
	{
		return true;
	}
	
	public function checkRegistrazione()
	{
		return true;
	}
	
	public function getHiddenFieldIncludeFile()
	{
		return "/Elementi/Captcha/nessun-antispam.php";
	}
	
	public function getHiddenFieldRegistrazioneIncludeFile()
	{
		return "/Elementi/Captcha/nessun-antispam.php";
	}
}
