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

class TicketfileModel extends GenericModel
{
	use TickettraitModel;
	
	public static $tipi = array(
		"immagine",
		"scontrino",
		"video",
	);
	
	public function __construct() {
		$this->_tables = 'ticket_file';
		$this->_idFields = 'id_ticket_file';
		
		$this->_idOrder = 'id_order';
		
		$allowedExtensions = 'png,jpg,jpeg';
		$allowedMimeTypes = 'image/jpeg,image/png';
		
		if (v("permetti_il_caricamento_di_video_nei_ticket"))
		{
			$allowedExtensions .= ",".v("ticket_video_extensions");
			$allowedMimeTypes .= ",".v("ticket_video_mime_types");
		}
		
		$this->uploadFields = array(
			"filename"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/ticket_immagini",
				"allowedExtensions"	=>	$allowedExtensions,
				'allowedMimeTypes'	=>	$allowedMimeTypes,
				"createImage"	=>	false,
				"maxFileSize"	=>	v("dimensioni_upload_video_ticket"),
				"clean_field"	=>	"clean_filename",
			),
		);
		
		parent::__construct();
	}
	
	public function insert()
	{
		$this->setTipo();
		
		$this->setEstensioneEMimeType();
		
		return parent::insert();
	}
	
	public function relations() {
        return array(
			'ticket' => array("BELONGS_TO", 'TicketModel', 'id_ticket',null,"RESTRICT","Si prega di selezionare un ticket di assistenza"),
        );
    }
}
