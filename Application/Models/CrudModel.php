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

trait CrudModel
{
	public function primaImmagineCarrelloCrud($record)
    {
		$immagine = ProdottiModel::immagineCarrello($record[$this->_tables]["id_page"], $record[$this->_tables]["id_c"]);
		
		if ($immagine)
			return "<img src='".Url::getRoot()."thumb/immagineinlistaprodotti/0/".$immagine."' />";
		
		return "";
    }
    
    public function titoloCrud($record)
	{
		$html = $record["caratteristiche"]["titolo"];
		
		if ($record["caratteristiche"]["nota_interna"])
			$html .= " (".$record["caratteristiche"]["nota_interna"].")";
		
		return $html;
	}
	
	public function attivo($record)
	{
		return $record[$this->_tables]["attivo"] ? gtext("Sì") : gtext("No");
	}
	
	public static function variabiliGestibili($id)
	{
		$model = self::g(false);
		
		return $model->where(array(
			$model->_idFields	=>	(int)$id,
		))->field("variabili_gestibili");
	}
	
	public function seCacheCrud($record)
	{
		if ($record[$this->_tables]["crea_cache"])
			return "<i class='text text-success fa fa-check'></i>";
		
		return "";
	}
}
