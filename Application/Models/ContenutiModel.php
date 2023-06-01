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

class ContenutiModel extends GenericModel {
	
	public static $posizioni = array(
		"eg-position-top-left"		=>	"In alto a sinistra",
		"eg-position-top-center"	=>	"In alto in centro",
		"eg-position-top-right"		=>	"In alto a destra",
		"eg-position-center-left"	=>	"In mezzo a sinistra",
		"eg-position-center"		=>	"In mezzo",
		"eg-position-center-right"	=>	"In mezzo a destra",
		"eg-position-bottom-left"	=>	"In basso a sinistra",
		"eg-position-bottom-center"	=>	"In basso in centro",
		"eg-position-bottom-right"	=>	"In basso a destra",
	);
	
	public static $contentData = array();
	
	public static $visibile = array(
		1	=>	"Visibile",
		0	=>	"Nascosto",
	);
	
	public static $animazioni = array(
		""			=>	"Nessuna",
		"-x"		=>	"Entra da sinistra",
		"x"			=>	"Entra da destra",
		"-y"		=>	"Entra dal basso",
		"y"			=>	"Entra dall'alto",
	);
	
	public static $fascePagina = array();
	public static $idElementoCorrente = 0;
	public static $tipoElementoCorrente = "";
	public static $idContenuto = 0;
	
	public function __construct() {
		$this->_tables='contenuti';
		$this->_idFields='id_cont';
		
		$this->_lang = 'It';
		
		$this->_idOrder = 'id_order';
		
		$this->traduzione = true;
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		$this->uploadFields = array(
			"immagine_1"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/contenuti",
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg,gif,svg',
				'allowedMimeTypes'	=>	'',
				"createImage"	=>	false,
				"maxFileSize"	=>	v("dimensioni_upload_contenuti"),
// 				"clean_field"	=>	"clean_immagine",
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	300,
					'imgHeight'		=>	300,
					'defaultImage'	=>  null,
					'cropImage'		=>	'no',
				),
			),
			"immagine_2"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/contenuti",
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg,gif,svg',
				'allowedMimeTypes'	=>	'',
				"createImage"	=>	false,
				"maxFileSize"	=>	v("dimensioni_upload_contenuti"),
// 				"clean_field"	=>	"clean_immagine",
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	300,
					'imgHeight'		=>	300,
					'defaultImage'	=>  null,
					'cropImage'		=>	'no',
				),
			),
			"filename"	=>	array(
				"type"	=>	"file",
				"path"	=>	"images/contenuti",
				"allowedExtensions"	=>	v("estensioni_upload_file_contenuti"),
				"maxFileSize"	=>	v("dimensioni_upload_contenuti"),
				"Content-Disposition"	=>	"inline",
			),
		);
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_cont', null, "CASCADE"),
			'testi' => array("HAS_MANY", 'TestiModel', 'id_cont', null, "CASCADE"),
			'page' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
			'category' => array("BELONGS_TO", 'CategoriesModel', 'id_c',null,"CASCADE"),
			'tipo' => array("BELONGS_TO", 'TipicontenutoModel', 'id_tipo',null,"CASCADE"),
			'gruppi' => array("MANY_TO_MANY", 'ReggroupsModel', 'id_group', array("ReggroupscontenutiModel","id_cont","id_group"), "CASCADE"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$tipo = (isset($_GET["tipo"]) && in_array($_GET["tipo"], array("FASCIA","GENERICO","MARKER"))) ? $_GET["tipo"] : "FASCIA";
		
		$contenuto = $this->selectId($id);
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'lingua'	=>	array(
					"type"	=>	"Select",
					"options"	=>	array("tutte" => "TUTTE") + $this->selectLingua(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					"labelString"	=>	"Visibile su lingua",
				),
				'id_tipo'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Tipo contenuto",
					"options"	=>	$this->selectTipo($tipo),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'target'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Target link",
					"options"	=>	array(
						"STESSO_TAB"	=>	"Apri nella stessa scheda",
						"NUOVO_TAB"		=>	"Apri in una nuova scheda",
					),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'link_contenuto'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Link al contenuto",
					"options"	=>	$this->selectLinkContenuto(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'posizione'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Posizione (per tutte le larghezza)",
					"options"	=>	array("" => "Nessuno") + self::$posizioni,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'posizione_xs'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Posizione telefono verticale",
					"options"	=>	array("" => "Default") + self::$posizioni,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'posizione_s'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Posizione telefono orizzontale o tablet verticale",
					"options"	=>	array("" => "Default") + self::$posizioni,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'posizione_m'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Posizione tablet orizzontale",
					"options"	=>	array("" => "Default") + self::$posizioni,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'posizione_l'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Posizione portatile",
					"options"	=>	array("" => "Default") + self::$posizioni,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'posizione_xl'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Posizione schermo grande",
					"options"	=>	array("" => "Default") + self::$posizioni,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'visibile_xs'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Visibilità telefono verticale",
					"options"	=>	self::$visibile,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'visibile_s'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Visibilità telefono orizzontale o tablet verticale",
					"options"	=>	self::$visibile,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'visibile_m'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Visibilità tablet orizzontale",
					"options"	=>	self::$visibile,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'visibile_l'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Visibilità portatile",
					"options"	=>	self::$visibile,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'visibile_xl'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Visibilità schermo grande",
					"options"	=>	self::$visibile,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'animazione'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Animazione",
					"options"	=>	self::$animazioni,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'tipo_layer'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Tipo layer",
					"options"	=>	array(
						"IMMAGINE"	=>	"IMMAGINE",
						"TESTO"		=>	"TESTO",
					),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'margine_superiore'	=>	array(
					"type"	=>	"Select",
					"options"	=>	array("" => "--") + OpzioniModel::codice("MARGINE_SUPERIORE_FASCIA"),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					"labelString"	=>	"Margine superiore fascia",
				),
				'margine_inferiore'	=>	array(
					"type"	=>	"Select",
					"options"	=>	array("" => "--") + OpzioniModel::codice("MARGINE_INFERIORE_FASCIA"),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					"labelString"	=>	"Margine inferiore fascia",
				),
				'descrizione'		=>	array(
					'labelString'=>	(isset($contenuto["tipo"]) && $contenuto["tipo"] == "FASCIA") ? "HTML fascia" : "Descrizione",
				),
				'id_page'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Contenuto da linkare',
					'options'	=>	$this->buildContentSelect(),
					'reverse' => 'yes',
					'entryClass'  => 'form_input_text cont_Select',
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
					'wrap'	=>	array(null,null,"<div>","</div>"),
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
		
		$this->formStruct["entries"] = $this->formStruct["entries"] + $this->getLinkEntries();
	}
	
	public function buildContentSelect()
	{
		$p = new PagesModel();
		$c = new CategoriesModel();
		
		$idC = $c->getShopCategoryId();
		
		return $p->clear()->addWhereCategoria($c->getShopCategoryId())->addWhereAttivo()->sWhere("id_user = 0")->orderBy("pages.id_order")->toList("id_page","title")->send();
	}
	
	public function selectTipo($tipo = null)
	{
		$t = new TipicontenutoModel();
		
		$t->clear()->orderBy("id_order")->toList("id_tipo","titolo");
		
// 		if ($tipo == "FASCIA")
			$t->where(array(
				"tipo"	=>	sanitizeDb($tipo),
			));
// 		else
// 			$t->where(array(
// 				"ne"	=>	array("tipo"	=>	"FASCIA"),
// 			));
		
		return $t->send();
	}
	
	public function buildAllPagesSelect()
	{
		$p = new PagesModel();
		
		return array("0"=>gtext("-- NON IMPOSTATO --")) + $p->clear()->orderBy("title")->toList("id_page","title")->send();
	}
	
    public function titoloContenuto($record)
	{
		return "<a class='iframe action_iframe' href='".Url::getRoot()."contenuti/form/update/".$record["contenuti"]["id_cont"]."?partial=Y&nobuttons=Y&tipo=".$record["contenuti"]["tipo"]."'>".$record["contenuti"]["titolo"]."</a>";
	}
	
	public function attivo($record)
	{
		if ($record["contenuti"]["attivo"] == "No")
			return "No";
		
		return "Sì";
	}
	
	public function accessi($record)
	{
		$rc = new ReggroupscontenutiModel();
		
		$gruppi = $rc->clear()->select("reggroups.name")->where(array(
			"id_cont"	=>	$record["contenuti"]["id_cont"],
		))->inner(array("gruppo"))->toList("reggroups.name")->send();
		
		if (count($gruppi) > 0)
			return implode("<br />", $gruppi);
		
		return "-";
	}
	
	public function immagini($record)
	{
		$html = "";
		
		if ($record["contenuti"]["immagine_1"] && file_exists(Domain::$parentRoot."/images/contenuti/".$record["contenuti"]["immagine_1"]))
			$html .= "<a target='_blank' href='".Domain::$name."/images/contenuti/".$record["contenuti"]["immagine_1"]."'><img src='".Url::getRoot()."contenuti/thumb/immagine_1/".$record["contenuti"]["id_cont"]."' /></a>";
		
		if ($record["contenuti"]["immagine_2"] && file_exists(Domain::$parentRoot."/images/contenuti/".$record["contenuti"]["immagine_2"]))
			$html .= "<a target='_blank' href='".Domain::$name."/images/contenuti/".$record["contenuti"]["immagine_2"]."'><img style='margin-top:5px;' src='".Url::getRoot()."contenuti/thumb/immagine_2/".$record["contenuti"]["id_cont"]."' /></a>";
		
		return $html;
	}
	
	public function update($id = NULL, $whereClause = NULL)
	{
		if ($this->upload("update"))
		{
			$record = $this->selectId((int)$id);
			
			if (!empty($record) && $record["tipo"] == "FASCIA" && !$record["descrizione"])
			{
				$tc = new TipicontenutoModel();
				
				$recordTipo = $tc->selectId((int)$record["id_tipo"]);
				
				$html = htmlentitydecode($recordTipo["descrizione"]);
				
				$this->values["descrizione"] = sanitizeDb($html);
			}
			
			return parent::update($id, $whereClause);
		}
		
		return false;
	}
	
	public function insert()
	{
		if (v("theme_folder"))
			$this->values["tema"] = v("theme_folder");
		
		if ($this->upload("insert"))
		{
			// Imposta i valori di default di margine_superiore e margine_inferiore se è una fascia
			if (isset($this->values["id_tipo"]) && (!isset($this->values["tipo"]) || $this->values["tipo"] == "FASCIA"))
			{
				$tc = new TipicontenutoModel();
				
				$recordTipo = $tc->selectId((int)$this->values["id_tipo"]);
				
				if (!empty($recordTipo))
				{
					$html = htmlentitydecode($recordTipo["descrizione"]);
					
					if (trim($html))
					{
						if (preg_match('/\[margineSuperiore\:(.*?)\]/', $html ,$matches))
							$this->values["margine_superiore"] = sanitizeAll($matches[1]);
						
						if (preg_match('/\[margineInferiore\:(.*?)\]/', $html ,$matches))
							$this->values["margine_inferiore"] = sanitizeAll($matches[1]);
					}
					
					$this->values["descrizione"] = sanitizeDb($html);
				}
			}
			
			$res = parent::insert();
			
			if ($res && v("attiva_reggroups_tipi"))
			{
				$rgt = new ReggroupstipiModel();
				
				$rgt->elaboraTutto("INSERT", $this->lId);
			}
			
			return $res;
		}
	}
	
	//duplica i contenuti
// 	public function duplica($from_id, $to_id)
// 	{
// 		$clean["from_id"] = (int)$from_id;
// 		$clean["to_id"] = (int)$to_id;
// 		
// 		$res = $this->clear()->where(array("id_page"=>$clean["from_id"]))->orderBy("id_order")->send(false);
// 		
// 		foreach ($res as $r)
// 		{
// 			$this->setValues($r, "sanitizeDb");
// 			$this->setValue("id_page", $to_id);
// 			
// 			unset($this->values["id_cont"]);
// 			unset($this->values["data_creazione"]);
// 			unset($this->values["id_order"]);
// // 			print_r($this->values);
// 			parent::insert();
// 		}
// 		
// // 		print_r($this->db->queries);
// 	}
	
	public function elaboraContenuti($idPage, $idC = 0, $obj = null)
	{
		// Estraggo le fasce
		$this->clear()->select("*")->inner(array("tipo"))->where(array(
			"OR"	=>	array(
				"lingua" => "tutte",
				" lingua" => sanitizeDb(Params::$lang),
			),
			"tipo"	=>	"FASCIA",
		))->orderBy("contenuti.id_order");
		
		if (!User::$adminLogged)
			$this->aWhere(array(
				"contenuti.attivo"	=>	"Y",
			));
		
		if ($idPage)
			$this->aWhere(array(
				"contenuti.id_page"	=>	(int)$idPage,
			));
		else
			$this->aWhere(array(
				"contenuti.id_c"	=>	(int)$idC,
			));
		
		if (v("filtra_fasce_per_tema"))
			$this->aWhere(array(
				"contenuti.tema"	=>	sanitizeDb(v("theme_folder")),
			));
		
		$idElemento = $idPage ? $idPage : $idC;
		$controller = $idPage ? "pagine" : "categories";
		
		self::$idElementoCorrente = $idElemento;
		self::$tipoElementoCorrente = $controller;
		
		$fasce = $this->send();
		
		$htmlFinale = "";
		
		$htmlFinale = User::$adminLogged ? "<div class='blocco_fasce_contenuto'>" : "";
		
		if (count($fasce) > 0)
		{
			foreach ($fasce as $f)
			{
				$idCont = $f["contenuti"]["id_cont"];
				
				self::$idContenuto = $idCont;
				
				self::$fascePagina[] = $f;
				
				self::$contentData = $f;
				
				$html = trim(nullToBlank($f["contenuti"]["descrizione"])) ? htmlentitydecode(nullToBlank($f["contenuti"]["descrizione"])) : htmlentitydecode($f["tipi_contenuto"]["descrizione"]);
				
				if ($f["contenuti"]["immagine_1"])
					$html = preg_replace('/\[srcImmagine1\]/', Url::getFileRoot()."images/contenuti/".$f["contenuti"]["immagine_1"] ,$html);
				
				if ($f["contenuti"]["immagine_2"])
					$html = preg_replace('/\[srcImmagine2\]/', Url::getFileRoot()."images/contenuti/".$f["contenuti"]["immagine_2"] ,$html);
				
				if ($f["contenuti"]["margine_superiore"])
					$html = preg_replace('/\[margineSuperiore\:(.*?)\]/', $f["contenuti"]["margine_superiore"] ,$html);
				
				if ($f["contenuti"]["margine_inferiore"])
					$html = preg_replace('/\[margineInferiore\:(.*?)\]/', $f["contenuti"]["margine_inferiore"] ,$html);
				
				$immagineDispositivo = (!User::$isPhone) ? $f["contenuti"]["immagine_1"] : $f["contenuti"]["immagine_2"];
				
				if (!$f["contenuti"]["immagine_2"])
					$immagineDispositivo = $f["contenuti"]["immagine_1"];
				
				$html = preg_replace('/\[srcImmagineResponsive\]/', Url::getFileRoot()."images/contenuti/".$immagineDispositivo ,$html);
				
				$html = preg_replace('/\[testoaw (.*?) attributi (.*?) wrap (.*?)\]/', '[testoaw ${1}_'.$idCont.' attributi ${2} wrap ${3}]' ,$html);
				$html = preg_replace('/\[testoa (.*?) attributi (.*?)\]/', '[testoa ${1}_'.$idCont.' attributi ${2}]' ,$html);
				$html = preg_replace('/\[testo (.*?)\]/', '[testo ${1}_'.$idCont.']' ,$html);
				
				$html = preg_replace('/\[immagine (.*?) attributi (.*?)\]/', '[immagine ${1}_'.$idCont.' attributi ${2}]' ,$html);
				$html = preg_replace('/\[link (.*?) attributi (.*?)\]/', '[link ${1}_'.$idCont.' attributi ${2}]' ,$html);
				
				$html = preg_replace('/\[immagine ([a-zA-Z0-9\_\-]{1,})\]/', '[immagine ${1}_'.$idCont.']' ,$html);
				$html = preg_replace('/\[link ([a-zA-Z0-9\_\-]{1,})\]/', '[link ${1}_'.$idCont.']' ,$html);
				$html = preg_replace('/\[video (.*?)\]/', '[video ${1}_'.$idCont.']' ,$html);
				
				$f["contenuti"]["descrizione"] = nullToBlank($f["contenuti"]["descrizione"]);
				
				$html = preg_replace('/\[descrizione\]/', $f["contenuti"]["descrizione"], $html);
				
				if (User::$adminLogged)
				{
					$htmlFinale .= "<div id='".$idCont."' class='fascia_contenuto ".v("fascia_contenuto_class")."'>";
					
					$htmlFinale .= "<div class='titolo_fascia'>Fascia: <b>".$f["contenuti"]["titolo"]."</b> - Tipo: <b>".$f["tipi_contenuto"]["titolo"]."</b>";
					
					$htmlFinale .= " - LINGUA: <b>".strtoupper($f["contenuti"]["lingua"])."</b>";
					
					$htmlFinale .= " <a title='".gtext("Modifica fascia")."' style='margin-left:5px;' class='class_edit_fascia iframe' href='".Url::getFileRoot()."admin/contenuti/form/update/".$idCont."?partial=Y'><i class='fa fa-pencil'></i></a>";
					
					if (TestiModel::numero($idCont))
						$htmlFinale .= " <a title='".gtext("Edita testi fascia")."' style='margin-left:5px;' class='class_edit_fascia iframe' href='".Url::getFileRoot()."admin/testi/main?partial=Y&id_cont=".self::$idContenuto."&lingua=".Params::$lang."'><i class='fa fa-font'></i></a>";
					
// 					$htmlFinale .= " <a title='".gtext("Ordina / Aggiungi fasce")."' style='margin-left:5px;' class='iframe' href='".Url::getFileRoot()."admin/$controller/contenuti/".(int)$idElemento."?partial=Y&nobuttons=Y'><i class='fa fa-sort'></i></a>";
					
					$htmlFinale .= "</div>";
				}
				
				$htmlFinale .= attivaModuli($html, $obj);
				
				if (User::$adminLogged)
					$htmlFinale .= "</div>";
			}
		}
		
		$htmlFinale .= User::$adminLogged ? "</div>" : "";
		
		self::$idContenuto = 0;
		
		return $htmlFinale;
	}
	
	// Estrai i contenuti della pagina
	public static function getContenutiPagina($idPage, $tipo = "GENERICO")
	{
		$c = new ContenutiModel();
		
		$c->clear()->addJoinTraduzione()->select("distinct contenuti.id_cont,contenuti.*,tipi_contenuto.*,contenuti_tradotti.*")->inner(array("tipo"))->where(array(
			"OR"	=>	array(
				"lingua" => "tutte",
				" lingua" => sanitizeDb(Params::$lang),
			),
			"tipo"	=>	$tipo,
			"id_page"	=>	(int)$idPage,
			"attivo"	=>	"Y",
		));
		
		if (v("attiva_gruppi_contenuti"))
			$c->left(array("gruppi"))->sWhere("(reggroups.name is null OR reggroups.name in ('".implode("','", User::$groups)."'))");
		
		return $c->save()->orderBy("contenuti.id_order")->send();
	}
}
