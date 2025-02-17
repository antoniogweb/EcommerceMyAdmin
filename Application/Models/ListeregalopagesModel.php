<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2025  Antonio Gallo (info@laboratoriolibero.com)
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

class ListeregalopagesModel extends GenericModel
{
	use CrudModel;
	
	public function __construct() {
		$this->_tables = 'liste_regalo_pages';
		$this->_idFields = 'id_lista_regalo_page';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'lista' => array("BELONGS_TO", 'ListeregaloModel', 'id_lista_regalo',null,"CASCADE"),
			'pagina' => array("BELONGS_TO", 'PagineModel', 'id_page',null,"CASCADE"),
			'combinazione' => array("BELONGS_TO", 'CombinazioniModel', 'id_c',null,"CASCADE"),
        );
    }
    
    public function insert()
    {
		$this->values["time_creazione"] = time();
		
		return parent::insert();
    }
    
    public function checkAccesso($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && ListeregaloModel::numeroListeUtente(User::$id, $record["id_lista_regalo"]))
			return true;
		
		return false;
    }
    
    public function deletable($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && !ListeregaloModel::numeroRegalati($record["id_lista_regalo"], $record["id_c"]))
			return true;
		
		return false;
    }
    
    public function elimina($id)
	{
		if ($this->checkAccesso((int)$id))
			return $this->del((int)$id);
		
		return false;
	}
	
	public function aggiungi($id_lista, $id_page, $id_c, $quantity)
    {
		$clean["id_lista"] = (int)$id_lista;
		$clean["id_page"] = (int)$id_page;
		$clean["quantity"] = abs((int)$quantity);
		$clean["id_c"] = (int)$id_c;
		
		$idRigaLista = 0;
		
		if (!ListeregaloModel::numeroListeUtente(User::$id, $clean["id_lista"]) || $clean["quantity"] <= 0)
			return $idRigaLista;
		
		$p = new PagesModel();
		
		$res = $p->clear()->select("*")->inner(array("combinazioni"))->addJoinTraduzionePagina()->where(array(
			"pages.id_page"		=>	$clean["id_page"],
			"combinazioni.id_c"	=>	$clean["id_c"],
		))->addWhereAttivoPermettiTest()->first();
		
		if (count($res) > 0)
		{
			$rigaLista = $this->clear()->where(array(
				"id_lista_regalo"	=>	$clean["id_lista"],
				"id_page"	=>	$clean["id_page"],
				"id_c"		=>	$clean["id_c"],
			))->record();
			
			if (!empty($rigaLista))
			{
				$this->sValues(array(
					"quantity"	=>	$rigaLista["quantity"] + $clean["quantity"],
				));
				
				$this->update((int)$rigaLista["id_lista_regalo_page"]);
				
				$idRigaLista = (int)$rigaLista["id_lista_regalo_page"];
			}
			else
			{
				$this->sValues(array(
					"id_lista_regalo"	=>	$clean["id_lista"],
					"id_page"	=>	$clean["id_page"],
					"id_c"		=>	$clean["id_c"],
					"titolo"	=>	htmlentitydecode(field($res, "title")),
					"quantity"	=>	$clean["quantity"],
				));
				
				$this->insert();
				
				$idRigaLista = (int)$this->lId;
			}
		}
		
		return $idRigaLista;
    }
    
	public function set($id, $quantity)
	{
		$clean["id"] = (int)$id;
		$clean["quantity"] = abs((int)$quantity);
		
		if ($this->checkAccesso((int)$id))
		{
			if ($clean["quantity"] === 0)
				$this->elimina($clean["id"]);
			else
			{
				$riga = $this->selectId($clean["id"]);
				
				if (!empty($riga))
				{
					$this->values = array(
						"quantity" => $clean["quantity"],
					);
					
					$this->update($clean["id"]);
				}
			}
		}
	}
	
	public function variante($record)
	{
		return CombinazioniModel::g()->getStringa($record["combinazioni"]["id_c"]);
	}
	
	public function prezzo($record)
	{
		if (v("prezzi_ivati_in_prodotti"))
		{
			$prezzo = $record["combinazioni"]["price_ivato"];
			$prezzoScontato = $record["combinazioni"]["price_scontato_ivato"];
		}
		else
		{
			$prezzo = $record["combinazioni"]["price"];
			$prezzoScontato = $record["combinazioni"]["price_scontato"];
		}
		
		return ($prezzo > $prezzoScontato) ? "<del>".setPriceReverse($prezzo)."</del> ".setPriceReverse($prezzoScontato) : setPriceReverse($prezzoScontato);
	}
	
	public function quantita($record)
	{
		return "<input id-riga='".$record["liste_regalo_pages"]["id_lista_regalo_page"]."' style='max-width:50px;' class='form-control' name='quantity' value='".$record["liste_regalo_pages"]["quantity"]."' />";
	}
	
	public function regalati($record)
	{
		return ListeregaloModel::numeroRegalati($record["liste_regalo_pages"]["id_lista_regalo"], $record["liste_regalo_pages"]["id_c"]);
	}
	
	public function ordini($record)
	{
		return ListeregaloModel::g()->ordini($record["liste_regalo_pages"]["id_lista_regalo"], $record["liste_regalo_pages"]["id_c"]);
	}
}
