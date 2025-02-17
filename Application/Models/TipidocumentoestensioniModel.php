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

class TipidocumentoestensioniModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'tipi_documento_estensioni';
		$this->_idFields = 'id_tipo_doc_est';
		
		$this->addStrongCondition("both",'checkNotEmpty',"estensione");
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'tipo_doc' => array("BELONGS_TO", 'TipidocumentoModel', 'id_tipo_doc',null,"CASCADE"),
        );
    }
    
    public static function cercaTipoDocumentoDaEstensione($ext)
    {
		$tde = new TipidocumentoestensioniModel();
		
		$res = $tde->clear()->select("tipi_documento.id_tipo_doc")->inner(array("tipo_doc"))->where(array(
			"estensione"	=>	sanitizeDb($ext)
		))->send();
		
		if (count($res) > 0)
			return $res[0]["tipi_documento"]["id_tipo_doc"];
		
		return 0;
    }
    
}
