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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!defined('EG')) die('Direct access not allowed!');

class OrdiniModel extends FormModel {
	
	public $campoTimeEventoRemarketing = "time_pagamento";
	public $baseUrl;
	
	public $campoTitolo = "id_o";
	
	public static $ordineImportato = false;
	
	public static $pagamentiSettati = false;
	public static $statiOrdineSettati = false;
	
	public static $stati = array(
		"pending"	=>	"Ordine ricevuto",
		"completed"	=>	"Ordine pagato e in lavorazione",
		"closed"	=>	"Ordine completato e spedito",
		"deleted"	=>	"Ordine annullato",
	);
	
	public static $statiPending = array("pending"); // array di stati considerati "pending" e che permettono al cliente di pagare online
	
	public static $labelTipoOrdine = array(
		"W"	=>	"Web",
		"B"	=>	"Backend",
	);
	
	public static $pagamenti = array();
	public static $pagamentiFull = array();
	
	public static $elencoPagamenti = array(
		"bonifico"		=>	"Bonifico bancario",
		"contrassegno"	=>	"Contrassegno (pagamento alla consegna)",
		"paypal"		=>	"Pagamento online tramite PayPal",
		"carta_di_credito"	=>	"Pagamento online tramite carta di credito",
	);
	
	public static $labelStati = array(
		"pending"	=>	"default",
		"completed"	=>	"primary",
		"closed"	=>	"success",
		"deleted"	=>	"danger",
	);
	
	public static $isDeletable = null;
	
	public static $colonneAggiuntiveRighe = array();
	
	public static function getWhereClausePagato()
	{
		return "(orders.stato in (select codice from stati_ordine where stati_ordine.attivo = 1 AND stati_ordine.pagato = 1) OR (orders.pagato = 1 AND orders.stato in (select codice from stati_ordine where stati_ordine.attivo = 1 AND stati_ordine.pagato = -1)))";
	}
	
	public static function isPagato($idO)
	{
		$o = new OrdiniModel();
		
		$record = $o->selectId($idO);
		
		if (!empty($record))
		{
			if (StatiordineModel::g(false)->pagato($record["stato"]) || (StatiordineModel::g(false)->neutro($record["stato"]) && $record["pagato"]))
				return true;
		}
		
// 		if (!empty($record) && ($record["stato"] == "completed" || $record["stato"] == "closed"))
// 			return true;
		
		return false;
	}
	
	// Se l'ordine è da spedire. Controlla da_spedire e lo stato (da fare)
	public static function daSpedire($idO)
	{
		$o = new OrdiniModel();
		
		$record = $o->selectId((int)$idO);
		
		if (!empty($record))
		{
			if (!$record["da_spedire"] && !$record["id_lista_regalo"])
				return false;
			
			if (!StatiordineModel::getCampo($record["stato"], "da_spedire"))
				return false;
		}
		
		return true;
	}
	
	// Mostra info della spedizione nell'elenco ordini
	public function spedizioneCrud($record)
	{
		$sp = new SpedizioninegozioModel();
		
		return $sp->badgeSpedizione($record["orders"]["id_o"], 0, false);
	}
	
	// Restituisce le righe ancora da spedire
	public static function righeDaSpedire($idO)
	{
		if (!self::daSpedire($idO))
			return array();
		
		$rModel = new RigheModel();
		
		$res =  $rModel->clear()->select("righe.*,sum(rr.quantity) as QTA_ORDINATA,rr.id_spedizione_negozio_riga")
			->left("(select id_spedizione_negozio_riga,id_r,quantity from spedizioni_negozio_righe inner join spedizioni_negozio on spedizioni_negozio.id_spedizione_negozio = spedizioni_negozio_righe.id_spedizione_negozio where spedizioni_negozio.stato != 'E') as rr")->on("rr.id_r = righe.id_r")
			->sWhere(array(
				"righe.gift_card = 0 and righe.prodotto_digitale = 0 and righe.prodotto_crediti = 0 and righe.id_o = ?",
				array((int)$idO)
			))->groupBy("righe.id_r HAVING (righe.quantity > QTA_ORDINATA or rr.id_spedizione_negozio_riga IS NULL)")->send(false);
		
// 		echo $rModel->getQuery();
		
		return $res;
	}
	
	// Restituisce tutte le righe dell'ordine che sono in spedizioni non ancora confermate
	public static function righeInSpedizione($idO)
	{
		if (!self::daSpedire($idO))
			return array();
		
		$rModel = new RigheModel();
		
		return $rModel->clear()->select("righe.id_r")
			->inner("spedizioni_negozio_righe")->on("spedizioni_negozio_righe.id_r = righe.id_r")
			->inner("spedizioni_negozio")->on("spedizioni_negozio.id_spedizione_negozio = spedizioni_negozio_righe.id_spedizione_negozio")
			->where(array(
				"righe.id_o"	=>	(int)$idO,
				"in"	=>	array(
					"spedizioni_negozio.stato"	=>	SpedizioninegozioModel::statiSpedizioniNonInviate(),
				),
			))->toList("righe.id_r")->send();
	}
	
	public static function setStatiOrdine()
	{
		if (self::$statiOrdineSettati)
			return;
		
		if (empty(VariabiliModel::$valori))
			VariabiliModel::ottieniVariabili();
		
		$s = new StatiordineModel();
		
		$res = $s->clear()->orderBy("id_order")->addJoinTraduzione()->send();
		
		self::$stati = array();
		
		if (v("attiva_gestione_stati_pending"))
			self::$statiPending = array();
		
		foreach ($res as $stato)
		{
			// Se nuovo o vecchio sistema
			$titoloPag = v("attiva_gestione_stati_ordine") ? sofield($stato, "titolo") : gtext($stato["stati_ordine"]["titolo"], false);
			
			self::$stati[$stato["stati_ordine"]["codice"]] = $titoloPag;
			self::$labelStati[$stato["stati_ordine"]["codice"]] = $stato["stati_ordine"]["classe"];
			
			if (v("attiva_gestione_stati_pending") && $stato["stati_ordine"]["stato_pending"])
				self::$statiPending[] = $stato["stati_ordine"]["codice"];
		}
		
		self::$statiOrdineSettati = true;
	}
	
	// Restituisci la classe/label dello stato
	public static function getLabelStato($stato)
	{
		if (isset(self::$labelStati[$stato]))
			return self::$labelStati[$stato];
		
		return "";
	}
	
	public static function getPagamentiPermessi($nazione)
	{
		return implode(",", self::getPagamentiRes($nazione, true));
	}
	
	public static function getPagamentiRes($nazione = "IT", $soloCodice = false)
	{
		$p = new PagamentiModel();
		
		$p->clear()->where(array(
			"attivo"	=>	1
		))->orderBy("id_order")->addJoinTraduzione();
		
		if (App::$isFrontend && (CartModel::soloProdottiSenzaSpedizione() || (!v("permetti_pagamento_contrassegno_fuori_nazione_default") && strtoupper($nazione) != v("nazione_default"))))
			$p->aWhere(array(
				"ne"	=>	array(
					"pagamenti.codice"	=>	"contrassegno",
				),
			));
		
		if (App::$isFrontend)
			$p->aWhere(array(
				"in"	=>	array(
					"pagamenti.utilizzo"	=>	array("W", "E"),
				),
			));
		
		if ($soloCodice)
			$p->toList("codice");
		
		return $p->send();
	}
	
	public static function setPagamenti($nazione = "IT")
	{
		if (self::$pagamentiSettati)
			return;
		
		if (empty(VariabiliModel::$valori))
			VariabiliModel::ottieniVariabili();
		
		$res = self::getPagamentiRes($nazione);
		
		self::$elencoPagamenti = array();
		
		foreach ($res as $pag)
		{
			// Se nuovo o vecchio sistema
			$titoloPag = v("attiva_gestione_pagamenti") ? pfield($pag, "titolo") : gtext($pag["pagamenti"]["titolo"], false);
			
			self::$pagamenti[$pag["pagamenti"]["codice"]] = self::$elencoPagamenti[$pag["pagamenti"]["codice"]] = $titoloPag;
			self::$pagamentiFull[$pag["pagamenti"]["codice"]] = $pag;
		}
		
		VariabiliModel::$valori["pagamenti_permessi"] = Parametri::$metodiPagamento = implode(",", array_keys(self::$elencoPagamenti));
		
		self::$pagamentiSettati = true;
	}
	
	public static function getImmaginePagamento($codice)
	{
		if (isset(self::$pagamentiFull[$codice]))
		{
			return self::$pagamentiFull[$codice]["pagamenti"]["immagine"];
		}
		
		return "";
	}
	
	public static function statiSuccessivi($stato)
	{
		$query = StatiordineModel::g(false)->select("codice,manda_mail_al_cambio_stato,descrizione")->where(array(
			"ne"	=>	array(
				"codice"	=>	$stato
			),
		))->orderBy("id_order");
		
		if (!v("attiva_spedizione") && !v("mostra_sempre_stato_closed"))
			$query->sWhere("codice != 'closed'");
		
		return $query->findAll(false);
	}
	
	public static function getTipoMail($tipo)
	{
		switch ($tipo)
		{
			case "A":
				return "Mail ordine annullato";
			case "F":
				return "Mail invio fattura";
			case "P":
				return "Mail pagamento avvenuto correttamente";
			case "C":
				return "Mail ordine consegnato";
			case "R":
				return "Mail ordine ricevuto";
			case "G":
				return "Mail ordine stato personalizzato";
			default:
				return "--";
		}
	}
	
	public $cart_uid = null;
	public $lId = null;
	
	protected $nomeCampoIdUser = "id_agente";
	
	public function __construct() {
		$this->_tables='orders';
		$this->_idFields='id_o';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'orders.id_order';
		$this->_lang = 'It';
		
// 		$this->_popupItemNames = array(
// 			'tipo_cliente'	=>	'tipo_cliente',
// 			'stato'	=>	'stato',
// 			'pagamento'	=>	'pagamento',
// 			'registrato'	=> 'registrato',
// 		);
// 
// 		$this->_popupLabels = array(
// 			'stato'	=>	'STATO ORDINE',
// 			'tipo_cliente'	=>	'TIPO CLIENTE',
// 			'pagamento'	=>	'PAGAMENTO',
// 			'registrato'	=> 'UTENTE REGISTRATO',
// 		);
// 
// 		$this->_popupFunctions = array(
// 			'stato'	=>	'statoOrdine',
// 			'pagamento'	=>	'metodoPagamento',
// 			'registrato'	=> 'getYesNo',
// 		);
		
		parent::__construct();
		
		if (!v("attiva_gestione_stati_ordine"))
			self::$stati = array(
				"pending"	=>	gtext("Ordine ricevuto", false),
				"completed"	=>	gtext("Ordine pagato e in lavorazione", false),
				"closed"	=>	gtext("Ordine completato e spedito", false),
				"deleted"	=>	gtext("Ordine annullato", false),
			);
		
		if (!v("attiva_spedizione") && !v("mostra_sempre_stato_closed"))
			unset(self::$stati["closed"]);
		
		if (!App::$isFrontend)
		{
			self::setStatiOrdine();
			self::setPagamenti();
		}
		
// 		$pagamentiPermessi = explode(",", v("pagamenti_permessi"));
// 		
// 		foreach (self::$elencoPagamenti as $k => $v)
// 		{
// 			if (in_array($k, $pagamentiPermessi))
// 				self::$pagamenti[$k] = gtext($v, false);
// 		}
	}
	
	public function relations() {
        return array(
			'righe' => array("HAS_MANY", 'RigheModel', 'id_o', null, "RESTRICT", "L'elemento ha delle righe associate e non può essere eliminato"),
			'fatture' => array("HAS_MANY", 'FattureModel', 'id_o', null, "RESTRICT", "L'elemento ha delle fatture associate e non può essere eliminato"),
			'pages' => array("HAS_MANY", 'MailordiniModel', 'id_o', null, "CASCADE"),
			'lista' => array("BELONGS_TO", 'ListeregaloModel', 'id_lista_regalo',null,"CASCADE"),
        );
    }
    
    public static function getLabelTipoOrdine($tipo)
    {
		return isset(self::$labelTipoOrdine[$tipo]) ? self::$labelTipoOrdine[$tipo] : $tipo;
    }
    
	public function getAdminToken($id_order, $cart_uid)
	{
		$clean["cart_uid"] = sanitizeAll($cart_uid);
		$clean["id_order"] = (int)$id_order;
		
		$res = $this->clear()->where(array("id_o" => $clean["id_order"], "cart_uid" => $clean["cart_uid"] ))->send();
		
		if (count($res) > 0)
		{
			return $res[0]["orders"]["admin_token"];
		}
		
		return "token";
	}
	
	//restituisci il codice fiscale o la partita iva
	public function getCFoPIva($id_o)
	{
		$clean["id_o"] = (int)$id_o;
		
		$res = $this->clear()->where(array("id_o" => $clean["id_o"]))->send();
		
		if (count($res) > 0)
		{
			if (strcmp($res[0]["orders"]["tipo_cliente"],"privato") === 0)
			{
				return "CF: ".$res[0]["orders"]["codice_fiscale"];
			}
			else
			{
				return "P.IVA: ".$res[0]["orders"]["p_iva"];
			}
		}
		
		return "";
	}
	
	//restituisci il nome del cliente dell'ordine (nome cognome o ragione sociale)
	public function getNome($id_o)
	{
		$clean["id_o"] = (int)$id_o;
		
		$res = $this->clear()->where(array("id_o" => $clean["id_o"]))->send();
		
		if (count($res) > 0)
		{
			if (strcmp($res[0]["orders"]["tipo_cliente"],"privato") === 0 || strcmp($res[0]["orders"]["tipo_cliente"],"libero_professionista") === 0)
			{
				return $res[0]["orders"]["nome"]." ".$res[0]["orders"]["cognome"];
			}
			else
			{
				return $res[0]["orders"]["ragione_sociale"];
			}
		}
		
		return "";
	}
	
	//get a unique uid
	public function getUniqueId($uid)
	{
		$clean["uid"] = sanitizeAll($uid);
		
		$res = $this->clear()->where(array("cart_uid"=>$clean["uid"]))->send();
		
		if (count($res) > 0)
		{
			$nUid = md5(randString(10).microtime().uniqid(mt_rand(),true));
			return $this->getUniqueId($nUid);
		}
		
		$this->cart_uid = $clean["uid"];
		return $clean["uid"];
	}
	
	//get a unique cod trans
	public function getUniqueCodTrans($uid)
	{
		$clean["uid"] = sanitizeAll($uid);
		
		$res = $this->clear()->where(array("codice_transazione"=>$clean["uid"]))->send();
		
		if (count($res) > 0)
		{
			$nUid = generateString(30);
			return $this->getUniqueCodTrans($nUid);
		}
		
		return $clean["uid"];
	}
	
	public function resettaCodiceTransazione($idOrdine)
	{
		$ordine = $this->selectId((int)$idOrdine);
		
		if (!empty($ordine))
		{
			$this->sValues(array(
				"codice_transazione"	=>	$this->getUniqueCodTrans(generateString(30)),
			));
			
			$this->pUpdate((int)$idOrdine);
		}
	}
	
	public function setAliquotaIva($idOrdine = 0)
	{
// 		if (!App::$isFrontend)
// 		{
// 			if (!$idOrdine || OrdiniModel::tipoOrdine($idOrdine) != "W")
// 			{
// 				if (isset($this->values["id_iva"]))
// 					$this->values["iva_spedizione"] = IvaModel::g(false)->getValore((int)$this->values["id_iva"]);
// 			}
// 		}
	}
	
	public function setPagato($id = 0)
	{
		if (!OrdiniModel::$ordineImportato && isset($this->values["stato"]))
		{
			$record = $this->selectId((int)$id);
			
			if (StatiordineModel::g(false)->pagato($this->values["stato"]))
			{
				if (empty($record) || !$record["pagato"])
				{
					$this->values["pagato"] = 1;
					$this->values["data_pagamento"] = date("Y-m-d H:i");
					$this->values["time_pagamento"] = time();
				}
			}
			else if (!empty($record) && !$record["evaso_non_evaso"])
			{
				if (StatiordineModel::g(false)->rimborsato($this->values["stato"]))
				{
					if (!$record["rimborsato"])
					{
						$this->values["rimborsato"] = 1;
						$this->values["data_rimborso"] = date("Y-m-d H:i");
						$this->values["time_rimborso"] = time();
					}
				}
				else if (StatiordineModel::g(false)->nonpagato($this->values["stato"]))
				{
					if (!$record["annullato"] && $record["stato"] != $this->values["stato"])
					{
						$this->values["annullato"] = 1;
						$this->values["data_annullamento"] = date("Y-m-d H:i");
						$this->values["time_annullamento"] = time();
					}
				}
			}
		}
	}
	
	public function setBackend()
	{
		$this->values["tipo_ordine"] = "B";
		$this->values["fonte"] = "ORDINE_NEGOZIO";
	}
	
	public function setOrdineWeb()
	{
		$this->values["tipo_ordine"] = "W";
		$this->values["fonte"] = "ORDINE_WEB";
	}
	
	public function insert()
	{
		if (!self::$ordineImportato)
		{
			if (App::$isFrontend)
				$this->values["cart_uid"] = $this->getUniqueId($this->values["cart_uid"]);
			else
				$this->values["cart_uid"] = $this->getUniqueId(randomToken());
		}
		
		if (!self::$ordineImportato)
			$this->values["codice_transazione"] = $this->getUniqueCodTrans(generateString(30));
		
		if (!isset($this->values["lingua"]))
			$this->values["lingua"] = Params::$lang;
		
		if (!User::$nazioneNavigazione)
			User::$nazioneNavigazione = isset(Params::$country) ? strtoupper(Params::$country) : v("nazione_default");
		
		if (!isset($this->values["nazione_navigazione"]))
			$this->values["nazione_navigazione"] = User::$nazioneNavigazione;
		
		$checkFiscale = v("abilita_codice_fiscale");
		
		// Se non c'è la spedizione attiva
		if (!v("attiva_spedizione"))
		{
			$this->values["id_spedizione"] = 0;
			$this->values["id_corriere"] = 0;
		}
		
		if (!App::$isFrontend && !self::$ordineImportato)
		{
			$this->setBackend();
			
			$this->values["id_admin"] = (int)User::$id;
		}
		
		if (v("usa_transactions"))
			$this->db->beginTransaction();
		
		if (!OrdiniModel::$ordineImportato)
		{
			$this->setAliquotaIva();
			
			$this->setProvince();
			
			$this->setPagato();
			
			$this->sistemaMaiuscole();
			
			$this->setNumeroDocumento();
		}
		
		if (!App::$isFrontend || ($this->controllaCF($checkFiscale) && $this->controllaPIva()) || self::$ordineImportato)
		{
			$res = parent::insert();
			
			if (!App::$isFrontend && $res)
			{
				$this->aggiornaTotali($this->lId);
				
				if (v("crea_sincronizza_cliente_in_ordini_offline"))
					$this->creaSincronizzaClienteSeAssente($this->lId);
			}
			
			if (v("usa_transactions"))
				$this->db->commit();
			
			return $res;
		}
		
		if (v("usa_transactions"))
			$this->db->commit();
		
		return false;
	}
	
	// Crea e sincronizza il cliente se assente, partendo dalla email dell'ordine
	public function creaSincronizzaClienteSeAssente($idOrdine)
	{
		if (!App::$isFrontend && OrdiniModel::tipoOrdine($idOrdine) != "W" && OrdiniModel::isDeletable($idOrdine) && !OrdiniModel::$ordineImportato)
		{
			$ordine = $this->selectId((int)$idOrdine);
			
			if (!empty($ordine) && !$ordine["id_user"] && $ordine["email"] && checkMail(trim($ordine["email"])))
			{
				$ruModel = new RegusersModel();
				
				$cliente = $ruModel->clear()->where(array(
					"username"	=>	sanitizeAll($ordine["email"]),
				))->record();

				$idCliente = 0;
				
				if (empty($cliente))
				{
					$ruModel->sValues(array(
						"username"	=>	$ordine["email"],
						"password"	=>	self::generaPassword(),
						"has_confirmed"	=>	0,
						"nazione_navigazione"	=>	$ordine["nazione"] ? $ordine["nazione"] : v("nazione_default"),
					));
					
					if ($ruModel->insert())
						$idCliente = $ruModel->lId;
				}
				else
					$idCliente = $cliente["id_user"];
				
				if ($idCliente)
				{
					$this->sValues(array(
						"id_user"		=>	(int)$idCliente,
						"registrato"	=>	"Y",
					));
					
					if ($this->pUpdate((int)$idOrdine))
						$ruModel->sincronizzaDaOrdine($idCliente, $idOrdine);
					
					$ordine["id_user"] = (int)$idCliente;
				}
			}
			
			// Sincronizzazione spedizione
			if (!empty($ordine) && $ordine["id_user"] && $ordine["indirizzo_spedizione"] && ($ordine["citta_spedizione"] || $ordine["cap_spedizione"] || $ordine["telefono_spedizione"]))
			{
				$spModel = new SpedizioniModel();
				
				$spModel->sValues(array(
					"id_user"	=>	$ordine["id_user"],
				));
				
				$campiSpedizione = OpzioniModel::arrayValori("CAMPI_SALVATAGGIO_SPEDIZIONE");
				
				foreach ($campiSpedizione as $cs)
				{
					$spModel->setValue($cs, $ordine[$cs], "sanitizeDb");
				}
				
				if ($ordine["id_spedizione"])
					$spModel->update($ordine["id_spedizione"]);
				else
				{
					if ($spModel->insert())
					{
						$this->sValues(array(
							"id_spedizione"		=>	$spModel->lId,
						));
						
						$this->pUpdate((int)$idOrdine);
					}
				}
			}
		}
	}
	
	public function update($id = null, $where = null)
	{
		$clean["id"] = (int)$id;
		
		$oldRecord = $this->selectId($clean["id"]);
		
		$checkFiscale = v("abilita_codice_fiscale");
		
		$this->setAliquotaIva($id);
		
		$this->setProvince();
		
		$this->setPagato($id);
		
		if (!App::$isFrontend || $this->controllaCF($checkFiscale))
		{
			$res = parent::update($clean["id"]);
			
			if ($res)
			{
				if (!App::$isFrontend)
				{
					$newRecord = $this->selectId($clean["id"]);
					
// 					if (!empty($oldRecord) && !empty($newRecord) && ($oldRecord["stato"] == "pending" || $newRecord["stato"] == "pending"))
					if (!empty($oldRecord) && !empty($newRecord) && (
						StatiordineModel::statoEditabileEdEliminabile($oldRecord["stato"]) || 
						StatiordineModel::statoEditabileEdEliminabile($newRecord["stato"])
					))
						self::$isDeletable = true;
					
					$this->aggiornaTotali($id);
					
					self::$isDeletable = null;
				}
				
				if (!OrdiniModel::$ordineImportato)
				{
					$this->triggersOrdine($id);
					
					// controlla se deve movimentare l'ordine
					$this->checkMovimentazioni($clean["id"]);
					
					if (!App::$isFrontend && v("crea_sincronizza_cliente_in_ordini_offline"))
						$this->creaSincronizzaClienteSeAssente($id);
					
					// Hook ad aggiornamento dell'ordine
					if (v("hook_update_ordine"))
						callFunction(v("hook_update_ordine"), $clean["id"], v("hook_update_ordine"));
				}
				
				return true;
			}
		}
		
		return false;
	}
	
	public function checkMovimentazioni($id)
	{
		if (VariabiliModel::movimenta())
		{
			$ordine = $this->selectId((int)$id);
			
			if (empty($ordine))
				return;
			
			$scarica = self::isPagato((int)$id);
			
			if ($ordine["stato"] == "pending")
				$scarica = true;
			
// 			var_dump($scarica);die();
			
			$rModel = new RigheModel();
			
			$righe = $rModel->clear()->where(array(
				"id_o"	=>	(int)$id,
			))->send(false);
			
			foreach ($righe as $r)
			{
				if (!$r["id_c"])
					continue;
				
				if ($scarica && !$r["movimentato"])
				{
					CombinazioniModel::g()->movimenta($r["id_c"], $r["quantity"], (int)$r["id_r"]);
					
					$rModel->setMovimentato((int)$r["id_r"], 1);
				}
				else if (!$scarica && $r["movimentato"])
				{
					CombinazioniModel::g()->movimenta($r["id_c"], (-1) * $r["quantity"], (int)$r["id_r"]);
					
					$rModel->setMovimentato((int)$r["id_r"], 0);
				}
			}
		}
	}
	
	public function triggersOrdine($idO)
	{
		$ordine = $this->selectId((int)$idO);
		
		if ($ordine["importato"])
			return;
		
		if (v("attiva_gift_card"))
		{
			if (!empty($ordine))
			{
				$rModel = new RigheModel();
				$pModel = new PromozioniModel();
				
				$righe = $rModel->clear()->where(array(
					"id_o"		=>	(int)$idO,
					"gift_card"	=>	1,
				))->send(false);
				
				foreach ($righe as $r)
				{
					$pModel->aggiungiDaRigaOrdine($r["id_r"]);
				}
			}
		}
		
		if (v("attiva_liste_regalo"))
		{
			$ordine = !isset($ordine) ? $this->selectId((int)$idO) : $ordine;
			
			if (!empty($ordine) && $ordine["id_lista_regalo"] && trim($ordine["firma"]) && trim($ordine["dedica"]))
			{
				$lModel = new ListeregaloModel();
				$lreModel = new ListeregaloemailModel();
				
				$lista = $lModel->selectId((int)$ordine["id_lista_regalo"]);
				
				if (!empty($lista) && trim($lista["email"]) && checkMail($lista["email"]))
				{
					$lreModel->aggiungiDaOrdine($ordine);
				}
			}
		}
		
		if (v("attiva_crediti"))
		{
			$ordine = !isset($ordine) ? $this->selectId((int)$idO) : $ordine;
			
			if (!empty($ordine) && $ordine["id_user"])
			{
				$rModel = new RigheModel();
				$cModel = new CreditiModel();
				
				$righe = $rModel->clear()->where(array(
					"id_o"		=>	(int)$idO,
					"prodotto_crediti"	=>	1,
				))->send(false);
				
				// Carica crediti
				foreach ($righe as $r)
				{
					$cModel->aggiungiDaRigaOrdine($r["id_r"]);
				}
				
				// Scarica crediti
				if ($ordine["euro_crediti"] > 0)
				{
					$cModel->aggiungiScaricoDaOrdine((int)$idO);
				}
				
				// Controlla se devo eliminare la riga con "in_scadenza = 1" per il cliente dell'ordine
				$cModel->controllaInScadenza($ordine["id_user"]);
			}
		}
	}
	
	public function cartUidAlreadyPresent($cart_uid)
	{
		$clean["cart_uid"] = sanitizeAll($cart_uid);
		
		$numero = $this->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->rowNumber();
		
		return ($numero > 0) ? true : false;
		
// 		$res = $this->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->send();
// 		
// 		if (count($res) > 0)
// 		{
// 			return true;
// 		}
// 		return false;
	}
	
	public function recordExists($id_o, $cart_uid, $admin_token = null, $checkAdminDa = 0)
	{
		$clean["id_o"] = (int)$id_o;
		$clean["cart_uid"] = sanitizeAll($cart_uid);
		
		$res = $this->clear()->where(array(
			"id_o"		=>	$clean["id_o"],
			"cart_uid"	=>	$clean["cart_uid"],
		))->send();
		
		if (count($res) > 0)
		{
			if (
					$checkAdminDa && ((int)$res[0]["orders"]["id_o"] >= (int)$checkAdminDa) && (!isset($admin_token) || !$admin_token || (string)$res[0]["orders"]["admin_token"] !== (string)$admin_token)
				)
				return false;
			
			return true;
		}
		
		return false;
	}
	
	public function pulsanteFattura($id_o)
	{
		$clean["id_o"] = (int)$id_o;
		
		$fatt = new FattureModel();
		
		$record = $fatt->clear()->select("numero")->where(array("id_o"=>$clean["id_o"]))->record();
		
		if (!empty($record))
			return "<a class='text_16' title='scarica fattura' href='".Url::getRoot()."fatture/vedi/".$clean["id_o"]."'><b>".$record["numero"]." <i class='fa fa-download'></i></b></a>";
		
		return "<a class='text_16' title='genera fattura' href='".Url::getRoot()."fatture/crea/".$clean["id_o"]."'><b><i class='fa fa-refresh'></i></b></a>";
	}
	
	public function mandaMailGeneric($id_o, $oggetto, $template, $tipo, $fattura = false, $forzaTemplate = false, $sendTo = null, $tipologia = null)
	{
		require_once(Domain::$adminRoot.'/External/PHPMailer-master/src/Exception.php');
		require_once(Domain::$adminRoot.'/External/PHPMailer-master/src/PHPMailer.php');
		require_once(Domain::$adminRoot.'/External/PHPMailer-master/src/SMTP.php');
		
		$clean["id_o"] = (int)$id_o;
		$this->baseUrl = Domain::$name;
		$sendPassword = false;
		$res = $this->clear()->where(array("id_o"=>$clean["id_o"]))->send();
		$f = new FattureModel();
		
		if (count($res) > 0)
		{
			$notice = null;
			$ordine = $res[0]["orders"];
			
			$r = new RigheModel();
			
			$righeOrdine = $r->clear()->where(array("id_o"=>$clean["id_o"]))->send();
			
			$bckLang = Params::$lang;
			$bckCountry = Params::$country;
			$bckContesto = TraduzioniModel::$contestoStatic;
			$bckPrezziIvatiInCarrello = v("prezzi_ivati_in_carrello");
			
			if (v("attiva_prezzi_ivati_in_carrello_per_utente_e_ordine"))
				VariabiliModel::$valori["prezzi_ivati_in_carrello"] = (int)$ordine["prezzi_ivati_in_carrello"];
			
			try
			{
				$mail = new PHPMailer(true); //New instance, with exceptions enabled

				if (Parametri::$useSMTP)
				{
					$mail->IsSMTP();                         // tell the class to use SMTP
					$mail->Port       = Parametri::$SMTPPort;                    // set the SMTP server port
					$mail->Host       = Parametri::$SMTPHost; 		// SMTP server
					
					if (Parametri::$SMTPUsername && Parametri::$SMTPPassword)
					{
						$mail->SMTPAuth   = true;                  // enable SMTP authentication
						$mail->Username   = Parametri::$SMTPUsername;     // SMTP server username
						$mail->Password   = Parametri::$SMTPPassword;            // SMTP server password
					}
					
					if (ImpostazioniModel::$valori["smtp_secure"])
						$mail->SMTPSecure = ImpostazioniModel::$valori["smtp_secure"];
				}
				
				$mail->From       = Parametri::$mailFrom;
				$mail->FromName   = Parametri::$mailFromName;
				$mail->CharSet = 'UTF-8';
				
				$mail->ClearAddresses();
				
				if (!isset($sendTo))
					$sendTo = $ordine["email"];
				
				$mail->AddAddress($sendTo);
				// $mail->AddReplyTo(Parametri::$mailReplyTo, Parametri::$mailFromName);
				$mail->AddReplyTo(ImpostazioniModel::getReplyToMailOrdini(), Parametri::$mailFromName);
				
				// Imposto le traduzioni del front
				TraduzioniModel::$contestoStatic = "front";
				Params::$lang = $ordine["lingua"];
				
				if (v("attiva_nazione_nell_url"))
					Params::$country = $ordine["nazione_navigazione"] ? strtolower($ordine["nazione_navigazione"]) : strtolower(v("nazione_default"));
				
				$tradModel = new TraduzioniModel();
				$tradModel->ottieniTraduzioni();
				
				self::$pagamentiSettati = false;
				self::$statiOrdineSettati = false;
				
				self::setStatiOrdine();
				self::setPagamenti();
				
				$oggetto = gtext($oggetto, false);
				$oggetto = str_replace("[ID_ORDINE]",$clean["id_o"], $oggetto);
				
				// Segnaposti
				$oggetto = htmlentitydecode(SegnapostoModel::sostituisci($oggetto, $ordine, null));
				
				$mail->Subject  = Parametri::$nomeNegozio." - $oggetto";
				$mail->IsHTML(true);
				
				$mail->SMTPOptions = array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
					)
				);
				
				// Svuoto tutte le mail BCC
				$mail->ClearBCCs();
				$emails = array($sendTo);
				$arrayBcc = MailordiniModel::setBcc($mail, $emails);
				
				ob_start();
				$baseUrl = Url::getRoot();
				$baseUrl = str_replace("admin/", "", $baseUrl);
				$tipoOutput = "mail_al_cliente";
				if ($tipo == "R" && file_exists(tpf("/Elementi/Mail/mail_ordine_ricevuto.php")) && !$forzaTemplate)
					include tpf("/Elementi/Mail/mail_ordine_ricevuto.php");
				else
				{
					if ($tipo == "R")
						$statoOrdine = "pending";
					else if (preg_match('/^mail\-([0-9a-zA-Z\-\_]{1,})$/',$template, $matches))
						$statoOrdine = $matches[1];
					
					if (isset($statoOrdine) && isset(OrdiniModel::$stati[$statoOrdine]))
					{
						$recordStato = StatiordineModel::g()->where(array(
							"codice"	=>	sanitizeAll($statoOrdine),
						))->first();
						
						if (!empty($recordStato))
						{
							$testoMail = htmlentitydecode(sofield($recordStato, "descrizione"));
							$testoMail = SegnapostoModel::sostituisci($testoMail, $ordine, $this);
						}
					}
					
					if (isset($testoMail) && !F::blank($testoMail))
						echo $testoMail;
					else
						include tpf("/Ordini/$template.php");
				}
				
				$output = ob_get_clean();
				$testoClean = $output;
				
				$output = MailordiniModel::loadTemplate($oggetto, $output);
// 				echo $output;die();
				// Imposto le traduzioni del back
				Params::$lang = $bckLang;
				Params::$country = $bckCountry;
				TraduzioniModel::$contestoStatic = $bckContesto;
				
				if (v("attiva_prezzi_ivati_in_carrello_per_utente_e_ordine"))
					VariabiliModel::$valori["prezzi_ivati_in_carrello"] = $bckPrezziIvatiInCarrello;
				
				$tradModel = new TraduzioniModel();
				$tradModel->ottieniTraduzioni();
				
				if ($fattura)
				{
					$fattura = $f->where(array(
						"id_o"	=>	(int)$ordine["id_o"]
					))->record();
					
					if (!empty($fattura) && file_exists(LIBRARY."/media/Fatture/".$fattura["filename"]))
					{
						$mail->AddAttachment(LIBRARY."/media/Fatture/".$fattura["filename"]);
					}
				}
				
				$mail->AltBody = "Per vedere questo messaggio si prega di usare un client di posta compatibile con l'HTML";
				$mail->MsgHTML($output);
				
				$params = array(
					"oggetto"	=>	$oggetto,
					"bcc"		=>	implode(",",$arrayBcc),
					"testo"		=>	$testoClean,
					"inviata"	=>	0,
					"email"		=>	$sendTo,
					"tipologia"	=>	$tipologia ? $tipologia : "ORDINE",
				);
				
				$mailOrdini = $this->aggiungiStoricoMail($clean["id_o"], $tipo, $params);
				$lId = $mailOrdini->lId;
				
				if ($mailOrdini->checkLimitiInvio && $mail->Send())
				{
					$mailOrdini->sValues(array(
						"inviata"	=>	1,
					));
					
					if ($tipo == "R")
						$mailOrdini->setValue("testo", "");
					
					$mailOrdini->update($lId);
					
					$this->notice = "<div class='alert alert-success'>Mail inviata con successo!</div>";
				}
				else
				{
					$this->notice = "<div class='alert alert-danger'>Errore nell'invio della mail.</div>";
				}
			} catch (Exception $e) {
				Params::$lang = $bckLang;
				Params::$country = $bckCountry;
				TraduzioniModel::$contestoStatic = $bckContesto;
				
				if (v("attiva_prezzi_ivati_in_carrello_per_utente_e_ordine"))
					VariabiliModel::$valori["prezzi_ivati_in_carrello"] = $bckPrezziIvatiInCarrello;
			}
		}
	}
	
	public function mandaMailFattura($id_o)
	{
		$this->mandaMailGeneric($id_o, "Invio fattura ordine N° [ID_ORDINE]", "mail-fattura", "F", true);
	}
	
	public function mandaMailCompleted($id_o)
	{
		$this->mandaMailGeneric($id_o, v("oggetto_ordine_pagato"), "mail-completed", "P", false);
	}
	
	public function mandaMailClosed($id_o)
	{
		$this->mandaMailGeneric($id_o, v("oggetto_ordine_spedito"), "mail-closed", "C", false);
	}
	
	public function mandaMailDeleted($id_o)
	{
		$this->mandaMailGeneric($id_o, v("oggetto_ordine_annullato"), "mail-deleted", "A", false);
	}
	
	public function mandaMailStatoGenerico($id_o, $stato)
	{
		$titolo = StatiordineModel::getCampo($stato, "titolo");
		
		if ($titolo)
			$this->mandaMailGeneric($id_o, "Ordine N° [ID_ORDINE] - $titolo", "mail-$stato", "G", false);
	}
	
	public function mandaMail($id_o)
	{
		$this->mandaMailGeneric($id_o, v("oggetto_ordine_ricevuto"), "resoconto-acquisto", "R", false);
	}
	
	public function aggiungiStoricoMail($id_o, $tipo = "F", $params = array())
	{
		$ordine = $this->selectId((int)$id_o);
		
		$mailOrdini = new MailordiniModel();
		
		$mailOrdini->setValues(array(
			"id_o"		=>	$id_o,
			"tipo"		=>	$tipo,
			"email"		=>	isset($params["email"]) ? $params["email"] : $ordine["email"],
			"id_user"	=>	isset($ordine["id_user"]) ? $ordine["id_user"] : 0,
			"oggetto"	=>	isset($params["oggetto"]) ? $params["oggetto"] : "",
			"bcc"		=>	isset($params["bcc"]) ? $params["bcc"] : "",
			"testo"		=>	isset($params["testo"]) ? $params["testo"] : "",
			"inviata"	=>	isset($params["inviata"]) ? $params["inviata"] : 1,
			"tipologia"	=>	isset($params["tipologia"]) ? $params["tipologia"] : "ORDINE",
		));
		
		$mailOrdini->insert();
		
		return $mailOrdini;
	}
	
	// Iscrivi a newsletter l'utente dell'ordine
	public function iscriviANewsletter($id_o)
	{
		$clean["id_o"] = (int)$id_o;
		
		$ordine = $this->clear()->where(array("id_o"=>$clean["id_o"]))->record();
		
		if (!empty($ordine))
		{
			// Iscrizione alla newsletter
			if (ImpostazioniModel::$valori["mailchimp_api_key"] && ImpostazioniModel::$valori["mailchimp_list_id"])
			{
				$dataMailChimp = array(
					"email"	=>	$ordine["email"],
					"status"=>	"subscribed",
				);
				
				if ($ordine["tipo_cliente"] == "azienda")
				{
					$dataMailChimp["firstname"] = $ordine["ragione_sociale"];
					$dataMailChimp["lastname"] = $ordine["ragione_sociale"];
				}
				else
				{
					$dataMailChimp["firstname"] = $ordine["nome"];
					$dataMailChimp["lastname"] = $ordine["cognome"];
				}
				
				$code = syncMailchimp($dataMailChimp);
				
// 				echo $code;
			}
		}
	}
	
	//riempi la tabella righe con le righe relative all'ordine in questione
	//$id_o: id dell'ordine
	public function riempiRighe($id_o, $movimentato = 1)
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
		
		$clean["id_o"] = (int)$id_o;
		
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$c = new CartModel();
		$r = new RigheModel();
		$re = new RigheelementiModel();
		
		$sconto = 0;
		if (hasActiveCoupon($id_o))
		{
			$p = new PromozioniModel();
			$coupon = $p->getCoupon(User::$coupon);
			
			if ($coupon["tipo_sconto"] == "PERCENTUALE")
				$sconto = $coupon["sconto"];
		}
		
		$pages = $c->getRighePerOrdine();
		
		$idsPage = [];
		
		foreach ($pages as $p)
		{
			// Creo un array con tutti gli ID delle pagine nell'ordine
			if (!in_array($p["cart"]["id_page"], $idsPage))
				$idsPage[] = $p["cart"]["id_page"];
			
			$r->values = $p["cart"];
			$r->values["id_o"] = $clean["id_o"];
			
			if (isset(IvaModel::$aliquotaEstera))
				$r->values["iva"] = IvaModel::$aliquotaEstera;
			
			if (isset(IvaModel::$idIvaEstera))
				$r->values["id_iva"] = IvaModel::$idIvaEstera;
			
			$r->values["price_ivato"] = number_format($r->values["price"] * (1 + ($r->values["iva"] / 100)),2,".","");
			$r->values["prezzo_intero_ivato"] = number_format($r->values["prezzo_intero"] * (1 + ($r->values["iva"] / 100)),2,".","");
			
			if (v("attiva_prezzo_fisso"))
			{
				$prezzo = number_format($r->values["price"],v("cifre_decimali"),".","");
				$prezzoFisso = number_format($r->values["prezzo_fisso"],v("cifre_decimali"),".","");
				
				$subtotaleRiga = number_format($prezzoFisso + ($prezzo * $r->values["quantity"]),v("cifre_decimali"),".","");
				
				$inPromoRiga = (!$r->values["id_riga_tipologia"] && PromozioniModel::checkProdottoInPromo($p["cart"]["id_page"]));
				
				if ($inPromoRiga)
				{
					$prezzo = number_format($prezzo - $prezzo * ($sconto/100),v("cifre_decimali"),".","");
					$prezzoFisso = number_format($prezzoFisso - $prezzoFisso * ($sconto/100),v("cifre_decimali"),".","");
				}
				
				$subtotaleRigaScontato = number_format($prezzoFisso + ($prezzo * $r->values["quantity"]),v("cifre_decimali"),".","");
				
				$r->values["prezzo_finale"] = $r->values["quantity"] > 0 ? number_format($subtotaleRigaScontato / $r->values["quantity"],v("cifre_decimali"),".","") : 0;
				
				if ($inPromoRiga && $subtotaleRiga > 0)
					$r->values["percentuale_promozione"] = number_format((($subtotaleRiga - $subtotaleRigaScontato) / $subtotaleRiga) * 100,2,".","");
			}
			else
			{
				if (!$r->values["id_riga_tipologia"] && PromozioniModel::checkProdottoInPromo($p["cart"]["id_page"]))
				{
					$r->values["prezzo_finale"] = number_format($r->values["price"] - ($r->values["price"] * ($sconto / 100)),v("cifre_decimali"),".","");
					$r->values["percentuale_promozione"] = $sconto;
				}
				else
					$r->values["prezzo_finale"] = number_format($r->values["price"],v("cifre_decimali"),".","");
			}
			
			$r->values["prezzo_finale_ivato"] = number_format($r->values["prezzo_finale"] * (1 + ($r->values["iva"] / 100)),2,".","");
			
			$r->values["fonte"] = App::$isFrontend ? "W" : "B";
			$r->values["id_admin"] = App::$isFrontend ? 0 : User::$id;
			
			$idRiff = 0;
			
			if (!App::$isFrontend && $r->values["id_rif"])
				$idRiff = (int)$r->values["id_rif"];
			
			$r->values["movimentato"] = (int)$movimentato;
			
			$r->delFields("data_creazione");
			$r->delFields("id_user");
			$r->delFields("email");
			
			if (!$idRiff)
				$r->delFields("id_cart");
			
			$r->delFields("id_order");
			$r->delFields("id_rif");
			
			if ($idRiff)
			{
				$r->delFields("title");
				
				if ($r->values["prodotto_generico"])
					$r->delFields("codice");
			}
			
			foreach (self::$colonneAggiuntiveRighe as $colonnaAggiuntiva)
			{
				$r->delFields($colonnaAggiuntiva);
			}
			
			$r->sanitize();
			
			if ($idRiff)
				$result = $r->pUpdate((int)$idRiff);
			else
				$result = $r->insert();
			
			if ($result)
			{
				// Elimino le righe di elementi collegati alla riga d'ordine
				if ($idRiff)
					$re->mDel(array("id_r = ?",array((int)$idRiff)));
				
				// Salvo gli elementi del carrello
				$elementiCarrello = CartelementiModel::getElementiCarrello($p["cart"]["id_cart"]);
				
				foreach ($elementiCarrello as $elCart)
				{
					unset($elCart["id_cart_elemento"]);
					unset($elCart["data_creazione"]);
					unset($elCart["id_cart"]);
					
					$re->sValues($elCart, "sanitizeDb");
					
					if ($idRiff)
						$re->setValue("id_r", $idRiff);
					else
						$re->setValue("id_r", $r->lId);
					
					$re->insert();
				}
			}
		}
		
		if (v("aggiorna_colonna_numero_acquisti_prodotti_ad_ordine_concluso"))
		{
			$pModel = new PagesModel();
			
			foreach ($idsPage as $idPage)
			{
				$pModel->aggiornaNumeroAcquisti($idPage);
			}
		}
	}
	
	public function totaleCrudPieno($record)
	{
		return number_format($record["orders"]["subtotal_ivato"] + $record["orders"]["spedizione_ivato"] + $record["orders"]["costo_pagamento_ivato"] - $record["orders"]["euro_crediti"],2,",",".");
	}
	
	public function totaleCrud($record)
	{
		return number_format($record["orders"]["total"],2,",",".");
	}
	
	public function accontoCrud($record)
	{
		return number_format($record["orders"]["acconto"],2,",",".");
	}
	
	public function saldoCrud($record)
	{
		return "<b>".number_format($record["orders"]["saldo"],2,",",".")."</b>";
	}
	
	public function fatturaCrud($record)
	{
		if ($record["orders"]["fattura"] || $record["orders"]["tipo_cliente"] != "privato")
			return "<span class='text-primary'><b>".gtext("Richiesta")."</b></span>";
		
		return "";
	}
	
	public function righeEvaseCrud($record)
	{
		$numeroTotale = $this->righeDaEvadereTotali((int)$record["orders"]["id_o"]);
		
		if ($numeroTotale > 0)
		{
			$numeroDaEvadere = $this->righeAncoraDaEvadere((int)$record["orders"]["id_o"]);
			
			if ($numeroDaEvadere <= 0)
				return "<i title='".gtext("Tutte le righe sono state evase")."' class='fa fa-thumbs-up text-success'></i>";
		}
		
		return "";
	}
	
	public function vedi($record, $queryString = "?partial=Y&nobuttons=Y")
	{
		return "<a title='".gtext("Dettaglio ordine")."' class='iframe action_iframe' href='".Url::getRoot()."ordini/vedi/".$record["orders"]["id_o"]."$queryString'>".$record["orders"]["id_o"]."</a>";
	}
	
	public function vediFull($record, $queryString = "?partial=Y&nobuttons=Y")
	{
		return $this->vedi($record, "?partial=Y");
	}
	
	public function vediDaListe($record, $queryString = "?partial=Y&nobuttons=Y")
	{
		return $this->vedi($record, "?partial=Y&from=liste");
	}
	
	public static function getTotaliIva($id_o)
	{
		$o = new OrdiniModel();
		$r = new RigheModel();
		
		$ordine = $o->selectId((int)$id_o);
		
		$righe = $r->clear()->where(array(
			"id_o"	=>	(int)$id_o,
		))->send(false);
		
		$totaleIva = $totaleProdotti = $totaleProdottiPieno = $spedizione = $pagamento = $totaleIvaProdotti = $totaleIvaProdottiPieno = $totaleProdottiIvato = 0;
		
		$arrayTotali = $arrayTotaliProdotti = $arrayTotaliProdottiPieno = $arrayTotaliIvati = array();
		
		foreach ($righe as $r)
		{
			if ($r["acconto"])
				continue;
			
			$subtotaleRiga = number_format($r["prezzo_finale"] * $r["quantity"],v("cifre_decimali"),".","");
			$totaleProdotti += $subtotaleRiga;
			
			if (isset($arrayTotali[$r["id_iva"]]))
				$arrayTotali[$r["id_iva"]] += $subtotaleRiga;
			else
				$arrayTotali[$r["id_iva"]] = $subtotaleRiga;
			
			$subtotaleRiga = number_format($r["prezzo_finale_ivato"] * $r["quantity"],v("cifre_decimali"),".","");
			$totaleProdottiIvato += $subtotaleRiga;
			
			if (isset($arrayTotaliIvati[$r["id_iva"]]))
				$arrayTotaliIvati[$r["id_iva"]] += $subtotaleRiga;
			else
				$arrayTotaliIvati[$r["id_iva"]] = $subtotaleRiga;
			
			$subtotaleRiga = number_format($r["price"] * $r["quantity"],v("cifre_decimali"),".","");
			$totaleProdottiPieno += $subtotaleRiga;
			
			if (isset($arrayTotaliProdottiPieno[$r["id_iva"]]))
				$arrayTotaliProdottiPieno[$r["id_iva"]] += $subtotaleRiga;
			else
				$arrayTotaliProdottiPieno[$r["id_iva"]] = $subtotaleRiga;
		}
		
		// salvo i totali dei prodotti
		$arrayTotaliProdotti = $arrayTotali;
		
		$i = new IvaModel();
		
		if (!empty($ordine))
		{
			if ($ordine["iva_spedizione_ripartita"])
			{
				if (v("prezzi_ivati_in_prodotti"))
				{
					foreach ($arrayTotaliIvati as $idIva => $subtotIvato)
					{
						$frazione = $totaleProdottiIvato > 0 ? ($subtotIvato / $totaleProdottiIvato) : 0;
						
						$aliquota = $i->getValore($idIva);
						
						// CREDITI
						if ($ordine["euro_crediti"] > 0)
						{
							$subtotaleRiga = number_format($ordine["euro_crediti"] * $frazione / (1 + ($aliquota / 100)),CartModel::$cifreCalcolo,".","");
							
							$arrayTotali[$idIva] -= $subtotaleRiga;
						}
						
						if ((strcmp($ordine["usata_promozione"],"Y") === 0 || $ordine["sconto"] > 0) && $ordine["tipo_promozione"] == "ASSOLUTO")
						{
							$subtotaleRiga = number_format($ordine["euro_promozione"] * $frazione/ (1 + ($aliquota / 100)),CartModel::$cifreCalcolo,".","");
							
							$arrayTotali[$idIva] -= $subtotaleRiga;
						}
						
						$subtotaleRiga = $spedizione = number_format($ordine["spedizione_ivato"] * $frazione / (1 + ($aliquota / 100)),CartModel::$cifreCalcolo,".","");
						
						$arrayTotali[$idIva] += $subtotaleRiga;
						
						$subtotaleRiga = $pagamento = number_format($ordine["costo_pagamento_ivato"] * $frazione / (1 + ($aliquota / 100)),CartModel::$cifreCalcolo,".","");
						
						$arrayTotali[$idIva] += $subtotaleRiga;
					}
				}
				else
				{
					foreach ($arrayTotali as $idIva => $subtot)
					{
						$frazione = $totaleProdotti > 0 ? ($subtot / $totaleProdotti) : 0;
						
						$aliquota = $i->getValore($idIva);
						
						// CREDITI
						if ($ordine["euro_crediti"] > 0)
						{
							$subtotaleRiga = number_format($ordine["euro_crediti"] * $frazione / (1 + ($aliquota / 100)),v("cifre_decimali"),".","");
							
							$arrayTotali[$idIva] -= $subtotaleRiga;
						}
						
						if ((strcmp($ordine["usata_promozione"],"Y") === 0 || $ordine["sconto"] > 0) && $ordine["tipo_promozione"] == "ASSOLUTO")
						{
							$subtotaleRiga = number_format($ordine["euro_promozione"] * $frazione/ (1 + ($aliquota / 100)),v("cifre_decimali"),".","");
							
							$arrayTotali[$idIva] -= $subtotaleRiga;
						}
						
						$subtotaleRiga = $spedizione = number_format($ordine["spedizione"] * $frazione,v("cifre_decimali"),".","");
						
						$arrayTotali[$idIva] += $subtotaleRiga;
						
						$subtotaleRiga = $pagamento = number_format($ordine["costo_pagamento"] * $frazione,v("cifre_decimali"),".","");
						
						$arrayTotali[$idIva] += $subtotaleRiga;
					}
				}
			}
			else
			{
				// CREDITI
				if ($ordine["euro_crediti"] > 0)
				{
					$subtotaleRiga = number_format($ordine["euro_crediti"] / (1 + ($ordine["iva_spedizione"] / 100)),v("cifre_decimali"),".","");
					
					if (isset($arrayTotali[$ordine["id_iva"]]))
						$arrayTotali[$ordine["id_iva"]] -= $subtotaleRiga;
					else
						$arrayTotali[$ordine["id_iva"]] = (-1)*$subtotaleRiga;
				}
				
				if ((strcmp($ordine["usata_promozione"],"Y") === 0 || $ordine["sconto"] > 0) && $ordine["tipo_promozione"] == "ASSOLUTO")
				{
					$subtotaleRiga = number_format($ordine["euro_promozione"] / (1 + ($ordine["iva_spedizione"] / 100)),v("cifre_decimali"),".","");
					
					if (isset($arrayTotali[$ordine["id_iva"]]))
						$arrayTotali[$ordine["id_iva"]] -= $subtotaleRiga;
					else
						$arrayTotali[$ordine["id_iva"]] = (-1)*$subtotaleRiga;
				}
				
				$subtotaleRiga = $spedizione = number_format($ordine["spedizione"],v("cifre_decimali"),".","");
				
				if (isset($arrayTotali[$ordine["id_iva"]]))
					$arrayTotali[$ordine["id_iva"]] += $subtotaleRiga;
				else
					$arrayTotali[$ordine["id_iva"]] = $subtotaleRiga;
				
				$subtotaleRiga = $pagamento = number_format($ordine["costo_pagamento"],v("cifre_decimali"),".","");
				
				$arrayTotali[$ordine["id_iva"]] += $subtotaleRiga;
			}
		}
		
		$arrayIva = array();
		
		foreach ($arrayTotali as $idAliquota => $totale)
		{
			$aliquota = $i->getValore($idAliquota);
			
			$arrayIva[$idAliquota] = $totale * ($aliquota / 100);
			$totaleIva += $totale * ($aliquota / 100);
		}
		
		foreach ($arrayTotaliProdotti as $idAliquota => $totale)
		{
			$aliquota = $i->getValore($idAliquota);
			
			$totaleIvaProdotti += $totale * ($aliquota / 100);
		}
		
		foreach ($arrayTotaliProdottiPieno as $idAliquota => $totale)
		{
			$aliquota = $i->getValore($idAliquota);
			
			$totaleIvaProdottiPieno += $totale * ($aliquota / 100);
		}
		
		$aliquotaOrdine = $i->getValore($ordine["id_iva"]);
		
		$subtotalIvato = $totaleProdottiPieno + $totaleIvaProdottiPieno;
		$prezzoScontatoIvato = $totaleProdotti + $totaleIvaProdotti;
		$spedizioneIvato = $spedizione * (1 + $aliquotaOrdine / 100);
		$costoPagamentoIvato = $pagamento * (1 + $aliquotaOrdine / 100);
		$total = $subtotalIvato + $spedizioneIvato + $costoPagamentoIvato;
		
		$arraySubtotali = array(
			"subtotal"	=>	$totaleProdottiPieno,
			"prezzo_scontato"	=>	$totaleProdotti,
			"spedizione"=>	$spedizione,
			"costo_pagamento"	=>	$pagamento,
			"subtotal_ivato"	=>	$subtotalIvato,
			"prezzo_scontato_ivato"	=>	$prezzoScontatoIvato,
			"spedizione_ivato"	=>	$spedizioneIvato,
			"costo_pagamento_ivato"	=>	$costoPagamentoIvato,
			"iva"			=>	$totaleIva,
			"total"			=>	$total,
			"total_pieno"	=>	$total,
		);
		
		return array($arrayIva, $arraySubtotali);
	}
	
	public function checkAggiorna($idOrdine)
	{
		if( !session_id() )
			session_start();
		
		if (isset($_SESSION["aggiorna_totali_ordine"]))
			$this->aggiornaTotali($idOrdine);
	}
	
	public function aggiornaTotali($idOrdine)
	{
		if (!App::$isFrontend && OrdiniModel::tipoOrdine($idOrdine) != "W" && OrdiniModel::isDeletable($idOrdine) && !OrdiniModel::$ordineImportato)
		{
			$ordine = $this->selectId((int)$idOrdine);
			
			Params::$setValuesConditionsFromDbTableStruct = false;
			Params::$automaticConversionToDbFormat = false;
			
			$c = new CartModel();
			$r = new RigheModel();
			$ruModel = new RegusersModel();
			
			$bckUserId = User::$id;
			
			User::$cart_uid = $ordine["cart_uid"];
			User::$id = (int)$ordine["id_user"];
			
			if ((int)$ordine["id_user"])
			{
				User::$dettagli = $ruModel->selectId($ordine["id_user"]);
				User::setClasseSconto();
			}
			
			if ($ordine["id_lista_regalo"])
				User::$idLista = (int)$ordine["id_lista_regalo"];
			
			$bckLang = Params::$lang;
			$lingua = $ordine["lingua"] ? $ordine["lingua"] : LingueModel::getPrincipaleFrontend();
			Params::$lang = $lingua;
			
			$bckAttivaGiacenza = v("attiva_giacenza");
			$bckAttivaMovimentazioniGiacenza = v("scala_giacenza_ad_ordine");
			$bckScorporaIvaPrezzoEstero = v("scorpora_iva_prezzo_estero");
			$bckScorporaIvaPrezzoEsteroAzienda = v("scorpora_iva_prezzo_estero_azienda");
			
			VariabiliModel::$valori["attiva_giacenza"] = 0;
			VariabiliModel::$valori["scala_giacenza_ad_ordine"] = 0;
			VariabiliModel::$valori["scorpora_iva_prezzo_estero"] = 0;
			VariabiliModel::$valori["scorpora_iva_prezzo_estero_azienda"] = 0;
			
			$_POST["nazione"] = $ordine["nazione"];
			$_POST["nazione_spedizione"] = $ordine["nazione_spedizione"] ? $ordine["nazione_spedizione"] : $ordine["nazione"];
			$_POST["tipo_cliente"] = $ordine["tipo_cliente"];
			$_POST["pagamento"] = $ordine["pagamento"];
			$_POST["email"] = $ordine["email"];
			
			IvaModel::getAliquotaEstera();
			
			$righe = $r->clear()->where(array(
				"id_o"	=>	(int)$idOrdine,
			))->orderBy("id_order")->send(false);
			
			// AGGIUNGO AL CARRELLO
			if (v("usa_transactions"))
				$this->db->beginTransaction();
			
// 			$righeCarrello = $c->getRighePerOrdine();
			
			$elementiPuliti = array();
			
			$c->del(null, array(
				"cart_uid"	=>	User::$cart_uid,
			));
			
			$movimentato = 1;
			
			foreach ($righe as $r)
			{
				if (!$r["acconto"])
				{
					$idCart = $c->add($r["id_page"], $r["quantity"], $r["id_c"], 0, array(), $r["prezzo_intero"], $r["prezzo_intero_ivato"], $r["price"], $r["price_ivato"], $r["id_r"], true);
					
					$elementiRiga = RigheelementiModel::getElementiRiga($r["id_r"]);
					
					foreach ($elementiRiga as $elRiga)
					{
						$elRiga = htmlentitydecodeDeep($elRiga);
						
						$elementiPuliti["CART-".(int)$idCart][] = array(
							"email"	=>	(string)$elRiga["email"],
							"testo"	=>	(string)$elRiga["testo"],
						);
					}
					
					$movimentato = $r["movimentato"];
				}
			}
			
			$c->correggiPrezzi();
			$c->aggiornaElementi($elementiPuliti);
			
			if (v("usa_transactions"))
				$this->db->commit();
			
			CartModel::attivaDisattivaSpedizione((int)$idOrdine);
			
			// CONTROLLO LA PROMO
// 			$sconto = 0;
			
			if ($ordine["id_p"])
			{
				$coupon = PromozioniModel::g()->selectId((int)$ordine["id_p"]);
				
				if (!empty($coupon))
				{
					User::$coupon = $coupon["codice"];
					PromozioniModel::$staticIdO = $ordine["id_o"];
					
					if (hasActiveCoupon($ordine["id_o"]))
						User::$prodottiInCoupon = PromozioniModel::g()->elencoProdottiPromozione(User::$coupon);
					else
						User::$coupon = null;
				}
			}
			
// 			$this->ricalcolaPrezziRighe((int)$ordine["id_o"], $sconto);
			
			$this->values = array();
			$this->values["acconto"] = $this->getTotaleAcconto($ordine["id_o"]);
			
			$this->aggiungiTotali($ordine["stato"]);
			
			if ($ordine["evaso_non_evaso"] && (v("imposta_allo_stato_se_tutte_righe_sono_evase") || v("imposta_allo_stato_se_non_tutte_righe_sono_evase")))
			{
				$statoEvaso = $this->statoSeTutteLeRigheSonoEvase($ordine["id_o"]);
				
				if ($statoEvaso)
					$this->values["stato"] = sanitizeAll($statoEvaso);
			}
			
			$this->pUpdate($idOrdine);
			
			if (v("ripartisci_iva_spese_accessorie_proporzionalmente_ai_prodotti"))
			{
				if (isset(CartModel::$arrayRipartizione))
					OrdiniivaripartitaModel::g()->inserisciRipartizioni((int)$idOrdine, CartModel::$arrayRipartizione);
				else
					OrdiniivaripartitaModel::g()->inserisciRipartizioni((int)$idOrdine, array());
			}
			
// 			RigheModel::g()->mDel(array("id_o = ?",array((int)$ordine["id_o"])));
			
			if (v("usa_transactions"))
				$this->db->beginTransaction();
				
			$this->riempiRighe($ordine["id_o"], $movimentato);
			
			if (v("usa_transactions"))
				$this->db->commit();
			
			$c->del(null, array(
				"cart_uid"	=>	User::$cart_uid,
			));
			
			VariabiliModel::$valori["attiva_giacenza"] = $bckAttivaGiacenza;
			VariabiliModel::$valori["scala_giacenza_ad_ordine"] = $bckAttivaMovimentazioniGiacenza;
			VariabiliModel::$valori["scorpora_iva_prezzo_estero"] = $bckScorporaIvaPrezzoEstero;
			VariabiliModel::$valori["scorpora_iva_prezzo_estero_azienda"] = $bckScorporaIvaPrezzoEsteroAzienda;
			
			User::$id = $bckUserId;
			PromozioniModel::$staticIdO = null;
			Params::$lang = $bckLang;
			
			Params::$setValuesConditionsFromDbTableStruct = true;
			Params::$automaticConversionToDbFormat = true;
			
			unset($_SESSION["aggiorna_totali_ordine"]);
		}
	}
	
	public function impostaAlloStatoSeTutteLeRigheSonoEvase($idOrdine)
	{
		$statoEvaso = $this->statoSeTutteLeRigheSonoEvase((int)$idOrdine);
		
		if ($statoEvaso)
		{
			$this->sValues(array(
				"stato"	=>	$statoEvaso,
			));
			
			OrdiniModel::$ordineImportato = true;
			
			$this->update((int)$idOrdine);
		}
	}
	
	public function righeAncoraDaEvadere($idOrdine, $numero = true)
	{
		$r = new RigheModel();
		
		$r->clear()->where(array(
			"id_o"				=>	(int)$idOrdine,
			"id_riga_tipologia"	=>	0,
			"evasa"				=>	0,
		));
		
		return ($numero ? $r->rowNumber() : $r->send(false));
	}
	
	public function righeDaEvadereTotali($idOrdine, $numero = true)
	{
		$r = new RigheModel();
		
		$r->clear()->where(array(
			"id_o"				=>	(int)$idOrdine,
			"id_riga_tipologia"	=>	0,
		));
		
		return ($numero ? $r->rowNumber() : $r->send(false));
	}
	
	public function statoSeTutteLeRigheSonoEvase($idOrdine)
	{
		$ordine = $this->selectId((int)$idOrdine);
		
		if (!empty($ordine) && $ordine["evaso_non_evaso"])
		{
			$r = new RigheModel();
			
			$numeroDaEvadere = $this->righeAncoraDaEvadere((int)$idOrdine);
			
			$numeroTotale = $this->righeDaEvadereTotali((int)$idOrdine);
			
			if ($numeroTotale > 0 && $numeroDaEvadere <= 0 && v("imposta_allo_stato_se_tutte_righe_sono_evase") && $ordine["stato"] != v("imposta_allo_stato_se_tutte_righe_sono_evase"))
				return v("imposta_allo_stato_se_tutte_righe_sono_evase");
			else if (v("imposta_allo_stato_se_non_tutte_righe_sono_evase") && $numeroDaEvadere > 0 && $ordine["stato"] != v("imposta_allo_stato_se_non_tutte_righe_sono_evase"))
				return v("imposta_allo_stato_se_non_tutte_righe_sono_evase");
		}
		
		return "";
	}
	
// 	public function ricalcolaPrezziRighe($idOrdine, $sconto)
// 	{
// 		$rModel = new RigheModel();
// 		$pModel = new PagesModel();
// 		$iModel = new IvaModel();
// 		
// 		$righe = $rModel->clear()->where(array(
// 			"id_o"	=>	(int)$idOrdine,
// 		))->send(false);
// 		
// 		$idIvaGenerica = $iModel->clear()->orderBy("id_order")->limit(1)->field("id_iva");
// 		
// 		if (v("usa_transactions"))
// 			$this->db->beginTransaction();
// 		
// 		foreach ($righe as $r)
// 		{
// 			$pagina = $pModel->selectId($r["id_page"]);
// 			
// 			$idIvaPagina = (!empty($pagina)) ? $pagina["id_iva"] : $idIvaGenerica;
// 			
// 			$prezzoFinale = number_format($r["price"] - ($r["price"] * ($sconto / 100)),v("cifre_decimali"),".","");
// 			
// 			$idIva = isset(IvaModel::$idIvaEstera) ? IvaModel::$idIvaEstera : $idIvaPagina;
// 			$iva = isset(IvaModel::$aliquotaEstera) ? IvaModel::$aliquotaEstera : IvaModel::g()->getValore((int)$idIvaPagina);
// 			
// 			$rModel->sValues(array(
// 				"id_iva"		=>	$idIva,
// 				"iva"			=>	$iva,
// 				"prezzo_finale"	=>	$prezzoFinale,
// 				"prezzo_finale_ivato"	=>	number_format($prezzoFinale * (1 + ($iva / 100)),2,".",""),
// 				"percentuale_promozione"=>	$sconto,
// 			));
// 			
// 			$rModel->pUpdate($r["id_r"]);
// 		}
// 		
// 		if (v("usa_transactions"))
// 			$this->db->commit();
// 	}
	
	// Calcola il totale dell'acconto nell'ordine
	public function getTotaleAcconto($idOrdine)
	{
		$rModel = new RigheModel();
		
		$res = $rModel->clear()->select("sum(quantity * prezzo_finale_ivato) as SOMMA")->where(array(
			"id_o"		=>	(int)$idOrdine,
			"acconto"	=>	1,
		))->send();
		
		if (isset($res[0]["aggregate"]["SOMMA"]) && $res[0]["aggregate"]["SOMMA"])
			return number_format($res[0]["aggregate"]["SOMMA"],2,".","");
		
		return 0;
	}
	
	public function aggiungiTotali($forzaAlloStato = null)
	{
		$this->values["subtotal"] = getSubTotalN();
		$this->values["spedizione"] = getSpedizioneN();
		$this->values["costo_pagamento"] = getPagamentoN();
		
		$this->values["subtotal_ivato"] = setPrice(getSubTotal(true));
		$this->values["spedizione_ivato"] = setPrice(getSpedizione(1));
		$this->values["costo_pagamento_ivato"] = setPrice(getPagamento(1));
		
		$this->values["iva"] = setPrice(getIva());
		$this->values["total"] = setPrice(getTotal());
		
		$this->values["iva_per_versamenti"] = setPrice(setPriceReverse(CartModel::g()->iva(true, false, true, true, true)));
		
		if (App::$isFrontend)
			$this->values["saldo"] = $this->values["total"];
		else
		{
			if (isset($this->values["acconto"]))
			{
				if ($this->values["total"] > $this->values["acconto"])
					$this->values["saldo"] = $this->values["total"] - $this->values["acconto"];
				else
					$this->values["saldo"] = 0;
			}
			else
				$this->values["saldo"] = $this->values["total"];
		}
		
		$this->values["cart_uid"] = User::$cart_uid;
		$this->values["admin_token"] = md5(randString(22).microtime().uniqid(mt_rand(),true));
		$this->values["banca_token"] = md5(randString(18).microtime().uniqid(mt_rand(),true));
		
		$this->values["total_pieno"] = $this->values["subtotal_ivato"] + $this->values["spedizione_ivato"] + $this->values["costo_pagamento_ivato"];
		
		$this->values["creation_time"] = time();
		
		if (!isset($forzaAlloStato))
		{
			$statoOrdine = "pending";
			
			if (number_format(getTotalN(),2,".","") <= 0.00)
				$statoOrdine = "completed";
		}
		else
			$statoOrdine = $forzaAlloStato;
		
		$this->values["stato"] = $statoOrdine;
		
		$this->values["prezzo_scontato"] = getPrezzoScontatoN(false, 0);
		$this->values["prezzo_scontato_ivato"] = getPrezzoScontatoN(false, 1);
		
		$this->values["prezzo_scontato_prodotti"] = getPrezzoScontatoN(false, 0, false, false, false);
		$this->values["prezzo_scontato_prodotti_ivato"] = getPrezzoScontatoN(false, 1, false, false, false);
		
		$this->values["codice_promozione"] = User::$coupon;
		$this->values["nome_promozione"] = htmlentitydecode(getNomePromozione());
		$this->values["usata_promozione"] = hasActiveCoupon() ? "Y" : "N";
		
		// CREDITI
		$this->values["euro_crediti"] = 0;
		
		if (v("attiva_crediti"))
		{
			$this->values["euro_crediti"] = number_format(getPrezzoScontatoN(true,1,false,false,false) - getPrezzoScontatoN(true,1,false,true,false),2,".","");
			$this->values["moltiplicatore_credito"] = v("moltiplicatore_credito");
		}
		
		$coupon = PromozioniModel::getCouponAttivo();
		
		if (!empty($coupon))
		{
			$this->values["tipo_promozione"] = $coupon["tipo_sconto"];
			$this->values["euro_promozione"] = $this->values["total_pieno"] - $this->values["total"] - $this->values["euro_crediti"];
			$this->values["id_p"] = $coupon["id_p"];
			$this->values["id_agente"] = $coupon["id_user"];
		}
		else
		{
			$this->values["tipo_promozione"] = "PERCENTUALE";
			$this->values["euro_promozione"] = 0.00;
			$this->values["id_p"] = 0;
			$this->values["id_agente"] = 0;
		}
		
		$this->values["id_iva"] = CartModel::getIdIvaSpedizione();
		$this->values["iva_spedizione"] = number_format(CartModel::getAliquotaIvaSpedizione(),8,".","");
		$this->values["iva_spedizione_ripartita"] = (CartModel::numeroAliquoteInCarrello() > 1) ? v("ripartisci_iva_spese_accessorie_proporzionalmente_ai_prodotti") : 0;
		
		if (isset(IvaModel::$aliquotaEstera))
		{
			$this->values["id_iva_estera"] = IvaModel::$idIvaEstera;
			$this->values["aliquota_iva_estera"] = IvaModel::$aliquotaEstera;
			$this->values["stringa_iva_estera"] = IvaModel::$titoloAliquotaEstera;
			$this->values["nascondi_iva_estera"] = IvaModel::$nascondiAliquotaEstera;
		}
		else
		{
			$this->values["id_iva_estera"] = 0;
			$this->values["aliquota_iva_estera"] = 0.00;
			$this->values["stringa_iva_estera"] = "";
			$this->values["nascondi_iva_estera"] = 0;
		}
		
		$this->values["da_spedire"] = v("attiva_spedizione");
		
		// Controllo se l'ordine è da spedire
		if (v("attiva_campo_ritiro_in_sede_su_corrieri") && isset($_POST["id_corriere"]) && $_POST["id_corriere"] && CorrieriModel::corriereEsistente((int)$_POST["id_corriere"]) && CorrieriModel::ritiroInSede((int)$_POST["id_corriere"]))
			$this->values["da_spedire"] = 0;
		
		if (App::$isFrontend)
		{
			$this->values["mostra_sempre_corriere"] = 0;
			
			if (!$this->values["da_spedire"] && !CartModel::soloProdottiSenzaSpedizione())
				$this->values["mostra_sempre_corriere"] = 1;
		}
		
		if (!App::$isFrontend && v("disattiva_costo_spedizione_ordini_offline"))
			$this->values["mostra_spese_spedizione_ordine_frontend"] = 0;
		
		if (!App::$isFrontend && v("disattiva_costo_pagamento_ordini_offline"))
			$this->values["mostra_spese_pagamento_ordine_frontend"] = 0;
			
		if (v("salva_ip"))
			$this->values["ip"] = getIp();

		if (v("traccia_sorgente_utente") && User::$sorgente)
			$this->values["sorgente"] = sanitizeAll(User::$sorgente);
		
		if (v("attiva_prezzi_ivati_in_carrello_per_utente_e_ordine"))
			$this->values["prezzi_ivati_in_carrello"] = v("prezzi_ivati_in_carrello");
		
		// Salvo la nazione della lista regalo
		if (ListeregaloModel::hasIdLista())
			$this->values["nazione_lista_regalo"] = ListeregaloModel::nazioneListaRegalo(0, User::$idLista);
	}
	
	public static function totaleNazione($nazione, $annoPrecedente = false)
	{
		$anno = date("Y");
		
		if ($annoPrecedente)
			$anno = date("Y", strtotime("-1 year", time()));
		
		$o = new OrdiniModel();
		
		$res = $o->clear()->select("SUM(prezzo_scontato) as TOTALE")->where(array(
			"nazione_spedizione"	=>	sanitizeAll($nazione),
		))->sWhere(array("DATE_FORMAT(data_creazione, '%Y') = ?",array($anno)))->send();
		
		if (isset($res[0]["aggregate"]["TOTALE"]) && $res[0]["aggregate"]["TOTALE"])
			return $res[0]["aggregate"]["TOTALE"];
		
		return 0;
	}
	
	public static function totaleFuoriItaliaEu($anno = null)
	{
		if (!isset($anno))
			$anno = date("Y");
		
		$o = new OrdiniModel();
		
		$res = $o->clear()->select("SUM(total - iva) as TOTALE")->where(array(
			"ne"	=>	array(
				"nazione_spedizione"	=>	"IT",
			),
		))
		// ->sWhere(self::getWhereClausePagato())
		->sWhere("orders.stato != 'deleted'")
		->sWhere(array("nazione_spedizione in (select iso_country_code from nazioni where tipo = 'UE') AND DATE_FORMAT(data_creazione, '%Y') = ?",array($anno)))->send();
		
		if (isset($res[0]["aggregate"]["TOTALE"]) && $res[0]["aggregate"]["TOTALE"])
			return $res[0]["aggregate"]["TOTALE"];
		
		return 0;
	}
	
	public function pagamentoCrud($record)
	{
		return metodoPagamento($record["orders"]["pagamento"]);
	}
	
	public function statoordinelabel($records)
	{
		if (isset(OrdiniModel::$stati[$records["orders"]["stato"]]))
			return "<span class='text-bold label label-".OrdiniModel::$labelStati[$records["orders"]["stato"]]."'>".OrdiniModel::$stati[$records["orders"]["stato"]]."<span>";
		
		return $records["orders"]["stato"];
	}
	
	// Importa i dati della spedizione $id_s nell'ordine $id_o
	public function importaSpedizione($id_o, $id_s)
	{
		$s = new SpedizioniModel();
		$o = new OrdiniModel();
		
		$spedizione = $s->selectId((int)$id_s);
		
		if (!empty($spedizione))
		{
			$o->setValues(array(
				"id_spedizione"			=>	(int)$id_s,
// 				"indirizzo_spedizione"	=>	$spedizione["indirizzo_spedizione"],
// 				"cap_spedizione"		=>	$spedizione["cap_spedizione"],
// 				"provincia_spedizione"	=>	$spedizione["provincia_spedizione"],
// 				"nazione_spedizione"	=>	$spedizione["nazione_spedizione"],
// 				"citta_spedizione"		=>	$spedizione["citta_spedizione"],
// 				"telefono_spedizione"	=>	$spedizione["telefono_spedizione"],
// 				"dprovincia_spedizione"	=>	$spedizione["dprovincia_spedizione"],
			), "sanitizeDb");
			
			$campiSpedizione = OpzioniModel::arrayValori("CAMPI_SALVATAGGIO_SPEDIZIONE");
			
			foreach ($campiSpedizione as $cs)
			{
				$o->setValue($cs, $spedizione[$cs], "sanitizeDb");
			}
			
			return $o->update((int)$id_o);
		}
		
		return false;
	}
	
	public static function getByCartUid($cartUid)
	{
		$clean["cartUid"] = sanitizeAll($cartUid);
		
		$o = new OrdiniModel();
		
		return $o->clear()->where(array(
			"cart_uid"	=>	$clean["cartUid"],
		))->record();
	}
	
	public static function conPagamentoOnline($ordine)
	{
		if ($ordine["pagamento"] == "paypal" || $ordine["pagamento"] == "carta_di_credito" || $ordine["pagamento"] == "klarna" || $ordine["pagamento"] == "satispay")
			return true;
		
		return false;
	}
	
	public function settaMailDaInviare($idO, $field = "mail_da_inviare")
	{
		$this->setValues(array(
			$field	=>	1,
		));
		
		$this->update((int)$idO);
	}
	
	// Restituisce un array con tutti gli indirizzi email a cui mandare la mail dell'ordine (lato negozio)
	public function getIndirizziEmailInvioOrdineAlNegozio()
	{
		$emails = array(Parametri::$mailInvioOrdine);
		
		if (v("mail_aggiuntive_invio_ordine_negozio"))
		{
			$emailAggiuntive = explode(",", v("mail_aggiuntive_invio_ordine_negozio"));
			
			foreach ($emailAggiuntive as $email)
			{
				if (trim($email))
					$emails[] = $email;
			}
		}
		
		return $emails;
	}
	
	public function mandaMailDopoPagamentoNegozio($idO)
	{
		$ordine = $this->selectId($idO);
		
		if (!empty($ordine) && $ordine["mail_da_inviare_negozio"])
		{
			$emails = $this->getIndirizziEmailInvioOrdineAlNegozio();
			
			foreach ($emails as $email)
			{
				$this->mandaMailGeneric((int)$idO, v("oggetto_ordine_ricevuto"), "resoconto-acquisto", "R", false, true, $email, "ORDINE NEGOZIO");
			}
			
			$this->setValues(array(
				"mail_da_inviare_negozio"	=>	0,
			));
			
			$this->update((int)$idO);
		}
	}
	
	public function mandaMailAdAgente($idO, $forzaInvio = false)
	{
		$ordine = $this->selectId($idO);
		
		if (v("attiva_agenti") && v("manda_mail_ordine_ad_agenti") && !empty($ordine) && $ordine["id_agente"] && ($ordine["mail_da_inviare_agente"] || $forzaInvio))
		{
			$ru = new RegusersModel();
			
			$agente = $ru->whereId((int)$ordine["id_agente"])->addWhereUtenteAttivo()->record();
			
			if (!empty($agente) && checkMail($agente["username"]))
			{
				ob_start();
				$tipoOutput = "mail_ad agente";
				include tpf("/Elementi/Mail/mail_ordine_ricevuto_agente.php");
				$output = ob_get_clean();
				
				$oggetto = str_replace("[CODICE_COUPON]", "[OGGETTO_PLACEHOLDER]", v("oggetto_ordine_ricevuto_agente"));
				
				$res = MailordiniModel::inviaMail(array(
					"emails"	=>	array($agente["username"]),
					"oggetto"	=>	$oggetto,
					"oggetto_placeholder"	=>	$ordine["codice_promozione"],
					"testo"		=>	$output,
					"tipologia"	=>	"ORDINE AGENTE",
					"id_user"	=>	(int)$ordine['id_user'],
					"tipo"		=>	"R",
					"id_o"		=>	(int)$idO,
					"array_variabili"	=>	$ordine,
				));
			}
		}
	}
	
	// Manda le mail dopo il pagamento
	public function mandaMailDopoPagamento($idO)
	{
		$ordine = $this->selectId($idO);
		
		if (!empty($ordine) && $ordine["mail_da_inviare"])
		{
			$this->mandaMail((int)$idO);
			
			$this->setValues(array(
				"mail_da_inviare"	=>	0,
			));
			
			$this->update((int)$idO);
			
			if ($ordine["id_user"])
			{
				$idUser = (int)$ordine["id_user"];
				
				$r = new RegusersModel();
				
				$cliente = $r->selectId($idUser);
				
				if (!empty($cliente) && !$cliente["credenziali_inviate"])
				{
					if (RegusersModel::resettaCredenziali($idUser))
					{
						$r->setValues(array(
							"credenziali_inviate"	=>	1,
						));
						
						$r->pUpdate($idUser);
					}
				}
			}
		}
	}
	
	public function quantitaTotale($idO)
	{
		$r = new RigheModel();
		
		$res = $r->clear()->select("sum(quantity) as SOMMA")->where(array(
			"id_o"	=>	$idO,
		))->findAll();
		
		if (count($res) > 0)
			return (int)$res[0]["aggregate"]["SOMMA"];
		
		return 1;
	}
	
	public function linkcrud($record)
	{
		return '<a href="'.Url::getRoot().$this->applicationUrl.$this->controller.'/vedi/'.$record["orders"]["id_o"].$this->cViewStatus.'">#'.$record["orders"]["id_o"].'</a>';
	}
	
	public static function sistemaDaSpedire()
	{
		if (!v("attiva_spedizione"))
		{
			$o = new OrdiniModel();
			
			$o->db->query("update orders set da_spedire = 0 where 1");
		}
	}
	
	public function listaregalo($record, $table = "orders")
	{
		if ($record[$table]["id_lista_regalo"])
		{
			$lista = ListeregaloModel::g()->selectId((int)$record[$table]["id_lista_regalo"]);
			
			if (!empty($lista))
			{
				return gtext("Titolo").": ". $lista["titolo"]."<br />".gtext("Codice").": <b>".$lista["codice"]."</b>";
			}
		}
		
		return "";
	}
	
	public function deletable($id)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record))
		{
			if ($record["tipo_ordine"] == "W")
				return false;
			
			if (!StatiordineModel::statoEditabileEdEliminabile($record["stato"]))
				return false;
			
			$fModel = new FattureModel();
			
			if ($fModel->clear()->where(array(
				"id_o"	=>	(int)$id,
			))->rowNumber())
				return false;
		}
		
		return true;
	}
	
	public function isDeletable($id)
	{
		if (!isset(self::$isDeletable))
			self::$isDeletable = $this->deletable($id);
		
		return self::$isDeletable;
	}
	
	public static function tipoOrdine($id)
	{
		return self::g()->select("tipo_ordine")->whereId((int)$id)->field("tipo_ordine");
	}
	
	public function tipoOrdineCrud($record)
	{
		return self::getLabelTipoOrdine($record["orders"]["tipo_ordine"]);
	}
	
	public function dedicaCrud($record)
	{
		$dedica = $this->getElemendoDedica($record["orders"]["id_o"]);
		
		if ($dedica)
		{
			return "<i class='fa fa-check text text-success'></i> <small>".gtext("Inviata il")." ".date("d/m/Y H:i", strtotime($dedica["data_creazione"]))."</small>";
		}
	}
	
	public function getElemendoDedica($idOrdine)
	{
		$idListaRegaloEmail = (int)ListeregaloemailModel::g()->where(array(
			"id_o"	=>	(int)$idOrdine
		))->field("id_lista_regalo_email");
		
		if ($idListaRegaloEmail)
		{
			return EventiretargetingelementiModel::getElemento((int)$idListaRegaloEmail, "liste_regalo_email");
		}
		
		return array();
	}
	
	public function getNominativoInOrdineOCliente($lingua, $record)
	{
		return self::getNominativo($record);
	}
	
	public function getRiferimentoOrdine($lingua, $record)
	{
		return $record["id_o"];
	}
	
	public function getLinkTrackingOrdine($lingua, $record)
	{
		return $record["link_tracking"];
	}
	
	public function getNomeSpedizioniereOrdine($lingua, $record)
	{
		$sp = new SpedizionieriModel();
		
		return (string)$sp->clear()->whereId($record["id_spedizioniere"])->field("titolo");
	}
	
	public function infoGatewayCrud($record)
	{
		$or = new OrdiniresponseModel();
		
		if (trim($record["orders"]["cart_uid"]))
		{
			$res = $or->where(array(
				"cart_uid"	=>	$record["orders"]["cart_uid"],
			))->limit(1)->orderBy("id_order_gateway_response desc")->findAll();
			
			if (count($res) > 0)
			{
				$label = $res[0]["orders_gateway_response"]["risultato_transazione"] ? "success" : "danger";
				return "<a class='iframe ext text-$label' href='".Url::getRoot()."/ordini/vediresponse/".$res[0]["orders_gateway_response"]["cart_uid"]."?partial=Y'><i class='fa fa-info-circle'></i></a>";
			}
		}
		
		return "";
	}
	
	public static function statoGestionale($ordine)
	{
		if ((trim($ordine["codice_gestionale"]) || $ordine["inviato_al_gestionale"]) && !trim($ordine["errore_gestionale"]))
			return 1;
		else if (trim($ordine["errore_gestionale"]))
			return -1;
		else
			return 0;
	}
	
	public function inviatoGestionaleCrud($record)
	{
		if (self::statoGestionale($record["orders"]) > 0)
		{
			$label = trim($record["orders"]["codice_gestionale"]) ? "success" : "info";
			
			return "<i style='font-size:16px;' class='fa fa-check text text-$label'></i>";
		}
		else if (self::statoGestionale($record["orders"]) < 0)
			return "<i style='font-size:16px;' class='fa fa-ban text text-danger'></i>";
	}
	
	public function gElencoProdottiPerFeedback($lingua, $record)
	{
		if (!isset($record["id_o"]))
			return "";
		
		$r = new RigheModel();
		
		$righeOrdine = $r->clear()->where(array("id_o"=>(int)$record["id_o"]))->send();
		
		$linguaUrl = $lingua ? "/$lingua/" : "/";
		
		ob_start();
		include tpf("/Elementi/Placeholder/elenco_prodotti_per_feedback.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function gLinkOrdine($lingua, $record)
	{
		if (!isset($record["id_o"]))
			return "";
		
		$linguaUrl = $lingua ? "/$lingua/" : "/";
		
		return Domain::$publicUrl.$linguaUrl."resoconto-acquisto/".$record["id_o"]."/".$record["cart_uid"]."/".$record["admin_token"]."?n=y";
	}
	
	public static function analizzaErroriCheckout($strutturaErrori)
	{
		$mostraCampiFatturazione = $mostraCampiSpedizione = $mostraCampiIndirizzoFatturazione = false;
		
		$fields = array();
		
		if (isset($strutturaErrori["Fields"]))
		{
			$fields = array_keys($strutturaErrori["Fields"]);
			
			$mysqli = Factory_Db::getInstance(DATABASE_TYPE);
			
			foreach ($fields as $f)
			{
				if ($f == "accetto")
					continue;
				
				$type = $mysqli->getTypes("regusers", $f, false, true);
				
				if ($type !== false && $f != "indirizzo_spedizione")
					$mostraCampiFatturazione = true;
				
				$type = $mysqli->getTypes("spedizioni", $f, false, true);
				
				if ($type !== false)
					$mostraCampiSpedizione = true;
				
				if (in_array($f, array("nazione","provincia","dprovincia","citta","indirizzo","cap")))
					$mostraCampiIndirizzoFatturazione = true;
			}
		}
		
		return array($mostraCampiFatturazione, $mostraCampiSpedizione, $mostraCampiIndirizzoFatturazione);
	}
	
	public function whereClauseEscludiAnnullati()
	{
		$this->aWhere(array(
			"ne"	=>	array(
				"stato"	=>	"deleted",
			),
		));
		
		return $this;
	}
	
	// Controlla che non abbia fatto più di tot ordini nello stesso giorno dallo stesso IP
	public function checkNumeroOrdini()
	{
		if (v("salva_ip") && (int)v("numero_massimo_ordini_giornalieri_stesso_ip") > 0)
		{
			$numeroMassimo = (int)v("numero_massimo_ordini_giornalieri_stesso_ip");
			$ip = getIp();
			
			$ipfModel = new IpfilterModel();
			
			if (!$ipfModel->check($ip, 1))
			{
				$numero = $this->clear()->where(array(
					"ip"	=>	sanitizeAll($ip),
				))->sWhere(array(
					"date_format(data_creazione,'%Y-%m-%d') = ?",
					array(
						date("Y-m-d"),
					)
				))->rowNumber();
				
				if ($numero >= $numeroMassimo)
				{
					header('HTTP/1.0 403 Not Found');
					die();
				}
			}
		}
	}
	
	public static function ordineNonEsistenteONonPending()
	{
		$clean['banca_token'] = isset($_GET["banca_token"]) ? sanitizeAll($_GET["banca_token"]) : "";
		$clean['cart_uid'] = isset($_GET["cart_uid"]) ? sanitizeAll($_GET["cart_uid"]) : "";
		
		$oModel = new OrdiniModel();
		
		$oModel->clear()->where(array(
			"cart_uid"	=>	sanitizeAll($clean['cart_uid']),
		));
		
		if ($clean['banca_token'])
			$oModel->aWhere(array(
				"banca_token"	=>	$clean['banca_token'],
			));
		
		$ordineEsistente = $oModel->rowNumber();
		
// 		var_dump($ordineEsistente);
		
		$ordinePending =  $oModel->addWherePending()->rowNumber();
		
// 		var_dump($ordinePending);
		
		return (!$ordineEsistente || !$ordinePending);
	}
	
	public static function getWhereClauseRicercaLibera($search)
	{
		$tokens = explode(" ", $search);
		$andArray = array();
		$iCerca = 8;
		
		foreach ($tokens as $token)
		{
			$andArray[str_repeat(" ", $iCerca)."lk"] = array(
				"n!concat(orders.ragione_sociale,' ',orders.nome,' ',orders.cognome,' ',orders.email)"	=>	sanitizeAll(htmlentitydecode($token)),
			);
			
			$iCerca++;
		}
		
		return $andArray;
	}
	
	public function cleanDataPagamento($record)
	{
		$formato = "d-m-Y H:i:s";

		if (isset($record[$this->_tables]["data_pagamento"]) && $record[$this->_tables]["data_pagamento"])
			return date($formato,strtotime($record[$this->_tables]["data_pagamento"]));

		return "";
	}

	public function cleanDateTimeConsegna($record)
    {
		$formato = "d-m-Y";
		
		if (isset($record[$this->_tables]["data_consegna"]) && $record[$this->_tables]["data_consegna"])
			return date($formato,strtotime($record[$this->_tables]["data_consegna"]));

		return "";
    }
    
    public function dataDocumentoCrud($record)
    {
		$formato = "d-m-Y";
		
		if (isset($record[$this->_tables]["data_documento"]) && $record[$this->_tables]["data_documento"])
			return date($formato,strtotime($record[$this->_tables]["data_documento"]));

		return "";
    }
    
    public function datePagamentoRimborsoCrud($record)
	{
		$formato = "d-m-Y H:i";
		
		$html = "";
		
		if ($record[$this->_tables]["data_pagamento"])
			$html .= "<div><b>".gtext("P").":</b> <b class='text-success'>".date($formato,strtotime($record[$this->_tables]["data_pagamento"]))."</b></div>";
		
		if ($record[$this->_tables]["data_rimborso"])
			$html .= "<div><b>".gtext("R").":</b> <b class='text-danger'>".date($formato,strtotime($record[$this->_tables]["data_rimborso"]))."</b></div>";
		
		return $html;
	}
    
    public function commessoCrud($record)
    {
		if ($record["orders"]["id_commesso"])
			return CommessiModel::getTitolo((int)$record["orders"]["id_commesso"]);
		
		return "";
    }
	
	// Importa i valori della testata dall'array $valori
	public function importaDatiDaArray($valori)
	{
		$fields = OpzioniModel::codice("CAMPI_SALVATAGGIO_ORDINE");
		
		foreach ($fields as $k => $v)
		{
			if (isset($valori[$k]))
				$this->setValue($k, $valori[$k]);
		}
	}
	
	public function setNumeroDocumento()
	{
		$sezionale = isset($this->values["sezionale"]) ? $this->values["sezionale"] : "";
		
		$this->clear()->select("max(numero_documento) as numero")->where(array(
			"sezionale"	=>	sanitizeDb($sezionale),
		))->sWhere(array(
			"DATE_FORMAT(data_creazione, '%Y') = ?",
			array(date("Y"))
		));
		
		if (v("usa_transactions"))
			$this->forUpdate();
		
		$documento = $this->send();
		
		if (empty($documento))
			$this->values["numero_documento"] = 1;
		else
			$this->values["numero_documento"] = ((int)$documento[0]["aggregate"]["numero"] + 1);
	}
	
	// Recupera la sorgente negli ordini degli ultimi $ultimiXGiorni giorni, cercando ordini con la stessa mail fatti nelle $cercaIntervalloXOre precedenti
	public function recuperaSorgente($ultimiXGiorni = 3, $cercaIntervalloXOre = 24, $log = null)
	{
		$tempo = time() - ($ultimiXGiorni * 24 * 3600);
		
		$ordiniSenzaSorgente = $this->clear()->select("id_o,email,creation_time")->where(array(
			"sorgente"			=>	"",
			"tipo_ordine"		=>	"W",
			"fonte"				=>	"ORDINE_WEB",
			"id_lista_regalo"	=>	0,
		))->sWhere(array(
			"creation_time > ?", array($tempo)
		))->orderBy("id_o desc")->send(false);
		
		// print_r($ordiniSenzaSorgente);
		
		foreach ($ordiniSenzaSorgente as $o)
		{
			$sorgente = "";
			
			$tempo = (int)$o["creation_time"] - ($cercaIntervalloXOre * 3600);
			
			$ordiniConSorgenteStessoCliente = $this->clear()->select("id_o,email,sorgente")->where(array(
				"ne"	=>	array(
					"sorgente"	=>	""
				),
				"tipo_ordine"		=>	"W",
				"fonte"				=>	"ORDINE_WEB",
				"id_lista_regalo"	=>	0,
				"email"				=>	sanitizeAll($o["email"]),
			))->sWhere(array(
				"creation_time > ? and creation_time < ? and id_o != ?", array($tempo, (int)$o["creation_time"], (int)$o["id_o"])
			))->orderBy("id_o desc")->limit(1)->send(false);
			
			// echo $this->getQuery()."\n\n";
			
			if (count($ordiniConSorgenteStessoCliente) > 0)
				$sorgente = $ordiniConSorgenteStessoCliente[0]["sorgente"];
			
			if (!$sorgente)
			{
				$tempoRicorrente = (int)$o["creation_time"] - (10 * 24 * 3600); // più vecchi di 10 giorni
				
				$numeroOrdiniPrecedentiStessoCliente = $this->clear()->select("id_o,email")->where(array(
					"email"				=>	sanitizeAll($o["email"]),
				))->sWhere(array(
					"creation_time < ? and id_o != ?", array($tempoRicorrente, (int)$o["id_o"])
				))->orderBy("id_o desc")->limit(1)->rowNumber();
				
				if ($numeroOrdiniPrecedentiStessoCliente > 0)
					$sorgente = "Ricorrente";
			}
			
			if ($sorgente)
			{
				// echo $o["id_o"]."-".$sorgente."\n";
				
				$this->sValues(array(
					"sorgente"	=>	$sorgente,
				));
				
				$this->pUpdate((int)$o["id_o"]);
				
				if ($log)
					$log->writeString("SALVATA SORGENTE $sorgente NELL'ORDINE ".(int)$o["id_o"]);
			}
		}
	}
	
	public static function getNazioneRilevanteIvaOrdine($record)
	{
		if ($record["nazione_lista_regalo"] && $record["id_lista_regalo"])
			return $record["nazione_lista_regalo"];
		
		return $record["nazione_spedizione"];
	}
	
	public function nazioneSpedizioneCrud($record)
	{
		$nazione = self::getNazioneRilevanteIvaOrdine($record["orders"]);
		
		return NazioniModel::g()->findTitoloDaCodice($nazione);
	}
	
	public function impostaStato($idO, $stato)
	{
		$sModel = new StatiordineModel();
		$ordine = $this->selectId((int)$idO);
		
		$numero = $sModel->clear()->where(array(
			"codice"	=>	sanitizeAll($stato),
		))->rowNumber();
		
		if ($numero && $ordine["stato"] != $stato)
		{
			$this->sValues(array(
				"stato"	=>	$stato,
			));
			
			return $this->update((int)$idO);
		}
		
		return false;
	}
	
	// Aggiungi una riga esistente ad un ordine esistente
	public function aggiungiRigaEsistenteAOrdine($idO, $idR, $template = "<br />Ordine <b>[ID_ORDINE]</b> del <b>[DATA_ORDINE]</b>")
	{
		$rModel = new RigheModel();
		
		$ordine = $this->selectId((int)$idO);
		$riga = $rModel->selectId((int)$idR);
		
		if (!in_array("title_lingua", self::$colonneAggiuntiveRighe))
			self::$colonneAggiuntiveRighe[] = "title_lingua";
		
		if (!empty($ordine) && !empty($riga) && $riga["id_o"] != $ordine["id_o"])
		{
			$idOrdineOriginario = (int)$riga["id_o"];
			$ordineOriginario = $this->selectId((int)$idOrdineOriginario);
			
			if (!empty($ordineOriginario))
			{
				unset($riga["id_r"]);
				unset($riga["data_creazione"]);
				unset($riga["id_cart"]);
				unset($riga["id_order"]);
				
				$riga["cart_uid"] = $ordine["cart_uid"];
				$riga["id_o"] = $ordine["id_o"];
				$riga["id_admin"] = $ordine["id_admin"];
				$riga["id_o_padre"] = $idOrdineOriginario;
				$riga["id_r_padre"] = (int)$idR;
				
				TraduzioniModel::sLingua(v("lingua_default_frontend"), "front");
				$templateTitle = gtext($template);
				TraduzioniModel::rLingua();
				
				TraduzioniModel::sLingua($ordine["lingua"], "front");
				$templateTitleLingua = gtext($template);
				TraduzioniModel::rLingua();
				
				$templateTitle = str_replace("[ID_ORDINE]", (int)$idOrdineOriginario, $templateTitle);
				$templateTitle = str_replace("[DATA_ORDINE]", date("d/m/Y",strtotime($ordineOriginario["data_creazione"])), $templateTitle);
				
				$templateTitleLingua = str_replace("[ID_ORDINE]", (int)$idOrdineOriginario, $templateTitleLingua);
				$templateTitleLingua = str_replace("[DATA_ORDINE]", date("d/m/Y",strtotime($ordineOriginario["data_creazione"])), $templateTitleLingua);
				
				$riga["title"] = $riga["title"].$templateTitle;
				$riga["title_lingua"] = $riga["title_lingua"].$templateTitleLingua;
				
				$rModel->sValues($riga, "sanitizeDb");
				
				return $rModel->insert();
			}
		}
		
		return false;
	}
	
	public function pdfScaricabile($idO)
	{
		if (v("stati_a_cui_permettere_scarido_pdf_ordine"))
		{
			$arrayStatiOrdineScaricabile = explode(",", v("stati_a_cui_permettere_scarido_pdf_ordine"));
			
			$statoOrdine = $this->clear()->select("stato")->whereId((int)$idO)->field("stato");
			
			if (count($arrayStatiOrdineScaricabile) > 0 && in_array($statoOrdine, $arrayStatiOrdineScaricabile))
				return true;
		}
		
		return false;
	}
	
	public function puoAnnullareDataPagamento($idO = 0)
	{
		$ordine = $this->selectId((int)$idO);
		
		if (!empty($ordine) && !OrdiniModel::conPagamentoOnline($ordine) && $ordine["pagato"] && $ordine["data_pagamento"] && date("Y-m-d", strtotime($ordine["data_pagamento"])) == date("Y-m-d"))
			return true;
		
		return false;
	}
	
	public function puoAnnullareDataAnnullamento($idO = 0)
	{
		$ordine = $this->selectId((int)$idO);
		
		if (!empty($ordine) && $ordine["annullato"] && $ordine["data_annullamento"] && date("Y-m-d", strtotime($ordine["data_annullamento"])) == date("Y-m-d"))
			return true;
		
		return false;
	}
	
	public function puoAnnullareDataRimborso($idO = 0)
	{
		$ordine = $this->selectId((int)$idO);
		
		if (!empty($ordine) && $ordine["rimborsato"] && $ordine["data_rimborso"] && date("Y-m-d", strtotime($ordine["data_rimborso"])) == date("Y-m-d"))
			return true;
		
		return false;
	}
	
	public static function isStatoPending($stato)
	{
		return in_array($stato, self::$statiPending) ? true : false;
	}
	
	public function addWherePending()
	{
		$this->aWhere(array(
			"in"	=>	array(
				"orders.stato"	=>	self::$statiPending,
			)
		));
		
		return $this;
	}
}
