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

$ip = getIp();

function logBlocco()
{

}

function generateStringBlockIp($charNumb = 8,$allowedChars = '0123456789abcdefghijklmnopqrstuvwxyz')
{
	$str = null;
	for ($i = 0; $i < $charNumb; $i++)
	{
		$str .= substr($allowedChars, random_int(0, strlen($allowedChars)-1), 1);
	}
	return $str;
}

$md5UserAgent = (isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT']) ? md5($_SERVER['HTTP_USER_AGENT']) : md5("XXX");

$DDOSRoot = LIBRARY."/Logs/CaptchaDDOS/";
$DDOSPath = $DDOSRoot."Img";

$md5UserAgentIp = $md5UserAgent."_".$ip;

if (is_dir($DDOSPath))
{
	require_once (LIBRARY . DS . 'Application/Models/Cidrfilter.php');
		
	$ipInWhiteList = false;
	
	if (isset($ip) && trim($ip))
		$ipInWhiteList = Cidrfilter::ipInWhiteList(trim($ip));
	
	if (!$ipInWhiteList)
	{
		$ipTentativi = trim($ip) ? trim($ip) : "_";
		
		// Verifico il numero di tentativi
		$IpNumberPath = $DDOSRoot."/Ip/$ipTentativi";
		
		if (!is_dir($IpNumberPath))
		{
			$old = umask(0);
			mkdir($IpNumberPath, 0777);
			umask($old);
		}
		
		$arrayTentativiIp = array();
		$items = scandir($IpNumberPath);
		foreach( $items as $this_file ) {
			if( strcmp($this_file,".") !== 0 && strcmp($this_file,"..") !== 0 && strcmp($this_file,"index.html") !== 0 && strcmp($this_file,".htaccess") !== 0) {
				$arrayTentativiIp[] = $this_file;
			}
		}
		
		if (count($arrayTentativiIp) >= 5)
		{
			http_response_code(403);
			die();
		}
		
		$captcha_ddos_session_ok_key = file_get_contents($DDOSRoot."/captcha_ddos_session_ok_key.txt");
		$captcha_ddos_session_post_key = file_get_contents($DDOSRoot."/captcha_ddos_post_key.txt");
		$captcha_ddos_session_key = file_get_contents($DDOSRoot."/captcha_ddos_session_key.txt");
		$captcha_ddos_json = file_get_contents($DDOSRoot."/captcha_ddos_json_codes.txt");
		
		$captcha_ddos_jsonArray = json_decode($captcha_ddos_json, true);
		$captcha_ddos_jsonArray_flipped = array_flip($captcha_ddos_jsonArray);
		
		if( !session_id() )
			session_start();
		
		if (isset($_POST[$captcha_ddos_session_post_key]) && isset($_SESSION[$captcha_ddos_session_key]))
		{
			if (strtolower((string)$_POST[$captcha_ddos_session_post_key]) === strtolower((string)$_SESSION[$captcha_ddos_session_key]))
				$_SESSION[$captcha_ddos_session_ok_key] = $md5UserAgentIp;
			else
			{
				$randomFile = generateStringBlockIp(10);
				file_put_contents($IpNumberPath."/$randomFile.txt", "");
				
				if (count($arrayTentativiIp) >= 4)
				{
					http_response_code(403);
					die();
				}
			}
		}
		
		if (!isset($_SESSION[$captcha_ddos_session_ok_key]) || $_SESSION[$captcha_ddos_session_ok_key] != $md5UserAgentIp)
		{
			if (isset($_SESSION[$captcha_ddos_session_key]) && ctype_alnum($_SESSION[$captcha_ddos_session_key]) && is_file($DDOSPath."/".$captcha_ddos_jsonArray_flipped[$_SESSION[$captcha_ddos_session_key]].".png"))
				$fileName = (string)$captcha_ddos_jsonArray_flipped[$_SESSION[$captcha_ddos_session_key]].".png";
			else
			{
				$arrayFiles = array();
				$items = scandir($DDOSPath);
				foreach( $items as $this_file ) {
					if( strcmp($this_file,".") !== 0 && strcmp($this_file,"..") !== 0 && strcmp($this_file,"index.html") !== 0 && strcmp($this_file,".htaccess") !== 0) {
						$arrayFiles[] = $this_file;
					}
				}
				
				$numero = count($arrayFiles);
				$numeroRandom = random_int(0,($numero-1));
				$fileName = $arrayFiles[$numeroRandom];
				
				$fileNameArray = explode(".", $fileName);
				
				if (isset($captcha_ddos_jsonArray[$fileNameArray[0]]))
					$_SESSION[$captcha_ddos_session_key] = $captcha_ddos_jsonArray[$fileNameArray[0]];
			}
		?>
			<!DOCTYPE html>
			<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
				<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
				<style>
				body
				{
					text-align:center;
				}
				</style>
			</head>
			</body>
				<?php if (Params::$lang == "it") { ?>
				<p>Dobbiamo verificare che tu non sia un robot.<br />Per favore digita nel campo sottostante il codice a 6 cifre e poi premi il pulsante "Send / Invia" per continuare a navigare il sito normalmente.</p>
				<?php } else if (Params::$lang == "en") { ?>
				<p>We need to verify that you are not a robot.<br />Please type the 6-digit code in the field below and then press the button "Send / Invia" to continue browsing the site normally.</p>
				<?php } else if (Params::$lang == "fr") { ?>
				<p>Nous devons vérifier que vous n'êtes pas un robot.<br />Veuillez saisir le code à 6 chiffres dans le champ ci-dessous, puis appuyez sur le bouton « Envoyer » pour continuer à naviguer normalement sur le site.</p>
				<?php } else if (Params::$lang == "es") { ?>
				<p>Necesitamos verificar que no eres un robot.<br />Por favor, introduce el código de 6 dígitos en el campo de abajo y pulsa el botón "Enviar" para seguir navegando por el sitio con normalidad.</p>
				<?php } else if (Params::$lang == "de") { ?>
				<p>Wir müssen überprüfen, ob Sie kein Roboter sind.<br />Bitte geben Sie den 6-stelligen Code in das untenstehende Feld ein und klicken Sie anschließend auf „Senden“, um die Website wie gewohnt weiter zu nutzen.</p>
				<?php } else { ?>
				<p>Dobbiamo verificare che tu non sia un robot.<br />Per favore digita nel campo sottostante il codice a 6 cifre e poi premi il pulsante "Send / Invia" per continuare a navigare il sito normalmente.</p>
				<?php } ?>
				
				<img src="/admin/Logs/CaptchaDDOS/Img/<?php echo $fileName;?>" /><br />
				<form action="" method="POST">
					<input style="width:200px;height:20px;margin-top:10px;" type="text" name="<?php echo $captcha_ddos_session_post_key;?>" value="" placeholder="Write here the code.."/><br />
					<button style="width:200px;height:30px;margin-top:10px;" type="submit">Send / Invia</button>
				</form>
			</body>
			</html>
			<?php
			logBlocco();
			die();
		}
	}
}
