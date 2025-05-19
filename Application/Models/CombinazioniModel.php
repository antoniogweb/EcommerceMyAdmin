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

class CombinazioniModel extends GenericModel {
	
	use CrudModel;
	
	private $arrayIdcRecuperati = array();
	
	public static $ricreaCombinazioneQuandoElimini = true;
	
	public static $permettiSempreEliminazione = false;
	
	public $cart_uid = null;
	public $colonne = null;
	public $valori = null;
	public $aggiornaGiacenzaPaginaQuandoSalvi = true;
	
	public static $aggiornaAliasAdInserimento = true;
	
	public $campoValore = "id_c";
	public $metodoPerTitolo = "titoloJson";
	
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
// 			!! NO !! 'movimenti' => array("HAS_MANY", 'CombinazionimovimentiModel', 'id_c', null, "CASCADE"),
			'pagina' => array("BELONGS_TO", 'PagineModel', 'id_page',null,"CASCADE","Si prega di selezionare la pagina"),
        );
    }
    
	public function getStringa($id_c, $char = "<br />", $json = false, $backend = false)
	{
		$clean["id_c"] = (int)$id_c;
		
		$res = $this->clear()->where(array("id_c"=>$clean["id_c"]))->send();
		
		if (count($res) > 0)
		{
			$clean["id_page"] = (int)$res[0]["combinazioni"]["id_page"];
			
			$pa = new PagesattributiModel();
			$attr = new AttributivaloriModel();
			
			$colonne = $pa->getNomiColonne($clean["id_page"], null, $backend);
			
			$string = "";
			$stringArray = array();
			$jsonArray = array();
			foreach ($colonne as $col => $name)
			{
				if ($backend)
					$valoreAttributo = $attr->getNameBackend($res[0]["combinazioni"][$col]);
				else
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
			
			if (v("campo_attributi_di_default") && count($stringArray) === 0)
				$stringArray[] = v("campo_attributi_di_default");
			
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
		$valoriTitolo = array();
		
		foreach ($attrCol as $id_a => $colonna)
		{
			$colonne[] = $colonna;
			$valori[] = $attr->clear()->where(array("id_a"=>(int)$id_a))->orderBy("id_order")->toList("id_av")->send();
		}
		
		$this->colonne = $colonne;
		$this->valori = $valori;
	}
	
	public function combinazionePrincipaleOCanonica($idPage, $forzaCanonicalSeNoPrincipale = false)
	{
		$clean["id_page"] = (int)$idPage;
		
		$principale = $this->combinazionePrincipale($clean["id_page"]);
		
		$idC = 0;
		
		if (!empty($principale))
			$idC = (int)$principale["id_c"];
		else if (v("permetti_acquisto_da_categoria_se_ha_una_combinazione") || $forzaCanonicalSeNoPrincipale)
			$idC = (int)PagesModel::g(false)->getIdCombinazioneCanonical($clean["id_page"]);
		
		return $idC;
	}
	
	public function combinazionePrincipale($idPage)
	{
		return $this->clear()->where(array(
			"id_page"	=>	(int)$idPage,
		))->sWhere("col_1 = 0 and col_2 = 0 and col_3 = 0 and col_4 = 0 and col_5 = 0 and col_6 = 0 and col_7 = 0 and col_8 = 0")->record();
	}
	
	public function setPriceNonIvato($idPage = 0)
	{
		if (v("prezzi_ivati_in_prodotti") && (isset($this->values["price_ivato"]) || isset($this->values["price_scontato_ivato"])))
		{
			$p = new PagesModel();
			$valore = $p->getIva($idPage);
			
			if (isset($this->values["price_ivato"]))
				$this->values["price"] = number_format(setPrice($this->values["price_ivato"]) / (1 + ($valore / 100)), v("cifre_decimali"),".","");
			
			if (isset($this->values["price_scontato_ivato"]))
				$this->values["price_scontato"] = number_format(setPrice($this->values["price_scontato_ivato"]) / (1 + ($valore / 100)), v("cifre_decimali"),".","");
		}
	}
	
	public function setDataOraModifica()
	{
		$this->values["data_ora_modifica"] = date("Y-m-d H:i:s");
	}
	
	public function insert()
	{
		if (isset($this->values["id_page"]))
			$this->setPriceNonIvato($this->values["id_page"]);
		
		$this->settaCifreDecimali();
		
		$this->setDataOraModifica();
		
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
// 				"price"		=>	$record["price"],
// 				"price_ivato"	=>	$record["price_ivato"],
// 				"codice"	=>	$record["codice"],
// 				"gtin"		=>	$record["gtin"],
// 				"mpn"		=>	$record["mpn"],
// 				"peso"		=>	$record["peso"],
// 				"giacenza"	=>	$record["giacenza"],
			));
			
			foreach (PagesModel::$campiDaSincronizzareConCombinazione as $field)
			{
				$p->setValue($field, $record[$field]);
			}
			
			if (v("gestisci_sconti_combinazioni_separatamente"))
			{
				$p->setValue("tipo_sconto", "ASSOLUTO");
				$p->setValue("prezzo_promozione_ass",  $record["price_scontato"]);
				$p->setValue("prezzo_promozione_ass_ivato", $record["price_scontato_ivato"]);
				
				if (number_format($record["price"],2,".","") == number_format($record["price_scontato"],2,".",""))
					$p->setValue("in_promozione", "N");
			}
			
			$p->salvaDataModifica = false;
			$p->pUpdate($record["id_page"]);
		}
	}
	
	public function update($id = null, $where = null)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record))
			$this->setPriceNonIvato($record["id_page"]);
		
		$this->settaCifreDecimali();
		
		$this->setDataOraModifica();
		
		if (parent::update($id, $where))
		{
			$this->aggiornaGiacenzaPagina($id);
			
			if (self::$aggiornaAliasAdInserimento)
				$this->aggiornaAlias(0,$id);
			
			return true;
		}
		
		return false;
	}
	
	public function aggiungiValoriACombinazione($temp, $dettagliPagina)
	{
		foreach (PagesModel::$campiDaSincronizzareConCombinazione as $field)
		{
			$temp[$field] = $dettagliPagina[$field];
		}
		
// 		$temp["codice"] = $dettagliPagina["codice"];
// 		$temp["price"] = $dettagliPagina["price"];
// 		$temp["price_ivato"] = $dettagliPagina["price_ivato"];
// 		$temp["peso"] = $dettagliPagina["peso"];
// 		$temp["giacenza"] = $dettagliPagina["giacenza"];
		
		$temp["immagine"] = getFirstImage($dettagliPagina["id_page"]);
		
		$temp["price_scontato"] = PagesModel::getPrezzoScontato($dettagliPagina, $temp["price"]);
		$temp["price_scontato_ivato"] = PagesModel::getPrezzoScontato($dettagliPagina, $temp["price_ivato"]);
		
		return $temp;
	}
	
// 	private function aggiungiColonna($temp, $where, $id_page, $dettagliPagina)
// 	{
// 		$clean["id_page"] = (int)$id_page;
// 		
// 		$t = $this->clear()->where($where)->send();
// 		if (count($t) > 0)
// 		{
// 			if (!ImmaginiModel::g()->imageExists($t[0]["combinazioni"]["immagine"],$clean["id_page"]))
// 				$t[0]["combinazioni"]["immagine"] = getFirstImage($dettagliPagina["id_page"]);
// 			
// 			return $t[0]["combinazioni"];
// 		}
// 		else
// 		{
// 			$temp = $this->aggiungiValoriACombinazione($temp, $dettagliPagina);
// 			return $temp;
// 		}
// 	}
	
	private function aggiungiColonna($temp, $where, $id_page, $dettagliPagina)
	{
		$clean["id_page"] = (int)$id_page;
		
		$t = $this->clear()->where($where)->send();
		if (count($t) > 0)
		{
			if (!ImmaginiModel::g()->imageExists($t[0]["combinazioni"]["immagine"],$clean["id_page"]))
				$t[0]["combinazioni"]["immagine"] = getFirstImage($dettagliPagina["id_page"]);
			
			$this->arrayIdcRecuperati[] = $t[0]["combinazioni"]["id_c"];
			
			return $t[0]["combinazioni"];
		}
		else
		{
			if (count($where) > 1)
			{
				$whereVariantiPrec = $where;
				array_pop($whereVariantiPrec);
				
				$t = $this->clear()->where($whereVariantiPrec)->send();
				
				if (count($t) > 0)
				{
					$tt = $t[0]["combinazioni"];
					
					foreach ($temp as $cField => $cValue)
					{
						$tt[$cField] = $cValue;
					}
					
					if (in_array($t[0]["combinazioni"]["id_c"], $this->arrayIdcRecuperati))
					{
						unset($tt["giacenza"]);
						unset($tt["canonical"]);
// 						unset($tt["acquistabile"]);
						unset($tt["id_c"]);
					}
					else
					{
						$this->arrayIdcRecuperati[] = $t[0]["combinazioni"]["id_c"];
					}
					
					return $tt;
				}
				else
				{
					$temp = $this->aggiungiValoriACombinazione($temp, $dettagliPagina);
					return $temp;
				}
			}
			else
			{
				$temp = $this->aggiungiValoriACombinazione($temp, $dettagliPagina);
				return $temp;
			}
		}
	}
	
	public function creaCombinazioni($id_page)
	{
		$this->arrayIdcRecuperati = array();
		
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
									
									if (count($colonne) > 3)
									{
										foreach ($valori[3] as $v4)
										{
											$temp[$colonne[3]] = $v4;
											$where[$colonne[3]] = $v4;
											
											if (count($colonne) > 4)
											{
												foreach ($valori[4] as $v5)
												{
													$temp[$colonne[4]] = $v5;
													$where[$colonne[4]] = $v5;
											
													$val[] = $this->aggiungiColonna($temp, $where, $clean["id_page"], $dettagliPagina);
												}
											}
											else
											{
												$val[] = $this->aggiungiColonna($temp, $where, $clean["id_page"], $dettagliPagina);
											}
										}
									}
									else
									{
										$val[] = $this->aggiungiColonna($temp, $where, $clean["id_page"], $dettagliPagina);
									}
								}
							}
							else
							{
								$val[] = $this->aggiungiColonna($temp, $where, $clean["id_page"], $dettagliPagina);
							}
						}
					}
					else
					{
						$val[] = $this->aggiungiColonna($temp, $where, $clean["id_page"], $dettagliPagina);
					}
				}
			}
			
			$combPrincipale = $this->combinazionePrincipale($clean["id_page"]);
			
			$this->del(null, array(
				"id_page"	=>	$clean["id_page"]
			));
			
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
			
			if (!empty($combPrincipale))
				PagesModel::$IdCmb = $combPrincipale["id_c"];
			
			$this->controlliDopoCreazioneCombinazione($dettagliPagina["id_page"]);
			
			// Recupero le combinazioni da ordini, liste regalo o movimentazioni
			$this->recuperaCombinazioni($dettagliPagina["id_page"]);
		}
		
		Params::$setValuesConditionsFromDbTableStruct = true;
	}
	
	public function recuperaCombinazioni($idPage)
	{
		$cmModel = new CombinazionimovimentiModel();
		
		$sql = "select id_c from combinazioni_movimenti where id_page = ? UNION select id_c from righe where id_page = ? UNION select id_c from liste_regalo_pages where id_page = ?";
		
		$res = $this->query(array($sql, array((int)$idPage, (int)$idPage, (int)$idPage)));
		
		$idCS = $this->getList($res, "aggregate.id_c");
		
		$idCS = array_unique($idCS);
		
		foreach ($idCS as $idC)
		{
			$idC = (int)$idC;
			
			if (!$this->clear()->whereId($idC)->rowNumber())
			{
				$this->sValues(array(
					"id_c"		=>	$idC,
					"id_page"	=>	$idPage,
					"canonical"	=>	0,
					"acquistabile"	=>	0,
				));
				
				$this->insert();
			}
		}
	}
	
	public function controlliDopoCreazioneCombinazione($idPage, $checkCanonicalExists = true)
	{
		// Controllo che ci sia la combinazione base
		$this->controllaCombinazioniPagina($idPage);
		
		// Genero gli alias di tutte le combinazioni coinvolte
		$this->aggiornaAlias($idPage);
		
		// Controlla che esista la combinazione canonical
		if ($checkCanonicalExists)
			$this->checkCanonical($idPage);

		$page = new PagesModel();

		// Imposto i prezzi scontati
		$page->aggiornaPrezziCombinazioni($idPage);
	}
	
	// Genera gli alias per tutte le righe di combinazione
	public function aggiornaAlias($idPage = 0, $idC = 0, $idAV = 0, $lingua = null)
	{
		if (!VariabiliModel::combinazioniLinkVeri())
			return "";

		if (v("usa_transactions"))
			$this->db->beginTransaction();
		
		LingueModel::getValoriAttivi();
		
		$ca = new CombinazionialiasModel();
		
		foreach (LingueModel::$valoriAttivi as $codice => $descrizione)
		{
			if (isset($lingua) && $lingua != $codice)
				continue;

			$this->clear()->select("pages.alias as aliasp,pagest.alias as aliaspt,combinazioni.id_page,combinazioni.id_c,combinazioni.codice,a1.alias as alias_1,a2.alias as alias_2,a3.alias as alias_3,a4.alias as alias_4,a5.alias as alias_5,a6.alias as alias_6,a7.alias as alias_7,a8.alias as alias_8,at1.alias as alias_t1,at2.alias as alias_t2,at3.alias as alias_t3,at4.alias as alias_t4,at5.alias as alias_t5,at6.alias as alias_t6,at7.alias as alias_t7,at8.alias as alias_t8")
				->left("attributi_valori as a1")->on("a1.id_av = combinazioni.col_1")->left("contenuti_tradotti as at1")->on(array("at1.id_av = a1.id_av and at1.lingua = ?",array(sanitizeDb($codice))))
				->left("attributi_valori as a2")->on("a2.id_av = combinazioni.col_2")->left("contenuti_tradotti as at2")->on(array("at2.id_av = a2.id_av and at2.lingua = ?",array(sanitizeDb($codice))))
				->left("attributi_valori as a3")->on("a3.id_av = combinazioni.col_3")->left("contenuti_tradotti as at3")->on(array("at3.id_av = a3.id_av and at3.lingua = ?",array(sanitizeDb($codice))))
				->left("attributi_valori as a4")->on("a4.id_av = combinazioni.col_4")->left("contenuti_tradotti as at4")->on(array("at4.id_av = a4.id_av and at4.lingua = ?",array(sanitizeDb($codice))))
				->left("attributi_valori as a5")->on("a5.id_av = combinazioni.col_5")->left("contenuti_tradotti as at5")->on(array("at5.id_av = a5.id_av and at5.lingua = ?",array(sanitizeDb($codice))))
				->left("attributi_valori as a6")->on("a6.id_av = combinazioni.col_6")->left("contenuti_tradotti as at6")->on(array("at6.id_av = a6.id_av and at6.lingua = ?",array(sanitizeDb($codice))))
				->left("attributi_valori as a7")->on("a7.id_av = combinazioni.col_7")->left("contenuti_tradotti as at7")->on(array("at7.id_av = a7.id_av and at7.lingua = ?",array(sanitizeDb($codice))))
				->left("attributi_valori as a8")->on("a8.id_av = combinazioni.col_8")->left("contenuti_tradotti as at8")->on(array("at8.id_av = a8.id_av and at8.lingua = ?",array(sanitizeDb($codice))))
				->inner(array("pagina"))
				->left("contenuti_tradotti as pagest")->on(array("pagest.id_page = pages.id_page and pagest.lingua = ?", array(sanitizeDb($codice))));
			
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
				
// 				$aliasPagina = $c["aliasp"];
// 				
// 				if ($codice != LingueModel::getPrincipaleFrontend() && $c["aliaspt"])
// 					$aliasPagina = $c["aliaspt"];
				
				$ca->sValues(array(
					"alias_attributi"	=>	$aliasAttributi,
					"lingua"		=>	$codice,
					"id_c"			=>	$c["id_c"],
					"id_page"		=>	$c["id_page"],
// 					"alias_pagina"	=>	$aliasPagina,
// 					"alias_pagina_codice"	=>	$aliasPagina."-".$c["codice"],
// 					"alias_pagina_attributo"=>	$aliasPagina."-".$aliasAttributi,
// 					"alias_pagina_attributo_codice"=>	$aliasPagina."-".$aliasAttributi."-".$c["codice"],
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
			$codice = str_replace("/","",$res[0]["combinazioni"]["codice"]);
			
			if ($agiungiAlias && v("usa_alias_combinazione_in_url_prodotto") && $aliasAttributi)
				$alias .= "-".$aliasAttributi;
			
			if (v("usa_codice_combinazione_in_url_prodotto") && $codice)
				$alias .= "-".strtolower($codice);
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
			->addWhereAttivo()
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
				"id_page"		=>	(int)$idPage,
				"acquistabile"	=>	1,
			))->orderBy("canonical desc,id_order")->limit(1)->send(false);
			
			if (count($combinazione) > 0 && !$combinazione[0]["canonical"])
			{
				$this->query(array("update combinazioni set canonical = 0 where id_page = ?",array((int)$idPage)));
				
				$this->sValues(array(
					"canonical"	=>	1,
				));
				
				$this->pUpdate($combinazione[0]["id_c"]);
			}
		}
	}
	
	public function varianti($record)
	{
// 		$divisorio = v("immagine_in_varianti") ? "<br />" : " - ";
		$divisorio = "<br />";
		
		return $this->getStringa($record["combinazioni"]["id_c"], $divisorio);
	}
	
	public function prodotto($record)
	{
		if (!isset($_GET["id_page"]) || $_GET["id_page"] == "tutti")
			return "<a target='_blank' href='".Url::getRoot()."prodotti/form/update/".$record["pages"]["id_page"]."'>".$record["pages"]["title"]."</a>";
		
		return $record["pages"]["title"];
	}
	
	public function marchioCrud($record)
	{
		$marchio = MarchiModel::getDataMarchio($record["pages"]["id_marchio"]);
		
		if (!empty($marchio))
			return mfield($marchio, "titolo");
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
	
	public function attivoCrud($record)
	{
		if (!isset($_GET["esporta"]) && !self::isFromLista())
		{
			if ($record["pages"]["attivo"] == "Y")
				return "<i class='fa fa-check text text-success'></i>";
			else
				return "<i class='fa fa-ban text text-danger'></i>";
		}
		else
			return $record["pages"]["attivo"] == "Y" ? gtext("Sì") : gtext("No");
	}
	
	public function visibileCrud($record)
	{
		$checked = $record["combinazioni"]["acquistabile"] ? "checked" : "";
		
		if (!isset($_GET["esporta"]) && !self::isFromLista())
			return "<input $checked type='checkbox' id-page='".$record["combinazioni"]["id_page"]."' id-c='".$record["combinazioni"]["id_c"]."' style='max-width:120px;' name='acquistabile' value='".$record["combinazioni"]["acquistabile"]."' />";
		else
			return $record["combinazioni"]["acquistabile"];
	}
	
	public function codiceView($record)
	{
		return gtext("SKU").": <b>".$record["combinazioni"]["codice"]."</b><br />\n".gtext("GTIN").": ".$record["combinazioni"]["gtin"]."<br />\n".gtext("MPN").": ".$record["combinazioni"]["mpn"];
	}
	
	public function codice($record)
	{
		if (!isset($_GET["esporta"]) && !self::isFromLista())
		{
			$html = "<div style='min-width:180px;margin-bottom:5px;'><b style='width:36px;display:inline-block;'>".gtext("SKU").":</b> <input id-page='".$record["combinazioni"]["id_page"]."' id-c='".$record["combinazioni"]["id_c"]."' style='max-width:140px;display:inline;' class='form-control class_combinazione class_combinazione_".$record["combinazioni"]["id_c"]."' name='codice' value='".$record["combinazioni"]["codice"]."' /></div>";
			
			$html .= "<div style='min-width:180px;margin-bottom:5px;'><b style='width:36px;display:inline-block;'>".gtext("GTIN").":</b> <input id-page='".$record["combinazioni"]["id_page"]."' id-c='".$record["combinazioni"]["id_c"]."' style='max-width:140px;display:inline;' class='form-control' name='gtin' value='".$record["combinazioni"]["gtin"]."' /></div>";
			
			$html .= "<div style='min-width:180px;'><b style='width:36px;display:inline-block;'>".gtext("MPN").":</b> <input id-page='".$record["combinazioni"]["id_page"]."' id-c='".$record["combinazioni"]["id_c"]."' style='max-width:140px;display:inline;' class='form-control' name='mpn' value='".$record["combinazioni"]["mpn"]."' /></div>";
			
			return $html;
		}
		else
			return $this->codiceView($record);
	}
	
	public function prezzo($record, $fieldName = "price")
	{
		$disabled = "";
		
		if (!isset($_GET["listino"]) || $_GET["listino"] == "tutti")
		{
			if (v("prezzi_ivati_in_prodotti"))
				$prezzo = $record["combinazioni"][$fieldName."_ivato"];
			else
				$prezzo = $record["combinazioni"][$fieldName];
			
			$attrIdCl = "id-cl='0'";
		}
		else
		{
			$cl = new CombinazionilistiniModel();
			list($idCl, $prezzo, $prezzoScontato) = $cl->getPrezzoListino($record["combinazioni"]["id_c"], $_GET["listino"]);
			
			if ($fieldName == "price_scontato")
				$prezzo = $prezzoScontato;
			
			$attrIdCl = "id-cl='$idCl'";
		}
		
		if ($fieldName == "price_scontato" && isset($record["pages"]["in_promozione"]) && $record["pages"]["in_promozione"] == "N")
			$disabled = "disabled";
		
		if (isset($prezzo))
		{
			$cifre = v("prezzi_ivati_in_prodotti") ? 2 : v("cifre_decimali_visualizzate");
			
			$prezzo = number_format($prezzo, $cifre, ",", "");
			
			if (!isset($_GET["esporta"]) && !self::isFromLista())
				return "<input $disabled id-c='".$record["combinazioni"]["id_c"]."' $attrIdCl style='max-width:120px;' class='form-control' name='$fieldName' value='".$prezzo."' />";
			else
				return $prezzo;
		}
		else
			return "!!";
	}
	
	public function prezzoScontato($record)
	{
		return $this->prezzo($record, "price_scontato");
	}
	
	protected static function isFromLista()
	{
		if ((isset($_GET["id_lista_regalo"]) && $_GET["id_lista_regalo"] != "tutti") || (isset($_GET["id_ordine"]) && $_GET["id_ordine"] != "tutti"))
			return true;
		
		return false;
	}
	
	public function peso($record)
	{
		$peso = number_format($record["combinazioni"]["peso"],2,",","");
		
		if (!isset($_GET["esporta"]) && !self::isFromLista())
			return "<input id-c='".$record["combinazioni"]["id_c"]."' style='max-width:120px;' class='form-control' name='peso' value='".$peso."' />";
		else
			return $peso;
	}
	
	public function giacenza($record)
	{
		if (!isset($_GET["esporta"]) && !self::isFromLista())
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
			$html .= "<img style='margin-right:5px;' src='".Url::getRoot()."thumb/immagineinlistaprodotti/0/".$imm["immagine"]."' />";
		}
		
		if (count($immagini) > 0)
			$html .= "<br />";
		
		$html .= "<a class='iframe label label-primary' href='".Url::getRoot()."prodotti/immagini/".$record["combinazioni"]["id_page"]."?partial=Y&nobuttons=Y&id_cmb=".$record["combinazioni"]["id_c"]."'><small>".gtext("Gestisci")." <i class='fa fa-pencil'></i></small></a>"; 
		
		return $html;
	}
	
	public function acquistabileCrudText($record)
	{
		if ($record["combinazioni"]["acquistabile"])
			return "<a class='ajlink text text-success' title='".gtext("Rendi il prodotto NON acquistabile")."' href='".Url::getRoot()."combinazioni/modificaacquistabile/".$record["combinazioni"]["id_c"]."/0'><i class='fa fa-check'></i></a>";
		else
			return "<a class='ajlink text text-danger' title='".gtext("Rendi il prodotto acquistabile")."' href='".Url::getRoot()."combinazioni/modificaacquistabile/".$record["combinazioni"]["id_c"]."/1'><i class='fa fa-ban'></i></a>";
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
			$this->query(array("update combinazioni set canonical = 0 where id_page = ?",array((int)$combinazione["id_page"])));
			
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
		
		$this->controlliDopoCreazioneCombinazione((int)$toId, false);
	}
	
	public function bulkaggiungialistaregalo($record)
    {
		return "<i data-azione='aggiungialistaregalo' title='".gtext("Aggiungi alla lista regalo")."' class='bulk_trigger help_trigger_aggiungi_a_lista_regalo fa fa-plus-circle text text-primary'></i>";
    }
    
    public function bulkaggiungiaordine($record)
    {
		return "<i data-azione='aggiungiaordine' title='".gtext("Aggiungi ad ordine")."' class='bulk_trigger help_trigger_aggiungi_a_ordine fa fa-plus-circle text text-primary'></i>";
    }
    
    public function aggiungialistaregalo($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && self::isFromLista())
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
    
    // $idOrdine: ID dell'ordine a cui attaccare la riga
    // $values: se non vuoto, prende i valori da quell'array
    // $colonneAggiuntive = array() : inserisce i valori di quelle colonne
    public function aggiungiaordine($id, $idOrdine = 0, $values = array(), $colonneAggiuntive = array())
    {
		if( !session_id() )
			session_start();
		
		// Imposta le colonne aggiuntive che non devono essere sovrascritte dal passaggio al carrello
		if (!empty($colonneAggiuntive))
			OrdiniModel::$colonneAggiuntiveRighe = array_keys($colonneAggiuntive);
		
		Params::$setValuesConditionsFromDbTableStruct = false;
		Params::$automaticConversionToDbFormat = false;
		
		$record = $this->selectId((int)$id);
		
		if ($idOrdine)
			$_GET["id_ordine"] = (int)$idOrdine;
		
		if (!empty($record) && self::isFromLista() && isset($_GET["id_ordine"]))
		{
			$ordine = OrdiniModel::g()->selectId((int)$_GET["id_ordine"]);
			
			$prezzoIvato = $prezzoNonIvato = null;
			
			if (!empty($ordine))
			{
				$lingua = $ordine["lingua"] ? $ordine["lingua"] : LingueModel::getPrincipaleFrontend();
				
				$pagina = PagesModel::g(false)->addJoinTraduzionePagina($lingua)->where(array(
					"id_page"	=>	(int)$record["id_page"],
				))->first();
				
				if (!empty($pagina))
				{
					$iva = IvaModel::g()->getValore((int)$pagina["pages"]["id_iva"]);
					
					// Ricalcolo i prezzi
					if (!$pagina["pages"]["prodotto_generico"] && $ordine["id_iva_estera"] && !isset($_GET["id_riga_tipologia"]))
						$record = ProdottiModel::ricalcolaPrezziSuNuovaIva($record, $iva, $ordine["aliquota_iva_estera"]);
					
					// Righe accessorie
					if (isset($_GET["id_riga_tipologia"]))
					{
						$rt = new RighetipologieModel();
						
						$rigaTipologia = $rt->clear()->selectId((int)$_GET["id_riga_tipologia"]);
						
						if (!empty($rigaTipologia))
						{
							$record["codice"] = $rigaTipologia["titolo_breve"];
							$record["peso"] = 0;
							
							if ($rigaTipologia["prezzo"] > 0)
							{
								$prezzoIvato = $rigaTipologia["prezzo"] * (int)$rigaTipologia["moltiplicatore"];
								
								$prezzoNonIvato = $prezzoIvato / (1 + ($iva / 100));
							}
							
							if (!RighetipologieModel::checkInserimentoTipologiaInOrdine((int)$_GET["id_ordine"], (int)$_GET["id_riga_tipologia"]))
								return;
						}
					}
					
					$title = isset($rigaTipologia["titolo"]) ? strtoupper($rigaTipologia["titolo"]) : field($pagina, "title");
					
					if (isset($values["titolo"]))
						$title = $values["titolo"];
					
					if (isset($values["prezzo"]))
					{
						$prezzoIvato = $values["prezzo"];
						$prezzoNonIvato = $prezzoIvato / (1 + ($iva / 100));
					}
					
					$r = new RigheModel();
					
					$r->sValues(array(
						"id_o"		=>	(int)$_GET["id_ordine"],
						"cart_uid"	=>	$ordine["cart_uid"],
						"creation_time"	=>	time(),
						"id_page"	=>	$pagina["pages"]["id_page"],
						"id_c"		=>	(int)$id,
						"title"		=>	$title,
						"immagine"	=>	ProdottiModel::immagineCarrello($pagina["pages"]["id_page"], (int)$id),
						"quantity"	=>	isset($values["quantity"]) ? (int)$values["quantity"] : 1,
						"codice"	=>	$record["codice"],
						"peso"		=>	$record["peso"],
						"attributi"	=>	$this->getStringa((int)$id),
						"id_iva"	=>	$pagina["pages"]["id_iva"],
						"iva"		=>	$iva,
						"gift_card"	=>	$pagina["pages"]["gift_card"],
						"prezzo_intero"	=>	$prezzoNonIvato ?? $record["price"],
						"prezzo_intero_ivato"	=> $prezzoIvato ?? $record["price_ivato"],
						"price"		=>	$prezzoNonIvato ?? $record["price_scontato"],
						"price_ivato"	=>	$prezzoIvato ?? $record["price_scontato_ivato"],
						"prezzo_finale"		=>	$prezzoNonIvato ?? $record["price_scontato"],
						"prezzo_finale_ivato"	=>	$prezzoIvato ?? $record["price_scontato_ivato"],
						"in_promozione"	=>	number_format($record["price_scontato"],2,".","") != number_format($record["price"],2,".","") ? "Y" : "N",
						"percentuale_promozione"	=>	self::calcolaSconto($record["price"], $record["price_scontato"]),
						"lingua"	=>	$lingua,
						"json_personalizzazioni"=>	"[]",
						"json_attributi"=>	$this->getStringa((int)$id,"",true),
						"json_sconti"=>	"[]",
						"fonte"		=>	"B",
						"id_admin"	=>	User::$id,
						"disponibile"	=>	($record["giacenza"] > 0) ? 1 : 0,
						"id_riga_tipologia"	=>	$rigaTipologia["id_riga_tipologia"] ?? 0,
						"prodotto_generico"	=>	$pagina["pages"]["prodotto_generico"],
						"acconto"	=>	$rigaTipologia["acconto"] ?? 0,
						"evasa"		=>	(isset($rigaTipologia["id_riga_tipologia"]) && $rigaTipologia["id_riga_tipologia"]) ? 1 : 0,
					), "sanitizeDb");
					
					foreach ($colonneAggiuntive as $cK => $cV)
					{
						$r->setValue($cK, $cV, "sanitizeDb");
					}
					
					if ($r->insert())
						$_SESSION["aggiorna_totali_ordine"] = true;
				}
			}
		}
		
		Params::$setValuesConditionsFromDbTableStruct = true;
		Params::$automaticConversionToDbFormat = true;
    }
    
    public static function calcolaSconto($prezzoIntero, $prezzoScontato)
    {
		return $prezzoIntero > 0 ? (($prezzoIntero - $prezzoScontato) / $prezzoIntero * 100) : 0;
    }
	
	// $idC: id combinazione
	// $qty: quantità movimentata
	// $idR: id riga
	// $resetta: se 1, imposta la giacenza manualmente, se 0 è un carico o uno scarico
	public function movimenta($idC, $qty, $idR = 0, $resetta = 0)
	{
		if (v("usa_transactions"))
		{
			$this->db->beginTransaction();
			$combinazione = $this->clear()->whereId((int)$idC)->forUpdate()->record();
		}
		else
			$combinazione = $this->selectId((int)$idC);
		
		if (!empty($combinazione) && !ProdottiModel::isGiftCart($combinazione["id_page"]))
		{
			$valoreFinale = ((int)$combinazione["giacenza"] - (int)$qty);
			
			$this->setValues(array(
				"giacenza"	=>	$valoreFinale,
			));
			
			if ($resetta || $this->pUpdate($combinazione["id_c"]))
			{
				// Salvo la movimentazione
				$cmModel = new CombinazionimovimentiModel();
				
				$ordine = RigheModel::g()->select("orders.id_o,orders.stato")->inner("orders")->on("righe.id_o = orders.id_o")->where(array(
					"id_r"	=>	(int)$idR,
				))->first();
				
				$cmModel->sValues(array(
					"titolo"	=>	$qty > 0 ? "SCARICO" : "CARICO",
					"valore"	=>	(-1) * $qty,
					"id_c"		=>	$combinazione["id_c"],
					"id_page"	=>	$combinazione["id_page"],
					"id_r"		=>	$idR,
					"id_o"		=>	!empty($ordine) ? $ordine["orders"]["id_o"] : 0,
					"stato_ordine"	=>	!empty($ordine) ? $ordine["orders"]["stato"] : "",
					"giacenza"	=>	$resetta ? $combinazione["giacenza"] : $valoreFinale,
					"resetta"	=>	$resetta,
				));
				
				$cmModel->insert();
			}
			
			// Aggiorno la combinazione della pagina
			$this->aggiornaGiacenzaPagina($combinazione["id_c"]);
		}
		
		if (v("usa_transactions"))
			$this->db->commit();
	}
	
	public static function campiPrezzo()
	{
		$campoPrice = "price";
		$campoPriceScontato = "price_scontato";
		
		if (v("prezzi_ivati_in_prodotti"))
		{
			$campoPrice = "price_ivato";
			$campoPriceScontato = "price_scontato_ivato";
		}
		
		return array($campoPrice, $campoPriceScontato);
	}
	
	public function numeroRegalati($record)
	{
		if (isset($_GET["id_lista_reg_filt"]))
		{
			return ListeregaloModel::g()->numeroRegalati((int)$_GET["id_lista_reg_filt"], $record["combinazioni"]["id_c"]);
		}
		
		return "";
	}
	
	public function numeroRimastiDaRegalare($record)
	{
		if (isset($_GET["id_lista_reg_filt"]))
		{
			return ListeregaloModel::g()->numeroRimastiDaRegalare((int)$_GET["id_lista_reg_filt"], $record["combinazioni"]["id_c"]);
		}
		
		return "";
	}
	
	public function idCombCrud($record)
	{
		$html = $record["combinazioni"]["id_c"];
		
		$html .= "<input type='hidden' name='id_c' value='".$record["combinazioni"]["id_c"]."' />";
		
		return $html;
	}
	
	public function selectValoreAttributoCrud($idC, $col)
	{
		$record = $this->selectId((int)$idC);
		
		if (empty($record))
			return "";
		
		$av = new AttributivaloriModel();
		$pa = new PagesattributiModel();
		
		$idA = $pa->clear()->where(array(
			"id_page"	=>	(int)$record["id_page"],
			"colonna"	=>	sanitizeDb($col),
		))->field("id_a");
		
		$idAv = $record[$col];
		
		if ($idA)
		{
			$select = array("0"	=>	"--") + $av->selectPerFiltro($idA, "id_order");
			return Html_Form::select("id_av",$idAv, $select, "valore_attributo_combinazione valore_attributo_combinazione_$idC form-control", null, "yes");
		}
	}
	
	public function selectValoreAttributoCrudcol_1($idC)
	{
		return $this->selectValoreAttributoCrud($idC, "col_1");
	}
	
	public function selectValoreAttributoCrudcol_2($idC)
	{
		return $this->selectValoreAttributoCrud($idC, "col_2");
	}
	
	public function selectValoreAttributoCrudcol_3($idC)
	{
		return $this->selectValoreAttributoCrud($idC, "col_3");
	}
	
	public function selectValoreAttributoCrudcol_4($idC)
	{
		return $this->selectValoreAttributoCrud($idC, "col_4");
	}
	
	public function selectValoreAttributoCrudcol_5($idC)
	{
		return $this->selectValoreAttributoCrud($idC, "col_5");
	}
	
	public function selectValoreAttributoCrudcol_6($idC)
	{
		return $this->selectValoreAttributoCrud($idC, "col_6");
	}
	
	public function selectValoreAttributoCrudcol_7($idC)
	{
		return $this->selectValoreAttributoCrud($idC, "col_7");
	}
	
	public function selectValoreAttributoCrudcol_8($idC)
	{
		return $this->selectValoreAttributoCrud($idC, "col_8");
	}
	
	public function linkMovimentiCrud($record)
	{
		$cmModel = new CombinazionimovimentiModel();
		
		if ($cmModel->clear()->where(array(
			"id_c"	=>	(int)$record["combinazioni"]["id_c"],
		))->rowNumber())
			return "<a title='".gtext("Elenco movimentazioni prodotto")."' class='iframe' href='".Url::getRoot()."combinazionimovimenti/main?partial=Y&id_c=".(int)$record["combinazioni"]["id_c"]."'><i class='fa fa-history'></i></a>";
		
		return "";
	}
	
	public function linkListeRegaloCrud($record)
	{
		$idC = (int)$record[$this->_tables]["id_c"];
		
		$lrpModel = new ListeregalopagesModel();
		
		$res = $lrpModel->clear()->select("sum(liste_regalo_pages.quantity) as SOMMA")->where(array(
			"id_c"	=>	$idC,
		))->send();
		
		$lrpModel = new ListeregalopagesModel();
		
		if (count($res) > 0 && $res[0]["aggregate"]["SOMMA"] > 0)
			return $res[0]["aggregate"]["SOMMA"]." <a title='Elenco liste dove è inserito il prodotto' class='iframe' href='".Url::getRoot()."listeregalo/main?partial=Y&id_c=".(int)$record["combinazioni"]["id_c"]."'><i class='fa fa-gift'></i></a>";
		
		return "";
	}
	
	public function deletable($idC)
	{
		if (CombinazioniModel::$permettiSempreEliminazione)
			return true;
		
		$res = $this->elementoNonUsato($idC);
		
		if (!$res)
			return false;
		
		$record = $this->clear()->select("id_page")->whereId((int)$idC)->record();
		$p = new PagesModel();
		
		if (!empty($record) && (int)$p->numeroVarianti((int)$record["id_page"]) === 0)
			return false;
		
		return true;
	}
	
	static public function acquistabile($idC)
	{
		$cModel = new CombinazioniModel();
		
		return $cModel->clear()->where(array(
			"id_c"			=>	(int)$idC,
			"acquistabile"	=>	1,
		))->rowNumber();
	}
	
	public function getTitoloCombinazione($idC, $lingua = null)
	{
		$record = $this->selectId((int)$idC);
		
		if (empty($record))
			return "";
		
		$titoli = AttributivaloriModel::getArrayIdTitolo($lingua, $idC);
		
		$arrayTitoli = array();
		
		for ($i = 1; $i < 9; $i++)
		{
			if (isset($record["col_".$i]) && $record["col_".$i] && isset($titoli[$record["col_".$i]]) && $titoli[$record["col_".$i]])
				$arrayTitoli[] = $titoli[$record["col_".$i]];
		}
		
		return count($arrayTitoli) > 0 ? implode(" ", $arrayTitoli) : "";
	}
	
	// controlla che il codice non sia già stato usato
	public static function checkCodiceUnivoco($codice, $id)
	{
		if (!v("controlla_che_il_codice_prodotti_sia_unico"))
			return true;
		
		$c = new CombinazioniModel();
		
		$c->clear()->where(array(
			"codice"	=>	sanitizeAll($codice),
		));
		
		if ($id)
			$c->aWhere(array(
				"ne"	=>	array(
					"id_page"	=>	(int)$id,
				),
			));
		
		return !$c->rowNumber();
	}
	
	public function titoloJson($id)
	{
		$clean["id"] = (int)$id;
		
		$record = $this->clear()->select("pages.attivo,combinazioni.*")->inner(array("pagina"))->whereId($clean["id"])->first();
		
		if (!empty($record))
		{
			$stringa = strip_tags($this->getStringa($clean["id"], ","));
			$stringa = $stringa ? $stringa : gtext("Variante: --");
			$stringa .= " - ".htmlentitydecode($record["combinazioni"]["codice"]);
			
			if ($record["pages"]["attivo"] == "N" || !$record["combinazioni"]["acquistabile"])
				$stringa .= " (NON ACQUISTABILE)";
				
			return $stringa;
		}
		
		return "";
	}
}
