<?php

class NessunFiltro
{
	private $params = "";
	
	public function __construct($recordCaptcha = null)
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
