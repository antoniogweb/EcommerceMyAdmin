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
	
	public static $arrayIdsPagineFiltrate = array();
	public static $elencoCategorieFull = array();
	public static $associazioneSezioneId = null;
	
	public static $sezioneVariabile = array(
		"faq"			=>	"mostra_faq",
		"testimonial"	=>	"mostra_testimonial",
		"gallery"		=>	"mostra_gallery",
		"eventi"		=>	"mostra_eventi",
		"referenze"		=>	"referenze_attive",
		"blog"			=>	"blog_attivo",
		"team"			=>	"team_attivo",
		"download"		=>	"download_attivi",
		"avvisi"		=>	"mostra_avvisi",
		"icone"			=>	"mostra_icone",
		"prodotti"		=>	"attiva_menu_ecommerce",
		"slide"			=>	"mostra_slide",
		"modali"		=>	"attiva_modali",
		"email"			=>	"attiva_template_email",
	);
	
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
			"immagine_sfondo"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/categorie_sfondo",
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg,gif',
				'allowedMimeTypes'	=>	'',
				"createImage"	=>	false,
				"maxFileSize"	=>	3000000,
// 				"clean_field"	=>	"clean_immagine",
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	600,
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
			'sitemap' => array("HAS_MANY", 'SitemapModel', 'id_c', null, "CASCADE"),
        );
    }
    
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'title'		=>	array(
					'labelString'=>	'Titolo',
					'entryClass'	=>	'form_input_text help_titolo',
				),
				'description'	=>	array(
					'labelString'=>	'Descrizione',
					'entryClass'	=>	'form_textarea help_descrizione',
				),
				'alias'		=>	array(
					'labelString'=>	'Alias (per URL)',
					'entryClass'	=>	'form_input_text help_alias',
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
					'entryClass'	=>	'form_input_text help_padre',
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
				'codice_categoria_prodotto_google'	=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Inserisci il codice tassonomico di Google.")." <a target='_blank' href='".v("url_codici_categorie_google")."'>".gtext("Elenco codici")."</a></div>"
					),
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
	
	public function checkPagesAlias($id = 0)
	{
		if (strcmp($this->values[$this->aliaseFieldName],"") === 0)
			$this->values[$this->aliaseFieldName] = sanitizeDb(encodeUrl($this->values[$this->titleFieldName]));
		
		$this->checkAliasAll($id);
	}
	
	public function update($id = null, $where = null)
	{
		if ($this->checkAll)
		{
			$this->checkPagesAlias($id);
		}
		
		if ($this->upload("update"))
		{
			parent::update($id, $where);
			
			if ($this->queryResult)
			{
				$this->controllaLingua($id);
				
				// Check sitemap
				$this->controllaElementoInSitemap($id);
				
				return true;
			}
		}
		
		return false;
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
			{
				$this->controllaLingua($this->lId);
				
				// Check sitemap
				$this->controllaElementoInSitemap($this->lId);
				
				return true;
			}
		}
		
		return false;
	}
	
	public function checkDataPerSitemap($id)
	{
		return $this->clear()->addWhereAttivoCategoria()->aWhere(array(
				"id_c"	=>	(int)$id,
				"ne"	=>	array(
					"id_c"	=>	1,
				),
				"add_in_sitemap"=>	"Y",
			))->record();
	}
	
	public function aggiungiAllaSitemap($category)
	{
		if (v("permetti_gestione_sitemap"))
		{
			$sm = new SitemapModel();
			
			$sm->setValues(array(
				"id_page"	=>	0,
				"id_c"		=>	$category["id_c"],
				"ultima_modifica"	=>	$category["data_ultima_modifica"],
				"priorita"	=>	$category["priorita_sitemap"],
			));
			
			$sm->insert();
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
		
		$record = $this->selectId($clean["id_c"]);
		$p = new PagesModel();
		$res = $p->clear()->where(array("-id_c"=>$clean["id_c"]))->rowNumber();
		
		if (trim($record["section"]))
		{
			$this->notice = "<div class='alert'>".gtext("Non è possibile eliminare questa categoria")."</div>";
			return false;
		}
		
		if ($res > 0)
		{
			$this->notice = "<div class='alert'>Non è possibile eliminare questa categoria perché ci sono dei contenuti associati ad essa. Si prega di eliminare prima quei contenuti</div>";
			return false;
		}
		else
		{
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
		}
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
	
	public static function getIdFromSection($section)
	{
		$c = new CategorieModel();
		
		return (int)$c->clear()->where(array(
			"section"	=>	sanitizeDb($section),
		))->field("id_c");
	}
	
	// Restituisce l'id della categoria ecommerce
	public function getShopCategoryId()
	{
		return self::getIdFromSection(Parametri::$nomeSezioneProdotti);
		
// 		return (int)$this->clear()->where(array(
// 			"section"	=>	Parametri::$nomeSezioneProdotti
// 		))->field("id_c");
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
	
	public static function gCatWhere($id_c, $full = true, $key = "-id_c")
	{
		if ($full)
		{
			$c = new CategoriesModel();
			
			$children = $c->children((int)$id_c, true);
			$catWhere = "in(".implode(",",$children).")";
			
			return array(
				"in" => array($key => $children),
			);
		}
		else
			return array(
				$key		=>	(int)$id_c,
			);
	}
	
	public static function gPage($id_c, $full = true, $traduzione = true)
	{
		$p = new PagesModel();
		
		$p->aWhere(self::gCatWhere($id_c, $full))->addWhereAttivo();
		
		if ($traduzione)
			$p->addJoinTraduzionePagina();
		
		return $p;
	}
	
	public function addJoinTraduzioneCategoria()
	{
		$this->addJoinTraduzione(null, "contenuti_tradotti_categoria");
		
		return $this;
	}
	
	public function numeroProdottiFull($id_c)
	{
		return self::gPage($id_c, true, false)->rowNumber();
	}
	
	public function numeroProdotti($id_c)
	{
		return self::gPage($id_c, false, false)->rowNumber();
	}
	
	public function categorieFiglie($id_c, $select = "categories.*,contenuti_tradotti_categoria.*")
	{
		return $this->clear()->select($select)->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")->where(array("id_p"=>(int)$id_c, "attivo"=>"Y"))->orderBy("categories.lft")->send();
	}
	
	public function categorieFiglieSelect($id_c)
	{
		$children = $this->categorieFiglie($id_c, "categories.id_c,categories.title,contenuti_tradotti_categoria.title");
		
		$arrayFigli = array();
		
		foreach ($children as $c)
		{
			$arrayFigli[$c["categories"]["id_c"]] = cfield($c, "title");
		}
		
		return $arrayFigli;
	}
	
	public static function getUrlAliasTagMarchio($id_tag = 0, $id_marchio = 0, $id_c = 0, $viewStatus = "", $filtri = array(), $filtriLoc = array(), $filtriAltri = array())
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
		
		// Filtri URL caratteristiche
		if (count($filtri) > 0)
			$urlArray = array_merge($urlArray, $filtri);
		
		// Filtri URL Loc
		if (count($filtriLoc) > 0)
			$urlArray = array_merge($urlArray, $filtriLoc);
		
		// Filtri URL altri
		if (count($filtriAltri) > 0)
			$urlArray = array_merge($urlArray, $filtriAltri);
		
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
	
	public static function getClosestParentThatHasField($id, $field, $lingua = null)
	{
		$c = new CategoriesModel();
		
		$parents = $c->parents($id, false, false, $lingua);
		
		rsort($parents);
		
		foreach ($parents as $p)
		{
			if ($p["categories"][$field])
				return $p;
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
	
// 	public function getRowList($queryResult, $rowHtml, $numCol)
// 	{
// 		return "<td colspan='$numCol'><table style='width:100%;'><tr>$rowHtml</tr></table></td>";
// 	}
	
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
		
		if ($alias)
		{
			return $str." ".$titolo." <br />$strAlias<span style='font-size:10px;font-style:italic;'>(alias: ".$field[$this->aliaseFieldName].")</span>";
		}
		
		return $str." ".$titolo;
	}
	
	public function getAllRowsList($queryResult, $htmlData, $numCol)
	{
		if ($this->section)
		{
			$idCat = (int)$this->clear()->where(array(
				"section"	=>	$this->section,
			))->field("id_c");
		
			$res = $this->getTreeWithDepth(999, $idCat);
			
			$htmlList = $this->getCategoryUlLiTree($res,$htmlData);
			
			$htmlList = "<tr class='listRow'><td class='td_no_padding' colspan='$numCol'>".$htmlList."</td></tr>";
		}
		else
		{
			$htmlList = "";
			
			for ($i = 0; $i < count($queryResult); $i++)
			{
				$htmlList .= "<tr class='listRow'>".$htmlData[$i]."</tr>";
			}
		}
		
		return $htmlList;
	}
	
	public static function checkSection($section)
	{
		$c = new CategoriesModel();
		
		return $c->clear()->where(array(
			"section"	=>	sanitizeAll($section),
		))->sWhere("section is not null and section != ''")->rowNumber();
	}
	
	public function sistemaVisibilitaSezioni()
	{
		$sezioni = $this->clear()->sWhere("section != '' and section is not null")->send(false);
		
		foreach ($sezioni as $sez)
		{
			foreach (self::$sezioneVariabile as $sezione => $variabile)
			{
				if ($sez["section"] == $sezione)
				{
					$attivo = v($variabile) ? "Y" : "N";
					
					if ($sez["bloccato"])
						$attivo = "N";
					
					$this->setValues(array(
						"installata"	=>	v($variabile),
						"attivo"		=>	$attivo,
					));
					
					$this->pUpdate($sez["id_c"]);
				}
			}
		}
	}
	
	public static function getUrlAliasPromo()
	{
		$filtriUrlAltriFiltri = AltriFiltri::getArrayUrlCaratteristiche(AltriFiltri::$altriFiltriTipi["stato-prodotto-promo"], AltriFiltri::$aliasValoreTipoPromo[0]);
		
		return CategoriesModel::getUrlAliasTagMarchio(0, 0, self::$idShop, "", array(), array(), $filtriUrlAltriFiltri);
	}
	
	public static function getIdCategoriaDaSezione($sezione)
	{
		if (!isset(self::$associazioneSezioneId))
			self::associaSezioneId();
		
		if (isset(self::$associazioneSezioneId[$sezione]))
			return self::$associazioneSezioneId[$sezione];
		
		return 0;
	}
	
	public static function associaSezioneId()
	{
		self::$associazioneSezioneId = CategoriesModel::g()->where(array(
			"ne"	=>	array(
				"section"	=>	"",
			),
		))->toList("section", "id_c")->send();
	}
}
