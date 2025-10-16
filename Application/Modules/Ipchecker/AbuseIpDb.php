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

class AbuseIpDb extends Ipchecker
{
	public static $minConfidenceScore = 10;
	
	public function gCampiForm()
	{
		return 'titolo,attivo,key_1';
	}
	
	public function isAttivo()
	{
		if ($this->params["attivo"] && trim($this->params["key_1"]))
			return true;
		
		return false;
	}
	
	public function editFormStruct($model, $record)
	{
		$model->formStruct["entries"]["key_1"]["labelString"] = "AbuseIpDB ApiKey";
		$model->formStruct["entries"]["key_1"]["type"] = "Password";
		$model->formStruct["entries"]["key_1"]["fill"] = true;
	}
	
	public function check($ip)
	{
		if (!F::checkIpESubIp($ip))
			return null;
		
		require_once(LIBRARY . '/External/libs/vendor/autoload.php');
		
		$client = new GuzzleHttp\Client([
			'base_uri' => 'https://api.abuseipdb.com/api/v2/'
		]);

		$response = $client->request('GET', 'check', [
			'query' => [
				'ipAddress' => $ip,
				'maxAgeInDays' => '90',
			],
			'headers' => [
				'Accept' => 'application/json',
				'Key' => $this->getParam("key_1")
		],
		]);

		$output = $response->getBody();
		
		$this->logCall($ip, $output);
		
		$ipDetails = json_decode($output, true);
		
		if ($ipDetails["data"]["abuseConfidenceScore"] >= self::$minConfidenceScore)
			return false;
		
		return true;
	}
}