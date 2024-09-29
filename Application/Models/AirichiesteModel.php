<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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

class AirichiesteModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'ai_richieste';
		$this->_idFields = 'id_ai_richiesta';
		
		$this->_idOrder = 'id_order';
		
		$this->addValuesCondition("both",'checkNotEmpty',"titolo");

		parent::__construct();
	}

	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'titolo'		=>	array(
					'labelString'=>	'Richiesta',
					'type'		 =>	'Textarea',
				),
				'id_c'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	gtext('Parla di questa categoria'),
					'options'	=>	$this->buildAllCatSelect(),
					'reverse' => 'yes',
					'entryClass'  => 'form_input_text',
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
					'wrap'	=>	array(null,null,"<div>","</div>"),
				),
				'id_page'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	gtext('Parla di questa pagina'),
					'options'	=>	$this->selectLinkContenuto(),
					'reverse' => 'yes',
					'entryClass'  => 'form_input_text',
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
					'wrap'	=>	array(null,null,"<div>","</div>"),
				),
				'id_marchio'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	gtext("Parla di questo marchio"),
					'options'	=>	$this->selectMarchi(),
					'reverse' => 'yes',
					'entryClass'  => 'form_input_text',
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
					'wrap'	=>	array(null,null,"<div>","</div>"),
				),
			),
		);
	}

	public function estraiContesti($id)
	{
		$record = $this->selectId((int)$id);

		$arrayIds = [];

		if (!empty($record))
		{
			$idC = isset($this->values["id_c"]) ? (int)$this->values["id_c"] : 0;
			$idMarchio = isset($this->values["id_marchio"]) ? (int)$this->values["id_marchio"] : 0;
			$idPage = isset($this->values["id_page"]) ? (int)$this->values["id_page"] : 0;

			if ($idPage)
				$arrayIds[] = $idPage;

			$idS = ProdottiModel::prodottiPiuVenduti($idC, $idMarchio);

			$arrayIds = array_merge($arrayIds, $idS);

			$arrayIds = array_unique($arrayIds);
		}

		return $arrayIds;
	}

	public function update($id = null, $where = null)
	{
		$res = parent::update($id, $where);

		if ($res)
		{
			$idS = $this->estraiContesti($id);

			$aircModel = new AirichiestecontestiModel();

			foreach ($idS as $idPage)
			{
				$aircModel->sValues(array(
					"id_ai_richiesta"	=>	(int)$id,
					"id_page"			=>	(int)$idPage,
				));

				$aircModel->insert();
			}
		}

		return $res;
	}
}
