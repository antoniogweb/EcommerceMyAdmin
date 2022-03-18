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

if (!defined('EG')) die('Direct access not allowed!');

class TraduzioniModel extends GenericModel {
	
	public static $contestoStatic = "front";
	
	public static $edit = false;
	
	public $contestoCorrente = null;
	
	public function __construct() {
		$this->_tables='traduzioni';
		$this->_idFields='id_t';
		
		$this->_lang = 'It';

// 		$this->formStruct = array
// 		(
// 			'entries' 	=> 	array(
// 				'valore'		=>	array(
// 					'className'		=>	'dettagli',
// 				),
// 				'lingua'	=>	array(
// 					"options" => Parametri::$opzioniCampoLingua,
// 					"reverse" => "yes",
// 				),
// 			),
// 		);
		
		parent::__construct();
	}
	
	public static function checkTraduzioneAttiva()
	{
		if (isset($_GET["traduzioni1234"]) and strcmp($_GET["traduzioni1234"],"Y") === 0)
		{
			setcookie("traduzioni", "Y", time() + 2592000, '/');
			
			$_COOKIE["traduzioni"] = "Y";
			
			self::$edit = true;
		}
		else if (isset($_GET["traduzioni1234"]) and strcmp($_GET["traduzioni1234"],"N") === 0)
		{
			setcookie("traduzioni", "Y", time() - 3600, '/');
			
			if (isset($_COOKIE["traduzioni"]))
				unset($_COOKIE["traduzioni"]);
			
			self::$edit = false;
		}
		
		if (isset($_COOKIE["traduzioni"]))
			self::$edit = true;
	}
	
	public function ottieniTraduzioni($contesto = null)
	{
		if (!isset($contesto))
			$contesto = self::$contestoStatic;
		
		$tempLang = getLinguaIso();
		
		if (isset($this->contestoCorrente) && isset(Lang::$i18n[$tempLang]) && !empty(Lang::$i18n[$tempLang]) && $contesto == $this->contestoCorrente)
			return Lang::$i18n[$tempLang];
		
		$this->contestoCorrente = $contesto;
		
		$values = $this->clear()->where(array(
			"lingua"	=>	sanitizeAll(getLinguaIso()),
			"contesto"	=>	sanitizeDb($contesto),
		))->toList("chiave", "valore")->send();
		
		Lang::$i18n[$tempLang] = $values;
	}
	
	public function getTraduzione($chiave, $function = "none", $contesto = null)
	{
		if (!isset($contesto))
			$contesto = self::$contestoStatic;
		
		$res = $this->clear()->where(array(
			"chiave"	=>	sanitizeDb($chiave),
			"lingua"	=>	sanitizeAll(getLinguaIso()),
			"contesto"	=>	sanitizeDb($contesto),
		))->record();
			
		if (count($res) > 0)
		{
			$iframe = self::$contestoStatic == "back" ? "iframe" : "";
			$href = self::$contestoStatic == "back" ? "href='".Url::getRoot("traduzioni/form/update/".$res["id_t"])."'" : "";
			
			$valore = call_user_func($function,$res["valore"]);
			return "<span class='blocco_traduzione'>".htmlentitydecode($valore)."<img $href data-id='".$res["id_t"]."' class='edit_traduzione $iframe' src='".Url::getFileRoot()."Public/Img/mini-plus.jpg' /></span>";
		}
		
		return "";
	}
	
	public function editLingua($lingua, $record)
	{
		$traduzione =  $this->clear()->where(array(
			"lingua"	=>	$lingua,
			"chiave"	=>	sanitizeDb($record["traduzioni"]["chiave"]),
			"contesto"	=>	"front",
		))->record();
		
		if (empty($traduzione))
		{
			$string = $record["traduzioni"]["chiave"];
			
			$this->values = array(
				"chiave"	=>	sanitizeDb($string),
				"valore"	=>	sanitizeDb($string),
				"lingua"	=>	$lingua,
				"contesto"	=>	"front",
				"applicativo"	=>	$record["traduzioni"]["applicativo"],
			);
			
			if ($this->insert())
				$traduzione =  $this->selectId($this->lId);
		}
		
		if (!empty($traduzione))
		{
			$valore = $traduzione["valore"];
			
			if (isset($_GET["esporta"]) && $_GET["esporta"] == "Y")
				$valore = htmlentitydecode($valore);
			
			return "<div style='position:relative;'><textarea style='min-width:300px;' id-t='".$traduzione["id_t"]."' class='form-control edit-traduzione' name='en' >".$valore."</textarea><i style='display:none;position:absolute;top:5px;right:5px;' class='fa fa-refresh fa-spin'></i><i style='display:none;position:absolute;top:5px;right:5px;' class='fa fa-check verde'></i></div>";
		}
		
		return "";
	}
	
	public function editIt($record)
	{
		return $this->editLingua("it", $record);
	}
	
	public function update($id = null, $where = null)
	{
		$this->values["tradotta"] = 1;
		
		return parent::update($id, $where);
	}
	
	public function elimina($record)
	{
		return "<a href='".Url::getRoot()."traduzioni/elimina/".$record["traduzioni"]["id_t"]."' class='text text-danger text_16 elimina_traduzione'><i class='fa fa-trash'></i></a>";
	}
}
