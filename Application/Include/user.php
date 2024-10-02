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

class User
{
	public static $id = 0;
	public static $logged = false;
	public static $token = '';
	public static $name = '';
	public static $cart_uid;
	public static $wishlist_uid;
	public static $coupon;
	public static $groups = array();
	public static $dettagli = array();
	public static $classeSconto = null;
	public static $sconto = 0;
	public static $categorieInClasseSconto = array();
	public static $prodottiInCoupon = array();
	public static $isMobile = false;
	public static $isTablet = false;
	public static $isPhone = false;
	public static $nomeCliente = '';
	public static $idLista = 0;
	
	public static $adminLogged = false;
	public static $asJson = false;
	
	public static $nazione = null;
	public static $nazioneNavigazione = "";
	
	public static $ruid = null;
	public static $isAgente = false;
	
	public static function has($permessi)
	{
		$gruppi = explode(",",$permessi);
	
		foreach ($gruppi as $gruppo)
		{
			if (in_array($gruppo, User::$groups))
			{
				return true;
			}
		}
		
		return false;
	}
	
	public static function getSpedizioneDefault()
	{
		return v("nazione_default");
	}
	
	public static function setClasseSconto()
	{
		if (isset(User::$dettagli["id_classe"]))
		{
			// Estraggo lo sconto dell'utente
			User::$classeSconto = ClassiscontoModel::g()->selectId(User::$dettagli["id_classe"]);
			
			if (!empty(User::$classeSconto) && User::$classeSconto["sconto"] > 0 && User::$classeSconto["sconto"] < 100)
			{
				User::$sconto = User::$classeSconto["sconto"];
				
				$cModel = new CategoriesModel();
				
				User::$categorieInClasseSconto = $cModel->getListaCategorieInClasseSconto();
			}
		}
	}
	
	public static function setUserCountryFromUrl()
	{
		$paramsCountry = isset(Params::$country) ? strtoupper(Params::$country) : null;
		
		User::$nazione = null;
		
		if (isset($_GET["listino"]) && $_GET["listino"] != v("nazione_default") && CombinazionilistiniModel::listinoEsistente($_GET["listino"]))
			User::$nazione = sanitizeAll(strtoupper($_GET["listino"]));
		else if (isset($paramsCountry) && $paramsCountry != v("nazione_default") && CombinazionilistiniModel::listinoEsistente($paramsCountry))
			User::$nazione = sanitizeAll($paramsCountry);
	}
	
	public static function setPostCountryFromUrl()
	{
		User::setUserCountryFromUrl();
		
		if (User::$nazione)
		{
			$_POST["nazione_spedizione"] = strtoupper(User::$nazione);
			$_POST["tipo_cliente"] = "privato";
		}
		else if (isset(Params::$country))
		{
			$_POST["nazione_spedizione"] = strtoupper(Params::$country);
			$_POST["tipo_cliente"] = "privato";
		}
	}
	
	// Restituisce la nazione dell'utente, dà sempre la priorità al listino e in seguito alla nazione nell'URL
	public static function getNazioneUtente()
	{
		$nazione = v("nazione_default");

		if (User::$nazione)
			$nazione = User::$nazione;
		else if (isset(Params::$country))
			$nazione = strtoupper(Params::$country);

		return $nazione;
	}

	public static function attivo($idUser)
	{
		$ru = new RegusersModel();
		
		return $ru->clear()->where(array(
			"id_user"	=>	(int)$idUser,
			Users_CheckAdmin::$statusFieldName	=>	Users_CheckAdmin::$statusFieldActiveValue,
		))->rowNumber();
	}

	// Imposta nazione_spedizione e id_corriere sulla base della nazione nell'URL
	public static function impostaNazioneSpedizioneECorriereDaUrl()
	{
		if (v("imposta_la_nazione_di_default_a_quella_nell_url") && Params::$country)
		{
			$nazione = strtoupper(Params::$country);

			$corr = new CorrieriModel();

			$idsCorrieri = $corr->getIdsCorrieriNazione($nazione);

			if (count($idsCorrieri) > 0)
			{
				$_POST["nazione_spedizione"] = $nazione;
				$_POST["id_corriere"] = $idsCorrieri[0];
			}
		}
	}
}
