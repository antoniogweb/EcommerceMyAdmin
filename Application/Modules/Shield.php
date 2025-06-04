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

class Shield
{
	public static $freedAfterSeconds = 3600;
	
	public static function createLogFolders()
	{
		createFolderFull("Logs/Jail", ROOT);
		createFolderFull("Logs/Jail/Temp", ROOT);
		createFolderFull("Logs/Jail/Perm", ROOT);
		createFolderFull("Logs/Jail/Log", ROOT);
		createFolderFull("Logs/Jail/Freed", ROOT);
	}
	
	public static function writeIp($ip, $query = "--", $secondi = "--")
	{
		$pathJail = ROOT."/Logs/Jail/";
		
		$content = date("Y-m-d H:i:s")."\nQuery:$query\nSecondi:$secondi";
		
		$temp = is_file($pathJail."/Freed/".$ip) ? "Perm" : "Temp";
		
		if (!is_file($pathJail."/$temp/".$ip))
			FilePutContentsAtomic($pathJail."/$temp/".$ip, $content);
		
		if (!is_file($pathJail."/Log/".$ip))
			FilePutContentsAtomic($pathJail."/Log/".$ip, $content);
	}
	
	public static function blockIps($ips, $secondi = "--")
	{
		if (empty($ips))
			return;
		
		self::createLogFolders();
		
		$pathJail = ROOT."/Logs/Jail/";
		
		foreach ($ips as $ip => $query)
		{
			// $ip = sanitizeIp($ip);
			$ip = F::checkIpESubIp($ip);
			
			if (trim($ip))
				self::writeIp($ip, $query, $secondi);
		}
	}
	
	public static function freeTempIps($log = null)
	{
		$pathJail = ROOT."/Logs/Jail/";
		
		if (@is_dir($pathJail))
		{
			foreach (new DirectoryIterator($pathJail."Temp") as $fileInfo)
			{
				$fileName = $fileInfo->getFilename();
				
				if ($fileInfo->isDot())
					continue;
				
				if ($fileName == "index.html" || $fileName == ".htaccess")
					continue;
				
				if ((time() - $fileInfo->getCTime()) >= self::$freedAfterSeconds)
				{
					rename($pathJail."/Temp/".$fileName, $pathJail."/Freed/".$fileName);
					
					if ($log)
						$log->writeString("Liberato IP $fileName dopo ".self::$freedAfterSeconds." secondi");
				}
			}
		}
	}
}
