<?php
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
}
