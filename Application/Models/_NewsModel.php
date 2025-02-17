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

class NewsModel extends GenericModel {

	public $parentRootFolder;
	
	public function __construct() {
		$this->_tables='news';
		$this->_idFields='id_n';

		$this->orderBy = 'id_order desc';
		$this->_idOrder = 'id_order';
		$this->_lang = 'It';

// 		$this->_popupItemNames = array(
// 			'attivo'	=>	'attivo',
// 		);
// 
// 		$this->_popupLabels = array(
// 			'attivo'	=>	'PUBBLICATO?',
// 		);
// 
// 		$this->_popupFunctions = array(
// 			'attivo'	=>	'getYesNo',
// 		);
		
// 		$this->_reference = array('album','id_c');
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'alias'	=>	array(
					'labelString'=>	'Alias per URL',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>L'URL della news (se lasciato vuoto viene creato in automatico)</div>"
					),
				),
// 				'immagine'		=>	array(
// 					'type'		=>	'File',
// 					'className'	=>	'form_file',
// 					'labelString'=>	'Immagine',
// // 					'deleteButton' =>	array('deleteFoto','elimina','delete_button'),
// 					'wrap'		=>	array(
// 						null,
// 						null,
// 						"<div class='thumb' data-field='immagine' data-field-path='images/news'>;;value;;</div>",
// 					),
// 				),
				'attivo'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Pubblicata?',
					'options'	=>	array('sì'=>'Y','no'=>'N'),
				),
				'keywords'		=>	array(
					'labelString'=>	'Parole chiave (separate dalla virgola)',
				),
				'meta_description'		=>	array(
					'type'		=>	'Textarea',
					'labelString'=>	'Descrizione in meta tag',
					'className'		=>	'form-control meta_textarea',
// 					'wrap'		=>	array(
// 						null,null,null,null,"</div></div>"
// 					),
				),
				
				'descrizione'		=>	array(
					'type'		=>	'Textarea',
					'labelString'=>	'Testo notizia',
					'className'		=>	'editor_textarea',
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		$this->uploadFields = array(
			"immagine"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/news",
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg,gif',
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
			"documento"	=>	array(
				"type"	=>	"file",
				"path"	=>	"images/documenti",
				"allowedExtensions"	=>	'pdf',
				"maxFileSize"	=>	3000000,
				"clean_field"	=>	"clean_documento",
				"Content-Disposition"	=>	"inline",
			),
		);
		
		parent::__construct();
		
	}
	
	public function update($id = NULL, $whereClause = NULL)
	{
		if ($this->upload("update"))
		{
			$this->alias($id);
			
			return parent::update($id, $whereClause);
		}
		
// 		$this->files->setBase(Domain::$parentRoot."/images/news");
// 		$list = array("index.html") + $this->clear()->select()->toList('immagine')->send();
// 		
// 		$this->files->removeFilesNotInTheList($list);
// 		
// 		$this->files->setBase(Domain::$parentRoot."/images/documenti");
// 		$list = array("index.html") + $this->clear()->select()->toList('documento')->send();
// 		
// 		$this->files->removeFilesNotInTheList($list);
	}
	
	public function insert()
	{
		if ($this->upload("insert"))
		{
			$this->alias();
			
			return parent::insert();
		}
	}
	
}
