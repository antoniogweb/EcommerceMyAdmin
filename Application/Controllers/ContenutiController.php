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

class ContenutiController extends BaseController
{
	use TraitController;
	
	public $tabella = "elemento";
	
	public $argKeys = array(
		'id_page:sanitizeAll'	=>	'tutti',
		'id_c:sanitizeAll'		=>	'tutti',
		'tipo:sanitizeAll'		=>	'tutti',
		'id_tipo:sanitizeAll'	=>	'tutti',
		'id_fascia:sanitizeAll'	=>	'tutti',
		'id_tipo_figlio:sanitizeAll'	=>	'tutti',
	);
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);

		$this->model("ReggroupscontenutiModel");
		$this->model("TipicontenutoModel");
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$this->shift(2);
		
		if ($this->viewArgs["id_fascia"] != "tutti")
			$this->menuLinks = $this->menuLinksInsert = "back_fascia,save";
		else
			$this->menuLinks = $this->menuLinksInsert = "save";
		
		$recordTipo = $data["recordTipo"] = array();
		
		if ($this->viewArgs["id_tipo"] != "tutti")
			$recordTipo = $data["recordTipo"] = $this->m["TipicontenutoModel"]->selectId($this->viewArgs["id_tipo"]);
		else
		{
			$recordContenuto = $this->m[$this->modelName]->selectId((int)$id);
			
			if ($recordContenuto)
				$recordTipo = $data["recordTipo"] = $this->m["TipicontenutoModel"]->selectId($recordContenuto["id_tipo"]);
		}
		
// 		$this->m[$this->modelName]->setValuesFromPost("titolo,id_tipo,lingua,immagine_1,immagine_2,descrizione,link_contenuto,link_libero,target");
		
		$fields = "lingua,attivo";
		
		if (!empty($recordTipo) && trim($recordTipo["campi"]))
			$fields .= ",".$recordTipo["campi"];
		else
		{
			if ($this->viewArgs["tipo"] == "GENERICO")
				$fields .= ",descrizione,immagine_1,filename";
			else if ($this->viewArgs["tipo"] == "MARKER")
				$fields .= ",descrizione,coordinate";
		}
		
		$fields .= ",titolo";
		
		$data["editor_visuale"] = true;
		
		if (isset($recordTipo["tipo"]) && $recordTipo["tipo"] == "FASCIA" && $queryType == "update")
		{
			$fields .= ",descrizione";
			$data["editor_visuale"] = false;
		}
		
		if (($this->viewArgs["id_tipo"] == "tutti" && $queryType == "insert") || (isset($recordTipo["tipo"]) && $recordTipo["tipo"] != "FASCIA" && $queryType == "update"))
			$fields .= ",id_tipo";
		
		if ($queryType == "update")
		{
			$recordContenuto = $this->m[$this->modelName]->selectId((int)$id);
			
			if (!empty($recordContenuto) && !$recordContenuto["id_page"] && !$recordContenuto["id_c"] && !$recordContenuto["id_fascia"])
				$fields .= ",id_page";
		}
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		if ($this->viewArgs["id_page"] != "tutti")
			$this->m[$this->modelName]->setValue("id_page", (int)$this->viewArgs["id_page"]);
		
		if ($this->viewArgs["id_c"] != "tutti")
			$this->m[$this->modelName]->setValue("id_c", (int)$this->viewArgs["id_c"]);
		
		if ($this->viewArgs["id_fascia"] != "tutti")
			$this->m[$this->modelName]->setValue("id_fascia", (int)$this->viewArgs["id_fascia"]);
		
		if ($this->viewArgs["tipo"] != "tutti")
		{
			$this->m[$this->modelName]->setValue("tipo", $this->viewArgs["tipo"]);
			
// 			$this->tabella = strtolower($this->viewArgs["tipo"]);
		}
		
		if ($this->viewArgs["id_tipo"] != "tutti")
		{
			$this->m[$this->modelName]->setValue("id_tipo", $this->viewArgs["id_tipo"]);
		}
		
// 		if ($recordTipo)
// 			$this->tabella = $recordTipo["titolo"];
		
		parent::form($queryType, $id);
		
		$this->append($data);
	}
	
	protected function aggiungiUrlmenuScaffold($id)
	{
		if (isset($this->scaffold->mainMenu->links['back_fascia']))
			$this->scaffold->mainMenu->links['back_fascia']['absolute_url'] = $this->baseUrl.'/contenuti/figli/'.$this->viewArgs["id_fascia"]."?partial=Y&id_tipo_figlio=".$this->viewArgs["id_tipo_figlio"];
	}
	
	public function figli($id = 0)
	{
		$this->_posizioni['figli'] = 'class="active"';
		
		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_cont";
		
		$this->mainButtons = "ldel";
		
		$this->mainFields = array("titoloContenutoFascia");
		$this->mainHead = "Titolo";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"figli/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("contenuti.*")->orderBy("id_order")->where(array(
			"id_fascia"			=>	$clean['id'],
			"id_tipo"			=>	(int)$this->viewArgs["id_tipo_figlio"],
		))->convert()->save();
		
		parent::main();
		
		$data['tabella'] = "fascia";
		
		$data["titoloRecord"] = $this->m("ContenutiModel")->where(array("id_cont"=>$clean['id']))->field("titolo");
		
		$record = $this->m("ContenutiModel")->selectId((int)$id);
		
		if (!empty($record))
			$data["recordTipo"] = $this->m("TipicontenutoModel")->selectId($record["id_tipo"]);
		
		$this->append($data);
	}
	
	public function ordina()
	{
		$this->orderBy = "id_order";
		
		parent::ordina();
	}
	
	public function thumb($field = "", $id = 0)
	{
		parent::thumb($field, $id);
	}
	
	public function gruppi($id = 0)
	{
		$this->_posizioni['gruppi'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_cont";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "ReggroupscontenutiModel";
		
		$this->m[$this->modelName]->setFields('id_group','sanitizeAll');
		$this->m[$this->modelName]->values['id_cont'] = $clean['id'];
		$this->m[$this->modelName]->updateTable('insert,del');
		
		$this->mainFields = array("reggroups.name");
		$this->mainHead = "Gruppo";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"gruppi/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("reggroups_contenuti.*,reggroups.*")->inner("reggroups")->using("id_group")->orderBy("reggroups.name")->where(array("id_cont"=>$clean['id']))->convert()->save();
		
		parent::main();
		
		$data["listaGruppi"] = $this->m[$this->modelName]->clear()->from("reggroups")->select("reggroups.name,reggroups.id_group")->orderBy("reggroups.name")->toList("reggroups.id_group","reggroups.name")->send();
		
		$data['tabella'] = "fascia";
		
		$data["titoloRecord"] = $this->m("ContenutiModel")->where(array("id_cont"=>$clean['id']))->field("titolo");
		
		$record = $this->m("ContenutiModel")->selectId((int)$id);
		
		if (!empty($record))
			$data["recordTipo"] = $this->m("TipicontenutoModel")->selectId($record["id_tipo"]);
		
		$this->append($data);
	}
	
	public function aggiungitesto($idContenuto = 0, $tipo = "TESTO", $posizione = "DOPO")
	{
		$this->clean();
		
		$contenuto = $this->m[$this->modelName]->selectId((int)$idContenuto);
		
		$clean["tag"] = $this->request->post("tag","","none");
		$clean["tipo"] = sanitizeAll($tipo);
		
		if (!empty($contenuto) && in_array($clean["tipo"], array("TESTO", "IMMAGINE", "LINK")))
		{
			$contenuto = htmlentitydecodeDeep($contenuto);
			
			$nuovoTesto = ContenutiModel::nuovoPlaceholder($clean["tipo"]); //"[".strtolower($clean["tipo"])." testo_".generateString(8)."]";
			
// 			echo $nuovoTesto;
			
			$testo = $contenuto["descrizione"];
			
			$clean["tag"] = ContenutiModel::cercaPlaceholder($testo, $clean["tag"]);
			
			$testo = str_replace($clean["tag"], $clean["tag"].$nuovoTesto, $testo);
			
			$this->m[$this->modelName]->sValues(array(
				"descrizione"	=>	$testo
			));
			
			$this->m[$this->modelName]->pUpdate((int)$idContenuto);
		}
	}
	
	public function eliminatesto($idContenuto = 0, $idTesto = 0)
	{
		$this->clean();
		
		$contenuto = $this->m[$this->modelName]->selectId((int)$idContenuto);
		
		$clean["tag"] = $this->request->post("tag","","none");
		
		if (!empty($contenuto))
		{
			$contenuto = htmlentitydecodeDeep($contenuto);
			
			$testo = $contenuto["descrizione"];
			
			$clean["tag"] = ContenutiModel::cercaPlaceholder($testo, $clean["tag"]);
			
			$testo = str_replace($clean["tag"], "", $testo);
			
			$this->m[$this->modelName]->sValues(array(
				"descrizione"	=>	$testo
			));
			
			if ($this->m[$this->modelName]->pUpdate((int)$idContenuto))
			{
				$testoRecord = $this->m("TestiModel")->selectId((int)$idTesto);
				
				if (!empty($testoRecord))
				{
					$this->m("TestiModel")->del(null, array(
						"chiave"	=>	sanitizeAll($testoRecord["chiave"]),
					));
				}
			}
		}
	}
}
