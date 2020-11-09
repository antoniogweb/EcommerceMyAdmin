<?php

if (!defined('EG')) die('Direct access not allowed!');

class IplocationModel extends Model_Tree {
	
	public static $durataIp = 3600;
	
	public function __construct() {
		$this->_tables = 'ip_location';
		$this->_idFields = 'id_ip_location';

		parent::__construct();
	}
	
	public function deleteExpired()
	{
		$limit = time() - self::$durataIp; 
		$this->db->del('ip_location','time_creazione < '.$limit);
	}
	
	public static function setData()
	{
		$data = self::getData();
		
		$nazione = v("nazione_default");
		
		$n = new NazioniModel();
		
		if (isset($data["nazione"]))
		{
			if ($n->clear()->where(array(
					"iso_country_code"	=>	sanitizeAll($data["nazione"]),
				))->rowNumber() > 0)
			{
				$nazione = $data["nazione"];
			}
		}
		
		if (function_exists("setLanguageAndCountry"))
		{
			setLanguageAndCountry($data);
		}
// 		print_r(Params::$frontEndLanguages);die();
// 		$lang = strtolower($nazione);
		
// 		if (in_array($lang, Params::$frontEndLanguages))
// 			Params::$defaultFrontEndLanguage = $lang;
		
		User::$nazioneNavigazione = $nazione;
	}
	
	public static function getData()
	{
		$ip = getIp();
		
// 		if ($ip == "127.0.0.1")
// 			return array(
// 				"nazione"	=>	v("nazione_default"),
// 			);
		
		$il = new IplocationModel();
		
		$il->deleteExpired();
		
		$recordIp = $il->clear()->where(array(
			"ip"	=>	sanitizeAll($ip),
		))->record();
		
		if (!empty($recordIp))
			return $recordIp;
		else if (function_exists("getLocationData"))
		{
			$data = getLocationData();
			
			if (isset($data["nazione"]))
			{
				$il->setValues(array(
					"ip"		=>	sanitizeAll($ip),
					"nazione"	=>	$data["nazione"],
					"time_creazione"	=>	time(),
				));
				
				$il->insert();
				
				return $data;
			}
		}
		
		return array(
			"nazione"	=>	v("nazione_default"),
		);
	}

}
