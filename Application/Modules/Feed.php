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

class Feed
{
	use Modulo;
	
	protected $usato = false;
	
	protected function getQueryString()
	{
		return htmlentitydecode($this->params["query_string"]);
	}
	
	protected function elaboraNodiAttributi($nodo, $attributi)
	{
		if (count($attributi) > 0)
		{
			foreach ($attributi as $a)
			{
				if (trim($a["tipologia"]) && method_exists($this, "tagName".ucfirst(strtolower(trim($a["tipologia"])))))
				{
					$xmlTabName = call_user_func(array($this, "tagName".ucfirst(strtolower(trim($a["tipologia"])))));
					
					$nodo[$xmlTabName] = $a["valore"];
				}
			}
		}
		
		return $nodo;
	}
	
	protected function linkAlleVarianti()
	{
		return VariabiliModel::combinazioniLinkVeri() ? (int)$this->params["link_a_combinazione"] : null;
	}
	
	public function gCampiForm()
	{
		return 'titolo,attivo,link_a_combinazione,usa_token_sicurezza,token_sicurezza,query_string,tempo_cache,url_feed';
	}
	
	public function getFeedUrl()
	{
		$feedUrl = 'feed/prodotti/'.strtolower($this->params["codice"]).'/';
		
		if ($this->params["usa_token_sicurezza"])
			$feedUrl .= $this->params["token_sicurezza"];
		
		return $feedUrl;
	}
	
	public function getRoutesOfFeed()
	{
		if (!$this->params["url_feed"])
			return null;
		
		$feedUrl = $this->getFeedUrl();
		
		return array($this->params["url_feed"] => $feedUrl);
	}
	
	public function editFormStruct($model, $record)
	{
		$model->formStruct["entries"]["frequenza_modifica"]["type"] = "Select";
		$model->formStruct["entries"]["frequenza_modifica"]["options"] = array(
			"always"	=>	"always",
			"hourly"	=>	"hourly",
			"daily"		=>	"daily",
			"weekly"	=>	"weekly",
			"monthly"	=>	"monthly",
			"yearly"	=>	"yearly",
			"never"		=>	"never",
		);
		$model->formStruct["entries"]["frequenza_modifica"]["reverse"] = "yes";
	}
}
