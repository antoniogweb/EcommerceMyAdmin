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

trait Modulo
{
	protected $params = array();
	
	protected $cacheAbsolutePath = null;
	protected $logsFolder = "Logs";
	protected $cacheFolder = "Cache";
	
	public function __construct($record = array())
	{
		$this->params = $record;
		
		$this->cacheAbsolutePath = rtrim(LIBRARY,"/");
		
		if (!@is_dir($this->cacheAbsolutePath."/".$this->logsFolder))
		{
			createFolderFull($this->logsFolder, $this->cacheAbsolutePath);
			@chmod($this->cacheAbsolutePath."/".$this->logsFolder, octdec('777'));
		}
		
		$this->cacheAbsolutePath .= "/".$this->logsFolder;
		
		if (isset($this->params["codice"]) && trim($this->params["codice"]))
		{
			$moduleFullPath = $this->cacheAbsolutePath."/".trim($this->params["codice"]);
			
			// Controllo e in caso creo la cartella del modulo
			if (!@is_dir($moduleFullPath))
			{
				createFolderFull(trim($this->params["codice"]), $this->cacheAbsolutePath);
			}
			
			// Controllo e in caso creo la cartella per la Cache del modulo
			if (isset($this->params["tempo_cache"]) && $this->params["tempo_cache"] > 0 && @is_dir($moduleFullPath) && !@is_dir($moduleFullPath."/".$this->cacheFolder))
			{
				createFolderFull($this->cacheFolder, $moduleFullPath);
			}
		}
	}
	
	public function getParams()
	{
		return $this->params;
	}
	
	public function gCampiForm()
	{
		return 'titolo,attivo,usa_token_sicurezza,token_sicurezza,query_string';
	}
	
	public function isAttivo()
	{
		return $this->params["attivo"];
	}
	
	protected function getCacheFolderPath()
	{
		return $this->cacheAbsolutePath."/".trim($this->params["codice"])."/".$this->cacheFolder;
	}
	
	protected function scaduto($cachedFile)
	{
		$time = time() - (int)$this->params["tempo_cache"];
		
		return (filemtime($cachedFile) < $time);
	}
	
	protected function getFromCache($signature)
	{
		$cachedFile = $this->getCacheFolderPath()."/".$signature.".log";
		
		if (@is_file($cachedFile) && !$this->scaduto($cachedFile))
			return unserialize(file_get_contents($cachedFile));
		
		return false;
	}
	
	protected function cleanCache()
	{
		F::deleteFilesOlderThanXSecs($this->getCacheFolderPath(), (int)$this->params["tempo_cache"], "/*.{log,php}", GLOB_BRACE);
	}
	
	protected function saveInCache($signature, $data)
	{
		$this->cleanCache();
		
		$cacheFolderPath = $this->getCacheFolderPath();
		
		if (@is_dir($cacheFolderPath))
		{
			// controllo che non abbia superato il numero massimo di elementi in cache
			if (isset($this->params["massimo_numero_di_ricerche_in_cache"]))
			{
				$iterator = new FilesystemIterator($cacheFolderPath, FilesystemIterator::SKIP_DOTS);
				$numberOfFilesCached = iterator_count($iterator);
				
				if ($numberOfFilesCached >= $this->params["massimo_numero_di_ricerche_in_cache"])
					return;
			}
			
			FilePutContentsAtomic($cacheFolderPath."/".$signature.".log", serialize($data));
// 			FilePutContentsAtomic($cacheFolderPath."/".$signature.".php", "<?php\nreturn ".var_export($data, true).';');
		}
	}
	
	public function strutturaFeedProdotti($p = null, $idPage = 0, $idC = 0, $combinazioniLinkVeri = null, $cacheTime = 0)
	{
		$c = new CategoriesModel();
		$comb = new CombinazioniModel();
		$pCats = new PagescategoriesModel();
		$cart = new CartModel();
		$corr = new CorrieriModel();
		$corrSpese = new CorrierispeseModel();
		$avModel = new AttributivaloriModel();
		
		if (!isset($combinazioniLinkVeri))
			$combinazioniLinkVeri = VariabiliModel::combinazioniLinkVeri();
		
		$corrieri = $corr->clear()->select("distinct corrieri.id_corriere,corrieri.*")->inner("corrieri_spese")->on("corrieri.id_corriere = corrieri_spese.id_corriere")->orderBy("corrieri.id_order")->send(false);
		
		$idCorriere = 0;
		
		if (count($corrieri) > 0)
			$idCorriere = $corrieri[0]["id_corriere"];
		
		$nazione = User::$nazione ? User::$nazione : v("nazione_default");
		
		if (!isset($p))
		{
			$p = new PagesModel();
			$p->clear();
		}
		
		$idShop = $c->getShopCategoryId();
		
		$children = $c->children($idShop, true);
		
		if ($idPage)
			$p->aWhere(array(
				"id_page"	=>	(int)$idPage,
			));
		
		if ($idC)
			$p->aWhere(array(
				"combinazioni.id_c"	=>	(int)$idC,
			));
		
		$select = "distinct pages.codice_alfa,pages.title,pages.description,categories.title,categories.description,pages.id_page,pages.id_c,pages.immagine,contenuti_tradotti.title,contenuti_tradotti_categoria.title,contenuti_tradotti.description,contenuti_tradotti_categoria.description,pages.gift_card,pages.peso,marchi.id_marchio,marchi.titolo,pages.al,pages.sottotitolo,contenuti_tradotti.sottotitolo,categories.id_corriere,pages.campo_cerca,pages.id_marchio";
		
		if ($combinazioniLinkVeri || $idC)
		{
			$select .= ",combinazioni.*";
			
			$p->inner(array("combinazioni"))->aWhere(array(
				"combinazioni.acquistabile"	=>	1,
			));
		}
		
		$p->select($select)
			->addWhereAttivo()
			->addJoinTraduzionePagina()
			->left(array("marchio"))
			->addWhereCategoria((int)$idShop)
			->orderBy("pages.title");
		
		$signature = "";
		
		// controllo che ci siano dati in cache
		if ($cacheTime > 0)
		{
			$signature = md5($p->signature());
			
			$cachedData = $this->getFromCache($signature);
			
			if ($cachedData !== false)
				return $cachedData;
		}
		
		$res = $p->send();
		
		$res = PagesModel::impostaDatiCombinazionePagine($res);
		
		$strutturaFeed = array();
		
		foreach ($res as $r)
		{
			$idC = isset($r["combinazioni"]["id_c"]) ? (int)$r["combinazioni"]["id_c"] : $p->getIdCombinazioneCanonical((int)$r["pages"]["id_page"]);
			
			$titoloCombinazione = $combinazioniLinkVeri ? " ".$comb->getTitoloCombinazione($r["combinazioni"]["id_c"]) : "";
			
			$arrayIdCat = array($r["pages"]["id_c"]);
			
// 			$arrayIdCat = array_merge(array($r["pages"]["id_c"]), $pCats->clear()->select("pages_categories.id_c")->where(array(
// 				"id_page"	=>	(int)$r["pages"]["id_page"],
// 				"genitore"	=>	0,
// 			))->inner(array("categoria"))->orderBy("categories.lft")->toList("pages_categories.id_c")->send());
// 			
// // 			print_r($arrayIdCat);
// 			
// 			$arrayIdCat = array_unique($arrayIdCat);
			
			$structCategory = array();
			
			foreach ($arrayIdCat as $idCat)
			{
				$parents = $c->parents((int)$idCat, false, false, Params::$lang, "coalesce(contenuti_tradotti.title,categories.title) as titolo", 2);
				$parents = $c->getList($parents, "aggregate.titolo");
				
				if (count($parents) > 0)
					$structCategory[] = $parents;
			}
			
			$prezzoMinimo = $p->prezzoMinimo($r["pages"]["id_page"], false, $idC);
			$prezzoMinimoIvato = calcolaPrezzoIvato($r["pages"]["id_page"],$prezzoMinimo);
			
			$prezzoFinale = $cart->calcolaPrezzoFinale($r["pages"]["id_page"], $prezzoMinimo, 1, true, true, $idC);
			$prezzoFinaleIvato = calcolaPrezzoIvato($r["pages"]["id_page"],$prezzoFinale);
			
			$inPromo = 0;
			
			if (number_format($prezzoMinimoIvato,2,".","") != number_format($prezzoFinaleIvato,2,".",""))
				$inPromo = 1;
			
			if ($p->inPromozione($r["pages"]["id_page"]))
				$now = DateTime::createFromFormat('Y-m-d', $r["pages"]["al"]);
			else
			{
				$now = new dateTime();
				$now->modify("+10 days");
			}
			
			$ivaSpedizione = CartModel::getAliquotaIvaSpedizione();
			
			if ($r["pages"]["gift_card"])
				$speseSpedizione = 0;
			else
			{
				$subtotaleSpedizione = (!v("prezzi_ivati_in_carrello")) ? $prezzoFinale : $prezzoFinaleIvato;
				
				// Solo spedizioni gratuite e solo nazione default
				if (ImpostazioniModel::$valori["spedizioni_gratuite_sopra_euro"] > 0 && $subtotaleSpedizione >= ImpostazioniModel::$valori["spedizioni_gratuite_sopra_euro"])
					$speseSpedizione = 0;
				else
				{
					if (v("scegli_il_corriere_dalla_categoria_dei_prodotti") && $r["categories"]["id_corriere"])
						$idCorriere = $r["categories"]["id_corriere"];
					
					$speseSpedizione = $corrSpese->getPrezzo($idCorriere,$r["pages"]["peso"], $nazione);
				}
			}
			
			$temp = array(
				"id_page"	=>	$r["pages"]["id_page"],
				"id_comb"	=>	$idC,
				"id_c"		=>	$r["pages"]["id_c"],
				"id_marchio"	=>	$r["pages"]["id_marchio"],
				"titolo"	=>	trim(field($r, "title").$titoloCombinazione),
				"codice"	=>	isset($r["combinazioni"]["codice"]) ? $r["combinazioni"]["codice"] : $r["pages"]["codice"],
				"sottotitolo"	=>	trim(field($r, "sottotitolo")),
				"descrizione"	=>	trim(field($r, "description")),
				"campo_cerca"	=>	$r["pages"]["campo_cerca"],
				"categorie"	=>	$structCategory,
				"immagine_principale"	=>	$r["pages"]["immagine"],
				"altre_immagini"	=>	ImmaginiModel::altreImmaginiPagina((int)$r["pages"]["id_page"], $idC),
				"link"		=>	Url::getRoot().getUrlAlias((int)$r["pages"]["id_page"], $idC),
				"prezzo_pieno"	=> number_format($prezzoMinimoIvato,2,".",""),
				"in_promo"	=>	$inPromo,
				"data_scadenza_promo"	=>	$now->format("Y-m-d"),
				"prezzo_scontato"	=> number_format($prezzoFinaleIvato,2,".",""),
				"spese_spedizione"	=>	number_format($speseSpedizione * (1 + ($ivaSpedizione / 100)),2,".",""),
				"marchio"	=>	$r["marchi"]["titolo"],
				"peso"		=>	$r["pages"]["peso"],
				"giacenza"	=>	PagesModel::disponibilita($r["pages"]["id_page"],$idC),
				"gtin"		=>	$r["pages"]["gtin"],
				"mpn"		=>	$r["pages"]["mpn"],
				"id_corriere"	=>	$r["categories"]["id_corriere"],
			);
			
			if ($combinazioniLinkVeri)
			{
				$attributi = $avModel->getArrayIdTipologia($idC);
				
				$attrStruct = [];
				
				foreach ($attributi as $attr)
				{
					$attrStruct[] = array(
						"tipologia"	=>	$attr["tipologie_attributi"]["titolo"],
						"titolo"	=>	$attr["attributi"]["titolo"],
						"valore"	=>	$attr["attributi_valori"]["titolo"],
					);
				}
				
				$temp["attributi"] = $attrStruct;
			}
			
			$strutturaFeed[] = $temp;
		}
		
		// Controllo e in caso salvo in cache
		if ($cacheTime > 0 && $signature)
			$this->saveInCache($signature, $strutturaFeed);
		
// 		print_r($strutturaFeed);die();
		
		return $strutturaFeed;
	}
}
