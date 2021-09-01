<?php
if (!defined('EG')) die('Direct access not allowed!');

class Tema
{
	public static function check($tema)
	{
		if ($tema)
		{
			$tema = basename($tema);
			
			$items = scandir(ROOT."/Application/Views/");
			
			foreach( $items as $this_file )
			{
				if(strcmp($this_file,".") !== 0 && strcmp($this_file,"..") !== 0 && strcmp($this_file,"_") !== 0)
				{
					if (@is_dir(ROOT."/Application/Views/$this_file") && (string)$tema === (string)$this_file && ctype_alnum($tema))
					{
						return true;
					}
				}
			}
		}
		
		return false;
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
