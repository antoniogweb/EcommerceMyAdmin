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

class FattureModel extends Model_Tree {

	public $fattureOk = true;
	public $noticeHtml = null;
	public $year = "";
	
	public function __construct() {
		$this->_tables='fatture';
		$this->_idFields='id_f';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'fatture.id_order';
		$this->_lang = 'It';
		
		parent::__construct();
		
		$this->year = date("Y");
		
		$this->files->setBase(LIBRARY . "/../" . rtrim("/".Parametri::$cartellaFatture));
// 		echo LIBRARY . "/.." . rtrim("/".Parametri::$cartellaFatture);die();
		if (v("check_fatture"))
			$this->checkFatture();
	}

	public function getNumeroFattura()
	{
		return (int)$this->clear()->where(array("n!YEAR(data_creazione)"=>$this->year))->getMax("numero") + 1;
	}
	
	public function getUltimaFattura()
	{
		$clean["ultimoNumero"] = (int)$this->clear()->where(array("n!YEAR(data_creazione)"=>$this->year))->getMax("numero");
		
		if ($clean["ultimoNumero"] > 0)
		{
			$res = $this->clear()->where(array("n!YEAR(data_creazione)"=>$this->year,"numero"=>$clean["ultimoNumero"]))->send();
			
			if (count($res) > 0)
			{
				return $res[0]["fatture"]["id_f"];
			}
		}
		
		return 0;
	}
	
	public function checkFolder()
	{
		F::createFolder("media/Fatture");
	}
	
	public function checkFatture()
	{
		if (!v("check_fatture"))
			return;
		
		F::createFolder("media/Fatture");
		
		$max = (int)$this->clear()->where(array("n!YEAR(data_creazione)"=>$this->year))->getMax("numero");
		
		//controllo che non ci siano buchi e che non ci siano fatture doppie
		$numeriFatture = $this->clear()->where(array("n!YEAR(data_creazione)"=>$this->year))->orderBy("id_f")->toList("numero")->send();
		
		$buchi = array();
		$doppie = array();
		$stessoOrdine = array();
		$fileMancanti = array();
		
		$numeri = array_count_values($numeriFatture);
		
		for ($i=1;$i<=$max;$i++)
		{
			if (!in_array($i, $numeriFatture))
			{
				$buchi[] = $i;
			}
			
			if (in_array($i, $numeriFatture))
			{
				if ($numeri[$i] > 1)
				{
					$doppie[] = $i;
				}
			}
		}
		
		//controllo che non ci siano file mancanti
		$fatture = $this->clear()->where(array("n!YEAR(data_creazione)"=>$this->year))->orderBy("id_f")->send();
		$fattureRoot = $this->files->getBase();

		foreach ($fatture as $f)
		{
			
			if (!file_exists($fattureRoot . $f["fatture"]["filename"]))
			{
				$fileMancanti[] = $f["fatture"]["id_o"];
			}
			
		}
		
		//controllo che non ci siano due fatture relative allo stesso ordine
		$ordini = $this->clear()->where(array("n!YEAR(data_creazione)"=>$this->year))->orderBy("id_f")->toList("id_o")->send();
		$numeriOrdini = array_count_values($ordini);
		foreach ($numeriOrdini as $no => $nv)
		{
			if ($nv > 1)
			{
				$stessoOrdine[] = $no;
			}
		}
		
		$htmlNotice = "";
		
		//controllo che le date abbiano lo stesso ordine dei numeri di fattura
		$ordinaPerNumero = $this->clear()->where(array("n!YEAR(data_creazione)"=>$this->year))->orderBy("numero")->toList("filename")->send();
		$ordinaPerData = $this->clear()->where(array("n!YEAR(data_creazione)"=>$this->year))->orderBy("data_creazione")->toList("filename")->send();
		
		for ($i=0;$i<count($ordinaPerNumero);$i++)
		{
			if (strcmp($ordinaPerNumero[$i], $ordinaPerData[$i]) !== 0)
			{
				$htmlNotice .= "<div class='alert'>Attenzione ci sono dei problemi nelle date delle fatture!</div>";
				$this->fattureOk = false;
				break;
			}
		}
		
		if (count($fileMancanti) > 0)
		{
			$htmlNotice .= "<div class='alert'>Attenzione mancano i file delle fatture dei seguenti ordini: <i>ordine#".implode(" ordine#", $fileMancanti)."</i>.<br />Si prega di rigenerare i file seguendo i link sottostanti:<ul>";
			
			foreach ($fileMancanti as $fm)
			{
				$htmlNotice .= "<li><a href='http://".DOMAIN_NAME."/fatture/crea/$fm'>Rigenera il file della fattura dell' ordine #$fm</a></li>";
			}
			
			$htmlNotice .= "</ul></div>";
		}
		
		if (count($stessoOrdine) > 0)
		{
			$htmlNotice .= "<div class='alert'>Attenzione i seguenti ordini hanno più di una fattura: <i>ordine#".implode(" ordine#", $stessoOrdine)."</i><br />Si prega di contattare l'amministratore del gestionale.</div>";
		}
		if (count($buchi) > 0)
		{
			$htmlNotice .= "<div class='alert'>Attenzione mancano le seguenti fatture: <i>fattura#".implode(" fattura#", $buchi)."</i><br />Si prega di contattare l'amministratore del gestionale.</div>";
		}
		if (count($doppie) > 0)
		{
			$htmlNotice .= "<div class='alert'>Attenzione le seguenti fatture sono doppie: <i>fattura#".implode(" fattura#", $doppie)."</i><br />Si prega di contattare l'amministratore del gestionale.</div>";
		}
		
		$this->noticeHtml = $htmlNotice;
		
		if (count($buchi) > 0 or count($doppie) > 0 or count($stessoOrdine) > 0)
		{
			$this->fattureOk = false;
		}
	}
	
	public function checkFiles()
	{
		$this->checkFolder();
		
// 		$list = $this->clear()->select()->toList('filename')->send();
// 		$this->files->removeFilesNotInTheList($list);
	}
	
	public function del($id = null, $whereClause = null)
	{
		$clean["id"] = (int)$id;
		$ultimaFattura = (int)$this->getUltimaFattura();
		
// 		echo $ultimaFattura;
// 		die();
		
		if ($clean["id"] === $ultimaFattura)
		{
			return parent::del($clean["id"], $whereClause);
		}
		else
		{
			$this->notice = "<div class='alert'>Non è possibile cancellare questa fattura perché non è l'ultima</div>";
		}
		return false;
	}
	
	public function crea($id_o)
	{
		if (!$this->fattureOk)
			die();
		
		if (!file_exists(Domain::$adminRoot."/Application/Views/Fatture/layout_fattura.php"))
			die("ATTENZIONE MANCA IL FILE DI TEMPLATE DELLA FATTURA Application/Views/Fatture/layout_fattura.php, COPIARLO DA Application/Views/Fatture/layout_fattura.sample.php");
		
		$this->checkFolder();
		
		$clean["id_o"] = (int)$id_o;
		
		$o = new OrdiniModel();
		$righe = new RigheModel();
		
		$res = $o->clear()->where(array("id_o"=>$clean["id_o"]))->send();
		
		//echo Domain::$adminName;die();
		if (count($res) > 0)
		{
			require_once(Domain::$adminRoot."/External/html2pdf_v4.03/html2pdf.class.php");
			
			$ordine = $res[0]["orders"];
			$numeroFattura = "";
			
			//controllo se esiste già la fattura relativa a quell'ordine
			$fatt = $this->clear()->where(array("id_o"=>$clean["id_o"]))->orderBy("id_f desc")->send();
			
			try
			{
				if (count($fatt) > 0)
				{
					$fattura = $fatt[0]["fatture"];
					$clean["fileName"] =  sanitizeAll($fattura["filename"]);
					$dataFattura = smartDate($fattura["data_creazione"]);
					$numeroFattura = $clean["numeroFattura"] = (int)$fattura["numero"];
				}
				else
				{
					$numeroFattura = $clean["numeroFattura"] = (int)$this->getNumeroFattura();
					$dataFattura = date("d-m-Y");
					$clean["fileName"] = sanitizeAll($numeroFattura."W_".date("d-m-Y").".pdf");
				}
				
				if (count($fatt) > 0)
				{
// 					$fileName = 
				}
				else
				{
					$this->values = array(
						"id_o" => $clean["id_o"],
						"numero" => $clean["numeroFattura"],
						"filename" => $clean["fileName"],
					);
					$this->insert();
// 					$fileName = $ordine["id_o"]."W_".smartDate($ordine["data_creazione"]);
				}
				
				$righeOrdine = $righe->clear()->where(array("id_o"=>$clean["id_o"]))->send();
				
				ob_start();
				include(Domain::$adminRoot."/Application/Views/Fatture/layout_fattura.php");
				$content = ob_get_clean();
				
// 				echo $content;die();
// 				echo LIBRARY . "/media/Fatture/" . $clean["fileName"];die();

				Pdf::$params["margin_top"] = "40";
				
				Pdf::output("", LIBRARY . "/media/Fatture/" . $clean["fileName"], array(), "F", $content);
				
// 				$html2pdf = new HTML2PDF('P','A4','it', true, 'ISO-8859-15', array("0mm", "0mm", "0mm", "0mm"));
// 				
// // 				$html2pdf = new HTML2PDF('P', 'A4', 'it',true);
// 		//      $html2pdf->setModeDebug();
// 				$html2pdf->setDefaultFont('Helvetica');
// 				$html2pdf->writeHTML($content);
// 				
// 				$html2pdf->Output(LIBRARY . "/.." . rtrim("/".Parametri::$cartellaFatture) . "/" . $clean["fileName"],'F');
				
// 				$this->checkFiles();
				//$this->redirect("ordini/vedi/".$ordine["id_o"]."/".$ordine["admin_token"]."?n=y");
			}
			catch(HTML2PDF_exception $e) {
				echo $e;
				exit;
			}

		}

	}
}
