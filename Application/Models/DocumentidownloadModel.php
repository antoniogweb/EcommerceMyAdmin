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

class DocumentidownloadModel extends GenericModel {

	public function __construct() {
		$this->_tables='documenti_download';
		$this->_idFields='id_doc_dow';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'documento' => array("BELONGS_TO", 'DocumentiModel', 'id_doc',null,"CASCADE"),
			'user' => array("BELONGS_TO", 'RegusersModel', 'id_user',null,"CASCADE"),
        );
    }
    
	public function salvaDownload($idDoc)
	{
		$documento = DocumentiModel::g()->clear()->selectId((int)$idDoc);
		
		if (!empty($documento))
		{
			$this->sValues(array(
				"id_doc"	=>	(int)$idDoc,
				"id_user"	=>	(int)User::$id,
			));
			
			$res = $this->insert();
			
			return $res;
		}
		
		return false;
	}
	
	public function filename($record)
    {
		return "<a target='_blank' href='".Url::getRoot()."documenti/documento/filename/".$record["documenti"]["id_doc"]."'>".$record["documenti"]["clean_filename"]."</a>";
    }
    
    public function utenteCrud($record)
	{
		if ($record[$this->_tables]["id_user"] && isset($record["regusers"]["username"]))
		{
			return self::getNominativo($record["regusers"])." - ".$record["regusers"]["username"];
		}
		
		return "";
	}
}
