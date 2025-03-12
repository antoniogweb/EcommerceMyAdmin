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

class BaseRiservataController extends BaseController
{
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->load('header');
		$this->load('footer','last');
		
		$data["arrayLingue"] = array();
		
		$this->s['registered']->check(null,0);
		
		$data["isAreaRiservata"] = true;
		
		$this->append($data);
	}

	public function index()
	{
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/area-riservata";
		}
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Area riservata"));

		$this->append($data);
		
		$this->load('main');

	}

	public function ordini()
	{
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/ordini-effettuati";
		}
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Lista ordini effettuati"));

		$data['ordini'] = $this->m("OrdiniModel")->clear()->where(array("id_user"=>$this->iduser))->orderBy("id_o desc")->send();
		
		$this->append($data);
		
		$this->load('lista_ordini');
	}
	
	public function ordinicollegati()
	{
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/ordini-collegati";
		}
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Lista ordini collegati ai miei codici coupon"));

		$data['ordini'] = $this->m("OrdiniModel")->clear()->where(array("id_agente"=>$this->iduser))->orderBy("id_o desc")->send();
		
		$this->append($data);
		
		$this->load('lista_ordini_collegati');
	}
	
	public function promozioni()
	{
		if (!v("attiva_agenti"))
			$this->responseCode(403);
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/i-miei-coupon";
		}
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Lista coupon"));

		$data['promozioni'] = $this->m("PromozioniModel")->clear()->where(array("id_user"=>$this->iduser))->orderBy("promozioni.dal desc,promozioni.al desc,promozioni.id_p desc")->send();
		
		$this->append($data);
		
		$this->load('lista_promozioni');
	}
	
	public function dettagliopromozione($id_p)
	{
		if (!v("attiva_agenti"))
			$this->responseCode(403);
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/dettaglio-promozione/".(int)$id_p;
		}
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Dettaglio coupon"));

		$data['promozione'] = $this->m("PromozioniModel")->clear()->where(array(
			"id_user"	=>	(int)$this->iduser,
			"id_p"		=>	(int)$id_p,
		))->record();
		
		if (empty($data['promozione']))
			$this->responseCode(403);
		
		$this->append($data);
		
		$this->load('dettaglio_promozione');
	}
	
	public function indirizzi()
	{
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/riservata/indirizzi";
		}
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Lista indirizzi di spedizione"));
		
		$clean["id_spedizione"] = $this->request->get("del",0,"forceInt");
		
		if ($clean["id_spedizione"] > 0 && v("permetti_modifica_account"))
			$this->m("SpedizioniModel")->del(null, array("id_spedizione = ? AND id_user = ?",array($clean["id_spedizione"], User::$id)));
		
		$data['indirizzi'] = $this->m("SpedizioniModel")->clear()->where(array("id_user"=>$this->iduser))->orderBy("indirizzo_spedizione desc")->send();
		
		if (Output::$html)
		{
			$this->append($data);
			
			$this->load('lista_indirizzi');
		}
		else
			$this->load("api_output");
	}
	
	public function feedback()
	{
		if (!v("abilita_feedback") || !v("feedback_visualizza_in_area_riservata"))
			$this->redirect("");
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/riservata/feedback";
		}
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Elenco feedback inseriti"));
		
		$this->append($data);
		
		$this->load('lista_feedback');
	}
	
	public function privacy()
	{
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/riservata/privacy";
		}
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Condizioni di privacy"));
		
		$data["notice"] = $data["noticecookies"] = null;
		
		if (isset($_GET["cancella_cookies"]))
		{
			if (isset($_COOKIE["ok_cookie_terzi"]))
				setcookie("ok_cookie_terzi","OK",(time()-3600),"/");
			
			flash("noticecookies","<div class='".v("alert_success_class")."'>".gtext("Approvazione all'utilizzo di cookies revocata correttamente.")."</div><br /><br />");
			
			$this->redirect("riservata/privacy");
		}
		
		if (isset($_POST["cancella"]) && v("permetti_eliminazione_account"))
		{
			$clean["password"] = $this->request->post("password","","sanitizeDb");
			
			$user = $this->m("RegusersModel")->where(array(
				"id_user"	=>	User::$id,
			))->record();
			
			if (!empty($user) && passwordverify($clean["password"], $user["password"]))
			{
				$tokenEliminazione = $this->m("RegusersModel")->deleteAccount($user["id_user"]);
				$this->s['registered']->logout();
				
				$this->redirect(RegusersModel::getUrlAccountEliminato($tokenEliminazione));
			}
			else
				$data["notice"] = "<div class='".v("alert_error_class")."'>".gtext("Attenzione, password non corretta.")."</div><div class='evidenzia'>class_password</div>";
		}
		
		$this->append($data);
		$this->load('privacy');
	}
	
	public function documenti()
	{
		if (!v("attiva_biblioteca_documenti"))
			$this->responseCode(403);
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/biblioteca-documenti";
		}
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Biblioteca documenti"));
		
		$data['documenti'] = $this->m("DocumentiModel")->getDocumentiUtente(User::$id);
		
		$this->append($data);
		
		$this->load('documenti');
	}
	
	public function documentiriservati()
	{
		if (!v("documenti_in_clienti"))
			$this->responseCode(403);
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/documenti-riservati";
		}
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Documenti riservati"));
		
		$data['documenti'] = $this->m("DocumentiModel")->getDocumentiRiservatiUtente(User::$id);
		
		$this->append($data);
		
		$this->load('documenti_riservati');
	}
	
	public function crediti()
	{
		if (!v("attiva_crediti"))
			$this->responseCode(403);
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/gestione-crediti/";
		}
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Gestione crediti"));
		
		$data["storico"] = $this->m("CreditiModel")->getStoricoCrediti(User::$id);
		
		$this->append($data);
		
		$this->load('crediti');
	}
}
