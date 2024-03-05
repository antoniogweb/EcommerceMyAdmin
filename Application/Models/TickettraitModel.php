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

trait TickettraitModel
{
	public static $tipi = array(
		"immagine",
		"scontrino",
		"video",
	);
	
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
	
	public function setUploadFields()
	{
		if (isset($_FILES["filename"]["name"]) and strcmp($_FILES["filename"]["name"],'') !== 0)
		{
			$ext = $this->files->getFileExtension($_FILES["filename"]["name"]);
			
			if (Files_Upload::isImage($ext))
			{
				$this->uploadFields["filename"]["createImage"] = true;
				$this->uploadFields["filename"]["maxFileSize"] = v("dimensioni_upload_immagine_ticket");
			}
		}
	}
}
