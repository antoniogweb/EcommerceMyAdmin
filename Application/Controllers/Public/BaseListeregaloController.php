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

class BaseListeregaloController extends BaseController
{
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->load('header');
		$this->load('footer','last');
		
		$data["arrayLingue"] = array();
		
		$this->s['registered']->check(null,0);
		
		$this->model("ListeregalotipiModel");
		
		$this->append($data);
	}

	public function index()
	{
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/liste-regalo/";
		}
		
		$data['title'] = Parametri::$nomeNegozio . ' - '. gtext("Liste regalo");
		
		$clean["id_lista"] = $this->request->get("id_lista",0,"forceInt");
		$clean["valore"] = $this->request->get("valore","","sanitizeAll");
		
		if ($clean["id_lista"] > 0 && ListeregaloModel::numeroListeUtente(User::$id, $clean["id_lista"]) && in_array($clean["valore"], array("Y","N")))
		{
			$valoreAttivo = (string)$clean["valore"] === "Y" ? "Y" : "N";
			
			$this->m["ListeregaloModel"]->sValues(array(
				"attivo"	=>	$valoreAttivo,
			));
			$this->m["ListeregaloModel"]->pUpdate($clean["id_lista"]);
			
			$this->redirect("liste-regalo/");
// 			echo $this->m["SpedizioniModel"]->notice;
		}
		
		$data['liste'] = $this->m["ListeregaloModel"]->clear()->select("*")->inner(array("tipo"))->where(array("id_user"=>$this->iduser))->orderBy("time_creazione desc")->send();
		
		$this->append($data);
		
		$this->load('main');
	}
	
	public function checkLista($id = 0, $redirect = true)
	{
		$clean["id"] = (int)$id;
		
		if ($clean["id"] > 0)
		{
			$numero = ListeregaloModel::numeroListeUtente(User::$id, $clean["id"]);
			
			if ($numero === 0)
			{
				$this->redirect("");
				die();
			}
		}
	}
	
	public function modifica($id = 0)
	{
		$clean["id"] = $data["id"] = (int)$id;
		
		$this->checkLista($id);
		
		$lista = $this->m['ListeregaloModel']->selectId($clean["id"]);
		
		$idTipoLista = !empty($lista) ? $lista["id_lista_tipo"] : $this->request->post("id_lista_tipo",0,"forceInt");
		
		$data["selectTipi"] = ListeregalotipiModel::getSelectTipi($idTipoLista);
		
		if (!$idTipoLista)
			$idTipoLista = count($data["selectTipi"]) > 0 ? key($data["selectTipi"]) : 0;
		
		$data["idTipoLista"] = $idTipoLista;
		
		$tipoLista = $this->m["ListeregalotipiModel"]->selectId((int)$idTipoLista);
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/listeregalo/modifica/".$clean["id"];
		}
		
		$data['title'] = Parametri::$nomeNegozio . ' - ' . gtext("Gestisci lista nascita", false);
		$data['notice'] = null;
		$data['action'] = "/listeregalo/modifica/".$clean["id"];
		
		$campiObbligatori = "titolo";
		
		if (!$clean["id"])
			$campiObbligatori .= ",id_lista_tipo";
		
		if (!empty($tipoLista))
		{
			$fields = 'titolo,id_lista_tipo';
			
			if ($tipoLista["campi"])
				$fields .= ','.$tipoLista["campi"];
			
			if ($tipoLista["campi_obbligatori"])
				$campiObbligatori .= ','.$tipoLista["campi_obbligatori"];
		}
		else
			$fields = 'titolo,id_lista_tipo,nome_bambino,genitore_1,genitore_2,sesso,data_nascita,data_battesimo';
		
		$this->m['ListeregaloModel']->setFields($fields,'sanitizeAll');
		
		$this->m['ListeregaloModel']->setValue("id_user", User::$id);
		
		if (!empty($lista))
			$this->m['ListeregaloModel']->delFields("id_lista_tipo");
		
		$this->m['ListeregaloModel']->clearConditions("strong");
		$this->m['ListeregaloModel']->addStrongCondition("both",'checkNotEmpty',$campiObbligatori);
		
		if (!$clean["id"])
			$this->m['ListeregaloModel']->addStrongCondition("both",'checkIsStrings|'.implode(",",array_keys($data["selectTipi"])),"id_lista_tipo|".gtext("<b>Si prega di selezionare il tipo della lista</b>"));
		
		$this->m['ListeregaloModel']->addSoftCondition("both",'checkIsStrings|M,F',"sesso");
		
		$this->m['ListeregaloModel']->updateTable('insert,update',$clean["id"]);
		
		if ($this->m['ListeregaloModel']->queryResult)
		{
// 			if (!empty($ordine))
// 				$this->redirect("ordini/modifica/".$ordine["id_o"]."/".$ordine["cart_uid"]);
// 			else
				$this->redirect("liste-regalo/");
		}
		else
		{
			if (!$this->m['ListeregaloModel']->result)
				$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>".$this->m['ListeregaloModel']->notice;
		}
		
		$submitAction = $id > 0 ? "update" : "insert";
		
		$data['values'] = $this->m['ListeregaloModel']->getFormValues($submitAction,'sanitizeHtml',$clean["id"],array("id_lista_tipo"=>$idTipoLista));
		
		$this->append($data);
		$this->load('modifica');
	}
}
