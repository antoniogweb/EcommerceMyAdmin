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

class ChatGPT35Turbo extends ModelloAI
{
	private $client = null;

	public function gCampiForm()
	{
		return 'titolo,attivo,predefinito,key_1';
	}
	
	public function isAttivo()
	{
		if ($this->params["attivo"] && trim($this->params["key_1"]))
			return true;
		
		return false;
	}
	
	public function editFormStruct($model, $record)
	{
		$model->formStruct["entries"]["key_1"]["labelString"] = "Chiave segreta";
		$model->formStruct["entries"]["key_1"]["type"] = "Password";
		$model->formStruct["entries"]["key_1"]["fill"] = true;
	}

	protected function getClient()
	{
		if (!isset($this->client))
		{
			require_once(LIBRARY . '/External/libs/vendor/autoload.php');

			if (class_exists("OpenAI"))
				$this->client = OpenAI::client($this->getParam("key_1"));
		}

		return $this->client;
	}

	protected function elaboraMessaggi($messaggi, $contesto = "")
	{
		$messaggiChat = $this->creaStreamContesto($contesto);

		foreach ($messaggi as $m)
		{
			$messaggiChat[] = $m;
		}

		return $messaggiChat;
	}

	public function chat($messaggi, $contesto = "")
	{
		$client = $this->getClient();

		if (isset($client))
		{
			$messaggi = $this->elaboraMessaggi($messaggi, $contesto);

			try
			{
				// print_r($messaggi);die();
				$response = $client->chat()->create([
					'model' => $this->getParam("nome_modello"),
					'messages' => $messaggi,
				]);

				$responseArray = $response->toArray();

				// var_dump($responseArray);

				if (isset($responseArray["choices"]) && is_array($responseArray["choices"]) && count($responseArray["choices"]) > 0)
					return array(1, $responseArray["choices"][0]["message"]["content"]);
			} catch (Exception $e) {
					return array(0, $e->getMessage());
			}

			return array("","");
		}
	}
}
