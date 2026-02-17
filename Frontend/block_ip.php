<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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

include_once(LIBRARY."/config.php");
require_once (LIBRARY . DS . 'Application/Modules/' . DS . 'Device.php');

function getIpToCheck()
{
    $ip = "";
	
    if (isset($_SERVER))
    {
		$remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';
		
		if (defined('TRUSTED_PROXIES'))
		{
			if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			{
				$trustedProxies = TRUSTED_PROXIES;
				$isTrustedProxy = ($remoteAddr !== '' && in_array($remoteAddr, $trustedProxies, true));
				
				if ($isTrustedProxy)
				{
					$parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
					
					if (is_array($parts))
					{
						$last = trim($parts[count($parts)-1]);
						
						if (filter_var($last, FILTER_VALIDATE_IP))
						{
							$ip = $last;
						}
					}
				}
			}
		}
		else if ($remoteAddr !== '' && filter_var($remoteAddr, FILTER_VALIDATE_IP))
		{
			$ip = $remoteAddr;
		}
	}
	
	return $ip;
}

$ip = getIpToCheck();

if (trim($ip))
{
    $ipArray = explode(".", $ip);
    
    $subIp = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ? $ipArray[0].".".$ipArray[1].".".$ipArray[2] : $ip;
    
    if (@is_file(ROOT."/admin/Logs/Jail/Perm/$ip") || @is_file(ROOT."/admin/Logs/Jail/Perm/$subIp"))
    {
        http_response_code(403);
        die();
    }
    
    if (@is_file(ROOT."/admin/Logs/Jail/Temp/$ip") || @is_file(ROOT."/admin/Logs/Jail/Temp/$subIp"))
    {
        http_response_code(403);
        die();
    }
    
    $botName = Device::getBotName();

	if ($botName)
	{
		if (@is_file(ROOT."/admin/Logs/Jail/Perm/$botName"))
		{
			http_response_code(403);
			die();
		}
		
		if (@is_file(ROOT."/admin/Logs/Jail/Temp/$botName"))
		{
			http_response_code(403);
			die();
		}
	}
}
else if (!defined('APP_CONSOLE'))
{
    if (@is_dir(ROOT."/admin/Logs/NoIp"))
    {
        $file = date("YmdHis")."_".microtime(true);
        $fp = fopen(ROOT.'/admin/Logs/NoIp/'.$file.'.txt', 'a+');
        fwrite($fp, date("Y-m-d H:i:s")."\n");
        fwrite($fp, print_r($_COOKIE,true));
        fwrite($fp, print_r($_GET,true));
        fwrite($fp, print_r($_POST,true));
        if (isset($_SERVER))
            fwrite($fp, print_r($_SERVER,true));
        fclose($fp);
    }
    
    http_response_code(403);
    die();
}