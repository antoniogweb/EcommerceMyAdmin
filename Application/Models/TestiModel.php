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

class TestiModel extends GenericModel {
	
	public static $mostraIconaEdit = true;
	
	public static $uploadFile = true;
	
	public function __construct() {
		$this->_tables='testi';
		$this->_idFields='id_t';
		
		$this->_lang = 'It';

		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'valore'		=>	array(
					'className'		=>	'dettagli dettagli_testi',
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
		
		$this->uploadFields = array(
			"immagine"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/widgets",
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg,gif,svg',
				'allowedMimeTypes'	=>	'',
				"createImage"	=>	false,
				"maxFileSize"	=>	3000000,
// 				"clean_field"	=>	"clean_immagine",
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	300,
					'imgHeight'		=>	300,
					'defaultImage'	=>  null,
					'cropImage'		=>	'no',
				),
			),
			"immagine_2x"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/widgets",
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg,gif',
				'allowedMimeTypes'	=>	'',
				"createImage"	=>	false,
				"maxFileSize"	=>	3000000,
// 				"clean_field"	=>	"clean_immagine",
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	300,
					'imgHeight'		=>	300,
					'defaultImage'	=>  null,
					'cropImage'		=>	'no',
				),
			),
		);
		
		parent::__construct();
	}
	
	public function setFormStruct($id = 0)
	{

		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'id_contenuto'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Link al contenuto",
					"options"	=>	$this->selectLinkContenuto(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'entryClass'  => 'form_input_text cat_Select',
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
					'wrap'	=>	array(null,null,"<div>","</div>"),
				),
				'id_categoria'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Link alla categoria",
					"options"	=>	$this->buildAllCatSelect(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'entryClass'  => 'form_input_text cat_Select',
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
					'wrap'	=>	array(null,null,"<div>","</div>"),
				),
				'target_link'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Apri in ",
					"options"	=>	array(
						"INTERNO"	=>	"Stessa scheda",
						"ESTERNO"	=>	"Nuova scheda",
					),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'editor_visuale'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Usa editor visuale",
					"options"	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'tag_elemento'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Tipo elemento",
					"options"	=>	OpzioniModel::codice("TAG_CONTENITORE"),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'template'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Template elemento",
					"options"	=>	$this->selectTemplateElemento($id),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'link_id_documento'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Link a documento',
					'options'	=>	$this->selectDocumento(),
					'reverse' => 'yes',
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
					'wrap'	=>	array(null,null,"<div>","</div>"),
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
		
// 		$this->formStruct["entries"] = $this->formStruct["entries"] + $this->getLinkEntries();
	}
	
	public function update($id = NULL, $whereClause = NULL)
	{
		if (!self::$uploadFile || $this->upload("update"))
		{
			$record = $this->selectId((int)$id);
			
			if (!empty($record) && $record["tipo"] != "TESTO")
				$this->values["valore"] = "";
			
			return parent::update($id, $whereClause);
		}
	}
	
	public function insert()
	{
		if (!self::$uploadFile || $this->upload("insert"))
		{
			return parent::insert();
		}
	}
	
	public function selectTemplateElemento($id)
	{
		$record = $this->selectId($id);
		
		if (!empty($record))
		{
			return array(""	=>	"Default") + Tema::getSelectElementi("Contenuti/Elementi/Widget/".ucfirst(strtolower($record["tipo"])));
		}
		
		return array(""	=>	"Default");
	}
	
	public function lingua($record)
	{
		return strtoupper($record["testi"]["lingua"]);
	}
	
	public function thumb($record)
	{
		if ($record["testi"]["tipo"] == "IMMAGINE" && $record["testi"]["immagine"] && file_exists(Domain::$parentRoot."/images/widgets/".$record["testi"]["immagine"]))
			return "<img width='100px' src='".Domain::$publicUrl."/images/widgets/".$record["testi"]["immagine"]."' />";
		
		return "";
	}
	
	public static function numero($idCont)
	{
		$t = new TestiModel();
		
		return $t->clear()->where(array(
			"id_cont"	=>	(int)$idCont,
		))->rowNumber();
	}
	
	public function getTesto($matches, $tags = null, $tipo = "TESTO", $cleanFlush = true, $ritornaElemento = false)
	{
		$clean["chiave"] = sanitizeDb($matches[1]);
		
		$t = new TestiModel();
		
		if ($tipo == "TESTO" || $tipo == "LINK")
			$lingua = sanitizeAll(getLinguaIso());
		else
			$lingua = LingueModel::getPrincipale();
		
		$testo = $t->clear()->where(array(
			"chiave"=>$clean["chiave"],
			"lingua"	=>	$lingua,
		))->record();
		
	// 	echo $t->getQuery()."<br />";
		
		if (count($testo) > 0)
		{
			if ($ritornaElemento)
				return $testo;
			
			$clean["id"] = (int)$testo["id_t"];
			
			$iconaEdit = User::$adminLogged ? "<span rel='".$clean["id"]."' title='modifica il testo' class='edit_blocco_testo' href='#'><i class='fa fa-pencil'></i></span>" : null;
			
			$tags = nullToBlank($tags);
			
			$t = strcmp($tags,"") !== 0 ? strip_tags(htmlentitydecode($testo["valore"]),$tags) : htmlentitydecode($testo["valore"]);
			
			$alt = $testo["alt"] ? 'alt="'.$testo["alt"].'"' : "";
			
			if ($testo["immagine"])
				$t .= "<img src='".Domain::$publicUrl."/thumb/widget/".$testo["id_t"]."/".$testo["immagine"]."' $alt/>";
			
			$urlLink = $target = "";
			
			if ($testo["id_contenuto"] || $testo["id_categoria"] || $testo["link_id_documento"] || $testo["url_link"])
			{
				$target = "";
				
				if ($testo["target_link"] == "ESTERNO")
					$target = "target='_blank'";
				
				if ($testo["id_contenuto"])
					$urlLink = Url::getRoot().getUrlAlias($testo["id_contenuto"], sanitizeAll(getLinguaIso()));
				else if ($testo["id_categoria"])
					$urlLink = Url::getRoot().getCategoryUrlAlias($testo["id_categoria"], sanitizeAll(getLinguaIso()));
				else if ($testo["link_id_documento"])
					$urlLink = Url::getRoot().DocumentiModel::getUrlAlias($testo["link_id_documento"]);
				else
					$urlLink = $testo["url_link"];
				
				if ($testo["testo_link"])
					$t = $testo["testo_link"];
				
	// 			$t .= "<a $target class='link_testi' href='".$urlLink."'>".$testo["testo_link"]."</a>";
			}
			
			if ($urlLink)
			{
				$t = "<a $target class='link_testi' href='".$urlLink."'>".$t."</a>";
			}
			
			if ($testo["tag_elemento"])
				$t = "<".$testo["tag_elemento"]." ".htmlentitydecode($testo["attributi"]).">$t</".$testo["tag_elemento"].">";
			
			$path = tpf("Contenuti/Elementi/Widget/".strtolower($tipo).".php");
			
			if ($testo["template"])
				$path = tpf("Contenuti/Elementi/Widget/".ucfirst(strtolower($tipo))."/".$testo["template"]);
			
			if (file_exists($path))
			{
				ob_start();
				include $path;
				if ($cleanFlush)
					$t = ob_get_clean();
				else
					$t = ob_get_flush();
			}
			
			if (User::$adminLogged && TestiModel::$mostraIconaEdit)
			{
				return "<".v("tag_blocco_testo")." class='blocco_testo'>".$t."$iconaEdit</".v("tag_blocco_testo").">";
			}
			else
			{
				return $t;
			}
		}
		else
		{
			$testoPrincipale = $t->clear()->where(array(
				"chiave"=>$clean["chiave"],
				"lingua"	=>	LingueModel::getPrincipale(),
			))->record();
			
			if (!empty($testoPrincipale))
			{
				$t->setValues($testoPrincipale, "sanitizeDb");
				
				unset($t->values["id_t"]);
			}
			else
			{
				if ($tipo == "IMMAGINE" && file_exists(LIBRARY."/Frontend/Public/Img/nofound.jpeg"))
					$t->values = array(
						"valore"	=>	sanitizeDb("<img width='200px' src='".Url::getFileRoot()."admin/Frontend/Public/Img/nofound.jpeg' />"),
					);
				else
					$t->values = array(
						"valore"	=>	$clean["chiave"],
					);
				
				if (ContenutiModel::$idContenuto)
					$t->values["id_cont"] = ContenutiModel::$idContenuto;
			}
			
			$t->values["chiave"] = $clean["chiave"];
			$t->values["lingua"] = sanitizeDb($lingua);
			$t->values["tipo"] = sanitizeDb($tipo);
			
			if (isset($matches[2]) && $matches[2])
				$t->values["attributi"] = sanitizeAll($matches[2]);
			
			if (isset($matches[3]) && $matches[3])
				$t->values["tag_elemento"] = sanitizeAll($matches[3]);
			
			if ($t->insert())
			{
				unset(Cache_Db::$cachedTables[array_search("testi", Cache_Db::$cachedTables)]);
				return getTesto($matches, $tags, $tipo, $cleanFlush);
			}
		}
		
		return "";
	}
}
