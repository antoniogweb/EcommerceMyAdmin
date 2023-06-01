<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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

class EventiretargetingelementiModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='eventi_retargeting_elemento';
		$this->_idFields='id_evento_elemento';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'evento' => array("BELONGS_TO", 'EventiretargetingModel', 'id_evento',null,"CASCADE"),
			'mail' => array("BELONGS_TO", 'MailordiniModel', 'id_mail',null,"CASCADE"),
		);
    }
    
    public function inviata($record)
    {
		if ($record["mail_ordini"]["inviata"])
			return "<i class='text text-success fa fa-thumbs-up'></i>";
		else
			return "<i class='text text-danger fa fa-thumbs-down'></i>";
    }
	
	public static function getElemento($idElemento, $tabellaElemento)
	{
		$ere = new EventiretargetingelementiModel();
		
		return $ere->clear()->where(array(
			"id_elemento"		=>	(int)$idElemento,
			"tabella_elemento"	=>	sanitizeAll($tabellaElemento),
		))->record();
	}
	
	public function dettagliElementoCrud($record)
	{
		if ($record["eventi_retargeting_elemento"]["model"])
		{
			$eModel = new $record["eventi_retargeting_elemento"]["model"];
			
			if (method_exists($eModel, "dettagliElementoCrud"))
			{
				return $eModel->dettagliElementoCrud($record);
			}
		}
		
		return "";
	}
	
}
