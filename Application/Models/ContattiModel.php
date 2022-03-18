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

class ContattiModel extends GenericModel {
	
	public static $uidc = null;
	
	public static $campiContatti = null;
	
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
			EventiretargetingModel::processaContatto($idContatto);
	}
	
	public function insertDaArray($dati, $fonte)
	{
		$email = isset($dati["username"]) ? $dati["username"] : $dati["email"];
		
		if (isset($dati["ragione_sociale"]))
			$azienda = $dati["ragione_sociale"];
		else if (isset($dati["azienda"]))
			$azienda = $dati["azienda"];
		else
			$azienda = "";
		
		$idContatto = $this->getIdFromMail($email);
		$dati = htmlentitydecodeDeep($dati);
		
		$this->setValues(array(
			"email"	=>	$email,
			"azienda"	=>	$azienda,
			"accetto"	=>	(isset($dati["accetto"]) && $dati["accetto"]) ? 1 : 0,
			"fonte"		=>	$fonte,
		));
		
		$arrayCampi = array(
			"nome"		=>	"",
			"cognome"	=>	"",
			"telefono"	=>	"",
			"citta"		=>	"",
			"redirect_to_url"	=>	"",
			"redirect_query_string"	=>	"",
			"nazione"	=>	v("nazione_default"),
			"lingua"	=>	Params::$lang,
			"id_ruolo"	=>	"",
		);
		
		if (isset(self::$campiContatti))
			$arrayCampi = $arrayCampi + self::$campiContatti;
		
		foreach ($arrayCampi as $campo => $valore)
		{
			if (isset($dati[$campo]) && $dati[$campo])
				$this->setValue($campo, $dati[$campo]);
			else if ($valore)
				$this->setValue($campo, $valore);
		}
		
		if ($idContatto)
			$this->update($idContatto);
		else
		{
			$this->setValue("fonte_iniziale", $fonte);
			if ($this->insert())
				$idContatto = $this->lId;
		}
		
		return $idContatto;
	}
	
	// Ottieni il contatto
	public function getDatiContatto()
	{
		$this->getCookie();
		
		if (isset(self::$uidc))
		{
			return $this->clear()->where(array(
				"uid_contatto"	=>	sanitizeAll(self::$uidc),
				"verificato"	=> 1,
			))->record();
		}
		
		return array();
	}
	
	// Recupero il cookie
	public function getCookie()
	{
		if (v("attiva_verifica_contatti") && isset($_COOKIE["uid_contatto"]))
		{
			$clean["uid_contatto"] = sanitizeAll($_COOKIE["uid_contatto"]);
			
			$numero = $this->clear()->where(array(
				"uid_contatto"	=>	$clean["uid_contatto"],
				"verificato"	=>	1,
			))->rowNumber();
			
			if ($numero)
				self::$uidc = $clean["uid_contatto"];
			else
				Cookie::set("uid_contatto", "", (time()-3600), "/");
		}
		
		return self::$uidc;
	}
	
	public function settaCookie($cookieUid)
	{
		$clean["cookieUid"] = sanitizeAll($cookieUid);
		
		$time = time() + v("tempo_durata_uid_contatto");
		self::$uidc = $clean["cookieUid"];
		Cookie::set("uid_contatto", $clean["cookieUid"], $time, "/", true, 'Strict');
		
		$this->setValues(array(
			"time_conferma"	=>	0,
			"verificato"	=>	1,
		));
		
		$this->pUpdate(null, "uid_contatto = '".$clean["cookieUid"]."'");
	}
	
	private function setContactUid()
	{
		self::$uidc = md5(randString(9).microtime().uniqid(mt_rand(),true));
		
		$this->values["time_conferma"] = time();
		$this->values["uid_contatto"] = sanitizeDb(self::$uidc);
	}
	
	public function insert()
	{
		$this->unsetDescrizione();
		
		$this->values["creation_time"] = time();
		
		if (!isset($this->values["lingua"]) && isset(Params::$lang))
			$this->values["lingua"] = Params::$lang;
		
		// Imposta l'uid del contatto
		$this->setContactUid();
		
		$res = parent::insert();
		
		if ($res)
			$this->processaEventiContatto($this->lId);
		
		return $res;
	}
	
	public function update($id = null, $where = null)
	{
		$this->unsetDescrizione();
		
		// Imposta l'uid del contatto
		$this->setContactUid();
		
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
