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

class Cidrfilter
{
	public static $whiteListCidrs = null;

	public static function getIpBotFolder()
	{
		return LIBRARY."/Logs/IpBot";
	}
	
	public static function checkIpBotFolder()
	{
		if (is_dir(self::getIpBotFolder()))
			return true;
		
		return false;
	}
	
	public static function ipInWhiteList($ip)
	{
		if (!isset(self::$whiteListCidrs))
		{
			if (!self::checkIpBotFolder())
				return false;
			
			self::$whiteListCidrs = array();
			
			$cachePath = self::getIpBotFolder() . "/cacheIpBot.json";
			
			if (is_file($cachePath))
			{
				$contenuto = @file_get_contents($cachePath);
				self::$whiteListCidrs = json_decode($contenuto, true);
			}
		}
		
		foreach (self::$whiteListCidrs as $cird)
		{
			if (self::ipInCidr($ip, $cird))
				return true;
		}
		
		return false;
	}
	
	public static function ipInCidr($ip, $cidr)
	{
		if (strpos($cidr, '/') === false) return false;
		
		[$net, $prefixStr] = explode('/', $cidr, 2);
		$prefix = (int)$prefixStr;

		$ipBin  = @inet_pton($ip);
		$netBin = @inet_pton($net);
		if ($ipBin === false || $netBin === false) return false;

		$len = strlen($ipBin);
		if ($len !== strlen($netBin)) return false; // mix v4/v6

		$maxPrefix = $len * 8;
		if ($prefix < 0 || $prefix > $maxPrefix) return false;

		$fullBytes = intdiv($prefix, 8);
		$remBits   = $prefix % 8;

		if ($fullBytes > 0)
		{
			if (substr($ipBin, 0, $fullBytes) !== substr($netBin, 0, $fullBytes))
				return false;
		}
		
		if ($remBits === 0) return true;

		$mask = (0xFF << (8 - $remBits)) & 0xFF;

		$ipByte  = ord($ipBin[$fullBytes]);
		$netByte = ord($netBin[$fullBytes]);

		return (($ipByte & $mask) === ($netByte & $mask));
	}
}
