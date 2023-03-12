<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
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
// 		var_dump($this->m("RegusersModel")->accountEliminabileANuovoOrdine(User::$id));
		
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
	
	public function indirizzi()
	{
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/riservata/indirizzi";
		}
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Lista indirizzi di spedizione"));
		
		$clean["id_spedizione"] = $this->request->get("del",0,"forceInt");
		
		if ($clean["id_spedizione"] > 0)
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
}
