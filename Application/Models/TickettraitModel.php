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

trait TickettraitModel
{
	public static $allowedImgExtensions = "png,jpg,jpeg";
	public static $allowedImgMimeTypes = "image/jpeg,image/png";
	
	public static $tipi = array(
		"immagine",
		"scontrino",
		"video",
	);
	
	public function getAllowedExtensionMimeTypes()
	{
		$allowedExtensions = self::$allowedImgExtensions;
		$allowedMimeTypes = self::$allowedImgMimeTypes;
		
		if (v("permetti_il_caricamento_di_video_nei_ticket"))
		{
			$allowedExtensions .= ",".v("ticket_video_extensions");
			$allowedMimeTypes .= ",".v("ticket_video_mime_types");
		}
		
		return array($allowedExtensions, $allowedMimeTypes);
	}
	
	public function setTipo()
	{
		$estensioniVideo = explode(",", v("ticket_video_mime_types"));
		
		if (isset($this->values["mime_type"]) && in_array($this->values["mime_type"], $estensioniVideo))
		{
			$this->values["tipo"] = "VIDEO";
		}
	}
	
	public function setEstensioneEMimeType()
	{
		$filePath = $this->files->getBase()."/".$this->files->fileName;
		
		$this->setValue("estensione", $this->files->ext);
		$this->setValue("mime_type", $this->files->getContentType($filePath));
	}
	
	public function setUploadFields($tipo = null, $forzaEstensioneEMimeType = true)
	{
		if (isset($_FILES["filename"]["name"]) and strcmp($_FILES["filename"]["name"],'') !== 0)
		{
			$ext = $this->files->getFileExtension($_FILES["filename"]["name"]);
			
			if (Files_Upload::isImage($ext))
			{
				$this->uploadFields["filename"]["createImage"] = true;
				$this->uploadFields["filename"]["maxFileSize"] = v("dimensioni_upload_immagine_ticket");
			}
			
			if (!$forzaEstensioneEMimeType)
				return;
			
			if (isset($tipo) && $tipo == "VIDEO")
			{
				$this->uploadFields["filename"]["allowedExtensions"] = v("ticket_video_extensions");
				$this->uploadFields["filename"]["allowedMimeTypes"] = v("ticket_video_mime_types");
			}
			else
			{
				$this->uploadFields["filename"]["allowedExtensions"] = self::$allowedImgExtensions;
				$this->uploadFields["filename"]["allowedMimeTypes"] = self::$allowedImgMimeTypes;
			}
		}
	}
	
	public function upload($type = "update")
	{
		$res = parent::upload($type);
		
		if ($res)
		{
			self::creaCartellaImages("images/ticket_video", true);
			
			$filePath = $this->files->getBase()."/".$this->files->fileName;
			$mimeType = $this->files->getContentType($filePath);
			
			$estensioniVideo = explode(",", v("ticket_video_mime_types"));
			
			if (in_array($mimeType,$estensioniVideo))
			{
				$fileNameTxt = $this->files->fileName.".txt";
				
				file_put_contents(Domain::$parentRoot."/images/ticket_video/".$fileNameTxt, "");
			}
		}
		
		return $res;
	}
	
	public static function daElaborare($file)
	{
		$fileUpload = new Files_Upload(Domain::$parentRoot."/images/ticket_video");
		
		$fileNameTxt = (string)sanitizeHtml($file).".txt";
		
		if (file_exists(Domain::$parentRoot."/images/ticket_video/$fileNameTxt"))
			return true;
		
		return false;
	}
	
	public static function fileEsistente($fileName)
	{
		if (file_exists(Domain::$parentRoot."/images/ticket_immagini/$fileName"))
			return true;
		
		return false;
	}
	
	public function fileEsistenteInDb($file)
	{
		return $this->clear()->where(array(
			"filename"	=>	sanitizeAll($file),
		))->rowNumber();
	}
}
