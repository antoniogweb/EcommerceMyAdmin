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

class FornitoriimportModel extends GenericModel
{
	public $campoTitolo = "id_fornitore_import";
	public $salvaDataModifica = true;
	public $salvaIdInserimentoModifica = true;
	
	public function __construct() {
		$this->_tables = 'fornitori_import';
		$this->_idFields = 'id_fornitore_import';
		
		$this->_idOrder='id_order';
		
		$this->uploadFields = array(
			"filename"	=>	array(
				"type"	=>	"file",
				"path"	=>	"admin/media/Import",
				"allowedExtensions"	=>	'xls,xlsx',
				"maxFileSize"	=>	1000000,
				"clean_field"	=>	"clean_filename",
				"Content-Disposition"	=>	"attachment",
			),
		);
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'fornitore' => array("BELONGS_TO", 'FornitoriModel', 'id_fornitore',null,"RESTRICT", "Si prega di selezionare il fornitore"),
		);
    }
    
    public static function getFolderPath()
	{
		return LIBRARY . "/media/Import";
	}
    
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'colonna_descrizione'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectColonne($id),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'colonna_codice_sku'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectColonne($id),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'colonna_codice_ean_gtin'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectColonne($id),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'colonna_codice_mpn_barcode'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectColonne($id),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'colonna_prezzo'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectColonne($id),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	public function selectColonne($id)
	{
		$fileName = $this->clear()->select("filename")->whereId((int)$id)->field("filename");
		
		$selectArray = array("" => "--");
		
		if ($fileName)
		{
			$filePath = self::getFolderPath()."/".$fileName;
			
			if (is_file($filePath))
			{
				$data = Xlsx::getData($filePath, null, 0, true);
				
				if (count($data) > 0)
				{
					foreach ($data[0] as $k => $v)
					{
						if ($v)
							$selectArray[$k] = $k." - ".$v;
					}
				}
			}
		}
		
		return $selectArray;
	}
	
	public function selectFogli($id)
	{
		$fileName = $this->clear()->select("filename")->whereId((int)$id)->field("filename");
		
		if ($fileName)
		{
			$filePath = LIBRARY . "/media/Import/$fileName";
			
			if (is_file($filePath))
			{
				$sheets = Xlsx::getSheets($filePath);
				
				if (count($sheets) > 0)
					return $sheets;
			}
		}
		
		return array(0 => gtext("Default"));
	}
	
	public function insert()
	{
		if ($this->upload("insert"))
			return parent::insert();
		
		return false;
	}
	
	public function update($id = NULL, $whereClause = NULL)
	{
		if ($this->upload("update"))
			return parent::update($id, $whereClause);
		
		return false;
	}
	
	public function filenameCrud($record)
    {
		return "<a class='iframe action_iframe' href='".Url::getRoot()."fornitoriimport/form/update/".$record["fornitori_import"]["id_fornitore_import"]."?partial=Y&nobuttons=N'>".$record["fornitori_import"]["clean_filename"]."</a>";
    }
    
    public function completo($id)
	{
		$record = $this->selectId((int)$id);
		
		if ($record)
		{
			if (!$record["elaborato"] && $record["filename"] && is_file(self::getFolderPath()."/".$record["filename"]) && $record["colonna_descrizione"] && $record["colonna_codice_sku"] && $record["colonna_codice_ean_gtin"] && $record["colonna_codice_mpn_barcode"] && $record["colonna_prezzo"])
				return true;
		}
		
		return false;
	}
	
	public function deletable($id)
	{
		if ($this->clear()->whereId((int)$id)->field("elaborato"))
			return false;
		
		return true;
	}
	
	public function manageable($id)
	{
		if ($this->clear()->whereId((int)$id)->field("elaborato"))
			return false;
		
		return true;
	}
	
	public function elaboratoCrud($record)
	{
		if ($record["fornitori_import"]["elaborato"])
			return "<i class='fa fa-check text text-success'></i>";
		else
			return "<i class='fa fa-ban text text-success'></i>";
	}
}
