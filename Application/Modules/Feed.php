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

class Feed
{
	protected $usato = false;
	
	protected $params = "";
	
	public function __construct($record)
	{
		$this->params = $record;
	}
	
	public function getParams()
	{
		return $this->params;
	}
	
	public function gCampiForm()
	{
		return 'titolo,attivo,usa_token_sicurezza,token_sicurezza';
	}
	
	public function isAttivo()
	{
		return $this->params["attivo"];
	}
	
	protected function strutturaFeedProdotti($p = null)
	{
		$c = new CategoriesModel();
		$comb = new CombinazioniModel();
		
		if (!isset($p))
		{
			$p = new PagesModel();
			$p->clear();
		}
		
		$idShop = $c->getShopCategoryId();
		
		$children = $c->children($idShop, true);
		
		if (isset($_GET["id_page"]))
			$p->aWhere(array(
				"id_page"	=>	(int)$_GET["id_page"],
			));
		
		$select = "distinct pages.codice_alfa,pages.title,pages.description,categories.title,categories.description,pages.id_page,pages.id_c,contenuti_tradotti.title,contenuti_tradotti_categoria.title,contenuti_tradotti.description,contenuti_tradotti_categoria.description";
		
		if (VariabiliModel::combinazioniLinkVeri())
			$select .= ",combinazioni.*";
		
		$p->select($select)
			->addWhereAttivo()
			->addJoinTraduzionePagina()
			->addWhereCategoria((int)$idShop)
			->orderBy("pages.title");
		
		if (VariabiliModel::combinazioniLinkVeri())
			$p->inner(array("combinazioni"))->aWhere(array(
				"combinazioni.acquistabile"	=>	1,
			));
		
		$res = $p->send();
		
		if (!VariabiliModel::combinazioniLinkVeri())
			$res = PagesModel::impostaDatiCombinazionePagine($res);
		
		$strutturaFeed = array();
		
		foreach ($res as $r)
		{
			$titoloCombinazione = VariabiliModel::combinazioniLinkVeri() ? " ".$comb->getTitoloCombinazione($r["combinazioni"]["id_c"]) : "";
			
			$strutturaFeed[] = array(
				"id_page"	=>	$r["pages"]["id_page"],
				"titolo"	=>	trim(F::alt(field($r, "title").$titoloCombinazione)),
				"codice"	=>	isset($r["combinazioni"]["codice"]) ? $r["combinazioni"]["codice"] : $r["pages"]["codice"],
				"descrizione"	=>	trim(F::alt(field($r, "description"))),
			);
		}
		
		return $strutturaFeed;
	}
}
