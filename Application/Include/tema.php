<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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

if (!defined('EG')) die('Direct access not allowed!');

class Tema
{
	public static $noCacheForChildren = false;
	public static $staticCachable = null;
	
	public static $elenco = array();
	
	public static $nomeTemaStartEmpty = "__EMPTY__";
	
	public static function getRoot()
	{
		return Domain::$parentRoot."/Application/Views";
	}
	
	public static function check($tema)
	{
		if ($tema)
		{
			$tema = basename($tema);
			
			$temi = self::getElencoTemi($tema);
			
			if (count($temi) > 0)
				return true;
		}
		
		return false;
	}
	
	// Ritorna il JSON del file layout.json nella cartella del tema
	public static function getJsonLayout($tema)
	{
		$percorso = self::getElencoTemi($tema, true);
		
		if (count($percorso) > 0)
		{
			$path = $percorso[0]["path"];
			
			if (file_exists($path."/layout.json"))
				return file_get_contents($path."/layout.json");
		}
		
		return null;
	}
	
	// Crea un nuovo tema
	public static function crea($tema)
	{
		$tema = encodeUrl(basename($tema));
		
		$percorso = self::getElencoTemi($tema, true);
		
		$nomeTemaVuoto = self::$nomeTemaStartEmpty;
		
		if ((int)count($percorso) === 0 && file_exists(LIBRARY."/Frontend/media/Temi/$nomeTemaVuoto.zip"))
		{
			$pathFinale = Domain::$parentRoot."/Application/Views";
			
			if (copy(LIBRARY."/Frontend/media/Temi/$nomeTemaVuoto.zip", $pathFinale."/$nomeTemaVuoto.zip"))
			{
				$zip = new ZipArchive;
				
				if (file_exists($pathFinale."/$nomeTemaVuoto.zip") && $zip->open($pathFinale."/$nomeTemaVuoto.zip") === TRUE) {
					$zip->extractTo($pathFinale);
					$zip->close();
				}
				
				rename($pathFinale."/__EMPTY__", $pathFinale."/$tema");
				
				@unlink($pathFinale."/$nomeTemaVuoto.zip");
			}
		}
	}
	
	public static function getElencoTemi($tema = null, $empty = false)
	{
		if ($empty)
			self::$elenco = array();
		
		$arrayPercorsi = array(
			LIBRARY."/Frontend/Application/Views" =>	array(
				"frontend"	=>	Domain::$adminName . "/Frontend/Application/Views",
			),
			self::getRoot()	=>	array(
				"frontend"	=>	Domain::$publicUrl . "/Application/Views",
			),
		);
		
		foreach ($arrayPercorsi as $path => $valori)
		{
			$items = scandir($path);
		
			foreach( $items as $this_file )
			{
				if(strcmp($this_file,".") !== 0 && strcmp($this_file,"..") !== 0 && strcmp($this_file,"_") !== 0 && strpos($this_file, "__") === false)
				{
					if (@is_dir($path."/$this_file"))
					{
						if (!$tema || ((string)$tema === (string)$this_file && ctype_alnum($tema)))
						{
							$previewUrl = $valori["frontend"] . "/$this_file/_Preview/preview.png";
							
							self::$elenco[] = array(
								"nome"	=>	$this_file,
								"preview"	=>	file_exists($path."/$this_file/_Preview/preview.png") ? true : false,
								"preview_url"	=>	$previewUrl,
								"path"	=>	$path."/$this_file",
							);
						}
					}
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
		$path = LIBRARY."/Frontend/Application/Views/_/$percorsoElemento";
		
		if (@is_dir($path))
		{
			$filesModel = new Files_Upload($path);
			
			$filesModel->listFiles();
			
			$fileCartella = $filesModel->getFiles();
		}
		else
			$fileCartella = array();
			
		if (v("theme_folder"))
		{
			$path = Domain::$parentRoot."/Application/Views/".v("theme_folder")."/$percorsoElemento";
			
			$filesModel = new Files_Upload($path);
			
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
			$pos = strpos($f, "___");
			
			if ($pos === false)
			{
				$backFile = $f;
				
				$temp = explode(".", $f);
				
				if (count($temp) > 1)
					array_pop($temp);
				
				if (!$mantieniEstensionePhp)
					$backFile = implode(".",$temp);
				
				$arraySelect[$backFile] = ucfirst(implode(" ",explode("_",implode(".",$temp))));
			}
		}
		
		return $arraySelect;
	}
	
	public static function tpfc($filePath = "", $public = false, $cachable = true, $stringaCache = "", $cachedTemplateFile = false)
	{
		return Tema::tpf($filePath, $public, $cachable, $stringaCache, $cachedTemplateFile);
	}
	
// 	public static function tpf($filePath = "", $public = false, $cachable = true, $stringaCache = "", $cachedTemplateFile = false)
// 	{
// 		if (isset(Tema::$staticCachable))
// 			$cachable = Tema::$staticCachable;
// 		
// 		if (Tema::$noCacheForChildren)
// 			Tema::$staticCachable = true;
// 		
// 		$cache = Cache_Html::getInstance();
// 	
// 		$themeFolder = v("theme_folder");
// 		
// 		$subfolder = $themeFolder ? DS . $themeFolder : "";
// 		
// 		$subFolderFullPath = Domain::$parentRoot."/Application/Views$subfolder"."/".ltrim($filePath,"/");
// 		$subFolderFullPathPublic = Domain::$publicUrl."/Application/Views$subfolder"."/".ltrim($filePath,"/");
// 		
// 		if (file_exists($subFolderFullPath))
// 			return $public ? $subFolderFullPathPublic : $cache->saveDynamic($subFolderFullPath, $cachable, $stringaCache, $cachedTemplateFile);
// 		
// 		if ($themeFolder)
// 		{
// 			$subFolderFullPathParentFrontend = Domain::$parentRoot."/Application/Views/_/".ltrim($filePath,"/");
// 			$subFolderFullPathParentFrontendPublic = Domain::$publicUrl."/Application/Views/_/".ltrim($filePath,"/");
// 			
// 			if (file_exists($subFolderFullPathParentFrontend))
// 				return $public ? $subFolderFullPathParentFrontendPublic : $cache->saveDynamic($subFolderFullPathParentFrontend, $cachable, $stringaCache, $cachedTemplateFile);
// 		}
// 		
// 		if ($public)
// 			return Domain::$publicUrl."/admin/Frontend/Application/Views/_/".ltrim($filePath,"/");
// 		else
// 			return $cache->saveDynamic(Domain::$parentRoot."/admin/Frontend/Application/Views/_/".ltrim($filePath,"/"), $cachable, $stringaCache, $cachedTemplateFile);
// 	}
	
	public static function tpf($filePath = "", $public = false, $cachable = true, $stringaCache = "", $cachedTemplateFile = false)
	{
// 		$timer = Factory_Timer::getInstance();
		
// 		$timer->startTime("TPF","TPF");
		
		if (isset(Tema::$staticCachable))
			$cachable = Tema::$staticCachable;
		
		if (Tema::$noCacheForChildren)
			Tema::$staticCachable = true;
		
		$cache = Cache_Html::getInstance();
	
		$themeFolder = v("theme_folder");
		
		$subfolder = $themeFolder ? DS . $themeFolder : "";
		
		$subFolderFullPath = Domain::$parentRoot."/Application/Views$subfolder"."/".ltrim($filePath,"/");
		$subFolderFullPathPublic = Domain::$publicUrl."/Application/Views$subfolder"."/".ltrim($filePath,"/");
		
		$subFolderFullPathParentFrontend = Domain::$parentRoot."/Application/Views/_/".ltrim($filePath,"/");
		$subFolderFullPathParentFrontendPublic = Domain::$publicUrl."/Application/Views/_/".ltrim($filePath,"/");
		
		if (file_exists($subFolderFullPath))
			$finalPath = $public ? $subFolderFullPathPublic : $cache->saveDynamic($subFolderFullPath, $cachable, $stringaCache, $cachedTemplateFile);
		else if ($themeFolder && file_exists($subFolderFullPathParentFrontend))
			$finalPath = $public ? $subFolderFullPathParentFrontendPublic : $cache->saveDynamic($subFolderFullPathParentFrontend, $cachable, $stringaCache, $cachedTemplateFile);
		else 
			$finalPath = $public ? Domain::$publicUrl."/admin/Frontend/Application/Views/_/".ltrim($filePath,"/") : $cache->saveDynamic(Domain::$parentRoot."/admin/Frontend/Application/Views/_/".ltrim($filePath,"/"), $cachable, $stringaCache, $cachedTemplateFile);
		
// 		$timer->endTime("TPF","TPF");
		
		return $finalPath;
	}
	
	public static function resetStaticCachable()
	{
		Tema::$noCacheForChildren = false;
		Tema::$staticCachable = null;
	}
	
	public static function getParamsPath()
	{
		$path = FRONT . "/Application/Layout/".v("theme_folder")."/layout.php";
		
		if (!file_exists($path))
		{
			$path = FRONT . "/Application/Views/".v("theme_folder")."/layout.php";
			
			if (!file_exists($path))
				$path = LIBRARY . "/Frontend/Application/Views/_/layout.php";
		}
		
		return $path;
	}
}
