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
}
