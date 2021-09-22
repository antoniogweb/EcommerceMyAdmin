<?php

if (!defined('EG')) die('Direct access not allowed!');

require_once(ROOT."/../Application/Include/import_contenuti.php");

class Import
{
	public static function prodotti()
	{
		ImportContenuti::prodotti();
	}
	
	public static function utenti()
	{
		ImportContenuti::utenti();
	}
	
	public static function news()
	{
		ImportContenuti::news();
	}
	
	public static function contenuti()
	{
		if (method_exists("ImportContenuti", "contenuti"))
			ImportContenuti::contenuti();
	}
}
