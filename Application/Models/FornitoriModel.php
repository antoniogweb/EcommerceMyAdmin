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

class FornitoriModel extends GenericModel
{
	use AcquistoModel;
	
	public $campoTitolo = "ragione_sociale";
	public $salvaDataModifica = true;
	public $salvaIdInserimentoModifica = true;
	
	public function __construct() {
		$this->_tables = 'fornitori';
		$this->_idFields = 'id_fornitore';
		
		$this->_idOrder='id_order';
		
		$this->addStrongCondition("both",'checkNotEmpty',"ragione_sociale");
		$this->addSoftCondition("both",'checkMail',"email,email_amministrativa,pec,email_referente");
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'ordini' => array("HAS_MANY", 'OrdiniacquistoModel', 'id_fornitore', null, "RESTRICT", "L'elemento ha degli ordini di acquisto collegati e non può essere eliminato"),
		);
    }
    
    public function filtroFornitore()
	{
		return $this->select("id_fornitore,ragione_sociale")->orderBy("ragione_sociale")->toList("id_fornitore", "ragione_sociale")->send();
	}
}
