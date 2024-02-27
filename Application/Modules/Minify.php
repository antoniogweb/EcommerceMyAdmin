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

// use MatthiasMullie\Minify;

if (!defined('EG')) die('Direct access not allowed!');

class Minify
{
	private static $instance = null; //instance of this class
	
	private static $filesToBeMinified = array(
		"LIBRARY"	=>	array(
			"Frontend/Public/Js"	=>	array(
				"folder"	=>	"Minified",
				"files"		=>	array(
						"cart.js",
						"cms.js",
						"cookies.js",
						"crud.js",
						"functions.js",
						"listeregalo.js",
						"rating.js",
						"promozioni.js",
						"ticket.js",
					),
				),
		),
	);
	
	private function __construct() {}
	
	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className();
		}

		return self::$instance;
	}
	
	public static function minify()
	{
		require_once(LIBRARY . '/External/libs/vendor/autoload.php');
		
		foreach (Minify::$filesToBeMinified as $type => $struct)
		{
			$absolutePath = $type == "LIBRARY" ? LIBRARY : str_replace("/".v("cartella_backend"), LIBRARY);
			
			foreach ($struct as $path => $elements)
			{
				$absolutePath .= "/".ltrim($path, "/");
				
				$folder = ltrim($elements["folder"],"/");
				
				if (@!is_dir($absolutePath."/$folder"))
				{
					createFolderFull($folder, $absolutePath, true, false);
				}
				
				if (@is_dir($absolutePath."/$folder"))
				{
					foreach ($elements["files"] as $file)
					{
						$file = ltrim($file, "/");
						$filePath = $absolutePath."/$file";
						
						if (@is_file($absolutePath."/$file"))
						{
							$path_parts = pathinfo($filePath);
							
							$minifier = new MatthiasMullie\Minify\JS($filePath);
							$minifier->minify($absolutePath."/$folder/".$path_parts["filename"].".min.".$path_parts["extension"]);
						}
					}
				}
			}
		}
		
	}
}
