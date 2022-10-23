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

class ListeregaloModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'liste_regalo';
		$this->_idFields = 'id_lista_regalo';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'tipo' => array("BELONGS_TO", 'ListeregalotipiModel', 'id_lista_tipo',null,"CASCADE"),
        );
    }
    
    public function settaDataScadenza()
    {
		if (isset($this->values["id_lista_tipo"]))
		{
			$numeroGiorni = (int)ListeregalotipiModel::g()->where(array(
				"id_lista_tipo"	=>	(int)$this->values["id_lista_tipo"],
			))->field("giorni_scadenza");
			
			if ($numeroGiorni > 0)
			{
				$date = new DateTime();
				$date->modify("+$numeroGiorni days");
				
				$this->values["data_scadenza"] = $date->format("Y-m-d");
			}
		}
    }
    
    //get a unique cod trans
	public function codiceUnivoco($codice, $numero = 7)
	{
		$res = $this->clear()->where(array("codice"=>sanitizeDb($codice)))->send();
		
		if (count($res) > 0)
		{
			$numero++;
			$nUid = generateString($numero);
			return $this->codiceUnivoco($nUid, $numero);
		}
		
		return $codice;
	}
    
    public function insert()
    {
		if (!isset($this->values["alias"]))
			$this->values["alias"] = "";
		
		$this->checkAliasAll(0, true, false);
		
		$this->values["alias"] = sanitizeAll($this->values["alias"]);
		
		$this->values["time_creazione"] = time();
		
		$this->settaDataScadenza();
		
		if (parent::insert())
		{
			if (isset($this->values["genitore_1"]))
				$codice = encodeUrl(str_replace(" ","",htmlentitydecode($this->values["genitore_1"]))).$this->lId;
			else if (isset($this->values["titolo"]))
				$codice = encodeUrl(str_replace(" ","",htmlentitydecode($this->values["titolo"]))).$this->lId;
			else
				$codice = generateString(8).$this->lId;
			
			$codice = $this->codiceUnivoco($codice);
			
			$this->sValues(array(
				"codice"	=>	$codice,
			));
			
			$this->pUpdate($this->lId);
		}
    }
    
    public function update($id = null, $where = null)
    {
		$this->values["alias"] = "";
		
		$this->checkAliasAll((int)$id, true, false);
		
		return parent::update($id, $where);
    }
    
    public static function listeUtenteModel($idUser = 0, $idLista = 0)
    {
		$model = self::g();
		
		if ($idUser)
			$model->aWhere(array(
			"id_user"	=>	(int)$idUser,
		));
		
		if ($idLista)
			$model->aWhere(array(
				"id_lista_regalo"	=>	(int)$idLista,
			));
		
		return $model;
    }
    
    public static function numeroListeUtente($idUser = 0, $idLista = 0)
    {
		return self::listeUtenteModel($idUser, $idLista)->rowNumber();
    }
    
    public static function listeUtenteAttiveModel($idUser = 0, $idLista = 0)
    {
		return self::listeUtenteModel($idUser, $idLista)->aWhere(array(
			"attivo"	=>	"Y",
			"gte"	=>	array(
				"data_scadenza"	=>	date("Y-m-d"),
			),
		));
    }
    
    public static function listeUtente($idUser = 0, $idLista = 0, $soloAttive = true)
    {
		if ($soloAttive)
			$model = self::listeUtenteAttiveModel($idUser, $idLista);
		else
			$model = self::listeUtenteModel($idUser, $idLista);
		
		return $model->toList("id_lista_regalo", "titolo")->send();
    }
    
    public static function scaduta($idLista)
    {
		return self::listeUtenteModel(0, $idLista)->aWhere(array(
			"lt"	=>	array(
				"data_scadenza"	=>	date("Y-m-d"),
			),
		))->rowNumber();
    }
    
    public static function attiva($idLista)
    {
		return self::listeUtenteAttiveModel(0, $idLista)->rowNumber();
    }
    
    public static function numeroProdotti($idLista, $idC = 0)
    {
		$lrp = new ListeregalopagesModel();
		
		$lrp->clear()->select("sum(quantity) as SOMMA")->where(array(
			"id_lista_regalo"	=>	(int)$idLista,
		));
		
		if ($idC)
			$lrp->aWhere(array(
				"id_c"	=>	(int)$idC,
			));
		
		$res = $lrp->send();
		
		if (count($res) > 0)
			return (int)$res[0]["aggregate"]["SOMMA"];
		
		return 0;
    }
    
    public function getProdotti($idLista)
    {
		$lrp = new ListeregalopagesModel();
		
		return $lrp->clear()->select("*")
			->inner(array("pagina"))
			->left("contenuti_tradotti")->on("contenuti_tradotti.id_page = pages.id_page and contenuti_tradotti.lingua = '".sanitizeDb(Params::$lang)."'")
			->inner("categories")->on("categories.id_c = pages.id_c")
			->aWhere(array(
				"liste_regalo_pages.id_lista_regalo"	=>	(int)$idLista,
			))
			->send();
    }
    
    public static function numeroRegalati($idLista, $idC = 0)
    {
		return 0;
    }
    
    public static function numeroRimastiDaRegalare($idLista, $idC = 0)
    {
		return self::numeroProdotti($idLista, $idC);
    }
	
	public static function getCookieIdLista()
	{
		if (isset($_COOKIE[v("nome_cookie_id_lista")]))
		{
			$idLista = (int)$_COOKIE[v("nome_cookie_id_lista")];
			
			if (self::attiva($idLista))
			{
				User::$idLista = $idLista;
			}
			else
			{
				setcookie(v("nome_cookie_id_lista"), "", time()-3600,"/");
				unset($_COOKIE[v("nome_cookie_id_lista")]);
			}
		}
	}
	
    public static function setCookieIdLista($idLista)
    {
		if (self::attiva((int)$idLista))
		{
			User::$idLista = (int)$idLista;
			
			$time = time() + v("tempo_durata_cookie_id_lista");
			
			Cookie::set(v("nome_cookie_id_lista"), User::$idLista, $time, "/", true, 'Lax');
		}
    }
}
