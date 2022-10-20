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
    
    public function insert()
    {
		$this->values["time_creazione"] = time();
		
		$this->settaDataScadenza();
		
		return parent::insert();
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
    
    public function aggiungi($id_lista, $id_page, $id_c, $quantity)
    {
		$clean["id_lista"] = (int)$id_lista;
		$clean["id_page"] = (int)$id_page;
		$clean["quantity"] = abs((int)$quantity);
		$clean["id_c"] = (int)$id_c;
		
		$idRigaLista = 0;
		
		if (!self::numeroListeUtente(User::$id, $clean["id_lista"]) || $clean["quantity"] <= 0)
			return $idRigaLista;
		
		$p = new PagesModel();
		
		$res = $p->clear()->select("*")->inner(array("combinazioni"))->addJoinTraduzionePagina()->where(array(
			"pages.id_page"		=>	$clean["id_page"],
			"combinazioni.id_c"	=>	$clean["id_c"],
		))->addWhereAttivo()->first();
		
		if (count($res) > 0)
		{
			$lrp = new ListeregalopagesModel();
			
			$rigaLista = $lrp->clear()->where(array(
				"id_lista_regalo"	=>	$clean["id_lista"],
				"id_page"	=>	$clean["id_page"],
				"id_c"		=>	$clean["id_c"],
			))->record();
			
			if (!empty($rigaLista))
			{
				$lrp->sValues(array(
					"quantity"	=>	$rigaLista["quantity"] + $clean["quantity"],
				));
				
				$lrp->update((int)$rigaLista["id_lista_regalo_page"]);
				
				$idRigaLista = (int)$rigaLista["id_lista_regalo_page"];
			}
			else
			{
				$lrp->sValues(array(
					"id_lista_regalo"	=>	$clean["id_lista"],
					"id_page"	=>	$clean["id_page"],
					"id_c"		=>	$clean["id_c"],
					"titolo"	=>	htmlentitydecode(field($res, "title")),
					"quantity"	=>	$clean["quantity"],
				));
				
				$lrp->insert();
				
				$idRigaLista = (int)$lrp->lId;
			}
		}
		
		return $idRigaLista;
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
}
