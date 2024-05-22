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

class OrdinipdfModel extends GenericModel
{
	public function __construct() {
		$this->_tables='orders_pdf';
		$this->_idFields='id_o_pdf';
		
		$this->orderBy = 'orders.id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'ordine' => array("BELONGS_TO", 'OrdiniModel', 'id_o',null,"CASCADE"),
        );
    }
    
    public function generaPdf($id)
    {
		createFolderFull("media/Pdf", LIBRARY);
		
		$oModel = new OrdiniModel();
		
		$ordine = $oModel->selectId((int)$id);
		
		if (empty($ordine))
			return [];
		
		ob_start();
		if (file_exists(Domain::$adminRoot."/Application/Views/Ordinipdf/layout_pdf.php"))
			include(Domain::$adminRoot."/Application/Views/Ordinipdf/layout_pdf.php");
		else
			include(Domain::$adminRoot."/Application/Views/Ordinipdf/layout_pdf.sample.php");
		$content = ob_get_clean();
		
		$fileName = md5(randString(30).uniqid(mt_rand(),true)).".pdf";
		
		Pdf::$params["margin_top"] = "40";
		
		Pdf::output("", LIBRARY . "/media/Pdf/" . $fileName, array(), "F", $content);
		
		$this->query(array(
			"update orders_pdf set corrente = 0 where id_o = ?",
			array(
				(int)$ordine["id_o"]
			)
		));
		
		$values = array(
			"id_o"			=>	(int)$ordine["id_o"],
			"filename"	=>	$fileName,
			"titolo"	=>	"Ordine_".(int)$ordine["id_o"]."_".date("d_m_Y",strtotime($ordine["data_creazione"])).".pdf",
			"corrente"	=>	1,
		);
		
		$this->sValues($values);
		
		if ($this->insert())
		{
			$values["id_o_pdf"] = $this->lId;
			
			return $values;
		}
		
		return [];
    }
    
    // Crea e restituisce i valori della riga della tabella orders_pdf relativa al file PDF dell'ordine
    // $filename: se presente, cerca quel file senza crearlo
    public function generaORestituisciPdfOrdine($id = 0, $filename = "")
    {
		$clean["filename"] = sanitizeAll((string)basename($filename));
		
		if ((string)$filename)
		{
			return $this->clear()->where(array(
				"filename"	=>	$clean["filename"],
			))->record();
		}
		else
			return $this->generaPdf((int)$id);
    }
    
    public function inviaPdf($id)
    {
		$oModel = new OrdiniModel();
		$oPdfModel = new OrdinipdfModel();
		
		$ordine = $oModel->selectId((int)$id);
		
		if (empty($ordine))
			$this->responseCode(403);
		
		if ($ordine["email"] && checkMail(htmlentitydecode($ordine["email"])))
		{
			$values = $oPdfModel->generaPdf($ordine["id_o"]);
			
			$folder = LIBRARY . "/media/Pdf";
			
			if (is_array($values) && !empty($values) && isset($values["id_o_pdf"]) && $values["id_o_pdf"] && file_exists($folder."/".$values["filename"]))
			{
				$email = htmlentitydecode($ordine["email"]);
				
				$nomeFile = "Ordine_".$ordine["id_o"];
				
				return MailordiniModel::inviaMail(array(
					"emails"	=>	array($email),
					"oggetto"	=>	"Ordine ".$ordine["id_o"]. " - stampa PDF",
					"id_o"		=>	(int)$ordine["id_o"],
					"tipologia"	=>	"ORDINE",
					"tipo"		=>	"G",
					"lingua"	=>	$ordine["lingua"],
					"testo_path"	=>	"Elementi/Mail/OrdiniOffline/mail_pdf_ordine.php",
					"tabella"	=>	"orders_pdf",
					"id_elemento"	=>	(int)$values["id_o_pdf"],
					"array_variabili_tema"	=>	array(
						
					),
					"allegati"	=>	array(
						$nomeFile.".pdf" => $folder."/".$values["filename"],
					),
				));
			}
		}
		
		return false;
    }
}
