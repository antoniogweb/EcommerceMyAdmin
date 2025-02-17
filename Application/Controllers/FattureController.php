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

Helper_List::$filtersFormLayout["filters"]["id_fattura"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"Numero fattura ..",
	),
);

Helper_List::$filtersFormLayout["filters"]["id_o"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"Numero ordine ..",
	),
);

class FattureController extends BaseController {
	
	public $sezionePannello = "ecommerce";
	
	public $argKeys = array(
		'page:forceInt'		=>	1,
		'id_fattura:sanitizeAll'	=>	'tutti',
		'id_o:sanitizeAll'	=>	'tutti',
		'token:sanitizeAll'	=>	'token',
	);
	
	public function __construct($model, $controller, $queryString, $application, $action)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("fatture_attive"))
			die("Modulo non attivo");
		
		$this->model("OrdiniModel");
		$this->model("RigheModel");
		
		$this->s['admin']->check();
		
		$data["sezionePannello"] = "ecommerce";
		
		$this->append($data);
	}
	
	public function main()
	{
		$this->shift();
		
		$this->bulkQueryActions = "";
		$this->mainButtons = "ldel,ledit";
		$this->addBulkActions = false;
		
		$this->colProperties = array();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>'');
		
		$this->mainFields = array(
			"<a href='".$this->baseUrl."/fatture/vedi/;orders.id_o;'>Fattura #;fatture.numero;</a>",
			"<a href='".$this->baseUrl."/ordini/vedi/;orders.id_o;/;orders.admin_token;'>Ordine #;orders.id_o;</a>",
			"aggregate.anno_fattura",
			"smartDate|fatture.data_fattura",
			'OrdiniModel.getNome|orders.id_o',
			'orders.email',
			'orders.tipo_cliente',
			"€ ;orders.total;",
		);
		$this->mainHead = "Fattura,Ordine,Anno fattura,Data fattura,Nome/Rag.Soc,Email,Tipo,Totale";
		
		$this->m[$this->modelName]->clear()->select("fatture.*,orders.*,year(fatture.data_fattura) as anno_fattura")
			->inner(array("ordine"))
			->where(array(
				'numero'	=>	$this->viewArgs['id_fattura'],
				'id_o'	=>	$this->viewArgs['id_o'],
			))
			->orderBy("year(fatture.data_fattura) desc,fatture.numero desc")
			->convert()
			->save();
		
		$this->filters = array('id_fattura','id_o');
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$fields = "numero,data_fattura";
		
// 		$this->functionsIfFromDb = array(
// 			"data_creazione"	=>	"getAnnoDaDatetime",
// 		);
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
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
	
}
