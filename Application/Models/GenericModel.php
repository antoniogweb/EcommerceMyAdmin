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

class GenericModel extends Model_Tree
{
	use CommonModel;
	
	public static $apiMethod = "POST";
	public static $uploadFileGeneric = true;
	
	public $usingApi = false;
	public $campoTitolo = "titolo";
	public $uploadFields = array();
	public $lId = null;
	public $traduzione = false;
	public $formStructAggiuntivoEntries = array();
	public $salvaDataModifica = false;
	
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
	);
	
	public static $tabelleConAlias = array();
	
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
		$arrayUnion = array();
		
		foreach (self::$tabelleConAliasMap as $table => $params)
		{
			$campoChiave = $params["chiave"];
			$campoTitolo = $params["campoTitolo"];
			
			if ($params["tradotta"])
				$sql = "select coalesce(contenuti_tradotti.$campoTitolo, $table.$campoTitolo) as titolo_filtro from $table left join contenuti_tradotti on contenuti_tradotti.$campoChiave =  $table.$campoChiave and contenuti_tradotti.lingua = '".sanitizeDb(Params::$lang)."' where coalesce(contenuti_tradotti.alias,$table.alias) = '".sanitizeAll($alias)."'";
			else
				$sql = "select $table.$campoTitolo as titolo_filtro from $table where $table.alias = '".sanitizeAll($alias)."'";
			
			$arrayUnion[] = $sql;
		}
		
		$arrayUnion[] = "select titolo from nazioni where iso_country_code = '".sanitizeAll($alias)."'";
		
		$sql = implode(" UNION ", $arrayUnion);
		
		$mysqli = Db_Mysqli::getInstance();
		
		$res = $mysqli->query($sql);
		
		if (count($res) > 0 && isset($res[0]["aggregate"]["titolo_filtro"]) && trim($res[0]["aggregate"]["titolo_filtro"]))
			return $res[0]["aggregate"]["titolo_filtro"];
		
		if ($alias == AltriFiltri::$aliasValoreTipoInEvidenza[0])
			return AltriFiltri::$aliasValoreTipoInEvidenza[1];
		
		if ($alias == AltriFiltri::$aliasValoreTipoNuovo[0])
			return AltriFiltri::$aliasValoreTipoNuovo[1];
		
		if ($alias == AltriFiltri::$aliasValoreTipoPromo[0])
			return AltriFiltri::$aliasValoreTipoPromo[1];
		
		return null;
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
			$this->values["alias"] = $this->values["alias"] . "-" . generateString(4,"123456789abcdefghilmnopqrstuvz");
	}
	
	public function checkAliasAll($id, $noTraduzione = false)
	{
		if (isset($this->values["alias"]) && !trim($this->values["alias"]))
			$this->values["alias"] = sanitizeDb(encodeUrl($this->values["titolo"]));
			
		$clean["id"] = (int)$id;
		
		if (!$clean["id"])
			$res = $this->query("select alias from ".$this->_tables." where alias = '".sanitizeDb($this->values["alias"])."'");
		else
			$res = $this->query("select alias from ".$this->_tables." where alias = '".sanitizeDb($this->values["alias"])."' and ".$this->_idFields."!=".$clean["id"]);
		
		$this->addTokenAlias($res);
		
		$arrayUnion = array();
		
		foreach (GenericModel::$tabelleConAlias as $table)
		{
			if ($table == $this->_tables)
				continue;
			
			$arrayUnion[] = "select alias from $table where alias = '".sanitizeDb($this->values["alias"])."'";
		}
		
		$sql = implode(" UNION ", $arrayUnion);
		
		$res = $this->query($sql);
		
		$this->addTokenAlias($res);
		
		if (!isset($id) || $noTraduzione)
			$res = $this->query("select alias from contenuti_tradotti where alias = '".sanitizeDb($this->values["alias"])."'");
		else
			$res = $this->query("select alias from contenuti_tradotti where alias = '".sanitizeDb($this->values["alias"])."' and ".$this->_idFields."!=".$clean["id"]);
		
		$this->addTokenAlias($res);
	}
	
// 	public function alias($id = null) {}
	
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
						
						if (isset($params["clean_field"]))
						{
							$fileName = md5(randString(22).microtime().uniqid(mt_rand(),true));
						}
						else
						{
							$fileName = $this->files->getNameWithoutFileExtension($_FILES[$field]["name"]);
							$fileName = encodeUrl($fileName);
							$fileName = $this->files->getUniqueName($fileName);
						}
						
						self::creaCartellaImages($path);
						
						if ($this->files->uploadFile($fileName))
						{
							$this->values[$field] = sanitizeAll($this->files->fileName);
							
							if (isset($params["clean_field"]))
							{
								$cleanFileName = $this->files->getNameWithoutFileExtension($_FILES[$field]["name"]);
								$ext = $this->files->getFileExtension($_FILES[$field]["name"]);
								
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
						$src = Url::getRoot().$this->controller."/thumb/".$field."/".$clean["id"];
						$href = Url::getRoot().$this->controller."/documento/".$field."/".$clean["id"];
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
	
	public function update($id = null, $where = null)
	{
		$this->salvaDataUltimaModifica();
		
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
// 					$this->files->setBase(Domain::$parentRoot."/".trim($params["path"],"/"));
// 					$list = $this->clear()->select($field)->toList($field)->send();
// 					$list[] = "index.html";
// 					
// 					$this->files->removeFilesNotInTheList($list);
				}
			}
		}
		
		return $res;
	}
	
	public function pInsert()
	{
		return parent::insert();
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
		
		parent::insert();
		$res = $this->queryResult;
		
		$this->lId = $this->lastId();
		
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
	
	public function selectNazione($empty = false)
	{
		$n = new NazioniModel();
		
		if (!isset(NazioniModel::$elenco))
		{
			$default = $empty ? array("W"	=>	"Tutte le nazioni") : array("0"	=>	"Seleziona");
			NazioniModel::$elenco = $default + $n->select("iso_country_code,titolo")->orderBy("titolo")->toList("iso_country_code","titolo")->send();
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
			$ruoli = $r->clear()->select("*")->left("contenuti_tradotti")->on("contenuti_tradotti.id_ruolo = ruoli.id_ruolo and contenuti_tradotti.lingua = '".sanitizeDb(Params::$lang)."'")->orderBy("ruoli.titolo")->send();
			
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
	
	public function selectTipi($frontend = false)
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
			return array(0	=>	"--") + $this->clear()->orderBy($this->table().".".$this->campoTitolo)->toList($this->getPrimaryKey(), $this->campoTitolo)->send();
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
		
		return array(0	=>	gtext("-- NON IMPOSTATO --")) + $p->clear()->inner("categories")->on("pages.id_c = categories.id_c")->orderBy("pages.title")->where(array(
			"nin"	=>	array("categories.alias"	=>	array("slide")),
		))->toList("pages.id_page","pages.title")->send();
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
	
	// Controllo che la lingua esista
	public function controllaLinguaGeneric($id, $keyField = "id_page", $sezione = "")
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record))
		{
			$ct = new ContenutitradottiModel();
			
			foreach (BaseController::$traduzioni as $lingua)
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
				
				$ct->setValues(array(
					"lingua"		=>	sanitizeDb($lingua),
					"title"			=>	isset($record["title"]) ? $record["title"] : "",
					"description"	=>	isset($record["description"]) ? $record["description"] : "",
					"alias"			=>	isset($record["alias"]) ? $record["alias"] : "",
					"keywords"		=>	isset($record["keywords"]) ? $record["keywords"] : "",
					"meta_description"	=>	isset($record["meta_description"]) ? $record["meta_description"] : "",
					"url"			=>	isset($record["url"]) ? $record["url"] : "",
					"sottotitolo"	=>	isset($record["sottotitolo"]) ? $record["sottotitolo"] : "",
					"titolo"		=>	isset($record["titolo"]) ? $record["titolo"] : "",
					"descrizione"	=>	isset($record["descrizione"]) ? $record["descrizione"] : "",
					"testo_link"	=>	isset($record["testo_link"]) ? $record["testo_link"] : "",
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
			Cache::$cachedTables = array();
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
	
// 	public function linken($record)
// 	{
// 		return $this->linklingua($record, "en");
// 	}
	
// 	public function linkfr($record)
// 	{
// 		return $this->linklingua($record, "fr");
// 	}
// 	
// 	public function linkes($record)
// 	{
// 		return $this->linklingua($record, "es");
// 	}
// 	
// 	public function linkde($record)
// 	{
// 		return $this->linklingua($record, "de");
// 	}
// 	
// 	public function linkpl($record)
// 	{
// 		return $this->linklingua($record, "pl");
// 	}
// 	
// 	public function linkpt($record)
// 	{
// 		return $this->linklingua($record, "pt");
// 	}
// 	
// 	public function linkru($record)
// 	{
// 		return $this->linklingua($record, "ru");
// 	}
// 	
// 	public function linkse($record)
// 	{
// 		return $this->linklingua($record, "se");
// 	}
	
	public function buildAllCatSelect()
	{
		$c = new CategoriesModel();
		
		return array("0"=>gtext("-- NON IMPOSTATO --")) + $c->buildSelect(null, false);
	}
	
	public function buildAllPagesSelect()
	{
		return array("0"=>gtext("-- NON IMPOSTATO --")) + $this->clear()->orderBy("title")->toList("id_page","title")->send();
	}
	
	public function addJoinTraduzione($lingua = null, $alias = "contenuti_tradotti", $selectAll = true)
	{
		if (!isset($lingua))
			$lingua = Params::$lang;
		
		$strAlias = " as $alias";
		
		$this->select("*")->left("contenuti_tradotti $strAlias")->on("$alias.".$this->_idFields." = ".$this->_tables.".".$this->_idFields." and $alias.lingua = '".sanitizeDb($lingua)."'");
		
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
		
		$res = $this->clear()->where(array($field=>$clean["from_id"]))->orderBy($this->_idFields)->send(false);
		
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
			$this->insert();
			self::$uploadFileGeneric = true;
		}
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
	
	public function selectMarchi($empty = true)
	{
		$m = new MarchiModel();
		
		$select = array();
		
		$select = $this->addNonImpostato($select, $empty);
		
		$select += $m->clear()->orderBy("titolo")->toList("id_marchio","titolo")->send();
		
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
	
	public function getLinkEntries()
	{
		return array(
			'link_id_page'		=>	array(
				'type'		=>	'Select',
				'labelString'=>	'Link a contenuto',
				'options'	=>	$this->buildAllPagesSelect(),
				'reverse' => 'yes',
				"idName"	=>	"combobox",
			),
			'link_id_c'		=>	array(
				'type'		=>	'Select',
				'labelString'=>	'Link a categoria',
				'options'	=>	$this->buildAllCatSelect(),
				'reverse' => 'yes',
				"idName"	=>	"combobox1",
			),
			'link_id_marchio'		=>	array(
				'type'		=>	'Select',
				'labelString'=>	'Link a marchio',
				'options'	=>	$this->selectMarchi(),
				'reverse' => 'yes',
				"idName"	=>	"combobox",
			),
			'link_id_tag'		=>	array(
				'type'		=>	'Select',
				'labelString'=>	'Link a tag',
				'options'	=>	$this->selectTag(),
				'reverse' => 'yes',
				"idName"	=>	"combobox",
			),
		);
	}
	
	public function salvaMeta($metaModificato = 0, $campoDescrizione = "description")
	{
		if (isset($this->values[$campoDescrizione]) && !$metaModificato)
			$this->values["meta_description"] = sanitizeDb(strip_tags(br2space(htmlentitydecode($this->values[$campoDescrizione]))));
	}
	
	public function addWhereAttivo()
	{
		$this->aWhere(array(
			"pages.attivo"	=>	"Y",
			"pages.acquistabile"	=>	"Y",
		));
		
		return $this;
	}
	
	public function sWhereFiltriSuccessivi()
	{
		$whereIn = "";
		
		if (v("attiva_filtri_successivi"))
		{
			if (count(CategoriesModel::$arrayIdsPagineFiltrate) > 0)
				$whereIn = "pages.id_page in (".implode(",",CategoriesModel::$arrayIdsPagineFiltrate).")";
			else
				$whereIn = "1 =! 1";
			
			$this->sWhere($whereIn);
		}
		
		return $this;
	}
	
	public static function g($traduzione = true)
	{
		$className = get_called_class();
		
		$m = new $className;
		
		if ($traduzione && $m->traduzione)
			$m->addJoinTraduzione();
		
		return $m;
	}
	
	public static function numeroRecord()
	{
		$className = get_called_class();
		
		$m = new $className;
		
		return $m->rowNumber();
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
			$this->values["lingua"] = LingueModel::g()->clear()->where(array(
				"id_lingua"	=>	(int)$this->values["id_lingua"],
			))->field("codice");
		}
    }
}
