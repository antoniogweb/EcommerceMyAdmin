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

class ImpostazioniModel extends GenericModel {

	public static $valori = null;
	public static $parametriImpostati = false;
	
	public function __construct() {
		$this->_tables='impostazioni';
		$this->_idFields='id_imp';

// 		$this->_idOrder = 'id_order';
		$this->_lang = 'It';
		
		parent::__construct();
		
	}
	
	public function getImpostazioni()
	{
		$res = $this->send();
		
		if (count($res) > 0)
		{
			self::$valori = $res[0]["impostazioni"];
			
			self::$valori["smtp_psw"] = htmlentitydecode(self::$valori["smtp_psw"]);
			
			if (v("email_sviluppo"))
			{
				self::$valori["mail_invio_ordine"] = v("email_sviluppo");
				self::$valori["mail_invio_conferma_pagamento"] = v("email_sviluppo");
				self::$valori["bcc"] = v("email_sviluppo");
			}
		}
	}
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'nome_sito'		=>	array(
					'labelString'=>	gtext("Nome sito/ecommerce"),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("È il nome che apparirà nelle comunicazioni al cliente, comprese le mail, e nella barra del browser")."</div>",
					),
				),
				'title_home_page'		=>	array(
					'labelString'=>	gtext("Meta tag 'title' della home"),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("È il titolo della home page del sito visto dai motori di ricerca")."</div>",
					),
				),
				'meta_description'		=>	array(
					'labelString'=>	gtext("Descrizione per motori di ricerca"),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("È la descrizione della home page vista dai motori di ricerca")."</div>",
					),
				),
				'keywords'		=>	array(
					'labelString'=>	gtext("Parole chiave (divise da virgola)"),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Parole chiave della home page")."</div>",
					),
				),
				'iva'		=>	array(
					'labelString'=>	gtext("Iva di default"),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Aliquota IVA che viene usata quando non specificata diversamente (ad esempio in singoli prodotti)")."</div>",
					),
				),
				'mail_invio_ordine'		=>	array(
					'labelString'=>	"Mail a cui inviare l'avviso di nuovo ordine",
				),
				'mail_invio_conferma_pagamento'		=>	array(
					'labelString'=>	"Mail a cui inviare l'avviso che l'ordine è stato pagato (paypal, carta di credito)",
				),
				'mail_registrazione_utenti'		=>	array(
					'labelString'=>	"Mail a cui inviare l'avviso di nuovo cliente registrato",
				),
				'smtp_from'		=>	array(
					'labelString'=>	'Campo DA (FROM) nelle mail di sistema',
				),
				'smtp_nome'		=>	array(
					'labelString'=>	'Campo NOME (FROM NAME) nelle mail di sistema',
				),
				'smtp_secure'		=>	array(
					'labelString'=>	'Protocollo sicuro per il servizio esterno SMTP',
					"type"	=>	"Select",
					"options"	=>	array(
						""		=>	"Nessuno",
						"ssl"	=>	"SSL",
						"tls"	=>	"STARTTLS",
					),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Compilare solo se si impostano le porte 465 o 587")."</div>",
					),
				),
				'reply_to_mail'		=>	array(
					'labelString'=>	'Reply To nelle mail di sistema',
				),
				'reply_to_mail_ordini'		=>	array(
					'labelString'=>	'Reply To nelle mail di sistema LEGATE AGLI ORDINI',
				),
				'bcc'		=>	array(
					'labelString'=>	gtext("Campo copia nascosta"),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se riempito con una singola mail, tutte le mail di sistema verranno inviate in copia nascosta a quella mail")."</div>",
					),
				),
				'usa_smtp'		=>	array(
					'labelString'=>	gtext("Usa SMTP esterno nelle mail di sistema"),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se utilizzare l'SMTP del server o un SMTP esterno")."</div>",
					),
				),
				'smtp_host'		=>	array(
					'labelString'=>	gtext("HOST del servizio SMTP esterno"),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Compilare solo se il campo 'Usa SMTP esterno nelle mail di sistema' è stato impostato a sì")."</div>",
					),
				),
				'smtp_port'		=>	array(
					'labelString'=>	gtext("Porta usata per il servizio esterno SMTP"),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Compilare solo se il campo 'Usa SMTP esterno nelle mail di sistema' è stato impostato a sì")."</div>",
					),
				),
				'smtp_user'		=>	array(
					'labelString'=>	gtext("Utente usato per il servizio esterno SMTP"),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Compilare solo se il campo 'Usa SMTP esterno nelle mail di sistema' è stato impostato a sì")."</div>",
					),
				),
				'smtp_psw'		=>	array(
					'labelString'=>	gtext("Password usata per il servizio esterno SMTP"),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Compilare solo se il campo 'Usa SMTP esterno nelle mail di sistema' è stato impostato a sì")."</div>",
					),
				),
				'usa_sandbox'		=>	array(
					'labelString'=>	gtext("Usa il sistema di pagamento di test di Paypal (Sandbox)"),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Il servizio Sandbox di Paypal permette di simulare l'esperienza di acquisto del cliente senza che avventa un reale scambio di denaro")."</div>",
					),
				),
				'paypal_seller'		=>	array(
					'labelString'=>	gtext("Account Business Paypal (questo è il vero account)"),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Deve essere l'indirizzo email principale impostato nel vostro account Paypal Business")."</div>",
					),
				),
				'paypal_sandbox_seller'		=>	array(
					'labelString'=>	gtext("Account Paypal di test"),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Inserite un indirizzo Paypal del venditore di test (creato nella piattaforma Sandbox)")."</div>",
					),
				),
				'spedizioni_gratuite_sopra_euro'		=>	array(
					'labelString'=>	gtext("Spese di spedizione gratuite sopra euro"),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Inserire 0 se si vuole che le spese di spedizione non siano mai gratuite")."</div>",
					),
				),
				'mailchimp_list_id'		=>	array(
					'labelString'=>	gtext("ID della lista Mailchimp da collegare"),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("È l'ID della lista Mailchimp. È presente nel pannello di gestione di Mailchimp, nei parametri di configurazione della lista")."</div>",
					),
				),
				'mailchimp_api_key'		=>	array(
					'labelString'=>	gtext("Chiave di sicurezza Mailchimp"),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Questo parametro va creato nel pannello di Mailchimp, se non già presente")."</div>",
					),
				),
			),
		);
	}
	
	public static function init()
	{
		if (self::$parametriImpostati)
			return;
		
		self::g(false)->getImpostazioni();
		
		//leggi le impostazioni
		if (self::$valori)
		{
			Parametri::$useSMTP = self::$valori["usa_smtp"] == "Y" ? true : false;
			Parametri::$SMTPHost = self::$valori["smtp_host"];
			Parametri::$SMTPPort = self::$valori["smtp_port"];
			Parametri::$SMTPUsername = self::$valori["smtp_user"];
			Parametri::$SMTPPassword = self::$valori["smtp_psw"];
			Parametri::$mailFrom = self::$valori["smtp_from"];
			Parametri::$mailFromName = htmlentitydecode(self::$valori["smtp_nome"]);
			Parametri::$mailInvioOrdine = self::$valori["mail_invio_ordine"];
			Parametri::$mailInvioConfermaPagamento = self::$valori["mail_invio_conferma_pagamento"];
			Parametri::$nomeNegozio = htmlentitydecode(self::$valori["nome_sito"]);
			Parametri::$iva = self::$valori["iva"];
			Parametri::$ivaInclusa = self::$valori["iva_inclusa"] == "Y" ? true : false;
			Parametri::$useSandbox = self::$valori["usa_sandbox"] == "Y" ? true : false;
			Parametri::$paypalSeller = self::$valori["paypal_seller"];
			Parametri::$paypalSandBoxSeller = self::$valori["paypal_sandbox_seller"];
			Parametri::$mailReplyTo = (isset(self::$valori["reply_to_mail"]) && self::$valori["reply_to_mail"]) ? self::$valori["reply_to_mail"] : Parametri::$mailFrom;
		}
		
		self::$parametriImpostati = true;
	}
	
	public static function getEmailAvvisoPagamentoOrdine()
	{
		return Parametri::$mailInvioConfermaPagamento ? Parametri::$mailInvioConfermaPagamento : Parametri::$mailInvioOrdine;
	}
	
	public static function getReplyToMailOrdini()
	{
		return (isset(self::$valori["reply_to_mail_ordini"]) && self::$valori["reply_to_mail_ordini"]) ? self::$valori["reply_to_mail_ordini"] : Parametri::$mailReplyTo;
	}
}
