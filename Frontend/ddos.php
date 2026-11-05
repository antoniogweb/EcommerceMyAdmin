<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2026  Antonio Gallo (info@laboratoriolibero.com)
// See COPYRIGHT.txt and LICENSE.
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

$ip = getIp();

if (isset($ip) && trim($ip) && defined('DDOS_GEOLOCALIZATION_LIMITS') && isset(DDOS_GEOLOCALIZATION_LIMITS["geolocator"]) && isset(DDOS_GEOLOCALIZATION_LIMITS["limits"]))
{
	require_once (LIBRARY . DS . 'Application/Models/Cidrfilter.php');
	
	$ipInWhiteList = Cidrfilter::ipInWhiteList(trim($ip));
	
	if (!$ipInWhiteList)
	{
		$geolocator = DDOS_GEOLOCALIZATION_LIMITS["geolocator"];
		$limits = DDOS_GEOLOCALIZATION_LIMITS["limits"];
		
		$logPathFile = LIBRARY."/Logs/total_queries.log";
		
		if (is_file($logPathFile))
		{
			$logPathFileContent = file_get_contents($logPathFile);
			
			$logPathFileContentArray = json_decode($logPathFileContent, true);
			
			if (count($logPathFileContentArray) > 0)
			{
				require_once(LIBRARY . '/External/libs/vendor/maxmind-db/reader/autoload.php');
				require_once (LIBRARY . DS . 'Application/Modules/Modulo.php');
				require_once (LIBRARY . DS . 'Application/Modules/Geolocator.php');
				
				if (isset($geolocator["codice"]) && isset($geolocator["classe"]))
				{
					require_once (LIBRARY . DS . 'Application/Modules/Geolocator/'.$geolocator["classe"].'.php');
					
					$objectReflection = new ReflectionClass($geolocator["classe"]);
					$geolocator = $objectReflection->newInstanceArgs(array(array("codice" => $geolocator["codice"])));
					
					$numQueries = (int)$logPathFileContentArray[0];
					
					foreach ($limits as $limit => $struct)
					{
						if ($numQueries > $limit)
						{
							$country = $geolocator->getCountryFromIpMMDB_($ip);
							
							$allowedCountries = $struct["allowed_countries"];
							
							if ($country && !in_array($country, $allowedCountries))
							{
								$retryAfterSeconds = (int)$struct["retry_after_seconds"] + 20;
								$retryDate = gmdate('D, d M Y H:i:s', time() + $retryAfterSeconds) . ' GMT';
				
								http_response_code(429);
								header('Retry-After: ' . $retryDate);
								header('Content-Type: application/json');
								header('Cache-Control: no-store');
								
								echo json_encode([
									'error' => 'Too Many Requests',
									'message' => 'Throttle active. Retry after the date indicated in Retry-After.',
									'retry_after' => $retryDate
								]);
								
								exit;
							}
						}
					}
				}
			}
		}
	}
}

