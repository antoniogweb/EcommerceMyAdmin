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

class FattureController extends BaseController {
	
	public $sezionePannello = "ecommerce";
	
	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);

		$this->model();

		$this->setArgKeys(array('page:forceInt'=>1,'id_f:sanitizeAll'=>'tutti','id_o:sanitizeAll'=>'tutti','token:sanitizeAll'=>'token'));

		$this->model("OrdiniModel");
		$this->model("RigheModel");
		
		$this->s['admin']->check();
		
		$data["sezionePannello"] = "ecommerce";
		
		$this->append($data);
	}

	public function main()
	{
		$this->shift();

		Params::$nullQueryValue = 'tutti';
		
		if (isset($_GET["delete"]))
		{
			$clean["id_f"] = $this->request->get("delete","0","forceInt");
			
			$this->m["FattureModel"]->del($clean["id_f"]);
		}
		
		$data["ultimaFattura"] = (int)$this->m["FattureModel"]->clear()->where(array("n!YEAR(data_creazione)"=>date("Y")))->getMax("numero");
		$data["idUltimaFattura"] = (int)$this->m["FattureModel"]->getUltimaFattura();
		
		$this->loadScaffold('main',array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>20, 'mainMenu'=>''));
		
		$tableFields = array(
			"<a href='".$this->baseUrl."/fatture/vedi/;orders.id_o;'>Fattura #;fatture.numero;</a>",
			"<a href='".$this->baseUrl."/ordini/vedi/;orders.id_o;/;orders.admin_token;'>Ordine #;orders.id_o;</a>",
			"aggregate.anno_fattura",
			"smartDate|fatture.data_creazione",
			"€ ;orders.total;",
		);
		
		$this->scaffold->loadMain($tableFields,'fatture:id_f','');
		
		$this->scaffold->setHead('FATTURA,ORDINE,ANNO FATTURA,DATA FATTURA,TOTALE');
		
		$this->scaffold->fields = "fatture.*,orders.*,year(fatture.data_creazione) as anno_fattura";
		$this->scaffold->model->clear()->inner("orders")->using("id_o")->orderBy("anno_fattura desc,fatture.numero desc");
		
		$where = array(
			'numero'	=>	$this->viewArgs['id_f'],
			'id_o'	=>	$this->viewArgs['id_o'],
		);
		
		$this->scaffold->model->where($where);
		
		$this->scaffold->itemList->setFilters(array('id_f','id_o'));
		
		$data['scaffold'] = $this->scaffold->render();
// 		echo $this->scaffold->model->getQuery();
		
		$data['menu'] = $this->scaffold->html['menu'];
		$data['popup'] = $this->scaffold->html['popup'];
		$data['main'] = $this->scaffold->html['main'];
		$data['pageList'] = $this->scaffold->html['pageList'];
		
		$data['notice'] = $this->scaffold->model->notice;
		
		$data["tabella"] = "fatture";
		
		$this->append($data);
		$this->load('main');
		
		$this->m["FattureModel"]->checkFiles();
	}
	
	public function vedi($id_o)
	{
		$this->clean();
		
		$clean["id_o"] = (int)$id_o;
		
		//controllo se esiste già la fattura relativa a quell'ordine
		$res = $this->m["FattureModel"]->clear()->where(array("id_o"=>$clean["id_o"]))->orderBy("id_f desc")->send();
		
		if (count($res) > 0)
		{
			header('Content-disposition: attachment; filename='.$res[0]['fatture']['filename']);
			header('Content-Type: application/pdf');
			readfile(LIBRARY . "/.." . rtrim("/".Parametri::$cartellaFatture) . "/" . $res[0]['fatture']['filename']);
		}
		else
		{
			$this->redirect("ordini/main");
		}
	}
	
	public function crea($id_o)
	{
		if (!$this->m["FattureModel"]->fattureOk)
		{
			$this->redirect("panel/main");
			die();
		}
		
		$this->clean();
		
		$this->shift();

		$clean["id_o"] = (int)$id_o;
		
		$this->m["FattureModel"]->crea($clean["id_o"]);
		
		$res = $this->m["OrdiniModel"]->clear()->where(array("id_o"=>$clean["id_o"]))->send();
		
		if (count($res) > 0)
		{
			$ordine = $res[0]["orders"];
			
			$this->redirect("ordini/vedi/".$ordine["id_o"]."?n=y");
		}

	}
	
// 	public function crea($id_o)
// 	{
// 		if (!$this->m["FattureModel"]->fattureOk)
// 		{
// 			$this->redirect("panel/main");
// 			die();
// 		}
// 		
// 		$this->clean();
// 		
// 		$this->shift();
// 		
// 		$clean["id_o"] = (int)$id_o;
// 		
// 		$res = $this->m["OrdiniModel"]->clear()->where(array("id_o"=>$clean["id_o"]))->send();
// 		
// 		if (count($res) > 0)
// 		{
// 			require_once(ROOT."/External/html2pdf_v4.03/html2pdf.class.php");
// 			
// 			$ordine = $res[0]["orders"];
// 			$numeroFattura = "";
// 			
// 			//controllo se esiste già la fattura relativa a quell'ordine
// 			$fatt = $this->m["FattureModel"]->clear()->where(array("id_o"=>$clean["id_o"]))->orderBy("id_f desc")->send();
// 			
// 			try
// 			{
// 				if (count($fatt) > 0)
// 				{
// 					$fattura = $fatt[0]["fatture"];
// 					$clean["fileName"] =  sanitizeAll($fattura["filename"]);
// 					$dataFattura = smartDate($fattura["data_creazione"]);
// 					$numeroFattura = $clean["numeroFattura"] = (int)$fattura["numero"];
// 				}
// 				else
// 				{
// 					$numeroFattura = $clean["numeroFattura"] = (int)$this->m["FattureModel"]->getNumeroFattura();
// 					$dataFattura = date("d-m-Y");
// 					$clean["fileName"] = sanitizeAll($numeroFattura."W_".date("d-m-Y").".pdf");
// 				}
// 				
// 				if (count($fatt) > 0)
// 				{
// // 					$fileName = 
// 				}
// 				else
// 				{
// 					$this->m["FattureModel"]->values = array(
// 						"id_o" => $clean["id_o"],
// 						"numero" => $clean["numeroFattura"],
// 						"filename" => $clean["fileName"],
// 					);
// 					$this->m["FattureModel"]->insert();
// // 					$fileName = $ordine["id_o"]."W_".smartDate($ordine["data_creazione"]);
// 				}
// 				
// 				$righeOrdine = $this->m["RigheModel"]->clear()->where(array("id_o"=>$clean["id_o"]))->send();
// 				
// 				ob_start();
// 				include(ROOT."/Application/Views/Fatture/layout_fattura.php");
// 				$content = ob_get_clean();
// 			
// 				$html2pdf = new HTML2PDF('P','A4','it', true, 'ISO-8859-15', array("0mm", "0mm", "0mm", "0mm"));
// 				
// // 				$html2pdf = new HTML2PDF('P', 'A4', 'it',true);
// 		//      $html2pdf->setModeDebug();
// 				$html2pdf->setDefaultFont('Arial');
// 				$html2pdf->writeHTML($content);
// 				
// 				$html2pdf->Output(Domain::$parentRoot . rtrim("/".Parametri::$cartellaFatture) . "/" . $clean["fileName"],'F');
// 				
// 				$this->m["FattureModel"]->checkFiles();
// 				$this->redirect("ordini/vedi/".$ordine["id_o"]);
// 			}
// 			catch(HTML2PDF_exception $e) {
// 				echo $e;
// 				exit;
// 			}
// 
// 		}
// 
// 	}
}
