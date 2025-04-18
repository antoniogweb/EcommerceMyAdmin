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

class MenuModel extends HierarchicalModel {
	
	public static $lingua = "it";
	
	public $controller = "menu";
	public $checkAll = true;
	
	public $menuLang = "it";
	public $urlLang = null;
	
	public $titoloMenu = "GESTIONE MENU";
	public $orderBy = 'menu.id_order desc';
	
	protected $_tables = 'menu';
	protected $_idFields = 'id_m';
	protected $_idOrder = 'id_order';
	protected $_lang = 'It';
	
	public function __construct() {
		
		parent::__construct();
		
		$this->urlLang = isset($this->menuLang) ? $this->menuLang . "/" : null;
		
		if (isset($_GET["lingua"]) and in_array($_GET["lingua"], BaseController::$traduzioni))
			self::$lingua = sanitizeAll($_GET["lingua"]);
		
		$this->titoloMenu .= " (".strtoupper(self::$lingua).")";
	}

	public function setFormStruct($id = 0)
	{
		$opzioniVoceMenu =  array(
			"cat"=>"Categoria",
			"cont"=>"Contenuto",
			"home"=>"Home Page",
			"libero" => "Libero",
			"esterno" => "Esterno",
			"custom" => "Codice custom",
		);
		
		if (v("usa_marchi"))
			$opzioniVoceMenu["marchio"] = gtext("Marchio");
		
		if (v("usa_tag"))
			$opzioniVoceMenu["tag"] = gtext("Tag");
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'title'		=>	array(
					'labelString'=>	'Titolo',
				),
				'attivo'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Pubblicata?',
					'options'	=>	array('sì'=>'Y','no'=>'N'),
				),
				'link_to'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Tipo di link',
					'options'	=>	$opzioniVoceMenu,
					'reverse' => 'yes',
					'idName'  => 'tipo_link',
				),
				'link_alias'		=>	array(
					'labelString'=>	'URL (alias) da linkare',
					'entryClass'  => 'alias_Select',
				),
				'file_custom_html'		=>	array(
					'labelString'=>	'File PHP con codice custom',
					'entryClass'  => 'form_input_text file_Select',
				),
				'active_link'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Il link è attivo?',
					'options'	=>	array('sì'=>'Y','no'=>'N'),
				),
				'id_p'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Genitore',
					'options'	=>	$this->buildSelect(),
					'reverse' => 'yes',
// 					'idName'	=>	'combobox',
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
					'wrap'	=>	array(null,null,"<div>","</div>"),
				),
				'id_c'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Categoria da linkare',
					'options'	=>	$this->buildCategorySelect(),
					'reverse' => 'yes',
					'entryClass'  => 'form_input_text cat_Select',
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
					'wrap'	=>	array(null,null,"<div>","</div>"),
				),
				'id_page'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Contenuto da linkare',
					'options'	=>	$this->buildContentSelect(),
					'reverse' => 'yes',
					'entryClass'  => 'form_input_text cont_Select',
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
					'wrap'	=>	array(null,null,"<div>","</div>"),
				),
				'id_marchio'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	gtext("Marchio"),
					'options'	=>	$this->selectMarchi(false),
					'reverse' => 'yes',
// 					'idName'	=>	'combobox',
					'entryClass'  => 'form_input_text marchi_Select',
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
					'wrap'	=>	array(null,null,"<div>","</div>"),
				),
				'id_tag'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	gtext("Tag"),
					'options'	=>	$this->selectTag(false),
					'reverse' => 'yes',
					'idName'	=>	'combobox',
					'entryClass'  => 'form_input_text tag_Select',
				),
				'id_m'	=>	array(
					'type'		=>	'Hidden'
				),
			),
		);
	}
	
	public function selectTag($empty = true)
	{
		if (!TagModel::numeroRecord())
			$empty = true;
		
		return parent::selectTag($empty);
	}
	
	public function selectMarchi($empty = true, $where = array())
	{
		if (!MarchiModel::numeroRecord())
			$empty = true;
		
		return parent::selectMarchi($empty);
	}
	
	public function getTableN()
	{
		return $this->_tables;
	}
	
	public function getPrimaryN()
	{
		return $this->_idFields;
	}
	
	public function getSelectMenu($tree, $complete = true)
	{
		if (count($tree) > 0)
		{
			$requestUri = isset($_SERVER['REQUEST_URI']) ? strtok($_SERVER['REQUEST_URI'],"?") : "";
			
			$menuHtml = null;
			
			if ($complete)
			{
				$menuHtml .= "<select id='select_".strtolower(get_class($this))."' class='select_".strtolower(get_class($this))."' name='select_".strtolower(get_class($this))."'>\n";
			}
			$menuHtml .= "\t<option class='option_not_active_link' value='http://".DOMAIN_NAME."'>-- Seleziona --</option>";
			foreach ($tree as $node)
			{
				$notActiveClass = strcmp($node["node"]["active_link"],"N") === 0 ? "option_not_active_link" : "option_active_link";
				$rel = strcmp($node["node"]["link_to"],"esterno") === 0 ? "rel='nofollow'" : null;
				
				$selected = null;
				if (strcmp(Domain::$name.$requestUri, $node["node"]["link_alias"]) === 0)
				{
					$selected = "selected='".$node["node"]["link_alias"]."'";
				}
						
				$depth = (int)$node["aggregate"]["depth"] -1;
				$menuHtml .= "\t<option class='$notActiveClass' $rel value='".$node["node"]["link_alias"]."' $selected>".str_repeat(" - ",$depth).$node["node"][$this->titleFieldName]."</option>\n";
			}
			if ($complete)
			{
				$menuHtml .= "</select>\n";
			}
			return $menuHtml;
		}
		return "";
	}
	
	//create the HTML of the menu
	//$tree: nodes as given by getTreeWithDepth
	//$complete: if true returns also the opening and closing <ul>
	public function getMenu($tree, $complete = true, $lingua = null, $simple = false, $mobile = false)
	{
		if (count($tree) > 0)
		{
			$requestUri = isset($_GET['url']) ? "/".$_GET['url'] : "/";

			$tree[] = $tree[count($tree) -1];
			$depth = $tree[count($tree) -1]["aggregate"]["depth"] = $tree[0]["aggregate"]["depth"];
			$tree[count($tree) -1]["is_last"] = 1;
			
			$count = 0;
			
			$menuHtml = null;
			
			if ($complete)
				$menuHtml .= "<ul id='ul_".strtolower(get_class($this))."' class='elementor-nav-menu menu ul_".strtolower(get_class($this))."'>";
			
			$indice = 0;
			
			$hasChildClass = $hasChildLinkClass = $hasChildLinkAttributes = "";
			
			$submenu_wrap_open = v("submenu_wrap_open");
			$submenu_wrap_close = v("submenu_wrap_close");
			
			// Wrap HTML da file del tema
			$submenu_wrap_open_full = $submenu_wrap_close_full = null;
			
			$linkTextWrapTag = v("linkTextWrapTag");
			$linkTextWrapClass = v("linkTextWrapClass");
			
			if ($simple)
				$submenu_wrap_open = $submenu_wrap_close = "";
			
			foreach ($tree as $node)
			{
				// Appiattisci il menù se è quello semplice e se l'opzione è attiva
				if ($simple && v("appiattisci_menu_semplice"))
					$node["aggregate"]["depth"] = 1;

				// Wrap HTML da file del tema
				if (!$simple && file_exists(tpf("_Menu/submenu_wrap_open.php")) && file_exists(tpf("_Menu/submenu_wrap_close.php")))
				{
					ob_start();
					include(tpf("_Menu/submenu_wrap_open.php"));
					$submenu_wrap_open_full = ob_get_clean();
					
					ob_start();
					include(tpf("_Menu/submenu_wrap_close.php"));
					$submenu_wrap_close_full = ob_get_clean();
				}
				
				$linkText = $node["node"][$this->titleFieldName];
				
				if ($linkTextWrapTag)
					$linkText = wrap($linkText, array(
						$linkTextWrapTag	=>	$linkTextWrapClass,
					));
				
				$menuLinkClass = v("menu_link_class");
				$menuItemClass = v("menu_item_class");
				
				if (isset($tree[($indice+1)]) && $tree[($indice+1)]["aggregate"]["depth"] > $node["aggregate"]["depth"])
				{
					$hasChildClass = v("has_child_class");
					$inLinkHtmlAfter = v("in_link_html_after");
					$hasChildLinkClass = v("has_child_link_class");
					$hasChildLinkAttributes = v("has_child_link_attributes");
				}
				else
				{
					$hasChildClass = "";
					$inLinkHtmlAfter = "";
					$hasChildLinkClass = "";
					$hasChildLinkAttributes = "";
				}
				
				$subMenuItemClass = $subMenuLinkClass = "";
				
				if ($node["aggregate"]["depth"] > $depth)
				{
					$depth = $node["aggregate"]["depth"];
					
					$menuHtml = substr($menuHtml, 0, -5);
					
					$subMenuClass = "";
					
					if ($depth > 1)
					{
						$subMenuClass = v("submenu_class")." ";
						$subMenuLinkClass = v("submenu_link_class");
						$subMenuItemClass = v("submenu_item_class");
						$menuLinkClass = "";
						$menuItemClass = "";
					}
					
					if ($simple)
						$menuHtml .= $submenu_wrap_open."<ul>";
					else
					{
						if (isset($submenu_wrap_open_full))
							$menuHtml .= $submenu_wrap_open_full;
						else
							$menuHtml .= $submenu_wrap_open."<ul class='$subMenuClass ul_menu_level ul_menu_level_".$depth."'>";
					}
				}
				if ($node["aggregate"]["depth"] < $depth)
				{
					$diff = (int)($depth - $node["aggregate"]["depth"]);
					
					if (isset($submenu_wrap_close_full))
						$menuHtml .= str_repeat("</ul>".$submenu_wrap_close_full."</li>", $diff);
					else
						$menuHtml .= str_repeat("</ul>".$submenu_wrap_close."</li>", $diff);
					
					$depth = $node["aggregate"]["depth"];
				}
				if (!isset($node["is_last"]))
				{
					if ($depth > 1)
					{
						$subMenuClass = v("submenu_class")." ";
						$subMenuLinkClass = v("submenu_link_class");
						$subMenuItemClass = v("submenu_item_class");
						$menuLinkClass = "";
						$menuItemClass = "";
					}
					
					$currClass = $currClassLink = null;
					if ((strcmp($requestUri,"/") === 0 or in_array(trim($requestUri,"/"),Params::$frontEndLanguages)) and strcmp($node["node"]["link_to"],"home") === 0)
					{
						$currClass = v("current_menu_item");
						$currClassLink = v("current_menu_item_link");
					}
					else if (strcmp($requestUri,"/") !== 0 and strcmp($node["node"]["link_to"],"home") !== 0)
					{
						$linkAlias = $node["node"]["link_alias"] ? $node["node"]["link_alias"] : $node["node"]["alias"];
						
						$pattern = str_replace(".html","",str_replace(DS, '\\'.DS, $linkAlias));
						$pattern = rtrim($pattern,"\/")."\/";
						
						if (isset($lingua))
						{
							if (Params::$country)
								$domain = isset($lingua) ? str_replace("/".$lingua.Params::$languageCountrySeparator.Params::$country,"",Domain::$name) : Domain::$name;
							else
								$domain = isset($lingua) ? str_replace("/".$lingua,"",Domain::$name) : Domain::$name;
						}
						
						$subject = str_replace(".html","",$domain.$requestUri);;
						$subject = rtrim($subject,"/")."/";

						if (v("attiva_nazione_nell_url"))
							$pattern = str_replace("[COUNTRY]", Params::$country, $pattern);
						
						if (preg_match("/(".$pattern.")/",$subject))
						{
							$currClass = v("current_menu_item")." current_page_item current_item";
							$currClassLink = v("current_menu_item_link");
						}
					}
					$notActiveClass = strcmp($node["node"]["active_link"],"N") === 0 ? "not_active_link" : null;
					$target = strcmp($node["node"]["link_to"],"esterno") === 0 ? "target='_blank'" : null;
					
					$classeCssPersonalizzata = isset($node["node"]["classe_css_personalizzata"]) ? $node["node"]["classe_css_personalizzata"] : "";
					
					if ($node["node"]["link_to"] != "custom")
					{
						ob_start();
						if ($simple)
							include(tpf("_Menu/simple_node.php"));
						else
							include(tpf("_Menu/full_node.php"));
						$menuHtml .= ob_get_clean();
					}
					else
					{
						ob_start();
						include(tpf("_Menu/".$node["node"]["file_custom_html"]));
// 						include ROOT."/Application/Views/_Menu/".$node["node"]["file_custom_html"];
						$menuHtml .= ob_get_clean();
					}
				}
				$count++;
				
				$indice++;
			}
			if ($complete)
			{
				$menuHtml .= "</ul>\n";
			}
			
			if (v("attiva_nazione_nell_url"))
				$menuHtml = str_replace("[COUNTRY]", Params::$country, $menuHtml);
			
			return $menuHtml;
		}
		return "";
	}
	
	//get the indentation of the row
	public function indent($id, $alias = true, $editLink = true, $useHtml = true)
	{
		return parent::indent($id, false, false);
	}
	
	public function indentList($id, $alias = true, $editLink = true, $useHtml = true)
	{
		$str = "<div class='record_id' style='display:none'>$id</div><i title='Trascina per ordinare' class='ancora_ordinamento fa fa-arrows text text-warning' style='padding-right:3px;font-size:12px;'></i>";
		
		$clean["id"] = (int)$id;
		
		$field = isset(self::$currentRecord) ? self::$currentRecord["node"] : $this->clear()->selectId($clean["id"]);
		$titolo = "<a href='".Url::getRoot().$this->controller."/form/update/".$clean["id"].self::$viewStatus."'>".$field[$this->titleFieldName]."</a>";
		
		return $str." ".$titolo;
	}
	
	public function buildSelect($id = null, $showRoot = true, $where = null, $bindValues = array(), $nascondiNonAttivi = true)
	{
		return parent::buildSelect($id, true, " (node.lingua= ? OR node.lingua='') and ", array(sanitizeAll(self::$lingua)), $nascondiNonAttivi);
	}
	
	public function buildCategorySelect()
	{
		$c = new CategoriesModel();
		return $c->buildSelect(null,false);
	}
	
	public function buildContentSelect()
	{
		$c = new PagesModel();
		return $c->clear()->addWhereAttivo()->sWhere("id_user = 0")->orderBy("pages.id_order")->toList("id_page","title")->send();
	}
	
	public function update($id = null, $where = null)
	{
		$clean["id"] = (int)$id;
		
		$this->values["alias"] = "";
		
		$this->updateLinkAlias($this->values["link_to"]);
		
		return parent::update($clean["id"]);
	}
	
	public function insert()
	{
		$this->values["alias"] = "";
		
		$this->updateLinkAlias($this->values["link_to"]);
		
		return parent::insert();
	}
	
	protected function updateLinkAlias($link_to, $lingua = null)
	{
		if (!isset($lingua))
			$lingua = self::$lingua;
		
		$linguaNazione = $lingua;
		
		if (v("attiva_nazione_nell_url"))
			$linguaNazione .= "_[COUNTRY]";
		
// 		$homeUrlLang = isset($this->menuLang) ? "/".$this->menuLang : null;
		
		$parentUrl = str_replace("/admin","",Url::getFileRoot());
		
		switch ($link_to)
		{
			case "cat":
				$c = new CategoriesModel();
				$this->values["link_alias"] = $parentUrl."$linguaNazione/".sanitizeAll($c->getUrlAlias($this->values["id_c"], $lingua));
				break;
			case "cont":
				$p = new PagesModel();
				$this->values["link_alias"] = $parentUrl."$linguaNazione/".sanitizeAll($p->getUrlAlias($this->values["id_page"], $lingua));
				break;
			case "marchio":
				$m = new MarchiModel();
				$this->values["link_alias"] = $parentUrl."$linguaNazione/".sanitizeAll($m->getUrlAlias($this->values["id_marchio"], false, $lingua));
				break;
			case "tag":
				$this->values["link_alias"] = $parentUrl."$linguaNazione/".sanitizeAll(TagModel::getUrlAlias($this->values["id_tag"], $lingua));
				break;
			case "home":
				$this->values["link_alias"] = $parentUrl.$linguaNazione;
				break;
			case "nessuno":
				$this->values["link_alias"] = "";
				break;
		}
	}
	
	public function updateAllLinksAlias()
	{
		$res = $this->clear()->send();
		
		foreach ($res as $r)
		{
			$this->values = $r[$this->_tables];
			$this->sanitize();
			$this->updateLinkAlias($this->values["link_to"], $r["menu"]["lingua"]);
			$this->pUpdate($this->values[$this->_idFields]);
		}
	}
	
	public function getAllRowsList($queryResult, $htmlData, $numCol)
	{
		$res = $this->getTreeWithDepth(999, 1, self::$lingua);
		array_shift($res);
		
		$htmlList = $this->getCategoryUlLiTree($res,$htmlData);
		
		$htmlList = "<tr class='listRow'><td class='td_no_padding' colspan='$numCol'>".$htmlList."</td></tr>";
		
		return $htmlList;
	}

}
