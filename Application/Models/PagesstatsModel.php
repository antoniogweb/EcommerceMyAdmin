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

class PagesstatsModel extends GenericModel {
	
	public static $folder = "Statistiche";
	
	public $parentRootFolder;
	
	public function __construct() {
		$this->_tables='pages_stats';
		$this->_idFields='id_page_stat';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'page' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
			'contatto' => array("BELONGS_TO", 'ContattiModel', 'id_contatto',null,"CASCADE"),
			'utente' => array("BELONGS_TO", 'RegusersModel', 'id_user',null,"CASCADE"),
        );
    }
    
    public function aggiungi($idPage)
    {
		$this->setValues(array(
			"id_page"	=>	$idPage,
		));
		
		$c = new ContattiModel();
		$contatto = $c->getDatiContatto();
		
		if (!empty($contatto))
			$this->values["id_contatto"] = (int)$contatto["id_contatto"];
		else if (isset($_SESSION["email_carrello"]))
		{
			$contatto = $c->clear()->where(array(
				"email"	=>	sanitizeAll($_SESSION["email_carrello"]),
			))->record();
			
			if (!empty($contatto))
				$this->values["id_contatto"] = (int)$contatto["id_contatto"];
		}
		
		$this->values["id_user"] = (int)User::$id;
		$this->values["data_stat"] = date("Y-m-d");
		$this->values["uid_stats"] = sanitizeAll($this->getStatsUid());
		
		if (v("ecommerce_attivo") && isset(User::$cart_uid) && User::$cart_uid)
			$this->values["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$this->insert();
    }
    
    private function getStatsUid()
	{
		if (isset($_COOKIE["uid_stats"]))
			return $_COOKIE["uid_stats"];
		else
		{
			$token = md5(randString(9).microtime().uniqid(mt_rand(),true));
			$time = time() + v("durata_statistiche_cookie");
			$_COOKIE["uid_stats"] = $token;
			Cookie::set("uid_stats", $token, $time, "/");
			return $token;
		}
	}
	
	public function contatto($record)
	{
		if ($record["contatti"]["email"])
		{
			$contatto = $record["contatti"];
			
			return $contatto["nome"]." ".$contatto["cognome"]." - ".$contatto["email"];
		}
		
		return "--";
	}
	
	public function cliente($record)
	{
		if ($record["regusers"]["username"])
		{
			$contatto = $record["regusers"];
			
			return $contatto["nome"]." ".$contatto["cognome"]." - ".$contatto["username"];
		}
		
		return "--";
	}
	
	public static function salvaSuFile($idPage = 0, $idC = 0, $idM = 0)
	{
		createFolderFull("Logs/".self::$folder);
		
		$token = randomToken();
		
		$fullPath = ROOT."/Logs/".self::$folder."/".$token.".log";
		
		$json = json_encode(array(
			"cart_uid"	=>	User::$cart_uid,
			"id_page"	=>	$idPage,
			"id_c"		=>	$idC,
			"idM"		=>	$idM,
			"lingua"	=>	Params::$lang,
			"data_creazione"	=>	date("Y-m-d H:i:s"),
		));
		
		FilePutContentsAtomic($fullPath, $json);
	}
	
	public static function importaDaFile($log = null)
	{
		$fullPath = ROOT."/../Logs/".self::$folder;
		
		if (@is_dir($fullPath))
		{
			$psModel = new PagesstatsModel();
			
			$filesModel = new Files_Upload($fullPath);
			
			$filesModel->listFiles();
			
			$files = $filesModel->getFiles();
			
			if (v("usa_transactions"))
				$psModel->db->beginTransaction();
			
			foreach ($files as $file)
			{
				$ext = $filesModel->getFileExtension($file);
				
				if ($ext == "log")
				{
					$json = file_get_contents($fullPath."/".$file);
					
					$jsonArray = json_decode($json, true);
					
					$psModel->sValues(array(
						"id_page"	=>	$jsonArray["id_page"] ?? 0,
						"cart_uid"	=>	$jsonArray["cart_uid"] ?? "",
						"uid_stats"	=>	$jsonArray["cart_uid"] ?? "",
						"data_creazione"	=>	$jsonArray["data_creazione"] ?? date("Y-m-d H:i:s"),
						"data_stat"	=>	isset($jsonArray["data_creazione"]) ? date("Y-m-d", strtotime($jsonArray["data_creazione"])) : date("Y-m-d"),
					));
					
					if ($psModel->insert())
					{
						if ($log)
							$log->writeString("IMPORTATE STATISTICHE: ".$json);
						
						@unlink($fullPath."/".$file);
					}
				}
			}
			
			if (v("usa_transactions"))
				$psModel->db->commit();
		}
	}
	
}
