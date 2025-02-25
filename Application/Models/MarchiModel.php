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

class MarchiModel extends GenericModel
{
	use CrudModel;
	
	public static $strutturaMarchi = [];
	
	public static $uploadFile = true;
	
	public static $currentId = 0;
	
	public static $elencoAliasId = null;

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
			'pagine' => array("HAS_MANY", 'PagesModel', 'id_marchio', null, "RESTRICT", "L'elemento ha delle relazioni e non può essere eliminato"),
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_marchio', null, "CASCADE"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'attivo'	=>	self::$entryAttivo,
				'alias'	=>	array(
					'labelString'=>	'Alias per URL',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>Indicazione del marchio nell'URL (se lasciato vuoto viene creato in automatico)</div>"
					),
				),
				'nazione'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectNazione(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'in_evidenza'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'In evidenza?',
					'options'	=>	array('no'=>'N','sì'=>'Y'),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Verrà evidenziato nel sito (in home, nei menù, etc), in funzione del tema")."</div>"
					),
				),
				'nuovo'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Marcato come nuovo?',
					'options'	=>	array('no'=>'N','sì'=>'Y'),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se viene indicato come nuovo nel sito, in funzione del tema")."</div>"
					),
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
    public function update($id = NULL, $whereClause = NULL)
	{
		if (!self::$uploadFile || $this->upload("update"))
		{
			$record = $this->selectId((int)$id);
			
			if (isset($this->values["alias"]))
				$this->checkAliasAll($id);
			
			// Salva informazioni meta della pagina
			$this->salvaMeta($record["meta_modificato"], "descrizione");
			
			return parent::update($id, $whereClause);
		}
		
		return false;
	}
	
	public function insert()
	{
		if (!self::$uploadFile || $this->upload("insert"))
		{
			if (isset($this->values["alias"]))
				$this->checkAliasAll(0);
			
			// Salva informazioni meta della pagina
			$this->salvaMeta(0, "descrizione");
			
			return parent::insert();
		}
		
		return false;
	}
	
	public function nazione($record)
	{
		if ($record["marchi"]["nazione"] != "")
			return findTitoloDaCodice($record["marchi"]["nazione"]);
		
		return "";
	}
	
	// Controllo che la lingua esista
	public function controllaLingua($id)
	{
		$this->controllaLinguaGeneric($id, "id_marchio", "-marchio-");
	}
	
	public function getUrlAlias($id, $paginaDettaglioMarchio = false, $lingua = null)
	{
		$marchio = $this->clear()->where(array(
			"id_marchio"	=>	(int)$id,
		))->addJoinTraduzione($lingua)->send();
		
// 		$marchio = $this->selectId((int)$id);
		
		if (count($marchio) > 0)
		{
			if (v("shop_in_alias_marchio") && !$paginaDettaglioMarchio)
			{
				$c = new CategoriesModel;
		
				$idShop = $c->getShopCategoryId();
				
				if (v("marchio_prima_della_categoria_in_url"))
					return mfield($marchio[0],"alias")."/".$c->getUrlAlias($idShop, $lingua);
				else
				{
					Parametri::$useHtmlExtension = false;
					$aliasShop = $c->getUrlAlias($idShop);
					Parametri::$useHtmlExtension = true;
					return $aliasShop."/".mfield($marchio[0],"alias").v("estensione_url_categorie");
				}
			}
			else
				return mfield($marchio[0],"alias").v("estensione_url_categorie");
		}
		
		return "";
	}
	
	public static function getDataMarchio($idMarchio)
	{
		if (isset(self::$strutturaMarchi[$idMarchio]))
			return self::$strutturaMarchi[$idMarchio];
		
		$m = new MarchiModel();
		
		$res = $m->clear()->addJoinTraduzione()->send();
		
		foreach ($res as $marchio)
		{
			self::$strutturaMarchi[$marchio["marchi"]["id_marchio"]] = $marchio;
		}
		
		if (isset(self::$strutturaMarchi[$idMarchio]))
			return self::$strutturaMarchi[$idMarchio];
		
		return array();
	}

	public static function getElencoAliasId()
	{
		if (!isset(self::$elencoAliasId))
		{
			$mModel = new MarchiModel();

			self::$elencoAliasId = $mModel->clear()->select("id_marchio,alias")->toList("alias", "id_marchio")->send();
		}

		return self::$elencoAliasId;
	}
}
