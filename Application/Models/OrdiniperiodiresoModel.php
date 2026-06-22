<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2026  Antonio Gallo (info@laboratoriolibero.com)
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

class OrdiniperiodiresoModel extends GenericModel
{
	public function __construct() {
		$this->_tables = 'orders_periodi_reso';
		$this->_idFields = 'id_o_periodo_reso';
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'ordine' => array("BELONGS_TO", 'OrdiniModel', 'id_o',null,"CASCADE"),
        );
    }
	
	public function getUrlRichiediReso($id)
	{
		$pr = $this->selectId((int)$id);
		
		if (!empty($pr))
		{
			$ordine = OrdiniModel::g(false)->clear()->select("id_o,cart_uid,admin_token")->whereId((int)$pr["id_o"])->record();
			
			if (!empty($ordine))
				return Url::getRoot()."reso-ordine/".$ordine["id_o"]."/".$ordine["cart_uid"]."/".$ordine["admin_token"]."/".((int)$id);
		}
		
		return "";
	}
	
	public function inPeriodoReso($id)
	{
		$pr = $this->selectId((int)$id);
		
		if (!empty($pr))
		{
			$inizio = new DateTime($pr["data_inizio"]);
			$fine = new DateTime($pr["data_fine"]);
			$oggi = new DateTime(date("Y-m-d"));
			
			if ($oggi >= $inizio && $oggi <= $fine)
				return true;
		}
		
		return false;
	}
	
	public function isResoInStatoPermesso($id)
	{
		$record = $this->clear()->select("orders_periodi_reso.*,orders.stato")->inner(array("ordine"))->whereId((int)$id)->first();
		
		if (!empty($record))
		{
			$statoOrdine = $record["orders"]["stato"];
			
			if (StatiordineModel::g(false)->permettiReso($statoOrdine))
				return true;
		}
		
		return false;
	}
}
