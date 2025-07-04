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

class PromozioniModel extends GenericModel {

	public $lId = 0;
	
	public static $staticIdO = null;
	public static $tipiClientiPromo = array();
	
	public static $erroreCouponUtente = "";
	
	public static $selectIncludiEscludi = null;
	
	public static $checkValoreSconto = false; // se viene fatto il check al valore dello sconto, metodo self::checkValoreSconto()
	
	public function __construct() {
		$this->_tables='promozioni';
		$this->_idFields='id_p';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'promozioni.dal desc,promozioni.al desc';
		$this->_lang = 'It';
		
		if (!isset(self::$selectIncludiEscludi))
			self::$selectIncludiEscludi = array(
				"1"	=>	gtext("Includi"),
				"0"	=>	gtext("Escludi"),
			);
		
		parent::__construct();
	}
	
	public function setFormStruct($id = 0)
	{
		$labelSconto = 'Valore sconto';
		
		if (!v("attiva_promo_sconto_assoluto"))
		{
			$tipo = $this->clear()->where(array("id_p"=>(int)$id))->field("tipo_sconto");
			
			$labelSconto .= ($tipo == "ASSOLUTO") ? " (in €)" : " (in %)";
		}
		
		$opzioniTipoCredito = array('ESAURIMENTO'=>'AD ESAURIMENTO','INFINITO'=>'INFINITO');
		
		$record = $this->selectId((int)$id);
		
		if ((!empty($record) && $record["id_user"]) || (isset($_GET["id_user"]) && (int)$_GET["id_user"]))
			$opzioniTipoCredito = array('INFINITO'=>'INFINITO');
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'titolo'		=>	array(
					'labelString'=>	'Titolo',
				),
				'codice'		=>	array(
					'labelString'=>	'Codice del coupon',
				),
				'dal'		=>	array(
					'labelString'=>	'Attiva dal',
					'className'	=>	'data_field form-control',
				),
				'al'		=>	array(
					'labelString'=>	'Attiva fino al',
					'className'	=>	'data_field form-control',
				),
				'sconto'		=>	array(
					'labelString'=>	$labelSconto,
				),
				'attivo'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Coupon attivo?',
					'options'	=>	array('sì'=>'Y','no'=>'N'),
				),
				'numero_utilizzi'		=>	array(
					'labelString'=>	'Numero massimo totale di utilizzi',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Numero massimo di utilizzi totale, considerando anche clienti diversi (diversi indirizzi email)")."</div>"
					),
				),
				'numero_utilizzi_per_email'		=>	array(
					'labelString'=>	'Numero massimo di utilizzi per email',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se impostato a 0, non viene considerato un numero massimo di utilizzo per singola mail.")."</div>"
					),
				),
				'tipo_sconto'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Tipo di sconto',
					'options'	=>	array('PERCENTUALE'=>'In percentuale','ASSOLUTO'=>'Assoluto'),
					'reverse'	=>	'yes',
					'attributes'=>	"on-c='check-v'",
				),
				'tipo_credito'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Tipo di credito',
					'options'	=>	$opzioniTipoCredito,
					'reverse'	=>	'yes',
					'entryAttributes'	=>	array(
						"visible-f"	=>	"tipo_sconto",
						"visible-v"	=>	"ASSOLUTO",
					),
				),
				'id_p'	=>	array(
					'type'		=>	'Hidden'
				),
				'sconto_valido_sopra_euro'		=>	array(
					'labelString'=>	'Sconto valido se si spende almeno (€)',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Verrà applicato solo se il totale del carrello sarà maggiore o uguale alla cifra indicata (spese di spedizione escluse).")."</div>"
					),
				),
				'nazione_validita'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Nazione in cui vale il buono",
					"options"	=>	array("" => "Seleziona") + $this->selectNazioneNoDefault(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se la nazione viene lasciata vuota, tale coupon ha validità in TUTTE LE NAZIONI")."</div>"
					),
				),
			),
		);
	}
	
	public function relations() {
        return array(
			'categorie' => array("HAS_MANY", 'PromozionicategorieModel', 'id_p', null, "CASCADE"),
			'pagine' => array("HAS_MANY", 'PromozionipagineModel', 'id_p', null, "CASCADE"),
			'tipi_clienti' => array("HAS_MANY", 'PromozionitipiclientiModel', 'id_p', null, "CASCADE"),
			'righe' => array("BELONGS_TO", 'RigheModel', 'id_r',null,"CASCADE"),
        );
    }
    
    public function processaEventiPromozione($idPromozione)
	{
		$promozione = $this->selectId((int)$idPromozione);
		
		if (!empty($promozione) && isset($promozione["email"]) && $promozione["email"] && checkMail($promozione["email"]) && $promozione["testo"] && $promozione["attivo"] == "Y" && $promozione["tipo_sconto"] == "ASSOLUTO")
			EventiretargetingModel::processaPromo($idPromozione);
	}
    
    protected function checkValoreSconto()
	{
		if (!self::$checkValoreSconto || (isset($this->values["tipo_sconto"]) && $this->values["tipo_sconto"] == "ASSOLUTO") || (isset($this->values["sconto"]) && number_format(setPrice($this->values["sconto"]),2,".","") <= 100))
			return true;
		
		return false;
	}
    
	public function insert()
	{
		if (strcmp($this->values["codice"],"") === 0)
		{
			$this->values["codice"] = md5(randString(22).microtime().uniqid(mt_rand(),true));
		}
		
		$this->values["dal"] = reverseData($this->values["dal"]);
		$this->values["al"] = reverseData($this->values["al"]);
		
		if (checkIsoDate($this->values["dal"]) and checkIsoDate($this->values["al"]))
		{
			$res = $this->clear()->where(array("codice"=>$this->values["codice"]))->send();
			
			if (count($res) === 0)
			{
				if ($this->checkValoreSconto())
				{
					$res = parent::insert();
					
					if ($res)
						$this->processaEventiPromozione($this->lId);
					
					return $res;
				}
				else
				{
					$this->notice = "<div class='alert alert-danger'>".gtext("Attenzione: non è possibile uno sconto percentuale maggiore del 100%")."</div>";
					$this->result = false;
				}
			}
			else
			{
				$this->notice = "<div class='alert alert-danger'>".gtext("Attenzione: il codice promozione è già presente, si prega di selezionarne un altro")."</div>";
				$this->result = false;
			}
		}
		else
		{
			$this->notice = "<div class='alert alert-danger'>".gtext("Si prega di ricontrollare il formato delle date di validità della promozione")."</div>";
			$this->result = false;
		}
		
		$this->lId = $this->lastId();
	}
	
	public function update($id = null, $where = null)
	{
		$clean["id"] = (int)$id;
		
		if (isset($this->values["al"]))
			$this->values["al"] = reverseData($this->values["al"]);
		
		if (isset($this->values["dal"]))
			$this->values["dal"] = reverseData($this->values["dal"]);
		
		if ((!isset($this->values["dal"]) || checkIsoDate($this->values["dal"])) && (!isset($this->values["al"]) || checkIsoDate($this->values["al"])))
		{
			$numero = 0;
			
			if (isset($this->values["codice"]))
				$numero = $this->clear()->where(array(
					"codice"	=>	$this->values["codice"],
					"ne"	=>	array("id_p" => $clean["id"]),
				))->rowNumber();
			
			if ((int)$numero === 0)
			{
				if ($this->checkValoreSconto())
				{
					$res = parent::update($id);
					
	// 				if ($res)
	// 					$this->processaEventiPromozione($id);
					
					return $res;
				}
				else
				{
					$this->notice = "<div class='alert alert-danger'>".gtext("Attenzione: non è possibile uno sconto percentuale maggiore del 100%")."</div>";
					$this->result = false;
				}
			}
			else
			{
				$this->notice = "<div class='alert alert-danger'>".gtext("Attenzione: il codice promozione è già presente, si prega di selezionarne un altro")."</div>";
				$this->result = false;
			}
		}
		else
		{
			$this->notice = "<div class='alert alert-danger'>".gtext("Si prega di ricontrollare il formato delle date di validità della promozione")."</div>";
			$this->result = false;
		}
	}
	
	public function isActive($id_p)
	{
		$clean["id_p"] = (int)$id_p;
		
		$field = $this->clear()->selectId($clean["id_p"]);
		
		if (count($field) > 0 and $field["attivo"] === "Y")
		{
			return true;
		}
		return false;
	}
	
	public function recordExists($id_p)
	{
		$clean["id_p"] = (int)$id_p;
		
		$res = $this->clear()->where(array("id_p"=>$clean["idp"]))->send();
		
		if (count($res) > 0)
		{
			return true;
		}
		return false;
	}
	
	public function elencoTipiClientiPromo($id_p)
	{
		if (!isset(self::$tipiClientiPromo[$id_p]))
			self::$tipiClientiPromo[$id_p] =  $this->clear()->select("promozioni_tipi_clienti.codice")->inner(array("tipi_clienti"))->where(array(
				"id_p"	=>	(int)$id_p,
			))->toList("promozioni_tipi_clienti.codice")->send();
		
// 		print_r(self::$tipiClientiPromo[$id_p]);
		
		return self::$tipiClientiPromo[$id_p];
	}
	
	//controllo che il coupon sia attivo
	public function isActiveCoupon($codice, $ido = null, $checkCart = true)
	{
		if (!isset($ido))
			$ido = self::$staticIdO;
			
		$clean["codice"] = sanitizeAll($codice);
		
// 		if ($checkCart && CartModel::numeroGifCartInCarrello() > 0)
// 			return false;
		
		$res = $this->clear()->where(array("codice"=>$clean["codice"],"attivo"=>"Y"))->send();
		
		if (count($res) > 0)
		{
			$dal = getTimeStampComplete($res[0]["promozioni"]["dal"]);
			$al = getTimeStampComplete($res[0]["promozioni"]["al"]) + 86400;
			
			$now = time();
			
			if ($now >= $dal and $now <= $al)
			{
				if ($res[0]["promozioni"]["tipo_sconto"] == "ASSOLUTO" && self::gNumeroEuroRimasti($res[0]["promozioni"]["id_p"], $ido) <= 0)
					return false;
				
				// controllo che il coupon sia usato nella nazione di validità
				if (v("gift_card_validita_nazionale") && $checkCart && isset($_POST["nazione_spedizione"]) && trim($_POST["nazione_spedizione"]) && trim($res[0]["promozioni"]["nazione_validita"]))
				{
					$nazioneSpedizione = $_POST["nazione_spedizione"];
					
					if (
						isset($_POST["nazione"]) && 
						((isset($_POST["spedisci_dati_fatturazione"]) && strcmp($_POST["spedisci_dati_fatturazione"],"Y") === 0) || !v("attiva_spedizione"))
					)
						$nazioneSpedizione = $_POST["nazione"];
					
					if (ListeregaloModel::hasIdLista())
					{
						$nazioneLista = ListeregaloModel::nazioneListaRegalo(0, User::$idLista);
						
						if ($nazioneLista)
							$nazioneSpedizione = $nazioneLista;
					}
						
					if (trim($nazioneSpedizione) != trim($res[0]["promozioni"]["nazione_validita"]))
					{
						self::$erroreCouponUtente = "Il codice coupon inserito è valido solo per la nazione ".NazioniModel::g()->findTitoloDaCodice($res[0]["promozioni"]["nazione_validita"]);
						
						return false;
					}
				}
				
				// Controllo che non compri uma gift card con un'altra gift card
				if ($checkCart && $res[0]["promozioni"]["tipo_sconto"] == "ASSOLUTO" && $res[0]["promozioni"]["fonte"] == "GIFT_CARD" && CartModel::numeroGifCartInCarrello() > 0)
					return false;
				
				$numeroUtilizzi = (int)$res[0]["promozioni"]["numero_utilizzi"];
				
				$numeroVolteUsata = (int)$this->getNUsata($res[0]["promozioni"]["id_p"], $ido);
				
				if ($numeroUtilizzi > $numeroVolteUsata)
				{
					$numeroUtilizziPerEmail = (int)$res[0]["promozioni"]["numero_utilizzi_per_email"];
					$emailInPromo = (string)$res[0]["promozioni"]["email"];
					
					// controllo il numero di utilizzi per singola email
					if ($numeroUtilizziPerEmail > 0 && isset($_POST["email"]) && checkMail($_POST["email"]) && $numeroUtilizziPerEmail <= (int)$this->getNUsata($res[0]["promozioni"]["id_p"], $ido, $_POST["email"]))
						return false;
					
// 					if (isset($_POST["email"]) && checkMail($_POST["email"]) && $emailInPromo && checkMail($emailInPromo) && trim(strtolower((string)$_POST["email"])) != trim(strtolower($emailInPromo)))
// 						return false;
					
					// controllo il tipo cliente
					if (count($this->elencoTipiClientiPromo($res[0]["promozioni"]["id_p"])) && isset($_POST["tipo_cliente"]) && !in_array($_POST["tipo_cliente"], $this->elencoTipiClientiPromo($res[0]["promozioni"]["id_p"])))
						return false;
					
					if (!$checkCart)
						return true;
					
					$cart = new CartModel();
					$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
					$prodottiCarrello = $cart->clear()->where(array("cart_uid"=>$clean["cart_uid"]))->toList("id_page")->send();
					
					if (count($prodottiCarrello) > 0)
					{
						$scontoValidoSopraEuro = $res[0]["promozioni"]["sconto_valido_sopra_euro"];
						
						if ($scontoValidoSopraEuro > 0)
							$totaleCarrello = ($res[0]["promozioni"]["tipo_sconto"] == "ASSOLUTO") ? getTotalN(true) : getSubTotalN(v("prezzi_ivati_in_carrello"));
						
						if ($scontoValidoSopraEuro <= 0 || number_format($totaleCarrello,2,".","") >= $scontoValidoSopraEuro)
						{
							$prodottiPromozione = $this->elencoProdottiPromozione($clean["codice"]);
							
							if ((int)count($prodottiPromozione) === 0)
								return false;
							
							foreach ($prodottiCarrello as $idPage)
							{
								if (in_array($idPage, $prodottiPromozione))
									return true;
							}
						}
					}
					
					return false;
				}
				else
					return false;
			}
		}
		
		return false;
	}
	
	public static function checkProdottoInPromo($idPage)
	{
		if ((int)count(User::$prodottiInCoupon) === 0 || in_array($idPage, User::$prodottiInCoupon))
			return true;
		
		return false;
	}
	
	public static function hasCouponAssoluto($ido = null)
	{
		$coupon = self::getCouponAttivo($ido);
		
		return (!empty($coupon) && $coupon["tipo_sconto"] == "ASSOLUTO") ? true : false;
	}
	
	// Restituisce il coupon attivo
	public static function getCouponAttivo($ido = null)
	{
		$p = new PromozioniModel();
		
		if (isset(User::$coupon) && $p->isActiveCoupon(User::$coupon, $ido))
			return $p->getCoupon(User::$coupon);
		
		return array();
	}
	
	//restituisce tutti i dati del coupon
	public function getCoupon($codice)
	{
		$clean["codice"] = sanitizeAll($codice);
		
		$res = $this->clear()->where(array("codice"=>$clean["codice"]))->send();
		
		if (count($res) > 0)
		{
			return $res[0]["promozioni"];
		}
		return array();
	}
	
	public function getNUsata($id_p, $ido = null, $email = null)
	{
		if (!isset($ido))
			$ido = self::$staticIdO;
		
		$clean["id_p"] = (int)$id_p;
		
		$res = $this->clear()->where(array("id_p"=>$clean["id_p"]))->send();
		
		if (count($res) > 0)
		{
			$clean['coupon'] = sanitizeAll($res[0]["promozioni"]["codice"]);
			
			$o = new OrdiniModel();
			
			$o->clear()->select("orders.id_o")->where(array("codice_promozione"=>$clean['coupon']));
			
			// Escluso gli annullati dal conteggio
			if (v("non_conteggiare_ordini_annullati"))
				$o->whereClauseEscludiAnnullati();
			
			if ($ido)
				$o->sWhere(array("id_o != ?",array((int)$ido)));
			
			if ($email)
				$o->aWhere(array(
					"email"	=>	sanitizeAll($email),
				));
			
			$res2 = $o->rowNumber();
			
			return $res2;
		}
		
		return 0;
	}
	
	public static function gTabellaPromoEuroUsati($ido = null)
	{
		$o = new OrdiniModel();
		
		$o->clear()->select("orders.id_p,sum(euro_promozione - sconto) as SOMMA")->where(array(
			"ne"	=>	array(
				"id_p"	=>	0,
			),
		));
		
		if ($ido)
			$o->sWhere(array("id_o != ?",array((int)$ido)));
		
		return $o->groupBy("id_p")->toList("orders.id_p", "aggregate.SOMMA")->send();
	}
	
	public static function gNumeroEuroUsati($id_p, $ido = null)
	{
		if (!isset($ido))
			$ido = self::$staticIdO;
		
		$clean["id_p"] = (int)$id_p;
		
		$o = new OrdiniModel();
		
		$o->clear()->select("sum(euro_promozione - sconto) as SOMMA")->where(array("id_p"=>$clean['id_p']));
		
		// Escluso gli annullati dal conteggio
		if (v("non_conteggiare_ordini_annullati"))
			$o->whereClauseEscludiAnnullati();
		
		if ($ido)
			$o->sWhere(array("id_o != ?",array((int)$ido)));
		
		$res = $o->send();
		
		if (count($res) > 0)
			return (float)$res[0]["aggregate"]["SOMMA"];
		
		return 0;
	}
	
	public static function gNumeroEuroRimasti($id_p, $ido = null)
	{
		if (!isset($ido))
			$ido = self::$staticIdO;
		
		$clean["id_p"] = (int)$id_p;
		
		$p = new PromozioniModel();
		
		$promozione = $p->selectId($clean["id_p"]);
		
		if (!empty($promozione))
		{
			if ($promozione["tipo_sconto"] == "ASSOLUTO" && $promozione["tipo_credito"] == "INFINITO" && $promozione["sconto"] > 0)
				return $promozione["sconto"];
			
			$usati = self::gNumeroEuroUsati($id_p, $ido);
			
			if ($promozione["tipo_sconto"] == "ASSOLUTO" && $promozione["sconto"] > $usati)
				return ($promozione["sconto"] - $usati);
		}
		
		return 0;
	}
	
	// Restituisce l'elenco dei prodotti in $idsC (array di id_c) considerando l'inclusione o l'esclusione di marchi ($whereMarchi) e l'inclusione o l'esclusione di prodotti ($wherePagine)
	protected function getElencoProdottiCategorie($idsC, $whereMarchi = array(), $wherePagine = array())
	{
		$c = new CategoriesModel();
		$p = new PagesModel();
		
		$idPages = $arrayIdCategorie = array();
		
		foreach ($idsC as $idC)
		{
			if (in_array($idC, $arrayIdCategorie))
				continue;
			
			$children = $c->children((int)$idC, true);
			
			if (count($children) > 0)
				$arrayIdCategorie = array_merge($arrayIdCategorie, $children);
			
			$bindedValues = $children;
			$bindedValues[] = (int)$idC;
			
			$pages = $p->clear()->select("id_page")->where(array(
				"attivo" => "Y",
				"principale"=>"Y",
			))
			->sWhere(array("(pages.id_c in(".$p->placeholdersFromArray($children).") OR pages.id_page in (select id_page from pages_categories where id_c = ?))",$bindedValues))
			->aWhere($whereMarchi)
			->aWhere($wherePagine)
			->toList("id_page")->send();
			
			$idPages = array_merge($idPages, $pages);
		}
		
		$idPages = array_unique($idPages);
		
		return $idPages;
	}
	
	// Estrae l'elenco di tuti i prodotti nella promozione
	public function elencoProdottiPromozione($coupon)
	{
		$clean["codice"] = sanitizeAll($coupon);
		
		$promozione = $this->clear()->where(array("codice"=>$clean["codice"]))->record();
		
		$pc = new PromozionicategorieModel();
		$pp = new PromozionipagineModel();
		$p = new PagesModel();
		$c = new CategoriesModel();
		$pm = new PromozionimarchiModel();
		
		###### CATEGORIE ####
		$idPagineIncluseDaCategorie = $idPagineEscluseDaCategorie = array();
		
		$idCsIncluse = $pc->clear()->where(array(
			"id_p"	=>	(int)$promozione["id_p"],
			"includi"	=>	1,
		))->toList("id_c")->send();
		
		$idCsEscluse = $pc->aWhere(array(
			"includi"	=>	0,
		))->toList("id_c")->send();
		
		if (count($idCsIncluse) > 0)
			$idPagineIncluseDaCategorie = $this->getElencoProdottiCategorie($idCsIncluse);
		
		if (count($idCsEscluse) > 0)
			$idPagineEscluseDaCategorie = $this->getElencoProdottiCategorie($idCsEscluse);
		
		###### MARCHI ####
		$idMarchisInclusi = $idMarchisEsclusi = array();
		
		if (v("usa_marchi") && v("attiva_filtro_marchi_su_promo"))
		{
			$idMarchisInclusi = $pm->clear()->select("promozioni_marchi.id_marchio")->inner(array("marchio"))->where(array(
				"id_p"		=>	(int)$promozione["id_p"],
				"includi"	=>	1,
			))->toList("id_marchio")->send();
			
			$idMarchisEsclusi = $pm->aWhere(array(
				"includi"	=>	0,
			))->toList("id_marchio")->send();
		}
		
		$whereMarchi = array();
		
		if (count($idMarchisInclusi) > 0)
			$whereMarchi = array(
				" in "	=>	array(
					"pages.id_marchio"	=>	forceIntDeep($idMarchisInclusi),
				)
			);
		else if (count($idMarchisEsclusi) > 0)
			$whereMarchi = array(
				" nin "	=>	array(
					"pages.id_marchio"	=>	forceIntDeep($idMarchisEsclusi),
				)
			);
		
		###### PAGINE ####
		$idPagesIncluse = $pp->clear()->select("promozioni_pages.id_page")->inner(array("pagina"))->where(array(
			"id_p"	=>	(int)$promozione["id_p"],
			"promozioni_pages.includi"	=>	1,
		))->toList("id_page")->send();
		
		$idPagesEscluse = $pp->aWhere(array(
			"promozioni_pages.includi"	=>	0,
		))->toList("id_page")->send();
		
		$idPagesIncluse = array_merge($idPagesIncluse, $idPagineIncluseDaCategorie);
		$idPagesEscluse = array_merge($idPagesEscluse, $idPagineEscluseDaCategorie);
		
		$wherePagine = array();
		
		if (count($idPagesIncluse) > 0)
			$wherePagine = array(
				"  in "	=>	array(
					"pages.id_page"	=>	forceIntDeep($idPagesIncluse),
				)
			);
		else if (count($idPagesEscluse) > 0)
			$wherePagine = array(
				"  nin "	=>	array(
					"pages.id_page"	=>	forceIntDeep($idPagesEscluse),
				)
			);
		######
		
		$idC = $c->clear()->where(array("section" => Parametri::$nomeSezioneProdotti))->field("id_c");
		
		$children = $c->children((int)$idC, true);
		
		$idPagesIncluse = $p->clear()->select("id_page")->where(array(
				"attivo" => "Y",
				"principale"=>"Y",
				"in" => array("-id_c" => $children),
			))
			->aWhere($whereMarchi)
			->aWhere($wherePagine)
			->toList("id_page")
			->send();
		
		return $idPagesIncluse;
	}
	
	public function sconto($record)
	{
		$valore = "<b>".$record["promozioni"]["sconto"]."</b>";
		
		if ($record["promozioni"]["tipo_sconto"] == "ASSOLUTO")
			$valore .= " €<br /><span class='text text-primary'><i>usato: ".self::gNumeroEuroUsati($record["promozioni"]["id_p"])."€</i></span>";
		else
			$valore .= " %";
		
		return $valore;
	}
	
	// Crea la promo dalla riga ordine
	public function aggiungiDaRigaOrdine($idR)
	{
		$rModel = new RigheModel();
		
		$riga = $rModel->selectId((int)$idR);
		$oModel = new OrdiniModel();
		
		if (empty($riga))
			return;
		
		$attivo = OrdiniModel::isPagato($riga["id_o"]) ? "Y" : "N";
		
		$promo = $this->clear()->where(array(
			"id_r"	=>	(int)$idR,
		))->send(false);
		
		$ora = new DateTime();
		$ora->modify("+2 years");
		
		if (count($promo) > 0)
		{
			foreach ($promo as $p)
			{
				if ($p["attivo"] != $attivo)
				{
					$this->sValues(array(
						"dal"	=>	date("d-m-Y", strtotime($p["dal"])),
						"al"	=>	$ora->format("d-m-Y"),
						"codice"	=>	$p["codice"],
						"attivo"	=>	$attivo,
					));
					
					$this->update((int)$p["id_p"]);
				}
			}
		}
		else if ($attivo == "Y")
		{
			$recordOrdine = $oModel->selectId((int)$riga["id_o"]);
			$nazioneValidita = "IT";
			
			if (!empty($recordOrdine))
				$nazioneValidita = $recordOrdine["nazione_lista_regalo"] ? strtoupper($recordOrdine["nazione_lista_regalo"]) : strtoupper($recordOrdine["nazione_spedizione"]);
				
			$elementiRiga = RigheelementiModel::getElementiRiga($idR);
			
			for ($i = 0; $i < $riga["quantity"]; $i++)
			{
				$this->sValues(array(
					"dal"	=>	date("d-m-Y"),
					"al"	=>	$ora->format("d-m-Y"),
					"sconto"	=>	$riga["prezzo_intero_ivato"],
					"titolo"	=>	$riga["title"],
					"codice"	=>	$this->getCodiceRandom("GIFT".generateString(10)), //md5(randString(20).microtime().uniqid(mt_rand(),true)),
					"numero_utilizzi"	=>	9999,
					"tipo_sconto"	=>	"ASSOLUTO",
					"id_r"		=>	$idR,
					"attivo"	=>	$attivo,
					"fonte"		=>	"GIFT_CARD",
					"email"		=>	isset($elementiRiga[$i]["email"]) ? trim($elementiRiga[$i]["email"]) : "",
					"testo"		=>	isset($elementiRiga[$i]["testo"]) ? $elementiRiga[$i]["testo"] : "",
					"nome"		=>	$oModel->getNome($riga["id_o"]),
					"creation_time"	=>	time(),
					"lingua"	=>	$riga["lingua"],
					"nazione_navigazione"	=>	$riga["nazione_navigazione"],
					"nazione_validita"	=>	$nazioneValidita,
				), "sanitizeDb");
				
				$this->insert();
			}
		}
	}
	
	public function getCodiceRandom($codice)
	{
		$clean["codice"] = sanitizeAll($codice);
		
		$res = $this->clear()->select("codice")->where(array("codice"=>$clean["codice"]))->send();
		
		if (count($res) > 0)
		{
			$nCodice = "GIFT".generateString(10);
			return $this->getCodiceRandom($nCodice);
		}
		
		return $clean["codice"];
	}
	
	public function ordine($record)
	{
		if ($record["orders"]["id_o"])
			return "<a class='iframe' href='".Url::getRoot()."ordini/vedi/".$record["orders"]["id_o"]."?partial=Y&nobuttons=Y'>#".$record["orders"]["id_o"]."</a>";
		
		return "";
	}
	
	public static function getPromoRigaOrdine($idR)
	{
		$p = new PromozioniModel();
		
		return $p->clear()->where(array(
			"id_r"	=>	(int)$idR,
		))->send(false);
	}
	
	public function gSconto($lingua, $record)
	{
		return setPriceReverse($record["sconto"]);
	}
	
	public function gCodicePromo($lingua, $record)
	{
		return $record["codice"];
	}
	
	public function gDedicaPromo($lingua, $promo)
	{
		ob_start();
		$tipoOutput = "mail_al_cliente";
		include tpf("/Elementi/Placeholder/dedica_gift_card.php");
		$output = ob_get_clean();
		
		return $output;
	}
	
	public function deletable($id) {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && $record["id_r"])
			return false;
		
		return true;
	}
	
	public function vedi($record)
	{
		return "<a title='".gtext("Dettaglio coupon")."' class='iframe action_iframe' href='".Url::getRoot()."promozioni/form/update/".$record["promozioni"]["id_p"]."?partial=Y&nobuttons=Y'>".$record["promozioni"]["titolo"]."</a>";
	}
	
	public function getIdUtente($idPromo)
	{
		return (int)$this->clear()->whereId((int)$idPromo)->field("id_user");
	}
	
	public function inviaCouponCrud($record)
	{
		$html = "";
		
		if ($record["promozioni"]["id_user"] && User::attivo($record["promozioni"]["id_user"]))
		{
			$utente = RegusersModel::g()->selectId((int)$record["promozioni"]["id_user"]);
			
			$html = "<a title='".gtext("Invia il codice coupon")."' class='ajlink' href='".Url::getRoot()."promozioni/inviacouponalcliente/".$record["promozioni"]["id_p"]."'><i class='fa fa-envelope'></i></a>";
			
			$numeroInviate = MailordiniModel::numeroInviate("promozioni", $record["promozioni"]["id_p"], $utente["username"]);
			
			$label = $numeroInviate ? "info" : "default";
			
			$html .= " <span title='".gtext("Numero di volte che il coupon è stato inviato")."' class='label label-$label'>$numeroInviate</span>";
		}
		
		return $html;
	}
	
	public static function promoUtenteModel($idUser = 0, $idP = 0)
    {
		$model = self::g();
		
		if ($idUser)
			$model->aWhere(array(
				"id_user"	=>	(int)$idUser,
			));
		
		if ($idP)
			$model->aWhere(array(
				"id_p"	=>	(int)$idP,
			));
		
		return $model;
    }
    
     public static function numeroPromoUtente($idUser = 0, $idP = 0)
    {
		return self::promoUtenteModel($idUser, $idP)->rowNumber();
    }
	
	// Invia il codice del coupon via mail
	public function inviaCodiceCouponAlCliente($idPromo)
	{
		$record = $this->select("*")->inner("regusers")->on("regusers.id_user = promozioni.id_user")->where(array(
			"promozioni.id_p"	=>	(int)$idPromo,
			"regusers.".Users_CheckAdmin::$statusFieldName	=>	Users_CheckAdmin::$statusFieldActiveValue,
		))->first();
		
		if (!empty($record) && checkMail($record["regusers"]["username"]))
		{
			$res = MailordiniModel::inviaMail(array(
				"emails"	=>	array($record["regusers"]["username"]),
				"oggetto"	=>	"Il tuo codice coupon ".$record["promozioni"]["codice"],
				"tipologia"	=>	"CODICE COUPON AD AGENTE",
				"tabella"	=>	"promozioni",
				"id_elemento"	=>	(int)$idPromo,
				"testo_path"	=>	"Elementi/Mail/mail_link_codice_coupon.php",
				"array_variabili_tema"	=>	array(
					"CODICE_COUPON"	=>	$record["promozioni"]["codice"],
					"TITOLO_PROMO"	=>	$record["promozioni"]["titolo"],
					"NOME_UTENTE"	=>	self::getNominativo($record["regusers"]),
					"EMAIL_UTENTE"	=>	$record["regusers"]["username"],
					"LINK_AREA_RISERVATA"	=>	Domain::$publicUrl."/".$record["regusers"]["lingua"].F::getNazioneUrl($record["regusers"]["nazione_navigazione"])."/area-riservata",
				),
			));
			
			return $res;
		}
		
		return false;
	}
	
	public function nazioneValiditaCrud($record)
	{
		return NazioniModel::g()->getNome($record["promozioni"]["nazione_validita"]);
	}
}
