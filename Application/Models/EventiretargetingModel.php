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

class EventiretargetingModel extends GenericModel {
	
	public static $tipiRetargeting = array(
		"FORM_CONTATTO"	=>	"Manda email dopo che il cliente ha compilato un FORM CONTATTO qualsiasi",
		"NEWSLETTER"	=>	"Manda email dopo che il cliente si è iscritto alla NEWSLETTER",
	);
	
	public static $scattaDopoOre = array(
		0	=>	"Immediatamente",
		1	=>	"Dopo 1 ora",
		2	=>	"Dopo 2 ore",
		3	=>	"Dopo 3 ore",
		4	=>	"Dopo 4 ore",
		5	=>	"Dopo 5 ore",
		6	=>	"Dopo 6 ore",
		12	=>	"Dopo 12 ore",
		24	=>	"Dopo 1 giorno",
		48	=>	"Dopo 2 giorni",
		72	=>	"Dopo 3 giorni",
		96	=>	"Dopo 4 giorni",
		120	=>	"Dopo 5 giorni",
		144	=>	"Dopo 6 giorni",
		168	=>	"Dopo 7 giorni",
		192	=>	"Dopo 8 giorni",
		216	=>	"Dopo 9 giorni",
		240	=>	"Dopo 10 giorni",
	);
	
	public static $eventiAttivi = null;
	
	public function __construct() {
		$this->_tables='eventi_retargeting';
		$this->_idFields='id_evento';
		
		$this->_idOrder = 'id_order';
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'email' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
		);
    }
    
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'titolo'		=>	array(
					'labelString'=>	'Dai un titolo al tuo evento',
					'entryClass'	=>	'form_input_text help_titolo',
				),
				'tipo'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Quale evento vuoi scatenare?",
					"options"	=>	$this->tipoEvento(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'scatta_dopo_ore'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Dopo quante ore vuoi inviare la mail?",
					"options"	=>	$this->scattaDopoOre(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'id_page'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Quale mail vuoi inviare?",
					"options"	=>	$this->selectMail(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'attivo'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Attiva l'evento",
					"options"	=>	$this->selectAttivo(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
		);
	}
	
	public function attivo($record)
	{
		return $record[$this->_tables]["attivo"] ? "<i class='fa fa-check text text-success'></i>" : "<i class='fa fa-ban text text-danger'></i>";
	}
	
	public function selectAttivo()
	{
		return gtextDeep(self::$attivoSiNo);
	}
	
	public function tipo($record)
	{
		if (isset(self::$tipiRetargeting[$record["eventi_retargeting"]["tipo"]]))
			return gtext(self::$tipiRetargeting[$record["eventi_retargeting"]["tipo"]]);
		
		return "";
	}
	
	public function dopoquanto($record)
	{
		if (isset(self::$scattaDopoOre[$record["eventi_retargeting"]["scatta_dopo_ore"]]))
			return gtext(self::$scattaDopoOre[$record["eventi_retargeting"]["scatta_dopo_ore"]]);
		
		return "";
	}
	
	public function selectMail()
	{
		return PagesModel::g(false)->selectPagineSezione("email", false);
	}
	
	public function tipoEvento()
	{
		return gtextDeep(self::$tipiRetargeting);
	}
	
	public function scattaDopoOre()
	{
		return gtextDeep(self::$scattaDopoOre);
	}
	
	public static function processa($tipi = array(), $idElemento = 0)
	{
		$evModel = new EventiretargetingModel();
		
		if (!isset(self::$eventiAttivi))
		{
			self::$eventiAttivi = $evModel->clear()->where(array(
				"attivo"	=>	1,
			))
			->inner(array("email"))->orderBy("eventi_retargeting.id_order")->send();
		}
		
		if (!empty(self::$eventiAttivi))
		{
// 			print_r(self::$eventiAttivi);
			foreach (self::$eventiAttivi as $evento)
			{
				$tipo = $evento["eventi_retargeting"]["tipo"];
				$idPagina = $evento["eventi_retargeting"]["id_page"];
				$scattaDopoOre = $evento["eventi_retargeting"]["scatta_dopo_ore"];
				
				$email = PagesModel::getPageDetails($idPagina);
				
// 				print_r($email);
				
				if ($tipo == "FORM_CONTATTO" || $tipo == "NEWSLETTER")
				{
					$cModel = ContattiModel::g();
					
					if ($idElemento)
						$cModel->aWhere(array(
							"id_contatto"	=>	(int)$idElemento,
						));
					
					if ($tipo == "FORM_CONTATTO")
						$cModel->aWhere(array(
							"fonte"	=>	"CONTATTI",
						));
					else
						$cModel->aWhere(array(
							"fonte"	=>	"NEWSLETTER",
						));
					
					$elementi = $cModel->send(false);
					
					
				}
			}
		}
	}
}
