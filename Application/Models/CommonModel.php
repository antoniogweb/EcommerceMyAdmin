<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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

trait CommonModel {
	
	public static $redirect = "";
	public static $redirectQueryString = "";
	
	public function controllaCF($controlla = 1)
	{
		if ($controlla)
		{
			if (isset($this->values["codice_fiscale"]) && isset($this->values["tipo_cliente"]) && isset($_POST["nazione"]) && $_POST["nazione"] == "IT")
			{
				if ($this->values["tipo_cliente"] == "privato" || $this->values["tipo_cliente"] == "libero_professionista")
				{
					if (!codiceFiscale($this->values["codice_fiscale"]))
					{
						$this->notice = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare il campo <b>Codice Fiscale</b>")."</div><span class='evidenzia'>class_codice_fiscale</span>".$this->notice;
						$this->result = false;
						return false;
					}
				}
			}
		}
		
		return true;
	}
	
	public function controllaPIva($controlla = 1)
	{
		if (v("controlla_p_iva") && $controlla)
		{
			if (isset($this->values["p_iva"]) && isset($this->values["tipo_cliente"]) && isset($_POST["nazione"]) && $this->values["tipo_cliente"] != "privato")
			{
				include(ROOT."/admin/External/ddeboervatin/vendor/autoload.php");
				
				$validator = new Ddeboer\Vatin\Validator();
				
				if ($validator->isValidCountryCode($_POST["nazione"]))
				{
					$stringa = substr($this->values["p_iva"],0,2) == $_POST["nazione"] ? $this->values["p_iva"] : $_POST["nazione"].$this->values["p_iva"];
					
					$res = $validator->isValid($stringa);
					
					if (!$res)
					{
						$this->notice = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare il campo <b>Partita Iva</b>")."</div><span class='evidenzia'>class_p_iva</span>".$this->notice;
						$this->result = false;
						return false;
					}
				}
			}
		}
		
		return true;
	}
	
	public static function getUrlContenuto($p)
	{
		$className = get_called_class();
		
		$model = new $className();
		
		$tableName = $model->table();
		
		$url = "";
		if ($p[$tableName]["link_id_page"])
			$url = Url::getRoot().getUrlAlias($p[$tableName]["link_id_page"]);
		else if ($p[$tableName]["link_id_c"])
			$url = Url::getRoot().getCategoryUrlAlias($p[$tableName]["link_id_c"]);
		else if ($p[$tableName]["link_id_marchio"])
			$url = Url::getRoot().getMarchioUrlAlias($p[$tableName]["link_id_marchio"]);
		else if ($p[$tableName]["link_id_tag"])
			$url = Url::getRoot().TagModel::getUrlAlias($p[$tableName]["link_id_tag"]);
		else if ($p[$tableName]["link_id_documento"])
			$url = Url::getRoot().DocumentiModel::getUrlAlias($p[$tableName]["link_id_documento"]);
		else if (field($p, "url") && $tableName == "pages")
			$url = checkHttp(field($p, "url"));
		else if (field($p, "go_to") && $tableName == "pages")
			$url = "#".field($p, "go_to");
		
		return $url;
	}
	
	public static function eliminaCartella($path)
	{
		$dir = $path;
		
		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
			RecursiveIteratorIterator::CHILD_FIRST
		);

		foreach ($files as $fileinfo) {
			$todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
			
			call_user_func($todo, $fileinfo->getRealPath());
		}

		rmdir($dir);
	}
	
	public static function creaCartellaImages($path = null, $htaccess = false, $index = true)
	{
		//crea la cartella images se non c'è
		if(!is_dir(Domain::$parentRoot."/images"))
		{
			if (@mkdir(Domain::$parentRoot."/images"))
			{
				$fp = fopen(Domain::$parentRoot."/images/index.html", 'w');
				fclose($fp);
			}
		}
		
		if (!$path)
			return;
		
		//crea la cartella se non c'è
		if(!is_dir(Domain::$parentRoot."/".$path))
		{
			if (@mkdir(Domain::$parentRoot."/".$path))
			{
				if ($index)
				{
					$fp = fopen(Domain::$parentRoot."/".$path.'/index.html', 'w');
					fclose($fp);
				}
				
				if ($htaccess)
				{
					$fp = fopen(Domain::$parentRoot."/".$path.'/.htaccess', 'w');
					fwrite($fp, 'deny from all');
					fclose($fp);
				}
			}
		}
	}
	
	public function gUrlSito($lingua = null)
	{
		$linguaUrl = $lingua ? "/$lingua/" : "/";
		
		return rtrim(Url::getFileRoot(),"/").$linguaUrl;
	}
	
	public function forzaBloccato()
	{
		$this->values["bloccato"] = 1;
		$this->values["attivo"] = "N";
	}
	
	public static function getRedirect()
	{
		$r = new Request();
		
		$redirect = $r->get('redirect','','sanitizeAll');
		$redirect = ltrim($redirect,"/");
		
		//valori permessi per il redirect
		$allowedRedirect = explode(",",v("redirect_permessi"));
		
		if (is_numeric($redirect))
		{
			$p = new PagesModel();
			
			$page = $p->selectId((int)$redirect);
			
			if (!empty($page))
				$redirect = (int)$redirect;
			else
				$redirect = '';
		}
		else
		{
			if (!in_array($redirect,$allowedRedirect))
				$redirect = '';
		}
		
		self::$redirect = $redirect;
		self::$redirectQueryString = $redirect ? "?redirect=$redirect" : "";
		
		return $redirect;
	}
	
	public static function getUrlRedirect()
	{
		if (strcmp(self::$redirect,'') !== 0)
		{
			if (is_numeric(self::$redirect))
				$urlRedirect = Url::getRoot().getUrlAlias((int)self::$redirect);
			else
				$urlRedirect = Url::getRoot().self::$redirect;
			
			return $urlRedirect;
		}
		
		return '';
	}
	
	public static function generaPassword()
	{
		return generateString(10, "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-!");
	}
	
	public function settaPassword()
	{
		if (v("genera_e_invia_password") && !v("permetti_acquisto_anonimo"))
		{
			$randPass = self::generaPassword();
			$_POST["password"] = $_POST["confirmation"] = $randPass;
		}
	}
	
	public static function resettaCredenziali($idUser)
	{
		$res = false;
		
		$r = new RegusersModel();
		
		$password = self::generaPassword();
		
		$cliente = $r->selectId((int)$idUser);
		
		if (!empty($cliente))
		{
			$r->setValues(array(
				"password"	=>	sanitizeAll(call_user_func(PASSWORD_HASH,$password)),
			));
			
			if ($r->pUpdate((int)$idUser))
			{
				$res = MailordiniModel::inviaCredenziali($idUser, array(
					"username"	=>	$cliente["username"],
					"password"	=>	$password,
				));
			}
		}
		
		return $res;
	}
	
	public function redirectVersoAreaRiservata()
	{
		$h = new HeaderObj();
		
		$h->redirect(v("url_redirect_dopo_login"));
	}
	
	public static function camboObbligatorio($campo, $controller, $azione = "insert")
	{
		if ($campo == "telefono")
		{
			if ($controller == "ordini" && !v("insert_ordine_telefono_obbligatorio"))
				return false;
			else if ($controller == "regusers" && $azione == "insert" && !v("insert_account_telefono_obbligatorio"))
				return false;
		}
		
		return true;
	}
	
	public static function asterisco($campo, $controller, $azione = "insert")
	{
		if (self::camboObbligatorio($campo, $controller, $azione))
			return "*";
		
		return "";
	}
}
