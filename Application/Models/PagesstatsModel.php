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
		if (isset($_COOKIE["uid_stats"]) && $_COOKIE["uid_stats"] && (int)strlen($_COOKIE["uid_stats"]) === 32 && ctype_alnum((string)$_COOKIE["uid_stats"]))
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
	
	public function getIdsPagineViste($idPage = 0)
	{
		if( !session_id() )
			session_start();
		
		$idPages = $this->clear()->select("distinct id_page")->where(array(
			"cart_uid"	=>	sanitizeAll(User::$cart_uid),
		))->orderBy("id_page_stat desc")->limit(5)->toList("id_page")->send();
		
		if (isset($_SESSION["idPages"]) && is_array($_SESSION["idPages"]))
		{
			$sIdPages = forceIntDeep($_SESSION["idPages"]);
			
			array_unshift($_SESSION["idPages"], (int)$idPage);
// 			$_SESSION["idPages"][] = (int)$idPage;
		}
		else
			$_SESSION["idPages"] = array((int)$idPage);
		
		$idPages = array_merge($_SESSION["idPages"], $idPages);
		
		$idPages = array_unique($idPages);
		
		$idPages = array_slice($idPages, 0, 3);
		
// 		$idPages = array();
// 		$idPages[] = (int)$idPage;
		
		return forceIntDeep($idPages);
	}
	
	// Mostra i prodotti visti da altri clienti che hanno visto il prodotto attuale
	public function vistiDaAltriUtenti($idPages, $soglia = 3)
	{
		$params = $idPages;
		
		$queryIp = "";
		
		if (v("salva_ip_visualizzazione"))
		{
			$queryIp .= " AND pages_stats.ip != '' ";
			
			if (v("ip_sito"))
			{
				$queryIp .= " AND pages_stats.ip != ? ";
				
				$params[] = sanitizeAll(v("ip_sito"));
			}
		}
		
		$params = array_merge($params, $params);
		
		$now = new DateTime();
		$now->modify("-30 days");
		
		$params[] = $now->format("Y-m-d");
		
		$sql = "select p2.id_page,count(p2.id_page) as NUMERO from (select distinct cart_uid from pages_stats where id_page in (".$this->placeholdersFromArray($idPages).") $queryIp) as p1 inner join (select pages_stats.id_page,pages_stats.cart_uid,pages_stats.data_stat from pages_stats where pages_stats.id_page not in (".$this->placeholdersFromArray($idPages).") $queryIp group by pages_stats.cart_uid,pages_stats.id_page) as p2 on p1.cart_uid = p2.cart_uid where p2.data_stat >= ? group by p2.id_page having NUMERO >= ".(int)$soglia." order by count(p2.id_page) desc,p2.id_page desc;";
		
		return $this->clear()->query(array(
			$sql,
			$params
		));
	}
	
	public static function salvaSuFile($idPage = 0, $idC = 0, $idM = 0)
	{
		if (App::$operazioneSchedulata)
			return;
		
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
			"ip"		=>	getIp(),
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
						"ip"		=>	(isset($jsonArray["ip"]) && v("salva_ip_visualizzazione")) ? $jsonArray["ip"] : "",
					));
					
					if ($psModel->insert())
					{
// 						if ($log)
// 							$log->writeString("IMPORTATE STATISTICHE: ".$json);
						
						@unlink($fullPath."/".$file);
					}
				}
			}
			
			if (v("usa_transactions"))
				$psModel->db->commit();
		}
	}
	
}
