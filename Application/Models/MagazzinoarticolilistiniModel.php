<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2026  Antonio Gallo (info@laboratoriolibero.com)
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

class MagazzinoarticolilistiniModel extends GenericModel
{
	public $salvaDataModifica = true;
	public $salvaIdInserimentoModifica = true;
	
	public static $idArticoloUltimaRigaOrdine = array();
	
	public function __construct()
	{
		$this->_tables = 'magazzino_articoli_listini';
		$this->_idFields = 'id_articolo_listino';
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'fornitore' => array("BELONGS_TO", 'FornitoriModel', 'id_fornitore',null,"CASCADE"),
			'import' => array("BELONGS_TO", 'FornitoriimportModel', 'id_import',null,"CASCADE"),
		);
    }
    
    public function inAcquistiCrud($record)
	{
		$maModel = new MagazzinoarticoliModel();
		
		$numero = $maModel->clear()->where(array(
			"OR"	=>	array(
				"gtin"	=>	sanitizeAll($record["magazzino_articoli_listini"]["gtin"]),
				"mpn"	=>	sanitizeAll($record["magazzino_articoli_listini"]["mpn"]),
			)
		))->rowNumber();
		
		if ($numero)
			return "<i class='fa fa-check text text-success'></i>";
		
		return "";
	}
}
