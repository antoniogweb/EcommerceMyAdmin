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

class DocumentiModel extends GenericModel {

	public $parentRootFolder;
	public static $uploadFile = true;
	
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
				"maxFileSize"	=>	3000000,
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
				"path"	=>	"images/documenti",
				"allowedExtensions"	=>	'pdf,png,jpg,jpeg',
				"maxFileSize"	=>	3000000,
				"clean_field"	=>	"clean_filename",
				"Content-Disposition"	=>	"inline",
			),
		);
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_doc', null, "CASCADE"),
			'page' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
			'tipo' => array("BELONGS_TO", 'TipidocumentoModel', 'id_tipo_doc',null,"CASCADE"),
			'gruppi' => array("MANY_TO_MANY", 'ReggroupsModel', 'id_group', array("ReggroupsdocumentiModel","id_doc","id_group"), "CASCADE"),
        );
    }
    
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'lingua'	=>	array(
					"type"	=>	"Select",
					"options"	=>	array("tutte" => "TUTTE") + $this->selectLingua(),
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
			),
			
			'enctype'	=>	'multipart/form-data',
		);
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
    
	public function update($id = NULL, $whereClause = NULL)
	{
		if ($this->upload("update"))
			return parent::update($id, $whereClause);
	}
	
	public function insert()
	{
		if (!self::$uploadFile || $this->upload("insert"))
			return parent::insert();
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
	
	public function elaboraArchivio($id, $idPage = 0)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && $record["archivio"] && !$record["elaborato"])
		{
			$zip = new ZipArchive;
			
			$filePath = Domain::$parentRoot."/images/documenti/" . $record["filename"];
			
			self::creaCartellaImages("images/tmp", true);
			
			$extractPath = Domain::$parentRoot."/images/tmp/";
			
			if (file_exists($filePath) && $zip->open($filePath) === TRUE) {
				$zip->extractTo($extractPath);
				$zip->close();
			}
			
			$items = scandir($extractPath);
			foreach( $items as $this_file ) {
				if( strcmp($this_file,".") !== 0 && strcmp($this_file,"..") !== 0 && strcmp($this_file,"index.html") !== 0 && strcmp($this_file,".htaccess") !== 0) {
					$this_file = basename($this_file);
					
					if (@is_file($extractPath.$this_file))
					{
						$this->files->setBase($extractPath);
						
						$fileName = md5(randString(22).microtime().uniqid(mt_rand(),true));
						
						$ext = $this->files->getFileExtension($this_file);
						
						$this->setValues(array(
							"filename"			=>	$fileName.".".$ext,
							"clean_filename"	=>	$this_file,
							"titolo"			=>	$this->files->getNameWithoutFileExtension($this_file),
							"data_documento"	=>	date("Y-m-d"),
							"id_tipo_doc"		=>	TipidocumentoestensioniModel::cercaTipoDocumentoDaEstensione($ext),
							"estensione"		=>	$ext,
							"content_type"		=>	$this->files->getContentType($extractPath.$this_file),
							"id_page"			=>	$idPage,
							"id_archivio"		=>	$id,
						));
						
						DocumentiModel::$uploadFile = false;
						
						if (rename($extractPath.$this_file, Domain::$parentRoot."/images/documenti/$fileName".".$ext"))
						{
							if ($this->insert())
							{
								$this->setValues(array(
									"elaborato"	=>	1
								));
								
								$this->pUpdate((int)$id);
								
								// Gestisci archivio
								if (v("elimina_archivio_dopo_upload"))
									@unlink($filePath);
							}
							
							@unlink($extractPath.$this_file);
						}
					}
				}
			}
		}
	}
	
}
