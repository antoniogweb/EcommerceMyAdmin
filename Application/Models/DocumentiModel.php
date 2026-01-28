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

class DocumentiModel extends GenericModel {
	
	public static $pathFiles = "images/documenti";
	
	public $elencaDocumentiPaginaImport = false;
	
	public $parentRootFolder;
	
	public $sectionDocumenti = null;
	
	public $convertiInJpeg = false;
	
	public static $uploadFile = true;
	
	public $campoDataUploadDocumento = "data_file_upload";
	
	public function __construct() {
		$this->_tables='documenti';
		$this->_idFields='id_doc';

		$this->orderBy = 'id_order desc';
		$this->_idOrder = 'id_order';
		
		$this->traduzione = true;
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		$this->uploadFields = array(
			"immagine"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/anteprimedocumenti",
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg',
				'allowedMimeTypes'	=>	'',
				"createImage"	=>	true,
				"maxFileSize"	=>	v("dimensioni_upload_documenti"),
				"clean_field"	=>	"clean_immagine",
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	400,
					'imgHeight'		=>	400,
					'defaultImage'	=>  null,
					'cropImage'		=>	'no',
				),
			),
			"filename"	=>	array(
				"type"	=>	"file",
				"path"	=>	self::$pathFiles,
				"basePath"	=>	defined('BASE_PATH_DOCUMENTI') ? BASE_PATH_DOCUMENTI : '',
				"allowedExtensions"	=>	'pdf,png,jpg,jpeg',
				"maxFileSize"	=>	v("dimensioni_upload_documenti"),
				"clean_field"	=>	"clean_filename",
				"Content-Disposition"	=>	"inline",
			),
		);
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_doc', null, "CASCADE"),
			'lingue' => array("HAS_MANY", 'DocumentilingueModel', 'id_doc', null, "CASCADE"),
			'download' => array("HAS_MANY", 'DocumentidownloadModel', 'id_doc', null, "CASCADE"),
			'page' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
			'tipo' => array("BELONGS_TO", 'TipidocumentoModel', 'id_tipo_doc',null,"CASCADE"),
			'gruppi' => array("MANY_TO_MANY", 'ReggroupsModel', 'id_group', array("ReggroupsdocumentiModel","id_doc","id_group"), "CASCADE"),
			'user' => array("BELONGS_TO", 'RegusersModel', 'id_user',null,"CASCADE"),
        );
    }
    
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'lingua'	=>	array(
					"type"	=>	"Select",
					"options"	=>	array("tutte" => gtext("TUTTE")) + $this->selectLingua(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					"labelString"	=>	"Visibile su lingua",
				),
				'id_tipo_doc'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Tipo documento",
					"options"	=>	$this->selectTipo(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'id_page'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Contenuto da linkare',
					'options'	=>	$this->buildContentSelect(),
					'reverse' => 'yes',
					'entryClass'  => 'form_input_text cont_Select',
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
					'wrap'	=>	array(null,null,"<div>","</div>"),
				),
				'permessi'	=>	array(
					// "type"	=>	"Hidden",
					'entryClass'  => 'form_input_text js_permessi',
					"className"	=>	"form-control",
					"labelString"	=>	"Permessi",
					"attributes"	=>	"style='display:none;'",
					'wrap'		=>	array(
						null,
						null,
						"<div class='js_permessi_checkbox'>".$this->getHtmlCheckboxPermessi($id)."</div>",
					),
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	public function idGruppiDocumenti($id)
	{
		$rgdModel = new ReggroupsdocumentiModel();
		
		return $rgdModel->clear()->where(array(
			"id_doc"	=>	(int)$id
		))->toList("id_group")->send();
	}
	
	public function getHtmlCheckboxPermessi($id)
	{
		$rgModel = new ReggroupsModel();
		
		$idGruppiInseriti = $this->idGruppiDocumenti($id);
		
		$permessi = $rgModel ->select("reggroups.name,reggroups.id_group")->where(array(
			"reggroups.usato_per_documenti"	=>	1,
		))->orderBy("reggroups.name")->toList("reggroups.id_group","reggroups.name")->send();
		
		$html = array();
		
		foreach ($permessi as $id => $nome)
		{
			if ($this->result && $id)
				$valore = in_array($id, $idGruppiInseriti) ? $id : "";
			else if ($this->result && !$id)
				$valore = "";
			else if (!$this->result)
				$valore = isset($_POST["_GR_".$id]) ? $id : "";
			else
				$valore = "";
			
			$html[] = Html_Form::checkbox("_GR_".$id, $valore, $id, null, null, "style='width:20px;'")." ".$nome;
		}
		
		return implode("<br />", $html);
	}
	
	public function buildContentSelect()
	{
		$p = new PagesModel();
		$c = new CategoriesModel();
		
		if ($this->sectionDocumenti)
			$idC = (int)CategoriesModel::getIdCategoriaDaSezione($this->sectionDocumenti);
		else
			$idC = $c->getShopCategoryId();
		
		return $p->clear()->addWhereCategoria($c->getShopCategoryId())->addWhereAttivo()->sWhere("id_user = 0")->orderBy("pages.id_order")->toList("id_page","title")->send();
	}
	
	public function selectTipo()
	{
		$t = new TipidocumentoModel();
		
		return array(0	=>	"--") + $t->clear()->orderBy("titolo")->toList("id_tipo_doc","titolo")->send();
	}
    
    public function titoloDocumento($record)
    {
		return "<a class='iframe action_iframe' href='".Url::getRoot()."documenti/form/update/".$record["documenti"]["id_doc"]."?partial=Y&nobuttons=Y'>".$record["documenti"]["titolo"]."</a>";
    }
    
    public function filename($record)
    {
		return "<a target='_blank' href='".Url::getRoot()."documenti/documento/filename/".$record["documenti"]["id_doc"]."'>".$record["documenti"]["clean_filename"]."</a>";
    }
    
    public function immagine($record)
    {
		if ($record["documenti"]["immagine"])
			return "<img width='100px;' src='".Url::getRoot()."documenti/thumb/immagine/".$record["documenti"]["id_doc"]."'/>";
    }
    
    public function elaborato($record)
    {
		if ($record["documenti"]["elaborato"])
			return "<i class='text text-success fa fa-check'></i>";
		else
			return "<i class='text text-danger fa fa-ban'></i>";
    }
    
    public function hasUser($id)
    {
		return (int)$this->clear()->where(array(
			$this->_idFields	=>	(int)$id,
		))->field("id_user");
    }
    
	public function update($id = NULL, $whereClause = NULL)
	{
		if (v("attiva_reggroups_tipi"))
			$old = $this->selectId((int)$id);
		
		if ($this->upload("update"))
		{
			$this->recuperaEstensione();
			
			$res = parent::update($id, $whereClause);
			
			if ($res && v("attiva_reggroups_tipi"))
			{
				$new = $this->selectId((int)$id);
				
				if ((int)$new["id_tipo_doc"] !== (int)$old["id_tipo_doc"])
				{
					$rgt = new ReggroupstipiModel();
					
					$rgd = new ReggroupsdocumentiModel();
					
					$rgd->del(null, "id_doc = ".(int)$id);
					
					$rgt->elaboraTutto("INSERT", $id);
				}
			}
			
			return $res;
		}
	}
	
	protected function recuperaEstensione()
	{
		if (!isset($this->values["estensione"]))
		{
			$ext = $this->files->ext;
			
			if (isset($ext) && $ext)
				$this->setValue("estensione", $ext);
		}
	}
	
	public function insert()
	{
		if (!self::$uploadFile || $this->upload("insert"))
		{
			if (self::$uploadFile)
				$this->recuperaEstensione();
			
			if (!v("attiva_data_documento"))
				$this->values["data_documento"] = date("Y-m-d");
			
			$res = parent::insert();
			
			if ($res && v("attiva_reggroups_tipi"))
			{
				$rgt = new ReggroupstipiModel();
				
				$rgt->elaboraTutto("INSERT", $this->lId);
			}
			
			return $res;
		}
	}
	
	public function accessi($record)
	{
		$rc = new ReggroupsdocumentiModel();
		
		$gruppi = $rc->clear()->select("reggroups.name")->where(array(
			"id_doc"	=>	$record["documenti"]["id_doc"],
		))->inner(array("gruppo"))->toList("reggroups.name")->send();
		
		if (count($gruppi) > 0)
			return implode("<br />", $gruppi);
		
		return "-";
	}
	
	public function escludilingua($record)
	{
		$dl = new DocumentilingueModel();
		
		$altreLingue = $dl->clear()->where(array(
			"id_doc"	=>	(int)$record[$this->_tables]["id_doc"],
			"includi"	=>	0,
		))->toList("lingua")->send();
		
		if (count($altreLingue) > 0)
			return "<span class='text text-danger text-bold'>".strtoupper(implode(" + ", $altreLingue))."</span>";
		
		return "";
	}
	
	public function lingua($record)
	{
		LingueModel::getValori();
		
		if ("attiva_altre_lingue_documento")
		{
			$str = strtoupper($record[$this->_tables]["lingua"]);
			
			if ($record[$this->_tables]["lingua"] != "tutte")
			{
				$dl = new DocumentilingueModel();
				
				$altreLingue = $dl->clear()->where(array(
					"id_doc"	=>	(int)$record[$this->_tables]["id_doc"],
					"includi"	=>	1,
				))->toList("lingua")->send();
				
				if (count($altreLingue) > 0)
					$str .= " + ".strtoupper(implode(" + ", $altreLingue));
			}
		}
		else
		{
			if (isset(LingueModel::$valori[$record[$this->_tables]["lingua"]]))
				$str = strtoupper(LingueModel::$valori[$record[$this->_tables]["lingua"]]);
			else
				$str = strtoupper($record[$this->_tables]["lingua"]);
		}
		
		$str = str_replace("TUTTE", gtext("TUTTE"), $str);
		
		return "<span class='text text-success text-bold'>".$str."</span>";
	}
	
	public function elaboraArchivio($id, $idPage = 0, $idUser = 0)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && $record["archivio"] && !$record["elaborato"])
		{
			$zip = new ZipArchive;
			
			$filePath = Domain::$parentRoot."/images/documenti/" . $record["filename"];
			
			self::creaCartellaImages("images/tmp", true);
			
			$tempFolder = randomToken();
			
			GenericModel::creaCartellaImages("images/tmp/$tempFolder", false, false);
			
			$extractPath = Domain::$parentRoot."/images/tmp/$tempFolder/";
			
			if (file_exists($filePath) && $zip->open($filePath) === TRUE) {
				$zip->extractTo($extractPath);
				$zip->close();
			}
			
			$okElaborazione = array();
			
			$this->checkExtAndMimeTypeFull($extractPath);
			
			$items = scandir($extractPath);
			foreach( $items as $this_file ) {
				if( strcmp($this_file,".") !== 0 && strcmp($this_file,"..") !== 0) {
					$this_file = basename($this_file);
					
					if (is_dir($extractPath.$this_file))
						self::eliminaCartella($extractPath.$this_file);
					
					$okElaborazione[] = $this->scDocumento($extractPath, $this_file, 0, array(
						"id_page"		=>	$idPage,
						"id_archivio"	=>	$id,
						"id_user"		=>	$idUser,
					));
				}
			}
			
			@rmdir($extractPath);
			
			$okElaborazione = array_unique($okElaborazione);
			
			if (count($okElaborazione) === 1 && $okElaborazione[0])
			{
				$this->setValues(array(
					"elaborato"	=>	1
				));
				
				$this->pUpdate((int)$id);
				
				// Gestisci archivio
				if (v("elimina_archivio_dopo_upload"))
					@unlink($filePath);
			}
		}
	}
	
	public static function cercaLinguaDaNomeFile($nomeFileCompleto)
	{
		if (!v("cerca_lingua_documento_da_nome_file"))
			return v("lingua_default_documenti");
		
		$l = new LingueModel();
		
		$fileSenzaEstensione = $l->files->getNameWithoutFileExtension($nomeFileCompleto);
		
		$temp = explode(".", $fileSenzaEstensione);
		
		$lingua = end($temp);
		$lingua = strtolower($lingua);
		
		if ($lingua)
		{
			LingueModel::getValori();
			
			if (isset(LingueModel::$valori[$lingua]))
				return $lingua;
		}
		
		return v("lingua_default_documenti");
	}
	
	public function checkExtAndMimeTypeFull($extractPath)
	{
		$AllowedExtensionsArray = explode(",", v("estensioni_accettate_documenti"));
		$AllowedMimeTypesArray = v("mime_type_accettati_documenti") ? explode(",", v("mime_type_accettati_documenti")) : array();
		
		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($extractPath, RecursiveDirectoryIterator::SKIP_DOTS),
			RecursiveIteratorIterator::CHILD_FIRST
		);

		foreach ($files as $fileinfo)
		{
			if ($fileinfo->isFile())
			{
				$realPath = $fileinfo->getRealPath();
				$ext = $fileinfo->getExtension();
				$mimeType = $this->files->getContentType($realPath);
				
				// Check extension and MIME type
				if (!in_array($ext,$AllowedExtensionsArray) || (count($AllowedMimeTypesArray) > 0 && !in_array($mimeType,$AllowedMimeTypesArray)))
					@unlink($realPath);
			}
		}
	}
	
	public function scDocumento($extractPath, $this_file, $copia = 0, $params = array())
	{
		$idPage = isset($params["id_page"]) ? $params["id_page"] : 0;
		$idArchivio = isset($params["id_archivio"]) ? $params["id_archivio"] : 0;
		$idImport = isset($params["id_import"]) ? $params["id_import"] : 0;
		$lingua = isset($params["lingua"]) ? $params["lingua"] : null;
		$idUser = isset($params["id_user"]) ? (int)$params["id_user"] : 0;
		
		$okElaborazione = true;
		
		if (@is_file($extractPath.$this_file))
		{
			$this->files->setBase($extractPath);
			
			$fileName = randomToken();
			
			$ext = $this->files->getFileExtension($this_file);
			
			$idTipoDoc = isset($params["id_tipo_doc"]) ? $params["id_tipo_doc"] : TipidocumentoestensioniModel::cercaTipoDocumentoDaEstensione($ext);
			
			$this->setValues(array(
				"filename"			=>	$fileName.".".$ext,
				"clean_filename"	=>	$this_file,
				"titolo"			=>	$this->files->getNameWithoutFileExtension($this_file),
				"data_documento"	=>	date("Y-m-d"),
				"id_tipo_doc"		=>	$idTipoDoc,
				"estensione"		=>	$ext,
				"content_type"		=>	$this->files->getContentType($extractPath.$this_file),
				"id_page"			=>	$idPage,
				"id_archivio"		=>	$idArchivio,
				"id_import"			=>	$idImport,
				"id_user"			=>	$idUser,
			));
			
			// Lingua
			if (!$lingua)
				$lingua = DocumentiModel::cercaLinguaDaNomeFile($this_file);
			
			$this->setValue("lingua", $lingua);
			
			DocumentiModel::$uploadFile = false;
			
			$function = $copia ? "copy" : "rename";

			if (call_user_func_array($function, array(
				$extractPath.$this_file,
				Domain::$parentRoot."/images/documenti/$fileName".".$ext"
			)))
			{
				if (!$this->insert())
					$okElaborazione = false;
			}
		}
		else
			$okElaborazione = false;
		
		return $okElaborazione;
	}
	
	//duplica gli elementi della pagina
	public function duplica($from_id, $to_id, $field = "id_page")
	{
		self::$uploadFile = false;
		
		$dl = new DocumentilingueModel();
		
		$clean["from_id"] = (int)$from_id;
		$clean["to_id"] = (int)$to_id;
		
		$res = $this->clear()->where(array("id_page"=>$clean["from_id"]))->orderBy($this->_idFields)->send(false);
		
		foreach ($res as $r)
		{
			$this->setValues($r, "sanitizeDb");
			$this->setValue("id_page", $to_id);
			
			unset($this->values[$this->_idFields]);
			
			if (isset($this->values["data_creazione"]))
				unset($this->values["data_creazione"]);
			
			if (isset($this->values["id_order"]))
				unset($this->values["id_order"]);
			
			if ($this->insert())
			{
				$dl->duplica($r["id_doc"], $this->lId, "id_doc");
			}
		}
	}
	
	// Elimina tutti i file che non corrispondono più al DB
	public function pulisciFile()
	{
		$this->files->setBase(Domain::$parentRoot."/".trim(self::$pathFiles,"/"),"/");
		$list = $this->clear()->select("filename")->toList("filename")->send();
		$list[] = "index.html";
		$list[] = ".htaccess";
		
		$this->files->removeFilesNotInTheList($list);
	}
	
	public static function getUrlAlias($id)
	{
		return "contenuti/documento/".(int)$id;
	}
	
	// Restituisce tutti i documenti assegnati manualmente in admin ad un utente
	public function getDocumentiRiservatiUtente($idUser, $idDoc = 0, $ritornaNumero = false)
	{
		if (!$idUser)
			$idUser = -1;
		
		$this->clear()->addJoinTraduzione()->where(array(
				"id_user"	=>	(int)$idUser,
				"archivio"	=>	0,
			))->orderBy("documenti.id_order");
		
		if ($idDoc)
			$this->aWhere(array(
				"id_doc"	=>	(int)$idDoc,
			));
		
		return $ritornaNumero ? $this->rowNumber() : $this->send();
	}
	
	// Restituisce tutti i documenti acquistati da un utente
	public function getDocumentiUtente($idUser, $soloAttivi = true, $idDoc = 0, $ritornaNumero = false)
	{
		if (!$idUser)
			$idUser = -1;
		
		$this->clear()->select("documenti.*,pages.*,orders.*")
			->inner(array("page"))
			->inner("righe")->on("righe.id_page = pages.id_page")
			->inner("orders")->on("orders.id_o = righe.id_o")
			->addJoinTraduzione()
			->addJoinTraduzione(null, "contenuti_tradotti_pagina", true, new PagesModel())
			->where(array(
				"orders.id_user"	=>	(int)$idUser,
			))
			->addWhereAttivoPermettiTest()
			->orderBy("orders.id_o desc,righe.id_r desc,documenti.id_order");
		
		if ($soloAttivi)
			$this->sWhere(OrdiniModel::getWhereClausePagato());
		
		if ($idDoc)
			$this->aWhere(array(
				"id_doc"	=>	(int)$idDoc,
			));
		
		return $ritornaNumero ? $this->rowNumber() : $this->send();
	}
	
	// Controlla che il documento $idDoc sia in qualche modo collegato all'utente $idUser, probabilmente con un ordine confermato
	public function checkAccessoUtente($idDoc, $idUser = 0)
	{
		if (!$idUser)
			$idUser = User::$id;
		
		$record = $this->selectId((int)$idDoc);

		// Controllo l'accesso alla categoria di appartenenza della pagina del documento
		if (v("attiva_accessibilita_categorie"))
		{
			$pModel = new PagesModel();
			
			if (!empty($record) && $record["id_page"] && !$pModel->check($record["id_page"]))
				return false;
		}
		
		// Controllo l'accesso ai documenti digitali
		$documento = $this->clear()->inner(array("page"))->where(array(
			"id_doc"					=>	(int)$idDoc,
			"pages.prodotto_digitale"	=>	1,
		))->first();
		
		if (!empty($documento))
			return $this->getDocumentiUtente($idUser, true, $documento["documenti"]["id_doc"], true);
		
		if ((int)$record["id_user"])
			return $this->getDocumentiRiservatiUtente((int)$idUser, (int)$idDoc, true);
		
		return true;
	}
	
	// Sposto in una cartella old tutti i file da eliminare
	// $delete: bool true|false -> true: elimina, false: sposta nella cartella trash
	public function spostaDocumentiNonUtilizzati($cartella = "documenti", $delete = false, $log = null)
	{
		$labelLog = "SPOSTATI";
		$labelLogAzione = "SPOSTAMENTO";
		
		if ($delete)
		{
			$labelLog = "ELIMINATI";
			$labelLogAzione = "ELIMINAZIONE";
		}
		
		if ($log)
			$log->writeString("INIZIO $labelLogAzione DOCUMENTI");
		
		$cartellaDocumenti = "images/$cartella";
		$cartellaTrash = "$cartellaDocumenti/trash";
		
		if (@!is_dir(Domain::$parentRoot.'/'.$cartellaDocumenti))
			return;
		
		createFolderFull("trash", Domain::$parentRoot.'/'.$cartellaDocumenti);
		
		$this->files->setBase(Domain::$parentRoot.'/'.$cartellaDocumenti);
		$this->files->listFiles();
		
		$files = $this->files->getFiles();
		
		$elencoDocumentiDb = $this->clear()->select("filename")->toList("filename")->send();
		
		$elencoDocumentiDb = array_unique($elencoDocumentiDb);
		// print_r($elencoDocumentiDb);
		
		$indiceSpostati = $indicePresenti = $indiceFileNeutri = 0;
		
		foreach ($files as $file)
		{
			if (!in_array($file, App::$fileNeutri))
			{
				if (!in_array($file, $elencoDocumentiDb))
				{
					$indiceSpostati++;
					
					if ($delete)
						@unlink(Domain::$parentRoot.'/'.$cartellaDocumenti."/$file");
					else
						rename(Domain::$parentRoot.'/'.$cartellaDocumenti."/$file", Domain::$parentRoot.'/'.$cartellaTrash."/$file");
					
					if ($log)
						$log->writeString("spostato documento $file");
				}
				else
					$indicePresenti++;
			}
			else
				$indiceFileNeutri++;
		}
		
		$filePresenti = 0;
		
		foreach ($elencoDocumentiDb as $file)
		{
			if (trim($file) && @is_file(Domain::$parentRoot.'/images/'.$cartella."/$file"))
				$filePresenti++;
		}
		
		if ($log)
		{
			$log->writeString("NUMERO DOCUMENTI IN DB:".count($elencoDocumentiDb));
			$log->writeString("NUMERO FILE IN CARTELLA:".(count($files) - $indiceFileNeutri));
			$log->writeString("NUMERO DOCUMENTI IN DB CON FILE PRESENTE:".$filePresenti);
			$log->writeString("NUMERO FILE $labelLog:".$indiceSpostati);
			$log->writeString("NUMERO FILE NON $labelLog:".$indicePresenti);
			$log->writeString("FINE $labelLogAzione DOCUMENTI");
		}
	}
	
	public function getStrutturaQueryDaocumentiDaLeggere()
	{
		$this->select("documenti.*,pages.title,categories.title,documenti_tradotti.titolo,pagine_tradotte.title,categorie_tradotte.title,tipi_documento.titolo,categories.id_c,pages.id_page")
			->inner("pages")->on("pages.id_page = documenti.id_page")
			->inner("categories")->on("categories.id_c = pages.id_c")
			->addJoinTraduzione(null, "documenti_tradotti", false)
			->addJoinTraduzione(null, "pagine_tradotte", false, (new PagesModel()))
			->addJoinTraduzione(null, "categorie_tradotte", false, (new CategoriesModel()))
			->left(array("tipo"))
			->sWhere("documenti.id_doc not in (select id_doc from regusers_notifiche where id_user = ".(int)User::$id.")")
			->aWhere(array(
				"pages.attivo"			=>	"Y",
				"documenti.visibile"	=>	1,
				"in"	=>	array(
					"categories.id_c"	=>	CategoriesModel::getIdCategorieAccessibili(),
				),
				"ne"	=>	array(
					"documenti.filename"	=>	"",
				),
			))
			->sWhere(array("DATE_FORMAT(documenti.data_file_upload, '%Y-%m-%d') >= ?",array(sanitizeDb(date("Y-m-d",User::$dettagli["creation_time"])))))
			->groupBy("documenti.id_doc");
		
		if (v("attiva_gruppi_documenti"))
			$this->addAccessoGruppiWhereClase();
		
		return $this;
	}
	
	public function categorieConDocumentiDaLeggere()
	{
		$notifiche = $this->getStrutturaQueryDaocumentiDaLeggere()->orderBy("categories.lft")->groupBy("categories.id_c")->send();
		
		$arrayCategorie = array();
		
		$c = new CategoriesModel();
		
		foreach ($notifiche as $n)
		{
			$depth = $c->clear()->depth($n["categories"]["id_c"]);
			$arrayCategorie[$n["categories"]["id_c"]] = str_repeat("-",$depth)." ".genericField($n, "title", "categories", "categorie_tradotte");
		}
		
		return $arrayCategorie;
	}
	
	public function pagineConDocumentiDaLeggere()
	{
		$notifiche = $this->getStrutturaQueryDaocumentiDaLeggere()->orderBy("pages.title")->groupBy("pages.id_page")->send();
		
		$arrayPagine = array();
		
		foreach ($notifiche as $n)
		{
			$arrayPagine[$n["pages"]["id_page"]] = genericField($n, "title", "pages", "pagine_tradotte");
		}
		
		return $arrayPagine;
	}
	
	public function documentiDaleggere()
	{
		return $this->getStrutturaQueryDaocumentiDaLeggere()->orderBy("data_file_upload desc");
	}
	
	public function segnaComeLetti()
	{
		$res = $this->send();
		
		$run = new RegusersnotificheModel();
		
		foreach ($res as $r)
		{
			$run->aggiungiDocumento((int)$r["documenti"]["id_doc"]);
		}
	}
}
