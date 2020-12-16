<?php

// EasyGiant is a PHP framework for creating and managing dynamic content
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
// See COPYRIGHT.txt and LICENSE.txt.
//
// This file is part of EasyGiant
//
// EasyGiant is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// EasyGiant is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with EasyGiant.  If not, see <http://www.gnu.org/licenses/>.

if (!defined('EG')) die('Direct access not allowed!');

class Cache {
	
	public static $cachedQueries = array();
	public static $cachedTables = array();
	public static $cacheFolder = null;
	public static $cacheMinutes = 10;
	public static $cacheTimeString = null;
	
	public static function roundToLastSet()
	{
		$date = new DateTime();

		// Remove any seconds, we don't need them
		$seconds = $date->format('s');
		$date->modify('-' . $seconds . ' seconds');

		// Store the original number of minutes on the datetime
		$original_minutes = $date->format('i');
		// Calculate how many minutes past the last quarter hour
		$remaining_minutes = $original_minutes - ($original_minutes - ($original_minutes % self::$cacheMinutes));

		// Modify the minutes, remove the number of minutes past the last quarter of an hour
		$date->modify('-' . $remaining_minutes . ' minutes');

		return $date;
	}
	
	public static function deleteExpired()
	{
		$path = self::$cacheFolder."/last_clean.txt";
		
		if (file_exists(self::$cacheFolder))
		{
			if (file_exists($path))
			{
				$time = (int)file_get_contents($path);
				
				if ((time() - $time) >= 60*60)
				{
					foreach (new DirectoryIterator(self::$cacheFolder) as $fileInfo)
					{
						if ($fileInfo->isDot())
							continue;
						
						if ($fileInfo->getFilename() == "index.html")
							continue;
						
						if ($fileInfo->isFile() && ((time() - $fileInfo->getCTime()) >= 50*60))
							unlink($fileInfo->getRealPath());
					}
					
					file_put_contents($path, time());
				}
			}
			else
				file_put_contents($path, time());
		}
	}
	
	public static function getCacheTimeString()
	{
		if (!self::$cacheTimeString)
		{
			$date = self::roundToLastSet();
			self::$cacheTimeString = $date->format("Y_m_d_H_i");
		}
			
		
		return self::$cacheTimeString;
	}
	
	public static function getData($table, $query)
	{
		if (in_array($table, self::$cachedTables))
		{
			if (self::$cacheFolder)
			{
				$fileName = self::getCacheTimeString()."_".md5($query).".txt";
				
				if (file_exists(self::$cacheFolder."/".$fileName))
					return unserialize(file_get_contents(self::$cacheFolder."/".$fileName));
			}
			else if (isset(self::$cachedQueries[md5($query)]))
				return self::$cachedQueries[md5($query)];
		}
		
		return null;
	}
	
	public static function setData($table, $query, $data)
	{
		if (in_array($table, self::$cachedTables))
		{
			if (self::$cacheFolder)
			{
				if(!is_dir(self::$cacheFolder))
				{
					if (@mkdir(self::$cacheFolder))
					{
						$fp = fopen(self::$cacheFolder.'/index.html', 'w');
						fclose($fp);
					}
				}
				
				if(is_dir(self::$cacheFolder))
				{
					$fileName = self::getCacheTimeString()."_".md5($query).".txt";
					
					file_put_contents(self::$cacheFolder."/".$fileName, serialize($data));
				}
			}
			else
				self::$cachedQueries[md5($query)] = $data;
		}
	}

}
