<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!defined('EG')) die('Direct access not allowed!');

class BaseContenutiController extends BaseController
{
	public $pageArgs = array();
	public $cleanAlias = null;
	public $urlParent = array(); //parents array (only ALIAS) as taken by the URL
	public $rParent = array(); //right parents array (only ALIAS) as taken by database
	public $parents = array(); //right parents array as taken by database
	public $fullParents = array(); //right parents plus current element array as taken by database
	public $currUrl = null; //the URL of the current page
	public $elementsPerPage = 9; //number of elements per page
	public $idMarchio = 0;
	public $idTag = 0;
	
	public $pages = array(); // Array di pagina
	public $p = array(); // singola pagina
	public $altreImmagini = array(); // altre immagini
	public $lista_attributi;
	public $lista_valori_attributi;
	public $scaglioni;
	public $prezzoMinimo;
	
	public $firstSection;
	public $section;
	public $catSWhere = "";
	
	public function __construct($model, $controller, $queryString)
	{
		parent::__construct($model, $controller, $queryString);
		
		if (Output::$html)
		{
			$this->load('header');
			$this->load('footer','last');
		}
		
		$data["elementsPerPage"] = $this->elementsPerPage;
		
		$this->append($data);
	}

	public function index()
	{
		CategoriesModel::setAliases();
		
		$this->pageArgs = func_get_args();
		
		if (v("usa_tag"))
		{
			$elencoTag = $this->m["TagModel"]->clear()->addJoinTraduzione()->send();
			$elencoTagEncoded = array();
			foreach ($elencoTag as $tag)
			{
				$elencoTagEncoded[tagfield($tag,"alias")] = $tag["tag"]["id_tag"];
			}
			
			if (count($this->pageArgs) > 0 && isset($elencoTagEncoded[$this->pageArgs[0]]))
			{
				$this->idTag = $elencoTagEncoded[$this->pageArgs[0]];
				
				array_shift($this->pageArgs);
			}
		}
		
		if (v("usa_marchi"))
		{
			$elencoMarchi = $this->m["MarchiModel"]->clear()->addJoinTraduzione()->send();
			$elencoMarchiEncoded = array();
			foreach ($elencoMarchi as $marchio)
			{
				$elencoMarchiEncoded[mfield($marchio,"alias")] = $marchio["marchi"]["id_marchio"];
			}
			
			if (count($this->pageArgs) > 0 && isset($elencoMarchiEncoded[$this->pageArgs[0]]))
			{
				$this->idMarchio = $elencoMarchiEncoded[$this->pageArgs[0]];
				
				array_shift($this->pageArgs);
			}
		}
		
		if( count($this->pageArgs) > 0 && strcmp($this->pageArgs[count($this->pageArgs)-1],"") === 0)
		{
			array_pop($this->pageArgs);
		}
		
		// Controlla che non sia un marchio o un tag
		if (count($this->pageArgs) === 0 && ($this->idMarchio || $this->idTag))
		{
			$catProdotti = $this->m['CategoriesModel']->clear()
				->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")
				->where(array(
					"section"	=>	Parametri::$nomeSezioneProdotti
				))
				->first();
			
			if ($catProdotti)
				$this->pageArgs[] = cfield($catProdotti, "alias");
		}
		
		$args = $this->pageArgs;
		$data = array();
		
		if (count($args) > 0)
		{
			//parents array as taken by the URL
			$this->urlParent = $this->pageArgs;
			//remove the last element (content itself)
			array_pop($this->urlParent);
			
			$clean['alias'] = $this->cleanAlias = sanitizeAll($args[(count($args)-1)]);
			
			if ($this->m["PagesModel"]->isActiveAlias($clean['alias'], Params::$lang))
			{
				$ids = $this->m["PagesModel"]->getIdFromAlias($clean['alias'], Params::$lang);
				
// 				print_r($clean['id']);
				
				$clean["id"] = (int)$ids[0];
				
				$parents = array();
				$rParents = array();
				
				foreach ($ids as $id)
				{
					$par = $this->m["PagesModel"]->parents((int)$id,false,false,Params::$lang);
					
					//tolgo la root
					array_shift($par);
					
					$parents[$id] = $par;
					
					//tolgo la pagina
					array_pop($par);
					
					$temp = array();
					
					foreach ($par as $p)
					{
						$temp[] = isset(CategoriesModel::$aliases[$p["categories"]["alias"]]) ? CategoriesModel::$aliases[$p["categories"]["alias"]] : $p["categories"]["alias"];
					}
					
					$rParents[$id] = $temp;
				}
				
				if (count($rParents) > 0)
				{
					$idPrincipale = $this->m["PagesModel"]->getPrincipale($ids[0]);
					$this->fullParents = $parents[$idPrincipale];
					$this->rParent = $rParents[$idPrincipale];
				}
				
				foreach ($rParents as $id => $rP)
				{
					if ($this->urlParent === $rP)
					{
						$this->rParent = $rP;
						$this->fullParents = $parents[$id];
						$clean["id"] = $id;
						
						if (isset($parents[$id][count($parents[$id])-1]["contenuti_tradotti"]["title"]) && $parents[$id][count($parents[$id])-1]["contenuti_tradotti"]["title"])
							$stringIt = $stringLn = $parents[$id][count($parents[$id])-1]["contenuti_tradotti"]["title"];
						else
							$stringIt = $stringLn = $parents[$id][count($parents[$id])-1]["pages"]["title"];
// 						$stringLn = $parents[$id][count($parents[$id])-1]["pages"]["title".$this->langDb];
						
						$data["title"] = Parametri::$nomeNegozio . " - " . strtolower(getField($stringLn,$stringIt));
					}
				}

// 				$parents = $this->m["PagesModel"]->parents($clean['id'],false,false);
// 				array_shift($parents); //remove the root parent
// 				
// 				$data["title"] = Parametri::$nomeNegozio . " - " . strtolower($parents[count($parents)-1]["pages"]["title"]);
// 				
// 				$this->fullParents = $parents;
// 
// 				array_pop($parents); //remove the current element
// 				
// 				//build the array with the right parents
// 				foreach ($parents as $p)
// 				{
// 					$this->rParent[] = $p["categories"]["alias"];
// 				}
				$this->checkIfRigthParents();
				$this->page($clean['id']);
			}
			else if ($clean['id'] = (int)$this->m["CategoriesModel"]->getIdFromAlias($clean['alias'], Params::$lang))
			{
				$parents = $this->m["CategoriesModel"]->parents($clean['id'],false,false, Params::$lang);
				array_shift($parents); //remove the root parent
				
				if (isset($parents[count($parents)-1]["contenuti_tradotti"]["title"]) && $parents[count($parents)-1]["contenuti_tradotti"]["title"])
					$data["title"] = Parametri::$nomeNegozio . " - " . strtolower($parents[count($parents)-1]["contenuti_tradotti"]["title"]);
				else
					$data["title"] = Parametri::$nomeNegozio . " - " . strtolower($parents[count($parents)-1]["categories"]["title"]);
				
				$this->fullParents = $parents;

				array_pop($parents); //remove the current element
				
				$this->parents = $parents;
				
				//build the array with the right parents
				foreach ($parents as $p)
				{
					$this->rParent[] = isset(CategoriesModel::$aliases[$p["categories"]["alias"]]) ? CategoriesModel::$aliases[$p["categories"]["alias"]] : $p["categories"]["alias"];
				}
				$this->checkIfRigthParents();
				$this->category($clean['id']);
			}
			else
			{
				$this->redirect("contenuti/notfound");
			}
		}
		
		$data["currUrl"] = $this->getCurrentUrl();
		
		$this->append($data);
	}

	public function notfound()
	{
		$data["title"] = Parametri::$nomeNegozio . " - pagina non trovata";
				
		header('HTTP/1.0 404 Not Found');
		
		$this->append($data);
		
		$this->load("404");
	}
	
	public function nonpermesso()
	{
		$data["title"] = Parametri::$nomeNegozio . " - accesso non permesso";
		
		$this->append($data);
		
		$this->load("accesso_non_permesso");
	}
	
	private function checkIfRigthParents()
	{
		if ($this->urlParent !== $this->rParent)
		{
			$ext = Parametri::$useHtmlExtension ? ".html" : null;
			$rightUrl = ltrim(Url::createUrl(array_merge($this->rParent,array($this->cleanAlias)),null,true),"/");
			$this->redirect($rightUrl.$ext);
		}
	}
	
	protected function getCurrentUrl($completeUrl = true)
	{
		$ext = Parametri::$useHtmlExtension ? ".html" : null;
		
		$baseUrl = $completeUrl ? $this->baseUrl."/" : null;
		if (count($this->rParent) > 0)
		{
			return $baseUrl.implode("/",$this->rParent)."/".$this->cleanAlias.$ext;
		}
		else
		{
			return $baseUrl.$this->cleanAlias.$ext;
		}
	}
	
	//create the HTML of the breadcrumb
	private function breadcrumb($type = "category", $linkInLast = false, $separator = "&raquo;")
	{
		switch($type)
		{
			case "category":
				$table = "categories";
				$title = "title";
				break;
			case "page":
				$table = "pages";
				$title = "title";
				break;
			case "menu":
				break;
		}
		$tempParents = $this->fullParents;
		
// 		print_r($this->fullParents);
// 		die();
		$breadcrumbArray = array();
		
		$ext = Parametri::$useHtmlExtension ? ".html" : null;
		
		$i = 0;
		while (count($tempParents) > 0)
		{
			
			$j = 0;
			$hrefArray = array();
			foreach($tempParents as $row)
			{
				$table = ($j === (count($tempParents)-1) and $type === "page" and $i === 0) ? "pages" : "categories";
				$hrefArray[] = $row["contenuti_tradotti"]["alias"] ? $row["contenuti_tradotti"]["alias"] : $row[$table]["alias"];
				$j++;
			}
			$ref = implode("/",$hrefArray).$ext;
			
			$table = ($i === 0 and $type === "page") ? "pages" : "categories";
			$lClass = $i === 0 ? "breadcrumb_last" : null;
			
			if ($i === 0 and !$linkInLast)
			{
				$titolo = ($tempParents[count($tempParents)-1]["contenuti_tradotti"][$title]) ? $tempParents[count($tempParents)-1]["contenuti_tradotti"][$title] : $tempParents[count($tempParents)-1][$table][$title];
				
// 				print_r($tempParents[count($tempParents)-1]);
				array_unshift($breadcrumbArray, "<span class='breadcrumb_last_text'>".$titolo."</span>");
			}
			else
			{
				$alias = ($tempParents[count($tempParents)-1]["contenuti_tradotti"]['alias']) ? $tempParents[count($tempParents)-1]["contenuti_tradotti"]['alias'] : $tempParents[count($tempParents)-1][$table]['alias'];
				
				$titolo = $tempParents[count($tempParents)-1]["contenuti_tradotti"][$title] ? $tempParents[count($tempParents)-1]["contenuti_tradotti"][$title] : $tempParents[count($tempParents)-1][$table][$title];
				
				array_unshift($breadcrumbArray, "<a class='$lClass breadcrumb_item ".$alias."' href='".$this->baseUrl."/$ref'>".$titolo."</a>");
			}
			
			array_pop($tempParents);
			
			$i++;
		}
		return implode(v("divisone_breadcrum"), $breadcrumbArray);
	}
	
	protected function category($id)
	{
		$argKeys = array(
			'p:forceNat'	=>	1,
			'o:sanitizeAll'	=>	"tutti",
		);
		
		$this->setArgKeys($argKeys);
		$this->shift(count($this->pageArgs));
		
		$clean['id'] = $data["id_categoria"] = (int)$id;
		
		$this->checkCategory($clean["id"]);
		
		$section = $this->section = $this->m["CategoriesModel"]->section($clean['id']);
		$firstSection = $this->firstSection = $this->m["CategoriesModel"]->section($clean['id'], true);
		
		if ($firstSection == "prodotti")
		{
			$this->elementsPerPage = 999999;
		}
		
		$data["arrayLingue"] = array();
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/".$this->m["CategoriesModel"]->getUrlAlias($clean['id'], $l);
		}
		
		$data["idMarchio"] = $this->idMarchio;
		
		$data["aliasMarchioCorrente"] = "";
		
		if (v("usa_marchi"))
		{
			$marchioCorrente =  $data["marchioCorrente"] = $this->m["MarchiModel"]->clear()->addJoinTraduzione()->where(array(
				"marchi.id_marchio"	=>	(int)$this->idMarchio,
			))->first();
			
			if (count($marchioCorrente) > 0)
				$data["aliasMarchioCorrente"] = mfield($marchioCorrente, "alias")."/";
		}
		
		$data["idTag"] = $this->idTag;
		
		if (v("usa_tag"))
		{
			$tagCorrente =  $data["tagCorrente"] = $this->m["TagModel"]->clear()->addJoinTraduzione()->where(array(
				"tag.id_tag"	=>	(int)$this->idTag,
			))->first();
			
			if (count($tagCorrente) > 0)
				$data["aliasTagCorrente"] = tagfield($tagCorrente, "alias")."/";
		}
		
// 		print_r($arrayLingue);
// 		$urlAlias = $this->m["CategoriesModel"]->getUrlAlias($clean['id']);
// 		
// 		$data["itUrl"] = "it/$urlAlias";
// 		$data["enUrl"] = "en/$urlAlias";
			
		//estrai i dati della categoria
		$r = $this->m['CategoriesModel']->clear()->select("categories.*,contenuti_tradotti_categoria.*")->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")->where(array("id_c"=>$clean['id']))->send();
		$data["datiCategoria"] = $r[0];
		
		$data["categorieFiglie"] = $this->m['CategoriesModel']->clear()->select("categories.*,contenuti_tradotti_categoria.*")->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")->where(array("id_p"=>$clean['id']))->send();
		
// 		print_r($data["categorieFiglie"]);die();
		
		$template = strcmp($r[0]["categories"]["template"],"") === 0 ? null : $r[0]["categories"]["template"];
		
		if (isset($r[0]["contenuti_tradotti_categoria"]["meta_description"]) && $r[0]["contenuti_tradotti_categoria"]["meta_description"])
			$data["meta_description"] = htmlentitydecode($r[0]["contenuti_tradotti_categoria"]["meta_description"]);
		else if (strcmp($r[0]["categories"]["meta_description"],"") !== 0)
			$data["meta_description"] = htmlentitydecode($r[0]["categories"]["meta_description"]);
		
		if (isset($r[0]["contenuti_tradotti_categoria"]["keywords"]) && $r[0]["contenuti_tradotti_categoria"]["keywords"])
			$data["keywords"] = htmlentitydecode($r[0]["contenuti_tradotti_categoria"]["keywords"]);
		else if (strcmp($r[0]["categories"]["keywords"],"") !== 0)
			$data["keywords"] = htmlentitydecode($r[0]["categories"]["keywords"]);
		
		$data["breadcrumb"] = $this->breadcrumb();
		
		$iChildrenGross = $this->m["CategoriesModel"]->immediateChildren($clean['id']);
		
		$data["iChildren"] = array();
		foreach ($iChildrenGross as $row)
		{
			if ($this->m["CategoriesModel"]->hasActivePages($row["categories"]["id_c"]))
			{
				$data["iChildren"][] = $row;
			}
		}
		
		//se solo prodotti categoria corrente!!
// 		$data["pages"] = $this->m["PagesModel"]->clear()->where(array("-id_c"=>$clean['id'],"attivo"=>"Y"))->orderBy("id_order")->send();

		//se tutti i prodotti figli!!
		$children = $this->m["CategoriesModel"]->children($clean['id'], true);
		$catWhere = "in(".implode(",",$children).")";
		$this->m["PagesModel"]->clear()->select("distinct pages.codice_alfa,pages.*,categories.*,contenuti_tradotti.*,contenuti_tradotti_categoria.*")->where(array(
			"in" => array("-id_c" => $children),
			"pages.attivo"	=>	"Y",
		));
		
		if (Parametri::$hideNotAllowedNodesInLists)
		{
			$accWhere = $this->m["PagesModel"]->getAccessibilityWhere();
			$this->m["PagesModel"]->aWhere($accWhere);
		}
		
		if ($this->idMarchio)
			$this->m["PagesModel"]->aWhere(array(
				"id_marchio"	=>	(int)$this->idMarchio,
			));
		
		if ($this->idTag)
			$this->m["PagesModel"]->inner(array("tag"))->aWhere(array(
				"pages_tag.id_tag"	=>	(int)$this->idTag,
			));
		
		if ($this->catSWhere)
			$this->m["PagesModel"]->sWhere($this->catSWhere);
		
		$data["pages"] = $this->m["PagesModel"]->orderBy($this->gerOrderBy($section))->send();
		
		if ($firstSection == Parametri::$nomeSezioneProdotti)
			$this->m["PagesModel"]->orderBy($this->gerOrderByProdotti($this->viewArgs['o']));
		else
			$this->m["PagesModel"]->orderBy($this->gerOrderBy($section));
		
		$data["url_ordinamento"] = $this->baseUrl."/".$this->getCurrentUrl(false);
		
		$rowNumber = $data["rowNumber"] = $this->m["PagesModel"]->inner("categories")->on("categories.id_c = pages.id_c")
			->left("contenuti_tradotti")->on("contenuti_tradotti.id_page = pages.id_page and contenuti_tradotti.lingua = '".sanitizeDb(Params::$lang)."'")
			->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_page = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")
			->rowNumber();
		
// 		echo $this->m["PagesModel"]->getQuery();
// 		print_r($data["pages"]);
// 		die();
// 		$rowNumber = $data["rowNumber"] = count($data["pages"]);
		
		if ($rowNumber > $this->elementsPerPage)
		{
			//load the Pages helper
			$this->helper('Pages',$this->getCurrentUrl(false),'p');
			
			$page = $this->viewArgs['p'];
			
			$this->m['PagesModel']->limit = $this->h['Pages']->getLimit($page,$rowNumber,$this->elementsPerPage);
			$data["pages"] = $this->m['PagesModel']->send();
			
			$data['pageList'] = $this->h['Pages']->render($page-5,11);
		}
		else
			$data["pages"] = $this->m['PagesModel']->send();
		
		$this->pages = $data["pages"];
		
		// Estraggo le fasce
		$data["fasce"] = $this->m["ContenutiModel"]->elaboraContenuti(0, $clean['id'], $this);
		
		$pagineConDecode = array();
		
		if (Output::$json)
		{
			foreach ($data["pages"] as $page)
			{
				$temp = $page;
				$page["quantity"] = 1;
				$page["pages"]["url-alias"] = getUrlAlias($page["pages"]["id_page"]);
				$page["pages"]["price"] = number_format(calcolaPrezzoIvato($page["pages"]["id_page"], $page["pages"]["price"]),2,",",".");
				$page["pages"]["prezzo_promozione"] = number_format($page["pages"]["prezzo_promozione"],2,",",".");
				$page["pages"]["prezzo_scontato"] = prezzoPromozione($temp);
				$page["pages"] = htmlentitydecodeDeep($page["pages"]);
				$page["categories"] = htmlentitydecodeDeep($page["categories"]);
				$page["contenuti_tradotti"] = htmlentitydecodeDeep($page["contenuti_tradotti"]);
				$page["contenuti_tradotti_categoria"] = htmlentitydecodeDeep($page["contenuti_tradotti_categoria"]);
				
				$pagineConDecode[] = $page;
			}
		}
		
		Output::setBodyValue("Type", "Category");
		Output::setBodyValue("Pages", $pagineConDecode);
		
		$this->append($data);
		
		if (Output::$html)
			$this->sectionLoad($section, "category", $template);
		else
			$this->load("api_output");
	}
	
	protected function gerOrderByProdotti($string)
	{
		switch($string)
		{
			case "tutti":
				return "pages.id_order";
			case "crescente":
				return "pages.price,pages.id_order";
			case "decrescente":
				return "pages.price desc,pages.id_order";
			default:
				return "pages.id_order";
		}
	}
	
	protected function gerOrderBy($section)
	{
		switch ($section)
		{
			case "blog":
				return "pages.data_news desc";
				break;
		}
		
		return "pages.id_order";
	}
	
	private function sectionLoad($section, $type = "category", $template = null)
	{
		$alloweSections = $this->m["CategoriesModel"]->clear()->select("section")->where(array(
			"ne" => array("section" => ""),
			" ne" => array("section" => Parametri::$hierarchicalRootTitle),
		))->toList("section")->send();
		
		$viewFile = null;
		
		if (!isset($template))
		{
			if (in_array($section, $alloweSections))
			{
				if ($type === "category")
				{
					$viewFile = $section;
				}
				else
				{
					$viewFile = "$section-details";
				}
			}
		}
		else
		{
			$viewFile = $template;
		}
		
		if (isset($viewFile) and file_exists(ROOT."/Application/Views/Contenuti/$viewFile.php"))
		{
			$this->load($viewFile);
		}
		else
		{
			if ($type === "category")
			{
				$this->load("category");
			}
			else
			{
				$this->load("page");
			}
		}
	}
	
	private function checkPage($id)
	{
		$clean['id'] = (int)$id;
		
		if (!$this->m['PagesModel']->check($clean["id"]))
		{
			if ($this->islogged)
			{
				$this->redirect("contenuti/nonpermesso");
			}
			else
			{
				$this->redirect("regusers/login");
			}
		}
	}
	
	private function checkCategory($id)
	{
		$clean['id'] = (int)$id;
		
		if (!$this->m['CategoriesModel']->check($clean["id"]))
		{
			if ($this->islogged)
			{
				$this->redirect("contenuti/nonpermesso");
			}
			else
			{
				$this->redirect("regusers/login");
			}
		}
	}
	
	protected function page($id)
	{
		$clean["realId"] = $data["realId"] = (int)$id;
		
		$this->checkPage($clean["realId"]);
		
		$clean['id'] = $this->m['PagesModel']->getPrincipale($clean["realId"]);
		
// 		$urlAlias = $this->m["PagesModel"]->getUrlAlias($clean['realId']);
		
		$data["arrayLingue"] = array();
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/".$this->m["PagesModel"]->getUrlAlias($clean['realId'], $l);
		}
		
// 		print_r($data["arrayLingue"]);
// 		print_r($arrayLingue);
// 		$data["itUrl"] = "it/$urlAlias";
// 		$data["enUrl"] = "en/$urlAlias";
		
		$data["isPage"] = true;
		
		$data["breadcrumb"] = $this->breadcrumb("page");

		$data["scaglioni"] = $this->scaglioni = $this->m["ScaglioniModel"]->clear()->where(array("id_page"=>$clean['id']))->toList("quantita","sconto")->send();
		
		$data["pages"] = $this->pages = $this->m['PagesModel']->clear()->select("pages.*,categories.*,contenuti_tradotti.*,contenuti_tradotti_categoria.*,marchi.*")
			->inner("categories")->on("categories.id_c = pages.id_c")
			->left("contenuti_tradotti")->on("contenuti_tradotti.id_page = pages.id_page and contenuti_tradotti.lingua = '".sanitizeDb(Params::$lang)."'")
			->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")
			->left("marchi")->on("pages.id_marchio = marchi.id_marchio")
			->where(array("id_page"=>$clean['id']))->send();
		
		if (count($data["pages"]) > 0)
			$this->p = $data["pages"][0];
		
		$data["paginaPrecedente"] = $this->m['PagesModel']->where(array(
			"lt"	=>	array("pages.data_news"	=>	$data['pages'][0]["pages"]["data_news"]),
		))->orderBy("pages.data_news desc")->limit(1)->send();
		
		$data["paginaSuccessiva"] = $this->m['PagesModel']->where(array(
			"gt"	=>	array("pages.data_news"	=>	$data['pages'][0]["pages"]["data_news"]),
		))->orderBy("pages.data_news")->limit(1)->send();
		
// 		print_r($data["pages"]);
		if ($data['pages'][0]["contenuti_tradotti"]["meta_description"])
			$data["meta_description"] = htmlentitydecode($data['pages'][0]["contenuti_tradotti"]["meta_description"]);
		else if (strcmp($data['pages'][0]["pages"]["meta_description"],"") !== 0)
			$data["meta_description"] = htmlentitydecode($data['pages'][0]["pages"]["meta_description"]);
		
		if ($data['pages'][0]["contenuti_tradotti"]["keywords"])
			$data["keywords"] = htmlentitydecode($data['pages'][0]["contenuti_tradotti"]["keywords"]);
		else if (strcmp($data['pages'][0]["pages"]["keywords"],"") !== 0)
			$data["keywords"] = htmlentitydecode($data['pages'][0]["pages"]["keywords"]);
		
		list ($colonne, $data["lista_valori_attributi"]) = $this->m['PagesModel']->selectAttributi($clean['id']);
		
		$data["lista_attributi"] = $this->lista_attributi = $colonne;
		
// 		print_r($colonne);
// 		print_r($data["lista_valori_attributi"]);die();
		
		$this->lista_valori_attributi = $data["lista_valori_attributi"];
		
		$data["prezzoMinimo"] = $this->prezzoMinimo = $this->m['PagesModel']->prezzoMinimo($clean['id']);
		
		$data["prodotti_correlati"] = $this->m['PagesModel']->clear()->select("pages.*,prodotti_correlati.id_corr,categories.*,contenuti_tradotti.*,contenuti_tradotti_categoria.*")->from("prodotti_correlati")->inner("pages")->on("pages.id_page=prodotti_correlati.id_corr")
			->inner("categories")->on("categories.id_c = pages.id_c")
			->left("contenuti_tradotti")->on("contenuti_tradotti.id_page = pages.id_page and contenuti_tradotti.lingua = '".sanitizeDb(Params::$lang)."'")
			->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")
			->where(array(
				"prodotti_correlati.id_page"=>	$clean['id'],
				"attivo"	=>	"Y",
				"prodotti_correlati.accessorio"=>	0,
			))->orderBy("prodotti_correlati.id_order")->send();
		
		if (v("accessori_in_prodotti"))
		{
			$this->m['PagesModel']->where["prodotti_correlati.accessorio"] = 1;
			$data["accessori"] = $this->m['PagesModel']->send();
// 			echo $this->m['PagesModel']->getQuery();die();
		}
		
		$data["altreImmagini"] = array();
		
		if (count($data["pages"]) > 0)
		{
			$data["altreImmagini"] = $this->m["ImmaginiModel"]->clear()->where(array("id_page" => $clean['id']))->orderBy("id_order")->send(false);
		}
		
		$this->altreImmagini = $data["altreImmagini"];
		
		// CARATTERISTICHE
		$data["caratteristiche"] = $data["lista_caratteristiche"] = $this->m["PagescarvalModel"]->clear()->select("caratteristiche_valori.*,caratteristiche.*,caratteristiche_tradotte.*,caratteristiche_valori_tradotte.*")
			->inner("caratteristiche_valori")->using("id_cv")
			->inner("caratteristiche")->using("id_car")
			->left("contenuti_tradotti as caratteristiche_tradotte")->on("caratteristiche_tradotte.id_car = caratteristiche.id_car and caratteristiche_tradotte.lingua = '".sanitizeDb(Params::$lang)."'")
			->left("contenuti_tradotti as caratteristiche_valori_tradotte")->on("caratteristiche_valori_tradotte.id_cv = caratteristiche_valori.id_cv and caratteristiche_valori_tradotte.lingua = '".sanitizeDb(Params::$lang)."'")
			->orderBy("pages_caratteristiche_valori.id_order")
			->where(array(
				"pages_caratteristiche_valori.id_page"=>$clean['id']
			))
			->orderBy("pages_caratteristiche_valori.id_order")
			->send();
		
		// Personalizzazioni
		if (v("attiva_personalizzazioni"))
		{
			$data["personalizzazioni"] = $this->m['PagesModel']->selectPersonalizzazioni($clean['id']);
		}
		
		$data["documenti"] = $this->m["PagesModel"]->getDocumenti($clean['id']);
		
		if (v("mostra_link_in_blog"))
		{
			$this->model("PageslinkModel");
			$data["links"] = $this->m["PageslinkModel"]->clear()->where(array(
				"id_page"	=>	$clean['id'],
			))->orderBy("titolo")->send();
		}
		
		$firstSection = $this->m["PagesModel"]->section($clean['id'], true);
		
		if ($firstSection == "prodotti")
			$data["isProdotto"] = true;
		
		//estrai i dati della categoria
		$r = $this->m['CategoriesModel']->clear()->select("categories.*,contenuti_tradotti_categoria.*")->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")->where(array("section"=>sanitizeAll($firstSection)))->send();
		$data["datiCategoriaPrincipale"] = $r[0];
		
		$data["pagesCss"] = $data['pages'][0]["pages"]["css"];
		
		// Estraggo le fasce
		$data["fasce"] = $this->m["ContenutiModel"]->elaboraContenuti($clean['id'], 0, $this);
		
		// Estraggo i contenuti generici
		$data["contenuti"] = $this->m["ContenutiModel"]->clear()->select("contenuti.*,tipi_contenuto.*")->inner(array("tipo"))->where(array(
			"OR"	=>	array(
				"lingua" => "tutte",
				" lingua" => sanitizeDb(Params::$lang),
			),
			"tipo"	=>	"GENERICO",
			"id_page"	=>	$clean['id'],
			"attivo"	=>	"Y",
		))->save()->orderBy("contenuti.id_order")->send();
		
		// Estraggo i marker
		$data["marker"] = $this->m["ContenutiModel"]->restore(true)->where(array(
			"tipo"	=>	"marker",
		))->send();
		
// 		print_r($data["marker"]);die();
		
		$this->append($data);
		
		$template = strcmp($data['pages'][0]["pages"]["template"],"") === 0 ? null : $data['pages'][0]["pages"]["template"];
		
		$section = $this->m["PagesModel"]->section($clean['id']);
		
// 		$this->sectionLoad($section, "page", $template);
		
		$pagineConDecode = array();
		
		if (Output::$json)
		{
			foreach ($data["pages"] as $page)
			{
				$temp = $page;
				$page["quantity"] = 1;
				$page["pages"]["url-alias"] = getUrlAlias($page["pages"]["id_page"]);
				$page["pages"] = htmlentitydecodeDeep($page["pages"]);
				
				$page["pages"]["caratteristiche"] = $data["lista_caratteristiche"];
				
				$scaglioni = array();
				
				foreach ($data["scaglioni"] as $qty => $sconto)
				{
					$sconto = number_format($sconto,2,",","");
					$scaglioni[] = array($qty, $sconto);
				}
				
				$correlati = array();
				
				foreach ($data["prodotti_correlati"] as $corr)
				{
					$tempCorr = $corr;
					$corr["pages"]["price"] = number_format(calcolaPrezzoIvato($corr["pages"]["id_page"], $corr["pages"]["price"]),2,",",".");
					$corr["pages"]["prezzo_promozione"] = number_format($corr["pages"]["prezzo_promozione"],2,",",".");
					$corr["pages"]["prezzo_scontato"] = prezzoPromozione($tempCorr);
					$corr["pages"]["url-alias"] = getUrlAlias($corr["pages"]["id_page"]);
					
					$corr["pages"] = htmlentitydecodeDeep($corr["pages"]);
					$corr["categories"] = htmlentitydecodeDeep($corr["categories"]);
					$corr["contenuti_tradotti"] = htmlentitydecodeDeep($corr["contenuti_tradotti"]);
					$corr["contenuti_tradotti_categoria"] = htmlentitydecodeDeep($corr["contenuti_tradotti_categoria"]);
				
					$correlati[] = $corr;
				}
				
				$page["pages"]["scaglioni"] = $scaglioni;
				
				$page["pages"]["price"] = number_format(calcolaPrezzoIvato($page["pages"]["id_page"], $page["pages"]["price"]),2,",",".");
				$page["pages"]["prezzo_promozione"] = number_format($page["pages"]["prezzo_promozione"],2,",",".");
				$page["pages"]["prezzo_scontato"] = prezzoPromozione($temp);
				$page["pages"]["altre_immagini"] = $data["altreImmagini"];
				$page["pages"]["correlati"] = $correlati;
				
				$page["categories"] = htmlentitydecodeDeep($page["categories"]);
				$page["categories"]["url-alias"] = getCategoryUrlAlias($page["categories"]["id_c"]);
				
				$page["contenuti_tradotti"] = htmlentitydecodeDeep($page["contenuti_tradotti"]);
				$page["contenuti_tradotti_categoria"] = htmlentitydecodeDeep($page["contenuti_tradotti_categoria"]);
				
				$page["marchi"] = htmlentitydecodeDeep($page["marchi"]);
				
				$pagineConDecode[] = $page;
			}
		}
		
		Output::setBodyValue("Type", "Page");
		Output::setBodyValue("Pages", $pagineConDecode);
		
		if (Output::$html)
		{
			if (!isset($_GET["pdf"]))
				$this->sectionLoad($section, "page", $template);
			else
			{
				$this->clean();
				
				extract($data);
				
				require_once(ROOT."/admin/External/mpdfPHP7/vendor/autoload.php");

				ob_start();
				include(ROOT."/Application/Views/Contenuti/pdf.php");
				$content = ob_get_clean();
				
				$params = [
					'mode' => '',
					'format' => 'A4',
					'default_font_size' => "9",
					'default_font' => "",
					'margin_left' => "6",
					'margin_right' => "6",
					'margin_top' => "5",
					'margin_bottom' => "10",
					'margin_header' => "0",
					'margin_footer' => "2",
					'orientation'	=>	"P",
				];
				
				$html2pdf = new \Mpdf\Mpdf($params);
				
				$html2pdf->setDefaultFont('Arial');
				
				$html2pdf->WriteHTML($content);
				
// 				$html2pdf->Output(ROOT . "/media/Documenti/" . $titoloDocumento,'F');
				
				$html2pdf->Output(field($pages[0],"title").".pdf","I");
			}
		}
		else
			$this->load("api_output");
	}
	
	//controlla la combinazione (chiamata via ajax)
	public function comb()
	{
		$this->clean();
		
		$clean["id_page"] = $this->request->post("id_page",0,"forceInt");
		$clean["strcomb"] = $this->request->post("strcomb","","sanitizeAll");
		
		$where = array(
			"id_page"	=>	$clean["id_page"],
		);
		
		$allowedFields = array("col_1","col_2","col_3","col_4","col_5","col_6","col_7","col_8");
		
		$arrayComb = explode("|",$clean["strcomb"]);
		
		foreach ($arrayComb as $comb)
		{
			if (strcmp($comb,"") !== 0 and strstr($comb, ':'))
			{
				$temp = explode(":",$comb);
				
				$field = sanitizeAll($temp[0]);
				
				if (!in_array($field,$allowedFields))
				{
					die("KO");
				}
				
				$value = sanitizeAll($temp[1]);
				$where[$field] = $value;
			}
		}
		
		$res = $this->m["CombinazioniModel"]->clear()->where($where)->send();
		
		if (count($res) > 0)
		{
			$prezzoPieno = "";
			if (inPromozioneTot($clean["id_page"]))
				$prezzoPieno = setPriceReverse(calcolaPrezzoIvato($clean["id_page"], $res[0]["combinazioni"]["price"]));
			
			$qty = (int)$res[0]["combinazioni"]["giacenza"];
			
			if ($qty < 0)
				$qty = 0;
			
			echo '<span class="id_combinazione">'.$res[0]["combinazioni"]["id_c"].'</span><span class="codice_combinazione">'.$res[0]["combinazioni"]["codice"].'</span><span class="prezzo_combinazione">'.setPriceReverse(calcolaPrezzoFinale($clean["id_page"], $res[0]["combinazioni"]["price"])).'</span><span class="immagine_combinazione">'.$res[0]["combinazioni"]["immagine"].'</span><span class="prezzo_pieno_combinazione">'.$prezzoPieno.'</span><span class="giacenza_combinazione">'.$qty.'</span>';
		}
		else
		{
			echo "KO";
		}
	}
	
	public function search()
	{
		$clean["s"] = $data["s"] = $this->request->get("s","","sanitizeAll");
		
		$data["title"] = Parametri::$nomeNegozio . " - Cerca";
		
		$argKeys = array(
			'p:forceNat'	=>	1,
			's:sanitizeAll'	=>	"",
			'sec:sanitizeAll'	=>	"prodotti",
		);

		$this->setArgKeys($argKeys);
		$this->shift(count($this->pageArgs));
		
		//load the Pages helper
		$this->helper('Pages','risultati-ricerca','p');
		
		$data["pages"] = array();
		
		if (strcmp($this->viewArgs["s"],"") !== 0)
		{
			if ($this->viewArgs["sec"] == "prodotti")
				$clean["idSection"] = $this->m["CategoriesModel"]->getShopCategoryId();
			else
				$clean["idSection"] = $this->m['CategoriesModel']->clear()->where(array(
					"section"	=>	$this->viewArgs["sec"],
				))->field("id_c");
				
			$childrenProdotti = $this->m["CategoriesModel"]->children($clean["idSection"], true);
			
			$where = array(
				" OR"=> array(
					"lk" => array('pages.title' => $this->viewArgs["s"]),
					" lk" => array('pages.codice' => $this->viewArgs["s"]),
					"  lk" =>  array('contenuti_tradotti.title' => $this->viewArgs["s"]),
					),
				"in" => array("-id_c" => $childrenProdotti),
				"attivo" => "Y",
				"principale" => "Y"
			);
			
			$data["pages"] = $this->m['PagesModel']->clear()->where($where);
			
			if (Parametri::$hideNotAllowedNodesInLists)
			{
				$accWhere = $this->m["PagesModel"]->getAccessibilityWhere();
				$this->m["PagesModel"]->aWhere($accWhere);
			}
		
			$rowNumber = $data["rowNumber"] = $this->m['PagesModel']->select("distinct pages.codice_alfa, pages.*,contenuti_tradotti.*,contenuti_tradotti_categoria.*,categories.*")
				->inner("categories")->on("categories.id_c = pages.id_c")
				->left("contenuti_tradotti")->on("contenuti_tradotti.id_page = pages.id_page and contenuti_tradotti.lingua = '".sanitizeDb(Params::$lang)."'")
				->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_page = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")
				->orderBy("pages.id_order")
				->rowNumber();
			
			
// 			$rowNumber = $data["rowNumber"] = count($data["pages"]);
			
			$this->elementsPerPage = 999999;
			
			if ($rowNumber > $this->elementsPerPage)
			{
				$page = $this->viewArgs['p'];
				
				$this->m['PagesModel']->limit = $this->h['Pages']->getLimit($page,$rowNumber,$this->elementsPerPage);
				
				$data['pageList'] = $this->h['Pages']->render($page-5,11);
			}
			
			$data["pages"] = $this->m['PagesModel']->send();
		}
		
		$this->append($data);
		
		$pagineConDecode = array();
		
		if (Output::$json)
		{
			foreach ($data["pages"] as $page)
			{
				$temp = $page;
				$page["quantity"] = 1;
				$page["pages"]["url-alias"] = getUrlAlias($page["pages"]["id_page"]);
				$page["pages"]["price"] = number_format(calcolaPrezzoIvato($page["pages"]["id_page"], $page["pages"]["price"]),2,",",".");
				$page["pages"]["prezzo_promozione"] = number_format($page["pages"]["prezzo_promozione"],2,",",".");
				$page["pages"]["prezzo_scontato"] = prezzoPromozione($temp);
				$page["pages"] = htmlentitydecodeDeep($page["pages"]);
				$page["categories"] = htmlentitydecodeDeep($page["categories"]);
				$page["contenuti_tradotti"] = htmlentitydecodeDeep($page["contenuti_tradotti"]);
				$page["contenuti_tradotti_categoria"] = htmlentitydecodeDeep($page["contenuti_tradotti_categoria"]);
				
				$pagineConDecode[] = $page;
			}
		}
		
		Output::setBodyValue("Type", "Search");
		Output::setBodyValue("Pages", $pagineConDecode);
		
		if (Output::$html)
			$this->load('search');
		else
			$this->load("api_output");
	}
	
	public function promozione()
	{
		$nowDate = date("Y-m-d");
		$where = array(
			"gte"	=>	array("n!datediff('$nowDate',pages.dal)" => 0),
			" gte"	=>	array("n!datediff(pages.al,'$nowDate')" => 0),
			"attivo" => "Y",
			"in_promozione" => "Y",
			"principale" => "Y",
		);
		
		$data["title"] = Parametri::$nomeNegozio . " - Promozioni";
		
		$data["pages"] = $this->m['PagesModel']->clear()->where($where);
			
		if (Parametri::$hideNotAllowedNodesInLists)
		{
			$accWhere = $this->m["PagesModel"]->getAccessibilityWhere();
			$this->m["PagesModel"]->aWhere($accWhere);
		}
	
		$data["pages"] = $this->m['PagesModel']->select("distinct pages.codice_alfa,pages.*")->orderBy("pages.id_order")->send();
		
		$rowNumber = $data["rowNumber"] = count($data["pages"]);
		
		if ($rowNumber > $this->elementsPerPage)
		{
			$argKeys = array(
				'p:forceNat'	=>	1,
			);

			$this->setArgKeys($argKeys);
			$this->shift(count($this->pageArgs));
			
			//load the Pages helper
			$this->helper('Pages','prodotti-in-promozione','p');
			
			$page = $this->viewArgs['p'];
			
			$this->m['PagesModel']->limit = $this->h['Pages']->getLimit($page,$rowNumber,$this->elementsPerPage);
			$data["pages"] = $this->m['PagesModel']->send();
			
			$data['pageList'] = $this->h['Pages']->render($page-5,11);
		}
		
		$this->append($data);
		$this->load('promozione');
	}
	
	public function sitemap()
	{
		header ("Content-Type:text/xml");
		
		$this->clean();
		
		$data["sitemap"] = $this->m["PagesModel"]->clear()->where(array("attivo"=>"Y","add_in_sitemap"=>"Y"))->orderBy("data_creazione desc")->limit(100)->send();
		
		$this->append($data);
		$this->load('sitemap');
	}
	
	public function jsoncategorie($section = "")
	{
		$this->clean();
		
		if (!$section)
			$section = Parametri::$nomeSezioneProdotti;
		
		$idShopCat = (int)$this->m['CategoriesModel']->clear()->where(array(
			"section"	=>	Parametri::$nomeSezioneProdotti
		))->field("id_c");
		
		$res = $this->m["CategoriesModel"]->recursiveTree($idShopCat,2);
// 		$res = json_encode($res);
		
		Output::setBodyValue("Type", "Menu");
		Output::setBodyValue("Categories", $res);
		
		$this->load("api_output");
	}
	
	public function documento($id)
	{
		$documento = $this->m["DocumentiModel"]->selectId((int)$id);
		
		if (!empty($documento))
		{
			$path = ROOT."/images/documenti/".$documento['filename'];
				
			if (file_exists($path))
			{
				$extArray = explode('.', $documento['filename']);
				$ext = strtolower(end($extArray));
			
				$contentDisposition = ($ext == "pdf" || $ext == "png" || $ext == "jpg" || $ext == "jpeg") ? "inline" : "attachment";
				
				header('Content-disposition: '.$contentDisposition.'; filename='.$documento['clean_filename']);
				header('Content-Type: '.$documento['content_type']);
// 					echo file_get_contents(ROOT."/".Parametri::$cartellaDocumenti."/".$documento['filename']);
				readfile($path);
			}
		}
	}
}
