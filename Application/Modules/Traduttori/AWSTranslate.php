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

class AWSTranslate extends Traduttore
{
	public $placeholders = array();

	public function gCampiForm()
	{
		return 'titolo,attivo,regione,key_1,key_2';
	}
	
	public function isAttivo()
	{
		if ($this->params["attivo"] && trim($this->params["key_1"]) && trim($this->params["key_2"]) && trim($this->params["regione"]))
			return true;
		
		return false;
	}
	
	public function editFormStruct($model, $record)
	{
		$model->formStruct["entries"]["key_1"]["labelString"] = "Chiave AWS";
		$model->formStruct["entries"]["key_2"]["labelString"] = "Secret AWS";
		$model->formStruct["entries"]["key_2"]["type"] = "Password";
		$model->formStruct["entries"]["key_2"]["fill"] = true;
	}
	
	public function traduci($textToTranslate, $currentLanguage, $targetLanguage)
	{
		if (!$this->isAttivo())
			return false;
		
		require_once(LIBRARY . '/External/libs/vendor/autoload.php');
		
		$client = new Aws\Translate\TranslateClient([
			'region' => $this->getParam("regione"),
			'version' => 'latest',
			'credentials' => [
				'key'    => $this->getParam("key_1"),
				'secret' => $this->getParam("key_2"),
			],
		]);
		
		try {
			if (isset(self::$tabellaCorrezioni[$targetLanguage][$textToTranslate]))
				return self::$tabellaCorrezioni[$targetLanguage][$textToTranslate];

			// Elaboro il testo per gestire i placeholder
			$textToTranslate = $this->elaboraTesto($textToTranslate);

			$result = $client->translateText([
				'SourceLanguageCode' => $currentLanguage,
				'TargetLanguageCode' => $targetLanguage,
				'Text' => $textToTranslate,
			]);
			
			return $this->ripristinaPlaceholder($result['TranslatedText'])."\n";
		} catch(Aws\Exception\AwsException $e) {
			
			return false;
			// output error message if fails
// 			echo "Failed: ".$e->getMessage()."\n";
		}
	}
}
