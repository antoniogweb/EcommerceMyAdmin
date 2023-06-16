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

class BasePromozioniController extends BaseController
{
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_agenti"))
			$this->responseCode(403);
		
		$this->load('header');
		$this->load('footer','last');
		
		$data["arrayLingue"] = array();
		
		$this->s['registered']->check(null,0);
		
		$this->model("PromozioniModel");
		$this->model("ListeregalopagesModel");
		$this->model("PromozioniinviiModel");
		
		$data["isAreaRiservata"] = true;
		
		$this->append($data);
	}

	public function index()
	{
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/liste-regalo/";
		}
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/i-miei-coupon";
		}
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Lista coupon"));

		$data['promozioni'] = $this->m("PromozioniModel")->clear()->where(array("id_user"=>$this->iduser))->orderBy("promozioni.dal desc,promozioni.al desc,promozioni.id_p desc")->send();
		
		$this->append($data);
		
		$this->load('main');
	}
	
	public function checkPromo($id = 0, $redirect = true)
	{
		$clean["id"] = (int)$id;
		
		if ($clean["id"] > 0)
		{
			$numero = PromozioniModel::numeroPromoUtente(User::$id, $clean["id"]);
			
			if ($numero === 0)
				$this->responseCode(403);
		}
		else
			$this->responseCode(403);
	}
	
	public function gestisci($id = 0)
	{
		$clean["id"] = $data["id"] = $data["idListaRegalo"] = (int)$id;
		
		$this->checkPromo($id);
		
		$data['title'] = $this->aggiungiNomeNegozioATitle(gtext("Gestione coupon"));
		
		$data["promozione"] = $this->m('PromozioniModel')->selectId($clean["id"]);
		$data["invii_codice"] = $this->m("PromozioniinviiModel")->getInvii($clean["id"]);
		
		$data["ordini_coupon"] = $this->m('OrdiniModel')->clear()->where(array(
			"id_p"		=>	(int)$id,
			"id_agente"	=>	User::$id,
		))->send(false);
		
		$this->append($data);
		$this->load('gestisci');
	}
	
	public function elencoinvii($id = 0)
	{
		$clean["id"] = $data["id"] = (int)$id;
		
		$this->checkPromo($id);
		
		$this->clean();
		
		$data["invii_codice"] = $this->m("PromozioniinviiModel")->getInvii($clean["id"]);
		
		$this->append($data);
		$this->load('codici_inviati');
	}
	
	public function invianuovamentecodice($idLink = 0)
	{
		$this->clean();
		
		$clean["id"] = $data["id"] = (int)$idLink;
		
		$result = "KO";
		$notice = gtext("Errore nell'invio");
		
		if ($this->m('PromozioniinviiModel')->checkAccesso($clean["id"]))
		{
			if ($this->m('PromozioniinviiModel')->inviaMail($clean["id"]))
			{
				$result = "OK";
				$notice = gtext("Link correttamente inviato");
			}
		}
		
		echo json_encode(array(
			"result"	=>	$result,
			"errore"	=>	$notice,
		));
	}
	
	public function inviacodice($id = 0)
	{
		$this->clean();
		
		$clean["id"] = $data["id"] = (int)$id;
		
		$result = "KO";
		$notice = "";
		
		if (PromozioniModel::numeroPromoUtente(User::$id, $clean["id"]))
		{
			$campi = "nome,cognome,email";
			
			$this->m('PromozioniinviiModel')->setFields($campi,'sanitizeAll');
			$this->m('PromozioniinviiModel')->setValue("id_p", $clean["id"]);
			
			$this->m('PromozioniinviiModel')->addStrongCondition("both",'checkNotEmpty',"nome,email");
			$this->m('PromozioniinviiModel')->addStrongCondition("both",'checkMail',"email|".gtext("Si prega di ricontrollare <b>l'indirizzo email</b>")."<div class='evidenzia'>class_email</div>");
			
			$this->m('PromozioniinviiModel')->updateTable('insert',0);
			
			if ($this->m('PromozioniinviiModel')->queryResult)
			{
				$result = "OK";
				
				if ($this->m('PromozioniinviiModel')->inviaMail($this->m('PromozioniinviiModel')->lId))
					$notice = "<div class='".v("alert_success_class")."'>".gtext("Il codice del coupon è stato correttamente inviato alla mail indicata")."</div>";
				else
					$notice = "<div class='".v("alert_error_class")."'>".gtext("Attenzione, errore nell'invio della mail. Si prega di riprovare.")."</div>";
			}
			else
				$notice = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi segnati in rosso")."</div>".$this->m('PromozioniinviiModel')->notice;
		}
		
		echo json_encode(array(
			"result"	=>	$result,
			"errore"	=>	$notice,
		));
	}
	
	public function modifica($id = 0)
	{
		Params::$automaticConversionFromDbFormat = true;
		Params::$automaticConversionToDbFormat = true;
		Params::$setValuesConditionsFromDbTableStruct = true;
		
		$clean["id"] = $data["id"] = (int)$id;
		
		$this->checkPromo($id);
		
		$title = gtext("Modifica la descrizione");
		$data['title'] = $this->aggiungiNomeNegozioATitle($title);
		
		$promo = $this->m('PromozioniModel')->selectId($clean["id"]);
		
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/promozioni/modifica/".$clean["id"];
		}
		
		$data['notice'] = null;
		$data['action'] = "/promozioni/modifica/".$clean["id"];
		
		$campiObbligatori = $fields = "titolo";
		
		if (isset($_POST["titolo"]))
			$_POST["titolo"] = strip_tags($_POST["titolo"]);
		
		$this->m('PromozioniModel')->setFields($fields,'sanitizeAll');
		
		$this->m('PromozioniModel')->clearConditions("strong");
		$this->m('PromozioniModel')->addStrongCondition("both",'checkNotEmpty',$campiObbligatori);
		
		$this->m('PromozioniModel')->updateTable('update',$clean["id"]);
		
		if ($this->m('PromozioniModel')->queryResult)
		{
			if (!$clean["id"])
				$clean["id"] = (int)$this->m('PromozioniModel')->lId;
			
			$this->redirect("promozioni/gestisci/".$clean["id"]);
		}
		else
		{
			if (!$this->m('PromozioniModel')->result)
				$data['notice'] = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare i campi evidenziati")."</div>".$this->m('PromozioniModel')->notice;
		}
		
		$submitAction = "update";
		
		$data['values'] = $this->m('PromozioniModel')->getFormValues($submitAction,'sanitizeHtml',$clean["id"]);
		
		$this->append($data);
		$this->load('modifica');
	}
}
