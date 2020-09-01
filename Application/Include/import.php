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
}
