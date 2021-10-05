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

class BaseRiservataController extends BaseController
{

	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (Output::$html)
		{
			$this->load('header');
			$this->load('footer','last');
		}
		
		$data["arrayLingue"] = array();
		
		$this->append($data);
		
		$this->s['registered']->check(null,0);
	}

	public function index()
	{
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/area-riservata";
		}
		
		$data['title'] = Parametri::$nomeNegozio . ' - '. gtext("Area riservata");

		$this->append($data);
		
		$this->load('main');

	}

	public function ordini()
	{
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/ordini-effettuati";
		}
		
		$data['title'] = Parametri::$nomeNegozio . ' - '.gtext("Lista ordini effettuati");

		$data['ordini'] = $this->m["OrdiniModel"]->clear()->where(array("id_user"=>$this->iduser))->orderBy("id_o desc")->send();
		
		if (Output::$html)
		{
			$this->append($data);
			
			$this->load('lista_ordini');
		}
		else
		{
			$pagineConDecode = array();
			
			foreach ($data["ordini"] as $o)
			{
				$temp = $o["orders"];
				
				unset($temp["descrizione_acquisto"]);
				unset($temp["creation_time"]);
				unset($temp["id_order"]);
				unset($temp["admin_token"]);
				unset($temp["txn_id"]);
				unset($temp["registrato"]);
				unset($temp["banca_token"]);
				unset($temp["descrizione_acquisto"]);
				unset($temp["descrizione_acquisto"]);
				
				$temp = htmlentitydecodeDeep($temp);
				
				$temp["stato_desc"] = statoOrdine($temp["stato"]);
				$temp["data_ordine"] = date("d/m/Y", strtotime($temp["data_creazione"]));
				
				$temp["total"] = number_format($temp["total"],2,",","");
				$temp["subtotal"] = number_format($temp["subtotal"],2,",","");
				$temp["spedizione"] = number_format($temp["spedizione"],2,",","");
				$temp["iva"] = number_format($temp["iva"],2,",","");
				$temp["prezzo_scontato"] = number_format($temp["prezzo_scontato"],2,",","");
				$temp["peso"] = number_format($temp["peso"],2,",","");
				if ($temp["promo"] && is_numeric($temp["promo"]))
					$temp["promo"] = number_format($temp["promo"],2,",","");
				
				$pagineConDecode[] = $temp;
			}
			
			Output::setBodyValue("Type", "Ordini");
			Output::setBodyValue("Ordini", $pagineConDecode);
			
			$this->load("api_output");
		}
	}
	
	public function indirizzi()
	{
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/riservata/indirizzi";
		}
		
		$data['title'] = Parametri::$nomeNegozio . ' - '.gtext("Lista indirizzi di spedizione");
		
		$clean["id_spedizione"] = $this->request->get("del",0,"forceInt");
		
		if ($clean["id_spedizione"] > 0)
		{
			$this->m["SpedizioniModel"]->del(null, "id_spedizione = ".$clean["id_spedizione"]." AND id_user = ".User::$id);
// 			echo $this->m["SpedizioniModel"]->notice;
		}
		
		$data['indirizzi'] = $this->m["SpedizioniModel"]->clear()->where(array("id_user"=>$this->iduser))->orderBy("indirizzo_spedizione desc")->send();
		
		if (Output::$html)
		{
			$this->append($data);
			
			$this->load('lista_indirizzi');
		}
		else
			$this->load("api_output");
	}
	
	public function privacy()
	{
		foreach (Params::$frontEndLanguages as $l)
		{
			$data["arrayLingue"][$l] = $l."/riservata/privacy";
		}
		
		$data['title'] = Parametri::$nomeNegozio . ' - '.gtext("Condizioni di privacy");
		
		$data["notice"] = $data["noticecookies"] = null;
		
		if (isset($_GET["ok_no_cookies"]))
			$data["noticecookies"] = "<div class='executed'>".gtext("Approvazione all'utilizzo di cookies revocata correttamente.")."</div><br /><br />";
			
		if (isset($_GET["cancella_cookies"]))
		{
			setcookie("ok_cookie","OK",(time()-3600),"/");
			$this->redirect("riservata/privacy?ok_no_cookies");
		}
		
		if (isset($_POST["cancella"]))
		{
			$clean["password"] = $this->request->post("password","","sanitizeDb");
			
			$user = $this->m["RegusersModel"]->where(array(
				"id_user"	=>	User::$id,
			))->record();
			
			if (!empty($user) && passwordverify($clean["password"], $user["password"]))
			{
				$this->m["RegusersModel"]->deleteAccount($user["id_user"]);
				$this->s['registered']->logout();
				
				$idRedirect = PagineModel::gTipoPagina("ACCOUNT_ELIMINATO");
				
				if ($idRedirect)
					$this->redirect(getUrlAlias($idRedirect));
				else
					$this->redirect('account-cancellato.html',0);
			}
			else
				$data["notice"] = "<div class='".v("alert_error_class")."'>".gtext("Attenzione, password non corretta.")."</div><div class='evidenzia'>class_password</div>";
		}
		
		$this->append($data);
		$this->load('privacy');
	}
	
// 	public function cancellaaccount()
// 	{
// 		$this->clean();
// 	}
}
