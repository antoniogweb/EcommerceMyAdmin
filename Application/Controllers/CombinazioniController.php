<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
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

class CombinazioniController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $arrayAttributi = array();
	
	public $sezionePannello = "ecommerce";
	
	public $tabella = "magazzino";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		$this->argKeys = array(
			'prodotto:sanitizeAll'=>'tutti',
			'categoria:sanitizeAll'=>'tutti',
			'codice:sanitizeAll'=>'tutti',
			'id_page:sanitizeAll'=>'tutti',
			'listino:sanitizeAll'=>'tutti',
		);
		
		$this->model("PagesattributiModel");
		
		$this->arrayAttributi = $this->m["PagesattributiModel"]->clear()->select("distinct pages_attributi.id_a, attributi.titolo")->inner(array("attributo"))->toList("pages_attributi.id_a","attributi.titolo");
		
		if (isset($_GET["id_page"]) && $_GET["id_page"] != "tutti")
			$this->m["PagesattributiModel"]->where(array(
				"id_page"	=>	(int)$_GET["id_page"],
			))->orderBy("pages_attributi.id_order");
		
		$this->arrayAttributi = $this->m["PagesattributiModel"]->send();
		
		foreach ($this->arrayAttributi as $idA => $titoloA)
		{
			$this->argKeys["id_".$idA.":sanitizeAll"] = "tutti";
		}
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		$this->model("AttributivaloriModel");
		$this->model("CombinazionilistiniModel");
	}

	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("c1.title", "prodotto","varianti","codice","prezzo","peso");
		
		$prezzoLabel = "Prezzo";
		
		if ($this->viewArgs["listino"] == "tutti")
			$prezzoLabel .= " (Italia)";
		else if ($this->viewArgs["listino"] == "W")
			$prezzoLabel .= " (Mondo)";
		else
			$prezzoLabel .= " (".findTitoloDaCodice($this->viewArgs["listino"]).")";
		
		$this->mainHead = "Categoria,Prodotto,Combinazione,Codice,$prezzoLabel,Peso";
		
		if (v("attiva_giacenza"))
		{
			$this->mainFields[] = "giacenza";
			$this->mainHead .= ",Giacenza";
		}
		
		$this->mainFields[] = "ordini";
		$this->mainHead .= ",Acquisti";
		
		if ($this->viewArgs['id_page'] == "tutti")
			$this->filters = array("categoria", "prodotto", "codice");
		
		$attributi = $this->m["PagesattributiModel"]->clear()->select("distinct pages_attributi.id_a, attributi.titolo")->inner(array("attributo"))->toList("pages_attributi.id_a","attributi.titolo")->send();
		
		foreach ($this->arrayAttributi as $idA => $titoloA)
		{
			Helper_List::$filtersFormLayout["filters"]["id_".$idA] = array(
				"type"	=>	"select",
				"attributes"	=>	array(
					"class"	=>	"form-control",
				),
			);
			
			$filtriIdA = array("tutti" => $titoloA) + $this->m["AttributivaloriModel"]->selectPerFiltro($idA);
			
			$this->filters[] = array("id_".$idA,null,$filtriIdA);
		}
		
// 		$this->addBulkActions = false;
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>1000000, 'mainMenu'=>'save_combinazioni,esporta');
		
		$this->mainButtons = 'ldel';
		
		$this->m[$this->modelName]->clear()->select("c2.title,c1.title,pages.*,combinazioni.*")
				->inner(array("pagina"))
				->left("categories as c1")->on("c1.id_c = pages.id_c")
				->left("categories as c2")->on("c2.id_c = c1.id_p")
				->where(array(
					"lk" => array('pages.title' => $this->viewArgs['prodotto']),
					" lk" => array('c1.title' => $this->viewArgs['categoria']),
					"  lk" => array('combinazioni.codice' => $this->viewArgs['codice']),
					"id_page"	=>	$this->viewArgs['id_page']
				))
				->orderBy("c1.title,pages.title");
		
// 		print_r($this->viewArgs);die();
		
		$indice = 0;
		
		foreach ($this->arrayAttributi as $idA => $titoloA)
		{
			if ($this->viewArgs["id_".$idA] != "tutti")
			{
				$strOr = str_repeat(" ", $indice);
				
				$this->m[$this->modelName]->aWhere(array(
					$strOr."OR"	=>	array(
						"col_1"	=>	$this->viewArgs["id_".$idA],
						"col_2"	=>	$this->viewArgs["id_".$idA],
						"col_3"	=>	$this->viewArgs["id_".$idA],
						"col_4"	=>	$this->viewArgs["id_".$idA],
						"col_5"	=>	$this->viewArgs["id_".$idA],
						"col_6"	=>	$this->viewArgs["id_".$idA],
						"col_7"	=>	$this->viewArgs["id_".$idA],
						"col_8"	=>	$this->viewArgs["id_".$idA],
					),
				));
				
				$indice++;
			}
		}
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$this->m[$this->modelName]->setValuesFromPost('titolo,valore');
		
		parent::form($queryType, $id);
	}
	
	public function salva()
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
		
		$this->m[$this->modelName]->db->beginTransaction();
		
		$this->clean();
		
		$valori = $this->request->post("valori","[]");
		
		$valori = json_decode($valori, true);
		
		foreach ($valori as $v)
		{
			$this->m[$this->modelName]->setValues(array(
				"codice"	=>	$v["codice"],
				"peso"		=>	$v["peso"],
			));
			
			if (!$v["id_cl"])
				$this->m[$this->modelName]->setValue("price", $v["prezzo"]);
			
			if (isset($v["giacenza"]))
				$this->m[$this->modelName]->setValue("giacenza", $v["giacenza"]);
			
			$this->m[$this->modelName]->update($v["id_c"]);
			
			if ($v["id_cl"])
			{
				$this->m ["CombinazionilistiniModel"]->setValues(array(
					"price"	=>	$v["prezzo"],
				));
				
				$this->m ["CombinazionilistiniModel"]->update($v["id_cl"]);
			}
		}
		
		$this->m[$this->modelName]->db->commit();
	}
}
