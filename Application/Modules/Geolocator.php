<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2025  Antonio Gallo (info@laboratoriolibero.com)
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

class Geolocator
{
	use Modulo;
	
	protected $dbFolder = "MMDB";
	protected $dbFolderExtracted = "Extracted";
	
	public function getDbPath()
	{
		$path = $this->cacheAbsolutePath."/".trim($this->params["codice"]);
		
		if (!@is_dir($path."/".$this->dbFolder))
		{
			createFolderFull($this->dbFolder, $path);
			@chmod($path."/".$this->dbFolder, octdec('777'));
		}
		
		if (!@is_dir($path."/".$this->dbFolder."/".$this->dbFolderExtracted))
		{
			createFolderFull($this->dbFolderExtracted, $path."/".$this->dbFolder);
			@chmod($path."/".$this->dbFolder."/".$this->dbFolderExtracted, octdec('777'));
		}
		
		return $path."/".$this->dbFolder;
	}
}
