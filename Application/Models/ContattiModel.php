<?php

if (!defined('EG')) die('Direct access not allowed!');

class ContattiModel extends GenericModel {
	
	public static $elencoFonti = array(
		"CONTATTI"		=>	"FORM CONTATTI",
		"NEWSLETTER"	=>	"FORM NEWSLETTER",
	);
	
	public function __construct() {
		$this->_tables = 'contatti';
		$this->_idFields = 'id_contatto';

		$this->_lang = 'It';
		
		parent::__construct();
	}
	
	public function unsetDescrizione()
	{
		if (isset($this->values["messaggio"]))
			unset($this->values["messaggio"]);
	}
	
	public function insert()
	{
		$this->unsetDescrizione();
		
		if (isset(Params::$lang))
			$this->values["lingua"] = Params::$lang;
		
		return parent::insert();
	}
	
	public function update($id = null, $where = null)
	{
		$this->unsetDescrizione();
		
		return parent::update($id, $where);
	}
	
	public function getIdFromMail($email)
	{
		return (int)$this->clear()->where(array(
			"email"	=>	sanitizeAll($email),
		))->field("id_contatto");
	}
}
