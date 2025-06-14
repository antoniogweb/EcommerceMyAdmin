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
}
