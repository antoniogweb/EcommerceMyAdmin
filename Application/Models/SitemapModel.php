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

if (!defined('EG')) die('Direct access not allowed!');

class SitemapModel extends GenericModel {
	
	public static $categorie = null;
	public static $pagine = null;
	public static $tipi = array(
		"S"	=>	"Pagina strutturata del CMS",
		"L"	=>	"Url libero",
	);
	
	public function __construct() {
		$this->_tables='sitemap';
		$this->_idFields='id_sitemap';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'categoria' => array("BELONGS_TO", 'CategoriesModel', 'id_c',null,"CASCADE"),
			'pagina' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
		);
    }
    
    public function setFormStruct($id = 0)
	{
		$attributesVisibilita = array(
			"visible-f"	=>	"tipo",
			"visible-v"	=>	"L",
		);
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'tipo'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Tipo link",
					"options"	=>	self::$tipi,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					"entryAttributes"	=> self::$onChanggeCheckVisibilityAttributes,
				),
				'titolo'	=>	array(
					"entryAttributes"	=>	$attributesVisibilita,
				),
				'url'	=>	array(
					"entryAttributes"	=>	$attributesVisibilita,
				),
			),
		);
	}
    
    // Aggiorna la sitemap
	public function aggiorna($recuperaBackup = 0)
	{
		$nodi = self::getNodi($recuperaBackup);
		
		if (v("usa_transactions"))
			$this->db->beginTransaction();
		
		foreach ($nodi as $n)
		{
			$idPage = (int)$n["aggregate"]["id_page"];
			$idC = (int)$n["aggregate"]["id_c"];
			
			$numero = $this->clear()->where(array(
				"id_page"	=>	$idPage,
				"id_c"		=>	$idC,
			))->rowNumber();
			
			if (!$numero)
			{
				$this->setValues(array(
					"id_page"	=>	$idPage,
					"id_c"		=>	$idC,
					"ultima_modifica"	=>	$n["aggregate"]["ultima_modifica"],
					"priorita"	=>	$n["aggregate"]["priorita"],
					"home"		=>	$n["aggregate"]["home"]
				));
				
				$this->insert();
			}
		}
		
		if (v("usa_transactions"))
			$this->db->commit();
	}
    
    public function del($id = null, $where = null)
    {
		if ($id)
		{
			$record = $this->selectId((int)$id);
			
			if (empty($record))
				return false;
			
			$idPage = $record["id_page"];
			$idC = $record["id_c"];
			
			if ($idPage)
			{
				$p = new PagesModel();
				$p->inserisciTogliSitemap($idPage, "N");
			}
			else if ($idC)
			{
				$c = new CategoriesModel();
				$c->inserisciTogliSitemap($idC, "N");
			}
		}
		
		return parent::del($id, $where);
    }
    
    public static function getNodiFrontend()
    {
		if (v("permetti_gestione_sitemap"))
		{
			$nodi = self::g(false)->select("sitemap.priorita, pages.id_page, categories.id_c,coalesce(pages.data_ultima_modifica, categories.data_ultima_modifica) as ultima_modifica,sitemap.url")->left(array("categoria","pagina"))->orderBy("sitemap.id_order")->send(false);
			
			$dataModificaHome = self::dataModificaHome($nodi);
			
			for ($i=0; $i<count($nodi); $i++)
			{
				if (!$nodi[$i]["id_page"] && !$nodi[$i]["id_c"])
					$nodi[$i]["ultima_modifica"] = date("Y-m-d H:i:s", $dataModificaHome);
			}
		}
		else
			$nodi =  self::getNodi();
		
// 		print_r($nodi);
		
		return $nodi;
    }
    
    public static function dataModificaHome($nodi)
    {
		$dataModificaHome = 0;
		
		foreach ($nodi as $n)
		{
			$n = isset($n["aggregate"]) ? $n["aggregate"] : $n;
			
			if ($n["ultima_modifica"] && strtotime($n["ultima_modifica"]) > $dataModificaHome)
				$dataModificaHome = strtotime($n["ultima_modifica"]);
		}
		
		if ($dataModificaHome <= 0)
			return time();
		else
			return $dataModificaHome;
    }
    
    public static function getNodi($recuperaBackup = 0)
    {
		$c = new CategoriesModel();
		$p = new PagesModel();
		
		// Where categorie
		$c->clear()->where(array(
			"ne"	=>	array(
				"id_c"	=>	1,
			),
		))
		->addWhereAttivoCategoria()
		->addWhereCategoriaInstallata()
		->addWhereOkSitemap("id_p");
		
// 		if (!$recuperaBackup)
			$c->aWhere(array(
				"add_in_sitemap"	=>	"Y",
			));
		
		$elements = $c->treeQueryElements("categories");
		$binded = $elements["binded"];
		
		$sqlCategorie = "select id_c, 0 as id_page, priorita_sitemap as priorita,lft, coalesce(categories.data_ultima_modifica,categories.data_creazione) as ultima_modifica,0 as url,0 as home from categories where ".$elements["where"];
		
		// Where pagine
// 		$p->clear()->addWhereAttivo()->addWhereAttivoCategoria()->addWhereCategoriaInstallata()->addWhereOkSitemap();
		$p->clear()->addWhereClauseCerca();
		
// 		if (!$recuperaBackup)
			$p->aWhere(array(
				"add_in_sitemap"	=>	"Y",
				"categories.add_in_sitemap_children"	=>	"Y",
			));
		
		$elements = $p->treeQueryElements("pages");
		$binded = array_merge($binded, $elements["binded"]);
		
		$sqlPages = "select categories.id_c as id_c, id_page, pages.priorita_sitemap as priorita, 99999 as lft, coalesce(pages.data_ultima_modifica,pages.data_creazione) as ultima_modifica,0 as url,0 as home from pages inner join categories on categories.id_c = pages.id_c where ".$elements["where"];
		
		$sql = "$sqlCategorie union $sqlPages order by priorita desc, lft,id_c,id_page limit 1000";
		
// 		echo $sql;die();
		
		$nodi = $c->query(array($sql,$binded), false);
		
		$dataModificaHome = self::dataModificaHome($nodi);
		
		array_unshift($nodi, array(
			"aggregate"	=>	array(
				"id_c"		=>	0,
				"id_page"	=>	0,
				"priorita"	=>	1,
				"lft"		=>	0,
				"ultima_modifica"	=>	date("Y-m-d H:i:s", $dataModificaHome),
				"home"		=>	1,
				"url"		=>	"",
			),
		));
		
		return $nodi;
    }
    
    public function titolo($id)
    {
		$record = $this->selectId($id);
		
		if (!empty($record))
		{
			if ($record["id_page"])
				return PagesModel::g(false)->where(array("id_page"=>(int)$record["id_page"]))->field("title");
			else if ($record["id_c"])
				return CategoriesModel::g(false)->where(array("id_c"=>(int)$record["id_c"]))->field("title");
			else if ($record["titolo"])
				return $record["titolo"];
			else
				return gtext("HOME PAGE");
		}
		
		return "";
    }
    
    public function titolocrud($record)
    {
		$idPage = $record["sitemap"]["id_page"];
		$idC = $record["sitemap"]["id_c"];
		
		if ($idPage)
		{
			if (!isset(self::$pagine))
				self::$pagine = PagesModel::g(false)->select("id_page,title")->toList("id_page", "title")->send();

			if (isset(self::$pagine[$idPage]))
				return self::$pagine[$idPage];
		}
		else if ($idC)
		{
			if (!isset(self::$categorie))
				self::$categorie = CategoriesModel::g(false)->select("id_c,title")->toList("id_c", "title")->send();

			if (isset(self::$categorie[$idC]))
				return self::$categorie[$idC];
		}
		else if ($record["sitemap"]["home"])
			return "HOME PAGE";
		else if ($record["sitemap"]["url"])
			return $record["sitemap"]["titolo"] ? $record["sitemap"]["titolo"] : "Custom";
		
		return "1";
    }
    
    public function tipo($record)
    {
		$tipo = $record["sitemap"]["tipo"];
		
		if (isset(self::$tipi[$tipo]))
			return self::$tipi[$tipo];
		
		return "";
    }
    
    public function url($record)
    {
		$n = $record["sitemap"];
		
		if ($n["id_page"])
			return getUrlAlias($n["id_page"]);
		else if ($n["id_c"])
			return getCategoryUrlAlias($n["id_c"]);
		else if ($n["url"])
			return $n["url"];
		else
			return "/";
    }
    
}
