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

class NotificheModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='notifiche';
		$this->_idFields='id_notifica';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public static function getNotificheStatiche($soloPubblicate = 1)
	{
		$notifiche = array();
		
		$files = scandir(ROOT."/DB/Migrazioni", SCANDIR_SORT_DESCENDING);
		$ultimaMigrazione = $files[0];
		$migrationNum = (int)basename($ultimaMigrazione, '.sql');
		
		if ($migrationNum > (int)v("db_version") && ControllersModel::checkAccessoAlController(array("cron")))
			$notifiche[] = array(
				"testo"	=>	gtext("Attenzione, aggiorna il database!"),
				"link"	=>	Url::getRoot()."cron/migrazioni/".v("codice_cron"),
				"icona"	=>	"fa-database",
				"class"	=>	"text-yellow",
			);
		
		if (v("piattaforma_in_sviluppo") && ControllersModel::checkAccessoAlController(array("impostazioni")))
			$notifiche[] = array(
				"testo"	=>	gtext("Indicizzazione non attiva."),
				"link"	=>	Url::getRoot()."impostazioni/ecommerce/1",
				"icona"	=>	"fa-warning",
				"class"	=>	"text-yellow",
			);
		
		if (v("permetti_gestione_sitemap") && !SitemapModel::g(false)->rowNumber() && ControllersModel::checkAccessoAlController(array("sitemap")))
			$notifiche[] = array(
				"testo"	=>	gtext("Sitemap vuota!"),
				"link"	=>	Url::getRoot()."sitemap/main",
				"icona"	=>	"fa-map-o",
				"class"	=>	"text-yellow",
			);
		
		if (!$soloPubblicate)
		{
			if (!v("tempo_log_permanenti_giorni") && LogModel::numeroLogPermanenti())
				$notifiche[] = array(
					"testo"	=>	gtext("Impostare un tempo massimo per i log della piattaforma. Variabile tempo_log_permanenti_giorni"),
					"link"	=>	"",
					"icona"	=>	"fa-file-text-o",
					"class"	=>	"text-yellow",
				);
			
			if (!v("tempo_log_gateway_response") && OrdiniresponseModel::numeroLog())
				$notifiche[] = array(
					"testo"	=>	gtext("Impostare un tempo massimo per i log contententi gli esiti dei pagamenti. Variabile tempo_log_gateway_response"),
					"link"	=>	"",
					"icona"	=>	"fa-file-text-o",
					"class"	=>	"text-yellow",
				);
			
			if (!v("svuota_file_cookie_carrello_dopo_x_minuti") && v("ecommerce_attivo"))
				$notifiche[] = array(
					"testo"	=>	gtext("Salvare i cookie dei carrelli su file. Variabile svuota_file_cookie_carrello_dopo_x_minuti"),
					"link"	=>	"",
					"icona"	=>	"fa-file-text-o",
					"class"	=>	"text-yellow",
				);
			
			if (!v("attiva_check_ip"))
				$notifiche[] = array(
					"testo"	=>	gtext("Attivare il check IP sui form. Variabile attiva_check_ip. Impostare i limiti con le variabili limite_ip_chiave_contemporanee, limite_ip_chiave_minuto e limite_ip_chiave_orario"),
					"link"	=>	"",
					"icona"	=>	"fa-file-text-o",
					"class"	=>	"text-yellow",
				);
			
			if (!VariabiliModel::checkNumeroMailInviate())
				$notifiche[] = array(
					"testo"	=>	gtext("Attivare un numero massimo di email inviabili in un ora e/o in un giorno. Variabili max_numero_email_ora e max_numero_email_giorno"),
					"link"	=>	"",
					"icona"	=>	"fa-file-text-o",
					"class"	=>	"text-yellow",
				);
			
			if (!v("check_accesso_admin_token_ordine_frontend_da"))
				$notifiche[] = array(
					"testo"	=>	gtext("Attivare il doppio token per poter vedere il dettaglio dell'ordine in modalità anonima. Variabile check_accesso_admin_token_ordine_frontend_da."),
					"link"	=>	"",
					"icona"	=>	"fa-file-text-o",
					"class"	=>	"text-yellow",
				);
			
			if (!v("attiva_waf"))
				$notifiche[] = array(
					"testo"	=>	gtext("Attivare il WAF. Variabile attiva_waf."),
					"link"	=>	"",
					"icona"	=>	"fa-file-text-o",
					"class"	=>	"text-yellow",
				);
			
			if (!v("attiva_controllo_robustezza_password"))
				$notifiche[] = array(
					"testo"	=>	gtext("Attivare il controllo sulla robustezza della password. Variabile attiva_controllo_robustezza_password."),
					"link"	=>	"",
					"icona"	=>	"fa-file-text-o",
					"class"	=>	"text-yellow",
				);
			
			if (!v("attiva_autenticazione_due_fattori_admin"))
				$notifiche[] = array(
					"testo"	=>	gtext("Attivare l'autenticazione a due fattori lato admin. Variabile attiva_autenticazione_due_fattori_admin."),
					"link"	=>	"",
					"icona"	=>	"fa-file-text-o",
					"class"	=>	"text-yellow",
				);
			
			if (v("attiva_area_riservata") && !v("attiva_autenticazione_due_fattori_front"))
				$notifiche[] = array(
					"testo"	=>	gtext("Attivare l'autenticazione a due fattori lato frontend. Variabile attiva_autenticazione_due_fattori_front."),
					"link"	=>	"",
					"icona"	=>	"fa-file-text-o",
					"class"	=>	"text-yellow",
				);
			
			if ((v("attiva_autenticazione_due_fattori_front") || v("attiva_autenticazione_due_fattori_admin")) && (!defined('AES_KEY') || !defined('MAC_KEY')))
				$notifiche[] = array(
					"testo"	=>	gtext("Definire le chiavi AES e MAC per la cifratura del codice a 2 fattori"),
					"link"	=>	"",
					"icona"	=>	"fa-file-text-o",
					"class"	=>	"text-yellow",
				);
			
			if ((v("attiva_autenticazione_due_fattori_front") || v("attiva_autenticazione_due_fattori_admin")) && v("autenticazione_due_fattori_usa_id_user"))
				$notifiche[] = array(
					"testo"	=>	gtext("Non usare l'ID user nel cookie dei due fattori. Variabile autenticazione_due_fattori_usa_id_user"),
					"link"	=>	"",
					"icona"	=>	"fa-file-text-o",
					"class"	=>	"text-yellow",
				);
		}
		
		return $notifiche;
	}
	
	public static function getNotifiche()
	{
		self::segnaEseguite();
		
		$notifiche = self::getNotificheStatiche();
		
// 		$notifiche = array();
// 		
// 		$files = scandir(ROOT."/DB/Migrazioni", SCANDIR_SORT_DESCENDING);
// 		$ultimaMigrazione = $files[0];
// 		$migrationNum = (int)basename($ultimaMigrazione, '.sql');
// 		
// 		if ($migrationNum > (int)v("db_version") && ControllersModel::checkAccessoAlController(array("cron")))
// 			$notifiche[] = array(
// 				"testo"	=>	gtext("Attenzione, aggiorna il database!"),
// 				"link"	=>	Url::getRoot()."cron/migrazioni/".v("codice_cron"),
// 				"icona"	=>	"fa-database",
// 				"class"	=>	"text-yellow",
// 			);
// 		
// 		if (v("piattaforma_in_sviluppo") && ControllersModel::checkAccessoAlController(array("impostazioni")))
// 			$notifiche[] = array(
// 				"testo"	=>	gtext("Indicizzazione non attiva."),
// 				"link"	=>	Url::getRoot()."impostazioni/ecommerce/1",
// 				"icona"	=>	"fa-warning",
// 				"class"	=>	"text-yellow",
// 			);
// 		
// 		if (v("permetti_gestione_sitemap") && !SitemapModel::g(false)->rowNumber() && ControllersModel::checkAccessoAlController(array("sitemap")))
// 			$notifiche[] = array(
// 				"testo"	=>	gtext("Sitemap vuota!"),
// 				"link"	=>	Url::getRoot()."sitemap/main",
// 				"icona"	=>	"fa-map-o",
// 				"class"	=>	"text-yellow",
// 			);
		
		// if (!v("tempo_log_permanenti_giorni") && LogModel::numeroLogPermanenti())
		// 	$notifiche[] = array(
		// 		"testo"	=>	gtext("Impostare un tempo massimo<br /> per i log della piattaforma"),
		// 		"link"	=>	"",
		// 		"icona"	=>	"fa-file-text-o",
		// 		"class"	=>	"text-yellow",
		// 	);
		
		$n = new NotificheModel();
		$res = $n->clear()->where(array(
			"risolta"	=>	0,
		))->orderBy("id_order desc")->send(false);
		
		$contesti = $n->clear()->select("distinct contesto")->where(array(
			"risolta"	=>	0,
		))->orderBy("id_order desc")->toList("contesto")->send();
		
		$arrayAccesso = array();
		
		foreach ($contesti as $contesto)
		{
			$arrayAccesso[$contesto] = ControllersModel::checkAccessoAlController(array(strtolower($contesto)));
		}
		
		foreach ($res as $r)
		{
			$queryStringChar = strstr($r["url"], '?') ? "&" : "?";
			$queryString = $queryStringChar . "id_notifica=".$r["id_notifica"];
			
			parse_str($r["condizioni"], $condizioni);
			
			$contesto = $r["contesto"];
			
			if (VariabiliModel::verificaCondizioni($condizioni) && (!isset($arrayAccesso[$contesto]) || $arrayAccesso[$contesto]))
				$notifiche[] = array(
					"testo"	=>	gtext(htmlentitydecode($r["titolo"])),
					"link"	=>	Url::getRoot().$r["url"].$queryString,
					"icona"	=>	$r["icona"],
					"class"	=>	$r["classe"],
				);
		}
		
		return $notifiche;
	}
	
	public static function segnaEseguite()
	{
		if (isset($_GET["id_notifica"]))
		{
			$n = new NotificheModel();
			
			$notifica = $n->selectId((int)$_GET["id_notifica"]);
			
			if (!empty($notifica))
			{
				$n->setValues(array(
					"risolta"	=>	1,
				));
				
				$n->update(null, array(
					"url"	=>	sanitizeAll($notifica["url"]),
				));
			}
		}
	}
}
