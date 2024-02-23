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

if (!defined('EG')) die('Direct access not allowed!');

class PagesassociateModel extends GenericModel {

	public function __construct() {
		$this->_tables='pages_associate';
		$this->_idFields='id_page_associata';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'page' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
        );
    }
    
    public function getPagineAssociate()
    {
		return $this->clear()->query('select count(rc.id_page) as numero,righe.id_page as ID_PAGINA,pages.title as TITOLO,rc.id_page as ID_PAGINA_ASSOCIATA,rc.title as TITOLO_ASSOCIATO from righe inner join pages on pages.id_page = righe.id_page inner join orders on orders.id_o = righe.id_o inner join righe as rc on rc.id_o = righe.id_o and righe.id_r != rc.id_r where righe.id_o in (SELECT righe.id_o as numero FROM `righe` group by righe.id_o having count(id_r) > 1 order by count(id_r) desc) and stato != "deleted" and stato != "ACQUISTATO_NEGOZIO" and stato != "pending" and stato != "rimborsato" and rc.id_page != 0 and righe.id_page != rc.id_page group by rc.id_page,righe.id_page having count(rc.id_page) >= 3 order by count(rc.id_page) desc;');
    }
    
    public function riempi()
    {
		$pagineAssociate = $this->getPagineAssociate();
		
		$this->db->beginTransaction();
		
		foreach ($pagineAssociate as $row)
		{
			$this->sValues(array(
				"id_page"			=>	$row["righe"]["ID_PAGINA"],
				"id_associata"		=>	$row["rc"]["ID_PAGINA_ASSOCIATA"],
				"numero_acquisti"	=>	$row["aggregate"]["numero"],
			));
			
			$idRiga = $this->clear()->where(array(
				"id_page"		=>	(int)$row["righe"]["ID_PAGINA"],
				"id_associata"	=>	(int)$row["rc"]["ID_PAGINA_ASSOCIATA"],
			))->field("id_page_associata");
			
			if ($idRiga)
				$this->update($idRiga);
			else
				$this->insert();
		}
		
		$this->db->commit();
    }
    
    // Restituisce i prodotti spesso comprati assieme
    // $correlati: array con i prodotti correlati, se si vuole escludere i correlati dai prodotti spesso comprati assieme
	public function getCompratiAssieme($id_page, $correlati)
	{
		$clean['id'] = (int)$id_page;
		
		$idsCorrelati = [];
		
// 		foreach ($correlati as $c)
// 		{
// 			$idsCorrelati[] = $c["pages"]["id_page"];
// 		}
		
		$this->clear()
			->select("pages.*,categories.*,contenuti_tradotti.*,contenuti_tradotti_categoria.*")
			->inner("pages")->on("pages_associate.id_associata = pages.id_page")
			->inner("categories")->on("categories.id_c = pages.id_c")
			->addJoinTraduzione(null, "contenuti_tradotti", false, new PagesModel())
			->addJoinTraduzione(null, "contenuti_tradotti_categoria", false, new CategoriesModel())
			->addWhereAttivo()
			->aWhere(array(
				"pages.gift_card"	=>	0,
				"id_page"	=>	$clean['id'],
			))
			->orderBy("pages_associate.numero_acquisti desc,pages.id_order")
			->limit(v("numero_massimo_comprati_assieme"));
		
		if (count($idsCorrelati) > 0)
			$this->sWhere(array("pages.id_page not in (".$this->placeholdersFromArray($idsCorrelati).")", forceIntDeep($idsCorrelati)));
		
		$res = $this->send();
		
// 		echo $this->getQuery();die();
		
		PagesModel::preloadPages($res);
		
		return $res;
	}
}
