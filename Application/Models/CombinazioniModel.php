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

class CombinazioniModel extends GenericModel {
	
	public static $ricreaCombinazioneQuandoElimini = true;
	
	public $cart_uid = null;
	public $colonne = null;
	public $valori = null;
	public $aggiornaGiacenzaPaginaQuandoSalvi = true;
	
	public static $aggiornaAliasAdInserimento = true;
	
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
			'alias' => array("HAS_MANY", 'CombinazionialiasModel', 'id_c', null, "CASCADE"),
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
	
	public function setPriceNonIvato($idPage = 0)
	{
		if (v("prezzi_ivati_in_prodotti") && isset($this->values["price_ivato"]))
		{
			$p = new PagesModel();
			$valore = $p->getIva($idPage);
			
			$this->values["price"] = number_format(setPrice($this->values["price_ivato"]) / (1 + ($valore / 100)), v("cifre_decimali"),".","");
		}
	}
	
	public function insert()
	{
		if (isset($this->values["id_page"]))
			$this->setPriceNonIvato($this->values["id_page"]);
		
		$this->settaCifreDecimali();
		
		$res = parent::insert();
		
		if ($res && self::$aggiornaAliasAdInserimento)
			$this->aggiornaAlias(0,$this->lId);
		
		return $res;
	}
	
	public function aggiornaGiacenzaPagina($id)
	{
		if (!$this->aggiornaGiacenzaPaginaQuandoSalvi)
			return;
		
		$record = $this->selectId((int)$id);
		
		if (!$record["col_1"] && !$record["col_2"] && !$record["col_3"] && !$record["col_4"] && !$record["col_5"] && !$record["col_6"] && !$record["col_7"] && !$record["col_8"])
		{
			$p = new PagesModel();
			
			$p->setValues(array(
				"price"		=>	$record["price"],
				"price_ivato"	=>	$record["price_ivato"],
				"codice"	=>	$record["codice"],
				"peso"		=>	$record["peso"],
				"giacenza"	=>	$record["giacenza"],
			));
			
			$p->pUpdate($record["id_page"]);
		}
	}
	
	public function update($id = null, $where = null)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record))
			$this->setPriceNonIvato($record["id_page"]);
		
		$this->settaCifreDecimali();
		
		if (parent::update($id, $where))
		{
			$this->aggiornaGiacenzaPagina($id);
			
			if (self::$aggiornaAliasAdInserimento)
				$this->aggiornaAlias(0,$id);
			
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
										$temp["price_ivato"] = $dettagliPagina["price_ivato"];
										$temp["peso"] = $dettagliPagina["peso"];
										$temp["giacenza"] = $dettagliPagina["giacenza"];
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
									$temp["price_ivato"] = $dettagliPagina["price_ivato"];
									$temp["peso"] = $dettagliPagina["peso"];
									$temp["giacenza"] = $dettagliPagina["giacenza"];
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
							$temp["price_ivato"] = $dettagliPagina["price_ivato"];
							$temp["peso"] = $dettagliPagina["peso"];
							$temp["giacenza"] = $dettagliPagina["giacenza"];
							$temp["immagine"] = getFirstImage($dettagliPagina["id_page"]);
							$val[] = $temp;
						}
						
					}
				}
			}
			
			$this->del(null,"id_page='".$clean["id_page"]."'");
			
			CombinazioniModel::$aggiornaAliasAdInserimento = false;
			
			foreach ($val as $v)
			{
				$this->values = array();
				$this->values = $v;
				$this->values["id_page"] = $dettagliPagina["id_page"];
				
// 				$this->delFields("id_c");
				$this->delFields("id_order");
				
				$this->sanitize();
				
				$this->insert();
			}
			
			// Controllo che ci sia la combinazione base
			$this->controllaCombinazioniPagina($dettagliPagina["id_page"]);
			
			// Genero gli alias di tutte le combinazioni coinvolte
			$this->aggiornaAlias($dettagliPagina["id_page"]);
			
			// Controlla che esista la combinazione canonical
			$this->checkCanonical($dettagliPagina["id_page"]);
		}
		
		Params::$setValuesConditionsFromDbTableStruct = true;
	}
	
	// Genera gli alias per tutte le righe di combinazione
	public function aggiornaAlias($idPage = 0, $idC = 0, $idAV = 0)
	{
		if (!VariabiliModel::combinazioniLinkVeri())
			return "";

		if (v("usa_transactions"))
			$this->db->beginTransaction();
		
		LingueModel::getValoriAttivi();
		
		$ca = new CombinazionialiasModel();
		
		foreach (LingueModel::$valoriAttivi as $codice => $descrizione)
		{
			$this->clear()->select("combinazioni.id_page,combinazioni.id_c,combinazioni.codice,a1.alias as alias_1,a2.alias as alias_2,a3.alias as alias_3,a4.alias as alias_4,a5.alias as alias_5,a6.alias as alias_6,a7.alias as alias_7,a8.alias as alias_8,at1.alias as alias_t1,at2.alias as alias_t2,at3.alias as alias_t3,at4.alias as alias_t4,at5.alias as alias_t5,at6.alias as alias_t6,at7.alias as alias_t7,at8.alias as alias_t8")
				->left("attributi_valori as a1")->on("a1.id_av = combinazioni.col_1")->left("contenuti_tradotti as at1")->on("at1.id_av = a1.id_av and at1.lingua = '".sanitizeDb($codice)."'")
				->left("attributi_valori as a2")->on("a2.id_av = combinazioni.col_2")->left("contenuti_tradotti as at2")->on("at2.id_av = a2.id_av and at2.lingua = '".sanitizeDb($codice)."'")
				->left("attributi_valori as a3")->on("a3.id_av = combinazioni.col_3")->left("contenuti_tradotti as at3")->on("at3.id_av = a3.id_av and at3.lingua = '".sanitizeDb($codice)."'")
				->left("attributi_valori as a4")->on("a4.id_av = combinazioni.col_4")->left("contenuti_tradotti as at4")->on("at4.id_av = a4.id_av and at4.lingua = '".sanitizeDb($codice)."'")
				->left("attributi_valori as a5")->on("a5.id_av = combinazioni.col_5")->left("contenuti_tradotti as at5")->on("at5.id_av = a5.id_av and at5.lingua = '".sanitizeDb($codice)."'")
				->left("attributi_valori as a6")->on("a6.id_av = combinazioni.col_6")->left("contenuti_tradotti as at6")->on("at6.id_av = a6.id_av and at6.lingua = '".sanitizeDb($codice)."'")
				->left("attributi_valori as a7")->on("a7.id_av = combinazioni.col_7")->left("contenuti_tradotti as at7")->on("at7.id_av = a7.id_av and at7.lingua = '".sanitizeDb($codice)."'")
				->left("attributi_valori as a8")->on("a8.id_av = combinazioni.col_8")->left("contenuti_tradotti as at8")->on("at8.id_av = a8.id_av and at8.lingua = '".sanitizeDb($codice)."'")
				->inner(array("pagina"));
// 				->left("contenuti_tradotti as pagest")->on("pagest.id_page = pages.id_page and pagest.lingua = '".sanitizeDb($codice)."'");
			
			if ($idC)
				$this->where(array(
					"id_c"	=>	(int)$idC,
				));
			else if ($idPage)
				$this->where(array(
					"id_page"	=>	(int)$idPage,
				));
			else if ($idAV)
				$this->where(array(
					"OR"	=>	array(
						"combinazioni.col_1"	=>	(int)$idAV,
						"combinazioni.col_2"	=>	(int)$idAV,
						"combinazioni.col_3"	=>	(int)$idAV,
						"combinazioni.col_4"	=>	(int)$idAV,
						"combinazioni.col_5"	=>	(int)$idAV,
						"combinazioni.col_6"	=>	(int)$idAV,
						"combinazioni.col_7"	=>	(int)$idAV,
						"combinazioni.col_8"	=>	(int)$idAV,
					),
				));
			
			$combinazioni = $this->send(false);
			
			$arrayCol = array(1,2,3,4,5,6,7,8);
			
			foreach ($combinazioni as $c)
			{
				$ca->del(null, array(
					"lingua"	=>	sanitizeAll($codice),
					"id_c"		=>	(int)$c["id_c"],
				));
				
				$arrayAlias = array();
				
				foreach ($arrayCol as $col)
				{
					$alias = $c["alias_t".$col] ? $c["alias_t".$col] : $c["alias_".$col];
					
					if ($alias)
						$arrayAlias[] = $alias;
				}
				
				$aliasAttributi = (count($arrayAlias) > 0) ? implode("-", $arrayAlias) : "";
				
				$ca->sValues(array(
					"alias_attributi"	=>	$aliasAttributi,
					"lingua"		=>	$codice,
					"id_c"			=>	$c["id_c"],
					"id_page"		=>	$c["id_page"],
				), "sanitizeDb");
				
				$ca->insert();
			}
		}
		
		if (v("usa_transactions"))
			$this->db->commit();
	}
	
	public function getCanonical($idPage = 0, $lingua = null, $idC = 0, $fields = "combinazioni.codice,combinazioni_alias.alias_attributi")
	{
		$this->clear()->select($fields)->inner(array("alias"))->where(array(
				"combinazioni_alias.lingua"	=>	sanitizeAll($lingua),
			))->orderBy("canonical desc,id_order")->limit(1);
		
		if ($idC)
			$this->aWhere(array(
				"combinazioni_alias.id_c"	=>	(int)$idC,
			));
		else if ($idPage)
			$this->aWhere(array(
				"combinazioni_alias.id_page"	=>	(int)$idPage,
			));
		
		return $this->send();
	}
	
	// Restituisce l'alias della combinazione
	public function getAlias($idPage = 0, $lingua = null, $idC = 0, $agiungiAlias = true)
	{
		if (!VariabiliModel::combinazioniLinkVeri())
			return "";
		
		$alias = "";
		
		$res = $this->getCanonical($idPage, $lingua, $idC);
		
		if (count($res) > 0)
		{
			$aliasAttributi = $res[0]["combinazioni_alias"]["alias_attributi"];
			$codice = $res[0]["combinazioni"]["codice"];
			
			if ($agiungiAlias && v("usa_alias_combinazione_in_url_prodotto") && $aliasAttributi)
				$alias .= "-".$aliasAttributi;
			
			if (v("usa_codice_combinazione_in_url_prodotto") && $codice)
				$alias .= "-".$codice;
		}
		
		return $alias;
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
	
	public function del($id = null, $where = null)
	{
		if (!isset($where))
		{
			$record = $this->selectId($id);
			
			if (!empty($record))
			{
				if (parent::del($id, $where))
				{
					if (self::$ricreaCombinazioneQuandoElimini)
						$this->controllaCombinazioniPagina($record["id_page"]);
					
					// Controlla che esista la combinazione canonical
					$this->checkCanonical($record["id_page"]);
					
					return true;
				}
				
				return false;
			}
		}
		else
			return parent::del($id, $where);
	}
	
	// Controlla che esista la combinazione canonical di tutte le pagine
	public function checkCanonicalAll()
	{
		$p = new PagesModel();
		
		if (v("usa_transactions"))
			$this->db->beginTransaction();
		
		$idS = $p->clear()
			->select("pages.id_page")
			->inner("(select id_page,max(canonical) as C from combinazioni group by id_page) as comb")->on("pages.id_page = comb.id_page")
			->addWhereCategoria((int)CategoriesModel::getIdCategoriaDaSezione(Parametri::$nomeSezioneProdotti))
			->aWhere(array(
				"comb.C"	=>	0,
			))
			->toList("pages.id_page")
			->send();
		
		foreach ($idS as $id)
		{
			$this->checkCanonical($id);
		}
		
		if (v("usa_transactions"))
			$this->db->commit();
	}
	
	// Controlla che esista la combinazione canonical della pagina
	public function checkCanonical($idPage)
	{
		if (VariabiliModel::combinazioniLinkVeri())
		{
			$combinazione = $this->clear()->select("combinazioni.id_c,combinazioni.canonical")->where(array(
				"id_page"	=>	(int)$idPage,
			))->orderBy("canonical desc,id_order")->limit(1)->send(false);
			
			if (count($combinazione) > 0 && !$combinazione[0]["canonical"])
			{
				$this->sValues(array(
					"canonical"	=>	1,
				));
				
				$this->pUpdate($combinazione[0]["id_c"]);
			}
		}
	}
	
	public function varianti($record)
	{
		$divisorio = v("immagine_in_varianti") ? "<br />" : " - ";
		
		return $this->getStringa($record["combinazioni"]["id_c"], $divisorio);
	}
	
	public function prodotto($record)
	{
		if (!isset($_GET["id_page"]) || $_GET["id_page"] == "tutti")
			return "<a target='_blank' href='".Url::getRoot()."prodotti/form/update/".$record["pages"]["id_page"]."'>".$record["pages"]["title"]."</a>";
		
		return $record["pages"]["title"];
	}
	
	public function immagine($record)
	{
		$strutturaImmagini = PagesModel::listaImmaginiPagina();
		
		$html = "";
		
		$imgSrc = $record["combinazioni"]["immagine"] ? $record["combinazioni"]["immagine"] : "nofound.jpeg";
		$dataUrl = $record["combinazioni"]["immagine"] ? $record["combinazioni"]["immagine"] : "";
		$classe = isset($strutturaImmagini[$record["combinazioni"]["id_page"]]) ? "immagine_variante" : "";
		
		$html .= "<img class='$classe' src='".Url::getRoot()."thumb/immagineinlistaprodotti/0/".$imgSrc."' />";
		
		$html .= "<input type='hidden' name='immagine' value=\"".$record["combinazioni"]["immagine"]."\" />";
		
		if (isset($strutturaImmagini[$record["combinazioni"]["id_page"]]))
		{
			$html .= "<div style='display:none'><div class='box_immagini_varianti'>";
			
			foreach ($strutturaImmagini[$record["combinazioni"]["id_page"]] as $img)
			{
				$html .= "<img class='seleziona_immagine_variante' style='cursor:pointer;' data-img=\"".$img."\" src='".Url::getRoot()."thumb/immagineinlistaprodotti/0/".$img."' />";
			}
			
			$html .= "</div></div>";
			
		}
		
		return $html;
	}
	
	public function codice($record)
	{
		if (!isset($_GET["esporta"]) && !isset($_GET["id_lista_regalo"]))
			return "<input id-c='".$record["combinazioni"]["id_c"]."' style='max-width:120px;' class='form-control' name='codice' value='".$record["combinazioni"]["codice"]."' />";
		else
			return $record["combinazioni"]["codice"];
	}
	
	public function prezzo($record)
	{
		if (!isset($_GET["listino"]) || $_GET["listino"] == "tutti")
		{
			if (v("prezzi_ivati_in_prodotti"))
				$prezzo = $record["combinazioni"]["price_ivato"];
			else
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
			$cifre = v("prezzi_ivati_in_prodotti") ? 2 : v("cifre_decimali");
			
			$prezzo = number_format($prezzo, $cifre, ",", "");
			
			if (!isset($_GET["esporta"]) && !isset($_GET["id_lista_regalo"]))
				return "<input id-c='".$record["combinazioni"]["id_c"]."' $attrIdCl style='max-width:120px;' class='form-control' name='price' value='".$prezzo."' />";
			else
				return $prezzo;
		}
		else
			return "!!";
	}
	
	public function peso($record)
	{
		$peso = number_format($record["combinazioni"]["peso"],2,",","");
		
		if (!isset($_GET["esporta"]) && !isset($_GET["id_lista_regalo"]))
			return "<input id-c='".$record["combinazioni"]["id_c"]."' style='max-width:120px;' class='form-control' name='peso' value='".$peso."' />";
		else
			return $peso;
	}
	
	public function giacenza($record)
	{
		if (!isset($_GET["esporta"]) && !isset($_GET["id_lista_regalo"]))
			return "<input id-c='".$record["combinazioni"]["id_c"]."' style='max-width:120px;' class='form-control' name='giacenza' value='".$record["combinazioni"]["giacenza"]."' />";
		else
			return $record["combinazioni"]["giacenza"];
	}
	
	public function getPrezzoListino($idC, $nazione, $prezzo = null, $campoPrezzo = "price")
	{
		$cl = new CombinazionilistiniModel();
		
		$listino = $cl->clear()->where(array(
			"nazione"	=>	sanitizeAll($nazione),
			"id_c"		=>	(int)$idC,
		))->record();
		
		if (!empty($listino))
			return $listino[$campoPrezzo];
		else if (isset($prezzo))
			return $prezzo;
		else
			return null;
	}
	
	public function primaImmagineCrud($record)
	{
		$immagini = ImmaginiModel::immaginiCombinazione($record["combinazioni"]["id_c"]);
		
		$html = "";
		
		foreach ($immagini as $imm)
		{
			$html .= "<img class='immagine_variante' style='margin-right:5px;' src='".Url::getRoot()."thumb/immagineinlistaprodotti/0/".$imm["immagine"]."' />";
		}
		
		if (count($immagini) > 0)
			$html .= "<br />";
		
		$html .= "<a class='iframe label label-primary' href='".Url::getRoot()."prodotti/immagini/".$record["combinazioni"]["id_page"]."?partial=Y&nobuttons=Y&id_cmb=".$record["combinazioni"]["id_c"]."'><small>".gtext("Gestisci")." <i class='fa fa-pencil'></i></small></a>"; 
		
		return $html;
	}
	
	public function canonical($record)
	{
		if ($record["combinazioni"]["canonical"])
			return "<i class='fa fa-check text text-success'></i>";
		else
			return "<a class='ajlink text text-muted' title='".gtext("Rendi il prodotto canonical")."' href='".Url::getRoot()."combinazioni/rendicanonical/".$record["combinazioni"]["id_c"]."'><i class='fa fa-ban'></i></a>";
	}
	
	public function rendicanonical($idC)
	{
		$combinazione = $this->selectId((int)$idC);
		
		if (!empty($combinazione))
		{
			$this->query("update combinazioni set canonical = 0 where id_page = ".(int)$combinazione["id_page"]);
			
			$this->sValues(array(
				"canonical"	=>	1,
			));
			
			$this->pUpdate($combinazione["id_c"]);
		}
	}
	
	public function afterDuplica($toId, $oldPk, $newPk)
	{
		$i = new ImmaginiModel();
		
		$i->sValues(array(
			"id_c"	=>	$newPk,
		));
		
		$i->pUpdate(null, "id_page = ".(int)$toId." and id_c = ".(int)$oldPk);
	}
	
	public function bulkaggiungialistaregalo($record)
    {
		return "<i data-azione='aggiungialistaregalo' title='".gtext("Aggiungi alla lista regalo")."' class='bulk_trigger help_trigger_aggiungi_a_liste_regalo fa fa-plus-circle text text-primary'></i>";
    }
    
    public function aggiungialistaregalo($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && isset($_GET["id_lista_regalo"]))
		{
			$pagina = PagesModel::g(false)->where(array(
				"id_page"	=>	(int)$record["id_page"],
			))->record();
			
			if (!empty($pagina))
			{
				$lrp = new ListeregalopagesModel();
				
				$lrp->sValues(array(
					"id_lista_regalo"		=>	(int)$_GET["id_lista_regalo"],
					"id_page"	=>	$pagina["id_page"],
					"id_c"		=>	(int)$id,
					"titolo"	=>	$pagina["title"],
					"quantity"	=>	1,
				), "sanitizeDb");
				
				$lrp->pInsert();
			}
		}
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
