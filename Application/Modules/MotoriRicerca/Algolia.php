<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
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

use Algolia\AlgoliaSearch\SearchClient;

class Algolia extends MotoreRicerca
{
	public function gCampiForm()
	{
		return 'titolo,attivo,account_id,api_key,api_key_public';
	}
	
	public function editFormStruct($model, $record)
	{
		$model->formStruct["entries"]["api_key"]["labelString"] = "Admin API KEY";
		
		$model->formStruct["entries"]["api_key_public"]["labelString"] = "Search-Only API KEY";
	}
	
	public function isAttivo()
	{
		if (trim($this->params["account_id"]) && trim($this->params["api_key"]) && trim($this->params["api_key_public"]))
			return true;
		
		return false;
	}
	
	private function getClient()
	{
		require_once(LIBRARY . '/External/libs/vendor/autoload.php');
		
		$client = SearchClient::create($this->params["account_id"], $this->params["api_key"]);
		
		return $client;
	}
}
