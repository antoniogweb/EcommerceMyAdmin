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

class NoteModel extends GenericModel
{
	public static $elencoTabellePermesse = array(
		"liste_regalo_pages",
	);
	
	public function __construct() {
		$this->_tables = 'note';
		$this->_idFields = 'id_nota';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'utente' => array("BELONGS_TO", 'UsersModel', 'id_admin',null,"CASCADE"),
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
    
    public function noteCrudHtml($tabellaRif, $idRif)
    {
		$html = "";
		
		$ultimaNota = $this->clear()->select("note.testo,note.data_creazione,adminusers.username")->inner(array("utente"))->where(array(
			"tabella_rif"	=>	sanitizeAll($tabellaRif),
			"id_rif"		=>	(int)$idRif,
		))->orderBy("id_nota desc")->limit(1)->first();
		
		if (!empty($ultimaNota))
			$html .= "<div><small>".gtext("Ultima nota di")." <b>".$ultimaNota["adminusers"]["username"]."</b> ".gtext("il")." <b>".date("d/m/y H:i",strtotime($ultimaNota["note"]["data_creazione"]))."</b><br /><i>".$ultimaNota["note"]["testo"]."</i></small></div>";
		
		$html .= "<small><a class='iframe label label-info' title='".gtext("Aggiungi nota")."' href='".Url::getRoot()."note/form/insert/0?cl_on_sv=Y&partial=Y&nobuttons=Y&tabella=$tabellaRif&id_tabella=$idRif'><i class='fa fa-plus-square-o'></i> ".gtext("Aggiungi nota")."</a></small>";
		
		if (!empty($ultimaNota))
			$html .= "<small style='margin-left:10px;'><a class='iframe label label-default' title='".gtext("Aggiungi nota")."' href='".Url::getRoot()."note/main?partial=Y&tabella=$tabellaRif&id_tabella=$idRif'><i class='fa fa-list'></i> ".gtext("Tutte le note")."</a></small>";
		
		return $html;
    }
}
