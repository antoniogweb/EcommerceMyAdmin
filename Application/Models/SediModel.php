<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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

class SediModel extends BasicsectionModel {
	
	public $hModelName = "SedicatModel";
	
	public function overrideFormStruct()
	{
		$this->formStruct["entries"]["title"]["labelString"] = gtext("Rag. Soc.");
		$this->formStruct["entries"]["email_contatto_evento"]["labelString"] = gtext("Email");
		$this->formStruct["entries"]["telefono_contatto_evento"]["labelString"] = gtext("Telefono");
		$this->formStruct["entries"]["indirizzo_localita_evento"]["labelString"] = gtext("Indirizzo");
		$this->formStruct["entries"]["localita_evento"]["labelString"] = gtext("Località");
		$this->formStruct["entries"]["id_c"]["labelString"] = gtext("Tipologia");
	}
	
	public function setAliasAndCategory()
	{
		if (!isset($this->values["alias"]) || !$this->values["alias"])
			$this->values["alias"] = "";
		
		if (!v("attiva_categorie_sedi"))
		{
			$c = new CategoriesModel();
		
			$this->values["id_c"] = (int)$c->clear()->where(array("section"=>$this->hModel->section))->field("id_c");
		}
	}
	
	public function setFilters()
	{
		parent::setFilters();
		
		if (v("attiva_categorie_sedi"))
		{
			$this->_popupItemNames["id_c"]	=	'id_c';
			$this->_popupLabels["id_c"]	=	'CATEGORIA';
			$this->_popupFunctions["id_c"]	=	'getCatNameForFilters';
			$this->_popupOrderBy["id_c"]	=	'lft asc';
			$this->_popupWhere["id_c"]	=	$this->hModel->getChildrenFilterWhere();
		}
	}
	
	public function insert()
	{
		$res = parent::insert();
		
		if ($res)
			$this->aggiornaTabellaLocalita($this->lastId());
		
		return $res;
	}
	
	public function update($id = null, $where = null)
	{
		$res = parent::update($id, $where);
		
		if ($res)
			$this->aggiornaTabellaLocalita($id);
		
		return $res;
	}
	
	public function regioneCrud($record)
	{
		$r = new RegioniModel();
		
		return $r->titolo($record["pages"]["id_regione"]);
	}
}
