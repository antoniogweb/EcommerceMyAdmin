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

require_once(LIBRARY."/External/libs/vendor/autoload.php");

class Xlsx
{
	public static function downloadFromHtml($html, $titolo)
	{
		return HtmlToXlsx::download($html, $titolo);
	}
	
	public static function getSheets($filePath)
	{
		$sheets = [];
		
		if (is_file($filePath))
		{
			$inputFileType = ucfirst(Files_Upload::sFileExtension(basename($filePath)));
			
			if ($inputFileType == "Xls" || $inputFileType == "Xlsx")
			{
				$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
				$sheets = $reader->listWorksheetNames($filePath);
			}
		}
		
		return $sheets;
	}
	
	public static function getData($filePath, $sheet = null, $rowNumber = null, $useLetters = false)
	{
		$arrayDati = [];
		
		if (is_file($filePath))
		{
			$inputFileType = ucfirst(Files_Upload::sFileExtension(basename($filePath)));
			
			if ($inputFileType == "Xls" || $inputFileType == "Xlsx")
			{
				$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
				$reader->setReadDataOnly(true);
				
				if ($rowNumber !== null)
				{
					$reader->setReadFilter(new class((int)$rowNumber + 1) implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter {
						private $rowNumber;
						
						public function __construct($rowNumber)
						{
							$this->rowNumber = $rowNumber;
						}
						
						public function readCell(string $columnAddress, int $row, string $worksheetName = ""): bool
						{
							return $row === $this->rowNumber;
						}
					});
				}
				
				$sheetName = null;
				
				if ($sheet !== null)
				{
					if (is_int($sheet))
					{
						$sheetNames = $reader->listWorksheetNames($filePath);
						$sheetName = isset($sheetNames[$sheet]) ? $sheetNames[$sheet] : null;
					}
					else
					{
						$sheetName = $sheet;
					}
					
					if ($sheetName !== null)
					{
						$reader->setLoadSheetsOnly([$sheetName]);
					}
				}
				
				$Spreadsheet = $reader->load($filePath);
				$worksheet = $sheetName !== null ? $Spreadsheet->getSheetByName($sheetName) : $Spreadsheet->getActiveSheet();
				
				$rowIterator = $rowNumber !== null ? $worksheet->getRowIterator((int)$rowNumber + 1, (int)$rowNumber + 1) : $worksheet->getRowIterator();
				
				foreach ($rowIterator as $row)
				{
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(FALSE);
					
					$temp = array();
					
					foreach ($cellIterator as $cell)
					{
						if ($useLetters)
						{
							$temp[$cell->getColumn()] = $cell->getValue();
						}
						else
						{
							$temp[] = $cell->getValue();
						}
					}
					
					$arrayDati[] = $temp;
				}
			}
		}
		
		return $arrayDati;
	}
}
