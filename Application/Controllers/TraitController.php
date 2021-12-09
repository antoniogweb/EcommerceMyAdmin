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

trait TraitController
{
	public function ctform($queryType = 'insert', $id = 0)
	{
		$this->shift(2);
		
		$this->menuLinks = "save";
		
		$fields = 'title,alias,sottotitolo,description,meta_title,keywords,meta_description';
		
		if ($queryType == "insert")
			$section = $data["sectionCampiAggiuntivi"] = $this->viewArgs["section"];
		else
		{
			$record = $this->m[$this->modelName]->selectId((int)$id);
			
			if (!empty($record))
				$section = $data["sectionCampiAggiuntivi"] = $record["sezione"];
		}
		
		if (v("attiva_descrizione_2_in_prodotti") && $section == "prodotti_detail")
			$fields .= ",descrizione_2";
		
		if (v("attiva_descrizione_3_in_prodotti") && $section == "prodotti_detail")
			$fields .= ",descrizione_3";
		
		if ($section == "slide_detail" || $section == "modali_detail")
			$fields = 'title,sottotitolo,url,testo_link,description';
		else if ($section == "blog_detail")
			$fields = 'title,alias,sottotitolo,description,meta_title,keywords,meta_description';
		else if ($section == "-car-" || $section == "-cv-" || $section == "fasce_prezzo")
			$fields = 'titolo,alias';
		else if ($section == "-ruolo-" || $section == "attributi" || $section == "attributi_valori" || $section == "personalizzazioni" || $section == "tipi_azienda" || $section == "tipologie_caratteristiche")
			$fields = 'titolo';
		else if ($section == "-marchio-")
			$fields = 'titolo,sottotitolo,alias,descrizione,meta_title,keywords,meta_description';
		else if ($section == "tag" || $section == "-marchio-")
			$fields = 'titolo,alias,description,meta_title,keywords,meta_description';
		else if ($section == "documenti" || $section == "contenuti" || $section == "pagamenti")
			$fields = 'titolo,descrizione';
		else if ($section == "email_detail")
			$fields = 'title,editor_visuale,description';
		
		if (defined("CAMPI_AGGIUNTIVI_PAGINE") && isset(CAMPI_AGGIUNTIVI_PAGINE[$section]))
		{
			foreach (CAMPI_AGGIUNTIVI_PAGINE[$section] as $campo => $form)
			{
				$fields .= ",$campo";
				
				$this->m[$this->modelName]->formStructAggiuntivoEntries[$campo] = $form;
			}
		}
		
		// Campi aggiuntivi dalle APP
		if (isset(PagesModel::$campiAggiuntivi[$section]))
		{
			foreach (PagesModel::$campiAggiuntivi[$section] as $campo => $form)
			{
				if (in_array($campo, PagesModel::$campiAggiuntiviMeta["traduzione"]))
				{
					$fields .= ",$campo";
					
					if (!empty($form))
						$this->m[$this->modelName]->formStructAggiuntivoEntries[$campo] = $form;
				}
			}
		}
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		// Lo imposto come salvato manualmente
		$this->m[$this->modelName]->setValue("salvato",1);
		
		if ($section != "tutti")
			$this->m[$this->modelName]->setValue("sezione",$section);
		
		parent::form($queryType, $id);
		
		$data["editor_visuale"] = $this->getUsaEditorVisuale($queryType, $id);
		
		$this->append($data);
	}
	
	public function traduzione($id = 0, $idCt = 0)
	{
		$this->shift(2);
		
		$this->modelName = "ContenutitradottiModel";
		
		$this->formAction = $this->updateRedirectUrl = $this->controller."/traduzione/".(int)$id."/".(int)$idCt.$this->viewStatus;
		
		$this->ctform("update", $idCt);
		
		$data["id"] = (int)$id;
		$data["id_ct"] = (int)$idCt;
		
		$this->append($data);
	}
}
