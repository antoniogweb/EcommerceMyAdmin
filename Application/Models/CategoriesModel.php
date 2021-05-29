<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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

class CategoriesModel extends HierarchicalModel {

	public static $aliases = array();
	public static $elencoTag = null;
	public static $elencoMarchi = null;
	public static $idShop = 0;
	
	public $controller = "categories";
	
	public $checkAll = true;
	
	public function __construct() {
		$this->_tables='categories';
		$this->_idFields='id_c';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'categories.id_order desc';
		$this->_lang = 'It';
		
		$this->_popupItemNames = array(
			'attivo'	=>	'attivo',
		);

		$this->_popupLabels = array(
			'attivo'	=>	'PUBBLICATO?',
		);

		$this->_popupFunctions = array(
			'attivo'=>	'getYesNo',
		);
		
		$this->addStrongCondition("both",'checkNotEmpty',"title");
		
		$this->salvaDataModifica = true;
		
		$this->foreignKeys = array(
			"id_c parent of PagesModel(id_c) on delete restrict (Non è possibile eliminare questa categoria perché ci sono dei contenuti associati ad essa. Si prega di eliminare prima tali contenuti)",
		);
		
		$this->uploadFields = array(
			"immagine"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/categorie",
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg,gif',
				'allowedMimeTypes'	=>	'',
				"createImage"	=>	false,
				"maxFileSize"	=>	3000000,
// 				"clean_field"	=>	"clean_immagine",
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	300,
					'imgHeight'		=>	300,
					'defaultImage'	=>  null,
					'cropImage'		=>	'no',
				),
			),
			"immagine_2"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/categorie_2",
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg,gif',
				'allowedMimeTypes'	=>	'',
				"createImage"	=>	false,
				"maxFileSize"	=>	3000000,
// 				"clean_field"	=>	"clean_immagine",
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	300,
					'imgHeight'		=>	300,
					'defaultImage'	=>  null,
					'cropImage'		=>	'no',
				),
			),
		);
		
		parent::__construct();
		
	}
	
	public function relations() {
        return array(
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_c', null, "CASCADE"),
			'classisconto' => array("HAS_MANY", 'ClassiscontocategoriesModel', 'id_c', null, "CASCADE"),
        );
    }
    
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'title'		=>	array(
					'labelString'=>	'Titolo',
				),
				'description'	=>	array(
					'labelString'=>	'Descrizione',
				),
				'alias'		=>	array(
					'labelString'=>	'Alias (per URL)',
				),
				'attivo'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Pubblicata?',
					'options'	=>	array('sì'=>'Y','no'=>'N'),
				),
				'id_p'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Genitore',
					'options'	=>	$this->buildSelect(),
					'reverse' => 'yes',
				),
				'id_c'	=>	array(
					'type'		=>	'Hidden'
				),
				'colore_testo_in_slide'	=>	array(
					"className"	=>	"form-control colorpicker-element",
				),
				'priorita_sitemap'	=>	array(
					'labelString'=>	'Priorità sitemap',
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	public static function setAliases()
	{
		if (empty(self::$aliases))
		{
			$ct = new ContenutitradottiModel();
			
			self::$aliases = $ct->clear()->select("categories.alias,contenuti_tradotti.alias")->inner(array("category"))->where(array(
				"ne" => array("id_c" => 0),
				"lingua"	=>	Params::$lang,
			))->toList("categories.alias","contenuti_tradotti.alias")->send();
		}
		
		return self::$aliases;
	}
	
	//controlla che la categoria $id possa essere gestita dal model
	public function modificaCategoriaPermessa($id)
	{
		$clean["id"] = (int)$id;
		
		if (isset($this->section))
		{
			//ottengo la section legata a questo model
			$section = $this->section;
			
			$category = $this->clear()->selectId($clean["id"]);
			
			if (count($category) > 0)
			{
				if (strcmp($section,$this->rootSectionName) !== 0)
				{
					//ottengo i genitori
					$parents = $this->parents((int)$category["id_c"], false, false);
					
					//tolgo il genitore root
					array_shift($parents);
					
					foreach ($parents as $par)
					{
						if (strcmp($par["categories"]["section"],$section) === 0)
						{
							return true;
						}
					}
				}
				else
				{
					//ottengo i genitori
					$parents = $this->parents((int)$category["id_c"], false, true);
					
					//tolgo il genitore root
					array_shift($parents);
					
					foreach ($parents as $par)
					{
						if (strcmp($par["categories"]["section"],"") !== 0)
						{
							return false;
						}
					}
				
					return true;
				}
			}
			
			return false;
		}
		
		return true;
	}
	
	public function checkPagesAlias()
	{
		if (strcmp($this->values[$this->aliaseFieldName],"") === 0)
		{
			$this->values[$this->aliaseFieldName] = sanitizeDb(encodeUrl($this->values[$this->titleFieldName]));
		}
		
		$res = $this->query("select alias from pages where alias ='".$this->values["alias"]."'");

		if (count($res) > 0)
		{
			$this->values[$this->aliaseFieldName] = $this->values[$this->aliaseFieldName] . "-".generateString(4,"123456789");
		}
	}
	
	public function update($id = null, $where = null)
	{
		if ($this->checkAll)
		{
			$this->checkPagesAlias();
		}
		
		if ($this->upload("update"))
		{
			parent::update($id, $where);
			
			if ($this->queryResult)
				$this->controllaLingua($id);
		}
	}
	
	public function insert()
	{
		if ($this->checkAll)
		{
			$this->checkPagesAlias();
		}
		
		if ($this->upload("insert"))
		{
			parent::insert();
			
			if ($this->queryResult)
				$this->controllaLingua($this->lId);
		}
	}
	
	// Controllo che la lingua esista
	public function controllaLingua($id)
	{
		$sezione = $this->section((int)$id, true);
		
		$this->controllaLinguaGeneric($id, "id_c", $sezione);
	}
	
// 	// Controllo che la lingua esista
// 	public function controllaLingua($id)
// 	{
// 		$record = $this->selectId((int)$id);
// 		
// 		if (!empty($record))
// 		{
// 			$ct = new ContenutitradottiModel();
// 			
// 			foreach (BaseController::$traduzioni as $lingua)
// 			{
// 				$traduzione = $ct->clear()->where(array(
// 					"id_c"	=>	(int)$id,
// 					"lingua"	=>	sanitizeDb($lingua),
// 				))->send(false);
// 				
// 				$ct->setValues(array(
// 					"lingua"		=>	sanitizeDb($lingua),
// 					"title"			=>	$record["title"],
// 					"description"	=>	$record["description"],
// 					"alias"			=>	$record["alias"],
// 					"keywords"		=>	$record["keywords"],
// 					"meta_description"	=>	$record["meta_description"],
// 					"id_c"			=>	($id),
// 					"id_page"		=>	0,
// 				),"sanitizeDb");
// 				
// 				if (count($traduzione) === 0)
// 					$ct->insert();
// 				else if (!$traduzione[0]["salvato"])
// 					$ct->update($traduzione[0]["id_ct"]);
// 			}
// 		}
// 	}
	
	//create the HTML of the menu
	//$tree: nodes as given by getTreeWithDepth
	//$complete: if true returns also the opening and closing <ul>
	public function getMenu($tree, $complete = true)
	{
		$ext = Parametri::$useHtmlExtension ? ".html" : null;
		
		if (count($tree) > 0)
		{
			$requestUri = isset($_SERVER['REQUEST_URI']) ? strtok($_SERVER['REQUEST_URI'],"?") : "";
			
			$tree[] = $tree[count($tree) -1];
			$depth = $tree[count($tree) -1]["aggregate"]["depth"] = $tree[0]["aggregate"]["depth"];
			$tree[count($tree) -1]["is_last"] = 1;
			
			$count = 0;

			$menuHtml = null;
			$incremUrl = null;
			$prevAlias = null;
			
			if ($complete)
			{
				$menuHtml .= "<ul id='ul_".strtolower(get_class($this))."' class='ul_".strtolower(get_class($this))."'>";
			}
			foreach ($tree as $node)
			{
				if ($node["aggregate"]["depth"] > $depth)
				{
					$depth = $node["aggregate"]["depth"];
					
					$menuHtml = substr($menuHtml, 0, -5);
					
					$menuHtml .= "<ul class='ul_menu_level ul_menu_level_".$depth."'>";

					if (strcmp($prevAlias,"") !== 0)
					{
						$incremUrl .= "/". $prevAlias;
					}
				}
				if ($node["aggregate"]["depth"] < $depth)
				{
					$diff = (int)($depth - $node["aggregate"]["depth"]);
					
					$menuHtml .= str_repeat("</ul></li>", $diff);
					$depth = $node["aggregate"]["depth"];
					
					$tempIncremUrl = explode("/",$incremUrl);
					for ($i=0;$i<$diff;$i++)
					{
						array_pop($tempIncremUrl);
					}
					$incremUrl = implode("/",$tempIncremUrl);
				}
				if (!isset($node["is_last"]))
				{
					$currClass = null;
					if (strcmp($requestUri,"/") !== 0)
					{
						$pattern = str_replace(DS, '\\'.DS, $incremUrl."/".$node["node"][$this->aliaseFieldName]);
						$pattern = rtrim($pattern,"\/")."\/";
						$subject = str_replace(".html",null,$requestUri);
						$subject = rtrim($subject,"/")."/";

// 						echo $pattern."-".$subject."<br />";
						if (preg_match("/(".$pattern.")/",$subject))
						{
							$currClass = "current_item";
						}
					}
					
					$urlLang = isset(Params::$lang) ? "/".Params::$lang : null;
					
					$prevAlias = $node["node"][$this->aliaseFieldName];
					$menuHtml .= "<li class='li_menu_level li_menu_level_".$depth." ".$node["node"]["alias"]." $currClass'><a href='http://".DOMAIN_NAME.$urlLang.$incremUrl."/".$node["node"][$this->aliaseFieldName]."$ext'>".$node["node"][$this->titleFieldName]."</a></li>";
				}
				$count++;
			}
			if ($complete)
			{
				$menuHtml .= "</ul>\n";
			}
			return $menuHtml;
		}
		return "";
	}
	
	public function del($id_c = null, $where = null)
	{
		$clean["id_c"] = (int)$id_c;
		
		$p = new PagesModel();
		$res = $p->clear()->where(array("-id_c"=>$clean["id_c"]))->rowNumber();
		
// 		if ($res > 0)
// 		{
// 			$this->notice = "<div class='alert'>Non è possibile eliminare questa categoria perché ci sono dei contenuti associati ad essa. Si prega di eliminare prima quei contenuti</div>";
// 			return false;
// 		}
// 		else
// 		{
			if ($this->checkOnDeleteIntegrity($id_c, $where))
			{
				//cancello tutti i gruppi a cui è associato
				$gc = new ReggroupscategoriesModel();
				$list = $gc->clear()->select("id_gc")->where(array("id_c"=>$clean['id_c']))->toList("id_gc")->send();
				
				foreach ($list as $id)
				{
					$gc->del($id);
				}
			}
		
			return parent::del($id_c, $where);
// 		}
	}
	
	//return true if the category has active pages, otherwise return false
	public function hasActivePages($id_c = null)
	{
		$clean["id_c"] = (int)$id_c;
		
		$children = $this->children($clean["id_c"], true);
		
		$whereIds = "in(".implode(',',array_values($children)).")";
		$pages = $this->clear()->select("pages.id_page")->inner("pages")->using("id_c")->where(array(
			"in" => array("id_c" => $children),
			"n!pages.attivo"=>"Y"
		))->toList("pages.id_page")->send();
		
		if (count($pages) > 0)
		{
			if (Parametri::$hideNotAllowedNodesInLists)
			{
				$p = new PagesModel();
				
				foreach ($pages as $id_page)
				{
					if ($p->check($id_page))
					{
						return true;
					}
				}
			}
			else
			{
				return true;
			}
		}
		
		return false;
	}
	
// 	public function elencoClassiSconto($id_c)
// 	{
// 		$csc = new ClassiscontocategoriesModel();
// 		
// 		$classi = $csc->clear()->where(array(
// 			"id_classe"	=>	$idClasse,
// 		))->toList("titolo")->send();
// 		
// 		return implode("<br />",$classi);
// 	}
	
	//restituisce la lista dei gruppi utenti a cui può essere associata la categoria
	//sono quelli della categoria padre. Se la categoria padre non è associata a gruppi, guarda il padre del padre e in caso restituisce l'intera lista dei gruppi
	public function allowedGroups($id_c)
	{
		$clean['id_c'] = (int)$id_c;
		
		$parents = $this->parents($clean['id_c'], true, true);
		
		$gc = new ReggroupscategoriesModel();
		
		//elimino la categoria root
		array_shift($parents);
		
		$rparents = array_reverse($parents);
		
		if (count($rparents) > 0)
		{
			foreach ($rparents as $idP)
			{
				$gr = $gc->clear()->select("reggroups.id_group,reggroups.name")->inner("reggroups")->using("id_group")->where(array("id_c"=>(int)$idP))->toList("reggroups.id_group","reggroups.name")->send();
				
				if (count($gr) > 0)
				{
					return $gr;
				}
				else
				{
					return $this->allowedGroups($idP);
				}
			}
		}
		else
		{
			$rg = new ReggroupsModel();
			
			return $rg->clear()->orderBy("name")->toList("id_group","name")->send();
		}
		
	}
	
	//controlla l'accesso alla categoria e restituisce vero o falso
	public function check($id_c)
	{
		$clean['id_c'] = (int)$id_c;
		
		$parents = $this->parents($clean['id_c'], true, false);
		
		//elimino la categoria root
		array_shift($parents);
		
		$gc = new ReggroupscategoriesModel();
		
		$alberoGruppi = array();
		
		foreach ($parents as $idP)
		{
			$gr = $gc->clear()->select("reggroups.name")->inner("reggroups")->using("id_group")->where(array("id_c"=>(int)$idP))->toList("reggroups.name")->send();
			
			$alberoGruppi[] = $gr;
		}

		foreach ($alberoGruppi as $gruppi)
		{
			if (count($gruppi) > 0 and count(array_intersect($gruppi, User::$groups)) === 0)
			{
				return false;
			}
		}
		
		return true;
	}
	
	// Get the section
	public function section($id_c, $firstElement = false)
	{
		$clean['id_c'] = (int)$id_c;
		
		$parents = $this->parents($clean['id_c'], false, false);
		
		//elimino la categoria root
		array_shift($parents);
		
		$section = "";
		
		foreach ($parents as $p)
		{
			if (strcmp($p["categories"]["section"],"") !== 0)
			{
				$section = $p["categories"]["section"];
			}
			
			if ($firstElement)
			{
				return $section;
			}
		}
		
		return $section;
	}
	
	// Restituisce l'id della categoria ecommerce
	public function getShopCategoryId()
	{
		return (int)$this->clear()->where(array(
			"section"	=>	Parametri::$nomeSezioneProdotti
		))->field("id_c");
	}
	
	// Restituisce la lista di categorie in sconto
	public function getListaCategorieInClasseSconto()
	{
		if (isset(User::$classeSconto))
		{
			$idClasse = User::$classeSconto["id_classe"];
			
			$csc = new ClassiscontocategoriesModel();
			
			return $csc->clear()->select("id_c")->where(array(
				"id_classe"	=>	$idClasse,
			))->toList("id_c")->send();
		}
		
		return array();
	}
	
	public function linklingua($record, $lingua)
	{
		return $this->linklinguaGeneric($record["categories"]["id_c"], $lingua, "id_c");
	}
	
	public function numeroProdotti($id_c)
	{
		$p = new PagesModel();
		
		return $p->clear()->where(array(
			"-id_c"		=>	(int)$id_c,
			"attivo"	=>	"Y",
		))->rowNumber();
	}
	
	public function categorieFiglie($id_c)
	{
		return $this->clear()->select("categories.*,contenuti_tradotti_categoria.*")->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")->where(array("id_p"=>(int)$id_c, "attivo"=>"Y"))->orderBy("categories.lft")->send();
	}
	
	public static function getUrlAliasTagMarchio($id_tag = 0, $id_marchio = 0, $id_c = 0, $viewStatus = "")
	{
		$urlArray = array();
		
		if ($id_tag)
		{
			if (!isset(self::$elencoTag))
			{
				$t = new TagModel();
				
				$tags = $t->clear()->addJoinTraduzione()->send();
				
				foreach ($tags as $t)
				{
					self::$elencoTag[$t["tag"]["id_tag"]] = tagfield($t,"alias");
				}
			}
			
			if (isset(self::$elencoTag[$id_tag]))
				$urlArray[] = self::$elencoTag[$id_tag];
		}
		
		if ($id_marchio)
		{
			if (!isset(self::$elencoMarchi))
			{
				$t = new MarchiModel();
				
				$tags = $t->clear()->addJoinTraduzione()->send();
				
				foreach ($tags as $t)
				{
					self::$elencoMarchi[$t["marchi"]["id_marchio"]] = mfield($t,"alias");
				}
			}
			
			if (isset(self::$elencoMarchi[$id_marchio]))
				$urlArray[] = self::$elencoMarchi[$id_marchio];
		}
		
		if ($id_c && ($id_c != CategoriesModel::$idShop || v("shop_in_alias_tag") || v("shop_in_alias_marchio") || (!$id_tag && !$id_marchio)))
		{
			if (is_numeric($id_c))
			{
				$c = new CategoriesModel();
				
				Parametri::$useHtmlExtension = false;
				$urlArray[] = $c->getUrlAlias($id_c);
				Parametri::$useHtmlExtension = true;
			}
			else
				$urlArray[] = $id_c;
		}
		
		$url = implode("/", $urlArray).".html";
		
		if ($viewStatus)
			$url .= "$viewStatus";
		
		return $url;
	}
	
	public static function getFirstParentImage2($id)
	{
		$c = new CategoriesModel();
		
		$parents = $c->parents($id, false, false);
		
		rsort($parents);
		
		foreach ($parents as $p)
		{
			if ($p["categories"]["immagine_2"])
				return $p["categories"]["immagine_2"];
		}
	}
	
	public static function sDepth($id = null)
	{
		$c = new CategorieModel();
		
		return $c->depth($id);
	}
	
	public static function resultToIdList($categorie)
	{
		$arrayIds = array();
		
		foreach ($categorie as $c)
		{
			$arrayIds[] = $c["categories"]["id_c"];
		}
		
		return $arrayIds;
	}
}
