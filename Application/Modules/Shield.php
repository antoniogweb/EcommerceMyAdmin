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
	public static $freedThrottleAfterSeconds = 120;
	public static $freeDDOSSeconds = 600;
	public static $securityRules = array();
	
	public static function createLogFolders()
	{
		createFolderFull("Logs/Jail", LIBRARY);
		createFolderFull("Logs/Jail/Temp", LIBRARY);
		createFolderFull("Logs/Jail/Perm", LIBRARY);
		createFolderFull("Logs/Jail/Log", LIBRARY);
		createFolderFull("Logs/Jail/Freed", LIBRARY);
		createFolderFull("Logs/Jail/Throttle", LIBRARY);
		createFolderFull("Logs/Jail/LogThrottle", LIBRARY);
	}
	
	public static function writeIp($ip, $query = "--", $secondi = "--", $throttle = false)
	{
		$pathJail = LIBRARY."/Logs/Jail/";
		
		if ($throttle)
		{
			$retryAfterSeconds = self::$freedThrottleAfterSeconds + 20;
			$retryDate = gmdate('D, d M Y H:i:s', time() + $retryAfterSeconds) . ' GMT';
			$content = $retryDate;
			
			if (!is_file($pathJail."/Throttle/".$ip))
				FilePutContentsAtomic($pathJail."/Throttle/".$ip, $content);
			
			if (!is_file($pathJail."/LogThrottle/".$ip))
				FilePutContentsAtomic($pathJail."/LogThrottle/".$ip, $content);
		}
		else
		{
			$content = date("Y-m-d H:i:s")."\nQuery:$query\nSecondi:$secondi";
			
			$temp = is_file($pathJail."/Freed/".$ip) ? "Perm" : "Temp";
			
			if (!is_file($pathJail."/$temp/".$ip))
				FilePutContentsAtomic($pathJail."/$temp/".$ip, $content);
			
			if (!is_file($pathJail."/Log/".$ip))
				FilePutContentsAtomic($pathJail."/Log/".$ip, $content);
		}
	}
	
	public static function blockIps($ips, $secondi = "--")
	{
		if (empty($ips))
			return;
		
		self::createLogFolders();
		
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
		
		if (@is_dir($pathJail) && @is_dir($pathJail."Temp"))
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
	
	public static function throttleIps($ips, $secondi = "--")
	{
		if (empty($ips))
			return;
		
		self::createLogFolders();
		
		foreach ($ips as $ip => $query)
		{
			// $ip = sanitizeIp($ip);
			$ip = F::checkIpESubIp($ip);
			
			if (trim($ip))
				self::writeIp($ip, $query, $secondi, true);
		}
	}
	
	public static function freeThrottleIps($log = null)
	{
		$pathJail = LIBRARY."/Logs/Jail/";
		
		if (@is_dir($pathJail) && @is_dir($pathJail."Throttle"))
		{
			foreach (new DirectoryIterator($pathJail."Throttle") as $fileInfo)
			{
				$fileName = $fileInfo->getFilename();
				
				if ($fileInfo->isDot())
					continue;
				
				if ($fileName == "index.html" || $fileName == ".htaccess")
					continue;
				
				if ((time() - $fileInfo->getCTime()) >= self::$freedThrottleAfterSeconds)
				{
					unlink($pathJail."/Throttle/".$fileName);
					
					if ($log)
						$log->writeString("Rimosso throttle IP $fileName dopo ".self::$freedThrottleAfterSeconds." secondi");
				}
			}
		}
	}
	
	public static function creaCapctaDDOS($numero = 120)
	{
		if (!is_dir(LIBRARY."/Logs/CaptchaDDOS"))
		{
			createFolderFull("Logs/CaptchaDDOS", LIBRARY, false);
			FilePutContentsAtomic(LIBRARY."/Logs/CaptchaDDOS/time.txt", time());
			FilePutContentsAtomic(LIBRARY."/Logs/CaptchaDDOS/captcha_ddos_session_ok_key.txt", "ddos_ok_key_".generateString(20));
			FilePutContentsAtomic(LIBRARY."/Logs/CaptchaDDOS/captcha_ddos_post_key.txt", "ddos_post_key_".generateString(20));
			FilePutContentsAtomic(LIBRARY."/Logs/CaptchaDDOS/captcha_ddos_session_key.txt", "ddos_key_".generateString(20));
			
			createFolderFull("Logs/CaptchaDDOS/Img", LIBRARY, true, false);
			$fp = @fopen(LIBRARY."/Logs/CaptchaDDOS/Img/.htaccess", 'w');
			fwrite($fp, 'allow from all');
			fclose($fp);
			
			$old = umask(0);
			mkdir(LIBRARY."/Logs/CaptchaDDOS/Ip",0777);
			$fp = fopen(LIBRARY."/Logs/CaptchaDDOS/Ip" . "/index.html", 'w');
			fclose($fp);
			
			$fp = fopen(LIBRARY."/Logs/CaptchaDDOS/Ip" . "/.htaccess", 'w');
			fwrite($fp, 'deny from all');
			fclose($fp);
			umask($old);
			
			$captcha = new Image_Gd_Captcha(array(
				"boxWidth"	=>	200,
				"fontPath"	=>	LIBRARY."/External/Fonts/FreeFont/FreeMono.ttf",
				"boxHeight"	=>	60,
				"charHeight"=>	22,
			));
			
			$arrayOfCodes = array();
			
			for ($i = 0; $i < $numero; $i++)
			{
				$code = generateString(6);
				$codeFile = generateString(30);
				
				$arrayOfCodes[$codeFile] = $code;
				
				$captcha->setString($code);
				$captcha->render(LIBRARY."/Logs/CaptchaDDOS/Img/", $codeFile);
			}
			
			FilePutContentsAtomic(LIBRARY."/Logs/CaptchaDDOS/captcha_ddos_json_codes.txt", json_encode($arrayOfCodes));
		}
	}
	
	public static function CapctaDDOSFolderAlreadyPresent()
	{
		$pathDDOS = LIBRARY."/Logs/CaptchaDDOS/";
		
		if (is_dir($pathDDOS) && is_file($pathDDOS."time.txt"))
			return true;
		
		return false;
	}
	
	public static function freeDDOSAttack()
	{
		$pathDDOS = LIBRARY."/Logs/CaptchaDDOS/";
		
		if (is_dir($pathDDOS) && is_file($pathDDOS."time.txt"))
		{
			$time = (int)file_get_contents($pathDDOS."time.txt");
			
			if ((time() - $time) >= self::$freeDDOSSeconds)
				self::eliminaCapctaDDOS();
		}
	}
	
	public static function eliminaCapctaDDOS()
	{
		if (is_dir(LIBRARY."/Logs/CaptchaDDOS"))
		{
			$nuovoNome = randomToken(20);
			rename(LIBRARY."/Logs/CaptchaDDOS", LIBRARY."/Logs/$nuovoNome");
			PagesModel::eliminaCartella(LIBRARY."/Logs/$nuovoNome");
		}
	}
	
	public static function waf()
	{
		$ip = getIp();
		
		if (!trim($ip))
			return;
		
		Files_Log::$logFolder = LIBRARY."/Logs";
		$log = Files_Log::getInstance("log_monitoring");
		
		$payloadPath = is_dir(FRONT."/Logs/Payload") ? FRONT."/Logs/Payload" : LIBRARY."/Frontend/Logs/Payload";
		
		if (is_dir($payloadPath))
		{
			$erroriBlocco = array();
			
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
		
		if ((int)v("attiva_waf_euristico") !== 1)
			return;
		
		$securityScore = self::requestSecurityScore();
		$securityScoreTotale = $securityScore["total"];
		$securityScoreMassimo = $securityScore["max"];
		$securityScoreRegole = $securityScore["rules"];
		$sogliaCampoSospetto = self::wafEuristicoSoglia("waf_euristico_score_campo_sospetto", 5);
		$sogliaTotaleSospetto = self::wafEuristicoSoglia("waf_euristico_score_totale_sospetto", 8);
		$sogliaCampoBlocco = self::wafEuristicoSoglia("waf_euristico_score_campo_blocco", 8);
		$sogliaTotaleBlocco = self::wafEuristicoSoglia("waf_euristico_score_totale_blocco", 12);
		
		if ($securityScoreMassimo >= $sogliaCampoBlocco || $securityScoreTotale >= $sogliaTotaleBlocco)
		{
			$log->writeString("Bloccato IP $ip: score WAF euristico totale $securityScoreTotale, massimo $securityScoreMassimo, regole: $securityScoreRegole nel seguente request uri: ".(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ""));
			
			ConteggioqueryModel::aggiungiConCodice(0, 403, 1, $securityScoreTotale, $securityScoreMassimo);
			
			if (v("attiva_blocco_immediato"))
				self::checkEBloccaIp($log);
			
			http_response_code(403);
			die();
		}
		else if ($securityScoreMassimo >= $sogliaCampoSospetto || $securityScoreTotale >= $sogliaTotaleSospetto)
		{
			$log->writeString("Richiesta sospetta da IP $ip: score WAF euristico totale $securityScoreTotale, massimo $securityScoreMassimo, regole: $securityScoreRegole nel seguente request uri: ".(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ""));
			
			ConteggioqueryModel::aggiungiConCodice(1, 406, 1, $securityScoreTotale, $securityScoreMassimo);
			
			if (v("attiva_blocco_immediato"))
				self::checkEBloccaIp($log);
		}
	}

	public static function checkEBloccaIp($log = null)
	{
		$secondi = 60;
		
		$conteggio = ConteggioqueryModel::numeroAttacchi(v("numero_massimo_attacchi_minuto"), $secondi);
		
		if (!empty($conteggio))
		{
			Shield::freeTempIps($log);
			
			Shield::blockIps($conteggio, $secondi);
			
			$log->writeString("Gli IP sono stati bloccati");
			
			LogtecniciModel::aggiungi("ATTACCO", "Superato il limite di ".v("numero_massimo_attacchi_minuto")." attacchi negli ultimi $secondi secondi<br />\n". "<pre>".json_encode($conteggio,JSON_PRETTY_PRINT)."</pre>");
		}
	}
	
	public static function wafEuristicoSoglia($nomeVariabile, $default)
	{
		$valore = (int)v($nomeVariabile);
		
		return $valore > 0 ? $valore : $default;
	}
	
	public static function requestSecurityScore()
	{
		self::$securityRules = array();
		
		$getScore = self::arraySecurityScore($_GET);
		$postScore = self::arraySecurityScore($_POST);
		
		return array(
			"total"	=>	$getScore["total"] + $postScore["total"],
			"max"	=>	max($getScore["max"], $postScore["max"]),
			"rules"	=>	self::securityRulesString(),
		);
	}
	
	public static function arraySecurityScore($array)
	{
		$score = array(
			"total"	=>	0,
			"max"	=>	0,
		);
		
		foreach ($array as $key => $value)
		{
			$keyScore = self::securityScore((string)$key);
			$score["total"] += $keyScore;
			$score["max"] = max($score["max"], $keyScore);
			
			if (is_array($value))
			{
				$valueScore = self::arraySecurityScore($value);
				$score["total"] += $valueScore["total"];
				$score["max"] = max($score["max"], $valueScore["max"]);
			}
			else
			{
				$valueScore = self::securityScore((string)$value);
				$score["total"] += $valueScore;
				$score["max"] = max($score["max"], $valueScore);
			}
		}
		
		return $score;
	}
	
	public static function addSecurityRule($pattern, $weight)
	{
		self::$securityRules[$pattern] = $weight;
	}
	
	public static function securityRulesString()
	{
		if (empty(self::$securityRules))
			return "";
		
		$rules = array();
		
		foreach (self::$securityRules as $pattern => $weight)
		{
			$rules[] = $pattern." => ".(int)$weight;
		}
		
		return implode("; ", $rules);
	}
	
	public static function normalizeInputForSecurity($value)
	{
		if (!is_string($value))
			return '';
		
		for ($i = 0; $i < 3; $i++)
		{
			$decoded = urldecode($value);
			
			if ($decoded === $value)
				break;
			
			$value = $decoded;
		}
		
		$value = htmlentitydecode($value);
		
		return function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
	}
	
	public static function securityScore($value)
	{
		$input = self::normalizeInputForSecurity($value);
		$rawInput = function_exists('mb_strtolower') ? mb_strtolower((string)$value, 'UTF-8') : strtolower((string)$value);
		$score = 0;
		
		$patterns = array(
			// SQL injection evidenti
			'/\bunion\s+select\b/i'											=>	8,
			'/\bselect\b.{0,80}\bfrom\b/i'									=>	4,
			'/\binformation_schema\b/i'										=>	8,
			'/\bconcat\s*\(/i'											=>	3,
			'/\bsleep\s*\(/i'											=>	8,
			'/\bbenchmark\s*\(/i'										=>	8,
			'/\bload_file\s*\(/i'										=>	8,
			'/\binto\s+outfile\b/i'										=>	8,
			'/(\'|")\s*(or|and)\s+(\'|")?\w+(\'|")?\s*=\s*(\'|")?\w+/i'	=>	4,
			'/\/\*/'													=>	2,
			'/--\s*(\r?\n|$)/'										=>	3,
			'/(^|[^\w])#/'											=>	1,
			
			// XSS
			'/<\s*script\b/i'										=>	8,
			'/<\s*\/\s*script\s*>/i'									=>	8,
			'/<[^>]+\son\w+\s*=/i'									=>	5,
			'/\bon(?:abort|blur|click|error|focus|load|mouseover|submit)\s*=/i'	=>	4,
			'/javascript\s*:/i'										=>	8,
			'/data\s*:\s*text\/html/i'									=>	8,
			'/<\s*(iframe|object|embed|svg|body|meta|link)\b/i'			=>	4,
			'/<\s*img\b.{0,200}\bon\w+\s*=/i'					=>	5,
			'/<\s*img\b/i'										=>	2,
			
			// Path traversal / file probing
			'/(\.\.\/|\.\.\\\\)/'										=>	4,
			'/\/etc\/passwd/i'										=>	5,
			'/boot\.ini/i'											=>	5,
			'/windows\/system32/i'									=>	5,
			
			// Command injection
			'/(`|\$\(|&&|\|\|)/'									=>	3,
			'/(;|\|)/'											=>	1,
			'/\b(wget|curl|bash|nc|netcat|python|perl)\b/i'			=>	2,
			'/(`|\$\(|&&|\|\|).{0,40}\b(wget|curl|bash|nc|netcat|python|perl)\b/i'	=>	5,
			'/\b(wget|curl|bash|nc|netcat|python|perl)\b.{0,40}(`|\$\(|&&|\|\|)/i'	=>	5,
			
			// Encoding sospetto
			'/char\s*\(/i'											=>	3,
		);
		
		foreach ($patterns as $pattern => $weight)
		{
			if (preg_match($pattern, $input))
			{
				$score += $weight;
				self::addSecurityRule($pattern, $weight);
			}
		}
		
		if (preg_match('/%3c|%3e|%27|%22|%2f|%5c/i', $rawInput))
		{
			$score += 2;
			self::addSecurityRule('/%3c|%3e|%27|%22|%2f|%5c/i', 2);
		}
		
		if (strlen($input) > 1000)
		{
			$score += 2;
			self::addSecurityRule('strlen($input) > 1000', 2);
		}
		
		if (preg_match('/[^\p{L}\p{N}\s@\.\,\-\_\+\:\;\/\?=&%€£$!\'"\(\)]/u', $input))
		{
			$score += 1;
			self::addSecurityRule('/[^\p{L}\p{N}\s@\.\,\-\_\+\:\;\/\?=&%€£$!\'"\(\)]/u', 1);
		}
		
		return $score;
	}
}
