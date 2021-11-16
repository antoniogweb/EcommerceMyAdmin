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
	
	public static function getSelectElementi($percorsoElemento, $mantieniEstensionePhp = true)
	{
		// Cerco i temi del tema di default
		$filesModel = new Files_Upload(LIBRARY."/Frontend/Application/Views/_/$percorsoElemento");
		
		$filesModel->listFiles();
		
		$fileCartella = $filesModel->getFiles();
		
		if (v("theme_folder"))
		{
			$path = Domain::$parentRoot."/Application/Views/".v("theme_folder")."/$percorsoElemento";
			
			if (@is_dir($path))
			{
				$filesModel->setBase($path);
				
				$filesModel->listFiles();
			
				$fileCartellaTema = $filesModel->getFiles();
				
				$fileCartella = array_merge($fileCartella, $fileCartellaTema);
			}
			
			$path = Domain::$parentRoot."/Application/Views/_/$percorsoElemento";
			
			if (@is_dir($path))
			{
				$filesModel->setBase($path);
				
				$filesModel->listFiles();
			
				$fileCartellaTema = $filesModel->getFiles();
				
				$fileCartella = array_merge($fileCartella, $fileCartellaTema);
			}
		}
		
		$fileCartella = array_unique($fileCartella);
		
		$arraySelect = array();
		
		foreach ($fileCartella as $f)
		{
			$backFile = $f;
			
			$temp = explode(".", $f);
			
			if (count($temp) > 1)
				array_pop($temp);
			
			if (!$mantieniEstensionePhp)
				$backFile = implode(".",$temp);
			
			$arraySelect[$backFile] = ucfirst(implode(" ",explode("_",implode(".",$temp))));
		}
		
		return $arraySelect;
	}
}
