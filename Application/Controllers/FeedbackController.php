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

class FeedbackController extends BaseController {
	
	public $orderBy = "id_order desc";
	
	public $argKeys = array('id_page:sanitizeAll'=>'tutti');
	
	public $functionsIfFromDb = array(
		"voto"	=>	"sistemaVotoNumero",
	);
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->m[$this->modelName]->setValuesFromPost("autore,data_feedback,testo,attivo,voto");
		
		if ($this->viewArgs["id_page"] != "tutti")
			$this->m[$this->modelName]->setValue("id_page", $this->viewArgs["id_page"]);
		
		if ($queryType == "insert")
			$this->m[$this->modelName]->setValue("is_admin", 1);
		
		parent::form($queryType, $id);
	}
	
	public function approvarifiuta($queryType = 'insert', $id = 0)
	{
		if ($queryType != "update")
			die();
		
		$data["feedback"] = $this->m[$this->modelName]->selectId((int)$id);
		
		if (empty($data["feedback"]))
			die();
		
		$this->shift(2);
		
		$campiDisabilitati = "autore,email,data_feedback,testo,voto";
		
		$this->m[$this->modelName]->setValuesFromPost($campiDisabilitati.",commento_negozio");
		
		$this->m[$this->modelName]->setValue("da_approvare", 0);
		$this->m[$this->modelName]->setValue("dataora_approvazione_rifiuto", date("Y-m-d H:i:s"));
		$this->m[$this->modelName]->setValue("da_approvare", 0);
		
		$azione = $this->request->post("updateAction", "");
		
		if ($azione)
		{
			if ($azione == "approvaFeedback")
			{
				$this->m[$this->modelName]->setValue("approvato", 1);
				$this->m[$this->modelName]->setValue("attivo", 1);
			}
			else
			{
				$this->m[$this->modelName]->setValue("approvato", 0);
				$this->m[$this->modelName]->setValue("attivo", 0);
			}
		}
		
		$this->disabledFields = $campiDisabilitati;
		$this->m[$this->modelName]->delFields($campiDisabilitati);
		
		parent::form($queryType, $id);
		
		$this->append($data);
	}
}
