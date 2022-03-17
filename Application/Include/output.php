<?php
if (!defined('EG')) die('Direct access not allowed!');

class Output
{
	public static $html = true;
	public static $json = false;
	
	public static $header = array(
		"Status"	=>	"not-logged",
		"UserId"	=>	"",
	);
	
	public static $body = array();
	public static $contents = array();
	
	public static function setJson()
	{
		self::$html = false;
		self::$json = true;
	}
	
	public static function setHeaderValue($key, $value)
	{
		self::$header[$key] = $value;
	}
	
	public static function setBodyValue($key, $value)
	{
		self::$body[$key] = $value;
	}
	
	public static function setContents()
	{
		self::$contents = array(
			"Header"	=>	self::$header,
			"Body"		=>	self::$body,
		);
	}
	
	public static function printOutput()
	{
		header('Content-Type: application/json');
		
		self::setContents();
		
		if (self::$json)
			return json_encode(self::$contents);
		
		return "";
	}
}
