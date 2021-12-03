<?php

if (!defined('EG')) die('Direct access not allowed!');

require_once(LIBRARY."/Application/Controllers/Public/BaseFasceController.php");

trait FasceController
{
	use BaseFasceController;
	
	public function getFasciaPaccoRegaloBianca()
	{
		ob_start();
		include tpf("Fasce/fascia_pacco_regalo_bianca.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaFuroshikiCategoria()
	{
		ob_start();
		$output = '<div class="uk-margin-large uk-position-relative uk-visible-toggle" tabindex="-1" uk-slideshow="min-height: 400; max-height: 700; animation: push">';
		include tpf("Fasce/fascia_furoshiki.php");
		$output .= ob_get_clean();
		$output .= "</div>";
		
		return $output;
	}
	
	public function getFasciaFuroshikiHome()
	{
		ob_start();
		$output = '<div class="uk-margin-large-bottom uk-position-relative uk-visible-toggle" tabindex="-1" uk-slideshow="min-height: 400; max-height: 700; animation: push">';
		include tpf("Fasce/fascia_furoshiki.php");
		$output .= ob_get_clean();
		$output .= "</div>";
		
		return $output;
	}
	
	public function getFasciaTateCategoria()
	{
		ob_start();
		$output = '<div class="uk-margin-large uk-position-relative uk-visible-toggle" tabindex="-1" uk-slideshow="min-height: 400; max-height: 700; animation: push">';
		include tpf("Fasce/fascia_tate.php");
		$output .= ob_get_clean();
		$output .= "</div>";
		
		return $output;
	}
	
	public function getFasciaTateHome()
	{
		ob_start();
		$output = '<div class="uk-margin-large-bottom uk-position-relative uk-visible-toggle" tabindex="-1" uk-slideshow="min-height: 400; max-height: 700; animation: push">';
		include tpf("Fasce/fascia_tate.php");
		$output .= ob_get_clean();
		$output .= "</div>";
		
		return $output;
	}
	
	public function getFasciaBalloonCategoria()
	{
		ob_start();
		$output = '<div class="uk-margin-large uk-position-relative uk-visible-toggle" tabindex="-1" uk-slideshow="min-height: 400; max-height: 700; animation: push">';
		include tpf("Fasce/fascia_balloon.php");
		$output .= ob_get_clean();
		$output .= "</div>";
		
		return $output;
	}
	
	public function getFasciaBalloonHome()
	{
		ob_start();
		$output = '<div class="uk-margin-large-bottom uk-position-relative uk-visible-toggle" tabindex="-1" uk-slideshow="min-height: 400; max-height: 700; animation: push">';
		include tpf("Fasce/fascia_balloon.php");
		$output .= ob_get_clean();
		$output .= "</div>";
		
		return $output;
	}
	
	public function getFasciaLaNostraFilosofia()
	{
		ob_start();
		include tpf("Fasce/fascia_la_nostra_filosofia.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaFuroshikiTop()
	{
		ob_start();
		include tpf("Fasce/fascia_furoshiki_top.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaTateTop()
	{
		ob_start();
		include tpf("Fasce/fascia_tate_top.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaBalloonTop()
	{
		ob_start();
		include tpf("Fasce/fascia_balloon_top.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaTitoloIconaLinea()
	{
		ob_start();
		include tpf("Fasce/fascia_titolo_icona_linea.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaTitoloIconaLineaH1()
	{
		ob_start();
		include tpf("Fasce/fascia_titolo_icona_linea_h1.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getProdottiIdCat($idCat)
	{
		return $this->m['PagesModel']->clear()->select("*")
			->addJoinTraduzionePagina()
			->aWhere(CategoriesModel::gCatWhere((int)$idCat, true))->addWhereAttivo()
			->addWhereAttivo()
			->orderBy("pages.id_order")
			->send();
	}
	
	public function getFasciaCaroselloProdottiFuroshiki()
	{
		$idCat = 114;
		$pages = $this->getProdottiIdCat($idCat);
		
		ob_start();
		include tpf("Fasce/fascia_carosello_prodotti.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaCaroselloProdottiTate()
	{
		$idCat = 130;
		$pages = $this->getProdottiIdCat($idCat);
		
		ob_start();
		include tpf("Fasce/fascia_carosello_prodotti.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaCaroselloProdottiBalloon()
	{
		$idCat = 129;
		$pages = $this->getProdottiIdCat($idCat);
		
		ob_start();
		include tpf("Fasce/fascia_carosello_prodotti.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getFasciaVaiAlleFaq()
	{
		ob_start();
		include tpf("Fasce/fascia_vai_alle_faq.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
}
