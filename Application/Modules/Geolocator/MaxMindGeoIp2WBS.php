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

class MaxMindGeoIp2WBS extends Geolocator
{
	private $MMDBTarFileName = "GeoLite2-Country.tar";
	private $MMDBFileName = "GeoLite2-Country.mmdb";
	
	public function gCampiForm()
	{
		return 'titolo,attivo,key_1,key_2';
	}
	
	public function isAttivo()
	{
		if ($this->params["attivo"] && trim($this->params["key_1"]) && trim($this->params["key_2"]))
			return true;
		
		return false;
	}
	
	public function editFormStruct($model, $record)
	{
		$model->formStruct["entries"]["key_1"]["labelString"] = "MaxMind ID";
		
		$model->formStruct["entries"]["key_2"]["labelString"] = "MaxMind KEY";
		$model->formStruct["entries"]["key_2"]["type"] = "Password";
		$model->formStruct["entries"]["key_2"]["fill"] = true;
	}
	
	public function getNazione($ip)
	{
		require_once(LIBRARY . '/External/libs/vendor/autoload.php');

		$client = new GeoIp2\WebService\Client($this->getParam("key_1"), $this->getParam("key_2"));

		$record = $client->country($ip);

		$nazione = "";
		$network = "";
		
		if (isset($record->country->isoCode))
			$nazione = $record->country->isoCode;
		
		if (isset($record->traits->network))
			$network = $record->traits->network;
		
		return array($nazione, $network);
	}
	
	public function downloadMMDB_($log = null)
	{
		require_once(LIBRARY . '/External/libs/vendor/autoload.php');
		
		$path = $this->getDbPath();
		
		$accountId = $this->getParam("key_1");
		$licenseKey = $this->getParam("key_2");

		$url = 'https://download.maxmind.com/geoip/databases/GeoLite2-Country/download?suffix=tar.gz';
		$destinationTarGz = $path . '/'.$this->MMDBTarFileName.".gz";
		
		$fp = fopen($destinationTarGz, 'wb');

		if ($fp === false)
			throw new RuntimeException('Impossibile creare il file');

		$ch = curl_init($url);

		curl_setopt_array($ch, [
			CURLOPT_FILE => $fp,
			CURLOPT_USERPWD => $accountId . ':' . $licenseKey,
			CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_CONNECTTIMEOUT => 15,
			CURLOPT_TIMEOUT => 180,
			CURLOPT_FAILONERROR => true,
		]);

		$success = curl_exec($ch);
		$error = curl_error($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);
		fclose($fp);

		if (!$success) {
			@unlink($destinationTarGz);
			throw new RuntimeException("Download fallito: HTTP $httpCode - $error");
		}
		
		$extractPath = dirname($destinationTarGz) . '/'.$this->dbFolderExtracted;
		$tarPath = dirname($destinationTarGz) . '/'.$this->MMDBTarFileName;
		
		if (is_file($tarPath))
			unlink($tarPath);
		
		$phar = new PharData($destinationTarGz);
		$phar->decompress();
		
		$tar = new PharData($tarPath);
		$tar->extractTo($extractPath, null, true);
		
		$files = glob($extractPath . '/GeoLite2-Country_*/GeoLite2-Country.mmdb');

		if (!$files)
			throw new \RuntimeException("Database MMDB non trovato");

		$source = $files[0];
		$destinationMMDBFile = $path . '/'.$this->MMDBFileName;
		
		$tmp = $destinationMMDBFile . '.tmp';
		
		if (!copy($source, $tmp))
			throw new \RuntimeException("Impossibile copiare il database MMDB");

		try
		{
			$reader = new \MaxMind\Db\Reader($tmp);

			try
			{
				$record = $reader->get('8.8.8.8');

				if (!is_array($record) || empty($record['country']['iso_code']))
					throw new \RuntimeException("Database MMDB non valido");
			}
			finally
			{
				$reader->close();
			}
		}
		catch (\Throwable $e)
		{
			@unlink($tmp);
			throw new \RuntimeException(
				"Verifica database MMDB fallita: " . $e->getMessage(),
				0,
				$e
			);
		}

		if (!rename($destinationMMDBFile . '.tmp', $destinationMMDBFile))
			throw new \RuntimeException("Impossibile aggiornare il database MMDB");
		
		if (is_file($destinationTarGz))
			unlink($destinationTarGz);
		
		if (is_file($tarPath))
			unlink($tarPath);
		
		foreach (glob($path . '/Extracted/GeoLite2-Country_*', GLOB_ONLYDIR) as $dir)
		{
			GeolocatorModel::eliminaCartella($dir);
		}
		
		if ($log)
			$log->writeString("MMDB ".$this->MMDBFileName." scaricato");
	}
	
	public function getCountryFromIpMMDB_($ip)
	{
		$mmdbPath = $this->getDbPath()."/".$this->MMDBFileName;
		
		if (!is_file($mmdbPath))
			return null;
		
		require_once(LIBRARY . '/External/libs/vendor/maxmind-db/reader/autoload.php');
		
		$reader = new \MaxMind\Db\Reader($mmdbPath);

		$country = null;
		
		try
		{
			$record = $reader->get($ip);
			$country = $record['country']['iso_code'] ?? null;
		}
		finally
		{
			$reader->close();
		}
		
		return $country;
	}
}
