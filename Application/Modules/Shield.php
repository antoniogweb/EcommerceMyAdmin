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
		createFolderFull("Logs/Jail", LIBRARY);
		createFolderFull("Logs/Jail/Temp", LIBRARY);
		createFolderFull("Logs/Jail/Perm", LIBRARY);
		createFolderFull("Logs/Jail/Log", LIBRARY);
		createFolderFull("Logs/Jail/Freed", LIBRARY);
	}
	
	public static function writeIp($ip, $query = "--", $secondi = "--")
	{
		$pathJail = LIBRARY."/Logs/Jail/";
		
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
		
		$pathJail = LIBRARY."/Logs/Jail/";
		
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
		$pathJail = LIBRARY."/Logs/Jail/";
		
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
	
	public static function creaCapctaDDOS($numero = 120)
	{
		if (!is_dir(LIBRARY."/Logs/CaptchaDDOS"))
		{
			createFolderFull("Logs/CaptchaDDOS", LIBRARY, false);
			createFolderFull("Logs/CaptchaDDOS/Img", LIBRARY, false, false);
			
			$captcha = new Image_Gd_Captcha(array(
				"boxWidth"	=>	200,
				"fontPath"	=>	LIBRARY."/External/Fonts/FreeFont/FreeMono.ttf",
				"boxHeight"	=>	60,
				"charHeight"=>	22,
			));
			
			for ($i = 0; $i < $numero; $i++)
			{
				$captcha->setString(generateString(6));
				$captcha->render(LIBRARY."/Logs/CaptchaDDOS/Img/");
			}
		}
	}
	
	public static function waf()
	{
		$payloadPath = is_dir(FRONT."/Logs/Payload") ? FRONT."/Logs/Payload" : LIBRARY."/Frontend/Logs/Payload";
		
		if (is_dir($payloadPath))
		{
			$ip = getIp();
			
			Files_Log::$logFolder = LIBRARY."/Logs";
			$log = Files_Log::getInstance("log_monitoring");
			
			$erroriBlocco = array();
			
			if (trim($ip))
			{
				if (isset($_SERVER['REQUEST_URI']) && trim($_SERVER['REQUEST_URI']))
				{
					$requestUriPayloadFilePartial = "$payloadPath/URI/partial.txt";
					
					$requestUri = trim($_SERVER['REQUEST_URI']);
					
					if (is_file($requestUriPayloadFilePartial))
					{
						$stringhe = array_map('trim', file($requestUriPayloadFilePartial, FILE_IGNORE_NEW_LINES));
						
						foreach ($stringhe as $stringa)
						{
							if (stripos($requestUri, $stringa) !== false)
							{
								$erroriBlocco[] = "Bloccato IP $ip: stringa pericolosa <b>$stringa</b> nel seguente request uri: <b>$requestUri</b>";
							}
						}
					}
					
					$requestUriPayloadFileExact = "$payloadPath/URI/exact.txt";
					
					if (is_file($requestUriPayloadFileExact))
					{
						$stringhe = array_map('trim', file($requestUriPayloadFileExact, FILE_IGNORE_NEW_LINES));
						
						foreach ($stringhe as $stringa)
						{
							if (strtolower($requestUri) == strtolower($stringa))
							{
								$erroriBlocco[] = "Bloccato IP $ip: stringa pericolosa <b>$stringa</b> nel seguente request uri: <b>$requestUri</b>";
							}
						}
					}
				}
				
				$allPayloadFilePartial = "$payloadPath/ALL/partial.txt";
					
				if (is_file($allPayloadFilePartial))
				{
					foreach ($_COOKIE as $name => $value)
					{
						$stringhe = array_map('trim', file($allPayloadFilePartial, FILE_IGNORE_NEW_LINES));
						
						foreach ($stringhe as $stringa)
						{
							if (stripos($value, $stringa) !== false)
							{
								$erroriBlocco[] = "Bloccato IP $ip: stringa pericolosa <b>$stringa</b> nel cookie <b>$value</b>";
							}
						}
					}
				}
				
				if (count($erroriBlocco) > 0)
				{
					foreach ($erroriBlocco as $erroreBlocco)
					{
						$log->writeString($erroreBlocco);
					}
					
					ConteggioqueryModel::aggiungiConCodice(0, 403, 1);
					
					if (v("attiva_blocco_immediato"))
						self::checkEBloccaIp($log);
					
					http_response_code(403);
					die();
				}
			}
		}
	}
	
	public static function checkEBloccaIp($log)
	{
		$secondi = 60;
		
		$conteggio = ConteggioqueryModel::numeroAttacchi(v("numero_massimo_attacchi_minuto"), $secondi);
		
		if (!empty($conteggio))
		{
			Shield::blockIps($conteggio, $secondi);
			
			$log->writeString("Gli IP sono stati bloccati");
			
			LogtecniciModel::aggiungi("ATTACCO", "Superato il limite di ".v("numero_massimo_attacchi_minuto")." attacchi negli ultimi $secondi secondi<br />\n". "<pre>".json_encode($conteggio,JSON_PRETTY_PRINT)."</pre>");
		}
	}
}
