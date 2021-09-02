<?php
if (!defined('EG')) die('Direct access not allowed!');

class Tema
{
	public static $elenco = array();
	
	public static function getRoot()
	{
		return Domain::$parentRoot."/Application/Views";
	}
	
	public static function check($tema)
	{
		if ($tema)
		{
			$tema = basename($tema);
			
			$items = scandir(self::getRoot());
			
			foreach( $items as $this_file )
			{
				if(strcmp($this_file,".") !== 0 && strcmp($this_file,"..") !== 0 && strcmp($this_file,"_") !== 0)
				{
					if (@is_dir(self::getRoot()."/$this_file") && (string)$tema === (string)$this_file && ctype_alnum($tema))
					{
						return true;
					}
				}
			}
		}
		
		return false;
	}
	
	public static function getElencoTemi()
	{
		$items = scandir(self::getRoot());
		
		foreach( $items as $this_file )
		{
			if(strcmp($this_file,".") !== 0 && strcmp($this_file,"..") !== 0 && strcmp($this_file,"_") !== 0)
			{
				if (@is_dir(self::getRoot()."/$this_file"))
				{
					self::$elenco[] = array(
						"nome"	=>	$this_file,
						"preview"	=>	file_exists(self::getRoot()."/$this_file/_Preview/preview.png") ? true : false,
					);
				}
			}
		}
		
		return self::$elenco;
	}
	
	public static function set()
	{
		if (v("piattaforma_di_demo"))
		{
			$tema = null;
			
			if (isset($_GET["demo_theme"]))
				$tema = $_GET["demo_theme"];
			else if (isset($_COOKIE["demo_theme"]))
				$tema = $_COOKIE["demo_theme"];
				
			if (self::check($tema))
			{
				VariabiliModel::$valori["theme_folder"] = $tema;
				
				$time = time() + 3600*24*365*10;
				setcookie("demo_theme",$tema,$time,"/");
			}
		}
		else
		{
			if (isset($_COOKIE["demo_theme"]))
				setcookie ("demo_theme", "", time() - 3600,"/");
		}
	}
}
