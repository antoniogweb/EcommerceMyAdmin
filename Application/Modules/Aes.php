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

require_once(LIBRARY."/External/libs/vendor/autoload.php");

class Aes
{
	// ENCRYPT: prende OTP 6 cifre, chiavi binarie (32B), ritorna HEX (IV||CT||MAC)
	public static function encrypt(string $text): string {
		
		if (!defined('AES_KEY') || !defined('MAC_KEY'))
			return $text;
		
		// IV 16 byte
		$iv = random_bytes(16);

		$aes = new phpseclib\Crypt\AES('cbc');
		$aes->setKey(AES_KEY);
		$aes->setIV($iv);
		$ciphertext = $aes->encrypt($text); // pkcs7 padding gestito da phpseclib v2

		// MAC su IV||CT
		$mac = hash_hmac('sha256', $iv . $ciphertext, MAC_KEY, true);

		// restituisco HEX di IV||CT||MAC
		return bin2hex($iv . $ciphertext . $mac);
	}
	
	// DECRYPT: prende HEX (IV||CT||MAC), chiavi binarie, restituisce plaintext OTP o null su errore
	public static function decrypt(string $hexBlob): ?string {
		
		if (!defined('AES_KEY') || !defined('MAC_KEY'))
			return $hexBlob;
		
		$bin = hex2bin($hexBlob);
		if ($bin === false) return null;

		$ivLen = 16;
		$macLen = 32; // HMAC-SHA256 raw bytes
		if (strlen($bin) < ($ivLen + $macLen + 1)) return null;

		$iv = substr($bin, 0, $ivLen);
		$mac = substr($bin, -$macLen);
		$ciphertext = substr($bin, $ivLen, strlen($bin) - $ivLen - $macLen);

		// verifica MAC (constant-time)
		$calc = hash_hmac('sha256', $iv . $ciphertext, MAC_KEY, true);
		if (!hash_equals($calc, $mac)) return null;

		$aes = new phpseclib\Crypt\AES('cbc');
		$aes->setKey(AES_KEY);
		$aes->setIV($iv);
		$plain = $aes->decrypt($ciphertext);

		return $plain === false ? null : $plain;
	}
}
