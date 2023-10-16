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

class Excel extends Template
{
	public function salva($fileInput, $fileOutput, $placeholders = array())
	{
		if (file_exists($fileInput))
		{
			require_once(LIBRARY . '/External/libs/vendor/autoload.php');
			
			$ext = Files_Upload::sFileExtension($fileInput);
			
			if ($ext != "xls" && $ext != "xlsx")
				return false;
			
			if ($ext == "xls")
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
			else
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			
			$variabili = array_keys($placeholders);
			$variabili = array_map(function ($variabile) {
					return '${'.$variabile.'}';
				}, $variabili);
			
// 			print_r($variabili);
			
			$Spreadsheet = $reader->load($fileInput);
			$worksheet = $Spreadsheet->getActiveSheet();
			
			foreach ($worksheet->getRowIterator() as $row)
			{
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(FALSE);
				
				foreach ($cellIterator as $cell)
				{
					$value = trim(nullToBlank($cell->getValue()));
					
					if (in_array($value, $variabili))
					{
						$cellName = $cell->getCoordinate();
						
						$value = str_replace('${', "", $value);
						$value = str_replace('}', "", $value);
						
						$Spreadsheet->getActiveSheet()->setCellValue($cellName, $placeholders[$value]);
					}
				}
			}
			
			$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($Spreadsheet, 'Xls');
			$writer->save($fileOutput);
			
			return true;
		}
		
		return false;
	}
}
