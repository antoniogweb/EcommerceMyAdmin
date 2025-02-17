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

class IntegrazioniloginModel extends GenericModel {
	
	public static $modulo = null;
	
	public function __construct() {
		$this->_tables='integrazioni_login';
		$this->_idFields='id_integrazione_login';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
    
	public function setFormStruct($id = 0)
	{
		$record = $this->selectId($id);
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'attivo'	=>	self::$entryAttivo,
				'secret_key'		=>	array(
					'labelString'	=>	self::getApp($record["codice"])->gSecretLabel(),
					'type'	=>	"Password",
					'fill'	=>	true,
					'attributes'	=>	'autocomplete="new-password"',
				),
				'instagram_secret_key'		=>	array(
					'labelString'	=>	"Instagram secret key",
					'type'	=>	"Password",
					'fill'	=>	true,
					'attributes'	=>	'autocomplete="new-password"',
				),
				'colore_background_in_esadecimale'	=>	array(
					'labelString'=>	'Colore del pulsante',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Il colore, in esadecimale (compreso di #), dello sfondo del pulsante")."</div>"
					),
				),
				'html_icona'	=>	array(
					'labelString'=>	"Codice HTML dell'icona",
				),
				'testo_introduttivo' =>	array(
					'type'		 =>	'Textarea',
					'labelString'=>	'Testo introduttivo del pulsante',
					'className'		=>	'text_input form-control editor_textarea',
				),
				'access_token'	=>	array(
					'labelString'=>	'Facebook Login Access Token',
					'wrap'		=>	array(
						null,
						null,
						"<a style='margin-left:30px;' class='make_spinner' href='".Url::getRoot()."integrazionilogin/ottieniaccesstoken/".$record["codice"]."'><i class='fa fa-refresh'></i> ".gtext("Ottieni Facebook Login Access Token")."</a>"
					),
				),
				'instagram_access_token'	=>	array(
					'labelString'=>	'Instagram Access Token',
					'wrap'		=>	array(
						null,
						null,
						"<a style='margin-left:30px;' class='make_spinner' href='".Url::getRoot()."integrazionilogin/ottieniinstagramaccesstoken/".$record["codice"]."'><i class='fa fa-refresh'></i> ".gtext("Ottieni Instagram Access Token")."</a>"
					),
				),
			),
		);
	}
	
	public function attivo($record)
	{
		return $record[$this->_tables]["attivo"] ? gtext("Sì") : gtext("No");
	}
	
	public static function getApp($codice)
	{
		$i = new IntegrazioniloginModel();
		
		if (!isset(self::$modulo))
		{
			$attivo = $i->clear()->where(array(
				"codice"	=>	sanitizeDb($codice),
			))->record();
			
			if (!empty($attivo) && file_exists(LIBRARY."/Application/Modules/ExternalLogin/".$attivo["classe"].".php"))
			{
				require_once(LIBRARY."/Application/Modules/ExternalLogin.php");
				require_once(LIBRARY."/Application/Modules/ExternalLogin/".$attivo["classe"].".php");
				
				$objectReflection = new ReflectionClass($attivo["classe"]);
				$object = $objectReflection->newInstanceArgs(array($attivo));
				
				self::$modulo = $object;
			}
		}
		
		return $i;
	}
	
	public function __call($metodo, $argomenti)
	{
		if (isset(self::$modulo) && method_exists(self::$modulo, $metodo))
			return call_user_func_array(array(self::$modulo, $metodo), $argomenti);

		return false;
	}
	
	public static function integrazioneAttiva()
	{
		return self::getModulo()->isAttiva();
	}
	
	// Rinnova l'access token di Instagram
	public function rinnovaInstagramAccessToken()
	{
		$result = IntegrazioniloginModel::getApp("FACEBOOK_LOGIN")->refreshInstagramAccessToken();
		
		if (isset($result["access_token"]))
		{
			$this->sValues(array(
				"instagram_access_token"	=>	$result["access_token"],
			));
			
			$this->update(null, array(
				"codice"	=>	"FACEBOOK_LOGIN",
			));
		}
	}
	
	// Recupera i file multimediali da Instagram
	public function getInstagramMedia()
	{
		if (v("path_instagram_media_json_file"))
		{
			$json = IntegrazioniloginModel::getApp("FACEBOOK_LOGIN")->recuperaInstagramMedia();
			
			if ($json)
				FilePutContentsAtomic(v("path_instagram_media_json_file"), $json);
		}
	}
}
