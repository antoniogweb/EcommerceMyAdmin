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

trait TraitdocumentiController
{
	public function documenti($id = 0)
	{
		$this->orderBy = "documenti.id_order";
		
		$this->_posizioni['documenti'] = 'class="active"';
		
		$this->ordinaAction = "ordinadocumenti";
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$data['id_page'] = $clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_page";
		
		$bckModel = $this->modelName;
		
		$this->modelName = $this->m[$this->modelName]->documentiModelAssociato; //"DocumentiModel";
		
		if (!isset($this->m[$this->modelName]))
			$this->model($this->modelName);
		
		if (isset($_GET["pulisci_file"]) && $_GET["pulisci_file"] == "Y")
		{
			$this->m[$this->modelName]->pulisciFile();
			
			flash("notice","<div class='alert alert-success'>".gtext("Pulizia avvenuta")."</div>");
			
			$this->redirect($this->applicationUrl.$this->controller."/".$this->action."/".$clean['id'].$this->viewStatus);
		}
		
		$this->m[$this->modelName]->updateTable('del');
		
		$selectLingue = $this->m[$this->modelName]->selectLingua();
		
		$filtroLingua = array("tutti" => gtext("VEDI TUTTO")) + array("tutte" => gtext("TUTTE LE LINGUE")) + $selectLingue;
		$filtroTipoDoc = array("tutti" => gtext("VEDI TUTTO")) + $this->m[$this->modelName]->selectTipo("ecludi ");
		
		$this->aggregateFilters = false;
		$this->showFilters = true;
		
		if (v("attiva_immagine_in_documenti"))
		{
			$this->filters = array(null,null,"titolo_documento", null);
			$this->mainFields = array("immagine","titoloDocumento","filename");
			$this->mainHead = "Thumb,Titolo,File";
			
			$this->colProperties = array(
				array(
					'width'	=>	'60px',
				),
				array(
					'width'	=>	'160px',
				),
			);
		}
		else
		{
			$this->filters = array(null,"titolo_documento", null);
			$this->mainFields = array("titoloDocumento","filename");
			$this->mainHead = "Titolo,File";
			
			$this->colProperties = array(
				array(
					'width'	=>	'60px',
				),
			);
		}
		
		if (count($selectLingue) > 1)
		{
			$this->mainFields[] = "lingua";
			$this->mainHead .= ",Visibile su lingua";
			$this->filters[] = array("lingua_doc","",$filtroLingua);
		}
		
		if (v("attiva_altre_lingue_documento"))
		{
			$this->filters[] = null;
			$this->mainFields[] = "escludilingua";
			$this->mainHead .= ",Escludi lingua";
		}
		
		// Traduzione documenti
		if (!v("abilita_traduzioni_documenti"))
			$this->addTraduzioniInMain = false;
		
		$this->filters[] = array("id_tipo_doc","",$filtroTipoDoc);
		$this->mainFields[] = "tipi_documento.titolo";
		$this->mainHead .= ",Tipo";
		
		if (v("attiva_gruppi_documenti"))
		{
			$this->mainFields[] = "accessi";
			$this->mainHead .= ",Accessi";
		}
		
		$this->mainButtons = "ldel";
		
		$this->getTabViewFields("documenti");
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>$this->mainMenuAssociati,'mainAction'=>"documenti/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("distinct documenti.id_doc,documenti.*,tipi_documento.*")
			->left(array("tipo"))
			->left("documenti_lingue")->on("documenti_lingue.id_doc = documenti.id_doc and documenti_lingue.includi = 1")
			->orderBy("documenti.id_order")
			->where(array(
				"id_tipo_doc"	=>	$this->viewArgs["id_tipo_doc"],
				"visibile"	=>	1,
				"lk"		=>	array("documenti.titolo" => $this->viewArgs["titolo_documento"]),
			))->convert();
		
		if ($this->m[$this->modelName]->elencaDocumentiPaginaImport)
		{
			$this->m[$this->modelName]->left(array("page"))->aWhere(array(
				"id_import"	=>	$clean['id'],
			));
		}
		else
		{
			if ($this->documentiInPagina)
				$this->m[$this->modelName]->inner(array("page"))->aWhere(array(
					"id_page"	=>	$clean['id'],
				));
			else
				$this->m[$this->modelName]->inner("regusers")->on("documenti.id_user = regusers.id_user")->aWhere(array(
					"id_user"	=>	$clean['id'],
				));
		}
		
		if ($this->viewArgs["lingua_doc"] != "tutti")
		{
			$this->m[$this->modelName]->aWhere(array(
				"OR"	=>	array(
					"lingua"	=>	$this->viewArgs["lingua_doc"],
					"AND"	=>	array(
						"documenti_lingue.lingua"	=>	$this->viewArgs["lingua_doc"],
						"ne"	=>	array(
							"lingua"	=>	"tutte",
						),
					),
				),
			));
		}
		
		$this->m[$this->modelName]->save();
		
// 		$this->tabella = gtext("prodotti");
		
		parent::main();
		
		$data["titoloRecord"] = $this->m($bckModel)->titolo($clean['id']);
		
		$this->append($data);
	}
}
