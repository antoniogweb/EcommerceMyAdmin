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

class MarchiModel extends GenericModel {

	public function __construct() {
		$this->_tables='marchi';
		$this->_idFields='id_marchio';
		
		$this->traduzione = true;
		
		$this->_lang = 'It';
		
		$this->_idOrder = 'id_order';
		
		$this->uploadFields = array(
			"immagine"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/marchi",
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg,gif',
				'allowedMimeTypes'	=>	'',
				"createImage"	=>	false,
				"maxFileSize"	=>	3000000,
// 				"clean_field"	=>	"clean_immagine",
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	100,
					'imgHeight'		=>	100,
					'defaultImage'	=>  null,
					'cropImage'		=>	'yes',
				),
			),
			"immagine_2x"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/marchi",
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg,gif',
				'allowedMimeTypes'	=>	'',
				"createImage"	=>	false,
				"maxFileSize"	=>	3000000,
// 				"clean_field"	=>	"clean_immagine",
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	100,
					'imgHeight'		=>	100,
					'defaultImage'	=>  null,
					'cropImage'		=>	'yes',
				),
			),
		);
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'prezzi' => array("HAS_MANY", 'PagesModel', 'id_marchio', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_marchio', null, "CASCADE"),
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
						"<div class='form_notice'>Indicazione del marchio nell'URL (se lasciato vuoto viene creato in automatico)</div>"
					),
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
			$this->salvaMeta($record["meta_modificato"], "descrizione");
			
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
			$this->salvaMeta(0, "descrizione");
			
			return parent::insert();
		}
	}
	
	// Controllo che la lingua esista
	public function controllaLingua($id)
	{
		$this->controllaLinguaGeneric($id, "id_marchio", "-marchio-");
	}
	
	public function getUrlAlias($id, $paginaDettaglioMarchio = false)
	{
		$marchio = $this->clear()->where(array(
			"id_marchio"	=>	(int)$id,
		))->addJoinTraduzione()->send();
		
// 		$marchio = $this->selectId((int)$id);
		
		if (count($marchio) > 0)
		{
			if (v("shop_in_alias_marchio") && !$paginaDettaglioMarchio)
			{
				$c = new CategoriesModel;
		
				$idShop = $c->getShopCategoryId();
				
				return mfield($marchio[0],"alias")."/".getCategoryUrlAlias($idShop);
			}
			else
				return mfield($marchio[0],"alias").".html";
		}
		
		return "";
	}
}
