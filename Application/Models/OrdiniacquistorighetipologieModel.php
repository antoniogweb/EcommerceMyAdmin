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

class OrdiniacquistorighetipologieModel extends GenericModel
{
	public $campoTitolo = "titolo";
	public static $diTestata = array();
	
	public function __construct() {
		$this->_tables = 'ordini_acquisto_righe_tipologie';
		$this->_idFields = 'id_ordine_acquisto_riga_tipologia';
		
		$this->_idOrder='id_order';
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'righe' => array("HAS_MANY", 'OrdiniacquistorigheModel', 'id_ordine_acquisto_riga_tipologia', null, "RESTRICT", "L'elemento ha delle righe collegate e non può essere eliminato"),
		);
    }
    
    public static function numeroTipologiaInOrdine($idOrdine, $idTipologia)
	{
		$r = new OrdiniacquistorigheModel();
		
		return $r->clear()->where(array(
			"id_ordine_acquisto"				=>	(int)$idOrdine,
			"id_ordine_acquisto_riga_tipologia"	=>	(int)$idTipologia,
		))->rowNumber();
	}
	
	public static function checkInserimentoTipologiaInOrdine($idOrdine, $idTipologia)
	{
		$rt = new OrdiniacquistorighetipologieModel();
		
		$tipologia = $rt->selectId((int)$idTipologia);
		
		if (!empty($tipologia) && self::numeroTipologiaInOrdine($idOrdine, $idTipologia) < (int)$tipologia["max_numero_in_ordine"])
			return true;
		
		return false;
	}
	
	public static function rigaDiTestata($idTipologia)
	{
		$rt = new OrdiniacquistorighetipologieModel();
		
		if (!isset(self::$diTestata[$idTipologia]))
			self::$diTestata[$idTipologia] = (int)$rt->clear()->whereId((int)$idTipologia)->field("testata");
		
		return self::$diTestata[$idTipologia];
	}
}
