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

class CaratteristichevaloriModel extends GenericModel {
	
	use CrudModel;
	
	public $lId = 0;
	
	public static $names = array();
	
	public static $uploadFile = true;
	
	public function __construct() {
		$this->_tables='caratteristiche_valori';
		$this->_idFields='id_cv';
		
		$this->_idOrder = 'id_order';
		
		$this->traduzione = true;
		
		$this->orderBy = 'caratteristiche_valori.id_order';
		$this->_lang = 'It';
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo|Si prega di specificare il valore della caratteristica");
		
		$this->uploadFields = array(
			"immagine"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/valori_caratteristiche",
				"allowedExtensions"	=>	'png,jpg,jpeg,gif,svg',
				'allowedMimeTypes'	=>	'',
				"createImage"	=>	false,
				"maxFileSize"	=>	3000000,
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	50,
					'imgHeight'		=>	50,
					'defaultImage'	=>  null,
					'cropImage'		=>	'yes',
				),
			),
		);
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_cv', null, "CASCADE"),
			'caratteristica' => array("BELONGS_TO", 'CaratteristicheModel', 'id_car',null,"CASCADE"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$idTipo = "tutti";
		
		if (isset($_GET["id_tipo_car"]) && $_GET["id_tipo_car"] != "tutti")
			$idTipo = (int)$_GET["id_tipo_car"];
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'titolo'		=>	array(
					'labelString'=>	'Valore',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Rappresenta il valore della caratteristica. Ex: rosso, XL, cuore")."</div>",
					),
				),
				'titolo_2'		=>	array(
					'labelString'=>	'Valore aggiuntivo',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Rappresenta un valore aggiuntivo per specificare meglio la caratteristica. Non è supportato da tutti i temi.")."</div>",
					),
				),
				'id_car'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Caratteristica",
					"options"	=>	$this->selectCaratteristica(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
// 					'wrap'	=>	array(
// 						null,null," <a class='iframe pull-right' href='".Url::getRoot()."caratteristiche/form/insert/0?partial=Y&nobuttons=Y&cl_on_sv=Y&id_tip_car=$idTipo'><i class='fa fa-plus-square-o'></i> Nuovo</a>"
// 					),
				),
				'alias'		=>	array(
					'labelString'=>	"Alias (usato nell'URL)",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Quando si filtra nella sezione prodotti per tale caratteristica, nell'URL della pagina verrà usato l'alias indicato. Viene creato in automatico se lasciato vuoto.")."</div>",
					),
				),
				'colore'	=>	array(
					"className"	=>	"form-control colorpicker-element",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Inserire il codice esadecimale del colore con il cancelletto davanti. Ex: #000000 per il colore nero")."</div>",
					),
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	public function selectCaratteristica($conNote = false, $orderBy = "id_order")
	{
		$t = new CaratteristicheModel();
		
		$t->clear()->orderBy($orderBy);
		
		if (!$conNote)
			$t->toList("id_car","titolo");
		
		if (isset($_GET["id_tipo_car"]) && $_GET["id_tipo_car"] != "tutti")
			$t->aWhere(array(
				"id_tipologia_caratteristica"	=>	(int)$_GET["id_tipo_car"],
			));
		
		if (isset($_GET["id_page"]) && $_GET["id_page"] != "tutti")
		{
			$p = new PagesModel();
			
			$section = $p->section((int)$_GET["id_page"], true);
			
			$t->aWhere(array(
				"caratteristiche.section"	=>	sanitizeAll($section),
			));
		}
		
		$res = $t->send();
		
		if (!$conNote)
			return $res;
		else
		{
			$selectArray = array();
			
			foreach ($res as $r)
			{
				$titolo = $r["caratteristiche"]["titolo"];
				
				if ($r["caratteristiche"]["nota_interna"])
					$titolo .= " (".$r["caratteristiche"]["nota_interna"].")";
				
				$selectArray[$r["caratteristiche"]["id_car"]] = $titolo;
			}
			
			return $selectArray;
		}
	}
	
	//get the name of the attribute from the id
	public function getName($id_av)
	{
		$clean["id_av"] = (int)$id_av;
		
		if (isset(self::$names[$clean["id_av"]]))
		{
			return self::$names[$clean["id_av"]];
		}
		else
		{
			$res = $this->clear()->where(array("id_av"=>$clean["id_av"]))->send();
			
			if (count($res) > 0)
			{
				self::$names[$clean["id_av"]] = $res[0]["caratteristiche_valori"]["titolo"];
				return $res[0]["caratteristiche_valori"]["titolo"];
			}
		}
		return "";
	}
	
	public function edit($record)
	{
		if ($record["caratteristiche_valori"]["id_cv"])
			return "<a class='iframe action_iframe' href='".Url::getRoot()."caratteristichevalori/form/update/".$record["caratteristiche_valori"]["id_cv"]."?partial=Y&nobuttons=N'>".$record["caratteristiche_valori"]["titolo"]."</a>";
	}
	
	public function del($id = null, $whereClause = null)
	{
		$clean['id'] = (int)$id;

		$pcv = new PagescarvalModel();
		
		$res = $pcv->clear()->select("distinct pages.id_page,pages.*")->inner("pages")->using("id_page")->where(array("id_cv"=>$clean['id']))->send();

		if (count($res) > 0)
		{
			$this->notice = "<div class='alert'>Questa caratteristica è associata a uno o più prodotti e non può quindi essere cancellata se prima non si provvede a dissociarla da tali prodotti.<br/>Elenco prodotti che usano tale valore:<ul>";
			foreach ($res as $r)
			{
				$this->notice .= "<li><a style='color:#000;' target='_blank' href='http://".DOMAIN_NAME."/prodotti/caratteristiche/".$r["pages"]["id_page"]."'>".$r["pages"]["title"]."</a></li>";
			}
			$this->notice .= "</ul></div>";
			$this->result = false;
		}
		else
		{
			return parent::del($clean["id"]);
		}
	}
	
	public function insert()
	{
		$res = false;
		
		if (!self::$uploadFile || $this->upload("insert"))
		{
			if (isset($this->values["alias"]))
				$this->checkAliasAll(0);
			
			$res = parent::insert();
			
			if ($res)
			{
				// $this->controllaLingua($this->lId);
				
				// Aggiungo direttamente dal prodotto
				if (isset($_GET["id_page"]) && $_GET["id_page"] && is_numeric($_GET["id_page"]) && isset($this->values["id_car"]))
					$this->aggiungiaprodotto($this->lId);
			}
		}
		 
		return $res;
	}
	
	public function update($id = null, $where = null)
	{
		$res = false;
		
		if (!self::$uploadFile || $this->upload("update"))
		{
			if (isset($this->values["alias"]))
				$this->checkAliasAll($id);
			
			$res = parent::update($id, $where);
			
			if ($res)
			{
				// $this->controllalingua($id, "id_cv");
				
				// Aggiungo direttamente dal prodotto
				if (isset($_GET["id_page_update"]) && $_GET["id_page_update"] && is_numeric($_GET["id_page_update"]) && isset($this->values["id_car"]))
					$this->aggiungiaprodotto($id);
			}
		}
		
		return $res;
	}
	
	// Controllo che la lingua esista
	public function controllaLingua($id)
	{
		$this->controllaLinguaGeneric($id, "id_cv", "-cv-");
	}
	
	public function linklingua($record, $lingua)
	{
		return $this->linklinguaGeneric($record["caratteristiche_valori"]["id_cv"], $lingua, "id_cv");
	}
	
    public function aggiungiaprodotto($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && (isset($_GET["id_page"]) || isset($_GET["id_page_update"])))
		{
			$idPage = isset($_GET["id_page"]) ? $_GET["id_page"] : $_GET["id_page_update"];
			
			$pcv = new PagescarvalModel();
			
			$pcv->setValues(array(
				"id_page"	=>	(int)$idPage,
				"id_cv"		=>	(int)$id,
			), "sanitizeDb");
			
			if ($pcv->pInsert() && (int)$record["id_car"])
			{
				$pageModel = new PagesModel();
				$carModel = new CaratteristicheModel();
				
				$caratteristica = $carModel->selectId((int)$record["id_car"]);
				
				$section = (string)$pageModel->section((int)$idPage, true);
				
				if (!empty($caratteristica) && (string)$caratteristica["section"] !== (string)$section)
				{
					$carModel->setValues(array(
						"section"	=>	$section,
					));
					
					$carModel->pUpdate($record["id_car"]);
				}
			}
		}
    }
    
    public function thumb($record)
	{
		$html = "--";
		
		if ($record["caratteristiche_valori"]["immagine"] && file_exists(Domain::$parentRoot."/images/valori_caratteristiche/".$record["caratteristiche_valori"]["immagine"]))
		{
			$extArray = explode('.', $record["caratteristiche_valori"]["immagine"]);
			$ext = strtolower(end($extArray));
			
			$srcImmagine = ($ext != "svg") ? Url::getRoot()."caratteristichevalori/thumb/immagine/".$record["caratteristiche_valori"]["id_cv"] : Domain::$name."/images/valori_caratteristiche/".$record["caratteristiche_valori"]["immagine"];
			
			$width = ($ext != "svg") ? "" : "width='50'";
			
			$html = "<a target='_blank' href='".Domain::$name."/images/valori_caratteristiche/".$record["caratteristiche_valori"]["immagine"]."'><img $width src='".$srcImmagine."' /></a>";
		}
		
		return $html;
	}
	
	public static function numeroProdotti($aliasCar, $aliasCarVal, $filtriSuccessivi = false)
	{
		$p = PagesModel::g(false)->addWhereAttivo()
			->inner(array("caratteristiche"))
			->inner("caratteristiche_valori")->on("pages_caratteristiche_valori.id_cv = caratteristiche_valori.id_cv")
			->addJoinTraduzione(null, "contenuti_tradotti", false, (new CaratteristichevaloriModel()))
			->sWhere(array("coalesce(contenuti_tradotti.alias,caratteristiche_valori.alias) = ?",array(sanitizeAll($aliasCarVal))));
		
		if ($filtriSuccessivi)
			$p->sWhereFiltriSuccessivi($aliasCar);
		
		return $p->rowNumber();
	}
	
	public static function getTipo($idCV)
	{
		$cv = new CaratteristichevaloriModel();
		
		return $cv->select("caratteristiche.tipo")->inner(array("caratteristica"))->where(array(
			"id_cv"	=>	(int)$idCV,
		))->field("caratteristiche.tipo");
	}
	
	// Restituisce il record dall'alias
	public static function getRecordDaAlias($alias)
	{
		$cv = new CaratteristichevaloriModel();
		
		return $cv->addJoinTraduzione()->sWhere(array(
			"coalesce(contenuti_tradotti.alias,caratteristiche_valori.alias) = ?",
			array(sanitizeAll($alias))
		))->orderBy(null)->first();
	}
}
