<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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

class TagModel extends GenericModel {

	public function __construct() {
		$this->_tables='tag';
		$this->_idFields='id_tag';
		
		$this->traduzione = true;
		
		$this->_lang = 'It';
		
		$this->_idOrder = 'id_order';
		
		$this->traduzione = true;
		
		$this->uploadFields = array(
			"immagine"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/tag",
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
			"immagine_2"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/tag_2",
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
	
	public function relations() {
        return array(
			'pagine' => array("HAS_MANY", 'PagestagModel', 'id_tag', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_tag', null, "CASCADE"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'alias'	=>	array(
					'labelString'=>	'Alias per URL',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>Indicazione del tag nell'URL (se lasciato vuoto viene creato in automatico)</div>"
					),
				),
				'colore_testo_in_slide'	=>	array(
					"className"	=>	"form-control colorpicker-element",
				),
				'description'		=>	array(
					'type'		 =>	'Textarea',
					'labelString'=>	'Descrizione',
					'className'		=>	'dettagli',
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
    public function update($id = NULL, $whereClause = NULL)
	{
		if ($this->upload("update"))
		{
			$record = $this->selectId((int)$id);
			
			if (isset($this->values["alias"]))
				$this->checkAliasAll($id);
			
			// Salva informazioni meta della pagina
			$this->salvaMeta($record["meta_modificato"]);
			
			return parent::update($id, $whereClause);
		}
	}
	
	public function insert()
	{
		if ($this->upload("insert"))
		{
			if (isset($this->values["alias"]))
				$this->checkAliasAll(0);
			
			// Salva informazioni meta della pagina
			$this->salvaMeta(0);
			
			return parent::insert();
		}
	}
	
	public static function getUrlAlias($id, $lingua = null)
	{
		$t = new TagModel();
		
		$tag = $t->clear()->where(array(
			"id_tag"	=>	(int)$id,
		))->addJoinTraduzione($lingua)->send();
		
// 		$marchio = $this->selectId((int)$id);
		
		if (count($tag) > 0)
		{
			if (v("shop_in_alias_tag"))
			{
				$c = new CategoriesModel;
		
				$idShop = $c->getShopCategoryId();
				
				return tagfield($tag[0],"alias")."/".$c->getUrlAlias($idShop, $lingua);
			}
			else
				return tagfield($tag[0],"alias").".html";
		}
		
		return "";
	}
	
	public function selectPerFiltro()
	{
		return $this->clear()->toList("id_tag","titolo")->orderBy("titolo")->send();
	}
}
