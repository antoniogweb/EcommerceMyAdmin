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

class ImmaginiarchiviModel extends GenericModel
{
	public $campoTitolo = "immagine";
	
	public function __construct() {

		$this->_tables='immagini_archivi';
		$this->_idFields='id_immagine_archivio';

		$this->orderBy = 'immagini.id_order';

		$this->_idOrder = 'id_order';

		parent::__construct();

		$this->files->setBase(Domain::$parentRoot.'/'.Parametri::$cartellaImmaginiArchivi);
	}
	
	public function relations() {
		return array(
			'categoria' => array("BELONGS_TO", 'CategoriesModel', 'id_c',null,"CASCADE"),
			'marchio' => array("BELONGS_TO", 'MarchiModel', 'id_marchio',null,"CASCADE"),
			'tag' => array("BELONGS_TO", 'CategoriesModel', 'id_tag',null,"CASCADE"),
		);
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'id_immagine_tipologia'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Tipologia",
					"options"	=>	$this->selectTipologiaImmagine(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
		);
	}
	
	public function selectTipologiaImmagine()
	{
		$itModel = new ImmaginitipologieModel();
		
		if (isset($_GET["contesto"]) && is_string($_GET["contesto"]) && in_array($_GET["contesto"], ImmaginitipologieModel::$contesti))
		{
			$itModel->aWhere(array(
				"contesto"	=>	sanitizeAll($_GET["contesto"]),
			));
		}
		
		return array(0	=>	"--") + $itModel->orderBy("id_order")->toList("id_immagine_tipologia", "titolo")->send();
	}
}
