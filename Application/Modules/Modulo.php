<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
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

trait Modulo
{
	protected $params = array();
	
	protected $cacheAbsolutePath = null;
	protected $logsFolder = "Logs";
	
	public function __construct($record = array())
	{
		$this->params = $record;
		
		$this->cacheAbsolutePath = rtrim(str_replace("/admin","",LIBRARY),"/");
		
		if (!@is_dir($this->cacheAbsolutePath."/".$this->logsFolder))
		{
			createFolderFull($this->logsFolder, $this->cacheAbsolutePath);
			@chmod($this->cacheAbsolutePath."/".$this->logsFolder, octdec('777'));
		}
		
		$this->cacheAbsolutePath .= "/".$this->logsFolder;
		
		if (isset($this->params["codice"]) && trim($this->params["codice"]))
		{
			$moduleFullPath = $this->cacheAbsolutePath."/".trim($this->params["codice"]);
			
			if (!@is_dir($moduleFullPath))
			{
				createFolderFull(trim($this->params["codice"]), $this->cacheAbsolutePath);
			}
		}
	}
	
	public function getParams()
	{
		return $this->params;
	}
	
	public function gCampiForm()
	{
		return 'titolo,attivo,usa_token_sicurezza,token_sicurezza,query_string';
	}
	
	public function isAttivo()
	{
		return $this->params["attivo"];
	}
}
