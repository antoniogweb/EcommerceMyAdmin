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

require_once(LIBRARY."/Application/Modules/ModelliAI/ChatGPT35Turbo.php");

class GPTEmbeddings extends ChatGPT35Turbo
{
	public function embeddings($text)
	{
		$client = $this->getClient();

		if (isset($client))
		{
			try
			{
				$response = $client->embeddings()->create([
					'model' => 'text-embedding-3-small',
					'input' => $text,
				]);
				
				$responseArray = $response->toArray();
				
				if (isset($responseArray["data"][0]["embedding"]) && is_array($responseArray["data"][0]["embedding"]) && count($responseArray["data"][0]["embedding"]) > 0)
					return json_encode($responseArray["data"][0]["embedding"]);
				else
					return "";
			} catch (Exception $e) {
				return "";
			}
		}
		
		return "";
	}
}
