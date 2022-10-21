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
		$this->values["alias"] = "";
		
		$this->checkAliasAll(0, true, false);
		
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
    
    public static function listeUtenteModel($idUser, $idLista = 0)
    {
		$model = self::g()->where(array(
			"id_user"	=>	(int)$idUser,
		));
		
		if ($idLista)
			$model->aWhere(array(
				"id_lista_regalo"	=>	(int)$idLista,
			));
		
		return $model;
    }
    
    public static function numeroListeUtente($idUser, $idLista = 0)
    {
		return self::listeUtenteModel($idUser, $idLista)->rowNumber();
    }
    
    public static function listeUtente($idUser, $idLista = 0, $soloAttive = true)
    {
		$model = self::listeUtenteModel($idUser, $idLista);
		
		if ($soloAttive)
			$model->aWhere(array(
				"attivo"	=>	"Y",
				"gte"	=>	array(
					"data_scadenza"	=>	date("Y-m-d"),
				),
			));
		
		return $model->toList("id_lista_regalo", "titolo")->send();
    }
    
    public static function numeroProdotti($idLista)
    {
		$lrp = new ListeregalopagesModel();
		
		$res = $lrp->clear()->select("sum(quantity) as SOMMA")->where(array(
			"id_lista_regalo"	=>	(int)$idLista,
		))->send();
		
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
}
