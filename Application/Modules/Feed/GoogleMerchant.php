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

class GoogleMerchant extends Feed
{
	public $isFbk = false;
	
	public function feedProdotti($p = null, $outputFile = null)
	{
		$linkAlleVarianti = $this->linkAlleVarianti();
		
		if (!isset($p))
		{
			$p = new PagesModel();
			$p->clear();
		}
		
		$o = new OpzioniModel();
		
		$etichettePersonalizzate = $o->clear()->where(array(
			"codice"	=>	"ETICHETTE_FEED_GOOGLE",
			"attivo"	=>	1,
		))->orderBy("id_order")->toList("valore")->send();
		
		$strutturaFeedProdotti = $this->strutturaFeedProdotti($p, 0, 0, $linkAlleVarianti, (int)$this->params["tempo_cache"]);
		
		$prodotti = array();
		
		$outOfStock = v("attiva_giacenza") ? "out of stock" : "in stock";
		
		foreach ($strutturaFeedProdotti as $r)
		{
			$idElemento = $linkAlleVarianti ? $r["id_comb"] : $r["id_page"];
			
			$temp = array(
				"g:id"	=>	(v("usa_sku_come_id_item") && VariabiliModel::combinazioniLinkVeri()) ? $r["codice"] : $idElemento,
				"g:title"	=>	F::alt($r["titolo"], ENT_COMPAT),
				"g:link"	=>	$r["link"],
				"g:price"	=>	number_format($r["prezzo_pieno"],2,".",""). " EUR",
				"g:availability"	=>	$r["giacenza"] > 0 ? "in stock" : $outOfStock,
			);
			
			$temp["g:identifier_exists"] = $r["identifier_exists"] ? $r["identifier_exists"] : v("identificatore_feed_default");
			
// 			if (!$this->isFbk)
// 			{
				if ($r["gtin"])
				{
					$temp["g:gtin"] = htmlentitydecode($r["gtin"]);
					$temp["g:identifier_exists"] = "yes";
				}
				
				// Controlla se deve stampare il GTIN
				if (!$r["stampa_gtin_nel_feed"])
				{
					unset($temp["g:gtin"]);
					unset($temp["g:identifier_exists"]);
				}
				
				if ($r["mpn"])
					$temp["g:mpn"] = htmlentitydecode($r["mpn"]);
				
				if (v("usa_codice_articolo_su_mpn_google_facebook"))
					$temp["g:mpn"] = htmlentitydecode($r["codice"]);
// 			}
			
			if (!$this->isFbk || v("no_tag_descrizione_feed"))
				$temp["g:description"] = strip_tags(htmlentitydecode(F::sanitizeXML($r["descrizione"])));
			else
				$temp["g:description"] = htmlspecialchars(htmlentitydecode(F::sanitizeXML($r["descrizione"])), ENT_QUOTES, "UTF-8");
			
			if (v("elimina_emoticons_da_feed"))
			{
				$temp["g:title"] = F::removeEmoji($temp["g:title"]);
				$temp["g:description"] = F::removeEmoji($temp["g:description"]);
			}
			
			if (count($r["categorie"]) > 0)
			{
				if (!$this->isFbk)
					$temp["g:product_type"] = implode(" &gt; ", htmlentitydecodeDeep($r["categorie"][0]));
				else if ($r["categorie"][0] > 0)
					$temp["g:product_type"] = htmlentitydecode($r["categorie"][0][0]);
			}
			
			$parents = $p->parents((int)$r["id_page"], false, false, true);
			
			$codiceGoogle = $p->getFirstNotEmpty($r["id_page"], "codice_categoria_prodotto_google", $parents);
			
			if ($codiceGoogle)
				$temp["g:google_product_category"] = $codiceGoogle;
			else
				$temp["g:google_product_category"] = htmlentitydecode(cfield($r,"title"));
			
			if ($r["immagine_principale"])
				$temp["g:image_link"] = Url::getRoot()."thumb/dettagliobig/".$r["immagine_principale"];
			
			if (count($r["altre_immagini"]) > 0)
			{
				$temp["g:additional_image_link"] = array();
				
				$count = 0;
				
				$numeroLimite = $this->isFbk ? 20 : 10;
				
				foreach ($r["altre_immagini"] as $img)
				{
					if ($count >= $numeroLimite)
						break;
					
					$temp["g:additional_image_link"][] = Url::getRoot()."thumb/dettagliobig/".$img["immagine"];
					
					$count++;
				}
				
				if ($this->isFbk)
					$temp["g:additional_image_link"] = implode(",",$temp["g:additional_image_link"]);
			}
			
			if ($r["marchio"])
				$temp["g:brand"] = htmlentitydecode($r["marchio"]);
			
			if ($this->isFbk)
			{
				$temp["condition"] = "new";
				
				if ($this->params["default_gender"])
					$temp["gender"] = $this->params["default_gender"];
				
				if ($this->params["default_age_group"])
					$temp["age_group"] = $this->params["default_age_group"];
				
				if ($this->params["campo_per_item_group_id"] && isset($r[$this->params["campo_per_item_group_id"]]) && $r[$this->params["campo_per_item_group_id"]])
					$temp["g:item_group_id"] = $r[$this->params["campo_per_item_group_id"]];
			}
			
			if ($r["in_promo"])
			{
				$temp["g:sale_price"] = number_format($r["prezzo_scontato"],2,".",""). " EUR";
				$temp["g:sale_price_effective_date"] = date("c",strtotime($r["data_inizio_promo"]))."/".date("c",strtotime($r["data_scadenza_promo"]." 23:59:00"));
				
				$r["pages"]["in_promo_feed"] = true;
			}
			
			if (v("aggiungi_dettagli_spedizione_al_feed") && v("attiva_spedizione"))
			{
// 				$country = isset(Params::$country) ? strtoupper(Params::$country) : v("nazione_default");
				
				$temp["g:shipping"]["g:country"] = $r["nazione"];
				$temp["g:shipping"]["g:service"] = F::alt($r["nome_corriere"], ENT_NOQUOTES);
				$temp["g:shipping"]["g:price"] = $r["spese_spedizione"]." EUR";
			}
			
			if (!$this->isFbk && count($etichettePersonalizzate) > 0)
			{
				$page = $p->selectId((int)$r["id_page"]);
				$indiceEtichetta = 0;
				
				foreach ($etichettePersonalizzate as $etP)
				{
					if (method_exists($p, $etP))
					{
						$temp["g:custom_label_".$indiceEtichetta] = call_user_func_array(array($p, $etP), array($r["id_page"], $page));
						
						$indiceEtichetta++;
					}
				}
			}
			
			$prodotti[] = $temp;
		}
		
		$xmlArray = array();
		
		$wrap = array();
		
		if (file_exists(tpf("/Elementi/Feed/".$this->params["modulo"].".php")))
			include(tpf("/Elementi/Feed/".$this->params["modulo"].".php"));
		else
		{
			$itemTagName = $this->params["node_tag_name"];
			
			$xmlArray["channel"] = array(
				"title"	=>	htmlentitydecode(ImpostazioniModel::$valori["title_home_page"]),
				"link"	=>	Url::getRoot(),
				"description"	=>	htmlentitydecode(ImpostazioniModel::$valori["meta_description"]),
				"$itemTagName"	=>	$prodotti,
			);
			
			if (v("elimina_emoticons_da_feed"))
			{
				$xmlArray["channel"]["title"] = F::removeEmoji($xmlArray["channel"]["title"]);
				$xmlArray["channel"]["description"] = F::removeEmoji($xmlArray["channel"]["description"]);
			}
			
			
		}
		
// 		print_r($xmlArray);
		
		$xml = aToX($xmlArray);
		
		F::xml($xml, $wrap, $outputFile);
	}
}
