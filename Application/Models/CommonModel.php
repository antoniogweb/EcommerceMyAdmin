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
					if (trim($this->values["codice_fiscale"]) && !codiceFiscale($this->values["codice_fiscale"]))
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
		
		return Domain::$publicUrl.$linguaUrl;
	}
	
	public function forzaBloccato()
	{
		$this->values["bloccato"] = 1;
		$this->values["attivo"] = "N";
	}
	
	public static function getRedirect($char = "?", $soloRedirectPermessi = false)
	{
		$r = new Request();
		
		$redirect = $r->get('redirect','','sanitizeAll');
		$redirect = ltrim($redirect,"/");
		
		//valori permessi per il redirect
		$allowedRedirect = explode(",",v("redirect_permessi"));
		
		if (is_numeric($redirect) && !$soloRedirectPermessi)
		{
			if (PagesModel::isAttivaTrue((int)$redirect))
				$redirect = (int)$redirect;
			else
				$redirect = '';
		}
		else if (preg_match('/^([0-9]{1,10})\-([0-9]{1,10})$/',$redirect, $matches) && !$soloRedirectPermessi)
		{
			$idPage = $matches[1];
			$idProd = $matches[2];
			
			$redirect = '';
			
			if (PagesModel::isAttivaTrue($idPage) && PagesModel::isAttivaTrue($idProd))
				$redirect = (int)$idPage."-".(int)$idProd;
		}
		else
		{
			if (!in_array($redirect,$allowedRedirect))
				$redirect = '';
		}
		
		self::$redirect = $redirect;
		self::$redirectQueryString = $redirect ? $char."redirect=$redirect" : "";
		
		return $redirect;
	}
	
	public static function getUrlRedirect()
	{
		if (strcmp(self::$redirect,'') !== 0)
		{
			if (is_numeric(self::$redirect))
				$urlRedirect = Url::getRoot().getUrlAlias((int)self::$redirect);
			else if (preg_match('/^([0-9]{1,10})\-([0-9]{1,10})$/',self::$redirect, $matches))
				$urlRedirect = Url::getRoot().getUrlAlias((int)$matches[1])."?".v("var_query_string_id_rif")."=".(int)$matches[2];
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
		
		if ($campo == "codice_fiscale")
		{
			if (!v("abilita_codice_fiscale"))
				return false;
			
			if (isset($_POST["nazione"]) && $_POST["nazione"] == "IT")
			{
				if (v("codice_fiscale_obbligatorio_solo_se_fattura") && isset($_POST["tipo_cliente"]) && $_POST["tipo_cliente"] == "privato" && !isset($_POST["fattura"]))
					return false;
				
				if ($controller == "ordini")
					return true;
				
				if ($controller == "regusers" && $azione == "insert" && !v("insert_account_cf_obbligatorio"))
					return false;
			}
			else
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
	
	public function getWrapCategorieGoogle()
	{
		$stringaImporta = v("usa_transactions") ? " <a target='_blank' href='".Url::getRoot()."opzioni/importacategoriegoogle'>".gtext("Importa codici")."</a>" : "";
		
		if (v("categorie_google_tendina"))
			return array(
				null,
				null,
				"<div class='form_notice'>".gtext("Inserisci il codice tassonomico di Google.")." $stringaImporta</div>"
			);
		else
			return array(
				null,
				null,
				"<div class='form_notice'>".gtext("Inserisci il codice tassonomico di Google.")." <a target='_blank' href='".v("url_codici_categorie_google")."'>".gtext("Vedi i codici")."</a></div>"
			);
	}
	
	public function getOpzioniCategoriaGoogle($id)
	{
		if ($id)
		{
			$record = $this->selectId((int)$id);
			
			if (!empty($record))
			{
				$o = new OpzioniModel();
				
				return $o->clear()->where(array(
					"valore"	=>	$record["codice_categoria_prodotto_google"],
					"codice"	=>	OpzioniModel::CATEGORIE_GOOGLE,
				))->toList("valore", "titolo")->findAll();
			}
		}
		
		return array();
	}
	
	public static function ne($field)
	{
		return trim(strip_tags(htmlentitydecode($field)));
	}
	
	//restituisci il nome del cliente dell'ordine (nome cognome o ragione sociale)
	public function nome($row)
	{
		if (strcmp($row["regusers"]["tipo_cliente"],"azienda") !== 0)
			return $row["regusers"]["nome"]." ".$row["regusers"]["cognome"];
		else
			return $row["regusers"]["ragione_sociale"];
	}
	
	// Se è un utente da confermare
	public static function utenteDaConfermare($email, $trueFalse = true)
	{
		$r = new RegusersModel();
		
		$r->clear()->where(array(
			"username"	=>	sanitizeAll($email),
			"has_confirmed"			=>	1,
			"ha_confermato"			=>	0,
			"bloccato"				=>	0,
		));
		
		if ($trueFalse)
			return $r->rowNumber();
		else
			return $r->send();
	}
	
	public static function getNominativo($ordine)
	{
		if ($ordine["tipo_cliente"] == "azienda")
			return $ordine["ragione_sociale"];
		else
			return $ordine["nome"]." ".$ordine["cognome"];
	}
	
	public function deleteAccount($idUser, $codiceApp = "")
	{
		$user = $this->selectId($idUser);
		
		if (!empty($user))
		{
			if (!v("elimina_record_utente_ad_autoeliminazione"))
			{
				if ($user["deleted"] == "no")
				{
					$tokeEliminazione = randomToken();
					
					$this->sValues(array(
						"username"	=>	randomToken()."@---.--",
						Users_CheckAdmin::$statusFieldName	=>	1,
						"bloccato"	=>	1,
						"deleted"	=>	"yes",
						"nome"		=>	"--",
						"cognome"	=>	"--",
						"ragione_sociale"	=>	"--",
						"p_iva"		=>	"--",
						"codice_fiscale"	=>	"--",
						"indirizzo"	=>	"--",
						"cap"		=>	"--",
						"provincia"	=>	"--",
						"citta"		=>	"--",
						"telefono"	=>	"--",
						"email"		=>	"",
						"indirizzo_spedizione"	=>	"--",
						"codice_destinatario"	=>	"--",
						"dprovincia"=>	"--",
						"pec"		=>	"--",
						"telefono_2"=>	"--",
						"codice_app_eliminazione"	=>	$codiceApp,
						"time_eliminazione"	=>	time(),
						"token_eliminazione"=>	$tokeEliminazione,
					));
					
					$this->setValue("password", randomToken(), PASSWORD_HASH);
					
					if ($this->pUpdate((int)$idUser))
					{
						$sp = new SpedizioniModel();
						
						$campiSpedizione = OpzioniModel::arrayValori("CAMPI_SALVATAGGIO_SPEDIZIONE");
						
						foreach ($campiSpedizione as $cs)
						{
							if ($cs != "nazione_spedizione")
								$sp->setValue($cs, "");
						}
						
// 						$sp->sValues(array(
// 							"indirizzo_spedizione"	=>	"--",
// 							"cap_spedizione"		=>	"--",
// 							"provincia_spedizione"	=>	"--",
// 	// 						"nazione_spedizione"	=>	"--",
// 							"citta_spedizione"		=>	"--",
// 							"telefono_spedizione"	=>	"--",
// 							"dprovincia_spedizione"	=>	"--",
// 						));
						
						$sp->pUpdate(null, array(
							"id_user"	=>	(int)$idUser
						));
						
						return $tokeEliminazione;
					}
				}
			}
			else
			{
				$this->query(array("delete from spedizioni where id_user = ?",array((int)$idUser)));
				$this->query(array("delete from regusers_groups_temp where id_user = ?",array((int)$idUser)));
				$this->query(array("delete from regusers_groups where id_user = ?",array((int)$idUser)));
				$this->query(array("update orders set id_user = 0 where id_user = ?",array((int)$idUser)));
				$this->query(array("update feedback set id_user = 0 where id_user = ?",array((int)$idUser)));
				$this->query(array("delete from regusers where id_user = ?",array((int)$idUser)));
				$this->query(array("delete from contatti where email = ?",array(sanitizeAll($user["username"]))));
				
				return 1;
			}
		}
		
		return 0;
	}
	
	public function getTendinaIndirizzi($id_user)
	{
		$sp = new SpedizioniModel();
		
		$indirizzi = $sp->where(array(
			"id_user"	=>	(int)$id_user,
		))->orderBy("indirizzo_spedizione")->send(false);
		
		$arraySelect = array();
		
		foreach ($indirizzi as $i)
		{
			$arraySelect[$i["id_spedizione"]] = $i["indirizzo_spedizione"]." ".$i["cap_spedizione"]." ".$i["citta_spedizione"];
		}
		
		return $arraySelect;
	}
	
	public function getLingua($id)
	{
		$lingua = $this->clear()->whereId($id)->field("lingua");
		return $lingua ? $lingua : LingueModel::getPrincipaleFrontend();
	}
	
	public function setProvinciaFatturazione()
	{
		if (isset($this->values["nazione"]) && isset($this->values["provincia"]) && isset($this->values["dprovincia"]) && $this->values["nazione"] != "IT")
			$this->values["provincia"] = $this->values["dprovincia"];
	}
	
	public function setProvinciaSpedizione()
	{
		if (isset($this->values["nazione_spedizione"]) && isset($this->values["provincia_spedizione"]) && isset($this->values["dprovincia_spedizione"]) && $this->values["nazione_spedizione"] != "IT")
			$this->values["provincia_spedizione"] = $this->values["dprovincia_spedizione"];
	}
	
	public function setProvince()
	{
		if (App::$isFrontend)
			return;
		
		$this->setProvinciaFatturazione();
		
		$this->setProvinciaSpedizione();
	}
	
	public function sistemaMaiuscole()
	{
		if (!v("sistema_maiuscole_clienti"))
			return;
		
		if (isset($this->values["nome"]))
			$this->values["nome"] = ucfirst($this->values["nome"]);
		
		if (isset($this->values["cognome"]))
			$this->values["cognome"] = ucfirst($this->values["cognome"]);
		
		if (isset($this->values["codice_fiscale"]))
			$this->values["codice_fiscale"] = strtoupper($this->values["codice_fiscale"]);
	}
	
	public function addWhereUtenteAttivo()
	{
		$this->aWhere(array(
			"regusers.".Users_CheckAdmin::$statusFieldName	=>	Users_CheckAdmin::$statusFieldActiveValue,
		));
		
		return $this;
	}
	
	public function upload($type = "update")
	{
		if (self::$uploadFileGeneric)
		{
			foreach ($this->uploadFields as $field => $params)
			{
				if (isset($this->values[$field]))
				{
					$this->delFields($field);
					
					if (isset($_FILES[$field]["name"]) and strcmp($_FILES[$field]["name"],'') !== 0)
					{
						$path = $params["path"];
						
						if (isset($params["allowedExtensions"]))
						{
							$this->files->setParam('allowedExtensions',$params["allowedExtensions"]);
						}
						
						if (isset($params["allowedMimeTypes"]))
						{
							$this->files->setParam('allowedMimeTypes',$params["allowedMimeTypes"]);
						}
						
						if (isset($params["maxFileSize"]))
						{
							$this->files->setParam('maxFileSize',$params["maxFileSize"]);
						}
						
						if (isset($params["createImage"]) and $params["createImage"])
						{
							$this->files->setParam('createImage',true);
						}
						
						if (strcmp($params["type"],"file") === 0)
						{
							$this->files->setParam('createImage',false);
						}
						
						$this->files->setParam('fileUploadKey',$field);
						$this->files->setBase(Domain::$parentRoot."/".$path);
						
						if (isset($params["clean_field"]) || isset($params["forza_randon_field_name"]))
						{
							$fileName = md5(randString(22).microtime().uniqid(mt_rand(),true));
						}
						else
						{
							$fileName = $this->files->getNameWithoutFileExtension($_FILES[$field]["name"]);
							$fileName = encodeUrl($fileName);
							$fileName = $this->files->getUniqueName($fileName);
						}
						
						$htaccess = (isset($params["disallow"]) && $params["disallow"]) ? true : false;
						
						self::creaCartellaImages($path, $htaccess);
						
						if ($this->files->uploadFile($fileName))
						{
							$cleanFileName = $this->files->getNameWithoutFileExtension($_FILES[$field]["name"]);
							$ext = $this->files->getFileExtension($_FILES[$field]["name"]);
							
							if (strcmp($params["type"],"image") === 0 && !Files_Upload::isJpeg($ext) && v("converti_immagini_in_jpeg") && !isset($params["keepFormat"]))
							{
								$params = array(
									'forceToFormat'	=>	'jpeg',
								);
								
								$originalPath = rtrim($this->files->getBase());
								
								$newFileName = str_replace(".$ext", '.jpeg', $this->files->fileName);
								$newFileName = $this->files->getUniqueName($newFileName);
								$targetFile = $originalPath."/".$newFileName;
								
								$thumb = new Image_Gd_Thumbnail($originalPath, $params);
								$thumb->render($this->files->fileName,$targetFile);
								
								$this->files->fileName = $newFileName;
								$ext = 'jpeg';
							}
							
							$this->values[$field] = sanitizeAll($this->files->fileName);
							
							if (isset($params["clean_field"]))
							{
// 								$cleanFileName = $this->files->getNameWithoutFileExtension($_FILES[$field]["name"]);
// 								$ext = $this->files->getFileExtension($_FILES[$field]["name"]);
								
								$cleanFileName = (!isValidImgName($cleanFileName)) ? encodeUrl($cleanFileName) : $cleanFileName;
								
								$this->values[$params["clean_field"]] = sanitizeAll($cleanFileName.".".$ext);
							}
						}
						else
						{
							$this->notice = $this->files->notice;
							$this->result = false;
							
							return false;
						}
					}
					else
					{
						if (isset($params["mandatory"]))
						{
							if (strcmp($type,"insert") === 0)
							{
								$vcs = new Lang_En_ValCondStrings();
								
								$this->notice = "<div class='alert'>Si prega di selezionare un file per il campo ".getFieldLabel($field)."</div>\n".$vcs->getHiddenAlertElement($field);
								
								$this->result = false;
								
								return false;
							}
						}
						else if (isset($_POST[$field."--del--"]))
						{
							$this->values[$field] = "";
							
							if (isset($params["clean_field"]))
							{
								$this->values[$params["clean_field"]] = "";
							}
						}
					}
				}
			}
		}
		
		return true;
	}
}
