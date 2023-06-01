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

class CacheController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "utenti";

	public function svuotacache()
	{
		$this->clean();
		
		if (defined("CACHE_FOLDER"))
		{
			Cache_Db::$cacheFolder = Domain::$parentRoot."/".CACHE_FOLDER;
			Cache_Db::$cacheMinutes = 0;
			Cache_Db::$cleanCacheEveryXMinutes = 0;
			Cache_Db::deleteExpired(true);
		}
	}
	
	public function svuotacacheimmagini()
	{
		$this->clean();
		
		if (v("attiva_cache_immagini"))
		{
			$dir = Domain::$parentRoot."/thumb";
			
			if (@is_dir($dir))
				GenericModel::eliminaCartella($dir);
			
			$dir = LIBRARY."/thumb";
			
			if (@is_dir($dir))
				GenericModel::eliminaCartella($dir);
		}
	}
	
	public function svuotacachetemplate()
	{
		$this->clean();
		
		if (defined("SAVE_CACHE_HTML"))
		{
			$dir = Domain::$parentRoot."/Logs";
			
			if (@is_dir($dir))
			{
				$tmpFolder = randomToken(20);
				
				if (@rename($dir."/cachehtml", $dir."/$tmpFolder"))
				{
					GenericModel::eliminaCartella($dir."/$tmpFolder");
				}
			}
		}
	}
	
	public function svuotacachemetodi()
	{
		$this->clean();
		
		if (defined("CACHE_METHODS_TO_FILE"))
		{
			$dir = Domain::$parentRoot."/Logs";
			
			if (@is_dir($dir))
			{
				$tmpFolder = randomToken(20);
				
				if (@rename($dir."/CacheMethods", $dir."/$tmpFolder"))
				{
					GenericModel::eliminaCartella($dir."/$tmpFolder");
				}
			}
		}
	}
}
