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

class FeedbackModel extends GenericModel {
	
	public $campoTitolo = "autore";
	
	public static $sValues = array();
	public static $sNotice = null;
	public static $idProdotto = 0;
	public static $idCombinazione = 0;
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
	
	public function relations() {
        return array(
			'pagina' => array("BELONGS_TO", 'PagineModel', 'id_page',null,"CASCADE","Si prega di selezionare la pagina"),
			'utente' => array("BELONGS_TO", 'RegusersModel', 'id_user',null,"CASCADE","Si prega di selezionare la pagina"),
        );
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
				'testo'	=>	array(
					'type'		=>	'Textarea',
					'className'		=>	'testo_feedback',
				),
				'commento_negozio'	=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Inserisci un commento al feedback. Apparirà sotto al feedback del cliente.")."</div>"
					),
				),
				'id_page'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Prodotto',
					'options'	=>	$this->selectProdotti($id),
					'reverse' => 'yes',
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
					'wrap'		=>	array(
						null,
						null,
						$this->getTitoloFeedback($id)
					),
				),
			),
		);
	}
	
	public static function getCurrUrlIdRif($char = "?")
	{
		if (FeedbackModel::$idProdotto)
			return $char.v("var_query_string_id_rif")."=".(int)FeedbackModel::$idProdotto;
		
		return "";
	}
	
	public function getTitoloFeedback($id)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && isset($record["titolo"]))
			return "<div class='form_notice'>".gtext("Prodotto").": ".$record["titolo"]."</div>";
		
		return "";
	}
	
	public function selectProdotti($id)
	{
		$p = new PagesModel();
		
		return $p->selectProdotti($id);
	}
	
	public static function gIdProdotto()
	{
		self::$idProdotto = isset($_GET[v("var_query_string_id_rif")]) ? (int)$_GET[v("var_query_string_id_rif")] : 0;
		
		return self::$idProdotto;
	}
	
	public static function gIdCombinazione()
	{
		self::$idCombinazione = isset($_GET[v("var_query_string_id_comb")]) ? (int)$_GET[v("var_query_string_id_comb")] : 0;
		
		return self::$idCombinazione;
	}
	
	public static function gDatiProdotto()
	{
		self::$datiProdotto = PagesModel::getPageDetails(self::gIdProdotto());
		
		$idCombinazione = self::gIdCombinazione();
		
		if (self::$datiProdotto)
		{
			PagesModel::$IdCombinazione = $idCombinazione;
			$pages = PagesModel::impostaDatiCombinazionePagine(array(self::$datiProdotto));
			self::$datiProdotto = $pages[0];
		}
		
		return self::$datiProdotto;
	}
	
	public static function gValue($key)
	{
		if (isset(self::$sValues[$key]))
			return self::$sValues[$key];
		
		return "";
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
		
		$res = parent::insert();
		
		if ($res)
			$this->aggiungiNotifica();
		
		return $res;
	}
	
	public function numeroFeedbackPagina($idPage)
	{
		return $this->aWhere(array(
			"id_page"	=>	(int)$idPage,
			"id_user"	=>	User::$id,
		))->rowNumber();
	}
	
	public function aggiungiNotifica()
	{
		if (v("permetti_aggiunta_feedback") && App::$isFrontend && isset($this->values["id_user"]) && isset($this->values["is_admin"]) && !$this->values["is_admin"] && isset($this->values["id_page"]))
		{
			$n = new NotificheModel();
			
			$pagina = PagesModel::getPageDetails($this->values["id_page"]);
			
			if (!empty($pagina))
			{
				$n->setValues(array(
					"titolo"	=>	"Hai un nuovo feedback nella pagina<br /><b>".$pagina["pages"]["title"]."</b>",
					"contesto"	=>	"FEEDBACK",
					"url"		=>	"prodotti/feedback/".$pagina["pages"]["id_page"],
					"classe"	=>	"text-yellow",
					"icona"		=>	"fa-comment",
					"condizioni"=>	"abilita_feedback=1",
				));
				
				$n->insert();
			}
		}
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
		
		$linguaUrl = v("attiva_nazione_nell_url") ? $record["lingua"]."_".strtolower($record["nazione_navigazione"]) : $record["lingua"];
		
		$res = MailordiniModel::inviaMail(array(
			"emails"	=>	array($record["email"]),
			"oggetto"	=>	$oggetto,
			"tipologia"	=>	"FEEDBACK_APPR_CLIENTE",
			"id_page"	=>	(int)$record["id_page"],
			"lingua"	=>	$record["lingua"],
			"testo_path"	=>	$testoPath,
			"array_variabili_tema"	=>	array(
				"LINK_PRODOTTO"	=>	Domain::$publicUrl."/".$linguaUrl."/".$p->getUrlAlias($pagina["pages"]["id_page"], $record["lingua"], $record["id_c"]),
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
		$this->values["nazione_navigazione"] = sanitizeAll(User::getNazioneNavigazione());
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
	
	public function collega($record)
	{
		if (!$record["feedback"]["is_admin"])
			return $record["feedback"]["autore"];
		else
			return "<a class='iframe action_iframe' href='".Url::getRoot()."feedback/form/update/".$record["feedback"]["id_feedback"]."?partial=Y&nobuttons=Y&collega=Y'>".$record["feedback"]["autore"]."</a>";
	}
	
	public function editutente($record)
	{
		if ($record["feedback"]["email"])
		{
			if ($record["regusers"]["username"])
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
		$orderBy = v("permetti_aggiunta_feedback") ? "feedback.data_feedback desc,feedback.id_order desc" :"feedback.id_order";
		
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
	
	public function prodottoCrud($record)
	{
		if ($record["pages"]["id_page"])
			return "<a target='_blank' href='".Url::getRoot()."/prodotti/form/update/".$record["pages"]["id_page"]."'>".$record["pages"]["title"]."</a>";
		else if (isset($record["feedback"]["titolo"]))
			return "<i class='text text-danger fa fa-exclamation-circle'></i> ".$record["feedback"]["titolo"];
		
		return "";
	}
}
