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

class StatielementiModel extends GenericModel
{
	public static $elencoTabellePermesse = array(
		"liste_regalo_pages",
	);
	
	public function __construct() {
		$this->_tables = 'stati_elementi';
		$this->_idFields = 'id_stato_elemento';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'utente' => array("BELONGS_TO", 'UsersModel', 'id_admin',null,"CASCADE"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'id_stato'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Stato",
					"options"	=>	OpzioniModel::codice("STATI_ELEMENTI", "id_opzione"),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
		);
	}
    
    public function insert()
    {
		$this->values["id_admin"] = (int)User::$id;
		
		return parent::insert();
    }
    
    public function manageable($id)
	{
		return $this->deletable($id);
	}
    
    public function deletable($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && (int)$record["id_admin"] === User::$id)
			return true;
		
		return false;
    }
    
    public function statiElementiOptions($tabellaRif, $idRif)
    {
		$ultimaStato = $this->getUltimoUsato($tabellaRif, $idRif);
		
		$arrayOpzioni = null;
		
		if (!empty($ultimaStato))
		{
			$arrayOpzioni = array(
				"titolo"	=>	$ultimaStato["opzioni"]["titolo"],
				"colore"	=>	$ultimaStato["opzioni"]["valore"],
			);
		}
		
		return $arrayOpzioni;
    }
    
    private function getUltimoUsato($tabellaRif, $idRif)
    {
		return $this->clear()->select("opzioni.*,stati_elementi.id_stato,stati_elementi.data_creazione,adminusers.username")
			->inner(array("utente"))
			->inner("opzioni")->on("opzioni.id_opzione = stati_elementi.id_stato")
			->where(array(
				"tabella_rif"	=>	sanitizeAll($tabellaRif),
				"id_rif"		=>	(int)$idRif,
			))->orderBy("id_stato_elemento desc")->limit(1)->first();
    }
    
    public function statiElementiCrudHtml($tabellaRif, $idRif)
    {
		$html = "";
		
// 		$ultimaStato = $this->clear()->select("opzioni.*,stati_elementi.id_stato,stati_elementi.data_creazione,adminusers.username")
// 		->inner(array("utente"))
// 		->inner("opzioni")->on("opzioni.id_opzione = stati_elementi.id_stato")
// 		->where(array(
// 			"tabella_rif"	=>	sanitizeAll($tabellaRif),
// 			"id_rif"		=>	(int)$idRif,
// 		))->orderBy("id_stato_elemento desc")->limit(1)->first();
		
		$ultimaStato = $this->getUltimoUsato($tabellaRif, $idRif);
		
		if (!empty($ultimaStato))
			$html .= "<div><small>".gtext("Impostato da")." <b>".$ultimaStato["adminusers"]["username"]."</b> ".gtext("il")." <b>".date("d/m/y H:i",strtotime($ultimaStato["stati_elementi"]["data_creazione"]))."</b></small></div><span style='background-color:".$ultimaStato["opzioni"]["valore"]." !important;' class='label label-info'>".$ultimaStato["opzioni"]["titolo"]."</span>";
		
		$iconaModifica = !empty($ultimaStato) ? "pencil" : "plus";
		
		$html .= "<span style='margin-left:10px;'><a class='iframe badge' title='".gtext("Modifica stato")."' href='".Url::getRoot()."statielementi/form/insert/0?cl_on_sv=Y&partial=Y&nobuttons=Y&tabella=$tabellaRif&id_tabella=$idRif'><i class='fa fa-$iconaModifica'></i></a></span>";
		
		if (!empty($ultimaStato))
			$html .= "<span style='margin-left:10px;'><a class='iframe label label-info' title='".gtext("Storico degli stati")."' href='".Url::getRoot()."statielementi/main?partial=Y&tabella=$tabellaRif&id_tabella=$idRif'><i class='fa fa-clock-o'></i></a></span>";
		
		return $html;
    }
    
    public function labelStatoCrud($record)
    {
		$oModel = new OpzioniModel();
		
		$opzione = $oModel->clear()->selectId($record["stati_elementi"]["id_stato"]);
		
		if (!empty($opzione))
			return "<span style='background-color:".$opzione["valore"]." !important;' class='label label-info'>".$opzione["titolo"]."</span>";
		
		return "";
    }
}
