<?php

class CampoNascosto
{
	private $params = "";
	
	public function __construct($recordCaptcha)
	{
		$this->params = $recordCaptcha;
	}
	
	public function getParams()
	{
		return $this->params;
	}
	
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
		return "campo-nascosto.php";
	}
	
	public function getHiddenFieldRegistrazioneIncludeFile()
	{
		return "campo-nascosto-registrazione.php";
	}
}
