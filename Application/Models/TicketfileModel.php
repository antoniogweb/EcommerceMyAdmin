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

class TicketfileModel extends GenericModel
{
	use TickettraitModel;
	
	public static $tipi = array(
		"immagine",
		"scontrino",
		"video",
	);
	
	public static $maxNumero = array(
		"immagine"	=>	5,
		"scontrino"	=>	1,
		"video"		=>	1,
	);
	
	public function __construct() {
		$this->_tables = 'ticket_file';
		$this->_idFields = 'id_ticket_file';
		
		$this->_idOrder = 'id_order';
		
		list($allowedExtensions, $allowedMimeTypes) = $this->getAllowedExtensionMimeTypes();
		
		$this->uploadFields = array(
			"filename"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/ticket_immagini",
				"allowedExtensions"	=>	$allowedExtensions,
				'allowedMimeTypes'	=>	$allowedMimeTypes,
				"createImage"	=>	false,
				"createImageParams"	=>	array(
					"imgWidth"	=>	800,
					"imgHeight"	=>	800,
					"jpegImgQuality"	=>	60,
				),
				"maxFileSize"	=>	v("dimensioni_upload_video_ticket"),
				"clean_field"	=>	"clean_filename",
				"disallow"		=>	true,
			),
		);
		
		self::$maxNumero["immagine"] = v("ticket_max_immagini");
		self::$maxNumero["video"] = v("ticket_max_video");
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'ticket' => array("BELONGS_TO", 'TicketModel', 'id_ticket',null,"RESTRICT","Si prega di selezionare un ticket di assistenza"),
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
	
	public function insert()
	{
		if ($this->upload("insert"))
		{
			$this->setEstensioneEMimeType();
			
			$this->setTipo();
			
			if (parent::insert())
				return true;
		}
		
		return false;
	}
	
	public function del($id = null, $where = null)
	{
		$record = $this->selectId((int)$id);
		
		$res = parent::del($id, $where);
		
		if ($res && !empty($record))
		{
			@unlink(Domain::$parentRoot."/images/ticket_immagini/".$record["filename"]);
			
			$fileNameTxt = $this->files->getNameWithoutFileExtension($record["filename"]).".txt";
			
// 			if (file_exists(Domain::$parentRoot."/images/ticket_video/$fileNameTxt"))
// 				@unlink(Domain::$parentRoot."/images/ticket_video/$fileNameTxt");
		}
		
		return $res;
	}
	
	public function checkId($idFile, $idTicket)
	{
		return $this->clear()->where(array(
			"id_ticket"			=>	(int)$idTicket,
			"id_ticket_file"	=>	(int)$idFile,
		))->rowNumber();
	}
	
	public function getFiles($idTicket, $tipi = array())
	{
		$this->clear()->select("*")->inner(array("ticket"))->where(array(
			"id_ticket"	=>	(int)$idTicket,
		));
		
		if (!empty($tipi))
			$this->aWhere(array(
				"IN"	=>	array(
					"tipo"	=>	sanitizeAllDeep($tipi),
				),
			));
		
		return $this->orderBy("ticket_file.id_order")->send();
	}
	
	public function thumbCrud($record)
	{
		if (!TicketfileModel::fileEsistente($record["ticket_file"]["filename"]))
			return gtext("File eliminato");
		
		if ($record["ticket_file"]["tipo"] != "VIDEO")
		{
			return "<a target='_blank' href='".Url::getFileRoot()."thumb/immagineticket/".$record["ticket_file"]["filename"]."'><img src='".Url::getFileRoot()."thumb/immagineticket/".$record["ticket_file"]["filename"]."' /></a>";
		}
		
		return "";
	}
	
	public function filenameCrud($record)
	{
		if (!TicketfileModel::fileEsistente($record["ticket_file"]["filename"]))
			return gtext("File eliminato");
		
		if ($record["ticket_file"]["tipo"] != "VIDEO")
		{
			return "<a target='_blank' href='".Url::getFileRoot()."thumb/immagineticket/".$record["ticket_file"]["filename"]."'>".$record["ticket_file"]["clean_filename"]."</a>";
		}
		else
		{
			if (self::daElaborare($record["ticket_file"]["filename"]))
				return $record["ticket_file"]["clean_filename"]." (<i>".gtext("in elaborazione")."</i>)";
			else
				return "<a target='_blank' href='".Domain::$publicUrl."/ticket/scarica/".$record["ticket_file"]["filename"]."'>".$record["ticket_file"]["clean_filename"]."</a>";
		}
	}
}
