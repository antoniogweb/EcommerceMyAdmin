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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!defined('EG')) die('Direct access not allowed!');

class BaseContenutiController extends BaseController
{
	public $pageArgs = array();
	public $originalPageArgs = array();
	public $filtriCaratteristiche = array();
	public $filtriRegione = array();
	public $urlParent = array(); //parents array (only ALIAS) as taken by the URL
	public $rParent = array(); //right parents array (only ALIAS) as taken by database
	public $parents = array(); //right parents array as taken by database
	public $fullParents = array(); //right parents plus current element array as taken by database
	public $currUrl = null; //the URL of the current page
	public $elementsPerPage = 9999999; //number of elements per page
	public $idMarchio = 0;
	public $idTag = 0;
	public $aliasMarchio = "";
	public $aliasTag = "";
	public $documentiPagina = array();
	public $breadcrumbHtml = "";
	
	public $altreImmagini = array(); // altre immagini
	public $lista_attributi;
	public $lista_valori_attributi;
	public $scaglioni;
	public $prezzoMinimo;
	
	public $firstSection;
	public $section;
	public $catSWhere = "";
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->loadHeaderFooter();
		
		$data["elementsPerPage"] = $this->elementsPerPage;
		
		$this->append($data);
	}
	
	protected function loadHeaderFooter($viewFile = null)
	{
		if (Output::$html && !isset($_GET["vista_parziale"]))
		{
			$this->load('header');
			
			if ($viewFile)
				$this->load($viewFile);
			
			$this->load('footer','last');
		}
	}
	
	protected function sistemaFiltriLocalita()
	{
		if (count($this->filtriRegione) > 0 && ((count($this->filtriRegione) % 2) === 0))
		{
			$tempCar = array_chunk($this->filtriRegione, 2);
			
			foreach ($tempCar as $carArray)
			{
				if ((int)count($carArray) === 2)
				{
					$carAlias = sanitizeHtml($carArray[0]);
					$carValoreAlias = sanitizeHtml($carArray[1]);
					
					if (isset(RegioniModel::$filtriUrl[$carAlias]))
					{
						if (!in_array($carValoreAlias, RegioniModel::$filtriUrl[$carAlias]))
							RegioniModel::$filtriUrl[$carAlias][] = $carValoreAlias;
					}
					else
						RegioniModel::$filtriUrl[$carAlias] = array($carValoreAlias);
				}
			}
		}
	}
	
	protected function sistemaFiltriCaratteristiche()
	{
		if (count($this->filtriCaratteristiche) > 0 && ((count($this->filtriCaratteristiche) % 2) === 0))
		{
			$tempCar = array_chunk($this->filtriCaratteristiche, 2);
			
			foreach ($tempCar as $carArray)
			{
				if ((int)count($carArray) === 2)
				{
					$carAlias = sanitizeHtml($carArray[0]);
					$carValoreAlias = sanitizeHtml($carArray[1]);
					
					if (isset(CaratteristicheModel::$filtriUrl[$carAlias]))
					{
						if (!in_array($carValoreAlias, CaratteristicheModel::$filtriUrl[$carAlias]))
							CaratteristicheModel::$filtriUrl[$carAlias][] = $carValoreAlias;
					}
					else
						CaratteristicheModel::$filtriUrl[$carAlias] = array($carValoreAlias);
				}
			}
		}
	}
	
	protected function checkFiltriOk()
	{
		$filtriOk = true;
		
		if (count($this->filtriCaratteristiche) > 0 && (count($this->filtriCaratteristiche) % 2) != 0)
			$filtriOk = false;
		
		if (count($this->filtriRegione) > 0 && (count($this->filtriRegione) % 2) != 0)
			$filtriOk = false;
		
		if (!$filtriOk)
			$this->filtriCaratteristiche = $this->filtriRegione = array();
		
		return $filtriOk;
	}
	
	protected function getFiltriDaUrl()
	{
		$divisorioFiltriUrl = v("divisorio_filtri_url");
		
		// Altri filtri
		foreach (AltriFiltri::$altriFiltri as $filtro)
		{
			$tempsPageArgs = $this->pageArgs;
			
			if (in_array($filtro, $this->pageArgs) && (string)$this->pageArgs[count($this->pageArgs) - 1] !== (string)$filtro && (string)$this->pageArgs[count($this->pageArgs) - 1])
			{
				$indiciDivisori = array_keys($this->pageArgs, $filtro);
				
				if (count($indiciDivisori) > 0)
				{
					if ((int)count($indiciDivisori) === 1)
					{
						$filtriUrl = array_slice($tempsPageArgs, $indiciDivisori[0]);
						
						if ((int)count($filtriUrl) === 2)
						{
							$carAlias = sanitizeHtml($filtriUrl[0]);
							$carValoreAlias = sanitizeHtml($filtriUrl[1]);
								
							AltriFiltri::$filtriUrl[$carAlias] = $carValoreAlias;
							
							$this->pageArgs = array_slice($tempsPageArgs, 0, $indiciDivisori[0]);
						}
					}
				}
			}
		}
		
		// Filtri Caratteristiche e Località
		if (in_array($divisorioFiltriUrl, $this->pageArgs) && (string)$this->pageArgs[count($this->pageArgs) - 1] !== (string)$divisorioFiltriUrl && (string)$this->pageArgs[count($this->pageArgs) - 1])
		{
			$indiciDivisori = array_keys($this->pageArgs, $divisorioFiltriUrl);
			
			if (count($indiciDivisori) > 0)
			{
				$tempsPageArgs = $this->pageArgs;
				
				if ((int)count($indiciDivisori) === 1)
				{
					$filtriUrl = array_slice($tempsPageArgs, ($indiciDivisori[0] + 1));
					
					if (count($filtriUrl) > 0)
					{
						if (strcmp($filtriUrl[0],RegioniModel::$nAlias) === 0 || strcmp($filtriUrl[0],RegioniModel::$rAlias) === 0)
							$this->filtriRegione = $filtriUrl;
						else
							$this->filtriCaratteristiche = $filtriUrl;
					}
				}
				else if ((int)count($indiciDivisori) > 1)
				{
					$distanzaIndici = $indiciDivisori[1] - $indiciDivisori[0];
					$this->filtriCaratteristiche = array_slice($tempsPageArgs, ($indiciDivisori[0] + 1), ($distanzaIndici - 1));
					$this->filtriRegione = array_slice($tempsPageArgs, ($indiciDivisori[1] + 1));
				}
				
				if ($this->checkFiltriOk())
					$this->pageArgs = array_slice($tempsPageArgs, 0, $indiciDivisori[0]);
				
				$this->sistemaFiltriCaratteristiche();
				$this->sistemaFiltriLocalita();
			}
		}
	}
	
	public function index()
	{
		CategoriesModel::setAliases();
		
		$this->pageArgs = $this->originalPageArgs = func_get_args();
		
		// Recupera i filtri dall'URL
		$this->getFiltriDaUrl();
		
		$titleTag = $titleMarchio = "";
		
		$data = array();
		
		if (v("usa_tag"))
		{
			$elencoTag = $this->m["TagModel"]->clear()->addJoinTraduzione()->send();
			$elencoTagEncoded = array();
			$elencoTagTitle = array();
			foreach ($elencoTag as $tag)
			{
				$elencoTagEncoded[tagfield($tag,"alias")] = $tag["tag"]["id_tag"];
				$elencoTagTitle[tagfield($tag,"alias")] = tagfield($tag,"titolo");
			}
			
			if (count($this->pageArgs) > 0 && isset($elencoTagEncoded[$this->pageArgs[0]]))
			{
				$this->idTag = $elencoTagEncoded[$this->pageArgs[0]];
				$titleTag = $elencoTagTitle[$this->pageArgs[0]];
				$this->aliasTag = $data["aliasTagCorrente"] = $this->pageArgs[0];
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
				$this->aliasMarchio = $data["aliasMarchioCorrente"] = $this->pageArgs[0];
				
				array_shift($this->pageArgs);
			}
		}
		
		if( count($this->pageArgs) > 0 && strcmp($this->pageArgs[count($this->pageArgs)-1],"") === 0)
		{
			array_pop($this->pageArgs);
		}
		
		// Controlla che non sia un marchio o un tag
		if (count($this->pageArgs) === 0 && (($this->idMarchio && !v("attiva_pagina_produttore")) || $this->idTag))
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
						
						$metaTitlePagina = field($parents[$id][count($parents[$id])-1], "meta_title");
						$titoloPagina = field($parents[$id][count($parents[$id])-1], "title");
						
						$titleDaUsare = trim($metaTitlePagina) ? $metaTitlePagina : $titoloPagina;
						
						$data["title"] = Parametri::$nomeNegozio . " - ".$titleDaUsare;
						
// 						if (isset($parents[$id][count($parents[$id])-1]["contenuti_tradotti"]["title"]) && $parents[$id][count($parents[$id])-1]["contenuti_tradotti"]["title"])
// 							$stringIt = $stringLn = $parents[$id][count($parents[$id])-1]["contenuti_tradotti"]["title"];
// 						else
// 							$stringIt = $stringLn = $parents[$id][count($parents[$id])-1]["pages"]["title"];
// // 						$stringLn = $parents[$id][count($parents[$id])-1]["pages"]["title".$this->langDb];
// 						
// 						$data["title"] = Parametri::$nomeNegozio . " - " . strtolower(getField($stringLn,$stringIt));
					}
				}

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
				
				if ($titleTag && (int)$clean['id'] === (int)$this->idShop)
					$data["title"] = Parametri::$nomeNegozio . " - " .$titleTag;
				
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
		else if ($this->idMarchio && v("attiva_pagina_produttore"))
		{
			$this->marchio($this->idMarchio);
		}
		else
			$this->redirect("contenuti/notfound");
		
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
		
		$tempParents = $this->rParent;

		if ($this->idMarchio)
		{
			$t = new MarchiModel();
			$tag = $t->clear()->where(array(
				"id_marchio"	=>	$this->idMarchio,
			))->addJoinTraduzione()->first();
			
			if (!empty($tag))
				array_unshift($tempParents, mfield($tag,"alias"));
		}
		
		if ($this->idTag)
		{
			$t = new TagModel();
			$tag = $t->clear()->where(array(
				"id_tag"	=>	$this->idTag,
			))->addJoinTraduzione()->first();
			
			if (!empty($tag))
				array_unshift($tempParents, tagfield($tag,"alias"));
		}
		
		$arrayUrl = array($this->cleanAlias);
		
		// Caratteristiche
		if (!empty(CaratteristicheModel::$filtriUrl))
			$arrayUrl = array_merge($arrayUrl,CaratteristicheModel::getArrayUrlAll());
		
		// Località
		if (!empty(RegioniModel::$filtriUrl))
			$arrayUrl = array_merge($arrayUrl,RegioniModel::getArrayUrlAll());
		
		// Altri filtri
		if (!empty(AltriFiltri::$filtriUrl))
			$arrayUrl = array_merge($arrayUrl,AltriFiltri::getArrayUrlAll());
		
		$baseUrl = $completeUrl ? $this->baseUrl."/" : null;
		if (count($tempParents) > 0)
		{
			return $baseUrl.implode("/",$tempParents)."/".implode("/",$arrayUrl).$ext;
		}
		else
		{
			return $baseUrl.implode("/",$arrayUrl).$ext;
		}
	}
	
	//create the HTML of the breadcrumb
	protected function breadcrumb($type = "category", $linkInLast = false, $separator = "&raquo;", $fullParents = null)
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
		
		if ($fullParents)
			$tempParents = $fullParents;
		else
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
				$hrefArray[] = (isset($row["contenuti_tradotti"]["alias"]) && $row["contenuti_tradotti"]["alias"]) ? $row["contenuti_tradotti"]["alias"] : $row[$table]["alias"];
				$j++;
			}
			$ref = implode("/",$hrefArray).$ext;
			
			$table = ($i === 0 and $type === "page") ? "pages" : "categories";
			$lClass = $i === 0 ? "breadcrumb_last" : null;
			
			if ($i === 0 and !$linkInLast)
			{
				$titolo = (isset($tempParents[count($tempParents)-1]["contenuti_tradotti"][$title]) && $tempParents[count($tempParents)-1]["contenuti_tradotti"][$title]) ? $tempParents[count($tempParents)-1]["contenuti_tradotti"][$title] : $tempParents[count($tempParents)-1][$table][$title];
				
// 				print_r($tempParents[count($tempParents)-1]);
				array_unshift($breadcrumbArray, v("breadcrumb_element_open")."<span class='breadcrumb_last_text'>".$titolo."</span>".v("breadcrumb_element_close"));
			}
			else
			{
				$alias = (isset($tempParents[count($tempParents)-1]["contenuti_tradotti"]['alias']) && $tempParents[count($tempParents)-1]["contenuti_tradotti"]['alias']) ? $tempParents[count($tempParents)-1]["contenuti_tradotti"]['alias'] : $tempParents[count($tempParents)-1][$table]['alias'];
				
				$titolo = (isset($tempParents[count($tempParents)-1]["contenuti_tradotti"][$title]) && $tempParents[count($tempParents)-1]["contenuti_tradotti"][$title]) ? $tempParents[count($tempParents)-1]["contenuti_tradotti"][$title] : $tempParents[count($tempParents)-1][$table][$title];
				
				array_unshift($breadcrumbArray, v("breadcrumb_element_open")."<a class='$lClass breadcrumb_item ".$alias."' href='".$this->baseUrl."/$ref'>".$titolo."</a>".v("breadcrumb_element_close"));
			}
			
			array_pop($tempParents);
			
			$i++;
		}
		return implode(v("divisone_breadcrum"), $breadcrumbArray);
	}
	
	protected function category($id)
	{
		$this->m["CategoriesModel"]->checkBloccato($id);
		
		if (!in_array("combinazioni", Cache::$cachedTables))
			Cache::$cachedTables[] = "combinazioni";
		
		$argKeys = array(
			'p:forceNat'	=>	1,
			'o:sanitizeAll'	=>	"tutti",
			'search:sanitizeAll'	=>	"",
		);
		
		$this->setArgKeys($argKeys);
		$this->shift(count($this->originalPageArgs));
		
		$clean['id'] = $data["id_categoria"] = (int)$id;
		
		if (v("attiva_formn_contatti"))
			$this->inviaMailFormContatti(0);
		
		$this->checkCategory($clean["id"]);
		
		$section = $data["section"] = $this->section = $this->m["CategoriesModel"]->section($clean['id']);
		$firstSection = $data["fsection"] = $this->firstSection = $this->m["CategoriesModel"]->section($clean['id'], true);
		
		if ($firstSection == "prodotti")
			$this->elementsPerPage = $data["elementsPerPage"] = v("prodotti_per_pagina");
		else if ($firstSection == "blog")
			$this->elementsPerPage = $data["elementsPerPage"] = v("news_per_pagina");
		else if ($firstSection == "eventi")
			$this->elementsPerPage = $data["elementsPerPage"] = v("eventi_per_pagina");
		
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
			
		//estrai i dati della categoria
		$r = $this->m['CategoriesModel']->clear()->select("categories.*,contenuti_tradotti_categoria.*")->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")->where(array("id_c"=>$clean['id']))->send();
		$data["datiCategoria"] = $r[0];
		
		$data["categorieFiglie"] = $this->m['CategoriesModel']->clear()->select("categories.*,contenuti_tradotti_categoria.*")->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")->where(array("id_p"=>$clean['id']))->orderBy("categories.lft")->send();
		
		$template = strcmp($r[0]["categories"]["template"],"") === 0 ? null : $r[0]["categories"]["template"];
		
		if (isset($r[0]["contenuti_tradotti_categoria"]["meta_description"]) && $r[0]["contenuti_tradotti_categoria"]["meta_description"])
			$data["meta_description"] = F::meta($r[0]["contenuti_tradotti_categoria"]["meta_description"]);
		else if (strcmp($r[0]["categories"]["meta_description"],"") !== 0)
			$data["meta_description"] = F::meta($r[0]["categories"]["meta_description"]);
		
		if (isset($r[0]["contenuti_tradotti_categoria"]["keywords"]) && $r[0]["contenuti_tradotti_categoria"]["keywords"])
			$data["keywords"] = F::meta($r[0]["contenuti_tradotti_categoria"]["keywords"]);
		else if (strcmp($r[0]["categories"]["keywords"],"") !== 0)
			$data["keywords"] = F::meta($r[0]["categories"]["keywords"]);
		
		if (isset($tagCorrente) && !empty($tagCorrente) && (int)$id === $this->idShop)
		{
			$data["keywords"] = tagfield($tagCorrente, "keywords");
			$data["meta_description"] = F::meta(tagfield($tagCorrente, "meta_description"));
		}
		
		$data["breadcrumb"] = $this->breadcrumbHtml = $this->breadcrumb();
		
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
		$this->m["PagesModel"]->clear()->restore()->select("distinct pages.codice_alfa,pages.*,categories.*,contenuti_tradotti.*,contenuti_tradotti_categoria.*")->aWhere(array(
			"in" => array("-id_c" => $children),
// 			"pages.attivo"	=>	"Y",
// 			"acquistabile"	=>	"Y",
		))->addWhereAttivo();
		
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
		
		// Promozioni
		if (self::$isPromo)
			$this->addStatoWhereClause(AltriFiltri::$aliasValoreTipoPromo[0]);
		
		$this->addOrderByClause($firstSection);
		
		// Filtri caratteristiche
		if (!empty(CaratteristicheModel::$filtriUrl))
		{
			$combinazioniCaratteristiche = prodottoCartesiano(CaratteristicheModel::$filtriUrl);
			
			$tabellaCaratterisitche = "(select pages_caratteristiche_valori.id_page,group_concat(distinct(concat('#',coalesce(caratteristiche_tradotte.alias,caratteristiche.alias),'#')) order by caratteristiche.alias) as car_alias,group_concat(distinct(concat('#',coalesce(caratteristiche_valori_tradotte.alias,caratteristiche_valori.alias),'#')) order by caratteristiche_valori.alias) as car_val_alias from caratteristiche inner join caratteristiche_valori on caratteristiche_valori.id_car = caratteristiche.id_car inner join pages_caratteristiche_valori on pages_caratteristiche_valori.id_cv = caratteristiche_valori.id_cv and caratteristiche.filtro = 'Y' 
			left join contenuti_tradotti as caratteristiche_tradotte on caratteristiche_tradotte.id_car = caratteristiche.id_car and caratteristiche_tradotte.lingua = '".sanitizeDb(Params::$lang)."' 
			left join contenuti_tradotti as caratteristiche_valori_tradotte on caratteristiche_valori_tradotte.id_cv = caratteristiche_valori.id_cv and caratteristiche_valori_tradotte.lingua = '".sanitizeDb(Params::$lang)."' 
			group by pages_caratteristiche_valori.id_page) as tabella_caratteristiche";
			
			$this->m["PagesModel"]->inner($tabellaCaratterisitche)->on("pages.id_page = tabella_caratteristiche.id_page");
			
			$indice = 6;
			
			$aWhereArrayCar = $aWhereArrayCarVal = array();
			
			$sWhereArray = array();
			
			foreach ($combinazioniCaratteristiche as $combCar)
			{
				$carArray = array_keys($combCar);
				$carValArray = array_values($combCar);
				
				$sWhereArray[] = $carValArray;
			}
			
			$sWhereQueryArray = array();
			
			foreach ($sWhereArray as $sWhereValori)
			{
				$tempWhere = array();
				
				foreach ($sWhereValori as $sWhereValore)
				{
					$tempWhere[] = "car_val_alias like '%#".sanitizeDb($sWhereValore)."#%'";
				}
				
				$sWhereQueryArray[] = "(".implode(" AND ", $tempWhere).")";
			}
			
			if (count($sWhereQueryArray) > 1)
				$sWhereQuery = "(".implode(" OR ", $sWhereQueryArray).")";
			else if (count($sWhereQueryArray) > 0)
				$sWhereQuery = $sWhereQueryArray[0];
			
			$this->m["PagesModel"]->sWhere($sWhereQuery);
		}
		
		// Filtri località
		if (!empty(RegioniModel::$filtriUrl))
		{
			$prodottoTutteLeRegioni = v("prodotto_tutte_regioni_se_nessuna_regione");
			
			if ($prodottoTutteLeRegioni)
				$this->m["PagesModel"]->left(array("regioni"));
			else
				$this->m["PagesModel"]->inner(array("regioni"));
			
			$combinazioniLocalita = prodottoCartesiano(RegioniModel::$filtriUrl);
			
			$sWhereQueryArray = array();
			
			foreach ($combinazioniLocalita as $combCar)
			{
				$tempWhere = array();
				
				foreach ($combCar as $k => $v)
				{
					$field = $k == RegioniModel::$nAlias ? "alias_nazione" : "alias_regione";
					
					$tmpSql = "$field = '".sanitizeDb($v)."'";
					
					if ($prodottoTutteLeRegioni)
						$tmpSql .= " OR $field IS NULL";
					
					$tempWhere[] = $tmpSql;
				}
				
				$sWhereQueryArray[] = "(".implode(" AND ", $tempWhere).")";
			}
			
			if (count($sWhereQueryArray) > 1)
				$sWhereQuery = "(".implode(" OR ", $sWhereQueryArray).")";
			else if (count($sWhereQueryArray) > 0)
				$sWhereQuery = $sWhereQueryArray[0];
			
			$this->m["PagesModel"]->sWhere($sWhereQuery);
		}
		
		if (!empty(AltriFiltri::$filtriUrl))
		{
			foreach (AltriFiltri::$filtriUrl as $tipoFiltro => $valoreFiltro)
			{
				if (isset(AltriFiltri::$altriFiltriTipi["fascia-prezzo"]) && $tipoFiltro == AltriFiltri::$altriFiltriTipi["fascia-prezzo"])
				{
					if (User::$nazione)
						$tabellaListini = "(select id_page,coalesce(combinazioni_listini.price,combinazioni.price) as prezzo_prodotto from combinazioni left join combinazioni_listini on combinazioni_listini.id_c = combinazioni.id_c and combinazioni_listini.nazione = '".sanitizeAll(User::$nazione)."' group by combinazioni.id_page) as tabella_listini";
					else
						$tabellaListini = "(select id_page,combinazioni.price as prezzo_prodotto from combinazioni group by combinazioni.id_page) as tabella_listini";
					
					$this->m["PagesModel"]->inner($tabellaListini)->on("pages.id_page = tabella_listini.id_page");
					
					$fasciaPrezzo = $data["fasciaPrezzo"] = $this->m["FasceprezzoModel"]->clear()->addJoinTraduzione()->sWhere("coalesce(contenuti_tradotti.alias,fasce_prezzo.alias) = '".sanitizeDb($valoreFiltro)."'")->first();
					
					if (!empty($fasciaPrezzo))
					{
						$this->m["PagesModel"]->aWhere(array(
							"    gte"	=>	array(
								"tabella_listini.prezzo_prodotto"	=>	sanitizeDb($fasciaPrezzo["fasce_prezzo"]["da"]),
							),
							"     lt"	=>	array(
								"tabella_listini.prezzo_prodotto"	=>	sanitizeDb($fasciaPrezzo["fasce_prezzo"]["a"]),
							),
						));
					}
				}
				else if ($tipoFiltro == AltriFiltri::$altriFiltriTipi["stato-prodotto"])
					$this->addStatoWhereClause(AltriFiltri::$aliasValoreTipoInEvidenza[0]);
				else if ($tipoFiltro == AltriFiltri::$altriFiltriTipi["stato-prodotto-nuovo"])
					$this->addStatoWhereClause(AltriFiltri::$aliasValoreTipoNuovo[0]);
				else if ($tipoFiltro == AltriFiltri::$altriFiltriTipi["stato-prodotto-promo"])
					$this->addStatoWhereClause(AltriFiltri::$aliasValoreTipoPromo[0]);
			}
		}
		
		// Visibilità pagina
		if (v("abilita_visibilita_pagine"))
		{
			$this->m["PagesModel"]->addWhereLingua();
		}
		
		if (strcmp($this->viewArgs["search"],"") !== 0)
		{
			$this->m["PagesModel"]->aWhere(array(
				" OR"=> array(
					"lk" => array('pages.title' => $this->viewArgs["search"]),
					" lk" => array('pages.codice' => $this->viewArgs["search"]),
					"  lk" =>  array('contenuti_tradotti.title' => $this->viewArgs["search"]),
				),
			));
		}
		
		$rowNumber = $data["rowNumber"] = $this->m["PagesModel"]->addJoinTraduzionePagina()->save()->rowNumber();
		
// 		echo $this->m["PagesModel"]->getQuery();die();
		
		// Estraggo gli id delle pagine trovate
		if (v("attiva_filtri_successivi"))
			CategoriesModel::$arrayIdsPagineFiltrate = $this->m["PagesModel"]->select("distinct pages.codice_alfa,pages.id_page")->toList("pages.id_page")->send();
		
		$this->estraiDatiFiltri();
		
		$this->m["PagesModel"]->clear()->restore(true);
		
		$data["linkAltri"] = null;
		
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
		
// 		echo $this->m["PagesModel"]->getQuery();
		
		// Estraggo le fasce
		$data["fasce"] = $this->m["ContenutiModel"]->elaboraContenuti(0, $clean['id'], $this);
		
		// Estraggo le fasce di prezzo
		if (v("mostra_fasce_prezzo"))
			$data["fascePrezzo"] = $this->m["FasceprezzoModel"]->clear()->addJoinTraduzione()->orderBy("fasce_prezzo.da")->send();
		
		// Estraggo i materiali
		if (v("estrai_materiali"))
			$data["elencoMateriali"] = $this->m["CaratteristichevaloriModel"]->clear()->addJoinTraduzione(null, "caratteristiche_valori_tradotte")->inner(array("caratteristica"))->orderBy("caratteristiche_valori.id_order")->aWhere(array(
				"caratteristiche.tipo" => "MATERIALE",
			))->send();
		
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
	
	protected function addStatoWhereClause($tipo = "in-promozione")
	{
		$wherePromo = array();
		
		switch ($tipo)
		{
			case AltriFiltri::$aliasValoreTipoPromo[0]:
				$nowDate = date("Y-m-d");
				$wherePromo = array(
					"gte"	=>	array("n!datediff('$nowDate',pages.dal)" => 0),
					" gte"	=>	array("n!datediff(pages.al,'$nowDate')" => 0),
					"pages.in_promozione" => "Y",
				);
				
				self::$isPromo = true;
				break;
				
			case AltriFiltri::$aliasValoreTipoNuovo[0]:
				$wherePromo = array(
					"pages.nuovo" => "Y",
				);
				
				break;
				
			case AltriFiltri::$aliasValoreTipoInEvidenza[0]:
				$wherePromo = array(
					"pages.in_evidenza" => "Y",
				);
				
				break;
		}
		
		if (!empty($wherePromo))
			$this->m["PagesModel"]->aWhere($wherePromo);
	}
	
	protected function addOrderByClause($firstSection, $urlOrdinamento = null)
	{
		if ($firstSection == Parametri::$nomeSezioneProdotti)
			$this->m["PagesModel"]->orderBy($this->gerOrderByProdotti($this->viewArgs['o']));
		else
			$this->m["PagesModel"]->orderBy($this->gerOrderBy($firstSection));
		
		if (!$urlOrdinamento)
			$data["url_ordinamento"] = $this->baseUrl."/".$this->getCurrentUrl(false);
		else
			$data["url_ordinamento"] = $this->baseUrl."/".$urlOrdinamento;
		
		if ($firstSection == Parametri::$nomeSezioneProdotti)
		{
			if (User::$nazione)
				$tabellaCombinazioni = "(select id_page,min(coalesce(combinazioni_listini.price,combinazioni.price)) as prezzo_minimo from combinazioni left join combinazioni_listini on combinazioni_listini.id_c = combinazioni.id_c and combinazioni_listini.nazione = '".sanitizeAll(User::$nazione)."' group by combinazioni.id_page) as combinazioni_minime";
			else
				$tabellaCombinazioni = "(select id_page,min(price) as prezzo_minimo from combinazioni group by combinazioni.id_page) as combinazioni_minime";
			
			$this->m["PagesModel"]->inner($tabellaCombinazioni)->on("pages.id_page = combinazioni_minime.id_page");
		}
		
		$this->append($data);
	}
	
	protected function gerOrderByProdotti($string)
	{
		switch($string)
		{
			case "tutti":
				return "pages.id_order";
			case "crescente":
				return "combinazioni_minime.prezzo_minimo,pages.id_order";
			case "decrescente":
				return "combinazioni_minime.prezzo_minimo desc,pages.id_order";
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
			case "eventi":
				return "pages.data_inizio_evento desc, pages.ora_inizio_evento, pages.data_fine_evento desc, pages.ora_fine_evento, pages.data_news desc";
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
		
		if (isset($viewFile) and file_exists(tpf("/Contenuti/$viewFile.php")))
		{
			$this->cload($viewFile);
		}
		else
		{
			if ($type === "category")
			{
				$this->cload("category");
			}
			else
			{
				$this->cload("page");
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
	
	protected function marchio($id)
	{
		$linguaPrincipale = LingueModel::getPrincipale();
		
		$marchiAlias = $this->m["MarchiModel"]->clear()->select("contenuti_tradotti.lingua,contenuti_tradotti.alias")->left("contenuti_tradotti")->on("contenuti_tradotti.id_marchio = marchi.id_marchio")->where(array(
			"marchi.id_marchio"	=>	(int)$id,
		))->toList("contenuti_tradotti.lingua", "contenuti_tradotti.alias")->send();
		
		$marchioCorrente = $data["marchioCorrente"] = $this->m["MarchiModel"]->clear()->addJoinTraduzione()->where(array(
			"marchi.id_marchio"	=>	(int)$this->idMarchio,
		))->first();
		
		if (!empty($marchioCorrente))
		{
			$data["arrayLingue"] = array();
			
			foreach (Params::$frontEndLanguages as $l)
			{
				if ($l == $linguaPrincipale || !isset($marchiAlias[$l]))
					$data["arrayLingue"][$l] = $l."/".$marchioCorrente["marchi"]["alias"].".html";
				else
					$data["arrayLingue"][$l] = $l."/".$marchiAlias[$l].".html";
			}
			
			$data["meta_description"] = F::meta(mfield($marchioCorrente, "meta_description"));
			$data["keywords"] = mfield($marchioCorrente, "keywords");
			$data["title"] = mfield($marchioCorrente, "titolo");
			
			$this->append($data);
			
			$this->load("marchio");
		}
		else
			$this->redirect("contenuti/notfound");
	}
	
	protected function page($id)
	{
		$this->m["PagesModel"]->checkBloccato($id);
		
		$clean["realId"] = $data["realId"] = (int)$id;
		
		if (v("attiva_formn_contatti"))
			$this->inviaMailFormContatti($clean["realId"]);
		
		$this->checkPage($clean["realId"]);
		
		$clean['id'] = $this->m['PagesModel']->getPrincipale($clean["realId"]);
		
// 		$urlAlias = $this->m["PagesModel"]->getUrlAlias($clean['realId']);
		
		$data["arrayLingue"] = array();
		
		foreach (Params::$frontEndLanguages as $l)
		{
			if (v("abilita_visibilita_pagine"))
			{
				$linguePagina = $this->m["PagesModel"]->getArrayLingueAttiveFrontend($clean['realId']);
				
				if (!in_array($l, $linguePagina))
					continue;
			}
			
			$data["arrayLingue"][$l] = $l."/".$this->m["PagesModel"]->getUrlAlias($clean['realId'], $l);
		}
		
		$data["isPage"] = true;
		
		// Elaborazione feedback
		if (!$this->m['PagesModel']->checkTipoPagina($clean["realId"], "FORM_FEEDBACK"))
			$data["breadcrumb"] = $this->breadcrumbHtml = $this->breadcrumb("page");
		else
			$this->inserisciFeedback($clean["realId"]);
		
		$data["scaglioni"] = $this->scaglioni = $this->m["ScaglioniModel"]->clear()->where(array("id_page"=>$clean['id']))->toList("quantita","sconto")->send();
		
		$data["pages"] = $this->pages = $this->m['PagesModel']->clear()->select("pages.*,categories.*,contenuti_tradotti.*,contenuti_tradotti_categoria.*,marchi.*")
			->inner("categories")->on("categories.id_c = pages.id_c")
			->left("contenuti_tradotti")->on("contenuti_tradotti.id_page = pages.id_page and contenuti_tradotti.lingua = '".sanitizeDb(Params::$lang)."'")
			->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")
			->left("marchi")->on("pages.id_marchio = marchi.id_marchio")
			->where(array("id_page"=>$clean['id']))->send();
		
		if (count($data["pages"]) > 0)
		{
			if ($data["pages"][0]["pages"]["tipo_pagina"] == "COOKIE" && VariabiliModel::checkToken("var_query_string_no_cookie"))
				$data["pages"][0]["pages"]["description"] .= "[scelta-cookie]";
				
			$this->p = $data["pages"][0];
			
			$data["tipoPagina"] = $data["pages"][0]["pages"]["tipo_pagina"];
		}
		
		$data["paginaPrecedente"] = $this->m['PagesModel']->where(array(
			"lt"	=>	array("pages.data_news"	=>	$data['pages'][0]["pages"]["data_news"]),
		))->orderBy("pages.data_news desc")->limit(1)->send();
		
		$data["paginaSuccessiva"] = $this->m['PagesModel']->where(array(
			"gt"	=>	array("pages.data_news"	=>	$data['pages'][0]["pages"]["data_news"]),
		))->orderBy("pages.data_news")->limit(1)->send();
		
// 		print_r($data["pages"]);
		if ($data['pages'][0]["contenuti_tradotti"]["meta_description"])
			$data["meta_description"] = F::meta($data['pages'][0]["contenuti_tradotti"]["meta_description"]);
		else if (strcmp($data['pages'][0]["pages"]["meta_description"],"") !== 0)
			$data["meta_description"] = F::meta($data['pages'][0]["pages"]["meta_description"]);
		
		if ($data['pages'][0]["contenuti_tradotti"]["keywords"])
			$data["keywords"] = F::meta($data['pages'][0]["contenuti_tradotti"]["keywords"]);
		else if (strcmp($data['pages'][0]["pages"]["keywords"],"") !== 0)
			$data["keywords"] = F::meta($data['pages'][0]["pages"]["keywords"]);
		
		list ($colonne, $data["lista_valori_attributi"]) = $this->m['PagesModel']->selectAttributi($clean['id']);
		
		$data["lista_attributi"] = $this->lista_attributi = $colonne;
		
		$this->lista_valori_attributi = $data["lista_valori_attributi"];
		
		$data["haVarianti"] = count($data["lista_valori_attributi"]) > 0 ? true : false;
		
		$data["prezzoMinimo"] = $this->prezzoMinimo = $this->m['PagesModel']->prezzoMinimo($clean['id']);
		
		$data["prodotti_correlati"] = $this->m['PagesModel']->clear()->select("pages.*,prodotti_correlati.id_corr,categories.*,contenuti_tradotti.*,contenuti_tradotti_categoria.*")->from("prodotti_correlati")->inner("pages")->on("pages.id_page=prodotti_correlati.id_corr")
			->addJoinTraduzionePagina()
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
		
		// Pagine correlate
		$data["pagine_correlate"] = $this->m['PagesModel']->clear()->select("pages.*,pages_pages.id_corr,categories.*,contenuti_tradotti.*,contenuti_tradotti_categoria.*")->from("pages_pages")->inner("pages")->on("pages.id_page=pages_pages.id_corr")
			->addJoinTraduzionePagina()
			->where(array(
				"pages_pages.id_page"=>	$clean['id'],
				"attivo"	=>	"Y",
			))->orderBy("pages_pages.section,pages_pages.id_order")->send();
		
		$data["altreImmagini"] = array();
		
		if (count($data["pages"]) > 0)
			$data["altreImmagini"] = $this->m["ImmaginiModel"]->clear()->where(array("id_page" => $clean['id']))->orderBy("id_order")->send(false);
		
		$this->altreImmagini = $data["altreImmagini"];
		
		$orderByCaratteristiche = v("caratteristiche_in_tab_separate") ? "tipologie_caratteristiche.id_order, pages_caratteristiche_valori.id_order" : "pages_caratteristiche_valori.id_order" ;
		
		// CARATTERISTICHE
		$data["caratteristiche"] = $data["lista_caratteristiche"] = $this->m["PagesModel"]->selectCaratteristiche($clean['id']);
		
		$data["lista_caratteristiche_tipologie"] = array();
		
		if (v("caratteristiche_in_tab_separate"))
		{
			foreach ($data["caratteristiche"] as $car)
			{
				if (isset($data["lista_caratteristiche_tipologie"][$car["tipologie_caratteristiche"]["id_tipologia_caratteristica"]]))
					$data["lista_caratteristiche_tipologie"][$car["tipologie_caratteristiche"]["id_tipologia_caratteristica"]][] = $car;
				else
					$data["lista_caratteristiche_tipologie"][$car["tipologie_caratteristiche"]["id_tipologia_caratteristica"]] = array($car);
			}
		}
		
		$data["haPersonalizzazioni"] = false;
		
		// Personalizzazioni
		if (v("attiva_personalizzazioni"))
		{
			$data["personalizzazioni"] = $this->m['PagesModel']->selectPersonalizzazioni($clean['id']);
			
			if (count($data["personalizzazioni"]) > 0)
				$data["haPersonalizzazioni"] = true;
		}
		
		$data["documenti"] = $this->documentiPagina = $this->m["PagesModel"]->getDocumenti($clean['id']);
		
		if (v("mostra_link_in_blog"))
		{
			$this->model("PageslinkModel");
			$data["links"] = $this->m["PageslinkModel"]->clear()->where(array(
				"id_page"	=>	$clean['id'],
			))->orderBy("titolo")->send();
		}
		
		$firstSection = $data["fsection"] = $this->m["PagesModel"]->section($clean['id'], true);
		
		if ($firstSection == "prodotti")
		{
			if (v("abilita_rich_snippet"))
				$data["richSnippet"] = json_encode(PagesModel::getRichSnippet((int)$id), JSON_UNESCAPED_SLASHES);
			
			$data["isProdotto"] = true;
		}
		
		if (v("mostra_tendina_prodotto_principale"))
			$data["tagCanonical"] = PagesModel::getTagCanonical((int)$id);
		
		//estrai i dati della categoria
		$r = $this->m['CategoriesModel']->clear()->select("categories.*,contenuti_tradotti_categoria.*")->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")->where(array("section"=>sanitizeAll($firstSection)))->send();
		$data["datiCategoriaPrincipale"] = $r[0];
		
		$data["pagesCss"] = $data['pages'][0]["pages"]["css"];
		
		// Estraggo le fasce
		$data["fasce"] = $this->m["ContenutiModel"]->elaboraContenuti($clean['id'], 0, $this);
		
		// Estraggo i contenuti generici
		$data["contenuti"] = ContenutiModel::getContenutiPagina($clean['id'], "GENERICO");
		
		$data["contenuti_tab"] = array();
		
		foreach ($data["contenuti"] as $cont)
		{
			if (isset($data["contenuti_tab"][$cont["tipi_contenuto"]["titolo"]]))
				$data["contenuti_tab"][$cont["tipi_contenuto"]["titolo"]][] = $cont;
			else
				$data["contenuti_tab"][$cont["tipi_contenuto"]["titolo"]] = array($cont);
		}
		
		// Estraggo i marker
		$data["marker"] = ContenutiModel::getContenutiPagina($clean['id'], "MARKER");
		
		if (v("usa_marchi"))
		{
			$marchioCorrente =  $data["marchioCorrente"] = $this->m["MarchiModel"]->clear()->addJoinTraduzione()->where(array(
				"marchi.id_marchio"	=>	(int)$this->idMarchio,
			))->first();
			
			if (count($marchioCorrente) > 0)
				$data["aliasMarchioCorrente"] = mfield($marchioCorrente, "alias")."/";
		}
		
		$data["page_tags"] = array();
		
		if (v("usa_tag"))
		{
			$this->model("PagestagModel");
			$data["page_tags"] = $this->m["PagestagModel"]->clear()->select("*")->inner(array("tag"))->where(array(
				"id_page"	=>	$clean['id'],
			))->orderBy("pages_tag.id_order")->send();
		}
		
		$data["page_feedback"] = array();
		
		if (v("abilita_feedback"))
		{
			$data["page_feedback"] = $this->m["FeedbackModel"]->clear()->where(array(
				"id_page"	=>	$clean['id'],
// 				"is_admin"	=>	0,
				"attivo"	=>	1,
			))->orderBy("feedback.id_order")->send();
		}
		
		if (v("attiva_localizzazione_prodotto"))
			list($data["nazioni_prodotto"], $data["regioni_prodotto"]) = $this->m["PagesModel"]->getLocalizzazione($clean['id']);
		
// 		print_r
		
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
				
				require_once(ROOT."/admin/External/libs/vendor/autoload.php");

				ob_start();
				include(tpf("Contenuti/pdf.php"));
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
			$prezzoCombinazione = $res[0]["combinazioni"]["price"];
			
			if (User::$nazione)
				$prezzoCombinazione = $this->m["CombinazioniModel"]->getPrezzoListino($res[0]["combinazioni"]["id_c"], User::$nazione, $prezzoCombinazione);
			
			if (inPromozioneTot($clean["id_page"]))
				$prezzoPieno = setPriceReverse(calcolaPrezzoIvato($clean["id_page"], $prezzoCombinazione));
			
			$qty = (int)$res[0]["combinazioni"]["giacenza"];
			
			if ($qty < 0)
				$qty = 0;
			
			echo '<span class="id_combinazione">'.$res[0]["combinazioni"]["id_c"].'</span><span class="codice_combinazione">'.$res[0]["combinazioni"]["codice"].'</span><span class="prezzo_combinazione">'.setPriceReverse(calcolaPrezzoFinale($clean["id_page"], $prezzoCombinazione)).'</span><span class="immagine_combinazione">'.$res[0]["combinazioni"]["immagine"].'</span><span class="prezzo_pieno_combinazione">'.$prezzoPieno.'</span><span class="giacenza_combinazione">'.$qty.'</span><span class="peso_combinazione">'.setPriceReverse($res[0]["combinazioni"]["peso"]).'</span>';
		}
		else
		{
			echo "KO";
		}
	}
	
	public function search()
	{
		$clean["s"] = $data["s"] = $this->request->get("s","","sanitizeAll");
		
		$data["title"] = Parametri::$nomeNegozio . " - ".gtext("Cerca");
		
		$argKeys = array(
			'p:forceNat'	=>	1,
			's:sanitizeAll'	=>	"",
			'sec:sanitizeAll'	=>	Parametri::$nomeSezioneProdotti,
			'o:sanitizeAll'	=>	"tutti",
		);

		$this->setArgKeys($argKeys);
		$this->shift(count($this->pageArgs));
		
		//load the Pages helper
		$this->helper('Pages','risultati-ricerca','p');
		
		$data["pages"] = array();
		
		if (strcmp($this->viewArgs["s"],"") !== 0 && ($this->viewArgs["sec"] == "tutti" || CategoriesModel::checkSection($this->viewArgs["sec"])))
		{
			if ($this->viewArgs["sec"] == Parametri::$nomeSezioneProdotti)
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
				"attivo" => "Y",
				"principale" => "Y",
				"acquistabile"	=>	"Y",
			);
			
			$this->m['PagesModel']->clear()->where($where);
			
			if ($this->viewArgs["sec"] != "tutti")
				$this->m["PagesModel"]->aWhere(array(
					"in" => array("-id_c" => $childrenProdotti),
				));
			
// 			$data["pages"] = $this->m['PagesModel']->clear()->where($where);
			
			if (Parametri::$hideNotAllowedNodesInLists)
			{
				$accWhere = $this->m["PagesModel"]->getAccessibilityWhere();
				$this->m["PagesModel"]->aWhere($accWhere);
			}
			
			$this->addOrderByClause($this->viewArgs["sec"], 'risultati-ricerca');
			
			$rowNumber = $data["rowNumber"] = $this->m['PagesModel']->addJoinTraduzionePagina()->rowNumber();
			
			$this->elementsPerPage = 999999;
			
			if ($rowNumber > $this->elementsPerPage)
			{
				$page = $this->viewArgs['p'];
				
				$this->m['PagesModel']->limit = $this->h['Pages']->getLimit($page,$rowNumber,$this->elementsPerPage);
				
				$data['pageList'] = $this->h['Pages']->render($page-5,11);
			}
			
			$data["pages"] = $this->m['PagesModel']->send();
// 			echo $this->m['PagesModel']->getQuery();die();
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
		$data["title"] = Parametri::$nomeNegozio . " - Promozioni";
		
		$this->cleanAlias = "prodotti-in-promozione";
		
		self::$isPromo = $data["isPromo"] = true;
		
		$this->category($this->idShop);
		
		foreach (Params::$frontEndLanguages as $l)
			$data["arrayLingue"][$l] = $l."/prodotti-in-promozione.html";
		
		$data["breadcrumb"] = $this->breadcrumbHtml = gtext("promozioni");
		
		if (isset($data))
			$this->append($data);
		
		if (v("vista_promozioni_separata"))
		{
			$this->clean();
			
			if (Output::$html)
				$this->loadHeaderFooter("promozione");
			else
				$this->load("api_output");
		}
	}
	
	public function sitemap()
	{
		header ("Content-Type:text/xml");
		
		$this->clean();
		
		$data["nodi"] = SitemapModel::getNodiFrontend();
		
		$this->append($data);
		$this->load('sitemap');
	}
	
	public function robots()
	{
		header ("Content-Type:text/plain");
		
		$this->clean();
		
		$this->load('robots');
	}
	
	public function jsoncategorie($section = "")
	{
		$this->clean();
		
		if (!$section)
			$section = Parametri::$nomeSezioneProdotti;
		
		$idShopCat = (int)$this->m['CategoriesModel']->clear()->where(array(
			"section"	=>	$section,
		))->field("id_c");
		
		$res = $this->m["CategoriesModel"]->recursiveTree($idShopCat,2);
// 		$res = json_encode($res);
		
		Output::$json = true;
		Output::setBodyValue("Type", "Menu");
		Output::setBodyValue("Categories", $res);
		
		$this->load("api_output");
	}
	
	public function jsoncategoriefiglie($idCat)
	{
		$this->clean();
		
		$c = new CategorieModel();
		
		$arrayFigli = $c->categorieFiglieSelect($idCat);
		
		Output::$json = true;
		Output::setBodyValue("Type", "Menu");
		Output::setBodyValue("Data", $arrayFigli);
		
		$this->load("api_output");
	}
	
	public function documento($id)
	{
		$this->clean();
		
		$this->m["DocumentiModel"]->clear()->select("distinct documenti.id_doc,documenti.*")->where(array(
			"id_doc"	=>	(int)$id,
		));
		
		if (v("attiva_gruppi_documenti"))
			$this->m["DocumentiModel"]->left(array("gruppi"))->sWhere("(reggroups.name is null OR reggroups.name in ('".implode("','", User::$groups)."'))");
		
		$documento = $this->m["DocumentiModel"]->record();
// 		$documento = $this->m["DocumentiModel"]->selectId((int)$id);
		
		if (!empty($documento))
		{
			$path = ROOT."/images/documenti/".$documento['filename'];
				
			if (file_exists($path))
			{
				$extArray = explode('.', $documento['filename']);
				$ext = strtolower(end($extArray));
			
				$contentDisposition = ($ext == "pdf" || $ext == "png" || $ext == "jpg" || $ext == "jpeg") ? "inline" : "attachment";
				
				//get the MIME type of the file
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$MIMEtype = finfo_file($finfo, $path);
				$contentType = $MIMEtype;
				finfo_close($finfo);
				
				header('Content-disposition: '.$contentDisposition.'; filename='.$documento['clean_filename']);
				header('Content-Type: '.$contentType);
				readfile($path);
			}
		}
		else
			$this->redirect("");
	}
	
	public function processaschedulazione($c = "")
	{
		$this->clean();
		
		if (is_string($c) && trim(v("token_schedulazione")) && $c == v("token_schedulazione"))
		{
			Files_Log::$logFolder = LIBRARY."/Logs";
			$log = Files_Log::getInstance("retargeting");
			
			$debug = false;
			
			if (VariabiliModel::checkToken("debug_retargeting_get_variable"))
				$debug = true;
			
			if ($debug)
				EventiretargetingModel::setDebug();
			
			if (!$debug)
				$log->writeString("INIZIO LOG RETARGETING");
			
			EventiretargetingModel::processa();
			
			if (!$debug)
				$log->writeString("FINE LOG RETARGETING");
			
			if ($debug)
			{
				$html = EventiretargetingModel::printDebugResult();
				
				if (v("email_debug_retargeting") && count(EventiretargetingModel::$debugResult) > 0)
					MailordiniModel::inviaMailLog("Debut Retargeting", $html, "RETARGETING DEBUG", "email_debug_retargeting");
				
				echo $html;
			}
			else
				echo "Processazione schedulazione avvenuta";
		}
	}
	
	public function accettacookie()
	{
		if (VariabiliModel::checkToken("var_query_string_no_cookie"))
		{
			$time = time() + 3600*24*365*10;
			Cookie::set("ok_cookie", "OK", $time, "/");
			
			if (isset($_GET["all_cookie"]))
				Cookie::set("ok_cookie_terzi", "OK", $time, "/");
		}
		
		$this->redirect("");
	}
	
	public function confermacontatto($token = 0)
	{
		$this->clean();
		
		$clean["token"] = sanitizeAll($token);
		
		if (!trim((string)$clean["token"]) || !v("attiva_verifica_contatti"))
			$this->redirect("");
		
		$limit = time() - (int)v("tempo_conferma_uid_contatto");
		
		$contatto = $this->m["ContattiModel"]->clear()->where(array(
			"uid_contatto"	=> $clean["token"],
			"gt"	=>	array(
				"time_conferma"	=>	(int)$limit,
			),
		))->record();
		
		if (!empty($contatto))
		{
			$this->m["ContattiModel"]->settaCookie($clean["token"]);
			
			$this->redirectDopoConfermaContatto($clean["token"]);
		}
		else
		{
			$idPaginaConfermaScaduta = PagineModel::gTipoPagina("CONF_CONT_SCADUTO");
			
			if ($idPaginaConfermaScaduta)
				$this->redirect(getUrlAlias($idPaginaConfermaScaduta).F::partial());
			else
				$this->redirect("".F::partial());
		}
	}
	
	public function redirectDopoConfermaContatto($token)
	{
		return;
	}
}
