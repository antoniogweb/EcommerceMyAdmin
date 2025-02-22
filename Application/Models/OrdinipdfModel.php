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

class OrdinipdfModel extends GenericModel
{
	public static $dataCreazione = null;
	
	public function __construct() {
		$this->_tables='orders_pdf';
		$this->_idFields='id_o_pdf';
		
		$this->_idOrder = 'id_order';
		
		self::$dataCreazione = date("Y-m-d H:i:s");
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'ordine' => array("BELONGS_TO", 'OrdiniModel', 'id_o',null,"CASCADE"),
        );
    }
    
    public function generaPdf($id, $salva = true, $invia = false)
    {
		createFolderFull("media/Pdf", LIBRARY);
		
		$oModel = new OrdiniModel();
		
		$ordine = $oModel->selectId((int)$id);
		
		if (empty($ordine))
			return [];
		
		$fileName = md5(randString(30).uniqid(mt_rand(),true)).".pdf";
		
		$titolo = "Ordine_".(int)$ordine["id_o"]."_".date("d_m_Y",strtotime($ordine["data_creazione"])).".pdf";

		if (function_exists(v("function_pdf_ordine")))
			$titolo = call_user_func_array(v("function_pdf_ordine"), array($id, $fileName));
		else
		{
			$strutturaProdotti = GestionaliModel::getModuloPadre()->infoOrdine((int)$id);
			
			ob_start();
			include(Domain::$adminRoot."/Application/Views/Ordinipdf/layout_pdf.sample.php");
			$content = ob_get_clean();
			
// 			Pdf::$params["margin_top"] = "40";
			Pdf::output("", LIBRARY . "/media/Pdf/" . $fileName, array(), "F", $content);
		}

		if ($salva)
			$this->query(array(
				"update orders_pdf set corrente = 0 where id_o = ?",
				array(
					(int)$ordine["id_o"]
				)
			));
		
		$values = array(
			"id_o"			=>	(int)$ordine["id_o"],
			"filename"	=>	$fileName,
			"titolo"	=>	$titolo,
			"corrente"	=>	1,
			"inviato"	=>	$invia ? 1 : 0,
			"data_creazione"	=>	self::$dataCreazione,
		);
		
		$this->sValues($values);
		
		if ($salva && $this->insert())
		{
			$values["id_o_pdf"] = $this->lId;
			
			return $values;
		}
		
		return $values;
    }
    
    // Crea e restituisce i valori della riga della tabella orders_pdf relativa al file PDF dell'ordine
    // $idPdf: se presente, cerca quel file senza crearlo
    public function generaORestituisciPdfOrdine($id = 0, $idPdf = 0)
    {
		$clean["idPdf"] = sanitizeAll((int)$idPdf);
		
		if ($clean["idPdf"])
		{
			return $this->clear()->where(array(
				"id_o_pdf"	=>	$clean["idPdf"],
			))->record();
		}
		else
			return $this->generaPdf((int)$id);
    }
    
    public function inviaPdf($id)
    {
		$oModel = new OrdiniModel();
		
		$ordine = $oModel->selectId((int)$id);
		
		if (empty($ordine))
			$this->responseCode(403);
		
		if ($ordine["email"] && checkMail(htmlentitydecode($ordine["email"])))
		{
			$values = $this->generaPdf($ordine["id_o"], true, true);
			
			$folder = LIBRARY . "/media/Pdf";
			
			if (is_array($values) && !empty($values) && isset($values["id_o_pdf"]) && $values["id_o_pdf"] && file_exists($folder."/".$values["filename"]))
			{
				$email = htmlentitydecode($ordine["email"]);
				
				$nomeFile = v("filename_pdf_ordine");
				
				$nomeFile = str_replace("[ID_ORDINE]",$ordine["id_o"], $nomeFile);
				$nomeFile = str_replace("[NUMERO_DOCUMENTO]",$ordine["numero_documento"], $nomeFile);
				
				return MailordiniModel::inviaMail(array(
					"emails"	=>	array($email),
					"oggetto"	=>	v("oggetto_pdf_ordine"),
					"id_o"		=>	(int)$ordine["id_o"],
					"numero_documento"		=>	(int)$ordine["numero_documento"],
					"tipologia"	=>	"ORDINE",
					"tipo"		=>	"G",
					"lingua"	=>	$ordine["lingua"],
					"testo_path"	=>	"Elementi/Mail/OrdiniOffline/mail_pdf_ordine.php",
					"tabella"	=>	"orders_pdf",
					"id_elemento"	=>	isset($values["id_o_pdf"]) ? (int)$values["id_o_pdf"] : 0,
					"array_variabili_tema"	=>	array(
						"NOME_CLIENTE"	=>	self::getNominativo($ordine),
					),
					"allegati"	=>	array(
						$nomeFile.".pdf" => $folder."/".$values["filename"],
					),
				));
			}
		}
		
		return false;
    }
    
    // Elimina tutti i file fisici dei PDF che non sono presenti nella tabella orders_pdf
    public function eliminaPdfNonInviati()
    {
// 		$this->files->setBase(LIBRARY."/media/Pdf");
// 		$list = $this->clear()->select("filename")->toList("filename")->send();
// 		$list[] = "index.html";
// 		$list[] = ".htaccess";
// 		
// 		$this->files->removeFilesNotInTheList($list);
    }
    
    public function linkPdfCrud($record)
    {
		return '<a target="_blank" title="'.gtext("Vedi PDF").'" href="'.Url::getRoot().$this->applicationUrl.$this->controller."/stampapdf/0/".$record["orders_pdf"]["id_o_pdf"].'"><i class="fa fa-file-pdf-o"></i></a>';
    }
    
    public function deletable($id)
	{
		return false;
	}
	
	public function inviatoCrud($record)
	{
		if ($record["orders_pdf"]["inviato"])
			return "<i class='verde fa fa-check'></i>";
		
		return "";
	}
}
