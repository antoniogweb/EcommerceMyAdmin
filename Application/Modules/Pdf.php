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

require_once(LIBRARY."/External/libs/vendor/autoload.php");

class Pdf
{
	public static $params = [
		'mode' => '',
		'format' => 'A4',
		'default_font_size' => "9",
		'default_font' => "",
		'margin_left' => "6",
		'margin_right' => "6",
		'margin_top' => "5",
		'margin_bottom' => "10",
		'margin_header' => "0",
		'margin_footer' => "2",
		'orientation'	=>	"P",
	];
	
	public static function output($templatePath = "", $title = "", $data = array(), $output = "I", $pdfContent = "")
	{
		if (class_exists("\Mpdf\Mpdf"))
		{
			if (!empty($data))
				extract($data);
			
			if ($templatePath)
			{
				ob_start();
				include($templatePath);
				$content = ob_get_clean();
			}
			else if ($pdfContent)
				$content = $pdfContent;
				
			$html2pdf = new \Mpdf\Mpdf(self::$params);
			
			$html2pdf->setDefaultFont('Arial');
			
			$html2pdf->WriteHTML($content);
			
			$title = $title ? $title : gtext("esportazione_pdf_").date("Y-m-d").".pdf";
			
			$html2pdf->Output($title,$output);
		}
	}
}
