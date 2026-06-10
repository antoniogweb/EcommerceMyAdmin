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

class OrdiniacquistopdfModel extends GenericModel
{
	public static $dataCreazione = null;
	
	public function __construct() {
		$this->_tables='ordini_acquisto_pdf';
		$this->_idFields='id_ordine_acquisto_pdf';
		
		$this->_idOrder = 'id_order';
		
		self::$dataCreazione = date("Y-m-d H:i:s");
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'ordine' => array("BELONGS_TO", 'OrdiniacquistoModel', 'id_ordine_acquisto',null,"CASCADE"),
        );
    }
    
    public static function getMediaPath()
	{
		return "media/PdfAcquisto/";
	}
    
    public function generaPdf($id, $salva = true, $invia = false)
    {
		createFolderFull(self::getMediaPath(), LIBRARY);
		
		$oModel = new OrdiniacquistoModel();
		
		$ordine = $oModel->selectId((int)$id);
		
		if (empty($ordine))
			return [];
		
		$fileName = randomToken().".pdf";
		
		$titolo = "Ordine_".(int)$ordine["id_ordine_acquisto"]."_".date("d_m_Y",strtotime($ordine["data_creazione"])).".pdf";
		
		if (function_exists(v("function_pdf_ordine_acquisto")))
			$titolo = call_user_func_array(v("function_pdf_ordine_acquisto"), array($id, $fileName));
		else
		{
			// $strutturaProdotti = GestionaliModel::getModuloPadre()->infoOrdine((int)$id);
			
			ob_start();
			include(Domain::$adminRoot."/Application/Views/Ordiniacquistopdf/layout_pdf.sample.php");
			$content = ob_get_clean();
			
// 			Pdf::$params["margin_top"] = "40";
			Pdf::output("", LIBRARY . "/".self::getMediaPath()."/" . $fileName, array(), "F", $content);
		}

		if ($salva)
			$this->query(array(
				"update ordini_acquisto_pdf set corrente = 0 where id_ordine_acquisto = ?",
				array(
					(int)$ordine["id_ordine_acquisto"]
				)
			));
		
		$values = array(
			"id_ordine_acquisto"			=>	(int)$ordine["id_ordine_acquisto"],
			"filename"	=>	$fileName,
			"titolo"	=>	$titolo,
			"corrente"	=>	1,
			"inviato"	=>	$invia ? 1 : 0,
			"data_creazione"	=>	self::$dataCreazione,
		);
		
		$this->sValues($values);
		
		if ($salva && $this->insert())
		{
			$values["id_ordine_acquisto_pdf"] = $this->lId;
			
			return $values;
		}
		
		return $values;
    }
    
    // Crea e restituisce i valori della riga della tabella ordini_acquisto_pdf relativa al file PDF dell'ordine
    // $idPdf: se presente, cerca quel file senza crearlo
    public function generaORestituisciPdfOrdine($id = 0, $idPdf = 0)
    {
		$clean["idPdf"] = sanitizeAll((int)$idPdf);
		
		if ($clean["idPdf"])
		{
			return $this->clear()->where(array(
				"id_ordine_acquisto_pdf"	=>	$clean["idPdf"],
			))->record();
		}
		else
			return $this->generaPdf((int)$id);
    }
    
    public function inviaPdf($id)
    {
		$oModel = new OrdiniacquistoModel();
		
		$ordine = $oModel->selectId((int)$id);
		
		if (empty($ordine))
			$this->responseCode(403);
		
		if ($ordine["email_amministrativa"] && checkMail(htmlentitydecode($ordine["email_amministrativa"])))
		{
			$values = $this->generaPdf($ordine["id_ordine_acquisto"], true, true);
			
			$folder = LIBRARY . "/".self::getMediaPath();
			
			if (is_array($values) && !empty($values) && isset($values["id_ordine_acquisto_pdf"]) && $values["id_ordine_acquisto_pdf"] && is_file($folder."/".$values["filename"]))
			{
				$email_amministrativa = htmlentitydecode($ordine["email_amministrativa"]);
				
				$nomeFile = v("filename_pdf_ordine_acquisto");
				
				$nomeFile = str_replace("[ID_ORDINE]",$ordine["numero_ordine"], $nomeFile);
				
				return MailordiniModel::inviaMail(array(
					"emails"	=>	array($email_amministrativa),
					"oggetto"	=>	v("oggetto_pdf_ordine"),
					"numero_documento"		=>	(int)$ordine["numero_ordine"],
					"tipologia"	=>	"ORDINE_ACQUISTO",
					"lingua"	=>	v("default_backend_language"),
					"testo_path"	=>	"Elementi/Mail/OrdiniOffline/mail_pdf_ordine_acquisto.php",
					"tabella"	=>	"ordini_acquisto_pdf",
					"id_elemento"	=>	isset($values["id_ordine_acquisto_pdf"]) ? (int)$values["id_ordine_acquisto_pdf"] : 0,
					"array_variabili_tema"	=>	array(
						"NOME_FORNITORE"	=>	$ordine["ragione_sociale"],
					),
					"allegati"	=>	array(
						$nomeFile.".pdf" => $folder."/".$values["filename"],
					),
				));
			}
		}
		
		return false;
    }
    
    // Elimina tutti i file fisici dei PDF che non sono presenti nella tabella ordini_acquisto_pdf
    public function eliminaPdfNonInviati()
    {
// 		$this->files->setBase(LIBRARY."/".self::getMediaPath());
// 		$list = $this->clear()->select("filename")->toList("filename")->send();
// 		$list[] = "index.html";
// 		$list[] = ".htaccess";
// 		
// 		$this->files->removeFilesNotInTheList($list);
    }
    
    public function linkPdfCrud($record)
    {
		return '<a target="_blank" title="'.gtext("Vedi PDF").'" href="'.Url::getRoot().$this->applicationUrl.$this->controller."/stampapdf/0/".$record["ordini_acquisto_pdf"]["id_ordine_acquisto_pdf"].'"><i class="fa fa-file-pdf-o"></i></a>';
    }
    
    public function deletable($id)
	{
		return false;
	}
	
	public function inviatoCrud($record)
	{
		if ($record["ordini_acquisto_pdf"]["inviato"])
			return "<i class='verde fa fa-check'></i>";
		
		return "";
	}
}
