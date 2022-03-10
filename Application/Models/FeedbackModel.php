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

class FeedbackModel extends GenericModel {
	
	public $campoTitolo = "autore";
	
	public static $sValues = array();
	public static $sNotice = null;
	public static $idProdotto = 0;
	public static $datiProdotto = array();
	
	public static $tendinaPunteggi = array(
// 		"0_0"	=>	"0",
// 		"0_5"	=>	"0,5",
		"1_0"	=>	"1",
// 		"1_5"	=>	"1,5",
		"2_0"	=>	"2",
// 		"2_5"	=>	"2,5",
		"3_0"	=>	"3",
// 		"3_5"	=>	"3,5",
		"4_0"	=>	"4",
// 		"4_5"	=>	"4,5",
		"5_0"	=>	"5",
	);
	
	public function __construct() {
		$this->_tables='feedback';
		$this->_idFields='id_feedback';
		
		$this->_idOrder='id_order';
		
		parent::__construct();
	}
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'attivo'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Pubblicato',
					'options'	=>	array('1' => 'sì','0' => 'no'),
					"reverse"	=>	"yes",
				),
				'voto'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Punteggio',
					'options'	=>	self::$tendinaPunteggi,
					"reverse"	=>	"yes",
				),
				'commento_negozio'	=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Inserisci un commento al feedback. Apparirà sotto al feedback del cliente.")."</div>"
					),
				),
			),
		);
	}
	
	public static function gIdProdotto()
	{
		self::$idProdotto = isset($_GET[v("var_query_string_id_rif")]) ? (int)$_GET[v("var_query_string_id_rif")] : 0;
		
		return self::$idProdotto;
	}
	
	public static function gDatiProdotto()
	{
		self::$datiProdotto = PagesModel::getPageDetails(self::gIdProdotto());
		
		return self::$datiProdotto;
	}
	
	public static function gValue($key)
	{
		if (isset(self::$sValues[$key]))
			return self::$sValues[$key];
		
		return "";
	}
	
	public function relations() {
        return array(
			'pagina' => array("BELONGS_TO", 'PagineModel', 'id_page',null,"CASCADE","Si prega di selezionare la pagina"),
        );
    }
	
	public function sistemaVoto()
	{
		if (isset($this->values["voto"]))
			$this->values["voto"] = str_replace("_",".",$this->values["voto"]);
	}
	
	public function sistemaVotoNumero($valore)
	{
		return str_replace(",","_",$valore);
	}
	
	public function insert()
	{
		$this->sistemaVoto();
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
		$record = $this->selectId((int)$id);
		
		if (empty($record))
			return;
		
		$this->sistemaVoto();
		
		$res = parent::update($id, $where);
		
		if ($res && isset($_POST["updateAction"]) && isset($_POST["approvaAction"]) && $record["da_approvare"])
		{
			$approvato = ($_POST["updateAction"] == "approvaFeedback") ? 1 : 0;
			
			$this->mandaMailApprovazione($id, $approvato);
		}
		
		return $res;
	}
	
	public function mandaMailApprovazione($id, $approvato)
	{
		$record = $this->selectId((int)$id);
		
		if (empty($record))
			return;
		
		$pagina = PagesModel::getPageDetails((int)$record["id_page"], $record["lingua"]);
		$p = new PagesModel();
		
		if ($approvato)
			$oggetto = "la sua valutazione è stata approvata";
		else
			$oggetto = "la sua valutazione è stata rifiutata";
		
		if ($approvato)
			$testoPath = "Elementi/Mail/mail_approvazione_feedback.php";
		else
			$testoPath = "Elementi/Mail/mail_disapprovazione_feedback.php";
		
		$res = MailordiniModel::inviaMail(array(
			"emails"	=>	array($record["email"]),
			"oggetto"	=>	$oggetto,
			"tipologia"	=>	"FEEDBACK_APPR_CLIENTE",
			"id_page"	=>	(int)$record["id_page"],
			"lingua"	=>	$record["lingua"],
			"testo_path"	=>	$testoPath,
			"array_variabili_tema"	=>	array(
				"LINK_PRODOTTO"	=>	Domain::$publicUrl."/".$record["lingua"]."/".$p->getUrlAlias($pagina["pages"]["id_page"], $record["lingua"]),
				"NOME_PRODOTTO"	=>	field($pagina, "title"),
				"COMMENTO"	=>	$record["commento_negozio"],
			),
		));
	}
	
	public function setUserData()
	{
		$this->values["data_feedback"] = date("Y-m-d");
		$this->values["id_user"] = User::$id;
		$this->values["is_admin"] = 0;
		$this->values["attivo"] = 0;
		$this->values["id_page"] = self::$idProdotto;
		$this->values["da_approvare"] = 1;
		$this->values["lingua"] = Params::$lang;
	}
	
	public function dataora($record)
	{
		return date("d/m/Y H:i", strtotime($record["feedback"]["data_creazione"]));
	}
	
	public function attivo($record)
	{
		return $record["feedback"]["attivo"] ? "Sì" : "No";
	}
	
	public function daapprovare($record)
	{
		$html = "";
		
		if (!$record["feedback"]["is_admin"] && !$record["feedback"]["da_approvare"])
		{
			if ($record["feedback"]["approvato"])
				$html .= "<i class='fa fa-thumbs-up text-success'></i>";
			else
				$html .= "<i class='fa fa-thumbs-down text-danger'></i>";
		}
		
		return $html;
	}
	
	public static function gStatoFeedback($record)
	{
		if (!$record["feedback"]["is_admin"])
		{
			if ($record["feedback"]["da_approvare"])
				return "IN_GESTIONE";
			else if ($record["feedback"]["approvato"])
				return "APPROVATO";
			else if (!$record["feedback"]["approvato"])
				return "RIFIUTATO";
		}
		
		return "ADMIN";
	}
	
	public static function gHtmlStatoFeedback($record)
	{
		$stato = self::gStatoFeedback($record);
		
		$tpfFile = "";
		
		if ($stato == "IN_GESTIONE")
			$tpfFile = tpf(ElementitemaModel::p("FEEDBACK_STATO_GESTIONE","", array(
				"titolo"	=>	"Feedback in gestione",
				"percorso"	=>	"Elementi/Generali/StatoFeedback/InGestione",
			)));
		else if ($stato == "APPROVATO")
			$tpfFile = tpf(ElementitemaModel::p("FEEDBACK_STATO_APPROVATO","", array(
				"titolo"	=>	"Feedback approvato",
				"percorso"	=>	"Elementi/Generali/StatoFeedback/Approvato",
			)));
		else if ($stato == "RIFIUTATO")
			$tpfFile = tpf(ElementitemaModel::p("FEEDBACK_STATO_RIFIUTATO","", array(
				"titolo"	=>	"Feedback rifiutato",
				"percorso"	=>	"Elementi/Generali/StatoFeedback/Rifiutato",
			)));
		
		if ($tpfFile)
		{
			ob_start();
			include ($tpfFile);
			return ob_get_clean();
		}
		
		return "";
	}
	
	public function gestisci($record)
	{
		$label = $record["feedback"]["da_approvare"] ? "info" : "default";
		
		if (!$record["feedback"]["is_admin"])
			return "<a href='".Url::getRoot()."feedback/approvarifiuta/update/".$record["feedback"]["id_feedback"]."?partial=Y&nobuttons=Y' class='iframe label label-$label'><i class='fa fa-pencil'></i> ".gtext("gestisci")."</a>";
		
		return "";
	}
	
	public function datagestione($record)
	{
		if ($record["feedback"]["dataora_approvazione_rifiuto"])
			return date("d/m/Y H:i", strtotime($record["feedback"]["dataora_approvazione_rifiuto"]));
		
		return "";
	}
	
	public function edit($record)
	{
		if (!$record["feedback"]["is_admin"])
			return $record["feedback"]["autore"];
		else
			return "<a class='iframe action_iframe' href='".Url::getRoot()."feedback/form/update/".$record["feedback"]["id_feedback"]."?partial=Y&nobuttons=Y'>".$record["feedback"]["autore"]."</a>";
	}
	
	public function editutente($record)
	{
		if ($record["feedback"]["email"])
		{
			if ($record["feedback"]["id_user"])
				return "<a class='iframe' href='".Url::getRoot()."regusers/form/update/".$record["feedback"]["id_user"]."?partial=Y&nobuttons=Y'>".$record["feedback"]["email"]."</a>";
			else
				return $record["feedback"]["email"];
		}
		
		return "";
	}
	
	public function punteggio($record)
	{
		$punteggio = str_replace(",",".",$record["feedback"]["voto"]);
		
		$stellePiene = floor($punteggio);
		$mezzaStella = ($punteggio > $stellePiene) ? true : false;
		
		$arrayIcone = array();
		
		if ($punteggio <= 2)
			$color = "danger";
		else if ($punteggio <= 3)
			$color = "warning";
		else if ($punteggio <= 5)
			$color = "success";
			
		for ($i = 0; $i < $stellePiene; $i++)
		{
			$arrayIcone[] = "<i class='text text-$color fa fa-star'></i>";
		}
		
		if ($mezzaStella)
			$arrayIcone[] = "<i class='text text-$color fa fa-star-half'></i>";
		
		return implode(" ",$arrayIcone);
	}
	
	public function gOrderBy()
	{
		$orderBy = v("permetti_aggiunta_feedback") ? "feedback.data_feedback desc,feedback.id_order" :"feedback.id_order";
		
		$this->orderBy($orderBy);
		
		return $this;
	}
	
	public static function get($idPage = 0, $attivo = 1)
	{
		$f = new FeedbackModel();
		
		$f->clear()->gOrderBy();
		
		if ($attivo)
			$f->aWhere(array(
				"attivo"	=>	1,
			));
		
		if ($idPage)
			$f->aWhere(array(
				"id_page"	=>	(int)$idPage,
			));
		else
			$f->aWhere(array(
				"id_user"	=>	(int)User::$id,
			));
		
		return $f->send();
	}
	
}
