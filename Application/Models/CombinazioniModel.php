<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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

class CombinazioniModel extends GenericModel {

	public $cart_uid = null;
	public $colonne = null;
	public $valori = null;
	
	public function __construct() {
		$this->_tables='combinazioni';
		$this->_idFields='id_c';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'combinazioni.id_order';
		$this->_lang = 'It';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'listini' => array("HAS_MANY", 'CombinazionilistiniModel', 'id_c', null, "CASCADE"),
			'pagina' => array("BELONGS_TO", 'PagineModel', 'id_page',null,"CASCADE","Si prega di selezionare la pagina"),
        );
    }
    
	public function getStringa($id_c, $char = "<br />", $json = false)
	{
		$clean["id_c"] = (int)$id_c;
		
		$res = $this->clear()->where(array("id_c"=>$clean["id_c"]))->send();
		
		if (count($res) > 0)
		{
			$clean["id_page"] = (int)$res[0]["combinazioni"]["id_page"];
			
			$pa = new PagesattributiModel();
			$attr = new AttributivaloriModel();
			
			$colonne = $pa->getNomiColonne($clean["id_page"]);
			
			$string = "";
			$stringArray = array();
			$jsonArray = array();
			foreach ($colonne as $col => $name)
			{
				$valoreAttributo = $attr->getName($res[0]["combinazioni"][$col]);
				
				$template = v("template_attributo");
				
				if ($template)
				{
					$testoAttributo = str_replace("[NOME]",$name,$template);
					$testoAttributo = str_replace("[VALORE]",$valoreAttributo,$testoAttributo);
				}
				else
					$testoAttributo = "<span class='stringa_attributi_title'>".$name.": </span><span class='stringa_attributi_value'><b>".$valoreAttributo."</b></span> ";
				
				$stringArray[] = $testoAttributo;
				
				$jsonArray[] = array(
					"col"	=>	$col,
					"val"	=>	$res[0]["combinazioni"][$col],
				);
				
				$string .= $testoAttributo;
			}
			
// 			return $string;
			if ($json)
				return json_encode($jsonArray);
			else
				return implode($char, $stringArray);
		}
		
		return "";
	}
	
	// Crea struttura per configuratore
	public function getStrutturaConfiguratore($idPage)
	{
		$struttura = array();
		
		$pa = new PagesattributiModel();
		
		$colonne = $pa->getNomiColonne($idPage);
		
		$where = array(
			"id_page"	=>	(int)$idPage,
		);
		
// 		foreach ($colonne as $col => $name)
// 		{
// 			$res = $this->clear()
// 				->select("combinazioni.$c,attributi_valori.titolo")
// 				->inner("attributi_valori")->on("attributi_valori.id_av = combinazioni.$c")
// 				->orderBy("attributi_valori.id_order")
// 				->groupBy("combinazioni.$c")
// 				->send(false);
// 			
// 			$struttura[$name] = $res;
// 		}
	}
	
	public function creaColonne($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$pa = new PagesattributiModel();
		$attr = new AttributivaloriModel();
		
		$attrCol = $pa->clear()->where(array("id_page"=>$clean["id_page"]))->orderBy("id_order")->toList("id_a","colonna")->send();
			
		$colonne = array();
		$valori = array();
		
		foreach ($attrCol as $id_a => $colonna)
		{
			$colonne[] = $colonna;
			$valori[] = $attr->clear()->where(array("id_a"=>(int)$id_a))->orderBy("id_order")->toList("id_av")->send();
		}
		
		$this->colonne = $colonne;
		$this->valori = $valori;
	}
	
	public function combinazionePrincipale($idPage)
	{
		return $this->clear()->where(array(
			"id_page"	=>	(int)$idPage,
		))->sWhere("col_1 = 0 and col_2 = 0 and col_3 = 0 and col_4 = 0 and col_5 = 0 and col_6 = 0 and col_7 = 0 and col_8 = 0")->record();
	}
	
	public function update($id = null, $where = null)
	{
		if (parent::update($id, $where))
		{
			$record = $this->selectId((int)$id);
			
			if (!$record["col_1"] && !$record["col_2"] && !$record["col_3"] && !$record["col_4"] && !$record["col_5"] && !$record["col_6"] && !$record["col_7"] && !$record["col_8"])
			{
				$p = new PagesModel();
				
				$p->setValues(array(
					"price"		=>	$record["price"],
					"codice"	=>	$record["codice"],
					"peso"		=>	$record["peso"],
				));
				
				$p->pUpdate($record["id_page"]);
			}
			
			return true;
		}
		
		return false;
	}
	
	public function creaCombinazioni($id_page)
	{
		Params::$setValuesConditionsFromDbTableStruct = false;

		$clean["id_page"] = (int)$id_page;
		
		$page = new PagesModel();
		$imm = new ImmaginiModel();
		
		$rr = $page->clear()->where(array("id_page"=>$clean["id_page"]))->send();
		
		//controllo che esista la pagina
		if (count($rr) > 0)
		{
			$dettagliPagina = $rr[0]["pages"];

			$this->creaColonne($clean["id_page"]);
			
			$colonne = $this->colonne;
			$valori = $this->valori;
			
			$val = array();
			
			if (count($colonne) > 0)
			{
				foreach ($valori[0] as $v1)
				{
					$temp = array();
					$where = array("id_page"=>$clean["id_page"]);
					
					$temp[$colonne[0]] = $v1;
					$where[$colonne[0]] = $v1;
					
					if (count($colonne) > 1)
					{
						foreach ($valori[1] as $v2)
						{
							$temp[$colonne[1]] = $v2;
							$where[$colonne[1]] = $v2;
							
							if (count($colonne) > 2)
							{
								foreach ($valori[2] as $v3)
								{
									$temp[$colonne[2]] = $v3;
									$where[$colonne[2]] = $v3;
									
									$t = $this->clear()->where($where)->send();
									if (count($t) > 0)
									{
										if (!$imm->imageExists($t[0]["combinazioni"]["immagine"],$clean["id_page"]))
										{
											$t[0]["combinazioni"]["immagine"] = getFirstImage($dettagliPagina["id_page"]);
										}
										$val[] = $t[0]["combinazioni"];
									}
									else
									{
										$temp["codice"] = $dettagliPagina["codice"];
										$temp["price"] = $dettagliPagina["price"];
										$temp["peso"] = $dettagliPagina["peso"];
										$temp["immagine"] = getFirstImage($dettagliPagina["id_page"]);
										$val[] = $temp;
									}
								}
							}
							else
							{
								$t = $this->clear()->where($where)->send();
								if (count($t) > 0)
								{
									if (!$imm->imageExists($t[0]["combinazioni"]["immagine"],$clean["id_page"]))
									{
										$t[0]["combinazioni"]["immagine"] = getFirstImage($dettagliPagina["id_page"]);
									}
									$val[] = $t[0]["combinazioni"];
								}
								else
								{
									$temp["codice"] = $dettagliPagina["codice"];
									$temp["price"] = $dettagliPagina["price"];
									$temp["peso"] = $dettagliPagina["peso"];
									$temp["immagine"] = getFirstImage($dettagliPagina["id_page"]);
									$val[] = $temp;
								}
							}
						}
					}
					else
					{
						$t = $this->clear()->where($where)->send();
						if (count($t) > 0)
						{
							if (!$imm->imageExists($t[0]["combinazioni"]["immagine"],$clean["id_page"]))
							{
								$t[0]["combinazioni"]["immagine"] = getFirstImage($dettagliPagina["id_page"]);
							}
							$val[] = $t[0]["combinazioni"];
						}
						else
						{
							$temp["codice"] = $dettagliPagina["codice"];
							$temp["price"] = $dettagliPagina["price"];
							$temp["peso"] = $dettagliPagina["peso"];
							$temp["immagine"] = getFirstImage($dettagliPagina["id_page"]);
							$val[] = $temp;
						}
						
					}
				}
			}
			
			$this->del(null,"id_page='".$clean["id_page"]."'");
			
			foreach ($val as $v)
			{
				$this->values = array();
				$this->values = $v;
				$this->values["id_page"] = $dettagliPagina["id_page"];
				
				$this->delFields("id_c");
				$this->delFields("id_order");
				
				$this->sanitize();
				$this->insert();
			}
			
			// Controllo che ci sia la combinazione base
			$this->controllaCombinazioniPagina($dettagliPagina["id_page"]);
		}
		
		Params::$setValuesConditionsFromDbTableStruct = true;
	}
	
	public function controllaCombinazioniPagina($idPage)
	{
		// Controllo che ci sia la combinazione base
		$numero = $this->clear()->where(array(
			"id_page"	=>	(int)$idPage,
		))->rowNumber();
		
		if ((int)$numero === 0)
		{
			$page = new PagesModel();
			$page->controllaCombinazioni((int)$idPage);
		}
	}
	
	// Restituisce la giacenza della combinazione
	public function qta($idC)
	{
		return (int)$this->clear()->where(array(
			"id_c"	=>	(int)$idC,
		))->field("giacenza");
	}
	
// 	public function del($id = null, $where = null)
// 	{
// 		if (!isset($where))
// 		{
// 			$record = $this->selectId($id);
// 			
// 			if (!empty($record))
// 			{
// 				if (parent::del($id, $where))
// 				{
// 					$this->controllaCombinazioniPagina($record["id_page"]);
// 					
// 					return true;
// 				}
// 				
// 				return false;
// 			}
// 		}
// 		else
// 			return parent::del($id, $where);
// 	}
	
	public function varianti($record)
	{
		return $this->getStringa($record["combinazioni"]["id_c"], " - ");
	}
	
	public function prodotto($record)
	{
		if (!isset($_GET["id_page"]) || $_GET["id_page"] == "tutti")
			return "<a target='_blank' href='".Url::getRoot()."prodotti/form/update/".$record["pages"]["id_page"]."'>".$record["pages"]["title"]."</a>";
		
		return $record["pages"]["title"];
	}
	
	public function codice($record)
	{
		if (!isset($_GET["esporta"]))
			return "<input id-c='".$record["combinazioni"]["id_c"]."' style='max-width:120px;' class='form-control' name='codice' value='".$record["combinazioni"]["codice"]."' />";
		else
			return $record["combinazioni"]["codice"];
	}
	
	public function prezzo($record)
	{
		if (!isset($_GET["listino"]) || $_GET["listino"] == "tutti")
		{
			$prezzo = $record["combinazioni"]["price"];
			$attrIdCl = "id-cl='0'";
		}
		else
		{
			$cl = new CombinazionilistiniModel();
			list($idCl, $prezzo) = $cl->getPrezzoListino($record["combinazioni"]["id_c"], $_GET["listino"]);
			$attrIdCl = "id-cl='$idCl'";
		}
		
		if (isset($prezzo))
		{
			$prezzo = number_format($prezzo, 4, ",", "");
			
			if (!isset($_GET["esporta"]))
				return "<input id-c='".$record["combinazioni"]["id_c"]."' $attrIdCl style='max-width:120px;' class='form-control' name='price' value='".$prezzo."' />";
			else
				return $prezzo;
		}
		else
			return "!!";
	}
	
	public function peso($record)
	{
		if (!isset($_GET["esporta"]))
			return "<input id-c='".$record["combinazioni"]["id_c"]."' style='max-width:120px;' class='form-control' name='peso' value='".$record["combinazioni"]["peso"]."' />";
		else
			return $record["combinazioni"]["peso"];
	}
	
	public function giacenza($record)
	{
		if (!isset($_GET["esporta"]))
			return "<input id-c='".$record["combinazioni"]["id_c"]."' style='max-width:120px;' class='form-control' name='giacenza' value='".$record["combinazioni"]["giacenza"]."' />";
		else
			return $record["combinazioni"]["giacenza"];
	}
	
	public function ordini($record)
	{
		$idC = (int)$record["combinazioni"]["id_c"];
		
		$r = new RigheModel();
		
		$res = $r->clear()->select("sum(quantity) as SOMMA")->where(array(
			"id_c"	=>	$idC,
		))->send();
		
		if (count($res) > 0 && $res[0]["aggregate"]["SOMMA"] > 0)
		{
			if (!isset($_GET["esporta"]))
				return $res[0]["aggregate"]["SOMMA"]." <a title='Elenco ordini dove è stato acquistato' class='iframe' href='".Url::getRoot()."ordini/main?partial=Y&id_comb=$idC'><i class='fa fa-list'></i></a>";
			else
				return $res[0]["aggregate"]["SOMMA"];
		}
		
		return "";
	}
	
// 	public function col2($record)
// 	{
// 		$idAttr = $record["combinazioni"]["col_2"];
// 		
// 		$av = new AttributivaloriModel();
// 		
// 		$var = $av->selectId($idAttr);
// 		
// 		if (!empty($var))
// 			return $var["titolo"];
// 	}
}
