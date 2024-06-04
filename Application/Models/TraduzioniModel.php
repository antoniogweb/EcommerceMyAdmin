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

if (!defined('EG')) die('Direct access not allowed!');

class TraduzioniModel extends GenericModel {
	
	public static $contestoStatic = "front"; // usato quando leggo la lingua
	public static $contestoStaticEdit = "front"; // usato in edit lingua
	
	public static $edit = false;
	
	public static $bckLingua = null;
	public static $bckContesto = null;
	
	public $contestoCorrente = null;
	
	private static $instance = null; //instance of this class
	
	public function __construct() {
		$this->_tables='traduzioni';
		$this->_idFields='id_t';
		
		$this->_lang = 'It';
		
		parent::__construct();
	}
	
	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className();
		}

		return self::$instance;
	}
	
	public static function checkTraduzioneAttiva()
	{
		
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
			"contesto"	=>	self::$contestoStaticEdit,
		))->record();
		
		if (empty($traduzione))
		{
			$string = $record["traduzioni"]["chiave"];
			
			$this->values = array(
				"chiave"	=>	sanitizeDb($string),
				"valore"	=>	sanitizeDb($string),
				"lingua"	=>	$lingua,
				"contesto"	=>	self::$contestoStaticEdit,
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
			
			return "<div style='position:relative;'><textarea style='min-width:300px;' id-t='".$traduzione["id_t"]."' class='form-control edit-traduzione' name='en' >".$valore."</textarea>".F::getRefreshIconOnInput()."</div>";
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
	
	public static function sLingua($lingua, $contesto = "front")
	{
		if (Params::$lang != $lingua || self::$contestoStatic !=  $contesto)
		{
			// salvo i valori
			self::$bckLingua = Params::$lang;
			self::$bckContesto = self::$contestoStatic;
			
			self::$contestoStatic = $contesto;
			Params::$lang = $lingua;
			$tradModel = new TraduzioniModel();
			$tradModel->ottieniTraduzioni();
		}
	}
	
	public static function rLingua()
	{
		Params::$lang = self::$bckLingua;
		self::$contestoStatic = self::$bckContesto;
		$tradModel = new TraduzioniModel();
		$tradModel->ottieniTraduzioni();
	}
}
