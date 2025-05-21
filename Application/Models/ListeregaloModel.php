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

class ListeregaloModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'liste_regalo';
		$this->_idFields = 'id_lista_regalo';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'regali' => array("HAS_MANY", 'ListeregalopagesModel', 'id_lista_regalo', null, "RESTRICT", "La lista non è vuota, eliminare prima gli elementi della lista"),
			'ordini' => array("HAS_MANY", 'OrdiniModel', 'id_lista_regalo', null, "RESTRICT", "Esistono degli ordini collegati alla lista"),
			'link' => array("HAS_MANY", 'ListeregalolinkModel', 'id_lista_regalo', null, "CASCADE"),
			'tipo' => array("BELONGS_TO", 'ListeregalotipiModel', 'id_lista_tipo',null,"CASCADE"),
			'cliente' => array("BELONGS_TO", 'RegusersModel', 'id_user',null,"CASCADE"),
        );
    }
    
	public function setFormStruct($id = 0)
	{
		$record = $this->selectId((int)$id);
		
		$idUser = (!empty($record)) ? $record["id_user"] : 0;
		
		$linkAggiungi = (empty($record)) ? "<a class='iframe link_aggiungi' href='".Url::getRoot()."regusers/form/insert/0?partial=Y&nobuttons=Y'><i class='fa fa-plus-square-o'></i> ".gtext("Crea nuovo")."</a>" : "";
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'id_lista_tipo'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Tipo della lista",
					"options"	=>	ListeregalotipiModel::getSelectTipi($id),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'id_user'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Cliente",
					"options"	=>	$this->selectUtenti($idUser, v("utilizza_ricerca_ajax_su_select_2_clienti")),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'entryAttributes'	=>	array(
						"select2"	=>	VariabiliModel::getUrlAjaxClienti(),
					),
					'wrap'	=>	array(null,null,"$linkAggiungi<div>","</div>"),
				),
				'data_nascita'	=>	array(
					"labelString"	=>	"Data prevista nascita",
				),
				'codice'	=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Verrà creato in automatico")."</div>"
					),
				),
				'nazione'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Nazione di spedizione dei prodotti della lista",
					"options"	=>	array("" => "Seleziona") + $this->selectNazioneNoDefault(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Serve per il calcolo dell'IVA sugli ordini legati a questa lista")."</div>"
					),
				),
			),
		);
	}
    
    public function settaDataScadenza()
    {
		if (isset($this->values["id_lista_tipo"]))
		{
			$numeroGiorni = (int)ListeregalotipiModel::g()->where(array(
				"id_lista_tipo"	=>	(int)$this->values["id_lista_tipo"],
			))->field("giorni_scadenza");
			
			if ($numeroGiorni > 0)
			{
				$date = new DateTime();
				$date->modify("+$numeroGiorni days");
				
				$this->values["data_scadenza"] = $date->format("Y-m-d");
			}
		}
    }
    
    //get a unique cod trans
	public function codiceUnivoco($codice, $numero = 7)
	{
		$res = $this->clear()->where(array("codice"=>sanitizeDb($codice)))->send();
		
		if (count($res) > 0)
		{
			$numero++;
			$nUid = generateString($numero);
			return $this->codiceUnivoco($nUid, $numero);
		}
		
		return $codice;
	}
    
    public function insert()
    {
		if (!isset($this->values["alias"]))
			$this->values["alias"] = "";
		
		$this->checkAliasAll(0, true, false);
		
		$this->values["alias"] = sanitizeAll($this->values["alias"]);
		
		$this->values["creation_time"] = time();
		
		$this->settaDataScadenza();
		
		if (isset($this->values["id_user"]))
		{
			$ruModel = new RegusersModel;
			
			$recordUser = $ruModel->clear()->selectId($this->values["id_user"]);
			
			if (!empty($recordUser))
			{
				if (isset($recordUser["lingua"]) && $recordUser["lingua"])
					$this->setValue("lingua", $recordUser["lingua"]);

				$this->setValue("email", $recordUser["username"]);
				$this->values["nazione"] = $recordUser["nazione"] ? $recordUser["nazione"] : v("nazione_default");
			}
		}
		
		if (parent::insert())
		{
			if (isset($this->values["genitore_1"]) && $this->values["genitore_1"])
				$codice = encodeUrl(str_replace(" ","",$this->values["genitore_1"])).$this->lId;
			else if (isset($this->values["titolo"]) && $this->values["titolo"])
				$codice = encodeUrl(str_replace(" ","",$this->values["titolo"])).$this->lId;
			else
				$codice = generateString(8).$this->lId;
			
			$codice = $this->codiceUnivoco($codice);
			
			$this->sValues(array(
				"codice"	=>	$codice,
			));
			
			$this->pUpdate($this->lId);
			
			$this->processaEventiListaRegalo($this->lId);
		}
    }
    
    public function processaEventiListaRegalo($idLista)
	{
		$record = $this->selectId((int)$idLista);
		
		if (!empty($record) && isset($record["email"]) && $record["email"] && checkMail($record["email"]) && ListeregaloModel::attiva((int)$idLista))
			EventiretargetingModel::processa($idLista, "ListeregaloModel", true);
	}
    
    public function update($id = null, $where = null)
    {
		$this->values["alias"] = "";
		
		$this->checkAliasAll((int)$id, true, false);
		
		return parent::update($id, $where);
    }
    
    public static function listeUtenteModel($idUser = 0, $idLista = 0)
    {
		$model = self::g();
		
		if ($idUser)
			$model->aWhere(array(
				"id_user"	=>	(int)$idUser,
			));
		
		if ($idLista)
			$model->aWhere(array(
				"id_lista_regalo"	=>	(int)$idLista,
			));
		
		return $model;
    }
    
    // Restituisce la nazione della lista o blank
    public static function nazioneListaRegalo($idUser = 0, $idLista = 0)
    {
		if (!$idLista)
			return "";
		
		$res = self::listeUtenteModel($idUser, $idLista)->select("liste_regalo.nazione")->first();
		
		if (!empty($res))
			return $res["liste_regalo"]["nazione"];
		
		return "";
	}
	
    public static function numeroListeUtente($idUser = 0, $idLista = 0)
    {
		return self::listeUtenteModel($idUser, $idLista)->rowNumber();
    }
    
    public static function listeUtenteAttiveModel($idUser = 0, $idLista = 0)
    {
		return self::listeUtenteModel($idUser, $idLista)->aWhere(array(
			"attivo"	=>	"Y",
			"gte"	=>	array(
				"data_scadenza"	=>	date("Y-m-d"),
			),
		));
    }
    
    public static function listeUtente($idUser = 0, $idLista = 0, $soloAttive = true)
    {
		if ($soloAttive)
			$model = self::listeUtenteAttiveModel($idUser, $idLista);
		else
			$model = self::listeUtenteModel($idUser, $idLista);
		
		return $model->toList("id_lista_regalo", "titolo")->send();
    }
    
    public static function scaduta($idLista)
    {
		if (!$idLista)
			return 0;
		
		return self::listeUtenteModel(0, $idLista)->aWhere(array(
			"lt"	=>	array(
				"data_scadenza"	=>	date("Y-m-d"),
			),
		))->rowNumber();
    }
    
    public static function attiva($idLista)
    {
		if (!$idLista)
			return 0;
		
		return self::listeUtenteAttiveModel(0, $idLista)->rowNumber();
    }
    
    public static function numeroProdotti($idLista, $idC = 0)
    {
		$lrp = new ListeregalopagesModel();
		
		$lrp->clear()->select("sum(quantity) as SOMMA")->where(array(
			"id_lista_regalo"	=>	(int)$idLista,
		));
		
		if ($idC)
			$lrp->aWhere(array(
				"id_c"	=>	(int)$idC,
			));
		
		$res = $lrp->send();
		
		if (count($res) > 0)
			return (int)$res[0]["aggregate"]["SOMMA"];
		
		return 0;
    }
    
    public function getProdotti($idLista)
    {
		$lrp = new ListeregalopagesModel();
		
		return $lrp->clear()->select("*")
			->inner(array("pagina"))
			->addJoinTraduzione(null, "contenuti_tradotti", false, (new PagesModel()))
			->inner("categories")->on("categories.id_c = pages.id_c")
			->aWhere(array(
				"liste_regalo_pages.id_lista_regalo"	=>	(int)$idLista,
			))
			->orderBy("liste_regalo_pages.id_lista_regalo_page")
			->send();
    }
    
    public function getProdottiOrdinatiPerFrontend($idLista)
    {
		$lrp = new ListeregalopagesModel();
		
// 		select("if((liste_regalo_pages.quantity-count(righe.id_c)) > 0,0,1) as nn,liste_regalo_pages.*,pages.*,contenuti_tradotti.*,categories.*")
		
		$res = $lrp->clear()->select("*")
			->inner(array("pagina"))
			->left("(select id_c,orders.id_lista_regalo as id_lista from righe inner join orders on righe.id_o = orders.id_o where orders.stato != 'deleted') as r")->on("r.id_c = liste_regalo_pages.id_c and r.id_lista = liste_regalo_pages.id_lista_regalo")
			->addJoinTraduzione(null, "contenuti_tradotti", false, (new PagesModel()))
			->inner("categories")->on("categories.id_c = pages.id_c")
			->aWhere(array(
				"liste_regalo_pages.id_lista_regalo"	=>	(int)$idLista,
			))
			->groupBy("liste_regalo_pages.id_lista_regalo_page")
			->orderBy("if((liste_regalo_pages.quantity-count(r.id_c)) > 0,0,1),liste_regalo_pages.id_lista_regalo_page")
			->send();
		
// 		echo $lrp->getQuery();die();
		
		return $res;
    }
    
    public function getProdottiIds($idLista)
    {
		$prodotti = $this->getProdotti($idLista);
		
		return $this->getList($prodotti, "pages.id_page");
    }
    
    public function getLink($idLista)
    {
		$lrl = new ListeregalolinkModel();
		
		return $lrl->clear()->aWhere(array(
				"id_lista_regalo"	=>	(int)$idLista,
			))->orderBy("id_lista_regalo_link desc")->send();
    }
    
    public static function numeroRegalati($idLista, $idC = 0)
    {
		$res = RigheModel::regalati($idLista, $idC)->select("sum(quantity) as SOMMA")->send();
		
		if (count($res) > 0)
			return (int)$res[0]["aggregate"]["SOMMA"];
		
		return 0;
    }
    
    public static function numeroRimastiDaRegalare($idLista, $idC = 0)
    {
		$numero = (self::numeroProdotti($idLista, $idC) - self::numeroRegalati($idLista, $idC));
		
		if ($numero < 0)
			$numero = 0;
		
		return $numero;
    }
	
	public static function getCookieIdLista()
	{
		if (isset($_COOKIE[v("nome_cookie_id_lista")]))
		{
			$idLista = (int)$_COOKIE[v("nome_cookie_id_lista")];
			
			if (self::attiva($idLista))
			{
				User::$idLista = $idLista;
			}
			else
			{
				setcookie(v("nome_cookie_id_lista"), "", time()-3600,"/");
				unset($_COOKIE[v("nome_cookie_id_lista")]);
			}
		}
	}
	
	public static function unsetCookieIdLista()
	{
		if (isset($_COOKIE[v("nome_cookie_id_lista")]))
		{
			setcookie(v("nome_cookie_id_lista"), "", time()-3600,"/");
			unset($_COOKIE[v("nome_cookie_id_lista")]);
			User::$idLista = 0;
		}
	}
	
    public static function setCookieIdLista($idLista)
    {
		if (self::attiva((int)$idLista))
		{
			User::$idLista = (int)$idLista;
			
			$time = time() + v("tempo_durata_cookie_id_lista");
			
			Cookie::set(v("nome_cookie_id_lista"), User::$idLista, $time, "/", true, 'Lax');
			$_COOKIE[v("nome_cookie_id_lista")] = User::$idLista;
		}
    }
    
    public static function hasIdLista()
    {
		return (v("attiva_liste_regalo") && (int)User::$idLista) ? true : false;
    }
    
    public static function getRigheRegalate($idLista, $idC)
    {
		return RigheModel::regalati($idLista, $idC)->select("orders.*")->groupBy("orders.id_o")->send();
    }
    
    public static function getUrlAlias($idLista)
    {
		$lista = self::g()->selectId((int)$idLista);
		
		if (!empty($lista))
			return v("alias_pagina_lista")."/".$lista["codice"]."/".$lista["alias"].".html";
		
		return "";
    }
    
    public function svuotaData($valore)
    {
		if ($valore == "00-00-0000")
			return "";
		
		return $valore;
    }
    
    public function cliente($record)
	{
		return "<b>".self::getNominativo($record["regusers"])."</b><br />".$record["regusers"]["username"];;
	}
	
	public static function specchietto($idLista, $tag = "<br />", $valiAllaLista = true)
	{
		$res = self::listeUtenteModel(0, (int)$idLista)->select("*")->inner(array("cliente", "tipo"))->first();
		
		$html = "";
		
		if (!empty($res))
		{
			$html .= "<b>".gtext("Titolo").":</b> ".$res["liste_regalo"]["titolo"];
			
			if ($valiAllaLista && ControllersModel::checkAccessoAlController(array("listeregalo")))
				$html .= " <a target='_blank' href='".Url::getRoot()."listeregalo/form/update/".(int)$idLista."' class='label label-primary text-bold'>".gtext("vai alla lista")." <i class='fa fa-arrow-right'></i></a>";
			
			$html .= "$tag<b>".gtext("Tipo").":</b> ".$res["liste_regalo_tipi"]["titolo"];
			$html .= "$tag<b>".gtext("Creatore lista").":</b> ".self::getNominativo($res["regusers"])." (".$res["liste_regalo"]["email"].") ";
			
			if (ControllersModel::checkAccessoAlController(array("regusers")))
				$html .= "<a href='".Url::getRoot()."regusers/form/update/".(int)$res["regusers"]["id_user"]."?partial=Y&nobuttons=Y' class='iframe label label-info text-bold'><i class='fa fa-user'></i> ".gtext("dettagli utente")."</a>";
		}
		
		return $html;
	}
	
	public function ordini($idLista, $idC = 0)
	{
		$idLista = (int)$idLista;
		$idC = (int)$idC;
		
		$r = new RigheModel();
		
		$res = $r->clear()->inner("orders")->on("orders.id_o = righe.id_o")->select("sum(righe.quantity) as SOMMA")->where(array(
			"id_c"	=>	$idC,
			"orders.id_lista_regalo"	=>	$idLista,
// 			"ne" => array(
// 				"orders.stato"	=>	"deleted"
// 			),
		))->send();
		
		if (count($res) > 0 && $res[0]["aggregate"]["SOMMA"] > 0)
		{
// 			if (!isset($_GET["esporta"]))
				return $res[0]["aggregate"]["SOMMA"]." <a title='Elenco ordini dove è stato acquistato' class='iframe' href='".Url::getRoot()."ordini/main?partial=Y&id_lista_regalo=$idLista&id_comb=$idC'><i class='fa fa-list'></i></a>";
// 			else
// 				return $res[0]["aggregate"]["SOMMA"];
		}
		
		return "";
	}
	
	public function filtroListe()
	{
		$res = $this->clear()->orderBy("titolo")->send(false);
		
		$arrayFiltro = array();
		
		foreach ($res as $r)
		{
			$arrayFiltro[$r["id_lista_regalo"]] = $r["titolo"]." - ".$r["codice"];
		}
		
		return $arrayFiltro;
	}
	
	public function getNominativoLista($idLista)
	{
		$record = $this->selectId((int)$idLista);
		
		if (!empty($record))
			return $record["genitore_1"] ? $record["genitore_1"] : $record["nominativo"];
		
		return "";
	}
	
	public function gNominativoLista($lingua, $record)
	{
		return $record["genitore_1"] ? $record["genitore_1"] : $record["nominativo"];
	}
	
	public function gCodiceLista($lingua, $record)
	{
		return $record["codice"];
	}
	
	public static function sincronizzaEmailConAccount($idUser)
	{
		$ru = new RegusersModel();
		
		$record = $ru->clear()->selectId((int)$idUser);
		
		if (!empty($record))
		{
			$lr = new ListeregaloModel();
			
			$lr->query(array(
				"update liste_regalo set email = ? where id_user = ?",
				array(
					sanitizeAll($record["username"]),
					(int)$idUser,
				),
			));
		}
	}
	
	public static function getStatiLista($idLista)
	{
		$se = new StatielementiModel();
		
		return $se->clear()->where(array(
			"tabella_rif"	=>	"liste_regalo_pages",
		))->sWhere(array(
			"id_rif in (select id_lista_regalo_page from liste_regalo_pages where id_lista_regalo = ?)",
			array((int)$idLista)
		))->send(false);
	}
	
	// Restituisce i prodotti non più acquistabili
	public static function prodottiInListaNonPiuAcquistabili($ultimiXgiorni = 90, $soloMaiInviato = true)
	{
		$createDopoIl = new DateTime(date("Y-m-d"));
		$createDopoIl->modify("-$ultimiXgiorni days");
		
		$combModel = new CombinazioniModel();
		
		$idCnonAttive = $combModel->clear()->inner(array("pagina"))->where(array(
			"acquistabile"	=>	0,
			"pages.attivo"	=>	"N",
		))->toList("id_c")->send();
		
		$model = self::listeUtenteAttiveModel();
		
		$model->select("distinct liste_regalo.id_lista_regalo,liste_regalo.lingua,liste_regalo.titolo,liste_regalo.codice,liste_regalo.email,liste_regalo.data_creazione,liste_regalo.id_user,r.quantita_comprata,liste_regalo_pages.quantity,liste_regalo_pages.id_page,liste_regalo_pages.id_c,pages.title,regusers.*,liste_regalo_pages.id_lista_regalo_page")
			->inner(array("regali"))
			->inner("pages")->on("pages.id_page = liste_regalo_pages.id_page")
			->inner("regusers")->on("liste_regalo.id_user = regusers.id_user")
			->left("(select id_c,sum(quantity) as quantita_comprata,orders.id_lista_regalo as id_lista from righe inner join orders on righe.id_o = orders.id_o where orders.stato != 'deleted' and orders.id_lista_regalo != 0 group by righe.id_c,orders.id_lista_regalo) as r")->on("r.id_c = liste_regalo_pages.id_c and r.id_lista = liste_regalo_pages.id_lista_regalo")
			->sWhere("liste_regalo_pages.id_c in (select combinazioni.id_c from combinazioni inner join pages on combinazioni.id_page = pages.id_page where combinazioni.acquistabile = 0 or pages.attivo = 'N')")
			->sWhere("liste_regalo_pages.quantity > coalesce(r.quantita_comprata,0)")
			->orderBy("liste_regalo.id_lista_regalo desc")
			->sWhere(array(
				"date_format(liste_regalo.data_creazione,'%Y-%m-%d') >= ?",
				array(sanitizeAll($createDopoIl->format("Y-m-d")))
			));
		
		if ($soloMaiInviato)
			$model->sWhere("liste_regalo_pages.numero_avvisi = 0");
		
		$listeConProdottiNonAcquistabili = $model->send();
		
		$struttura = array();
		
		foreach ($listeConProdottiNonAcquistabili as $el)
		{
			$idLista = $el["liste_regalo"]["id_lista_regalo"];
			
			$page = PagesModel::getPageDetails($el["liste_regalo_pages"]["id_page"], $el["liste_regalo"]["lingua"]);
			
			if (!empty($page))
				$titoloprodotto = field($page, "title");
			else
				$titoloprodotto = $el["pages"]["title"];
			
			TraduzioniModel::sLingua($el["liste_regalo"]["lingua"], "front");
			$attributi = CombinazioniModel::g()->getStringa($el["liste_regalo_pages"]["id_c"], "<br />", false);
			TraduzioniModel::rLingua();
			
			if ($attributi)
				$titoloprodotto .= " ".$attributi;
			
			$str = array(
				"PRODOTTO"				=>	$titoloprodotto,
				"id_page"				=>	$el["liste_regalo_pages"]["id_page"],
				"id_c"					=>	$el["liste_regalo_pages"]["id_c"],
				"id_lista_regalo_page"	=>	$el["liste_regalo_pages"]["id_lista_regalo_page"],
			);
			
			if (isset($struttura[$idLista]))
				$struttura[$idLista]["prodotti"][] = $str;
			else
			{
				$struttura[$idLista]["lista"] = array(
					"NOME_CREATORE_LISTA"	=>	self::getNominativo($el["regusers"]),
					"EMAIL_CREATORE_LISTA"	=>	$el["regusers"]["username"],
					"TITOLO_LISTA"			=>	$el["liste_regalo"]["titolo"],
					"CODICE_LISTA"			=>	$el["liste_regalo"]["codice"],
					"LINK_LISTA"	=>	Domain::$publicUrl."/".$el["regusers"]["lingua"].F::getNazioneUrl($el["regusers"]["nazione_navigazione"])."/".ListeregaloModel::getUrlAlias($el["liste_regalo"]["id_lista_regalo"]),
					"data_creazione"	=>	$el["liste_regalo"]["data_creazione"],
					"id_user"			=>	$el["liste_regalo"]["id_user"],
					"email_in_lista"	=>	$el["liste_regalo"]["email"],
					"lingua"			=>	$el["liste_regalo"]["lingua"],
					"LINK_AREA_RISERVATA"	=>	Domain::$publicUrl."/".$el["regusers"]["lingua"].F::getNazioneUrl($el["regusers"]["nazione_navigazione"])."/area-riservata",
				);
				
				$struttura[$idLista]["prodotti"] = array($str);
			}
		}
			
		// echo $model->getQuery()."\n";
		
		return $struttura;
	}
	
	public static function inviaAvvisoProdottiNonPiuAcquistabili($liste, $log = null)
    {
		$lrpModel = new ListeregalopagesModel();
		
		foreach ($liste as $lista)
		{
			$prodottiHtml = "<ul>";
			
			foreach ($lista["prodotti"] as $prodotto)
			{
				$prodottiHtml .= "<li>".$prodotto["PRODOTTO"]."</li>";
			}
			
			$prodottiHtml .= "</ul>";
			
			$res = MailordiniModel::inviaMail(array(
				"emails"	=>	array($lista["lista"]["EMAIL_CREATORE_LISTA"]),
				"oggetto"	=>	"Alcuni prodotti nella tua lista non sono più acquistabili",
				"tipologia"	=>	"AVVISO LISTA PRODOTTI",
				"testo_path"	=>	"Elementi/Mail/ListeRegalo/mail_avviso_prodotti_lista_regalo_non_piu_acquistabili.php",
				"lingua"	=>	$lista["lista"]["lingua"],
				"array_variabili_tema"	=>	array(
					"NOME_CREATORE_LISTA"	=>	$lista["lista"]["NOME_CREATORE_LISTA"],
					"TITOLO_LISTA"	=>	$lista["lista"]["TITOLO_LISTA"],
					"EMAIL_CREATORE_LISTA"	=>	$lista["lista"]["NOME_CREATORE_LISTA"],
					"CODICE_LISTA"	=>	$lista["lista"]["CODICE_LISTA"],
					"LINK_LISTA"	=>	$lista["lista"]["LINK_LISTA"],
					"ELENCO_PRODOTTI"	=>	$prodottiHtml,
					"LINK_AREA_RISERVATA"	=>	$lista["lista"]["LINK_AREA_RISERVATA"],
				),
			));
			
			if ($res)
			{
				$log->writeString("Mandato avviso alla mail ".$lista["lista"]["EMAIL_CREATORE_LISTA"].", lista ".$lista["lista"]["TITOLO_LISTA"].", per i seguenti prodotti:");
				
				foreach ($lista["prodotti"] as $prodotto)
				{
					$idListaPage = $prodotto["id_lista_regalo_page"];
					
					$numeroInviati = (int)$lrpModel->clear()->select("numero_avvisi")->whereId((int)$idListaPage)->field("numero_avvisi");
					$numeroInviati = $numeroInviati + 1;
					
					$lrpModel->sValues(array(
						"numero_avvisi"	=>	$numeroInviati,
					));
					
					$lrpModel->update((int)$idListaPage);
					
					if ($log)
						$log->writeString("Prodotto: ".strip_tags($prodotto["PRODOTTO"])." - ID PAGE:".$prodotto["id_page"]." - ID COMB:".$prodotto["id_c"]." - ID PAGE IN LISTA:".$prodotto["id_lista_regalo_page"]);
				}
				
			}
		}
		
		return true;
    }
}
