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

ini_set("memory_limit",v("ricerca_semantica_memory_limit"));

class BaseAssistentevirtualeController extends BaseController
{
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_assistente_frontend"))
			$this->responseCode(403);
		
		if (v("assistente_virtuale_ip_permessi"))
		{
			$ipPermessi = explode(",", v("assistente_virtuale_ip_permessi"));
			
			if (!in_array(getIp(), $ipPermessi))
				$this->responseCode(403);
		}
		
		$_GET["partial"] = "Y";
		
		$this->load('header');
		$this->load('footer','last');
		
		$data["arrayLingue"] = array();
		
		$this->append($data);
	}

	public function index()
	{
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/".Url::routeToUrl("virtual-assistant")."/";
		}
		
		$idChat = $this->m("AirichiesteModel")->getChat();
		$data["messaggi"] = $this->m("AirichiestemessaggiModel")->getMessaggi((int)$idChat);
		
		$this->append($data);
		
		$this->load('index');
	}
	
	public function messaggi()
	{
		$this->clean();
		
		$idChat = $this->m("AirichiesteModel")->getChat();
		$data["messaggi"] = $this->m("AirichiestemessaggiModel")->getMessaggi((int)$idChat);
		
		$this->append($data);
		$this->load('messaggi');
	}
	
	public function request()
	{
		$this->clean();
		
		Session::open();
		
		$messaggio = $this->request->post("messaggio","");
		$messaggio = strip_tags(trim($messaggio));
		
		$idChat = $this->m("AirichiesteModel")->getChat(true);
		
		if ($idChat)
		{
			Airichiesteresponse::$idRichiesta = (int)$idChat;
			
			$this->m("AirichiesteModel")->messaggio((int)$idChat, $messaggio);
		}
		
		Session::restore();
	}
}
