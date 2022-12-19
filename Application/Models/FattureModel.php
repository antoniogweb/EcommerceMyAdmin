<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
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

class FattureModel extends GenericModel {

	public $fattureOk = true;
	public $noticeHtml = null;
	public $year = "";
	public $campoTitolo = "numero";
	
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
	
	public function relations() {
        return array(
			'ordine' => array("BELONGS_TO", 'OrdiniModel', 'id_o',null,"CASCADE"),
        );
    }
	
	public function getAnnoDaDatetime($datetime)
	{
		return date("d-m-Y", strtotime($datetime));
	}
	
	public function getNumeroFattura()
	{
		return (int)$this->clear()->where(array("n!YEAR(data_fattura)"=>$this->year))->getMax("numero") + 1;
	}
	
	public function getUltimaFattura()
	{
		$clean["ultimoNumero"] = (int)$this->clear()->where(array("n!YEAR(data_fattura)"=>$this->year))->getMax("numero");
		
		if ($clean["ultimoNumero"] > 0)
		{
			$res = $this->clear()->where(array("n!YEAR(data_fattura)"=>$this->year,"numero"=>$clean["ultimoNumero"]))->send();
			
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
		
		$max = (int)$this->clear()->where(array("n!YEAR(data_fattura)"=>$this->year))->getMax("numero");
		
		//controllo che non ci siano buchi e che non ci siano fatture doppie
		$numeriFatture = $this->clear()->where(array("n!YEAR(data_fattura)"=>$this->year))->orderBy("id_f")->toList("numero")->send();
		
		$buchi = array();
		$doppie = array();
		$stessoOrdine = array();
		$fileMancanti = array();
		
		$numeri = array_count_values($numeriFatture);
		
		for ($i=1;$i<=$max;$i++)
		{
			if (!in_array($i, $numeriFatture))
			{
// 				$buchi[] = $i;
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
		$fatture = $this->clear()->where(array("n!YEAR(data_fattura)"=>$this->year))->orderBy("id_f")->send();
		$fattureRoot = $this->files->getBase();

		foreach ($fatture as $f)
		{
			
			if (!file_exists($fattureRoot . $f["fatture"]["filename"]))
			{
// 				$fileMancanti[] = $f["fatture"]["id_o"];
			}
			
		}
		
		//controllo che non ci siano due fatture relative allo stesso ordine
		$ordini = $this->clear()->where(array("n!YEAR(data_fattura)"=>$this->year))->orderBy("id_f")->toList("id_o")->send();
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
// 		$ordinaPerNumero = $this->clear()->where(array("n!YEAR(data_fattura)"=>$this->year))->orderBy("numero")->toList("filename")->send();
// 		$ordinaPerData = $this->clear()->where(array("n!YEAR(data_fattura)"=>$this->year))->orderBy("data_creazione")->toList("filename")->send();
// 		
// 		for ($i=0;$i<count($ordinaPerNumero);$i++)
// 		{
// 			if (strcmp($ordinaPerNumero[$i], $ordinaPerData[$i]) !== 0)
// 			{
// 				$htmlNotice .= "<div class='alert'>Attenzione ci sono dei problemi nelle date delle fatture!</div>";
// 				$this->fattureOk = false;
// 				break;
// 			}
// 		}
		
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
	
	public function insert()
	{
		$this->values["data_fattura"] = date("Y-m-d");
		
		return parent::insert();
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
	
	public function update($id = null, $where = null)
	{
		if (parent::update($id, $where))
		{
			$record = $this->selectId((int)$id);
			
			if (!empty($record))
				$this->crea($record["id_o"]);
			
			return true;
		}
		
		return false;
	}
	
	public function crea($id_o)
	{
		if (!$this->fattureOk)
			die();
		
// 		if (!file_exists(Domain::$adminRoot."/Application/Views/Fatture/layout_fattura.php"))
// 			die("ATTENZIONE MANCA IL FILE DI TEMPLATE DELLA FATTURA Application/Views/Fatture/layout_fattura.php, COPIARLO DA Application/Views/Fatture/layout_fattura.sample.php");
		
		$this->checkFolder();
		
		$clean["id_o"] = (int)$id_o;
		
		$o = new OrdiniModel();
		$righe = new RigheModel();
		
		$res = $o->clear()->where(array("id_o"=>$clean["id_o"]))->send();
		
		//echo Domain::$adminName;die();
		if (count($res) > 0)
		{
			$ordine = $res[0]["orders"];
			$numeroFattura = "";
			
			//controllo se esiste già la fattura relativa a quell'ordine
			$fatt = $this->clear()->where(array("id_o"=>$clean["id_o"]))->orderBy("id_f desc")->send();
			
			if (count($fatt) > 0)
			{
				$fattura = $fatt[0]["fatture"];
				$clean["fileName"] =  sanitizeAll($fattura["filename"]);
				
				if (file_exists(LIBRARY . "/media/Fatture/" . $clean["fileName"]))
					unlink(LIBRARY . "/media/Fatture/" . $clean["fileName"]);
				
				$dataFattura = smartDate($fattura["data_fattura"]);
				$numeroFattura = $clean["numeroFattura"] = (int)$fattura["numero"];
				$clean["fileName"] = sanitizeAll($numeroFattura."W_".$dataFattura.".pdf");
			}
			else
			{
				$numeroFattura = $clean["numeroFattura"] = (int)$this->getNumeroFattura();
				$dataFattura = date("d-m-Y");
				$clean["fileName"] = sanitizeAll($numeroFattura."W_".date("d-m-Y").".pdf");
			}
			
			$this->values = array(
				"id_o" => $clean["id_o"],
				"numero" => $clean["numeroFattura"],
				"filename" => $clean["fileName"],
			);
			
			if (count($fatt) === 0)
				$this->insert();
			else
				$this->pUpdate($fattura["id_f"]);
			
			$righeOrdine = $righe->clear()->where(array("id_o"=>$clean["id_o"]))->send();
			
			ob_start();
			if (file_exists(Domain::$adminRoot."/Application/Views/Fatture/layout_fattura.php"))
				include(Domain::$adminRoot."/Application/Views/Fatture/layout_fattura.php");
			else
				include(Domain::$adminRoot."/Application/Views/Fatture/layout_fattura.sample.php");
			$content = ob_get_clean();

			Pdf::$params["margin_top"] = "40";
			
			Pdf::output("", LIBRARY . "/media/Fatture/" . $clean["fileName"], array(), "F", $content);
		}
	}
}
