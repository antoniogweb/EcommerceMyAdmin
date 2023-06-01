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

class MenuadminModel extends HierarchicalModel {
	
	public $titleFieldName = "titolo";
	public $checkAll = false;
	
	public static $iconaLista = "fa-list";
	public static $iconaAggiungi = "fa-plus-circle";
	
	public static $tipo = array(
		"LINK_ELENCO"	=>	"Link elenco",
		"LINK_AGGIUNGI"	=>	"Link aggiungi",
		"LABEL"			=>	"Label",
		"TITOLO"		=>	"Titolo",
		"LIBERO"		=>	"Libero",
	);
	
	public static $activeClass = array("active","in");
	
	public static $classiVociMenu = array();
	public static $currentAction = null;
	
	public static $contesto = array(
		"sito"			=>	"CMS",
		"ecommerce"		=>	"E-commerce",
		"utenti"		=>	"Configurazione",
		"marketing"		=>	"Marketing",
	);
	
	public function __construct() {
		$this->_tables = 'menu_admin';
		$this->_idFields = 'id_menu_admin';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}

	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'titolo'		=>	array(
					'labelString'=>	'Titolo',
				),
				'id_p'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Genitore',
					'options'	=>	$this->buildSelect(),
					'reverse' => 'yes',
					'idName'	=>	'combobox',
				),
				'tipo'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Tipo',
					'options'	=>	self::$tipo,
					'reverse' => 'yes',
				),
				'contesto'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Contesto',
					'options'	=>	self::$contesto,
					'reverse' => 'yes',
				),
			),
		);
	}
	
	//get the indentation of the row
	public function indentList($id, $alias = true, $editLink = true, $useHtml = true)
	{
		$clean["id"] = (int)$id;
		
		$depth = $this->clear()->depth($clean["id"]);
		$field = isset(self::$currentRecord) ? self::$currentRecord["node"] : $this->clear()->selectId($clean["id"]);
		
		$str = "";
		$strAlias = "";
		for($i = 0;$i < $depth;$i++)
		{
			$str .= $useHtml ? "<span style='padding-right:3px;'>-</span>" : "- ";
			$strAlias .= $useHtml ? "<span style='padding-right:3px;'>&nbsp</span>" : "- ";
		}
		
		if ($this->section)
			$str = "<div class='record_id' style='display:none'>$id</div><i title='Trascina per ordinare' class='ancora_ordinamento fa fa-arrows text text-warning' style='padding-right:3px;font-size:12px;'></i>";
		
		$strAlias = strcmp($strAlias,"") !== 0 ? $strAlias."&nbsp" : "";
		
		$titolo = $editLink ? "<a href='".Url::getRoot().$this->applicationUrl.$this->controller."/form/update/".$clean["id"].self::$viewStatus."'>".$field[$this->titleFieldName]."</a>" : $field[$this->titleFieldName];
		
		return $str." ".$titolo;
	}
	
	public static function creaMenu($contesto = "sito", $idP = 1)
	{
		$m = new MenuadminModel();
		
		$arrayMenu = $m->creaArrayMenu($contesto, $idP);
		
// 		print_r($arrayMenu);
// 		echo MenuadminModel::$currentAction;
		
		return $m->creaHtmlMenu($arrayMenu);
	}
	
	public function creaHtmlMenu($arrayMenu)
	{
		$htmlMenu = "";
		
		foreach ($arrayMenu as $voce)
		{
			ob_start();
			include(ROOT."/Application/Views/voce_menu.php");
			$htmlMenu .= ob_get_clean();
		}
		
		return $htmlMenu;
	}
	
	public static function getIcona($tipo, $icona)
	{
		switch ($tipo)
		{
			case "LINK_ELENCO":
				return self::$iconaLista;
				break;
			case "LINK_AGGIUNGI":
				return self::$iconaAggiungi;
				break;
		}
		
		return $icona;
	}
	
	public static function getTitolo($tipo, $titolo)
	{
		switch ($tipo)
		{
			case "LINK_ELENCO":
				return gtext("Lista");
				break;
			case "LINK_AGGIUNGI":
				return gtext("Aggiungi");
				break;
		}
		
		return $titolo;
	}
	
	public function creaArrayMenu($contesto = "sito", $idP = 1)
	{
		$res = $this->clear()->where(array(
			"contesto"	=>	$contesto,
			"id_p"		=>	(int)$idP,
			"attivo"	=>	1,
		))->orderBy("lft")->findAll(false);
		
		$arrayMenu = array();
		
		foreach ($res as $r)
		{
			$r = htmlentitydecodeDeep($r);
			
			$temp = $r;
			
			$temp["icona"] = self::getIcona($temp["tipo"], $temp["icona"]);
			$temp["titolo"] = self::getTitolo($temp["tipo"], $temp["titolo"]);
			$temp["controller"] = $temp["controller"] ? explode(",",$temp["controller"]) : array();
			$temp["action"] = $temp["action"] ? explode(",",$temp["action"]) : array();
			
			$temp["url"] = preg_replace_callback('/\[variabile (.*?)\]/', array('VariabiliModel', 'getVariabileMatches') ,$temp["url"]);
			
			$temp["figli"] = $this->creaArrayMenu($contesto, $r["id_menu_admin"]);
			$arrayMenu[] = $temp;
		}
		
		return $arrayMenu;
	}
	
	public static function classeCurrent($controller = array(), $action = array())
	{
		foreach ($controller as $c)
		{
			if (isset(self::$classiVociMenu[$c]))
			{
				if (empty($action) || in_array(MenuadminModel::$currentAction, $action))
					return "active";
			}
		}
		
		return "";
	}
}
