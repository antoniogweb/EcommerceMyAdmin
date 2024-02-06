<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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

ini_set("display_errors", "Off");

class HubSpot extends Newsletter
{
	private $params = "";
	
	public function __construct($record)
	{
		$this->params = $record;
	}
	
	public function getParams()
	{
		return $this->params;
	}
	
	public function setParam($nome, $valore)
	{
		$this->params[$nome] = $valore;
	}
	
	public function iscrivi($valori)
	{
		require_once(LIBRARY . '/External/libs/vendor/autoload.php');
		
		$hubspot = \HubSpot\Factory::createWithAccessToken(htmlentitydecode($this->params["secret_1"]));

		$email = $valori["email"];
		// 
		$filter = new \HubSpot\Client\Crm\Contacts\Model\Filter();
		$filter
			->setOperator('EQ')
			->setPropertyName('email')
			->setValue($email);

		$filterGroup = new \HubSpot\Client\Crm\Contacts\Model\FilterGroup();
		$filterGroup->setFilters([$filter]);

		$searchRequest = new \HubSpot\Client\Crm\Contacts\Model\PublicObjectSearchRequest();
		$searchRequest->setFilterGroups([$filterGroup]);

		// Get specific properties
		$searchRequest->setProperties(['firstname', 'lastname']);

		// @var CollectionResponseWithTotalSimplePublicObject $contactsPage
		$contactsPage = $hubspot->crm()->contacts()->searchApi()->doSearch($searchRequest);

		$results = $contactsPage->getResults();

		$idContattoHubSpot = 0;
		
		if (!empty($results))
			$idContattoHubSpot = $results[0]->getId();
		
		$contactInput = new \HubSpot\Client\Crm\Contacts\Model\SimplePublicObjectInput();
		
		if (isset($valori["nome"]))
		{
			$valoriFinali = array(
				'email' 	=>	$email,
				'firstname'	=>	$valori["nome"] ?? '',
				'lastname'	=>	$valori["cognome"] ?? '',
			);
		}
		else
			$valoriFinali = $valori;
		
		$contactInput->setProperties($valoriFinali);
		
		try
		{
			if ($idContattoHubSpot)
				$contact = $hubspot->crm()->contacts()->basicApi()->update($idContattoHubSpot, $contactInput);
			else
				$contact = $hubspot->crm()->contacts()->basicApi()->create($contactInput);
			
			return true;
		} catch (Exception $e) {
			return false;
// 			print_r($e->getResponseObject()->getMessage());
		}

		return null;
	}
	
	public function gCampiForm()
	{
		return 'titolo,attivo,secret_1';
	}
	
	public function isAttiva()
	{
		if (trim($this->params["secret_1"]))
			return true;
		
		return false;
	}
	
	public function gSecret1Label()
	{
		return "Access Token";
	}
}
