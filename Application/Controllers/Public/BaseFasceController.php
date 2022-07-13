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

trait BaseFasceController
{
	public function getProdotti()
	{
		if (!isset($this->pages))
			return "";
		
		$pages = $this->pages;
		
		ob_start();
		include ROOT."/Application/Views/Contenuti/Elementi/Categorie/blocco_prodotti.php";
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getProdottiInEvidenza()
	{
		if (!isset($this->prodottiInEvidenza))
			return "";
		
		$pages = $prodottiInEvidenza = $this->prodottiInEvidenza;
		$elencoMarchiFull = isset($this->elencoMarchiFull) ? $this->elencoMarchiFull : array();
		$idShop = $this->idShop;
		
		ob_start();
		include tpf("Fasce/prodotti_in_evidenza.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getNewsInEvidenza()
	{
		if (!isset($this->getNewsInEvidenza))
			return "";
		
		$pages = $ultimiArticoli = $this->getNewsInEvidenza;
		
		$idBlog = $data["idBlog"] = (int)$this->m["CategoriesModel"]->clear()->where(array(
			"section"	=>	"blog",
		))->field("id_c");
	
		ob_start();
		include tpf("Fasce/ultimi_articoli.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getTeam()
	{
		if (!isset($this->team))
			return "";
		
		$pages = $this->team;
		
		ob_start();
		include tpf("Fasce/team.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getTestimonial()
	{
		$pages = $this->testimonial;
		
		ob_start();
		include tpf("Fasce/testimonial.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getSlideProdotto()
	{
		if (!isset($this->pages))
			return "";
		
		$pages = $this->pages;
		$p = $this->p;
		$altreImmagini = $this->altreImmagini;
		
		ob_start();
		include tpf("Fasce/slide_prodotto.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getCarrelloProdotto()
	{
		if (!isset($this->pages))
			return "";
		
		$pages = $this->pages;
		$p = $this->p;
		
		$lista_attributi = $this->lista_attributi;
		$lista_valori_attributi = $this->lista_valori_attributi;
		$scaglioni = $this->scaglioni;
		$prezzoMinimo = $this->prezzoMinimo;
		
		ob_start();
		include tpf("Elementi/Pagine/carrello_prodotto.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getSlide()
	{
		if (!isset($this->slide))
		{
			$this->slide = $this->m["PagesModel"]->clear()->addJoinTraduzionePagina()
			->where(array(
				"categories.section"	=>	"slide",
				"attivo"=>"Y",
				"in_evidenza"	=>	"Y",
			))->orderBy(v("main_slide_order"))->send();
		}
		
		$pages = $slide = $this->slide;
		
		ob_start();
		include tpf("Fasce/slide_principale.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getCaroselloCategorie()
	{
		if (!isset($this->elencoCategorieFull))
			return "";
		
		$elencoCategorieFull = $this->elencoCategorieFull;
		
		ob_start();
		include tpf("Fasce/carosello_categorie.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getCategorieFascia()
	{
		if (!isset($this->elencoCategorieFull))
			return "";
		
		$elencoCategorieFull = $this->elencoCategorieFull;
		
		ob_start();
		include tpf("Fasce/fascia_categorie.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaNewsletter()
	{
		ob_start();
		include tpf("Fasce/fascia_newsletter.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFaqInEvidenza()
	{
		$pages = $this->faq;
		
		ob_start();
		include tpf("Fasce/fascia_faq.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getGalleryFascia()
	{
		$idTest = (int)$this->m["CategoriesModel"]->clear()->where(array(
			"section"	=>	"gallery",
		))->field("id_c");
		
		$pages = $this->m['PagesModel']->clear()->select("*")
			->addJoinTraduzionePagina()
			->where(array(
				"attivo"	=>	"Y",
				"id_c"		=>	(int)$idTest,
			))->orderBy("pages.id_order")->send();
		
		ob_start();
		include tpf("Fasce/fascia_gallery.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getEventiFascia()
	{
// 		$idTest = (int)$this->m["CategoriesModel"]->clear()->where(array(
// 			"section"	=>	"eventi",
// 		))->field("id_c");
// 		
// 		$pages = $this->m['PagesModel']->clear()->select("*")
// 			->addJoinTraduzionePagina()
// 			->where(array(
// 				"attivo"	=>	"Y",
// 				"id_c"		=>	(int)$idTest,
// 			))->limit(v("numero_eventi_home"))->orderBy("pages.data_inizio_evento desc, pages.ora_inizio_evento, pages.data_fine_evento desc, pages.ora_fine_evento,pages.data_news desc")->send();
		
		$pages = EventiModel::getElementiFascia();
		
		ob_start();
		include tpf("Fasce/fascia_eventi.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaMarchi()
	{
		$pages = $this->m["MarchiModel"]->clear()->addJoinTraduzione()->orderBy("marchi.id_order")->send();
		
		ob_start();
		include tpf("Fasce/fascia_marchi.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaTag()
	{
		$pages = $this->m["TagModel"]->clear()->addJoinTraduzione()->orderBy("tag.id_order")->send();
		
		ob_start();
		include tpf("Fasce/fascia_tag.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaCaroselloMarchi()
	{
		$marchi = $elencoMarchiFull = $this->m["MarchiModel"]->clear()->addJoinTraduzione()->orderBy("marchi.id_order")->send();
		
		ob_start();
		include tpf("Fasce/fascia_carosello_marchi.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaMarchiNuovi()
	{
		$pages = $elencoMarchiNuoviFull = $this->m["MarchiModel"]->clear()->addJoinTraduzione()->where(array(
			"nuovo"	=>	"Y",
		))->orderBy("marchi.id_order")->send();
		
		ob_start();
		include tpf("Fasce/fascia_marchi_nuovi.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaFormContatti()
	{
		ob_start();
		include tpf("Fasce/fascia_form_contatti.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaFormFeedback()
	{
		ob_start();
		include tpf("Fasce/fascia_form_feedback.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaInfoSpedizioni()
	{
		ob_start();
		include tpf("Fasce/fascia_spedizioni.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaInfoSpedizioniResi()
	{
		ob_start();
		include tpf("Fasce/fascia_spedizioni_resi.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaInfoPagamenti()
	{
		ob_start();
		include tpf("Fasce/fascia_pagamenti.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaProdottiInPromozione()
	{
		$inPromozione = $this->prodottiInPromozione;
		
		ob_start();
		include tpf("Fasce/prodotti_in_promozione.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaChiSiamo()
	{
		ob_start();
		include tpf("Fasce/fascia_chi_siamo.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaPaccoRegalo()
	{
		ob_start();
		include tpf("Fasce/fascia_pacco_regalo.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaServizi()
	{
		ob_start();
		include tpf("Fasce/fascia_servizi.php");
		return ob_get_clean();
	}
	
	public function getFasciaSedi()
	{
		ob_start();
		include tpf("Fasce/fascia_sedi.php");
		return ob_get_clean();
	}
	
	public function getFasciaProgetti()
	{
		ob_start();
		include tpf("Fasce/fascia_progetti.php");
		return ob_get_clean();
	}
}
