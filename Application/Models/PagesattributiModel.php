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

class PagesattributiModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='pages_attributi';
		$this->_idFields='id_pa';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'pages_attributi.id_order';
		$this->_lang = 'It';
		
		$this->addStrongCondition("both",'checkIsNotStrings|0',"id_a|Si prega di specificare l'attributo da aggiungere");
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'attributo' => array("BELONGS_TO", 'AttributiModel', 'id_a',null,"CASCADE","Si prega di selezionare il corso"),
        );
    }
    
	public function getColonna($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$colonne = $this->clear()->where(array("id_page"=>$clean["id_page"]))->orderBy("id_order")->toList("colonna")->send();
		
		$allowedCols = array("col_1","col_2","col_3","col_4","col_5","col_6","col_7","col_8");
		
		foreach ($allowedCols as $col)
		{
			if (!in_array($col,$colonne))
			{
				return $col;
			}
		}
		return 0;
	}
	
	public function getNomiColonne($id_page, $lingua = null)
	{
		$clean["id_page"] = (int)$id_page;
		
		if (!isset($lingua))
			$lingua = Params::$lang;
		
		$res = $this->clear()
				->select("pages_attributi.colonna,attributi.titolo,contenuti_tradotti.titolo")
				->inner("attributi")->on("attributi.id_a = pages_attributi.id_a")
				->addJoinTraduzione(null, "contenuti_tradotti", false, (new AttributiModel()))
				->where(array("id_page"=>$clean["id_page"]))
				->orderBy("pages_attributi.id_order")
				->send();
		
		$arrayColonne = array();
		
		foreach ($res as $r)
		{
			$arrayColonne[$r["pages_attributi"]["colonna"]] = afield($r, "titolo");
		}
		
		return $arrayColonne;
	}
	
	public function insert()
	{
		$clean["id_page"] = (int)$this->values["id_page"];
		$clean["id_a"] = (int)$this->values["id_a"];
		
// 		if (!PagesModel::variantiModificabili($clean['id_page']))
// 			$this->notice = "<div class='alert'>".gtext("Non è possibile aggiungere una variante ad un prodotto che ha già ordini")."</div>";
// 		else
// 		{
			$res = $this->clear()->where(array("id_page"=>$clean["id_page"],"id_a"=>$clean["id_a"]))->send();
			
			//controllo che l'attributo non sia già stato associato
			if (count($res) > 0)
			{
				$this->notice = "<div class='alert'>".gtext("Questo attributo è già stato associato")."</div>";
			}
			else
			{
				//controllo di non associare un attributo che non abbia valori
				$a = new AttributivaloriModel();
				$res2 = $a->clear()->where(array("id_a"=>$clean["id_a"]))->send();
				
				if (count($res2) > 0)
				{
					//controllo che non abbia associato più di tre attributi
					$res3 = $this->clear()->where(array("id_page"=>$clean["id_page"]))->send();
					if (count($res3) >= v("numero_massimo_varianti_per_prodotto"))
					{
						$this->notice = "<div class='alert'>Non è possibile associare più di ".v("numero_massimo_varianti_per_prodotto")." attributi ad un singolo prodotto</div>";
					}
					else
					{
						$colonna = $this->getColonna($clean["id_page"]);
						$this->values["colonna"] = $colonna;
						
						if ($colonna !== 0)
						{
							if (parent::insert() && v("aggiorna_combinazioni_automaticamente"))
							{
								CombinazioniModel::g()->creaCombinazioni($clean["id_page"]);
							}
						}
						
						return true;
					}
				}
				else
				{
					$this->notice = "<div class='alert'>Questo attributo non può essere associato perché non contiene alcun valore</div>";
				}
			}
// 		}
		
		return false;
	}
	
	public function titoloConNota($record)
	{
		$html = $record["attributi"]["titolo"];
		
		if ($record["attributi"]["nota_interna"])
			$html .= " (".$record["attributi"]["nota_interna"].")";
		
		return "<a class='action_iframe iframe' href='".Url::getRoot()."attributi/form/update/".$record["attributi"]["id_a"]."?partial=Y&nobuttons=Y'>".$html."</a>";
	}
	
	public function del($id = null, $where = null)
	{
		if ($id)
		{
			$record = $this->selectId((int)$id);
			
			if (!empty($record))
			{
				if (parent::del($id, $where) && v("aggiorna_combinazioni_automaticamente"))
					CombinazioniModel::g()->creaCombinazioni($record["id_page"]);
			}
		}
		else
			return parent::del($id, $where);
	}
	
	public function moveup($id)
	{
		$record = $this->selectId((int)$id);
		
		$res = parent::moveUp($id);
		
		if ($res && !empty($record) && v("aggiorna_combinazioni_automaticamente"))
			CombinazioniModel::g()->creaCombinazioni($record["id_page"]);
		
		return $res;
	}
	
	public function movedown($id)
	{
		$record = $this->selectId((int)$id);
		
		$res = parent::movedown($id);
		
		if ($res && !empty($record) && v("aggiorna_combinazioni_automaticamente"))
			CombinazioniModel::g()->creaCombinazioni($record["id_page"]);
		
		return $res;
	}
	
	public function deletable($id)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && !PagesModel::variantiModificabili((int)$record["id_page"]))
			return false;
		
		return true;
	}
}
