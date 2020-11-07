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
	
	public static $adminLogged = false;
	public static $asJson = false;
	
	public static $nazione = null;
	
	public static function getSpedizioneDefault()
	{
// 		if (User::$logged)
// 		{
// 			$sp = new SpedizioniModel();
// 			
// 			$spedizione = $sp->clear()->where(array(
// 				"id_user"		=>	(int)User::$id,
// 				"ultimo_usato"	=>	"Y",
// 			))->record();
// 			
// 			if (!empty($spedizione))
// 				return $spedizione["nazione_spedizione"];
// 		}
		
		return v("nazione_default");
	}
}
