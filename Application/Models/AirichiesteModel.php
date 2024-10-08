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
		
		parent::__construct();
	}

	public function relations() {
		return array(
			'messaggi' => array("HAS_MANY", 'AirichiestemessaggiModel', 'id_ai_richiesta', null, "RESTRICT", "L'elemento non è eliminabile perché ha dei messaggi collegati"),
			'contesti' => array("HAS_MANY", 'AirichiestecontestiModel', 'id_ai_richiesta', null, "CASCADE"),
			'modello' => array("BELONGS_TO", 'AimodelliModel', 'id_ai_modello',null,"RESTRICT","Si prega di selezionare il modello".'<div style="display:none;" rel="hidden_alert_notice">id_ai_modello</div>'),
		);
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
				'id_ai_modello'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	gtext('Modello di AI'),
					'options'	=>	$this->selectModelli($id),
					'reverse' => 'yes',
					'entryClass'  => 'form_input_text',
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

		if ($id)
			$this->formStruct["submit"] = [];
	}

	public function selectModelli($id)
	{
		$idModello = (int)$this->clear()->whereId((int)$id)->field("id_ai_modello");

		$aimModel = new AimodelliModel();

		$modelli = $aimModel->clear()->where(array(
			"OR"	=>	array(
				"id_ai_modello"	=>	(int)$idModello,
				"AND"	=>	array(
					"attivo"	=>	1,
				   "ne"	=>	array(
						"key_1"	=>	"",
					),
				),
			),
		))->orderBy("predefinito desc")->send(false);

		$selectModelli = [];

		foreach ($modelli as $m)
		{
			$selectModelli[$m["id_ai_modello"]] = $m["titolo"] . " - contesto di max " . $m["numero_pagine"] . " pagine";
		}

		return $selectModelli;
	}

	public function titolo($id)
	{
		$clean["id"] = (int)$id;

		$record = $this->selectId($clean["id"]);

		$titolo = [];

		if ($record["id_c"])
			$titolo[] = CategoriesModel::g(false)->clear()->whereId((int)$record["id_c"])->field("title");

		if ($record["id_marchio"])
			$titolo[] = MarchiModel::g(false)->clear()->whereId((int)$record["id_marchio"])->field("titolo");

		if ($record["id_page"])
			$titolo[] = PagesModel::g(false)->clear()->whereId((int)$record["id_page"])->field("title");

		return gtext("Parla di").": <i>".implode("</i> - <i>", $titolo)."</i>";
	}

	public function titoloCrud($record)
	{
		return $this->titolo($record["ai_richieste"]["id_ai_richiesta"]);
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

			$numeroMassimoContesti = AirichiesteModel::g(false)->numeroMassimoPagineContesto($id);

			$idS = ProdottiModel::prodottiPiuVenduti($idC, $idMarchio, $numeroMassimoContesti);

			$arrayIds = array_merge($arrayIds, $idS);

			$arrayIds = array_unique($arrayIds);
		}

		return $arrayIds;
	}

	public function inserisciContesti($id)
	{
		$idS = $this->estraiContesti($id);

		$aircModel = new AirichiestecontestiModel();

		// Inserisci tutti i contesti trovati senza verificare il numero
		AirichiestecontestiModel::$controllaNumeroPagineContesto = false;

		foreach ($idS as $idPage)
		{
			$aircModel->sValues(array(
				"id_ai_richiesta"	=>	(int)$id,
				"id_page"			=>	(int)$idPage,
			));

			$aircModel->insert();
		}
	}

	public function insert()
	{
		$this->values["id_admin"] = User::$id;

		$res = parent::insert();

		if ($res)
			$this->inserisciContesti($this->lId);

		return $res;
	}

	public function numeroMassimoPagineContesto($idRichiesta)
	{
		$idModello = $this->clear()->select("id_ai_modello")->whereId((int)$idRichiesta)->field("id_ai_modello");

		return (int)AimodelliModel::getModulo((int)$idModello)->getParam("numero_pagine");
	}

	public function messaggio($id)
	{
		$record = $this->selectId((int)$id);

		if (!empty($record))
		{
			$messaggio = $_POST["messaggio"] ?? "";

			if (trim($messaggio))
			{
				$airmModel = new AirichiestemessaggiModel();

				$contesto = AirichiestecontestiModel::g(false)->getContesto((int)$id);

				$res = $airmModel->clear()->select("messaggio,ruolo")->where(array(
					"id_ai_richiesta"	=>	(int)$id,
				))->orderBy("data_creazione")->process()->send(false);

				$messaggi = array();

				foreach ($res as $r)
				{
					$messaggi[] = array(
						"role"		=>	$r["ruolo"],
						"content"	=>	htmlentitydecode($r["messaggio"]),
					);
				}

				$messaggi[] = array(
					"role"		=>	"user",
					"content"	=>	$messaggio,
				);

				$airmModel->sValues(array(
					"messaggio"			=>	$messaggio,
					"id_ai_richiesta"	=>	(int)$id,
					"id_admin"			=>	User::$id,
					"ruolo"				=>	"user",
				));

				if ($airmModel->insert())
				{
					list($ris, $messaggio) = AimodelliModel::getModulo((int)$record["id_ai_modello"])->chat($messaggi, $contesto);

					$airmModel->sValues(array(
						"messaggio"			=>	F::sanitizeTesto($messaggio),
						"id_ai_richiesta"	=>	(int)$id,
						"id_admin"			=>	User::$id,
						"ruolo"				=>	"assistant",
						"risultato_richiesta"	=>	(int)$ris,
					));

					$airmModel->insert();
				}
			}
		}
	}

	public function deletable($id)
	{
		$airmModel = new AirichiestemessaggiModel();

		if ($airmModel->getMessaggi($id, true))
			return false;

		return true;
	}

	public function numeroMessaggiCrud($record)
	{
		$airmModel = new AirichiestemessaggiModel();

		return $airmModel->getMessaggi($record["ai_richieste"]["id_ai_richiesta"], true);
	}

	public function cercaOCrea($idC, $idMarchio, $idPage)
	{
		$where = array(
			"id_c"			=>	(int)$idC,
			"id_marchio"	=>	(int)$idMarchio,
			"id_page"		=>	(int)$idPage,
		);

		// $richiesta = $this->clear()->select("ai_richieste.id_ai_richiesta")->where($where)->left("ai_richieste_messaggi")->on("ai_richieste_messaggi.id_ai_richiesta = ai_richieste.id_ai_richiesta")->sWhere("ai_richieste_messaggi.id_ai_richiesta_messaggio IS NULL")->send();

		$richiesta = $this->clear()->select("ai_richieste.id_ai_richiesta")->where($where)->orderBy("id_ai_richiesta desc")->send();

		$idRichiesta = 0;

		if (!empty($richiesta))
			$idRichiesta = (int)$richiesta[0]["ai_richieste"]["id_ai_richiesta"];
		else
		{
			$this->sValues($where);

			$modelli = $this->selectModelli(0);

			if (count($modelli) > 0)
			{
				reset($modelli);
				$idModello = key($modelli);

				$this->setValue("id_ai_modello", $idModello, "forceInt");

				if ($this->insert())
					$idRichiesta = $this->lId;
			}
		}

		return $idRichiesta;
	}
}
