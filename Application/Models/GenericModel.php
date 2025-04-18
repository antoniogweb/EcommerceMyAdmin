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

class GenericModel extends Model_Tree
{
	use CommonModel;
	
	public static $elementoImportato = false;
	
	public static $generaMetaQuandoSalvi = true;
	public static $traduzioniNonPrincipali = null;
	public static $nomiDaAlias = array();
	
	public static $recordTabella = null;
	public static $recordTabellaG = [];
	public static $apiMethod = "POST";
	public static $uploadFileGeneric = true;
	public static $onChanggeCheckVisibilityAttributes = array(
		"on-c"	=>	"check-v"
	);
	
	protected $nomeCampoIdUser = "id_user";
	
	public static $entryAttivo = array(
		"type"	=>	"Select",
		"labelString"	=>	"Attivo",
		"options"	=>	array(
			"1"	=>	"Sì",
			"0"	=>	"No",
		),
		"reverse"	=>	"yes",
		"className"	=>	"form-control",
	);
	
	public static $attivoSiNo = array(
		"1"	=>	"Sì",
		"0"	=>	"No",
	);
	
	public static $attivoNoSi = array(
		"0"	=>	"No",
		"1"	=>	"Sì",
	);
	
	public static $yesNo = array(
		"yes"	=>	"Sì",
		"no"	=>	"No",
	);
	
	public static $YN = array(
		"tutti"	=>	"Tutti",
		"Y"	=>	"Sì",
		"N"	=>	"No",
	);
	
	public $usingApi = false;
	public $campoTitolo = "titolo";
	public $campoValore = "valore";
	public $metodoPerTitolo = null;
	
	public $uploadFields = array();
	public $lId = null;
	public $traduzione = false;
	public $formStructAggiuntivoEntries = array();
	public $salvaDataModifica = false;
	
	public $cViewStatus = "";
	
	public $campoTimeEventoRemarketing = "creation_time";
	
	public static $tabelleConAliasMap = array(
		"pages"			=>	array(
			"chiave"		=>	"id_page",
			"campoTitolo"	=>	"title",
			"tradotta"		=>	true,
		),
		"categories"	=>	array(
			"chiave"		=>	"id_c",
			"campoTitolo"	=>	"title",
			"tradotta"		=>	true,
		),
		"marchi"		=>	array(
			"chiave"		=>	"id_marchio",
			"campoTitolo"	=>	"titolo",
			"tradotta"		=>	true,
		),
		"tag"			=>	array(
			"chiave"		=>	"id_tag",
			"campoTitolo"	=>	"titolo",
			"tradotta"		=>	true,
		),
		"caratteristiche"	=>	array(
			"chiave"		=>	"id_car",
			"campoTitolo"	=>	"titolo",
			"tradotta"		=>	true,
		),
		"caratteristiche_valori" 	=>	array(
			"chiave"		=>	"id_cv",
			"campoTitolo"	=>	"titolo",
			"tradotta"		=>	true,
		),
		"regioni"		=>	array(
			"chiave"		=>	"id_regione",
			"campoTitolo"	=>	"titolo",
			"tradotta"		=>	false,
		),
		"fasce_prezzo"	=>	array(
			"chiave"		=>	"id_fascia_prezzo",
			"campoTitolo"	=>	"titolo",
			"tradotta"		=>	true,
		),
		"attributi_valori" 	=>	array(
			"chiave"		=>	"id_av",
			"campoTitolo"	=>	"titolo",
			"tradotta"		=>	true,
		),
	);
	
	public static $tabelleConAlias = array();
	
	// public $campiEditabiliDaMain = array();
	
	public function __construct() {
		
		self::$tabelleConAlias = array_keys(self::$tabelleConAliasMap);
		
		parent::__construct();

// 		$this->uploadFields = array(
// 			"immagine"	=>	array(
// 				"type"	=>	"image",
// 				"path"	=>	"images/news",
// 				"mandatory"	=>	true,
// 				"ext"	=>	'png,jpg,jpeg,gif',
// 				"createImage"	=>	true,
// 				"maxFileSize"	=>	3000000,
// 			),
// 		);
		
		if (!empty($this->uploadFields))
		{
			$this->files->setParam('allowedExtensions','png,jpg,jpeg,gif');
			$this->files->setParam('maxFileSize',4000000);
			$this->files->setParam('functionUponFileNane','sanitizeFileName');
			$this->files->setParam('fileUploadBehaviour','add_token');
		}
	}
	
	public function __call($method, $args)
	{
		return $this->callLingue($method, $args);
	}
	
	protected function checkCallLingue($metodo, $argomenti)
	{
		$codici = LingueModel::getCodici();
		$lingue = array_keys($codici);
		$lingueString = implode("|", $lingue);
		
		if (preg_match('/^link('.$lingueString.')$/',$metodo, $matches) || preg_match('/^edit('.$lingueString.')$/',$metodo, $matches))
			return true;
		
		return false;
	}
	
	protected function callLingue($method, $args)
	{
		$codici = LingueModel::getCodici();
		
		$lingue = array_keys($codici);
		$lingueString = implode("|", $lingue);
		
		if (preg_match('/^link('.$lingueString.')$/',$method, $matches))
		{
			$nArgs = $args;
			$nArgs[] = $codici[$matches[1]];
			return call_user_func_array(array($this, "linklingua"), $nArgs);
		}
		
		if (preg_match('/^edit('.$lingueString.')$/',$method, $matches))
		{
			$nArgs = $args;
			array_unshift($nArgs, $codici[$matches[1]]);
			return call_user_func_array(array($this, "editLingua"), $nArgs);
		}
		
		return null;
	}
	
	public static function getNomeDaAlias($alias)
	{
		if (isset(self::$nomiDaAlias[$alias]))
			return self::$nomiDaAlias[$alias];
		
		$arrayUnion = array();
		
		$arrayValori = array();
		
		$linguaPrincipale = LingueModel::getPrincipaleFrontend();
		
		// Versione estera
		if (v("considera_traduzione_sempre_esistente") && Params::$lang != $linguaPrincipale)
		{
			$sql = "select if(title != '',title,titolo) as titolo_filtro from contenuti_tradotti where contenuti_tradotti.lingua = ? and contenuti_tradotti.alias = ?";

			$arrayValori[] = sanitizeDb(Params::$lang);
			$arrayValori[] = sanitizeAll($alias);

			$arrayUnion[] = $sql;

			foreach (self::$tabelleConAliasMap as $table => $params)
			{
				if (!$params["tradotta"])
				{
					$campoTitolo = $params["campoTitolo"];

					$sql = "select $table.$campoTitolo as titolo_filtro from $table where $table.alias = ?";

					$arrayValori[] = sanitizeAll($alias);
				}
			}
		}
		else
		{
			foreach (self::$tabelleConAliasMap as $table => $params)
			{
				$campoChiave = $params["chiave"];
				$campoTitolo = $params["campoTitolo"];

				if ($params["tradotta"] && Params::$lang != $linguaPrincipale)
				{
					$sql = "select coalesce(contenuti_tradotti.$campoTitolo, $table.$campoTitolo) COLLATE utf8mb4_general_ci as titolo_filtro from $table left join contenuti_tradotti on contenuti_tradotti.$campoChiave =  $table.$campoChiave and contenuti_tradotti.lingua = ? where coalesce(contenuti_tradotti.alias,$table.alias) = ?";

					$arrayValori[] = sanitizeDb(Params::$lang);
					$arrayValori[] = sanitizeAll($alias);
				}
				else
				{
					$sql = "select $table.$campoTitolo as titolo_filtro from $table where $table.alias = ?";

					$arrayValori[] = sanitizeAll($alias);
				}

				$arrayUnion[] = $sql;
			}
		}

		$arrayUnion[] = "select titolo from nazioni where iso_country_code = ?";
		$arrayValori[] = sanitizeAll($alias);
		
		$sql = implode(" UNION ", $arrayUnion);

		$queryArray = array($sql, $arrayValori);
		$sql = self::g()->arrayToWhereClause($queryArray);
		
// 		$mysqli = Db_Mysqli::getInstance();
		$mysqli = Factory_Db::getInstance(DATABASE_TYPE);
		$res = $mysqli->query($sql);
		
		$nomeDaAlias = null;

		if (count($res) > 0 && isset($res[0]["aggregate"]["titolo_filtro"]) && trim($res[0]["aggregate"]["titolo_filtro"]))
			$nomeDaAlias = $res[0]["aggregate"]["titolo_filtro"];
		
		if ($alias == AltriFiltri::$aliasValoreTipoInEvidenza[0])
			$nomeDaAlias = AltriFiltri::$aliasValoreTipoInEvidenza[1];
		
		if ($alias == AltriFiltri::$aliasValoreTipoNuovo[0])
			$nomeDaAlias = AltriFiltri::$aliasValoreTipoNuovo[1];
		
		if ($alias == AltriFiltri::$aliasValoreTipoPromo[0])
			$nomeDaAlias = AltriFiltri::$aliasValoreTipoPromo[1];
		
		self::$nomiDaAlias[$alias] = $nomeDaAlias;
		
		return $nomeDaAlias;
	}
	
	public function bulkAction($bulk_list)
	{
		$bulkArray = explode(",",$bulk_list);
		
		if (isset($_POST["bulkAction"]) and isset($_POST["bulkActionValues"]))
		{
			if (in_array($_POST["bulkAction"],$bulkArray))
			{
				$action = $_POST["bulkAction"];
				
				if (method_exists($this,$action))
				{
					if (preg_match('/^[0-9]{1,}(\|[0-9]{1,})*$/',$_POST["bulkActionValues"]))
					{
						$bulkActionValuesArray = explode("|",$_POST["bulkActionValues"]);
						
						foreach ($bulkActionValuesArray as $bitem)
						{
							$this->$action($bitem);
						}
					}
// 					else if (preg_match('/^[0-9]{1,}\:[0-9\,]{1,}(\|[0-9]{1,}\:[0-9\,]{1,})*$/',$_POST["bulkActionValues"]) and in_array($_POST["bulkAction"],array("setquantita","setprezzo")))
// 					{
// 						$bulkActionValuesArray = explode("|",$_POST["bulkActionValues"]);
// 						
// 						foreach ($bulkActionValuesArray as $bitem)
// 						{
// 							if (strcmp($bitem,"") !== 0 and strstr($bitem, ':'))
// 							{
// 								$temp = explode(":",$bitem);
// 								$this->$action($temp[0],$temp[1]);
// 							}
// 						}
// 					}
// 					else if (preg_match('/^[0-9]{1,}\:(.*){1,}(\|[0-9]{1,}\:(.*){1,})*$/',$_POST["bulkActionValues"]) and in_array($_POST["bulkAction"],array("setvalore")))
// 					{
// 						$bulkActionValuesArray = explode("|",$_POST["bulkActionValues"]);
// 						
// 						foreach ($bulkActionValuesArray as $bitem)
// 						{
// 							if (strcmp($bitem,"") !== 0 and strstr($bitem, ':'))
// 							{
// 								$temp = explode(":",$bitem);
// 								$this->$action($temp[0],$temp[1]);
// 							}
// 						}
// 					}
// 					else
// 					{
// 						$this->notice = "<div class='alert'>Si prega di inserire valori numerici</div>";
// 					}
				}
			}
		}
	}
	
	protected function addTokenAlias($res)
	{
		if (count($res) > 0)
			$this->values["alias"] = $this->values["alias"] . v("carattere_divisione_parole_permalink") . generateString(4,"123456789abcdefghilmnopqrstuvz");
	}
	
	public function checkAliasAll($id, $noTraduzione = false, $checkUnico = true)
	{
		if (isset($this->values["alias"]) && !trim($this->values["alias"]))
			$this->values["alias"] = sanitizeDb(encodeUrl($this->values["titolo"]));
		
		if (!$checkUnico)
			return;
		
		$clean["id"] = (int)$id;
		
		// Controllo che non sia una di quelle tabelle che può avere un duplicato. Ex: attributi_valori
		$tabelleConAliasDuplicato = explode(",", v("tabelle_con_possibile_alias_duplicato"));
		
		if (!in_array($this->_tables, $tabelleConAliasDuplicato))
		{
			if (!$clean["id"])
				$res = $this->query(array("select alias from ".$this->_tables." where alias = ?",array(sanitizeDb($this->values["alias"]))));
			else
				$res = $this->query(array("select alias from ".$this->_tables." where alias = ? and ".$this->_idFields."!=?",array(sanitizeDb($this->values["alias"]),$clean["id"])));
		}
		else
			$res = array();
		
		$this->addTokenAlias($res);
		
		$arrayUnion = $dataValues = array();
		
		foreach (GenericModel::$tabelleConAlias as $table)
		{
			if ($table == $this->_tables)
				continue;
			
			$arrayUnion[] = "select alias from $table where alias = ?";
			$dataValues[] = sanitizeDb($this->values["alias"]);
		}
		
		$sql = implode(" UNION ", $arrayUnion);
		
		$res = $this->query(array($sql, $dataValues));
		
		$this->addTokenAlias($res);
		
		if (!in_array($this->_tables, $tabelleConAliasDuplicato))
		{
			if (!isset($id) || $noTraduzione)
				$res = $this->query(array("select alias from contenuti_tradotti where alias = ?",array(sanitizeDb($this->values["alias"]))));
			else
				$res = $this->query(array("select alias from contenuti_tradotti where alias = ? and ".$this->_idFields."!=?",array(sanitizeDb($this->values["alias"]),$clean["id"])));
		}
		else
			$res = $this->query(array("select alias from contenuti_tradotti where alias = ? and sezione != ?",array(sanitizeDb($this->values["alias"]), sanitizeDb($this->_tables))));
		
		$this->addTokenAlias($res);
	}
	
// 	public function upload($type = "update")
// 	{
// 		if (self::$uploadFileGeneric)
// 		{
// 			foreach ($this->uploadFields as $field => $params)
// 			{
// 				if (isset($this->values[$field]))
// 				{
// 					$this->delFields($field);
// 					
// 					if (isset($_FILES[$field]["name"]) and strcmp($_FILES[$field]["name"],'') !== 0)
// 					{
// 						$path = $params["path"];
// 						
// 						if (isset($params["allowedExtensions"]))
// 						{
// 							$this->files->setParam('allowedExtensions',$params["allowedExtensions"]);
// 						}
// 						
// 						if (isset($params["allowedMimeTypes"]))
// 						{
// 							$this->files->setParam('allowedMimeTypes',$params["allowedMimeTypes"]);
// 						}
// 						
// 						if (isset($params["maxFileSize"]))
// 						{
// 							$this->files->setParam('maxFileSize',$params["maxFileSize"]);
// 						}
// 						
// 						if (isset($params["createImage"]) and $params["createImage"])
// 						{
// 							$this->files->setParam('createImage',true);
// 						}
// 						
// 						if (strcmp($params["type"],"file") === 0)
// 						{
// 							$this->files->setParam('createImage',false);
// 						}
// 						
// 						$this->files->setParam('fileUploadKey',$field);
// 						$this->files->setBase(Domain::$parentRoot."/".$path);
// 						
// 						if (isset($params["clean_field"]))
// 						{
// 							$fileName = md5(randString(22).microtime().uniqid(mt_rand(),true));
// 						}
// 						else
// 						{
// 							$fileName = $this->files->getNameWithoutFileExtension($_FILES[$field]["name"]);
// 							$fileName = encodeUrl($fileName);
// 							$fileName = $this->files->getUniqueName($fileName);
// 						}
// 						
// 						$htaccess = (isset($params["disallow"]) && $params["disallow"]) ? true : false;
// 						
// 						self::creaCartellaImages($path, $htaccess);
// 						
// 						if ($this->files->uploadFile($fileName))
// 						{
// 							$cleanFileName = $this->files->getNameWithoutFileExtension($_FILES[$field]["name"]);
// 							$ext = $this->files->getFileExtension($_FILES[$field]["name"]);
// 							
// 							if (strcmp($params["type"],"image") === 0 && !Files_Upload::isJpeg($ext) && v("converti_immagini_in_jpeg") && !isset($params["keepFormat"]))
// 							{
// 								$params = array(
// 									'forceToFormat'	=>	'jpeg',
// 								);
// 								
// 								$originalPath = rtrim($this->files->getBase());
// 								
// 								$newFileName = str_replace(".$ext", '.jpeg', $this->files->fileName);
// 								$newFileName = $this->files->getUniqueName($newFileName);
// 								$targetFile = $originalPath."/".$newFileName;
// 								
// 								$thumb = new Image_Gd_Thumbnail($originalPath, $params);
// 								$thumb->render($this->files->fileName,$targetFile);
// 								
// 								$this->files->fileName = $newFileName;
// 								$ext = 'jpeg';
// 							}
// 							
// 							$this->values[$field] = sanitizeAll($this->files->fileName);
// 							
// 							if (isset($params["clean_field"]))
// 							{
// // 								$cleanFileName = $this->files->getNameWithoutFileExtension($_FILES[$field]["name"]);
// // 								$ext = $this->files->getFileExtension($_FILES[$field]["name"]);
// 								
// 								$cleanFileName = (!isValidImgName($cleanFileName)) ? encodeUrl($cleanFileName) : $cleanFileName;
// 								
// 								$this->values[$params["clean_field"]] = sanitizeAll($cleanFileName.".".$ext);
// 							}
// 						}
// 						else
// 						{
// 							$this->notice = $this->files->notice;
// 							$this->result = false;
// 							
// 							return false;
// 						}
// 					}
// 					else
// 					{
// 						if (isset($params["mandatory"]))
// 						{
// 							if (strcmp($type,"insert") === 0)
// 							{
// 								$vcs = new Lang_En_ValCondStrings();
// 								
// 								$this->notice = "<div class='alert'>Si prega di selezionare un file per il campo ".getFieldLabel($field)."</div>\n".$vcs->getHiddenAlertElement($field);
// 								
// 								$this->result = false;
// 								
// 								return false;
// 							}
// 						}
// 						else if (isset($_POST[$field."--del--"]))
// 						{
// 							$this->values[$field] = "";
// 							
// 							if (isset($params["clean_field"]))
// 							{
// 								$this->values[$params["clean_field"]] = "";
// 							}
// 						}
// 					}
// 				}
// 			}
// 		}
// 		
// 		return true;
// 	}
	
	public function setUploadForms($id = 0)
	{
		$clean["id"] = (int)$id;
		
		foreach ($this->uploadFields as $field => $params)
		{
			$values = $this->selectId($clean["id"]);
			
// 			if (isset($this->values[$field]))
// 			{
				$class = (!isset($params["type"]) or strcmp(strtolower($params["type"]),"image") === 0) ? "thumb" : "file";
				
				$wrapHtml = "<div class='$class' data-field='$field' data-field-path='".$params["path"]."'>;;value;;</div>";
				
				if (isset($values[$field]) and strcmp($values[$field],"") !== 0)
				{
					$value = isset($params["clean_field"]) ? $values[$params["clean_field"]] : ";;value;;";
					
					$src = $href = Domain::$name. "/" . $params["path"] . "/;;value;;";
					
					if (isset($params["clean_field"]))
					{
						$src = Url::getRoot().$this->applicationUrl.$this->controller."/thumb/".$field."/".$clean["id"];
						$href = Url::getRoot().$this->applicationUrl.$this->controller."/documento/".$field."/".$clean["id"];
					}
					
					if (!isset($params["type"]) or strcmp(strtolower($params["type"]),"image") === 0)
					{
						$wrapHtml = "<div class='thumb box_immagine_upload'><a target='_blank' href='".$href."'><img src='".$src."'></a><a data-field='$field' class='elimina_allegato' title='cancella documento' href=''><img src='".Url::getFileRoot()."Public/Img/Icons/elementary_2_5/delete.png' /></a></div>";
					}
					else
					{
						$wrapHtml = "<div class='file box_immagine_upload'><span class='file_container'><a target='_blank' href='".$href."'>$value</a></span><a data-field='$field' class='elimina_allegato' title='cancella immagine' href=''><img src='".Url::getFileRoot()."Public/Img/Icons/elementary_2_5/delete.png' /></a></div>";
					}
				}
				
				$temp = array(
					'type'		=>	'File',
					'className'	=>	'form_file',
					'wrap'		=>	array(
						null,
						null,
						$wrapHtml,
// 						"<div class='$class' data-field='$field' data-field-path='".$params["path"]."'>;;value;;</div>",
					),
				);
				
				if (isset($params["labelString"]))
					$temp["labelString"] = $params["labelString"];
				
				if (!isset($this->formStruct["entries"][$field]))
				{
					$this->formStruct["entries"][$field] = $temp;
				}
// 			}
		}
	}
	
	public function setFilters()
	{
		
	}
	
	public function editData() {}
	
	public function update($id = null, $where = null)
	{
		$this->salvaDataUltimaModifica();
		
		$this->editData();
		
		$res = parent::update($id, $where);
		
		if ($res)
		{
			if ($this->traduzione)
			{
				if ($res)
					$this->controllalingua($id, $this->_idFields);
			}
			
			foreach ($this->uploadFields as $field => $params)
			{
				if(@is_dir(Domain::$parentRoot."/".trim($params["path"],"/")))
				{
				
				}
			}
		}
		
		return $res;
	}
	
	public function pInsert()
	{
		$res = parent::insert();
		
		$this->lId = $this->lastId(true);
		
		return $res;
	}
	
	// 	Retrieves the ID generated for an AUTO_INCREMENT column by the previous query (usually INSERT). 
	public function lastId($forzaDb = false)
	{
		return !$forzaDb ? $this->lId : $this->db->lastId();
	}
	
	public function pUpdate($id = null, $where = null)
	{
		return parent::update($id, $where);
	}
	
	public function pDel($id = null, $where = null)
	{
		return parent::del($id, $where);
	}
	
	public function setFormStruct($id = 0)
	{
		
	}
	
	public function disabilita($entries)
	{
		$entriesArray = explode(",",$entries);
		
		foreach ($entriesArray as $e)
		{
			if (isset($this->form->entry[$e]))
			{
				$this->form->entry[$e]->attributes = "disabled";
			}
		}
	}
	
	public function salvaDataUltimaModifica()
	{
		if ($this->salvaDataModifica)
			$this->values["data_ultima_modifica"] = date("Y-m-d H:i:s");
	}
	
	public function insert()
	{
		$this->salvaDataUltimaModifica();
		
		$this->editData();
		
		parent::insert();
		$res = $this->queryResult;
		
		$this->lId = $this->lastId(true);
		
		if ($this->traduzione)
		{
			if ($res)
				$this->controllaLingua($this->lId, $this->_idFields);
		}
		
		return $res;
	}
	
	public function titolo($id)
	{
		$clean["id"] = (int)$id;
		
		$record = $this->selectId($clean["id"]);
		
		if (isset($record[$this->campoTitolo]))
		{
			return $record[$this->campoTitolo];
		}
		
		return "";
	}
	
	public static function sTitolo($id)
	{
		return self::g(false)->titolo($id);
	}
	
	public function selectRegione()
	{
		$r = new RegioniModel();
		
		return array("0" => "Seleziona") + $r->clear()->orderBy("titolo")->toList("id_regione", "titolo")->send();
	}
	
	public function selectNazioneNoDefault()
	{
		$n = new NazioniModel();
		
		return $n->select("iso_country_code,titolo")->orderBy("titolo")->toList("iso_country_code","titolo")->send();
	}
	
	public function selectNazione($empty = false)
	{
// 		$n = new NazioniModel();
		
		if (!isset(NazioniModel::$elenco))
		{
			$default = $empty ? array("W"	=>	"Tutte le nazioni") : array("0"	=>	"Seleziona");
			NazioniModel::$elenco = $default + $this->selectNazioneNoDefault();
		}
		
		return NazioniModel::$elenco;
	}
	
	public function selectNazioneId()
	{
		$n = new NazioniModel();
		
		return $n->select("id_nazione,titolo")->orderBy("titolo")->toList("id_nazione","titolo")->send();
	}
	
	public function selectRuoli($frontend = false)
	{
		$r = new RuoliModel();
		
		if ($frontend)
		{
			$ruoli = $r->clear()->select("*")
				->addJoinTraduzione(null, "contenuti_tradotti", false)
				->orderBy("ruoli.titolo")
				->send();
			
			$arrayRuoli = array(0	=>	"--");
			
			foreach ($ruoli as $ruolo)
			{
				$arrayRuoli[$ruolo["ruoli"]["id_ruolo"]] = rfield($ruolo, "titolo");
			}
			
			return $arrayRuoli;
		}
		else
			return array(0	=>	"--") + $r->clear()->orderBy("titolo")->toList("id_ruolo", "titolo")->send();
	}
	
	public function selectTipi($frontend = false, $valoreDefault = 0, $testoDefault = "--")
	{
		if ($frontend)
		{
			$ruoli = $this->clear()->addJoinTraduzione()->orderBy($this->table().".".$this->campoTitolo)->send();
			
			$arrayRuoli = array(0	=>	"--");
			
			foreach ($ruoli as $ruolo)
			{
				$arrayRuoli[$ruolo[$this->table()][$this->getPrimaryKey()]] = genericField($ruolo, $this->campoTitolo, $this->table());
			}
			
			return $arrayRuoli;
		}
		else
		{
			return array($valoreDefault	=>	$testoDefault) + $this->clear()->orderBy($this->table().".".$this->campoTitolo)->toList($this->getPrimaryKey(), $this->campoTitolo)->send();
		}
	}
	
	public function selectProvince()
	{
		$n = new ProvinceModel();

		return array(""=>"Seleziona") + $n->orderBy("provincia")->toList("codice_provincia","provincia")->send();
	}
	
// 	public function controllaCF()
// 	{
// 		if (isset($this->values["codice_fiscale"]) && isset($this->values["tipo_cliente"]) && isset($_POST["nazione"]) && $_POST["nazione"] == "IT")
// 		{
// 			if ($this->values["tipo_cliente"] == "privato" || $this->values["tipo_cliente"] == "libero_professionista")
// 			{
// 				if (!codiceFiscale($this->values["codice_fiscale"]))
// 				{
// 					$this->notice = "<div class='alert'>Si prega di controllare il Codice Fiscale</div><span class='evidenzia'>class_codice_fiscale</span>".$this->notice;
// 					$this->result = false;
// 					return false;
// 				}
// 			}
// 		}
// 		
// 		return true;
// 	}
	
	public function selectLinkContenuto()
	{
		$p = new PagesModel();
		
		return $p->buildAllPagesSelect();
	}
	
	public function selectLingua($prefisso = "")
	{
		LingueModel::getValoriAttivi();
		
		$temp = array();
		
		foreach (LingueModel::$valoriAttivi as $codice => $descrizione)
		{
			$temp[$codice] = $prefisso.strtoupper(gtext($descrizione));
		}
		
		return $temp;
	}
	
	public function lingua($record)
	{
		LingueModel::getValori();
		
		if (isset(LingueModel::$valori[$record[$this->_tables]["lingua"]]))
			return strtoupper(LingueModel::$valori[$record[$this->_tables]["lingua"]]);
		else
			return strtoupper($record[$this->_tables]["lingua"]);
	}
	
	// Controllo che la lingua esista
	public function controllaLingua($id)
	{
		$this->controllaLinguaGeneric($id, $this->_idFields, $this->_tables);
	}
	
	// Controllo che la lingua esista per tutti i record
	public function controllaLinguaAll()
	{
		$ids = $this->clear()->select($this->_idFields)->toList($this->_idFields)->send();

		foreach ($ids as $id)
		{
			$this->controllaLingua($id);
		}
	}

	public static function getTraduzioniNonPrincipali()
	{
		if (isset(self::$traduzioniNonPrincipali))
			return self::$traduzioniNonPrincipali;
		
		self::$traduzioniNonPrincipali = LingueModel::getLingueNonPrincipali();
		
		return self::$traduzioniNonPrincipali;
	}
	
	// Controllo che la lingua esista
	public function controllaLinguaGeneric($id, $keyField = "id_page", $sezione = "")
	{
		// Non generare la traduzione se è un elemento importato
		if (self::$elementoImportato)
			return;
		
		$record = $this->selectId((int)$id);
		
		if (!empty($record))
		{
			$ct = new ContenutitradottiModel();
			
// 			$traduzioni = LingueModel::getLingueNonPrincipali();
			$traduzioni = self::getTraduzioniNonPrincipali();
			
			foreach ($traduzioni as $lingua)
			{
				$traduzione = $ct->clear()->where(array(
					"$keyField"	=>	(int)$id,
					"lingua"	=>	sanitizeDb($lingua),
				))->send(false);
				
				$id_page = ($keyField == "id_page") ? (int)$id : 0;
				$id_c = ($keyField == "id_c") ? (int)$id : 0;
				$id_car = ($keyField == "id_car") ? (int)$id : 0;
				$id_cv = ($keyField == "id_cv") ? (int)$id : 0;
				$id_marchio = ($keyField == "id_marchio") ? (int)$id : 0;
				$id_ruolo = ($keyField == "id_ruolo") ? (int)$id : 0;
				$id_tipo_azienda = ($keyField == "id_tipo_azienda") ? (int)$id : 0;
				$id_a = ($keyField == "id_a") ? (int)$id : 0;
				$id_av = ($keyField == "id_av") ? (int)$id : 0;
				$id_pers = ($keyField == "id_pers") ? (int)$id : 0;
				$id_tag = ($keyField == "id_tag") ? (int)$id : 0;
				$id_fascia_prezzo = ($keyField == "id_fascia_prezzo") ? (int)$id : 0;
				$id_doc = ($keyField == "id_doc") ? (int)$id : 0;
				$id_cont = ($keyField == "id_cont") ? (int)$id : 0;
				$id_tipologia_caratteristica = ($keyField == "id_tipologia_caratteristica") ? (int)$id : 0;
				$id_pagamento = ($keyField == "id_pagamento") ? (int)$id : 0;
				$id_stato_ordine = ($keyField == "id_stato_ordine") ? (int)$id : 0;
				
				$editorVisuale = 1;
				
				if (isset($record["use_editor"]))
					$editorVisuale = $record["use_editor"] == "Y" ? 1 : 0;
				
				$ct->setValues(array(
					"lingua"		=>	sanitizeDb($lingua),
					"title"			=>	isset($record["title"]) ? $record["title"] : "",
					"description"	=>	isset($record["description"]) ? $record["description"] : "",
					"alias"			=>	isset($record["alias"]) ? $record["alias"] : "",
					"meta_title"	=>	isset($record["meta_title"]) ? $record["meta_title"] : "",
					"keywords"		=>	isset($record["keywords"]) ? $record["keywords"] : "",
					"meta_description"	=>	isset($record["meta_description"]) ? $record["meta_description"] : "",
					"url"			=>	isset($record["url"]) ? $record["url"] : "",
					"sottotitolo"	=>	isset($record["sottotitolo"]) ? $record["sottotitolo"] : "",
					"titolo"		=>	isset($record["titolo"]) ? $record["titolo"] : "",
					"descrizione"	=>	isset($record["descrizione"]) ? $record["descrizione"] : "",
					"descrizione_2"	=>	isset($record["descrizione_2"]) ? $record["descrizione_2"] : "",
					"descrizione_3"	=>	isset($record["descrizione_3"]) ? $record["descrizione_3"] : "",
					"descrizione_4"	=>	isset($record["descrizione_4"]) ? $record["descrizione_4"] : "",
					"istruzioni_pagamento"	=>	isset($record["istruzioni_pagamento"]) ? $record["istruzioni_pagamento"] : "",
					"testo_link"	=>	isset($record["testo_link"]) ? $record["testo_link"] : "",
					"editor_visuale"	=>	$editorVisuale,
					"id_page"		=>	$id_page,
					"id_c"			=>	$id_c,
					"id_car"		=>	$id_car,
					"id_cv"			=>	$id_cv,
					"id_marchio"	=>	$id_marchio,
					"id_ruolo"		=>	$id_ruolo,
					"id_tipo_azienda"	=>	$id_tipo_azienda,
					"id_a"			=>	$id_a,
					"id_av"			=>	$id_av,
					"id_pers"		=>	$id_pers,
					"id_tag"		=>	$id_tag,
					"id_fascia_prezzo"	=>	$id_fascia_prezzo,
					"id_doc"		=>	$id_doc,
					"id_cont"		=>	$id_cont,
					"id_tipologia_caratteristica"	=>	$id_tipologia_caratteristica,
					"id_pagamento"	=>	$id_pagamento,
					"id_stato_ordine"	=>	$id_stato_ordine,
					"sezione"		=>	$sezione,
				),"sanitizeDb");
				
				// Prendo i campi aggiuntivi
				if (defined("CAMPI_AGGIUNTIVI_PAGINE") && is_array(CAMPI_AGGIUNTIVI_PAGINE))
				{
					foreach (CAMPI_AGGIUNTIVI_PAGINE as $struct)
					{
						foreach ($struct as $campo => $frm)
						{
							$ct->setValue($campo, isset($record[$campo]) ? $record[$campo] : "", "sanitizeDb");
						}
					}
				}
				
				// Prendo i campi aggiuntivi dalle APP
				if (is_array(PagesModel::$campiAggiuntivi))
				{
					foreach (PagesModel::$campiAggiuntivi as $struct)
					{
						foreach ($struct as $campo => $frm)
						{
							if (in_array($campo, PagesModel::$campiAggiuntiviMeta["traduzione"]))
								$ct->setValue($campo, isset($record[$campo]) ? $record[$campo] : "", "sanitizeDb");
						}
					}
				}
				
// 				print_r($ct->values);die();
				
				if (count($traduzione) === 0)
					$ct->insert();
				else if (!$traduzione[0]["salvato"])
					$ct->update($traduzione[0]["id_ct"]);
			}
		}
	}
	
	public function linklingua($record, $lingua)
	{
		return $this->linklinguaGeneric($record[$this->_tables][$this->_idFields], $lingua, $this->_idFields);
	}
	
	public function linklinguaGeneric($id, $lingua, $field = "id_page")
	{
		$idPage = (int)$id;
		
		$ct = new ContenutitradottiModel();
		
		$contenuto = $ct->clear()->where(array(
			"$field"	=>	$idPage,
			"lingua"	=>	$lingua,
		))->record();
		
		if (empty($contenuto))
		{
			Cache_Db::$cachedTables = array();
			$this->controllaLingua($idPage);
		}
		
		$contenuto = $ct->clear()->where(array(
			"$field"	=>	$idPage,
			"lingua"	=>	$lingua,
		))->record();
		
		if(!empty($contenuto))
		{
			$href = Url::getRoot()."contenutitradotti/form/update/".$contenuto["id_ct"]."?partial=Y";
			
			if (!empty($contenuto) && $contenuto["salvato"])
				return "<a class='iframe' href='$href'><i class='fa fa-edit text_16'></i></a>";
			else
				return "<a class='iframe' href='$href'><i class='fa fa-plus text_16'></i></a>";
		}
		
		return "";
	}
	
	public function buildAllCatSelect()
	{
		$c = new CategoriesModel();
		
		return array("0"=>gtext("-- NON IMPOSTATO --")) + $c->buildSelect(null, false);
	}
	
	public function buildAllPagesSelect()
	{
		return array("0"=>gtext("-- NON IMPOSTATO --")) + $this->clear()->select("pages.id_page,pages.title")->addWhereAttivo()->orderBy("title")->toList("id_page","title")->send();
	}
	
	public function addJoinTraduzione($lingua = null, $alias = "contenuti_tradotti", $selectAll = true, $modelTabella = null)
	{
		if (!isset($lingua))
			$lingua = Params::$lang;
		
		$strAlias = " as $alias";
		
		if (!isset($modelTabella))
			$modelTabella = $this;
		
		if ($selectAll)
			$this->select("*");
		
		$this->left("contenuti_tradotti $strAlias")->on(array(
			"$alias.".$modelTabella->_idFields." = ".$modelTabella->_tables.".".$modelTabella->_idFields." and $alias.lingua = ?",
			array(
				sanitizeDb($lingua),
			),
		));
		
		return $this;
	}
	
	public function first()
	{
		$res = $this->send();
		
		if (count($res) > 0)
			return $res[0];
		
		return array();
	}
	
	//duplica gli elementi della pagina
	public function duplica($from_id, $to_id, $field = "id_page")
	{
		$clean["from_id"] = (int)$from_id;
		$clean["to_id"] = (int)$to_id;
		
		// Elimino la combinazione creata in automatico
		if ($this->_tables == 'combinazioni')
			$this->pDel(null, "id_page = ".$clean["to_id"]);
		
		$orderBy = isset($this->_idOrder) ? $this->_idOrder : $this->_idFields;
		
		$res = $this->clear()->where(array($field=>$clean["from_id"]))->orderBy($orderBy)->send(false);
		
		foreach ($res as $r)
		{
			$this->setValues($r, "sanitizeDb");
			$this->setValue($field, $to_id);
			
			unset($this->values[$this->_idFields]);
			
			if (isset($this->values["data_creazione"]))
				unset($this->values["data_creazione"]);
			
			if (isset($this->values["id_order"]))
				unset($this->values["id_order"]);
			
			self::$uploadFileGeneric = false;
			
			if ($this->insert())
			{
				$this->afterDuplica($to_id, $r[$this->_idFields], $this->lId);
			}
			
			self::$uploadFileGeneric = true;
		}
	}
	
	// Azione dopo il duplica (dopo aver eseguito l'insert del nuovo)
	// $toId: valore del nuovo elemento a cui è collegato
	// $oldPk: valore della vecchia primary key
	// $newPk: valore della nuova primary key
	public function afterDuplica($toId, $oldPk, $newPk)
	{
	
	}
	
	// Inserisci un gruppo
	public function inserisciGruppo($key)
	{
		$clean["id_elem"] = (int)$this->values[$key];
		$clean["id_group"] = (int)$this->values["id_group"];
		
		$u = new ReggroupsModel();
		
		$ng = $u->where(array("id_group"=>$clean["id_group"]))->rowNumber();
		
		if ($ng > 0)
		{
			$res3 = $this->clear()->where(array("id_group"=>$clean["id_group"],$key=>$clean["id_elem"]))->send();
			
			if (count($res3) > 0)
			{
				$this->notice = "<div class='alert'>Questo contenuto è già stato associato a questo gruppo</div>";
				return false;
			}
			else
			{
				return parent::insert();
			}
		}
		else
		{
			$this->notice = "<div class='alert'>Questo elemento non esiste</div>";
			return false;
		}
	}
	
	public function nazione($record)
	{
		if ($record["corrieri_spese"]["nazione"] != "W")
			return findTitoloDaCodice($record["corrieri_spese"]["nazione"]);
		
		return "Tutte";
	}
	
	public function nazionenavigazione($record)
	{
		$n = new NazioniModel();
		
		$nazione = $record[$this->_tables]["nazione_navigazione"];
		if (!$nazione)
			$nazione = v("nazione_default");
		
		return $n->findTitoloDaCodice($nazione);
	}
	
	public static function filtroNazioneNavigazione($ru)
	{
		$n = new NazioniModel();
		
		$res = $ru->clear()->select("distinct nazione_navigazione")->toList("nazione_navigazione")->send();
		
		$selectFiltro = array("tutti"=>"Tutti");
		
		foreach ($res as $r)
		{
			if (!$r)
				$r = v("nazione_default");
			
			$selectFiltro[$r] = $n->findTitoloDaCodice($r);
		}
		
		return $selectFiltro;
	}
	
	public function addNonImpostato($select, $empty)
	{
		if ($empty)
			$select += array(0 => gtext("-- NON IMPOSTATO --"));
		
		return $select;
	}
	
	public function selectMarchi($empty = true, $where = array())
	{
		$m = new MarchiModel();
		
		$select = array();
		
		$select = $this->addNonImpostato($select, $empty);
		
		$select += $m->clear()->select("concat(if(codice != '',concat(codice,' - '),''),titolo) as t,id_marchio")->where($where)->orderBy("titolo")->toList("id_marchio","aggregate.t")->send();
		
		return $select;
	}
	
	public function selectTag($empty = true)
	{
		$t = new TagModel();
		
		$select = array();
		
		$select = $this->addNonImpostato($select, $empty);
		
		$select += $t->clear()->orderBy("titolo")->toList("id_tag","titolo")->send();
		
		return $select;
	}
	
	public function selectDocumento($empty = true)
	{
		$d = new DocumentiModel();
		
		$select = array();
		
		$select = $this->addNonImpostato($select, $empty);
		
		$select += $d->clear()->orderBy("titolo")->toList("id_doc","titolo")->send();
		
		return $select;
	}
	
	public function getLinkEntries()
	{
		return array(
			'link_id_page'		=>	array(
				'type'		=>	'Select',
				'labelString'=>	'Link a contenuto',
				'options'	=>	$this->buildAllPagesSelect(),
				'reverse' => 'yes',
// 				"idName"	=>	"combobox",
				'entryAttributes'	=>	array(
					"select2"	=>	"",
				),
				'wrap'	=>	array(null,null,"<div>","</div>"),
			),
			'link_id_c'		=>	array(
				'type'		=>	'Select',
				'labelString'=>	'Link a categoria',
				'options'	=>	$this->buildAllCatSelect(),
				'reverse' => 'yes',
// 				"idName"	=>	"combobox1",
				'entryAttributes'	=>	array(
					"select2"	=>	"",
				),
				'wrap'	=>	array(null,null,"<div>","</div>"),
			),
			'link_id_marchio'		=>	array(
				'type'		=>	'Select',
				'labelString'=>	'Link a marchio',
				'options'	=>	$this->selectMarchi(),
				'reverse' => 'yes',
// 				"idName"	=>	"combobox",
				'entryAttributes'	=>	array(
					"select2"	=>	"",
				),
				'wrap'	=>	array(null,null,"<div>","</div>"),
			),
			'link_id_tag'		=>	array(
				'type'		=>	'Select',
				'labelString'=>	'Link a tag',
				'options'	=>	$this->selectTag(),
				'reverse' => 'yes',
// 				"idName"	=>	"combobox",
				'entryAttributes'	=>	array(
					"select2"	=>	"",
				),
				'wrap'	=>	array(null,null,"<div>","</div>"),
			),
			'link_id_documento'	=>	array(
				'type'		=>	'Select',
				'labelString'=>	'Link a documento',
				'options'	=>	$this->selectDocumento(),
				'reverse' => 'yes',
				'entryAttributes'	=>	array(
					"select2"	=>	"",
				),
				'wrap'	=>	array(null,null,"<div>","</div>"),
			),
			'target'	=>	array(
				'type'		=>	'Select',
				'labelString'=>	'Target del link',
				'options'	=>	array(
					"_self"		=>	"Nella stessa scheda",
					"_blank"	=>	"Su una nuova scheda",
				),
				'reverse' => 'yes',
// 				"idName"	=>	"combobox",
			),
		);
	}
	
	public function salvaMeta($metaModificato = 0, $campoDescrizione = "description")
	{
		if (self::$generaMetaQuandoSalvi && isset($this->values[$campoDescrizione]) && !$metaModificato)
		{
			$this->values["meta_description"] = sanitizeDb(strip_tags(br2space(htmlentitydecode($this->values[$campoDescrizione]))));
			
			if (v("numero_caratteri_meta_automatico") > 0)
				$this->values["meta_description"] = tagliaStringa($this->values["meta_description"], v("numero_caratteri_meta_automatico"));
		}
	}
	
	public function addWhereAttivoPermettiTest()
	{
		$this->aWhere(array(
			"pages.attivo"	=>	"Y",
			"pages.acquistabile"	=>	"Y",
			"pages.bloccato"	=>	0,
			"pages.temp"		=>	0,
			"pages.cestino"		=>	0,
		));
		
		return $this;
	}
	
	public function addWhereAttivo()
	{
		$this->aWhere(array(
			"pages.attivo"	=>	"Y",
			"pages.acquistabile"	=>	"Y",
			"pages.bloccato"	=>	0,
			"pages.test"		=>	0,
			"pages.temp"		=>	0,
			"pages.cestino"		=>	0,
		));
		
		return $this;
	}
	
	public function addWhereAttivoCategoria()
	{
		$this->aWhere(array(
			"categories.attivo"		=>	"Y",
			"categories.bloccato"	=>	0,
		));
		
		return $this;
	}
	
	public function addWhereOkSitemap($field = "id_c")
	{
		$c = new CategoriesModel();
		
		$this->aWhere(array(
			"   nin"	=>	array(
				"categories.$field"	=>	$c->childrenQuery(array(
					"add_in_sitemap_children"	=>	"N",
				)),
			),
		));
		
		return $this;
	}
	
	public function addWhereCategoriaInstallata()
	{
		$c = new CategoriesModel();
		
		$arrayIdCatAttive =  $c->childrenQuery(array(
			"installata"	=>	1,
		));
		
		$arrayIdCatAttive[] = 1;
		
		$this->aWhere(array(
			"  in"	=>	array(
				"categories.id_c"	=>	$arrayIdCatAttive,
			),
		));
		
		return $this;
	}
	
	public function controllaElementoInSitemap($id)
	{
		if (v("permetti_gestione_sitemap"))
		{
			$record = $this->checkDataPerSitemap($id);
			
			if (empty($record))
			{
				$sm = new SitemapModel();
				$sm->del(null, array(
					$this->_idFields	=>	(int)$id,
				));
			}
			else
				$this->aggiungiAllaSitemap($record);
		}
	}
	
	public function addWhereCategoria($idCat, $full = true, $key = "-id_c")
	{
		$this->aWhere(CategoriesModel::gCatWhere($idCat, $full, $key));
		
		return $this;
	}
	
	public function sWhereFiltriSuccessivi($elemento = "")
	{
		$whereIn = "";
		
		if (v("attiva_filtri_successivi"))
		{
			$binderdValues = array();
			
			if (isset(CategoriesModel::$arrayIdsPagineFiltrate[$elemento]))
			{
				$ids = array_map("forceInt",CategoriesModel::$arrayIdsPagineFiltrate[$elemento]);
				
				if (count(CategoriesModel::$arrayIdsPagineFiltrate[$elemento]) > 0)
				{
// 					echo "(select id_page from pages where id_page in (".implode(",",$ids).")) as tabella_filtri_successivi";
// 					$tabella_filtri_successivi = "(select id_page from pages where id_page in (".$this->placeholdersFromArray($ids).")) as tabella_filtri_successivi";
// 					$this->inner(array($tabella_filtri_successivi, $ids))->on("pages.id_page = tabella_filtri_successivi.id_page");
					
// 					if (isset(CategoriesModel::$arrayIdsPagineFiltrate[$elemento."[query]"]))
// 					{
// 						$query = CategoriesModel::$arrayIdsPagineFiltrate[$elemento."[query]"][0];
// 						$dataB = CategoriesModel::$arrayIdsPagineFiltrate[$elemento."[query]"][1];
// // 						echo $query;die();
// 						$this->inner(array("($query) as tabella_filtri_successivi", $dataB))->on("pages.id_page = tabella_filtri_successivi.id_page");
// 					}
// 					else
// 					{
						$whereIn = "pages.id_page in (".$this->placeholdersFromArray($ids).")";
						$binderdValues = $ids;
// 					}
				}
				else
					$whereIn = "1 != 1";
			}
			else
				$whereIn = "1 = 1";
			
// 			$this->sWhere(array($whereIn, $binderdValues));
			
			if ($whereIn)
				$this->sWhere(array($whereIn, $binderdValues));
		}
		
		return $this;
	}
	
	public static function g($traduzione = true)
	{
		$className = get_called_class();
		
		$m = new $className;
		$m->clear();
		
		if ($traduzione && $m->traduzione)
			$m->addJoinTraduzione();
		
		return $m;
	}
	
	public static function numeroRecord()
	{
		$className = get_called_class();
		
		$m = new $className;
		
		return $m->clear()->rowNumber();
	}
	
	// Usato da DocumentilinguaModel e PageslinguaModel
	public function tipoVisibilitaLingua($record)
    {
		if ($record[$this->_tables]["includi"])
			return "<span class='text text-success text-bold'>".gtext("Includi")."</span>";
		else
			return "<span class='text text-danger text-bold'>".gtext("Escludi")."</span>";
    }
    
    // Usato da DocumentilinguaModel e PageslinguaModel
    public function recuperaCodiceLingua()
    {
		if (isset($this->values["id_lingua"]))
		{
			$this->values["lingua"] = LingueModel::g(false)->clear()->where(array(
				"id_lingua"	=>	(int)$this->values["id_lingua"],
			))->field("codice");
		}
    }
    
    public function bulkaggiungiacategoria($record)
    {
		return "<i data-azione='aggiungiacategoria' title='".gtext("Aggiungi a categoria")."' class='bulk_trigger help_trigger_aggiungi_a_categoria fa fa-plus-circle text text-primary'></i>";
    }
    
    public function bulkaggiungiaprodotto($record)
    {
		return "<i data-azione='aggiungiaprodotto' title='".gtext("Aggiungi al prodotto")."' class='bulk_trigger help_trigger_aggiungi_al_prodotto fa fa-plus-circle text text-primary'></i>";
    }
    
    public function cleanDateTime($record)
    {
		$formato = "d-m-Y H:i";
		
		if (isset($record[$this->_tables]["data_creazione"]) && $record[$this->_tables]["data_creazione"])
			return date($formato,strtotime($record[$this->_tables]["data_creazione"]));
		
		return "";
    }
    
    public function setDalAlWhereClause($dal, $al, $field = "data_creazione", $table = null)
    {
		if (!isset($table))
			$table = $this->_tables;
		
		if ($dal != "tutti")
			$this->sWhere(array("DATE_FORMAT(".$table.".$field, '%Y-%m-%d') >= ?",array(getIsoDate($dal))));
		
		if ($al != "tutti")
			$this->sWhere(array("DATE_FORMAT(".$table.".$field, '%Y-%m-%d') <= ?",array(getIsoDate($al))));
    }
    
    public function overrideFormStruct() {}
    
    public function checkBloccato($id, $tipo = "category")
    {
		$record = $this->selectId((int)$id);
		
		$bloccato = false;
		
		if (empty($record))
			$bloccato = true;
		
		if ($record["bloccato"])
			$bloccato = true;
		
		if ($tipo == "page" && ($record["temp"] || $record["cestino"]))
			$bloccato = true;
		
		if ($bloccato)
		{
			$h = new HeaderObj();
			$h->redirect("");
			die();
		}
    }
    
    // Aggiungi o togli l'elemento dalla sitemap
    public function inserisciTogliSitemap($id, $valore = "Y")
    {
		$this->setValues(array(
			"add_in_sitemap"	=>	$valore,
		));
		
		$this->pUpdate((int)$id);
    }
    
    public function insertOrUpdate($where)
    {
		$record = $this->clear()->where($where)->find();
		
		if (!empty($record))
		{
			if ($this->update($record[$this->_idFields]))
				return $record[$this->_idFields];
		}
		else
		{
			if ($this->insert())
				return $this->lId;
		}
		
		return 0;
    }
    
    public function edit($record)
	{
		return "<span class='data-record-id' data-primary-key='".$record[$this->_tables][$this->_idFields]."'>".$record[$this->_tables][$this->campoTitolo]."</span>";
	}
	
	public function settaCifreDecimali()
	{
		if (!v("prezzi_ivati_in_prodotti"))
		{
			if (isset($this->values["price"]))
				$this->values["price"] = number_format(setPrice($this->values["price"]), v("cifre_decimali_visualizzate"),".","");
			
			if (isset($this->values["prezzo_promozione_ass"]))
				$this->values["prezzo_promozione_ass"] = number_format(setPrice($this->values["prezzo_promozione_ass"]), v("cifre_decimali_visualizzate"),".","");
		}
		
	}
	
	public function aggiungiAGruppoTipo($id, $tipo = "CO")
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && isset($_GET["id_group"]))
		{
			$rgt = new ReggroupstipiModel();
			
			$rgt->setValues(array(
				"id_group"	=>	(int)$_GET["id_group"],
				"id_tipo"	=>	(int)$id,
				"tipo"		=>	$tipo,
			), "sanitizeDb");
			
			if ($rgt->pInsert())
				$rgt->elabora($rgt->lId, "INSERT");
		}
    }
    
    public function gOrderBy()
    {
		return $this;
    }
    
    public function titolocompleto($record)
	{
		$titolo = $record[$this->_tables]["title"];
		
		if ($record[$this->_tables]["attributi"])
			$titolo .= "<br />".$record[$this->_tables]["attributi"];
		
		return $titolo;
	}
	
	public function thumb($record)
	{
		if ($record[$this->_tables]["immagine"])
			return "<img width='70px' src='".Url::getFileRoot()."thumb/contenuto/".$record[$this->_tables]["immagine"]."' />";
		
		return "";
	}
	
	public function ordini($record)
	{
		$idC = (int)$record[$this->_tables]["id_c"];
		
		$r = new RigheModel();
		
		$res = $r->clear()->inner("orders")->on("orders.id_o = righe.id_o")->select("sum(righe.quantity) as SOMMA")->where(array(
			"id_c"	=>	$idC,
// 			"ne" => array(
// 				"orders.stato"	=>	"deleted"
// 			),
		))->send();
		
		if (count($res) > 0 && $res[0]["aggregate"]["SOMMA"] > 0)
		{
			if (!isset($_GET["esporta"]))
				return $res[0]["aggregate"]["SOMMA"]." <a title='Elenco ordini dove è stato acquistato' class='iframe' href='".Url::getRoot()."ordini/main?partial=Y&id_comb=$idC'><i class='fa fa-list'></i></a>";
			else
				return $res[0]["aggregate"]["SOMMA"];
		}
		
		return "";
	}
	
	public function filtro($idA = 0)
	{
		return $this->clear()->toList($this->_idFields,$this->campoTitolo)->orderBy($this->campoTitolo)->send();
	}
	
	public static function getFiltroAttivo()
	{
		return array("attivo",null,array(
			"tutti"	=>	gtext("Visibilità"),
			"Y"		=>	gtext("Pubblicato"),
			"N"		=>	gtext("Non pubblicato"),
		));
	}
	
	public static function getFiltroCestino()
	{
		return array("cestino",null,array(
			"0"		=>	gtext("Elementi presenti"),
			"1"		=>	gtext("Cestino"),
		));
	}
	
	// Controlla l'utente frontend
	public function checkUtente($action = "insert", $id = 0)
	{
		if ($action == "insert")
			return true;
		else
		{
			$record = $this->selectId((int)$id);
			
			if (!empty($record) && (int)$record["id_user"] === (int)User::$id)
				return true;
		}
		
		return false;
	}
	
	// se cestino = 1
	public function isDeleted($id)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && $record["cestino"])
			return true;
		
		return false;
	}
	
	// imposta cestino = 0
	public function ripristina($id)
	{
		if ($this->isDeleted($id))
		{
			$this->setValues(array(
				"cestino"	=>	0,
			));
			
			return $this->pUpdate($id);
		}
	}
	
	public function hasPage($id)
    {
		return $this->clear()->where(array(
			$this->_idFields	=>	(int)$id,
		))->field("id_page");
    }
    
    public function ruoloCrud($record)
    {
		$r = new RuoliModel();
		
		return $r->titolo($record[$this->_tables]["id_ruolo"]);
    }
    
    public function mDel($where)
    {
		$idS = $this->clear()->sWhere($where)->toList($this->_idFields)->send();
		
		foreach ($idS as $id)
		{
			$this->del($id);
		}
    }
    
    public function selectUtenti($id = 0, $soloClienteSelezionato = false, $sWhere = null)
    {
		$ru = new RegusersModel();
		
		$ru->clear()
			->select("id_user,username,nome,cognome,ragione_sociale,tipo_cliente")
			->orderBy("deleted,nome,cognome,ragione_sociale,email");
		
		if ($soloClienteSelezionato)
			$ru->where(array(
				"OR"	=>	array(
					"id_user"		=>	(int)$id,
					" id_user"	=>	isset($_POST["id_user"]) ? (int)$_POST["id_user"] : "-1",
				),
			));
		else
			$ru->where(array(
				"OR"	=>	array(
					"id_user"	=>	(int)$id,
					"deleted"	=>	"no",
				),
			));
		
		if ($sWhere)
			$ru->sWhere($sWhere);
		
		$res = $ru->send(false);
		
		$select = array();
		
		foreach ($res as $r)
		{
			$select[$r["id_user"]] = self::getNominativo($r)." - ".$r["username"];
		}
		
		return $select;
    }
    
    public function selectIva()
	{
		$iva = new IvaModel();
		
		return $iva->clear()->orderBy("id_order")->toList("id_iva","titolo")->send();
	}
	
	public function setRecordTabella($key)
	{
		$res = $this->clear()->send(false);
		
		self::$recordTabella = array();
		
		foreach ($res as $r)
		{
			self::$recordTabella[$r[$key]] = $r;
		}
	}
	
	public function setRecordTabellaG($key)
	{
		$classe = get_called_class();
		
		$res = $this->clear()->send(false);
		
		self::$recordTabellaG[$classe] = array();
		
		foreach ($res as $r)
		{
			self::$recordTabellaG[$classe][$r[$key]] = $r;
		}
	}
	
	// Restituisce un campo di un record
	public static function getCampoG($codiceStato, $campo, $campoChiave = "codice")
	{
		$classe = get_called_class();
		
		if (!isset(self::$recordTabellaG[$classe]))
			self::g(false)->setRecordTabellaG($campoChiave);
		
		return isset(self::$recordTabellaG[$classe][$codiceStato][$campo]) ? self::$recordTabellaG[$classe][$codiceStato][$campo] : null;
	}
	
	public function noteCrud($record)
	{
		$nModel = new NoteModel();
		
		return $nModel->noteCrudHtml($this->_tables, $record[$this->_tables][$this->_idFields]);
	}
	
	public function statoElementoCrud($record)
	{
		$seModel = new StatielementiModel();
		
		return $seModel->statiElementiCrudHtml($this->_tables, $record[$this->_tables][$this->_idFields]);
	}
	
	public function gElencoProdotti($lingua, $record)
	{
		if (!isset($record["id_o"]))
			return "";
		
		$r = new RigheModel();
		
		$righeOrdine = $r->clear()->where(array("id_o"=>(int)$record["id_o"]))->send();
		
		ob_start();
		include tpf("/Elementi/Placeholder/elenco_prodotti.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function getWhereSearch($cerca, $maxNumber = 50, $campoCerca = "campo_cerca", $tabellaCerca = "pages")
	{
		$cercaArray = explode(" ",(string)trim($cerca));
		
		if (count($cercaArray) > $maxNumber)
			return array(
				"pages.id_page"	=>	"-1",
			);
		
		$andArray = array();
		
		$iCerca = 8;
		
		foreach ($cercaArray as $cercaTermine)
		{
			if (strpos(v("ricerca_termini_and_or"), "OR") === false || strlen($cercaTermine) > 2)
			{
				$andArray[str_repeat(" ", $iCerca)."lk"] = array(
					$tabellaCerca.".".$campoCerca	=>	sanitizeAll(htmlentitydecode($cercaTermine)),
				);
				
				$iCerca++;
			}
		}
		
		return $andArray;
	}
	
	public function addWhereSearch($cerca, $maxNumber = 50, $campoCerca = "campo_cerca", $tabellaCerca = "pages")
	{
		$andArray = $this->getWhereSearch($cerca, $maxNumber, $campoCerca, $tabellaCerca);
		
		if (count($andArray) > 0)
			$this->aWhere($andArray);
		
		return $this;
	}
	
	public static function filtroSoloCategoria()
	{
		if (CategoriesModel::$currentIdCategory && !MarchiModel::$currentId && !TagModel::$currentId && count(RegioniModel::$filtriUrl) === 0 && count(CaratteristicheModel::$filtriUrl) === 0 && count(AltriFiltri::$filtriUrl) === 0)
			return true;
		
		return false;
	}
	
	public function agenteCrud($record)
	{
		if ($record[$this->_tables][$this->nomeCampoIdUser])
		{
			$utente = RegusersModel::g()->selectId((int)$record[$this->_tables][$this->nomeCampoIdUser]);
			
			if (!empty($utente))
			{
				$html = RegusersModel::getNominativo($utente);
				
				if (ControllersModel::checkAccessoAlController(array("regusers")))
					$html .= " <a title='".gtext("Dettagli agente")."' href='".Url::getRoot()."regusers/form/update/".(int)$record[$this->_tables][$this->nomeCampoIdUser]."?partial=Y&nobuttons=Y&agente=1' class='iframe label label-info text-bold'><i class='fa fa-user'></i></a>";
				
				return $html;
			}
		}
		
		return "";
	}
	
	// se la data non è una vera data, restituisce BLANK
	public function fakeDataToBlank($data)
	{
		if (!checkIsoDate(getIsoDate(nullToBlank($data))))
			return "";
		
		return $data;
	}
	
	public function codiceGestionaleCrud($record)
	{
		if (isset($record[$this->_tables]["codice_gestionale"]))
			return $record[$this->_tables]["codice_gestionale"];
		
		return "";
	}
}
