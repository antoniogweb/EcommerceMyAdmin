<?php

if (!defined('EG')) die('Direct access not allowed!');

class ContattiModel extends GenericModel {
	
	public static $elencoFonti = array(
		"FORM_CONTATTO"		=>	"FORM CONTATTI",
		"NEWSLETTER"		=>	"FORM NEWSLETTER",
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
	
	public function processaEventiContatto($idContatto)
	{
		$contatto = $this->selectId((int)$idContatto);
		
		if (!empty($contatto) && isset($contatto["email"]) && $contatto["email"] && checkMail($contatto["email"]))
			EventiretargetingModel::processaContatto($this->lId);
	}
	
	public function insertDaArray($dati, $fonte)
	{
		$email = isset($dati["username"]) ? $dati["username"] : $dati["email"];
		$idContatto = $this->getIdFromMail($email);
		$dati = htmlentitydecodeDeep($dati);
		
		$this->setValues(array(
			"email"	=>	$email,
			"nome"	=>	isset($dati["nome"]) ? $dati["nome"] : "",
			"cognome"	=>	isset($dati["cognome"]) ? $dati["cognome"] : "",
			"telefono"	=>	isset($dati["telefono"]) ? $dati["telefono"] : "",
			"citta"	=>	isset($dati["citta"]) ? $dati["citta"] : "",
			"azienda"	=>	isset($dati["ragione_sociale"]) ? $dati["ragione_sociale"] : "",
			"nazione"	=>	isset($dati["nazione"]) ? $dati["nazione"] : "",
			"lingua"	=>	isset($dati["lingua"]) ? $dati["lingua"] : "",
			"fonte"		=>	$fonte,
		));
		
		if ($idContatto)
			$this->update($idContatto);
		else
		{
			$this->setValue("fonte_iniziale", $fonte);
			$this->insert();
		}
	}
	
	public function insert()
	{
		$this->unsetDescrizione();
		
		$this->values["creation_time"] = time();
		
		if (!isset($this->values["lingua"]) && isset(Params::$lang))
			$this->values["lingua"] = Params::$lang;
		
		$res = parent::insert();
		
		if ($res)
			$this->processaEventiContatto($this->lId);
		
		return $res;
	}
	
	public function update($id = null, $where = null)
	{
		$this->unsetDescrizione();
		
		$res = parent::update($id, $where);
		
		if ($res)
			$this->processaEventiContatto($id);
		
		return $res;
	}
	
	public function getIdFromMail($email)
	{
		return (int)$this->clear()->where(array(
			"email"	=>	sanitizeAll($email),
		))->field("id_contatto");
	}
}
