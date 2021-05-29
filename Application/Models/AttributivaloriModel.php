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

class AttributivaloriModel extends GenericModel {

	public $lId = 0;
	
	public static $names = array();
	
	public function __construct() {
		$this->_tables='attributi_valori';
		$this->_idFields='id_av';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'attributi_valori.id_order';
		$this->_lang = 'It';
		
		$this->traduzione = true;
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		$this->uploadFields = array(
			"immagine"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/valori_attributi",
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
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_av', null, "CASCADE"),
			'attributo' => array("BELONGS_TO", 'AttributiModel', 'id_a',null,"CASCADE"),
        );
    }
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	// Restituisce un array per la tendina del filtro
	public function selectPerFiltro($idA)
	{
		return $this->clear()->where(array(
			"id_a"	=>	(int)$idA,
		))->orderBy("titolo")->toList("id_av","titolo")->send();
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
			$res = $this->clear()->where(array("id_av"=>$clean["id_av"]))->addJoinTraduzione()->first();
			
			if (!empty($res))
			{
				$titolo = avfield($res, "titolo");
				self::$names[$clean["id_av"]] = $titolo;
				return $titolo;
			}
		}
		return "";
	}
	
	public function del($id = null, $whereClause = null)
	{
		$clean['id'] = (int)$id;

		$comb = new CombinazioniModel();
		
		$where = array(
			'OR' => array(
				"col_1"	=>	$clean['id'],
				"col_2"	=>	$clean['id'],
				"col_3"	=>	$clean['id'],
				"col_4"	=>	$clean['id'],
				"col_5"	=>	$clean['id'],
				"col_6"	=>	$clean['id'],
				"col_7"	=>	$clean['id'],
				"col_8"	=>	$clean['id'],
			),
		);
		
		$res = $comb->clear()->select("distinct pages.id_page")->inner("pages")->using("id_page")->where($where)->send();
		
		if (count($res) > 0)
		{
			$this->notice = "<div class='alert'>Questo valore è usato in una combinazione di uno o più prodotti e non può quindi essere cancellato se prima non si eliminano tali combinazioni.<br/>Elenco prodotti che usano tale attributo:<ul>";
			foreach ($res as $r)
			{
				$this->notice .= "<li><a target='_blank' href='http://".DOMAIN_NAME."/pages/attributi/".$r["pages"]["id_page"]."#refresh_link'>".$r["pages"]["title"]."</a></li>";
			}
			$this->notice .= "</ul></div>";
			$this->result = false;
		}
		else
		{
			return parent::del($clean["id"]);
		}
	}
	
	public function update($id = NULL, $whereClause = NULL)
	{
		if ($this->upload("update"))
		{
			return parent::update($id, $whereClause);
		}
	}
	
	public function insert()
	{
		if ($this->upload("insert"))
		{
			return parent::insert();
		}
	}
	
	public function thumb($record)
	{
		$html = "";
		
		if ($record["attributi_valori"]["immagine"] && file_exists(Domain::$parentRoot."/images/valori_attributi/".$record["attributi_valori"]["immagine"]))
			$html .= "<a target='_blank' href='".Domain::$name."/images/valori_attributi/".$record["attributi_valori"]["immagine"]."'><img src='".Url::getRoot()."attributivalori/thumb/immagine/".$record["attributi_valori"]["id_av"]."' /></a>";
		
		return $html;
	}
	
	public function edit($record)
	{
		if ($record["attributi_valori"]["id_av"])
			return "<a class='iframe action_iframe' href='".Url::getRoot()."/attributivalori/form/update/".$record["attributi_valori"]["id_av"]."?partial=Y&nobuttons=N'>".$record["attributi_valori"]["titolo"]."</a>";
	}
	
	public static function getTipo($idAV)
	{
		$av = new AttributivaloriModel();
		
		return $av->select("attributi.tipo")->inner(array("attributo"))->where(array(
			"id_av"	=>	(int)$idAV,
		))->field("attributi.tipo");
	}
}
