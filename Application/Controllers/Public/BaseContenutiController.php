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
	public $catSWhereCerca = "";
	
	public $titleTag = "";
	public $titleMarchio = "";
	
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
		
// 		print_r($this->pageArgs);
		
		if( count($this->pageArgs) > 0 && strcmp($this->pageArgs[count($this->pageArgs)-1],"") === 0)
			array_pop($this->pageArgs);
		
		// Recupera i filtri dall'URL
		$this->getFiltriDaUrl();
		
		$titleTag = $titleMarchio = "";
		
		$data = array();
		
		if (v("usa_tag"))
		{
			$elencoTag = $this->m("TagModel")->clear()->addJoinTraduzione()->send();
			$elencoTagEncoded = array();
			$elencoTagTitle = array();
			foreach ($elencoTag as $tag)
			{
				$elencoTagEncoded[tagfield($tag,"alias")] = $tag["tag"]["id_tag"];
				$elencoTagTitle[tagfield($tag,"alias")] = tagfield($tag,"titolo");
			}
			
			if (count($this->pageArgs) > 0 && isset($elencoTagEncoded[$this->pageArgs[0]]))
			{
				$this->idTag = TagModel::$currentId = $elencoTagEncoded[$this->pageArgs[0]];
				$titleTag = $this->titleTag = $elencoTagTitle[$this->pageArgs[0]];
				$this->aliasTag = $data["aliasTagCorrente"] = $this->pageArgs[0];
				array_shift($this->pageArgs);
			}
		}
		
		if (v("usa_marchi"))
		{
			$elencoMarchi = $this->m("MarchiModel")->clear()->addJoinTraduzione()->send();
			$elencoMarchiEncoded = array();
			$elencoMarchiTitle = array();
			foreach ($elencoMarchi as $marchio)
			{
				$elencoMarchiEncoded[mfield($marchio,"alias")] = $marchio["marchi"]["id_marchio"];
				$elencoMarchiTitle[mfield($marchio,"alias")] = mfield($marchio,"titolo");
			}
			
			// Marchio prima della categoria
			if (v("marchio_prima_della_categoria_in_url") && count($this->pageArgs) > 0 && isset($elencoMarchiEncoded[$this->pageArgs[0]]))
			{
				$this->idMarchio = MarchiModel::$currentId = $elencoMarchiEncoded[$this->pageArgs[0]];
				$titleMarchio = $this->titleMarchio = $elencoMarchiTitle[$this->pageArgs[0]];
				$this->aliasMarchio = $data["aliasMarchioCorrente"] = $this->pageArgs[0];
				
				array_shift($this->pageArgs);
			}
			
			// Marchio dopo la categoria
			if (!v("marchio_prima_della_categoria_in_url"))
			{
				$indiceMarchio = 0;
				
				foreach ($this->pageArgs as $pArg)
				{
					if (isset($elencoMarchiEncoded[$pArg]))
					{
						$this->idMarchio = MarchiModel::$currentId = $elencoMarchiEncoded[$pArg];
						$titleMarchio = $this->titleMarchio = $elencoMarchiTitle[$pArg];
						$this->aliasMarchio = $data["aliasMarchioCorrente"] = $pArg;
						
						break;
					}
					
					$indiceMarchio++;
				}
				
				if ($this->idMarchio)
					array_splice($this->pageArgs, $indiceMarchio, 1);
			}
		}
		
		// Controlla che non sia un marchio o un tag
		if (count($this->pageArgs) === 0 && (($this->idMarchio && !v("attiva_pagina_produttore")) || $this->idTag))
		{
			$catProdotti = $this->m('CategoriesModel')->clear()
				->addJoinTraduzioneCategoria()
				->where(array(
					"section"	=>	Parametri::$nomeSezioneProdotti
				))
				->first();
			
			if ($catProdotti)
				$this->pageArgs[] = cfield($catProdotti, "alias");
		}
		
		$args = $this->pageArgs;
		
		$tipoContenuto = "pagina";
		
		if (count($args) > 0)
		{
			//parents array as taken by the URL
			$this->urlParent = $this->pageArgs;
			//remove the last element (content itself)
			array_pop($this->urlParent);
			
			$dirtyAlias = $args[(count($args)-1)];
			$clean['alias'] = $this->cleanAlias = sanitizeAll($dirtyAlias);
			
// 			if ($this->m("PagesModel")->isActiveAlias($clean['alias'], Params::$lang))
			if ($ids = $this->m("PagesModel")->getIdFromAlias($dirtyAlias, Params::$lang))
			{
// 				$ids = $this->m("PagesModel")->getIdFromAlias($clean['alias'], Params::$lang);
				
				$clean["id"] = (int)$ids[0];
				
				$parents = array();
				$rParents = array();
				
				foreach ($ids as $id)
				{
					$par = $this->m("PagesModel")->parents((int)$id,false,false,Params::$lang);
					
					//tolgo la root
					array_shift($par);
					
					$parents[$id] = $par;
					
					//tolgo la pagina
					array_pop($par);
					
					$temp = array();
					
					if (v("mostra_categorie_in_url_prodotto") || !isProdotto($clean["id"]))
					{
						foreach ($par as $p)
						{
							$temp[] = isset(CategoriesModel::$aliases[$p["categories"]["alias"]]) ? CategoriesModel::$aliases[$p["categories"]["alias"]] : $p["categories"]["alias"];
						}
					}
					
					$rParents[$id] = $temp;
				}
				
				if (count($rParents) > 0)
				{
					$idPrincipale = $this->m("PagesModel")->getPrincipale($ids[0]);
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
						
						$data["title"] = $this->getTitlePagina($parents[$id][count($parents[$id])-1]);
					}
				}

				$this->checkIfRigthParents();
				$this->page($clean['id']);
			}
			else if ($clean['id'] = (int)$this->m("CategoriesModel")->getIdFromAlias($dirtyAlias, Params::$lang))
			{
				$tipoContenuto = "categoria";
				
				$parents = $this->m("CategoriesModel")->parents($clean['id'],false,false, Params::$lang);
				array_shift($parents); //remove the root parent
				
// 				if (isset($parents[count($parents)-1]["contenuti_tradotti"]["title"]) && $parents[count($parents)-1]["contenuti_tradotti"]["title"])
// 					$data["title"] = Parametri::$nomeNegozio . " - " . strtolower($parents[count($parents)-1]["contenuti_tradotti"]["title"]);
// 				else
// 					$data["title"] = Parametri::$nomeNegozio . " - " . strtolower($parents[count($parents)-1]["categories"]["title"]);
// 				
// 				if ($this->titleTag && (int)$clean['id'] === (int)$this->idShop)
// 					$data["title"] = Parametri::$nomeNegozio . " - " .$this->titleTag;
// 				
// 				if ($this->titleMarchio)
// 				{
// 					if ((int)$clean['id'] === (int)$this->idShop)
// 						$data["title"] = Parametri::$nomeNegozio . " - " .$this->titleMarchio;
// 					else
// 						$data["title"] .= " - " .$this->titleMarchio;
// 				}
				
				$data["title"] = $this->getTitleCategoria($parents[count($parents)-1], $clean['id']);
				
				$this->fullParents = $parents;

				array_pop($parents); //remove the current element
				
				$this->parents = $parents;
				
				//build the array with the right parents
				foreach ($parents as $p)
				{
					// rimuovi l'alias della sezione prodotti
					if (v("mantieni_alias_sezione_in_url_prodotti") || count($parents) <= 0 || $p["categories"]["section"] != Parametri::$nomeSezioneProdotti)
						$this->rParent[] = isset(CategoriesModel::$aliases[$p["categories"]["alias"]]) ? CategoriesModel::$aliases[$p["categories"]["alias"]] : $p["categories"]["alias"];
				}
				$this->checkIfRigthParents($tipoContenuto);
				$this->category($clean['id']);
			}
			else
			{
				$this->notfound();
// 				$this->redirect("contenuti/notfound");
			}
		}
		else if ($this->idMarchio && v("attiva_pagina_produttore"))
		{
			$this->marchio($this->idMarchio);
		}
		else
		{
			$this->notfound();
// 			$this->redirect("contenuti/notfound");
		}
		
		$data["currUrl"] = $this->getCurrentUrl(true, $tipoContenuto);

		$this->append($data);
	}
	
	// Restituisce il title html della pagina
	// $page contiene anche la traduzione in contenuti_tradotti
	protected function getTitlePagina($page)
	{
		$metaTitlePagina = field($page, "meta_title");
		$titoloPagina = field($page, "title");
		
		$titleDaUsare = trim($metaTitlePagina) ? $metaTitlePagina : $titoloPagina;
		
		return $this->aggiungiNomeNegozioATitle(F::meta($titleDaUsare));
	}
	
	// Restituisce il title html della categoria
	// $categoria contiene anche la traduzione in contenuti_tradotti
	protected function getTitleCategoria($categoria, $idCat = 0)
	{
		if (isset($categoria["contenuti_tradotti"]["title"]) && $categoria["contenuti_tradotti"]["title"])
			$title = $categoria["contenuti_tradotti"]["title"];
		else
			$title = $categoria["categories"]["title"];
		
		$metaTitleCategoria = cfield($categoria, "meta_title", "contenuti_tradotti");
		
		if (trim($metaTitleCategoria))
			$title = $metaTitleCategoria;
		
		if (v("funzione_su_title_categoria"))
			$title = call_user_func(v("funzione_su_title_categoria"),$title);

		if ($this->titleTag && (int)$idCat === (int)$this->idShop)
			$title = $this->titleTag;
		
		if ($this->titleMarchio)
		{
			if ((int)$idCat === (int)$this->idShop)
				$title = $this->titleMarchio;
			else
				$title .= " - " .$this->titleMarchio;
		}
		
		$title = $this->aggiungiNomeNegozioATitle($title);
		
		return $title;
	}
	
	protected function getMetaDescriptionCategoria($categoria)
	{
		if (cfield($categoria, "meta_description"))
			return F::meta(cfield($categoria, "meta_description"));
		
		return "";
	}
	
	public function notfound()
	{
		if (v("attiva_formn_contatti"))
			$this->inviaMailFormContatti(0);
		
		$data["title"] = Parametri::$nomeNegozio . " - pagina non trovata";
		
		if (Output::$html)
		{
			header('HTTP/1.0 404 Not Found');
			
			$notFoundFileName = $this->getPageNotFoundFileName();
			
			if ($this->isNotFoundAndCached())
			{
				$htmlNotFound = file_get_contents(ROOT."/Logs/404/".$notFoundFileName);
				$this->clean();
				echo $htmlNotFound;
			}
			else
			{
				$this->estraiDatiFiltri();
				
				User::$id = 0;
				User::$cart_uid = 0;
				User::$wishlist_uid = 0;
				User::$logged = false;
				$data["islogged"] = false;
				$data["carrello"] = array();
				$data["prodInCart"] = 0;
				$data["prodInWishlist"] = 0;
				$this->append($data);
				$this->clean();
				$this->loadHeaderFooter("404");
				
				ob_start();
				$cache = Cache_Html::getInstance();
				$timer = Factory_Timer::getInstance();
				$this->theme->render($cache, $timer);
				$content = ob_get_clean();
				
				createFolderFull("Logs/404", ROOT);
				App::createLogFolder();
				FilePutContentsAtomic(ROOT."/Logs/404/".$notFoundFileName, $content);
			}
		}
		else
			$this->load("api_output");
	}
	
	public function nonpermesso()
	{
		$data["title"] = Parametri::$nomeNegozio . " - accesso non permesso";
		
		$this->append($data);
		
		$this->load("accesso_non_permesso");
	}
	
	private function checkIfRigthParents($tipo = "pagina")
	{
		if ($this->urlParent !== $this->rParent)
		{
// 			$estensioneTipo = ($tipo == "categoria") ? v("estensione_url_categorie") : ".html";
// 			$ext = Parametri::$useHtmlExtension ? $estensioneTipo : null;
// 			$rightUrl = ltrim(Url::createUrl(array_merge($this->rParent,array($this->cleanAlias)),null,true),"/");
// 			$this->redirect($rightUrl.$ext);
		}
	}
	
	protected function getCurrentUrl($completeUrl = true, $tipo = "pagina")
	{
		$estensioneTipo = ($tipo == "categoria") ? v("estensione_url_categorie") : ".html";
		$ext = Parametri::$useHtmlExtension ? $estensioneTipo : null;
		
		$tempParents = $this->rParent;
		$aliasMarchio = "";
		
		if ($this->idMarchio)
		{
			$t = new MarchiModel();
			$tag = $t->clear()->where(array(
				"id_marchio"	=>	$this->idMarchio,
			))->addJoinTraduzione()->first();
			
			if (!empty($tag))
				$aliasMarchio = mfield($tag,"alias");
		}
		
		// Marchio prima della categoria
		if (v("marchio_prima_della_categoria_in_url") && $aliasMarchio)
			array_unshift($tempParents, mfield($tag,"alias"));
		
		if ($this->idTag)
		{
			$t = new TagModel();
			$tag = $t->clear()->where(array(
				"id_tag"	=>	$this->idTag,
			))->addJoinTraduzione()->first();
			
			if (!empty($tag))
				array_unshift($tempParents, tagfield($tag,"alias"));
		}
		
		$aliasShop = CategoriesModel::getAliasShop();
		
		$arrayUrl = array();
		
		if (!$this->idMarchio || (string)trim(nullToBlank($this->cleanAlias),"/") !== (string)$aliasShop || v("attiva_pagina_produttore"))
			$arrayUrl = array($this->cleanAlias);
		
		// Marchio dopo la categoria
		if (!v("marchio_prima_della_categoria_in_url") && $aliasMarchio)
			$arrayUrl[] = $aliasMarchio;
		
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
			if (count($arrayUrl) > 0)
				return $baseUrl.implode("/",$tempParents)."/".implode("/",$arrayUrl).$ext;
			else
				return $baseUrl.implode("/",$tempParents).$ext;
		}
		else
		{
			return $baseUrl.implode("/",$arrayUrl).$ext;
		}
	}
	
	//create the HTML of the breadcrumb
	protected function breadcrumb($type = "category", $linkInLast = false, $separator = "&raquo;", $fullParents = null)
	{
		$c = new CombinazioniModel();
		
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
		
		if (v("togli_link_categoria_prodotti_in_breadcrumb_in_dettaglio") && count($tempParents) > 0 && PagesModel::$currentIdPage && $this->firstSection == Parametri::$nomeSezioneProdotti)
			array_shift($tempParents);
		
// 		print_r($tempParents);
// 		die();
		$breadcrumbArray = array();
		
		$i = 0;
		while (count($tempParents) > 0)
		{
			// usato nel ciclo interno
			$tempParentsCiclo = $tempParents;
			
			// rimuovi l'alias della sezione prodotti
			if (!v("mantieni_alias_sezione_in_url_prodotti") && count($tempParentsCiclo) > 1 && isset($tempParentsCiclo[0]["categories"]["section"]) && $tempParentsCiclo[0]["categories"]["section"] == Parametri::$nomeSezioneProdotti)
				array_shift($tempParentsCiclo);
			
			$j = 0;
			$hrefArray = array();
			foreach($tempParentsCiclo as $row)
			{
				$table = ($j === (count($tempParentsCiclo)-1) and $type === "page" and $i === 0) ? "pages" : "categories";
				
				if ($i > 0 || v("mostra_categorie_in_url_prodotto") || $table == "pages")
				{
					$aliasAttributiCodice = "";
					
					if ($table == "pages" && FeedbackModel::gIdCombinazione())
						$aliasAttributiCodice = $c->getAlias(0, Params::$lang, FeedbackModel::gIdCombinazione());
					
					$hrefArray[] = (isset($row["contenuti_tradotti"]["alias"]) && $row["contenuti_tradotti"]["alias"]) ? $row["contenuti_tradotti"]["alias"].$aliasAttributiCodice : $row[$table]["alias"].$aliasAttributiCodice;
				}
				
				$j++;
			}
			
			$table = ($i === 0 and $type === "page") ? "pages" : "categories";
			$lClass = $i === 0 ? "breadcrumb_last" : null;
			
			$estensioneTipo = ($table == "categories") ? v("estensione_url_categorie") : ".html";
			$ext = Parametri::$useHtmlExtension ? $estensioneTipo : null;
			
			$ref = implode("/",$hrefArray).$ext;
			
			$breadcrumbLinkClass = v("classe_link_breadcrumb");
			
			if ($i === 0 and !$linkInLast)
			{
				$titolo = (isset($tempParents[count($tempParents)-1]["contenuti_tradotti"][$title]) && $tempParents[count($tempParents)-1]["contenuti_tradotti"][$title]) ? $tempParents[count($tempParents)-1]["contenuti_tradotti"][$title] : $tempParents[count($tempParents)-1][$table][$title];
				
				$titolo = $this->titoloBreadcrumb($titolo, true);
				
				array_unshift($breadcrumbArray, v("breadcrumb_element_open")."<span class='breadcrumb_last_text'>".$titolo."</span>".v("breadcrumb_element_close")."\n");
				
				// Aggiungo il marchio nella breadcrumb
				if (v("link_marchio_in_breadcrumb") && isset($tempParents[count($tempParents)-1]["pages"]["id_marchio"]) && $tempParents[count($tempParents)-1]["pages"]["id_marchio"])
				{
					$marchio = MarchiModel::getDataMarchio($tempParents[count($tempParents)-1]["pages"]["id_marchio"]);
					
					if ($marchio)
					{
						$alias = mfield($marchio,"alias");
						array_unshift($breadcrumbArray, v("breadcrumb_element_open")."<a class='$lClass $breadcrumbLinkClass ".$alias."' href='".$this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio(0, $tempParents[count($tempParents)-1]["pages"]["id_marchio"], $tempParents[count($tempParents)-1]["pages"]["id_c"])."'>".mfield($marchio,"titolo")."</a>".v("breadcrumb_element_close")."\n");
					}
				}
			}
			else
			{
				$alias = (isset($tempParents[count($tempParents)-1]["contenuti_tradotti"]['alias']) && $tempParents[count($tempParents)-1]["contenuti_tradotti"]['alias']) ? $tempParents[count($tempParents)-1]["contenuti_tradotti"]['alias'] : $tempParents[count($tempParents)-1][$table]['alias'];
				
				$titolo = (isset($tempParents[count($tempParents)-1]["contenuti_tradotti"][$title]) && $tempParents[count($tempParents)-1]["contenuti_tradotti"][$title]) ? $tempParents[count($tempParents)-1]["contenuti_tradotti"][$title] : $tempParents[count($tempParents)-1][$table][$title];
				
				$titolo = $this->titoloBreadcrumb($titolo, false);
				
				array_unshift($breadcrumbArray, v("breadcrumb_element_open")."<a class='$lClass $breadcrumbLinkClass ".$alias."' href='".$this->baseUrl."/$ref'>".$titolo."</a>".v("breadcrumb_element_close")."\n");
			}
			
			array_pop($tempParents);
			
			$i++;
		}
		
		App::$currentBreadcrumbArray = $breadcrumbArray;
		App::$currentBreadcrumb = implode(v("divisone_breadcrum"), $breadcrumbArray);
		
		return App::$currentBreadcrumb;
	}
	
	protected function titoloBreadcrumb($titolo)
	{
		return $titolo;
	}
	
	protected function setElementsPerPage($firstSection)
	{
		if ($firstSection == "prodotti")
			$this->elementsPerPage = v("prodotti_per_pagina");
		else if ($firstSection == "blog")
			$this->elementsPerPage = v("news_per_pagina");
		else if ($firstSection == "eventi")
			$this->elementsPerPage = v("eventi_per_pagina");
		else
			$this->elementsPerPage = 9999999;
	}
	
	protected function category($id)
	{
		$this->m("CategoriesModel")->checkBloccato($id);
		
		Cache_Db::addTablesToCache(array("combinazioni","scaglioni"));
		
		$argKeys = array(
			'p:forceNat'	=>	1,
			'o:sanitizeAll'	=>	v("default_ordinamento_prodotti"),
			'search:sanitizeAll'	=>	"",
		);
		
		if (v("attiva_ricerca_documento"))
			$argKeys["searchdoc:sanitizeAll"] = "";
		
		$this->setArgKeys($argKeys);
		$this->shift(count($this->originalPageArgs));
		
		$clean['id'] = $data["id_categoria"] = CategoriesModel::$currentIdCategory = (int)$id;
		
		if (v("attiva_formn_contatti"))
			$this->inviaMailFormContatti(0);
		
		$this->checkCategory($clean["id"]);
		
		$section = $data["section"] = $this->section = $this->m("CategoriesModel")->section($clean['id']);
		$firstSection = $data["fsection"] = $this->firstSection = $this->m("CategoriesModel")->section($clean['id'], true);
		
		$this->setElementsPerPage($firstSection);
		
		$data["elementsPerPage"] = $this->elementsPerPage;
		
		$data["arrayLingue"] = array();
		
		foreach (Params::$frontEndLanguages as $l)
		{
// 			$data["arrayLingue"][$l] = $l."/".$this->m("CategoriesModel")->getUrlAlias($clean['id'], $l);
			$data["arrayLingue"][$l] = $l."/".CategoriesModel::getUrlAliasTagMarchio($this->idTag, $this->idMarchio, $clean['id'],"", array(), array(), array(), $l);
		}
		
		$data["idMarchio"] = $this->idMarchio;
		$data["isCategory"] = true;
		
		$data["aliasMarchioCorrente"] = "";
		
		if (v("usa_marchi"))
		{
			$marchioCorrente =  $data["marchioCorrente"] = $this->m("MarchiModel")->clear()->addJoinTraduzione()->where(array(
				"marchi.id_marchio"	=>	(int)$this->idMarchio,
			))->first();
			
			if (count($marchioCorrente) > 0)
				$data["aliasMarchioCorrente"] = mfield($marchioCorrente, "alias")."/";
		}
		
		$data["idTag"] = $this->idTag;
		
		if (v("usa_tag"))
		{
			$tagCorrente =  $data["tagCorrente"] = $this->m("TagModel")->clear()->addJoinTraduzione()->where(array(
				"tag.id_tag"	=>	(int)$this->idTag,
			))->first();
			
			if (count($tagCorrente) > 0)
				$data["aliasTagCorrente"] = tagfield($tagCorrente, "alias")."/";
		}
		
		//estrai i dati della categoria
		$r = $this->m('CategoriesModel')->clear()->select("categories.*,contenuti_tradotti_categoria.*")->addJoinTraduzioneCategoria()->where(array("id_c"=>$clean['id']))->send();
		$data["datiCategoria"] = CategoriesModel::$currentCategoryData = $r[0];
		
		$this->m('CategoriesModel')->checkAndInCaseRedirect($data["datiCategoria"]);
		
		$cache = Cache_Html::getInstance();
		
		if (!User::$adminLogged && empty(AltriFiltri::$filtriUrl) && !isset($_GET["search"]) && $this->checkCacheHtmlAttiva())
			$cache->saveHtml = true;
		
		if (v("estrai_categorie_figlie"))
			$data["categorieFiglie"] = $this->m('CategoriesModel')->clear()->addJoinTraduzioneCategoria()->where(array("id_p"=>$clean['id']))->orderBy("categories.lft")->send();
		
		$template = strcmp($r[0]["categories"]["template"],"") === 0 ? null : $r[0]["categories"]["template"];
		
		$metaDescriptionCategoria = $this->getMetaDescriptionCategoria($r[0]);
		
		if ($metaDescriptionCategoria)
			$data["meta_description"] = $metaDescriptionCategoria;
		
		if (cfield($r[0], "keywords"))
			$data["keywords"] = F::meta(cfield($r[0], "keywords"));
		
		if (isset($tagCorrente) && !empty($tagCorrente) && (int)$id === $this->idShop)
		{
			$data["keywords"] = tagfield($tagCorrente, "keywords");
			$data["meta_description"] = F::meta(tagfield($tagCorrente, "meta_description"));
		}
		
		$data["breadcrumb"] = $this->breadcrumbHtml = $this->breadcrumb();
		
		if (v("mostra_categorie_figlie_in_griglia_prodotti"))
		{
			$iChildrenGross = $this->m("CategoriesModel")->immediateChildren($clean['id']);
			
			$data["iChildren"] = array();
			foreach ($iChildrenGross as $row)
			{
				if ($this->m("CategoriesModel")->hasActivePages($row["categories"]["id_c"]))
				{
					$data["iChildren"][] = $row;
				}
			}
		}
		
		// Estraggo gli id delle pagine trovate
		if ($firstSection == "prodotti" && v("attiva_filtri_successivi"))
		{
			$arrayElementi = array("[categoria]", "[nazione]", "[regione]", "[evidenza]", "[nuovo]", "[promozione]");
			
			if (v("usa_marchi"))
				$arrayElementi[] = "[marchio]";
			
			if (v("usa_tag"))
				$arrayElementi[] = "[tag]";
			
			$idCatFiltri = v("attiva_filtri_caratteristiche_separati_per_categoria") ? CategoriesModel::$currentIdCategory : 0;
			
			$arrayElementi = array_merge($arrayElementi, CaratteristicheModel::getAliasFiltri($idCatFiltri));
			
			foreach ($arrayElementi as $elemento)
			{
				$this->queryElencoProdotti($clean['id'], $firstSection, array($elemento), false);
				$ids = $this->m("PagesModel")->select("pages.id_page")->toList("pages.id_page")->send();
				CategoriesModel::$arrayIdsPagineFiltrate[$elemento] = array_unique($ids);
				
// 				if ($elemento == "[categoria]")
// 					CategoriesModel::$arrayIdsPagineFiltrate["[categoria][query]"] = $this->m("PagesModel")->select("pages.id_page")->queryStruct();
			}
			
			if (v("filtro_prezzo_slider") && $firstSection == "prodotti")
			{
				$this->queryElencoProdotti($clean['id'], $firstSection, array("prezzo"));
				$data["prezzoMinimoElenco"] = (float)$this->m("PagesModel")
					->select("combinazioni_minime.prezzo_minimo_ivato")
					->orderBy("combinazioni_minime.prezzo_minimo_ivato")
					->limit(1)
					->field("combinazioni_minime.prezzo_minimo_ivato");
				$data["prezzoMassimoElenco"] = (float)$this->m("PagesModel")
					->select("combinazioni_minime.prezzo_minimo_ivato")
					->orderBy("combinazioni_minime.prezzo_minimo_ivato desc")
					->limit(1)
					->field("combinazioni_minime.prezzo_minimo_ivato");
			}
		}
		
		$this->queryElencoProdotti($clean['id'], $firstSection);
		
		$rowNumber = $data["rowNumber"] = $this->m("PagesModel")->save()->rowNumber();
		
// 		echo $this->m("PagesModel")->getQuery();die();
		
		$this->estraiDatiFiltri();
		
		if (self::$isPromo)
			$cache->saveHtml = false;
		
		$this->m("PagesModel")->clear()->restore(true);
		
		$data["linkAltri"] = null;
		
		if ($rowNumber > $this->elementsPerPage)
		{
			//load the Pages helper
			$this->helper('Pages',$this->getCurrentUrl(false, "categoria"),'p');
			
			$page = $data["numeroDiPagina"] = $this->viewArgs['p'];
			
			$this->m('PagesModel')->limit = $this->h['Pages']->getLimit($page,$rowNumber,$this->elementsPerPage);
			
			$data["numeroDiPagine"] = $this->h['Pages']->getNumbOfPages();
			
			if ($firstSection == "prodotti" && v("usa_sotto_query_in_elenco"))
				$this->m('PagesModel')->select(PagesModel::getSelectDistinct(""))->toList("pages.id_page");
			
			$data["pages"] = $this->m('PagesModel')->send();
			
			$data['pageList'] = $this->h['Pages']->render($page-5,11);
		}
		else
		{
			if ($firstSection == "prodotti" && v("usa_sotto_query_in_elenco"))
				$this->m('PagesModel')->select(PagesModel::getSelectDistinct(""))->toList("pages.id_page");
				
			$data["pages"] = $this->m('PagesModel')->send();
		}
		
		// Imposto le pagine trovate
		if ($firstSection == "prodotti" && v("usa_sotto_query_in_elenco"))
			PagesModel::$currentIdPages = $data["pages"];
		
// 		echo $this->m("PagesModel")->getQuery();die();
		
		// Uso sottoquery
		if ($firstSection == "prodotti" && v("usa_sotto_query_in_elenco"))
			$data["pages"] = PagesModel::getPageDetailsList($data["pages"]);
		
		if ($firstSection == "prodotti")
			$data["pages"] = PagesModel::impostaDatiCombinazionePagine($data["pages"]);
		
		$this->pages = $data["pages"];
		
		PagesModel::preloadPages($data["pages"]);
		
		PagesModel::setPagesStruct($data["pages"]);
		
		// Estraggo le fasce
		if ($firstSection != "prodotti" || v("estrai_fasce_in_categoria_prodotti"))
			$data["fasce"] = $this->m("ContenutiModel")->elaboraContenuti(0, $clean['id'], $this);
		
		// Estraggo le fasce di prezzo
		if (v("mostra_fasce_prezzo"))
			$data["fascePrezzo"] = $this->m("FasceprezzoModel")->filtroFasce();
		
		// Estraggo i materiali
		if (v("estrai_materiali"))
			$data["elencoMateriali"] = $this->m("CaratteristichevaloriModel")->clear()->addJoinTraduzione(null, "caratteristiche_valori_tradotte")->inner(array("caratteristica"))->orderBy("caratteristiche_valori.id_order")->aWhere(array(
				"caratteristiche.tipo" => "MATERIALE",
			))->send();
		
		$data["tagCanonical"] = '<link rel="canonical" href="'.Url::getRoot().CategoriesModel::getUrlAliasTagMarchio($this->idTag, $this->idMarchio, $clean['id']).'" />';
		
		$this->append($data);
		
		if (Output::$html)
			$this->sectionLoad($section, "category", $template);
		else
			$this->load("api_output");
	}
	
	// Aggiungo la parte di JOIN con documenti e il cerca per titolo documento
	protected function getSearchWhereDoc()
	{
		$this->m("PagesModel")->inner(array("documenti"))->addJoinTraduzione(null, "contenuti_tradotti_documenti", false, new DocumentiModel());
		
		if (Params::$lang == Params::$defaultFrontEndLanguage)
			$this->m("PagesModel")->sWhere(array("(documenti.titolo like ? or documenti.clean_filename like ?)", array("%".addBackSlashLike($this->viewArgs["searchdoc"])."%", "%".addBackSlashLike($this->viewArgs["searchdoc"])."%")));
		else
			$this->m("PagesModel")->sWhere(array("(contenuti_tradotti_documenti.titolo like ? or documenti.clean_filename like ?)", array("%".addBackSlashLike($this->viewArgs["searchdoc"])."%", "%".addBackSlashLike($this->viewArgs["searchdoc"])."%")));
	}
	
	protected function getSearchWhere($argName = "s")
	{
		Cache_Db::$skipWritingCache = true;
		
		$orWhere = array();
		
		$idPages = $this->m("CombinazioniModel")->clear()->select("combinazioni.id_page")
			->inner(array("pagina"))
			->where(array(
				"combinazioni.codice"		=>	$this->viewArgs[$argName],
				"combinazioni.acquistabile"	=>	1,
			))
			->addWhereAttivo()
			->toList("combinazioni.id_page")
			->send();
		
		$idPages = array_unique($idPages);
		
		if (count($idPages))
			$orWhere = array(
				"  in"	=>	array(
					"pages.id_page"	=>	forceIntDeep($idPages),
				),
			);
		
		if (Params::$lang == Params::$defaultFrontEndLanguage)
		{
			if (v("mostra_filtro_ricerca_libera_in_magazzino"))
			{
				$search = RicerchesinonimiModel::g()->estraiTerminiDaStringaDiRicerca($this->viewArgs[$argName]);
				
				$orWhere[v("ricerca_termini_and_or")] = $this->m($this->modelName)->getWhereSearch($search);
			}
			else
				$orWhere[v("ricerca_termini_and_or")] = $this->m($this->modelName)->getWhereSearch($this->viewArgs[$argName], 50, "title");
		}
		else
		{
			$campoCerca = v("mostra_filtro_ricerca_libera_in_magazzino") ? "campo_cerca" : "title";
			
			$orWhere[v("ricerca_termini_and_or")] = $this->m($this->modelName)->getWhereSearch($this->viewArgs[$argName], 50, $campoCerca, "contenuti_tradotti");
		}
		
		return array(
			" OR"	=>	$orWhere,
		);
	}
	
	protected function queryElencoProdotti($id, $firstSection, $escludi = array(), $attivaOrderBy = true)
	{
		$clean['id'] = (int)$id;
		
		$this->m("PagesModel")->clear()->restore()->select(PagesModel::getSelectDistinct()."pages.*,categories.*,contenuti_tradotti.*,contenuti_tradotti_categoria.*")->addWhereAttivo();
		
		$this->addOrderByClause($firstSection, null, $attivaOrderBy);
		
		if ($this->catSWhere)
			$this->m("PagesModel")->sWhere($this->catSWhere);
		
		if (Parametri::$hideNotAllowedNodesInLists)
		{
			$accWhere = $this->m("PagesModel")->getAccessibilityWhere();
			$this->m("PagesModel")->aWhere($accWhere);
		}
		
		// Visibilità pagina
		if (v("abilita_visibilita_pagine"))
			$this->m("PagesModel")->addWhereLingua();
		
		if (strcmp($this->viewArgs["search"],"") !== 0)
		{
			if (empty($escludi))
			{
				// Salvo la ricerca
				$this->m("RicercheModel")->aggiungiRicerca($this->viewArgs["search"]);
			}
			
			$this->m("PagesModel")->aWhere($this->getSearchWhere("search"));
		}
		
		if (v("attiva_ricerca_documento") && strcmp($this->viewArgs["searchdoc"],"") !== 0)
			$this->getSearchWhereDoc();
		
		// Where figli
		if (in_array("[categoria]",$escludi))
			$clean['id'] = (int)CategoriesModel::getIdCategoriaDaSezione($firstSection);
		
		$children = $this->m("CategoriesModel")->children($clean['id'], true);
		$children = forceIntDeep($children);
		
		if (!in_array("[categoria]",$escludi) && v("attiva_categorie_in_prodotto"))
		{
			$bindedValues = $children;
			$bindedValues[] = (int)$clean['id'];
			$this->m("PagesModel")->sWhere(array("(pages.id_c in(".$this->m("PagesModel")->placeholdersFromArray($children).") OR pages.id_page in (select id_page from pages_categories where id_c = ?))",$bindedValues));
		}
		else
			$this->m("PagesModel")->aWhere(array(
				"in" => array("-id_c" => $children),
			));
		
		if ($this->idMarchio && !in_array("[marchio]",$escludi))
			$this->m("PagesModel")->aWhere(array(
				"id_marchio"	=>	(int)$this->idMarchio,
			));
		
		if ($this->idTag && !in_array("[tag]",$escludi))
			$this->m("PagesModel")->inner(array("tag"))->aWhere(array(
				"pages_tag.id_tag"	=>	(int)$this->idTag,
			));
		
		// Promozioni
		if (self::$isPromo)
		{
			$this->addStatoWhereClause(AltriFiltri::$aliasValoreTipoPromo[0]);
		}
		
		$temp = CaratteristicheModel::$filtriUrl;
		$temp = array_diff_key($temp, array_combine($escludi, $escludi));
		
		// Filtri caratteristiche
		if (!empty($temp))
		{
			$combinazioniCaratteristiche = prodottoCartesiano($temp);
			
// 			$tabellaCaratterisitche = "(select pages_caratteristiche_valori.id_page,group_concat(distinct(concat('#',coalesce(caratteristiche_tradotte.alias,caratteristiche.alias),'#')) order by caratteristiche.alias) as car_alias,group_concat(distinct(concat('#',coalesce(caratteristiche_valori_tradotte.alias,caratteristiche_valori.alias),'#')) order by caratteristiche_valori.alias) as car_val_alias from caratteristiche inner join caratteristiche_valori on caratteristiche_valori.id_car = caratteristiche.id_car inner join pages_caratteristiche_valori on pages_caratteristiche_valori.id_cv = caratteristiche_valori.id_cv and caratteristiche.filtro = 'Y' 
// 			left join contenuti_tradotti as caratteristiche_tradotte on caratteristiche_tradotte.id_car = caratteristiche.id_car and caratteristiche_tradotte.lingua = '".sanitizeDb(Params::$lang)."' 
// 			left join contenuti_tradotti as caratteristiche_valori_tradotte on caratteristiche_valori_tradotte.id_cv = caratteristiche_valori.id_cv and caratteristiche_valori_tradotte.lingua = '".sanitizeDb(Params::$lang)."' 
// 			group by pages_caratteristiche_valori.id_page) as tabella_caratteristiche";
			
			$tabellaCaratterisitche = array(
				"(select pages_caratteristiche_valori.id_page,group_concat(distinct(concat('#',coalesce(caratteristiche_tradotte.alias,caratteristiche.alias),'#')) order by caratteristiche.alias) as car_alias,group_concat(distinct(concat('#',coalesce(caratteristiche_valori_tradotte.alias,caratteristiche_valori.alias),'#')) order by caratteristiche_valori.alias) as car_val_alias from caratteristiche inner join caratteristiche_valori on caratteristiche_valori.id_car = caratteristiche.id_car inner join pages_caratteristiche_valori on pages_caratteristiche_valori.id_cv = caratteristiche_valori.id_cv and caratteristiche.filtro = ? 
				left join contenuti_tradotti as caratteristiche_tradotte on caratteristiche_tradotte.id_car = caratteristiche.id_car and caratteristiche_tradotte.lingua = ? 
				left join contenuti_tradotti as caratteristiche_valori_tradotte on caratteristiche_valori_tradotte.id_cv = caratteristiche_valori.id_cv and caratteristiche_valori_tradotte.lingua = ? 
				group by pages_caratteristiche_valori.id_page) as tabella_caratteristiche",
				array(
					'Y',
					sanitizeDb(Params::$lang),
					sanitizeDb(Params::$lang)
				),
			);
			
			$this->m("PagesModel")->inner($tabellaCaratterisitche)->on("pages.id_page = tabella_caratteristiche.id_page");
			
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
			
			$bindedValues = array();
			
			foreach ($sWhereArray as $sWhereValori)
			{
				$tempWhere = array();
				
				foreach ($sWhereValori as $sWhereValore)
				{
// 					$tempWhere[] = "car_val_alias like '%#".sanitizeDb($sWhereValore)."#%'";
					$tempWhere[] = "car_val_alias like ?";
					$bindedValues[] = "%#".addBackSlashLike(sanitizeDb($sWhereValore))."#%";
				}
				
				$sWhereQueryArray[] = "(".implode(" AND ", $tempWhere).")";
			}
			
			if (count($sWhereQueryArray) > 1)
				$sWhereQuery = "(".implode(" OR ", $sWhereQueryArray).")";
			else if (count($sWhereQueryArray) > 0)
				$sWhereQuery = $sWhereQueryArray[0];
			
// 			$this->m("PagesModel")->sWhere($sWhereQuery);
			$this->m("PagesModel")->sWhere(array($sWhereQuery, $bindedValues));
		}
		
		// Filtri località
		if (!empty(RegioniModel::$filtriUrl))
		{
			$prodottoTutteLeRegioni = v("prodotto_tutte_regioni_se_nessuna_regione");
			
			if ($prodottoTutteLeRegioni)
				$this->m("PagesModel")->left(array("regioni"));
			else
				$this->m("PagesModel")->inner(array("regioni"));
			
			$combinazioniLocalita = prodottoCartesiano(RegioniModel::$filtriUrl);
			
			$sWhereQueryArray = $bindedValues = array();
			
			foreach ($combinazioniLocalita as $combCar)
			{
				$tempWhere = array();
				
				foreach ($combCar as $k => $v)
				{
					$field = $k == RegioniModel::$nAlias ? "alias_nazione" : "alias_regione";
					
					if ($field == "alias_nazione" && in_array("[nazione]",$escludi))
						continue;
					
					if ($field == "alias_regione" && in_array("[regione]",$escludi))
						continue;
					
// 					$tmpSql = "$field = '".sanitizeDb($v)."'";
					$tmpSql = "$field = ?";
					
					$bindedValues[] = sanitizeDb($v);
					
					if ($prodottoTutteLeRegioni)
						$tmpSql .= " OR $field IS NULL";
					
					$tempWhere[] = $tmpSql;
				}
				
				if (count($tempWhere) > 0)
					$sWhereQueryArray[] = "(".implode(" AND ", $tempWhere).")";
			}
			
			$sWhereQuery = "";
			
			if (count($sWhereQueryArray) > 1)
				$sWhereQuery = "(".implode(" OR ", $sWhereQueryArray).")";
			else if (count($sWhereQueryArray) > 0)
				$sWhereQuery = $sWhereQueryArray[0];
			
			if ($sWhereQuery)
				$this->m("PagesModel")->sWhere(array($sWhereQuery,$bindedValues));
// 				$this->m("PagesModel")->sWhere($sWhereQuery);
		}
		
		if (!empty(AltriFiltri::$filtriUrl))
		{
			foreach (AltriFiltri::$filtriUrl as $tipoFiltro => $valoreFiltro)
			{
				if (isset(AltriFiltri::$altriFiltriTipi["fascia-prezzo"]) && $tipoFiltro == AltriFiltri::$altriFiltriTipi["fascia-prezzo"] && !in_array("prezzo",$escludi))
				{
					$campoPrezzo = "prezzo_minimo";
					
					if (v("mostra_fasce_prezzo") && !v("filtro_prezzo_slider"))
						$fasciaPrezzo = $data["fasciaPrezzo"] = $this->m("FasceprezzoModel")->clear()->addJoinTraduzione()->sWhere(array("coalesce(contenuti_tradotti.alias,fasce_prezzo.alias) = ?",array(sanitizeDb($valoreFiltro))))->first();
					else if (v("filtro_prezzo_slider") && preg_match('/^[a-zA-Z]{1,7}\-([0-9]{1,5})\-[a-zA-Z]{1,7}\-([0-9]{1,5})$/',$valoreFiltro, $matchesPrezzo))
					{
						Cache_Db::$skipWritingCache = true;
						
						$fasciaPrezzo = $data["fasciaPrezzo"] = array(
							"fasce_prezzo"	=>	array(
								"da"	=>	(int)$matchesPrezzo[1],
								"a"		=>	(int)$matchesPrezzo[2],
							),
						);
						
						$campoPrezzo = "prezzo_minimo_ivato";
					}
					
					if (isset($fasciaPrezzo) && !empty($fasciaPrezzo))
					{
						$this->m("PagesModel")->aWhere(array(
							"    gte"	=>	array(
								"combinazioni_minime.$campoPrezzo"	=>	sanitizeDb($fasciaPrezzo["fasce_prezzo"]["da"]),
							),
							"     lte"	=>	array(
								"combinazioni_minime.$campoPrezzo"	=>	sanitizeDb($fasciaPrezzo["fasce_prezzo"]["a"]),
							),
						));
					}
				}
				else if ($tipoFiltro == AltriFiltri::$altriFiltriTipi["stato-prodotto"] && !in_array("[evidenza]", $escludi))
					$this->addStatoWhereClause(AltriFiltri::$aliasValoreTipoInEvidenza[0]);
				else if ($tipoFiltro == AltriFiltri::$altriFiltriTipi["stato-prodotto-nuovo"] && !in_array("[nuovo]", $escludi))
					$this->addStatoWhereClause(AltriFiltri::$aliasValoreTipoNuovo[0]);
				else if ($tipoFiltro == AltriFiltri::$altriFiltriTipi["stato-prodotto-promo"] && !in_array("[promozioni]", $escludi))
					$this->addStatoWhereClause(AltriFiltri::$aliasValoreTipoPromo[0]);
			}
		}
		
		$this->m("PagesModel")->addJoinTraduzionePagina();
		
		if (isset($data))
			$this->append($data);
	}
	
	protected function addStatoWhereClause($tipo = "in-promozione")
	{
		$wherePromo = array();
		
		switch ($tipo)
		{
			case AltriFiltri::$aliasValoreTipoPromo[0]:
				$this->m("PagesModel")->addWherePromo();
				self::$isPromo = true;
				break;
				
			case AltriFiltri::$aliasValoreTipoNuovo[0]:
				$this->m("PagesModel")->addWhereNuovo();
				break;
				
			case AltriFiltri::$aliasValoreTipoInEvidenza[0]:
				$this->m("PagesModel")->addWhereEvidenza();
				break;
		}
		
		if (!empty($wherePromo))
			$this->m("PagesModel")->aWhere($wherePromo);
	}
	
	protected function addOrderByClause($firstSection, $urlOrdinamento = null, $attivaOrderBy = true)
	{
		if ($attivaOrderBy)
		{
			if ($firstSection == Parametri::$nomeSezioneProdotti)
				$this->m("PagesModel")->orderBy($this->gerOrderByProdotti($this->viewArgs['o']));
			else
				$this->m("PagesModel")->orderBy($this->gerOrderBy($firstSection));
		}
		
		if (!$urlOrdinamento)
			$data["url_ordinamento"] = $this->baseUrl."/".$this->getCurrentUrl(false, "categoria");
		else
			$data["url_ordinamento"] = $this->baseUrl."/".$urlOrdinamento;
		
		if ($firstSection == Parametri::$nomeSezioneProdotti)
		{
			$campoPrezzoMinimoIvato = v("sconti_combinazioni_automatiche") ? "price_scontato_ivato" : "price_ivato";
			
			$campoPrezzoMinimo = v("sconti_combinazioni_automatiche") ? "price_scontato" : "price";
			
			$bindedValues = array();
			
			if (VariabiliModel::combinazioniLinkVeri())
			{
				if (User::$nazione)
				{
					$tabellaCombinazioni = "(select id_page,coalesce(combinazioni_listini.$campoPrezzoMinimo,combinazioni.$campoPrezzoMinimo) as prezzo_minimo,coalesce(combinazioni_listini.$campoPrezzoMinimoIvato,combinazioni.$campoPrezzoMinimoIvato) as prezzo_minimo_ivato from combinazioni left join combinazioni_listini on combinazioni_listini.id_c = combinazioni.id_c and combinazioni_listini.nazione = ? where combinazioni.canonical = 1 and combinazioni.acquistabile = 1) as combinazioni_minime";
					
					$bindedValues[] = sanitizeAll(User::$nazione);
				}
				else
					$tabellaCombinazioni = "(select id_page,$campoPrezzoMinimo as prezzo_minimo,$campoPrezzoMinimoIvato as prezzo_minimo_ivato from combinazioni where combinazioni.canonical = 1 and combinazioni.acquistabile = 1) as combinazioni_minime";
			}
			else
			{
				if (User::$nazione)
				{
					$tabellaCombinazioni = "(select id_page,min(coalesce(combinazioni_listini.$campoPrezzoMinimo,combinazioni.$campoPrezzoMinimo)) as prezzo_minimo,min(coalesce(combinazioni_listini.$campoPrezzoMinimoIvato,combinazioni.$campoPrezzoMinimoIvato)) as prezzo_minimo_ivato from combinazioni left join combinazioni_listini on combinazioni_listini.id_c = combinazioni.id_c and combinazioni_listini.nazione = ? where combinazioni.acquistabile = 1 group by combinazioni.id_page) as combinazioni_minime";
					
					$bindedValues[] = sanitizeAll(User::$nazione);
				}
				else
					$tabellaCombinazioni = "(select id_page,min($campoPrezzoMinimo) as prezzo_minimo,min($campoPrezzoMinimoIvato) as prezzo_minimo_ivato from combinazioni where combinazioni.acquistabile = 1 group by combinazioni.id_page) as combinazioni_minime";
			}
			
			$this->m("PagesModel")->inner(array($tabellaCombinazioni,$bindedValues))->on("pages.id_page = combinazioni_minime.id_page");
		}
		
		if ($firstSection == Parametri::$nomeSezioneProdotti && $this->viewArgs['o'] == "piuvenduto")
		{
			if (!v("aggiorna_colonna_numero_acquisti_prodotti_ad_ordine_concluso"))
				$this->m("PagesModel")->left("(select id_page,sum(quantity) as numero_acquisti from righe group by id_page) as righe_sum")->on("pages.id_page = righe_sum.id_page");
		}
		
		$this->append($data);
	}
	
	protected function gerOrderByProdotti($string)
	{
		switch($string)
		{
			case "tutti":
				return "pages.id_order";
			case "az":
				return "coalesce(contenuti_tradotti.title,pages.title)";
			case "za":
				return "coalesce(contenuti_tradotti.title,pages.title) desc";
			case "crescente":
				return "combinazioni_minime.prezzo_minimo,pages.id_order";
			case "decrescente":
				return "combinazioni_minime.prezzo_minimo desc,pages.id_order";
			case "piuvenduto":
				if (!v("aggiorna_colonna_numero_acquisti_prodotti_ad_ordine_concluso"))
					return "pages.numero_acquisti_pagina desc,pages.id_order";
				else
					return "pages.numero_acquisti_pagina desc,pages.id_order";
			default:
				return "pages.id_order";
		}
	}
	
	protected function gerOrderBy($section)
	{
		switch ($section)
		{
			case "blog":
				return "pages.data_news desc,pages.id_order desc";
				break;
			case "eventi":
				return "pages.data_inizio_evento desc, pages.ora_inizio_evento, pages.data_fine_evento desc, pages.ora_fine_evento, pages.data_news desc";
				break;
		}
		
		return "pages.id_order";
	}
	
	private function sectionLoad($section, $type = "category", $template = null)
	{
		$alloweSections = $this->m("CategoriesModel")->clear()->select("section")->where(array(
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
		
		if (!$this->m('PagesModel')->check($clean["id"]))
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
		
		if (!$this->m('CategoriesModel')->check($clean["id"]))
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
		if (v("attiva_formn_contatti"))
			$this->inviaMailFormContatti(0);
		
		$linguaPrincipale = LingueModel::getPrincipale();
		
		$marchiAlias = $this->m("MarchiModel")->clear()->select("contenuti_tradotti.lingua,contenuti_tradotti.alias")->left("contenuti_tradotti")->on("contenuti_tradotti.id_marchio = marchi.id_marchio")->where(array(
			"marchi.id_marchio"	=>	(int)$id,
		))->toList("contenuti_tradotti.lingua", "contenuti_tradotti.alias")->send();
		
		$marchioCorrente = $data["marchioCorrente"] = $this->m("MarchiModel")->clear()->addJoinTraduzione()->where(array(
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
			
			if (Output::$html)
				$this->load("marchio");
			else
				$this->load("api_output");
		}
		else
			$this->redirect("contenuti/notfound");
	}
	
	protected function salvaStatisticheVisualizzazione($id)
	{
		$this->model("PagesstatsModel");
		
		$this->m("PagesstatsModel")->aggiungi($id);
	}
	
	// Restituisce i correlati di una pagina tramite una chiamata AJAX
	// $altreCategorie: quanti estrarne dalla stessa categoria
	// $altrivisitatori: quanti estrarne tra quelli visti da altri visitatori
	// numero di prodotti da saltare (già nella pagina al primo caricamento)
	public function correlatiajax($id, $altreCategorie = 0, $altrivisitatori = 0, $salta = 0)
	{
		$clean['id'] = (int)$id;
		$clean['altreCategorie'] = (int)$altreCategorie;
		$clean['altrivisitatori'] = (int)$altrivisitatori;
		
		$this->clean();
		
		if ($clean['altreCategorie'] > 30)
			$clean['altreCategorie'] = 0;
		
		if ($clean['altrivisitatori'] > 30)
			$clean['altrivisitatori'] = 0;
		
		$prodotti_correlati = $this->m('PagesModel')->getCorrelati($clean['id'], 0, $clean['altreCategorie'], $clean['altrivisitatori']);
		
		$data["prodotti_correlati"] = array();
		
		$indice = 0;
		
		foreach ($prodotti_correlati as $p)
		{
			if ($indice >= (int)$salta)
				$data["prodotti_correlati"][] = $p;
			
			$indice ++;
		}
		
		PagesModel::clearIdCombinazione();
		$data["prodotti_correlati"] = PagesModel::impostaDatiCombinazionePagine($data["prodotti_correlati"]);
		PagesModel::restoreIdCombinazione();
		
		$this->append($data);
		
		$this->load("prodotti-details-correlati");
	}
	
	public function fascia($idPage = 0, $idFascia = 0)
	{
		if (!User::$adminLogged)
			$this->responseCode(403);
		
		$clean['idPage'] = PagesModel::$currentIdPage = (int)$idPage;
		$clean['idFascia'] = (int)$idFascia;
		
		$this->clean();
		
		if ($idPage === 0)
			$clean["idPaginaHome"] = (int)$this->m("PagesModel")->clear()->where(array(
				"tipo_pagina"	=>	"HOME",
			))->field("id_page");
			
		$data["fasce"] = $this->m("ContenutiModel")->elaboraContenuti($clean['idPage'], 0, $this, 0, false, $clean['idFascia']);
		
		$this->append($data);
		$this->load("page-fascia");
	}
	
	protected function page($id)
	{
		$this->m("PagesModel")->checkBloccato($id, "page");
		
		Cache_Db::addTablesToCache(array("combinazioni","scaglioni"));
		
		$clean["realId"] = $data["realId"] = (int)$id;
		
		if (v("salva_satistiche_visualizzazione_pagina"))
			$this->salvaStatisticheVisualizzazione($id);
		
		if (v("attiva_formn_contatti"))
			$this->inviaMailFormContatti($clean["realId"]);
		
		$this->checkPage($clean["realId"]);
		
		$clean['id'] = $this->m('PagesModel')->getPrincipale($clean["realId"]);
		
		$firstSection = $data["fsection"] = $this->firstSection = $this->m("PagesModel")->section($clean['id'], true);
		
		$data["arrayLingue"] = array();
		
		foreach (Params::$frontEndLanguages as $l)
		{
			if (v("abilita_visibilita_pagine"))
			{
				$linguePagina = $this->m("PagesModel")->getArrayLingueAttiveFrontend($clean['realId']);
				
				if (!in_array($l, $linguePagina))
					continue;
			}
			
			$data["arrayLingue"][$l] = $l."/".$this->m("PagesModel")->getUrlAlias($clean['realId'], $l);
		}
		
		$data["isPage"] = true;
		
		PagesModel::$currentIdPage = (int)$clean['realId'];
		RegusersModel::getRedirect();
		
		// Elaborazione feedback
		if (!$this->m('PagesModel')->checkTipoPagina($clean["realId"], "FORM_FEEDBACK"))
			$data["breadcrumb"] = $this->breadcrumbHtml = $this->breadcrumb("page");
		else
			$this->inserisciFeedback($clean["realId"]);
		
		if ($firstSection == "prodotti")
			$data["scaglioni"] = $this->scaglioni = $this->m("ScaglioniModel")->clear()->where(array("id_page"=>$clean['id']))->toList("quantita","sconto")->send();
		
		$this->m('PagesModel')->clear()->select("pages.*,categories.*,contenuti_tradotti.*,contenuti_tradotti_categoria.*,marchi.*")
			->addJoinTraduzionePagina()
			->left("marchi")->on("pages.id_marchio = marchi.id_marchio")
			->where(array("id_page"=>$clean['id']));
		
		$data["pages"] = $this->pages = $this->m('PagesModel')->send();
		
		if (V("estrai_filtri_su_dettaglio_pagina"))
			$this->estraiDatiFiltri();
		
		if ($firstSection == "prodotti")
			$data["pages"] = PagesModel::impostaDatiCombinazionePagine($data["pages"]);
		
		if (count($data["pages"]) > 0)
		{
			// Reditect
			$this->m('PagesModel')->checkAndInCaseRedirect($data["pages"][0]);
			
			if ($data["pages"][0]["pages"]["tipo_pagina"] == "COOKIE")
			{
				VariabiliModel::noCookieAlert();
				$data["pages"][0]["pages"]["description"] .= "[ELENCO_COOKIE][scelta-cookie]";
				
				if (isset($data["pages"][0]["contenuti_tradotti"]["description"]))
					$data["pages"][0]["contenuti_tradotti"]["description"] .= "[ELENCO_COOKIE][scelta-cookie]";
			}
			
			// GDPR
			$data["pages"][0] = GDPR::filtraData($data["pages"][0]);
			$data["pages"][0]["pages"]["description"] = GDPR::filtraDecodeEncode($data["pages"][0]["pages"]["description"]);
			$data["pages"][0]["pages"]["video"] = GDPR::filtraDecodeEncode($data["pages"][0]["pages"]["video"]);
			
			$this->p = $data["pages"][0];
			
			$data["tipoPagina"] = PagesModel::$currentTipoPagina = $data["pages"][0]["pages"]["tipo_pagina"];
			
			if ($data["tipoPagina"] == "LISTA_REGALO")
				$this->getAppLogin();
			
			if (!$data["pages"][0]["pages"]["carica_header_footer"])
				$this->clean();
			
			if (!User::$adminLogged && $data["pages"][0]["pages"]["crea_cache"] && $this->checkCacheHtmlAttiva())
			{
				$cache = Cache_Html::getInstance();
				$cache->saveHtml = true;
			}
		}
		
		if ($firstSection != "prodotti")
		{
			$data["paginaPrecedente"] = $this->m('PagesModel')->where(array(
				"OR"	=>	array(
					"lt"	=>	array("pages.data_news"	=>	sanitizeDb($data['pages'][0]["pages"]["data_news"])),
					"AND"	=>	array(
						"pages.data_news"	=>	sanitizeDb($data['pages'][0]["pages"]["data_news"]),
						"lt"	=>	array("pages.id_order"	=>	(int)$data['pages'][0]["pages"]["id_order"]),
					),
				),
			))->addWhereCategoria((int)CategoriesModel::getIdCategoriaDaSezione($firstSection))->orderBy("pages.data_news desc,pages.id_order desc")->limit(1)->send();
			
			$data["paginaSuccessiva"] = $this->m('PagesModel')->where(array(
				"OR"	=>	array(
					"gt"	=>	array("pages.data_news"	=>	sanitizeDb($data['pages'][0]["pages"]["data_news"])),
					"AND"	=>	array(
						"pages.data_news"	=>	sanitizeDb($data['pages'][0]["pages"]["data_news"]),
						"gt"	=>	array("pages.id_order"	=>	(int)$data['pages'][0]["pages"]["id_order"]),
					),
				),
			))->addWhereCategoria((int)CategoriesModel::getIdCategoriaDaSezione($firstSection))->orderBy("pages.data_news,pages.id_order desc")->limit(1)->send();
		}
		
		if (field($data['pages'][0], "meta_description"))
			$data["meta_description"] = F::meta(field($data['pages'][0], "meta_description"));
		
		if (field($data['pages'][0], "keywords"))
			$data["keywords"] = F::meta(field($data['pages'][0], "keywords"));
		
		if ($firstSection == "prodotti")
		{
			list ($colonne, $data["lista_valori_attributi"]) = $this->m('PagesModel')->selectAttributi($clean['id']);
			
			$data["lista_attributi"] = $this->lista_attributi = $colonne;
			
			$this->lista_valori_attributi = $data["lista_valori_attributi"];
			
			$data["haVarianti"] = count($data["lista_valori_attributi"]) > 0 ? true : false;
			
			$data["prezzoMinimo"] = $this->prezzoMinimo = $this->m('PagesModel')->prezzoMinimo($clean['id']);
		}
		
		$data["prodotti_correlati"] = $this->m('PagesModel')->getCorrelati($clean['id']);
		
		if ((int)v("numero_massimo_comprati_assieme") > 0 && !$data['pages'][0]["pages"]["gift_card"])
			$data["prodotti_comprati_assieme"] = $this->m('PagesassociateModel')->getCompratiAssieme($clean['id'], $data["prodotti_correlati"]);
		
		if ($firstSection == "prodotti")
		{
			PagesModel::clearIdCombinazione();
			$data["prodotti_correlati"] = PagesModel::impostaDatiCombinazionePagine($data["prodotti_correlati"]);
			
			if ((int)v("numero_massimo_comprati_assieme") > 0 && !$data['pages'][0]["pages"]["gift_card"])
				$data["prodotti_comprati_assieme"] = PagesModel::impostaDatiCombinazionePagine($data["prodotti_comprati_assieme"]);
			PagesModel::restoreIdCombinazione();
		}
		
		if (v("accessori_in_prodotti"))
			$data["accessori"] = $this->m('PagesModel')->getCorrelati($clean['id'], 1);
		
		// Pagine correlate
		if (v("estrai_sempre_correlati") || $firstSection == "prodotti")
			$data["pagine_correlate"] = $this->m('PagesModel')->clear()->select("pages.*,pages_pages.id_corr,categories.*,contenuti_tradotti.*,contenuti_tradotti_categoria.*")->from("pages_pages")->inner("pages")->on("pages.id_page=pages_pages.id_corr")
				->addJoinTraduzionePagina()
				->where(array(
					"pages_pages.id_page"=>	$clean['id'],
					"attivo"	=>	"Y",
				))->orderBy("pages_pages.section,pages_pages.id_order")->send();
		
		$data["altreImmagini"] = array();
		
		if (count($data["pages"]) > 0)
			$data["altreImmagini"] = ImmaginiModel::altreImmaginiPagina($clean['id']);
		
		$this->altreImmagini = $data["altreImmagini"];
		
		$orderByCaratteristiche = v("caratteristiche_in_tab_separate") ? "tipologie_caratteristiche.id_order, pages_caratteristiche_valori.id_order" : "pages_caratteristiche_valori.id_order" ;
		
		// CARATTERISTICHE
		if (v("estrai_le_caratteristiche"))
		{
			$data["caratteristiche"] = $data["lista_caratteristiche"] = $this->m("PagesModel")->selectCaratteristiche($clean['id']);
			
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
		}
		
		$data["haPersonalizzazioni"] = false;
		
		// Personalizzazioni
		if ($firstSection == "prodotti" && v("attiva_personalizzazioni"))
		{
			$data["personalizzazioni"] = $this->m('PagesModel')->selectPersonalizzazioni($clean['id']);
			
			if (count($data["personalizzazioni"]) > 0)
				$data["haPersonalizzazioni"] = true;
		}
		
		if (v("estrai_i_documenti"))
			$data["documenti"] = $this->documentiPagina = $this->m("PagesModel")->getDocumenti($clean['id']);
		
		if (v("mostra_link_in_blog"))
		{
			$this->model("PageslinkModel");
			$data["links"] = $this->m("PageslinkModel")->clear()->where(array(
				"id_page"	=>	$clean['id'],
			))->orderBy("titolo")->send();
		}
		
		if ($firstSection == "prodotti")
			$data["isProdotto"] = true;
		
		$data["richSnippet"] = PagesModel::getRichSnippetPage((int)$id);
		
// 		if (v("mostra_tendina_prodotto_principale") || v("aggiorna_pagina_al_cambio_combinazione_in_prodotto"))
			$data["tagCanonical"] = PagesModel::getTagCanonical((int)$id);
		
		//estrai i dati della categoria
		$r = $this->m('CategoriesModel')->clear()->select("categories.*,contenuti_tradotti_categoria.*")->addJoinTraduzioneCategoria()->where(array("section"=>sanitizeAll($firstSection)))->send();
		$data["datiCategoriaPrincipale"] = $r[0];
		
		$data["pagesCss"] = $data['pages'][0]["pages"]["css"];
		
		// Estraggo le fasce
		$data["fasce"] = $this->m("ContenutiModel")->elaboraContenuti($clean['id'], 0, $this);
		
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
			$marchioCorrente =  $data["marchioCorrente"] = $this->m("MarchiModel")->clear()->addJoinTraduzione()->where(array(
				"marchi.id_marchio"	=>	(int)$data['pages'][0]["pages"]["id_marchio"],
			))->first();
			
			if (count($marchioCorrente) > 0)
				$data["aliasMarchioCorrente"] = mfield($marchioCorrente, "alias")."/";
		}
		
		$data["page_tags"] = array();
		
		if (v("usa_tag"))
		{
			$this->model("PagestagModel");
			$data["page_tags"] = $this->m("PagestagModel")->clear()->select("*")->inner(array("tag"))->where(array(
				"id_page"	=>	$clean['id'],
			))->orderBy("pages_tag.id_order")->send();
			
			// Con traduzione
			$data["page_tags_full"] = $this->m("TagModel")->clear()->select("*")->inner(array("pagine"))->addJoinTraduzione()->where(array(
				"pages_tag.id_page"	=>	$clean['id'],
			))->orderBy("pages_tag.id_order")->send();
		}
		
		$data["page_feedback"] = array();
		
		if (v("abilita_feedback"))
			$data["page_feedback"] = FeedbackModel::get($clean['id']);
		
		if (v("attiva_localizzazione_prodotto"))
			list($data["nazioni_prodotto"], $data["regioni_prodotto"]) = $this->m("PagesModel")->getLocalizzazione($clean['id']);
		
		if (v("attiva_liste_regalo") && $firstSection == "prodotti" && User::$logged)
			$data["liste_regalo"] = ListeregaloModel::listeUtente(User::$id);
		
		$this->append($data);
		
		$template = strcmp($data['pages'][0]["pages"]["template"],"") === 0 ? null : $data['pages'][0]["pages"]["template"];
		
		$section = $this->m("PagesModel")->section($clean['id']);
		
		if (Output::$html)
		{
			if (!isset($_GET["pdf"]) || !v("permetti_generazione_pdf_pagine_frontend"))
				$this->sectionLoad($section, "page", $template);
			else
			{
				$this->clean();
				
				Pdf::output(tpf("Contenuti/pdf.php"), field($data["pages"][0],"title").".pdf", $data);
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
		
		// se col non è blank, mostra le combinazioni al livello di col
		$clean["col"] = $this->request->post("col","","sanitizeAll");
		
		$where = array(
			"id_page"	=>	$clean["id_page"],
		);
		
		if (!User::$adminLogged)
			$where["combinazioni.acquistabile"] = 1;
		
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
		
		$this->m("CombinazioniModel")->clear()->where($where);
		
		if ($clean["col"] && in_array($clean["col"] , $allowedFields))
			$this->m("CombinazioniModel")->groupBy($clean["col"] );
		
		if (!$clean["col"] )
		{
			$res = $this->m("CombinazioniModel")->send();
			
			if (count($res) > 0)
			{
				PagesModel::$IdCombinazione = $res[0]["combinazioni"]["id_c"];
				
				$prezzoPieno = "";
				$prezzoCombinazione = $res[0]["combinazioni"]["price"];
				
				if (User::$nazione)
					$prezzoCombinazione = $this->m("CombinazioniModel")->getPrezzoListino($res[0]["combinazioni"]["id_c"], User::$nazione, $prezzoCombinazione);
				
				if (inPromozioneTot($clean["id_page"]))
					$prezzoPieno = setPriceReverse(calcolaPrezzoIvato($clean["id_page"], $prezzoCombinazione));
				
				$qty = (int)$res[0]["combinazioni"]["giacenza"];
				
				if ($qty < 0)
					$qty = 0;
				else if ($qty >= v("giacenza_massima_mostrata"))
					$qty = v("giacenza_massima_mostrata");
				
				echo '<span class="id_combinazione">'.$res[0]["combinazioni"]["id_c"].'</span><span class="codice_combinazione">'.$res[0]["combinazioni"]["codice"].'</span><span class="prezzo_combinazione">'.setPriceReverse(calcolaPrezzoFinale($clean["id_page"], $prezzoCombinazione,true,false, $res[0]["combinazioni"]["id_c"])).'</span><span class="immagine_combinazione">'.$res[0]["combinazioni"]["immagine"].'</span><span class="prezzo_pieno_combinazione">'.$prezzoPieno.'</span><span class="giacenza_combinazione">'.$qty.'</span><span class="peso_combinazione">'.setPriceReverse($res[0]["combinazioni"]["peso"]).'</span>';
				
				if (v("aggiorna_pagina_al_cambio_combinazione_in_prodotto") && (v("usa_codice_combinazione_in_url_prodotto") || v("usa_alias_combinazione_in_url_prodotto")))
				{
					echo '<span class="url_redirect_combinazione">'.$this->m("PagesModel")->getUrlAlias($res[0]["combinazioni"]["id_page"], null, $res[0]["combinazioni"]["id_c"]).'</span>';
					
					echo '<span class="url_redirect_fragment">'.v("fragmento_dettaglio_prodotto").'</span>';
				}
			}
			else
			{
				echo "KO";
			}
		}
		else
		{
			header('Content-type: application/json; charset=utf-8');
			
			$res = $this->m("CombinazioniModel")->send(false);
			
			$jsonArray = array();
			
			foreach ($res as $r)
			{
				$jsonArray[] = $r[$clean["col"]];
			}
			
			echo json_encode($jsonArray);
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
			// Salvo la ricerca
			$this->m("RicercheModel")->aggiungiRicerca($this->viewArgs["s"]);
			
			if ($this->viewArgs["sec"] == Parametri::$nomeSezioneProdotti)
				$clean["idSection"] = $this->m("CategoriesModel")->getShopCategoryId();
			else if ($this->viewArgs["sec"] != "tutti")
				$clean["idSection"] = $this->m('CategoriesModel')->clear()->where(array(
					"section"	=>	$this->viewArgs["sec"],
				))->field("id_c");
			
			if ($this->viewArgs["sec"] != "tutti")
				$childrenProdotti = $this->m("CategoriesModel")->children($clean["idSection"], true);
			
			$where = $this->getSearchWhere("s");
			
			$this->m('PagesModel')->clear()->where($where)->addWhereAttivo();
			
			if ($this->viewArgs["sec"] != "tutti")
				$this->m("PagesModel")->aWhere(array(
					"in" => array("-id_c" => $childrenProdotti),
				));
			
			if (Parametri::$hideNotAllowedNodesInLists)
			{
				$accWhere = $this->m("PagesModel")->getAccessibilityWhere();
				$this->m("PagesModel")->aWhere($accWhere);
			}
			
			$this->addOrderByClause($this->viewArgs["sec"], 'risultati-ricerca');
			
			if ($this->catSWhereCerca)
				$this->m("PagesModel")->sWhere($this->catSWhereCerca);
			
			$rowNumber = $data["rowNumber"] = $this->m('PagesModel')->addJoinTraduzionePagina()->aWhere(array(
				"pages.add_in_sitemap"=>	"Y",
// 				"categories.add_in_sitemap_children"	=>	"Y",
			))->addWhereClauseCerca()->rowNumber();
			
			$this->setElementsPerPage($this->viewArgs["sec"]);
			$data["elementsPerPage"] = $this->elementsPerPage;
			
			if ($rowNumber > $this->elementsPerPage)
			{
				$page = $this->viewArgs['p'];
				
				$this->m('PagesModel')->limit = $this->h['Pages']->getLimit($page,$rowNumber,$this->elementsPerPage);
				
				$data['pageList'] = $this->h['Pages']->render($page-5,11);
			}
			
			$data["pages"] = $this->m('PagesModel')->send();
			
// 			echo $this->m('PagesModel')->getQuery();die();
			
			if ($this->viewArgs["sec"] == Parametri::$nomeSezioneProdotti)
				$data["pages"] = PagesModel::impostaDatiCombinazionePagine($data["pages"]);
		}
		
		$this->append($data);
		
		$this->load('search');
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
		
		$idShopCat = (int)$this->m('CategoriesModel')->clear()->where(array(
			"section"	=>	$section,
		))->field("id_c");
		
		$res = $this->m("CategoriesModel")->recursiveTree($idShopCat,2);
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
		
		$this->m("DocumentiModel")->clear()->select("distinct documenti.id_doc,documenti.*")->where(array(
			"id_doc"	=>	(int)$id,
		));
		
		if (v("attiva_gruppi_documenti"))
			$this->m("DocumentiModel")->addAccessoGruppiWhereClase();
		
		$documento = $this->m("DocumentiModel")->record();
		
		if (!empty($documento))
		{
			if ($this->m("DocumentiModel")->checkAccessoUtente($documento["id_doc"]))
			{
				$path = $this->m("DocumentiModel")->getFolderBasePath("filename")."/images/documenti/".$documento['filename'];
				
				if (file_exists($path))
				{
					// Salva il download
					$idDownload = $this->m("DocumentidownloadModel")->salvaDownload((int)$id);
					
					// Azione che viene eseguita dopo il download del documento
					$this->azioneDopoDownloadDocumento((int)$id, (int)$idDownload);
					
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
		else
			$this->redirect("");
	}
	
	protected function azioneDopoDownloadDocumento($idDoc, $idDownload)
	{
		
	}
	
	public function recuperacarrello($c = "")
	{
		$this->clean();
		
		if (is_string($c) && trim(v("token_recupera_carrello")) && (string)$c === (string)v("token_recupera_carrello"))
		{
			$clean["cart_uid"] = $this->request->get("cart_uid","","sanitizeAll");
			
			if (trim($clean["cart_uid"]))
			{
				if ($this->m("CartModel")->clear()->where(array(
					"cart_uid"	=>	$clean["cart_uid"],
				))->rowNumber())
				{
					$time = time() + v("durata_carrello_wishlist_coupon");
					setcookie("cart_uid",$clean["cart_uid"],$time,"/");
				
					$this->redirect("checkout");
				}
			}
		}
		
		$this->redirect("");
	}
	
	public function processaschedulazione($c = "")
	{
		$this->clean();
		
		if (is_string($c) && trim(v("token_schedulazione")) && (string)$c === (string)v("token_schedulazione"))
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
		$redirect = RegusersModel::getRedirect();
		
		if (VariabiliModel::checkToken("var_query_string_no_cookie"))
		{
			App::cancellaCookiesGdpr();
			
			$allCookies = isset($_GET["all_cookie"]) ? true : false;
			
			if (!$allCookies)
			{
// 				$cookieTecnici = App::getCookieTecnici();
// 				
// 				foreach ($_COOKIE as $name => $value)
// 				{
// 					if (!isset($cookieTecnici[$name]))
// 					{
// 						setcookie($name,"OK",(time()-3600),"/",ltrim(DOMAIN_NAME,"www"));
// 						unset($_COOKIE[$name]);
// 					}
// 				}
			}
			
			App::settaCookiesGdpr($allCookies);
		}
		
		$urlRedirect = RegusersModel::getUrlRedirect();
		
		if ($urlRedirect)
			header('Location: '.$urlRedirect);
		else
			$this->redirect("");
	}
	
	public function confermacontatto($token = 0)
	{
		$this->clean();
		
		$clean["token"] = sanitizeAll($token);
		
		if (!trim((string)$clean["token"]) || !v("attiva_verifica_contatti"))
			$this->redirect("");
		
		$limit = time() - (int)v("tempo_conferma_uid_contatto");
		
		$contatto = $this->m("ContattiModel")->clear()->where(array(
			"uid_contatto"	=> $clean["token"],
			"gt"	=>	array(
				"time_conferma"	=>	(int)$limit,
			),
		))->record();
		
		if (!empty($contatto))
		{
			$this->m("ContattiModel")->settaCookie($clean["token"]);
			
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
		$this->redirect("");
	}
	
	public function listaregalo($codice = "", $alias = "")
	{
		if (isset($_GET["codice_lista"]))
			$codice = (string)$_GET["codice_lista"];
		
		if (!v("attiva_liste_regalo") || !trim($codice))
			$this->redirect("");
		
		$data["loadJsListe"] = true;
		
		$clean["codice"] = sanitizeAll($codice);
		
		$lista = $data["lista"] = $this->m("ListeregaloModel")->clear()->select("*")->inner(array("tipo"))->where(array(
			"codice"	=>	$clean["codice"],
			"attivo"	=>	"Y",
			"gte"	=>	array(
				"data_scadenza"	=>	date("Y-m-d"),
			),
		))->first();
		
		if (count($lista) > 0)
		{
			$data["title"] = Parametri::$nomeNegozio . " - ".gtext("lista")." ".$lista["liste_regalo"]["titolo"];
			
			$data["meta_description"] = $lista["liste_regalo"]["titolo"];
			
			$data["prodotti_lista"] = $this->m("ListeregaloModel")->getProdottiOrdinatiPerFrontend($lista["liste_regalo"]["id_lista_regalo"]);
			
			if (!empty($lista["liste_regalo"]) && in_array($lista["liste_regalo"]["id_lista_tipo"], ListeregalotipiModel::campoPresenteInTipi("sesso","")))
				$data["sessoLista"] = $lista["liste_regalo"]["sesso"];
			
			$this->append($data);
			$this->load('lista-regalo');
		}
		else
		{
			$idNonEsistente = PagineModel::gTipoPagina("LISTA_REGALO_NE");
			
			if ($idNonEsistente)
				$this->redirect(getUrlAlias($idNonEsistente));
			else
				$this->redirect("");
		}
	}
}
