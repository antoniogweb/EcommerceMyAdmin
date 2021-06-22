<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
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

class PagesModel extends GenericModel {

	public $lId = 0;
	public $titleFieldName = "title";
	public $aliaseFieldName = "alias";
	
	public $hModelName = "CategoriesModel";
	public $hModel = null; //hierarchical model

	public $checkAll = true;
	
	public static $currentRecord = null;
	
	public static $arrayImmagini = null;
	
	public static $pagineConFeedback = null;
	
	public static $tipiPagina = array(
		"GRAZIE"		=>	"Pagina grazie",
		"GRAZIE_NEWSLETTER"	=>	"Pagina grazie iscrizione a newsletter",
		"COOKIE"		=>	"Pagina cookie",
		"CONDIZIONI"	=>	"Condizioni Generali Di Vendita",
		"ACCOUNT_ELIMINATO"	=>	"Account eliminato",
		"PRIVACY"		=>	"Pagina privacy",
		"AZIENDA"		=>	"Pagina azienda",
		"CONTATTI"		=>	"Pagina contatti",
		"RESI"			=>	"Pagina resi",
		"FAQ"			=>	"Pagina FAQ",
		"INFO_LEGALI"	=>	"Pagina informazioni Legali",
		"SPEDIZIONI"	=>	"Pagina info spedizioni",
		"FILOSOFIA"		=>	"Pagina filosofia",
		"B2B"			=>	"Pagina info B2B",
		"HOME"			=>	"Home page",
	);
	
	public function __construct() {
		$this->_tables='pages';
		$this->_idFields='id_page';
		
		$this->hModel = new $this->hModelName();
		
		$this->_where = array(
			"id_c"			=>	"categories",
			"-id_marchio"	=>	"marchi",
		);
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'pages.id_order';
		$this->_lang = 'It';
		
		$this->addValuesCondition("both",'checkIsStrings|Y,N',"attivo,in_evidenza,in_promozione");
		
		$this->addStrongCondition("both",'checkNotEmpty',"title");
		
		$this->salvaDataModifica = true;
		
// 		$this->addStrongCondition("both",'checkNotEmpty',"codice|Si prega di inserire il codice del prodotto");
		
// 		$inProm = isset($_POST["in_promozione"]) ? $_POST["in_promozione"] : "N";
		
// 		$this->addStrongCondition("both",'checkMatch|/^[0-9]{1,8}(\,[0-9]{1,2})?$/',"price|Si prega di ricontrollare il campo <b>Prezzo</b>");
// 		$this->addStrongCondition("both",'checkMatch|/^[0-9]{1,8}(\,[0-9]{1,2})?$/',"peso|Si prega di ricontrollare il campo <b>Peso</b>");
		
// 		if (strcmp($inProm,"Y") === 0)
// 		{
// 			$this->addStrongCondition("both",'checkMatch|/^[0-9]{1,8}(\,[0-9]{1,2})?$/',"prezzo_promozione|Si prega di ricontrollare il campo <b>Prezzo in promozione</b>");
// 		}
		
// 		$this->addDatabaseCondition("both",'checkUnique',"codice");
		
// 		$this->databaseConditions['insert'] = array(
// 			'checkUnique'=>'codice|Il valore del campo codice è già stato usato per un altro prodotto, si prega di sceglierne un altro',
// 		);
// 		
// 		$this->databaseConditions['update'] = array(
// 			'checkUniqueCompl'=>'codice|Il valore del campo codice è già stato usato per un altro prodotto, si prega di sceglierne un altro',
// 		);
		
		$this->uploadFields = array();
		
		parent::__construct();

	}
	
	public function relations() {
		return array(
			'feedback' => array("HAS_MANY", 'FeedbackModel', 'id_page', null, "RESTRICT", "L'elemento ha dei feedback collegati e non può essere eliminato"),
			'traduzioni' => array("HAS_MANY", 'ContenutitradottiModel', 'id_page', null, "CASCADE"),
			'contenuti' => array("HAS_MANY", 'ContenutiModel', 'id_page', null, "CASCADE"),
			'documenti' => array("HAS_MANY", 'DocumentiModel', 'id_page', null, "CASCADE"),
			'personalizzazioni' => array("HAS_MANY", 'PagespersonalizzazioniModel', 'id_page', null, "CASCADE"),
			'combinazioni' => array("HAS_MANY", 'CombinazioniModel', 'id_page', null, "CASCADE"),
			'caratteristiche' => array("HAS_MANY", 'PagescarvalModel', 'id_page', null, "CASCADE"),
			'tag' => array("HAS_MANY", 'PagestagModel', 'id_page', null, "CASCADE"),
			'link' => array("HAS_MANY", 'PageslinkModel', 'id_page', null, "CASCADE"),
			'marchio' => array("BELONGS_TO", 'MarchiModel', 'id_marchio',null,"CASCADE"),
        );
    }
    
	public function setFilters()
	{
		$this->_popupItemNames = array(
			'attivo'	=>	'attivo',
			'id_c'	=>	'id_c',
			'in_evidenza'	=>	'in_evidenza',
// 			'in_promozione'	=>	'in_promozione',
		);
		
		$this->_popupLabels = array(
			'attivo'	=>	'PUBBLICATO?',
			'id_c'	=>	'CATEGORIA',
			'in_evidenza'	=>	'IN EVIDENZA?',
// 			'in_promozione'	=>	'IN PROMOZIONE?',
		);

		$this->_popupFunctions = array(
			'attivo'=>	'getYesNo',
			'id_c'	=>	'getCatNameForFilters',
			'in_evidenza'	=>	'getYesNo',
// 			'in_promozione'	=>	'getYesNo',
		);
		
		$this->_popupOrderBy = array(
			'id_c'	=>	'lft asc',
		);
		
		if (isset($this->hModel->section))
		{
			$this->_popupWhere = array(
				'id_c'	=>	$this->hModel->getChildrenFilterWhere(),
			);
		}
	}
	
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'title'		=>	array(
					'labelString'=>	'Titolo',
				),
				'data_news'		=>	array(
					'labelString'=>	'Data',
				),
				'coordinate'		=>	array(
					'labelString'=>	'Coordinate (latitudine e longitudine divise da virgola)',
				),
				'alias'		=>	array(
					'labelString'=>	'Alias (per URL)',
				),
				'id_c'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Categoria',
					'options'	=>	$this->buildCategorySelect(),
					'reverse' => 'yes',
					
				),
				'id_iva'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Aliquota Iva',
					'options'	=>	$this->selectIva(),
					'reverse' => 'yes',
					
				),
				'id_marchio'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	gtext('famiglia',false,"ucfirst"),
					'options'	=>	$this->selectMarchi(),
					'reverse' => 'yes',
					
				),
				'price'		=>	array(
					'labelString'=>	'Prezzo Iva esclusa (€)',
				),
				'price_ivato'	=>	array(
					'labelString'=>	'Prezzo Iva inclusa (€)',
				),
				'codice'		=>	array(
					'labelString'=>	'Codice prodotto',
				),
				'attivo'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Pubblicato?',
					'options'	=>	array('sì'=>'Y','no'=>'N'),
					
				),
				'in_evidenza'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'In evidenza?',
					'options'	=>	array('no'=>'N','sì'=>'Y'),
				),
				'peso'		=>	array(
					'labelString'=>	'Peso (kg)',
				),
				'in_promozione'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'In promozione?',
					'options'	=>	array('no'=>'N','sì'=>'Y'),
					'className'	=>	'in_promozione form-control',
					'entryClass'	=>	'form_input_text form_input_text_promozione',
				),
				'prezzo_promozione'		=>	array(
					'labelString'=>	'Sconto (in %)',
					'entryClass'	=>	'class_promozione form_input_text',
					'className'	=>	'input_corto form-control',
				),
				'dal'		=>	array(
					'labelString'=>	'In promozione dal',
					'className'	=>	'data_field input_corto form-control',
					'entryClass'	=>	'class_promozione form_input_text',
				),
				'al'		=>	array(
					'labelString'=>	'In promozione fino al',
					'className'	=>	'data_field input_corto form-control',
					'entryClass'	=>	'class_promozione form_input_text',
				),
				'description'		=>	array(
					'type'		 =>	'Textarea',
					'labelString'=>	'Descrizione',
					'className'		=>	'dettagli',
				),
				'descrizione_breve'		=>	array(
					'type'		 =>	'Textarea',
					'labelString'=>	'Descrizione breve',
					'className'		=>	'dettagli',
				),
				'dettagli'		=>	array(
					'type'		 =>	'Textarea',
					'labelString'=>	'Dettagli',
				),
				'id_page'	=>	array(
					'type'		=>	'Hidden'
				),
				'use_editor'	=>	array(
					'labelString'=>	'Editor visuale',
				),
				'immagine'	=>	array(
					'type'		=>	'Hidden'
				),
				'immagine_2'	=>	array(
					'type'		=>	'Hidden'
				),
				'immagine_fondo'	=>	array(
					'type'		=>	'Hidden'
				),
				'codice_nazione'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Nazione',
					'options'	=>	$this->selectNazione(),
					'reverse' => 'yes',
				),
				'giacenza'	=>	array(
					'labelString'=>	'Giacenza (disponibilità a magazzino)',
				),
				'tipo_pagina'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Tipo pagina',
					'options'	=>	$this->selectTipiPagina(),
					'reverse' => 'yes',
				),
				'acquisto_diretto'		=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>Se settato su no, non è permesso l'acquisto diretto, ma il prodotto è comunque visibile nel frontend.<br />Se predisposto, fa comparire un form di richiesta informazioni.</div>"
					),
				),
				'acquistabile'		=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>Se settato su no, il prodotto non è acquistabile e viene nascosto nel frontend.<br />Può solo essere aggiunto come accessorio.</div>"
					),
				),
				'aggiungi_sempre_come_accessorio'		=>	array(
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>Se settato su sì, il prodotto viene sempre aggiunto come accessorio alla creazione di qualsiasi nuovo prodotto.</div>"
					),
				),
				'id_p'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Prodotto principale',
					'options'	=>	$this->selectProdotti($id),
					'reverse' => 'yes',
					
				),
				'priorita_sitemap'	=>	array(
					'labelString'=>	'Priorità sitemap',
				),
				'allineamento'	=>	array(
					'type'		=>	'Select',
					'options'	=>	array('LX'=>'Sinistra','DX'=>'Destra','CE'=>'Centro'),
					'reverse' => 'yes',
					
				),
				'ora_inizio_evento'	=>	array(
					"className"		=>	"form-control clockpicker",
				),
				'ora_fine_evento'	=>	array(
					"className"		=>	"form-control clockpicker",
				),
			),
		);
		
		$this->formStruct["entries"] = $this->formStruct["entries"] + $this->getLinkEntries();
		
		if ($this->formStructAggiuntivoEntries)
			$this->formStruct["entries"] = $this->formStruct["entries"] + $this->formStructAggiuntivoEntries;
	}
	
	public function selectProdotti($id)
	{
		$clean['id'] = (int)$id;
		
		$cm = new CategoriesModel();
		
		$children = $cm->children((int)$cm->getShopCategoryId(), true);
		
		$res = $this->clear()->where(array(
			"ne" => array("id_page" => $clean['id']),
			"attivo" => "Y",
			"principale"=>"Y",
			"acquistabile"	=>	"Y",
			"in" => array("-id_c" => $children),
		))->orderBy("id_order")->toList("id_page", "title")->send();
		
		return array(0=>"--") + $res;
	}
	
	public function selectTipiPagina()
	{
		return array(""=>"--") + self::$tipiPagina;
	}
	
	public function selectIva()
	{
		$iva = new IvaModel();
		
		return $iva->clear()->orderBy("id_order")->toList("id_iva","titolo")->send();
	}
	
	public function getIva($idPage)
	{
		$iva = $this->clear()->select("iva.valore")->where(array("id_page"=>(int)$idPage))->inner("iva")->on("pages.id_iva = iva.id_iva")->send();
		
		if (count($iva) > 0)
			return $iva[0]["iva"]["valore"];
		
		return 0;
	}
	
	//controlla che la pagina $id possa essere gestita dal model
	public function modificaPaginaPermessa($id)
	{
		$clean["id"] = (int)$id;
		
		if (isset($this->hModel) and isset($this->hModel->section))
		{
			//ottengo la section legata a questo model
			$section = $this->hModel->section;
			
			$page = $this->clear()->selectId($clean["id"]);
			
			if (count($page) > 0)
			{
				$pages = $this->clear()->where(array("codice_alfa" => $page["codice_alfa"]))->toList("id_page")->send();
				
				if (strcmp($section,$this->hModel->rootSectionName) !== 0)
				{
					foreach ($pages as $id_p)
					{
						//ottengo i genitori
						$parents = $this->parents((int)$id_p, false, true);
						
						//tolgo il genitore root
						array_shift($parents);
						
						foreach ($parents as $par)
						{
							if (strcmp($par["categories"]["section"],$section) === 0)
							{
								return true;
							}
						}
					}
				}
				else
				{
					foreach ($pages as $id_p)
					{
						//ottengo i genitori
						$parents = $this->parents((int)$id_p, false, true);
						
						//tolgo il genitore root
						array_shift($parents);
						
						foreach ($parents as $par)
						{
							if (strcmp($par["categories"]["section"],"") !== 0)
							{
								return false;
							}
						}
					}
				
					return true;
				}
			}
			
			return false;
		}
		
		return true;
	}
	
	public function pUpdate($id = null, $where = null)
	{
		$clean["id"] = (int)$id;
		
		return parent::update($clean["id"], $where);
	}
	
	public function buildCategorySelect()
	{
		return $this->hModel->buildSelect();
	}
	
	public function aggiornaStatoProdottiInPromozione()
	{
		$res = $this->clear()->where(array("in_promozione"=>"Y"))->sWhere("al < '".date("Y-m-d")."'")->send();
		
		foreach ($res as $r)
		{
			if (!$this->inPromozione($r["pages"]["id_page"],$r))
			{
				$this->values = array(
					"in_promozione"	=>	"N",
// 					"prezzo_promozione"	=>	$r["pages"]["price"],
				);
				
				$this->sanitize();
				$this->pUpdate($r["pages"]["id_page"]);
			}
		}
	}
	
	// Imposta l'alias della pagina controllando che non ci sia un duplicato
	public function setAlias($id)
	{
		if (isset($this->values[$this->aliaseFieldName]) && strcmp($this->values[$this->aliaseFieldName],"") === 0)
			$this->values[$this->aliaseFieldName] = sanitizeDb(encodeUrl($this->values[$this->titleFieldName]));
		
		$this->checkAliasAll($id);
	}
	
	public function update($id = null, $where = null)
	{
		$clean["id"] = (int)$id;
		
		$r = false;
		
		if ($this->upload("update"))
		{
			$record = $this->selectId($clean["id"]);
			
			if (count($record) > 0)
			{
				if ($this->checkAll)
				{
					$this->setAlias($id);
					
					//controllo che non esista già una pagina secondaria con la stessa categoria
					$secondaria = $this->clear()->where(array("codice_alfa" => $record["codice_alfa"],"-id_c"=>$this->values["id_c"]))->record();
					$secondarie = $this->clear()->where(array("codice_alfa" => $record["codice_alfa"]))->toList("id_page")->send();
				}
				
				// Salva informazioni meta della pagina
				$this->salvaMeta($record["meta_modificato"]);
				
				// Imposta il prezzo non ivato
				$this->setPriceNonIvato();
				
				$r = parent::update($clean["id"]);
				
				//aggiorno i permessi della pagina
				if ($r)
				{
					$this->updatePageAccessibility($clean["id"]);
				}
				
				if ($this->checkAll)
				{
					//e in caso la cancello
					if ($r and count($secondaria) > 0)
					{
						$this->values = array(
							"id_c" => $record["id_c"],
						);
						$this->pUpdate($secondaria["id_page"]);
						
						$this->updatePageAccessibility($secondaria["id_page"]);
						
						foreach ($secondarie as $id_sec)
						{
							$this->updatePageAccessibility($id_sec);
						}
					}
				}
				
				if ($r)
				{
					// Controllo che esista il contenuto in lingua
					$this->controllaLingua($id);
					
					// Controllo che esista la combinazione
					$this->controllaCombinazioni($id);
					
					$this->sincronizza($clean["id"]);
				}
			}
		}
		
		return $r;
	}
	
	public function controllaCombinazioni($id)
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
		Params::$automaticConversionToDbFormat = false;
		
		if (!$this->isProdotto($id))
			return;
		
		$c = new CombinazioniModel();
		$pa = new PagesattributiModel();
		
		$numeroVarianti = $pa->clear()->where(array(
			"id_page"	=>	(int)$id,
		))->rowNumber();
		
		if ((int)$numeroVarianti === 0)
		{
			$pagina = $this->selectId($id);
			
			if (!empty($pagina))
			{
				$combinazione = $c->clear()->where(array(
					"id_page"	=>	(int)$id
				))->record();
				
				$c->setValues(array(
					"id_page"	=>	$id,
					"price"		=>	$pagina["price"],
					"price_ivato"	=>	$pagina["price_ivato"],
					"codice"	=>	$pagina["codice"],
					"peso"		=>	$pagina["peso"],
					"giacenza"	=>	$pagina["giacenza"],
					"immagine"	=>	getFirstImage($id),
				));
				
				if (empty($combinazione))
					$c->insert();
				else
					$c->update($combinazione["id_c"]);
			}
		}
		
		Params::$setValuesConditionsFromDbTableStruct = true;
		Params::$automaticConversionToDbFormat = true;
	}
	
	// Controllo che la lingua esista
	public function controllaLingua($id)
	{
		$sezione = $this->section((int)$id, true)."_detail";
		
		$this->controllaLinguaGeneric($id, "id_page", $sezione);
	}
	
	public function updatePageAccessibility($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$gruppi = $this->accessibility($id_page);
		
		$access = count($gruppi) > 0 ? "(".implode("),(",$this->accessibility($id_page)).")" : "--free--";
		
		$this->values = array("gruppi" => $access);
		$this->sanitize();
		$this->pUpdate((int)$id_page);
	}
	
	public function pInsert()
	{
		parent::insert();
		
		$this->lId = $this->lastId();
	}
	
	public function setPriceNonIvato()
	{
		if (v("prezzi_ivati_in_prodotti") && isset($this->values["price_ivato"]) && isset($this->values["id_iva"]))
		{
			$i = new IvaModel();
			$aliquota = $i->selectId($this->values["id_iva"]);
			
			if (!empty($aliquota))
				$this->values["price"] = number_format(setPrice($this->values["price_ivato"]) / (1 + ($aliquota["valore"] / 100)), v("cifre_decimali"),".","");
		}
	}
	
	public function insert()
	{
		$r = false;
		
		if ($this->upload("insert"))
		{
			$this->setAlias(0);
			
			if (!isset($this->values["codice_alfa"]))
			{
				$this->values["codice_alfa"] = md5(randString(22).microtime().uniqid(mt_rand(),true));
			}
			
			// Salva informazioni meta della pagina
			$this->salvaMeta();
			
			// Imposta il prezzo non ivato
			$this->setPriceNonIvato();
			
			$r = parent::insert();
			
			$this->lId = $this->lastId();
			
			//aggiorno i permessi della pagina
			if ($r)
			{
				// Controllo che esista il contenuto in lingua
				$this->controllaLingua($this->lId);
				
				// Controllo che esista la combinazione
				$this->controllaCombinazioni($this->lId);
				
				$this->updatePageAccessibility($this->lId);
				
				// Aggiungi tutti i prodotti sempre come accessori
				$this->aggiungiAccesori($this->lId);
			}
		}
		
		return $r;
	}
	
	public function getIdFromAlias($alias, $lingua = null)
	{
		$clean['alias'] = sanitizeAll($alias);
		
		$res = $this->clear()->where(array($this->aliaseFieldName=>$clean['alias']))->toList($this->_idFields)->send();
		
		if (count($res) > 0)
		{
// 			return $res[0];
			return $res;
		}
		else
		{
			// Cerco la traduzione
			$ct = new ContenutitradottiModel();
			
			$res = $ct->clear()->select("pages.id_page")->inner(array("page"))->where(array("alias"=>$clean['alias']))->toList("pages.id_page");
			
			if ($lingua)
			{
				$ct->aWhere(array(
					"lingua"	=>	sanitizeAll($lingua),
				));
			}
			
			$res = $ct->send();
			
			if (count($res) > 0)
			{
				return $res;
			}
		}
		
		return 0;
	}
	
	public function isActive($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$field = $this->clear()->selectId($clean["id_page"]);
		
		if (count($field) > 0 and $field["attivo"] === "Y")
		{
			return true;
		}
		return false;
	}
	
	public function recordExists($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$res = $this->clear()->where(array("id_page"=>$clean["id_page"]))->send();
		
		if (count($res) > 0)
		{
			return true;
		}
		return false;
	}
	
	public function isActiveAlias($alias, $lingua = null)
	{
		if (strcmp($alias,"") === 0)
		{
			return 0;
		}
		
		$clean["alias"] = sanitizeAll($alias);
		
		$res = $this->clear()->select("id_page")->where(array(
			$this->aliaseFieldName=>$clean['alias']
		));
		
		if (!User::$adminLogged)
			$this->aWhere(array(
				"attivo"=>"Y",
			));
		
		$res = $this->send();
		
		if (count($res) > 0)
		{
			return true;
		}
		else
		{
			// Cerco la traduzione
			$ct = new ContenutitradottiModel();
			
			$res = $ct->clear()->select("pages.id_page")->inner(array("page"))->where(array("alias"=>$clean['alias'],"pages.attivo"=>"Y"));
			
			if ($lingua)
			{
				$ct->aWhere(array(
					"lingua"	=>	sanitizeAll($lingua),
				));
			}
			
			$res = $ct->send();
			
			if (count($res) > 0)
			{
				return true;
			}
		}
		
		return false;
	}
	
	//restituisce titolo più alias
	public function getTitle($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$field = $this->clear()->selectId($clean["id_page"]);
		
		if (count($field) > 0)
		{
			return $field[$this->titleFieldName]." <br /><span style='font-size:10px;font-style:italic;'>(alias: ".$field[$this->aliaseFieldName].")</span><br />codice: <b>".$field["codice"]."</b><br />prezzo: <b>".setPriceReverse($field["price"])."€</b>";
		}
		return '';
	}
	
	//restituisce solo titolo
	public function getSimpleTitle($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$field = $this->clear()->selectId($clean["id_page"]);
		
		if (count($field) > 0)
		{
			return $field[$this->titleFieldName];
		}
		return '';
	}
	
	public function getAlias($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$field = $this->clear()->selectId($clean["id_page"]);
		
		if (count($field) > 0)
		{
			return $field["alias"];
		}
		return '';
	}
	
// 	//get the parents
// 	public function parents($id, $onlyIds = true, $onlyParents = true, $fields = null)
// 	{
// 		$clean["id"] = (int)$id;
// 		
// 		$res = $this->clear()->where(array($this->_idFields=>$clean["id"]))->send();
// 		
// 		if (count($res) > 0)
// 		{
// 			$clean['id_c'] = $res[0][$this->_tables]["id_c"];
// 			$c = new CategoriesModel();
// 			
// 			$parents = $c->parents($clean['id_c'],$onlyIds,false, $fields);
// 			
// 			if ($onlyParents)
// 			{
// 				return $parents;
// 			}
// 			else
// 			{
// 				if ($onlyIds)
// 				{
// 					$parents[] = $res[0][$this->_tables][$this->_idFields];
// 				}
// 				else
// 				{
// 					$parents[] = $res[0];
// 				}
// 				return $parents;
// 			}
// 		}
// 		
// 		return array();
// 	}
	
	//get the parents
	public function parents($id, $onlyIds = true, $onlyParents = true, $lingua = false)
	{
		$clean["id"] = (int)$id;
		
		$this->clear()->where(array($this->_idFields=>$clean["id"]))->send();
		
		if ($lingua)
			$this->left("contenuti_tradotti")->on("contenuti_tradotti.id_page = pages.id_page and contenuti_tradotti.lingua = '".sanitizeDb($lingua)."'")->select($this->_tables.".*,contenuti_tradotti.*");
			
		$res = $this->send();
		
		if (count($res) > 0)
		{
			$clean['id_c'] = $res[0][$this->_tables]["id_c"];
			$c = new CategoriesModel();
			
			$parents = $c->parents($clean['id_c'],$onlyIds,false, $lingua);
			
			if ($onlyParents)
			{
				return $parents;
			}
			else
			{
				if ($onlyIds)
				{
					$parents[] = $res[0][$this->_tables][$this->_idFields];
				}
				else
				{
					$parents[] = $res[0];
				}
				
				return $parents;
			}
		}
		
		return array();
	}
	
	public function getCategory($id)
	{
		$clean["id"] = (int)$id;
		
		$res = $this->clear()->where(array("id_page"=>$clean["id"]))->toList("id_c")->send();

		if (count($res) > 0)
		{
			return $res[0];
		}
		return 0;
	}
	
// 	//get the URL of a content
// 	public function getUrlAlias($id)
// 	{
// 		$clean["id"] = (int)$id;
// 
// 		$parents = $this->parents($clean["id"], false, false, "categories.alias");
// 		
// 		//remove the root node
// 		array_shift($parents);
// 		
// 		$urlArray = array();
// 		foreach ($parents as $node)
// 		{
// 			if (isset($node["categories"][$this->aliaseFieldName]))
// 			{
// 				$urlArray[] = $node["categories"][$this->aliaseFieldName];
// 			}
// 			else
// 			{
// 				$urlArray[] = $node[$this->_tables][$this->aliaseFieldName];
// 			}
// 		}
// 		
// 		$ext = Parametri::$useHtmlExtension ? ".html" : null;
// 		
// 		return implode("/",$urlArray).$ext;
// 	}
	
	//get the URL of a content
	public function getUrlAlias($id, $lingua = null)
	{
		$lingua = isset($lingua) ? $lingua : Params::$lang;
		
		$clean["id"] = (int)$id;

		$parents = $this->parents($clean["id"], false, false, $lingua);
		
		//remove the root node
		array_shift($parents);
		
		$urlArray = array();
		
		foreach ($parents as $node)
		{
			if (isset($node["categories"][$this->aliaseFieldName]))
			{
				if (isset($node["contenuti_tradotti"][$this->aliaseFieldName]) && $node["contenuti_tradotti"][$this->aliaseFieldName])
					$urlArray[] = $node["contenuti_tradotti"][$this->aliaseFieldName];
				else
					$urlArray[] = $node["categories"][$this->aliaseFieldName];
			}
			else
			{
				if (isset($node["contenuti_tradotti"][$this->aliaseFieldName]) && $node["contenuti_tradotti"][$this->aliaseFieldName])
					$urlArray[] = $node["contenuti_tradotti"][$this->aliaseFieldName];
				else
					$urlArray[] = $node[$this->_tables][$this->aliaseFieldName];
			}
		}
		
		$ext = Parametri::$useHtmlExtension ? ".html" : null;
		
		// Appendo il marchio se presente
		if (v("usa_marchi"))
		{
			$m = new MarchiModel();
			
			$res = $m->clear()->addJoinTraduzione($lingua)->inner("pages")->on("pages.id_marchio = marchi.id_marchio")->where(array(
				"pages.id_page"	=>	$this->getPrincipale((int)$id),
			))->first();
			
			if (count($res) > 0 && mfield($res,"alias"))
				array_unshift($urlArray, mfield($res,"alias"));
		}
		
		return implode("/",$urlArray).$ext;
	}
	
	public function categoriesS($id)
	{
		$clean["id"] = $this->getPrincipale((int)$id);
		
		$record = $this->selectId($clean['id']);
		
		if (count($record) > 0)
		{
			$res = $this->clear()->select("categories.title")->inner("categories")->using("id_c")->where(array("codice_alfa"=>$record["codice_alfa"]))->orderBy("categories.lft")->toList("categories.title")->send();

			return "<i style='font-size:12px;'>".implode("<br />", $res)."</i>";
		}
		
		return "";
	}
	
	public function pDel($id = null, $whereClause = null)
	{
		return parent::del($id, $whereClause);
	}
	
	public function del($id = null, $whereClause = null)
	{
		$clean['id'] = $this->getPrincipale((int)$id);
		
		$record = $this->selectId($clean['id']);
		
		if (count($record) > 0)
		{
			//cancello le immagini relative al prodotto
			$im = new ImmaginiModel();
			$res = $im->select()->where(array('id_page'=>$clean['id']))->toList('id_immagine','immagine')->send();
			foreach ($res as $id_imm => $fileName)
			{
	// 			$im->files->removeFile($fileName);
				$im->del($id_imm);
			}
			
			//cancello i prodotti correlati
			$c = new CorrelatiModel();
			$c->del(null,"id_page=".$clean['id']);
			$c->del(null,"id_corr=".$clean['id']);
			
			//cancello il prodotto nel carrello
			$cart = new CartModel();
			$cart->del(null, "id_page=".$clean['id']);
			
			//cancello gli attributi
			$attr = new PagesattributiModel();
			$attr->del(null,"id_page='".$clean["id"]."'");
			
			//cancello le combinazioni del prodotto
// 			$comb = new CombinazioniModel();
// 			$comb->del(null,"id_page='".$clean["id"]."'");
			
			//cancello le caratteristiche del prodotto
// 			$pcv = new PagescarvalModel();
// 			$pcv->del(null,"id_page='".$clean["id"]."'");
			
			//cancello gli scaglioni
			$pcv = new ScaglioniModel();
			$pcv->del(null,"id_page='".$clean["id"]."'");
			
			//cancello gli scaglioni
			$pcv = new LayerModel();
			$pcv->del(null,"id_page='".$clean["id"]."'");
			
			//cancello le pagine correlate
			$c = new PagespagesModel();
			$c->del(null,"id_page=".$clean['id']);
			$c->del(null,"id_corr=".$clean['id']);
			
// 			parent::del($clean['id']);
			parent::del(null, "codice_alfa = '".$record["codice_alfa"]."'");
		}
	}
	
	public function getInputOrdinamento($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		if (isset(self::$currentRecord))
		{
			$res = self::$currentRecord;
		}
		else
		{
			$res = $this->clear()->select()->where(array('id_page'=>$clean['id_page']))->send();
		}
	
		$id_order = 0;
		$id_p = 0;
	
		if (count($res) > 0)
		{
			$id_order = $res[0]["pages"]["id_order"];
			$id_p = $res[0]["pages"]["id_page"];
		}
	
		return "<input class='input_ordinamento' style='width:35px;' rel='$id_p' type='text' name='id_order' value='$id_order'>";
	}
	
	public function getPubblicatoCheckbox($id_page)
	{
		$clean['id_page'] = (int)$id_page;

		$res = $this->clear()->select()->where(array('id_page'=>$clean['id_page']))->send();
		
		if (count($res) > 0)
		{
			self::$currentRecord = $res;
			return Html_Form::checkbox('attivo',$res[0]['pages']['attivo'],'Y','attivo_checkbox',$res[0]['pages']['id_page']).'<span class="loading_gif_del"><img src="'.Url::getFileRoot()."Public/Img/Icons/loading4.gif".'" /></span>';
		}
		self::$currentRecord = null;
		return "";
	}
	
	public function getInEvidenzaCheckbox($id_page)
	{
		$clean['id_page'] = (int)$id_page;

		if (isset(self::$currentRecord))
		{
			$res = self::$currentRecord;
		}
		else
		{
			$res = $this->clear()->select()->where(array('id_page'=>$clean['id_page']))->send();
		}

		if (count($res) > 0)
		{
			return Html_Form::checkbox('attivo',$res[0]['pages']['in_evidenza'],'Y','in_evidenza_checkbox',$res[0]['pages']['id_page']).'<span class="loading_gif_del"><img src="'.Url::getFileRoot()."Public/Img/Icons/loading4.gif".'" /></span>';
		}
		return "";
	}
	
	public function getThumb($id_page)
	{
		$clean['id_page'] = (int)$id_page;
		
		$principale = $this->getPrincipale($clean['id_page']);
		
		return "<img src='".Url::getFileRoot()."thumb/immagineinlistaprodotti/".$principale."' />";
	}
	
	public function inPromozioneText($id_page)
	{
		$clean['id_page'] = (int)$id_page;
		
		$res = $this->clear()->select()->where(array('id_page'=>$clean['id_page']))->send();
		
		if (count($res) > 0)
		{
			if (strcmp($res[0]["pages"]["in_promozione"],"Y") === 0)
			{
				$dal = getTimeStampComplete($res[0]["pages"]["dal"]);
				$al = getTimeStampComplete($res[0]["pages"]["al"]) + 86400;
				
				$now = time();
				
				if ($now >= $dal and $now <= $al)
				{
					return "<span class='text text-success'><b>In corso</b></span><br />(".smartDate($res[0]["pages"]["dal"])." / ".smartDate($res[0]["pages"]["al"]).")<br /><b>".setPriceReverse($res[0]["pages"]["prezzo_promozione"])." %</b>";
				}
				if ($now < $dal)
				{
					return "<b>Non ancora partita</b><br />(".smartDate($res[0]["pages"]["dal"])." / ".smartDate($res[0]["pages"]["al"]).")";
				}
				else
				{
					return "promozione scaduta";
				}
			}
		}
		return "no";
	}
	
	public function inPromozione($id_page, $page = null)
	{
		$clean['id_page'] = (int)$id_page;
		
		if (isset($page))
		{
			$res[0] = $page;
		}
		else
		{
			$res = $this->clear()->select()->where(array('id_page'=>$clean['id_page']))->send();
		}
		
		if (count($res) > 0)
		{
			if (strcmp($res[0]["pages"]["in_promozione"],"Y") === 0)
			{
				$dal = getTimeStampComplete($res[0]["pages"]["dal"]);
				$al = getTimeStampComplete($res[0]["pages"]["al"]) + 86400;
				
				$now = time();
				
				if ($now >= $dal and $now <= $al)
				{
					return true;
				}
			}
		}
		return false;
	}
	
	public function inPromozioneTot($id_page, $page = null)
	{
		$promo = $this->inPromozione($id_page, $page);
		
		$page = isset($page) ? $page["pages"] : $this->selectId($id_page);
	
		$classe = in_array($page["id_c"], User::$categorieInClasseSconto) ? true : false;
		
		return ($promo || $classe);
	}
	
	//controlla che sia l'id principale e in caso stoppa l'esecuzione e dai errore
	public function checkPrincipale($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$record = $this->clear()->selectId($clean["id_page"]);
		
		if (count($record) > 0 and strcmp($record["principale"],"Y") === 0)
		{
			
		}
		else
		{
			die("non permesso");
		}
		
	}
	
	public function principale($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$record = $this->selectId($clean["id_page"]);
		
		if (count($record) > 0)
		{
			if (strcmp($record["principale"],"Y") === 0)
			{
				return true;
			}
		}
		
		return false;
	}
	
	public function getPrincipale($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		
		$record = $this->selectId($clean["id_page"]);
		
		if (count($record) > 0)
		{
			if (strcmp($record["principale"],"Y") === 0)
			{
				return $clean["id_page"];
			}
			else
			{
				$res = $this->clear()->where(array("codice_alfa"=>$record["codice_alfa"],"principale"=>"Y"))->toList("id_page")->send();
				
				if (count($res) > 0)
				{
					return $res[0];
				}
				else
				{
					$res = $this->clear()->inner("immagini")->using("id_page")->where(array("codice_alfa"=>$record["codice_alfa"]))->orderBy("pages.id_page")->toList("pages.id_page")->send();
					
					return $res[0];
				}
			}
		}
		
		return 0;
	}
	
	public function checkDates()
	{
		foreach ($this->values as $key => $value)
		{
			if (strcmp($value,"0000-00-00") === 0)
			{
				$this->delFields($key);
			}
		}
	}
	
	public function sincronizza($id_page)
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
		
		$clean["id_page"] = (int)$id_page;
		
		$record = $this->selectId($clean["id_page"]);
		
		if (count($record) > 0)
		{
// 			if (strcmp($record["principale"],"Y") === 0)
// 			{
				$this->values = $record;
				
				$this->delFields("principale");
				$this->delFields("id_c");
				
				$this->checkDates();
				
				$this->delFields("gruppi");
				$this->delFields("id_page");
				$this->delFields("id_order");
				
				$this->sanitize();
				
				$this->pUpdate(null, "codice_alfa = '".$record["codice_alfa"]."'");
// 			}
		}
	}
	
	public function incategoria($id_page, $id_c)
	{
		$clean["id_page"] = (int)$id_page;
		$clean["id_c"] = (int)$id_c;
		
		$record = $this->selectId($clean["id_page"]);
		
		if (count($record) > 0)
		{
			$this->values = $record;
			
			$this->values["principale"] = "N";
			$this->values["id_c"] = $clean["id_c"];

			$this->checkDates();
			
			$this->delFields("gruppi");
			$this->delFields("id_page");
			$this->delFields("id_order");
			
			$this->pInsert();
			
			$this->updatePageAccessibility($this->lId);
		}
		
		return false;
	}
	
	public function hasCombinations($id_page)
	{
		$clean['id_page'] = (int)$id_page;
		
		$c = new CombinazioniModel();
		
		$res = $c->clear()->where(array(
			"id_page"=>$clean['id_page'],
		))->sWhere("(col_1 != 0 OR col_2 != 0 OR col_3 != 0 OR col_4 != 0 OR col_5 != 0 OR col_6 != 0 OR col_7 != 0 OR col_8 != 0)")->rowNumber();
		
		if ($res > 0)
			return true;
		
		$pp = new PagespersonalizzazioniModel();
		
		return $pp->clear()->where(array(
			"id_page"	=>	$clean['id_page']
		))->rowNumber();
		
		return false;
	}

	//controlla l'accesso alla pagina e restituisce vero o falso
	public function check($id_page)
	{
		$clean['id_page'] = (int)$id_page;
		
		$parents = $this->parents($clean['id_page']);
		
		$lId = $parents[(count($parents)-1)];
		
		$c = new CategoriesModel();

		return $c->check($lId);
	}
	
	public function accessibility($id_page)
	{
		$clean['id_page'] = (int)$id_page;
		
		$parents = $this->parents($clean['id_page']);
		
		//elimino la categoria root
		array_shift($parents);
		
		$gc = new ReggroupscategoriesModel();
		
		$gruppi = array();
		
		foreach ($parents as $idP)
		{
			$gr = $gc->clear()->select("reggroups.name")->inner("reggroups")->using("id_group")->where(array("id_c"=>(int)$idP))->toList("reggroups.name")->send();
			
			if (count($gr) > 0)
			{
				$gruppi = $gr;
			}
// 			$gruppi = $gr;
		}
		
		return array_unique($gruppi);
	}
	
	public function getAccessibilityWhere()
	{
		$temp = array();
			
		$count = 1;
		$temp["gruppi"] = "--free--";
		
		foreach (User::$groups as $gr)
		{
			$sign = str_repeat("-",$count);
			$temp[$sign."gruppi"] = "like '%($gr)%'";
			
			$count++;
		}
		
		$where = array(
		
			"OR" => $temp,
		
		);
		
		return $where;
	}
	
	public function isProdotto($id_page)
	{
		$clean['id_page'] = (int)$id_page;
		
		$section = $this->section($clean['id_page'], true);
		
		if (strcmp($section,Parametri::$nomeSezioneProdotti) === 0)
		{
			return true;
		}
		
		return false;
	}
	
	//get the section
	public function section($id_page, $firstElement = false)
	{
		$clean['id_page'] = (int)$id_page;
		
		$parents = $this->parents($clean['id_page'], false);
		
		//elimino la categoria root
		array_shift($parents);
		
		$section = "";
		
		foreach ($parents as $p)
		{
			if (strcmp($p["categories"]["section"],"") !== 0)
			{
				$section = $p["categories"]["section"];
			}
			
			if ($firstElement)
			{
				return $section;
			}
		}
		
		return $section;
	}
	
	public function prezzoMinimo($id_page, $forzaPrincipale = false)
	{
		$clean['id_page'] = (int)$id_page;
		
		if (!User::$nazione || $forzaPrincipale)
		{
			// Listino principale
			$c = new CombinazioniModel();
			
			$res = $c->clear()->select("min(price) as PREZZO_MINIMO")->where(array(
				"id_page"	=>	$clean['id_page'],
			))->send();
			
			if (count($res) > 0)
				return $res[0]["aggregate"]["PREZZO_MINIMO"];
		}
		else
		{
			// Listino nazione
			$c = new CombinazioniModel();
			
			$res = $c->clear()->select("min(combinazioni_listini.price) as PREZZO_MINIMO")->inner(array("listini"))->where(array(
				"id_page"	=>	$clean['id_page'],
				"combinazioni_listini.nazione"	=>	sanitizeAll(User::$nazione),
			))->send();
			
			if (count($res) > 0 && isset($res[0]["aggregate"]["PREZZO_MINIMO"]) && $res[0]["aggregate"]["PREZZO_MINIMO"])
				return $res[0]["aggregate"]["PREZZO_MINIMO"];
			else
				return $this->prezzoMinimo($clean['id_page'], true);
		}
		
		return 0;
	}
	
	public static function pesoMinimo($id_page)
	{
		$clean['id_page'] = (int)$id_page;
		
		// Listino principale
		$c = new CombinazioniModel();
		
		$res = $c->clear()->select("min(peso) as PESO_MINIMO")->where(array(
			"id_page"	=>	$clean['id_page'],
		))->send();
		
		if (count($res) > 0)
			return $res[0]["aggregate"]["PESO_MINIMO"];
		
		return 0;
	}
	
	public function linklingua($record, $lingua)
	{
		return $this->linklinguaGeneric($record["pages"]["id_page"], $lingua, "id_page");
	}
	
	public function getDocumenti($id, $lingua = null)
	{
		if (!isset($lingua))
			$lingua = Params::$lang;
		
		$d = new DocumentiModel();
		
		$d->clear()->addJoinTraduzione()->select("distinct documenti.id_doc,documenti.*,tipi_documento.*,contenuti_tradotti.*")->left(array("tipo"))->where(array(
			"id_page"	=>	(int)$id,
			"OR"	=>	array(
				"lingua" => "tutte",
				" lingua" => $lingua,
			),
		));
		
		if (v("attiva_gruppi_documenti"))
			$d->left(array("gruppi"))->sWhere("(reggroups.name is null OR reggroups.name in ('".implode("','", User::$groups)."'))");
		
		return $d->orderBy("documenti.id_order")->send();
	}
	
	public static function getAttributoDaCol($idPage, $col)
	{
		$pa = new PagesattributiModel();
		
		$res = $pa->clear()->select("attributi.*")->inner(array("attributo"))->where(array(
			"id_page"	=>	(int)$idPage,
			"colonna"	=>	sanitizeAll($col),
		))->send();
		
		if (count($res) > 0)
			return $res[0]["attributi"];
		
		return array();
	}
	
	public static function isAttributoTipo($idPage, $col, $tipo)
	{
		$attributo = self::getAttributoDaCol($idPage, $col);
		
		if (!empty($attributo) && $attributo["tipo"] == $tipo)
			return true;
		
		return false;
	}
	
	public static function isRadioAttributo($idPage, $col)
	{
		return self::isAttributoTipo($idPage, $col, "RADIO");
	}
	
	public function selectAttributi($id_page)
	{
		$clean['id'] = (int)$id_page;
		
		$lingua = Params::$lang;
		
		$pa = new PagesattributiModel();
		$cm = new CombinazioniModel();
		
		//estraggo gli attributi e i loro valori per creare le select per l'utente
		$colonne = $pa->getNomiColonne($clean['id']);
		
		$lista_valori_attributi = array();
		
		foreach ($colonne as $c => $name)
		{
			$temp = array();
			
			$resValoriAttributi = $cm->clear()
								->select("combinazioni.$c,attributi_valori.titolo,attributi_valori.immagine,contenuti_tradotti.titolo,attributi.tipo")
								->inner("attributi_valori")->on("attributi_valori.id_av = combinazioni.$c")
								->inner("attributi")->on("attributi.id_a = attributi_valori.id_a")
								->left("contenuti_tradotti")->on("contenuti_tradotti.id_av = attributi_valori.id_av and contenuti_tradotti.lingua = '".sanitizeDb($lingua)."'")
								->where(array("id_page"=>$clean['id']))
								->orderBy("attributi_valori.id_order")
								->groupBy("combinazioni.$c,attributi_valori.id_av")
								->send();
			
			$arrayCombValori = array();
			
			$tipo = "TENDINA";
			
			if (count($resValoriAttributi) > 0)
			{
				$tipo = $resValoriAttributi[0]["attributi"]["tipo"];
				
				$temp = array();
				
				if ($tipo == "TENDINA" || $tipo == "IMMAGINE")
				{
					if (!v("primo_attributo_selezionato"))
						$temp = array("0" => $name);
				}
			}
			
			foreach ($resValoriAttributi as $rva)
			{
				if ($tipo == "RADIO")
					$arrayCombValori[$rva["combinazioni"][$c]] = "<span class='variante_radio_valore ".v("classe_variante_radio")."'>".avfield($rva, "titolo")."</span>";
				else if ($tipo == "IMMAGINE")
					$arrayCombValori[$rva["combinazioni"][$c]] = $rva["attributi_valori"]["immagine"];
				else
					$arrayCombValori[$rva["combinazioni"][$c]] = $name.": ".avfield($rva, "titolo");
			}
			
			$lista_valori_attributi[$c] = $temp + $arrayCombValori;
		}
		
		return array($colonne, $lista_valori_attributi);
	}
	
	public function selectPersonalizzazioni($id_page)
	{
		$pers = new PersonalizzazioniModel();
		
		return $pers->clear()->inner(array("pages"))->addJoinTraduzione()->where(array(
			"pages_personalizzazioni.id_page"	=>	(int)$id_page,
		))->send();
	}
	
	public function giacenzaPrincipale($id_page)
	{
		$c = new CombinazioniModel();
		$principale = $c->combinazionePrincipale((int)$id_page);
			
		if (!empty($principale))
			return $principale["giacenza"];
		
		return 0;
	}
	
	public function marchio($record)
	{
		return $record["marchi"]["titolo"];
	}
	
	public function tag($record)
	{
		$pt = new PagestagModel();
		
		$tags = $pt->clear()->select("tag.titolo")->inner(array("tag"))->where(array(
			"id_page"	=>	(int)$record["pages"]["id_page"],
		))->toList("tag.titolo")->send();
		
		return implode("<br />", $tags);
	}
	
	public function aggiungiAccesori($idPage)
	{
		$ids = $this->clear()->where(array(
			"attivo"	=>	"Y",
			"aggiungi_sempre_come_accessorio"	=>	"Y",
		))->toList("id_page")->send();
		
		$c = new CorrelatiModel();
		
		foreach ($ids as $id)
		{
			$numero = $c->clear()->where(array(
				"id_page"	=>	(int)$idPage,
				"id_corr"	=>	(int)$id,
				"accessorio"=>	1,
			))->rowNumber();
			
			if (!$numero)
			{
				$c->setValues(array(
					"id_page"		=>	$idPage,
					"id_corr"		=>	$id,
					"accessorio"	=>	1,
				));
				
				$c->insert();
			}
		}
	}
	
	public function acquistabile($idPage)
	{
		return $this->clear()->where(array(
			"id_page"		=>	(int)$idPage,
			"acquistabile"	=>	"Y",
			"acquisto_diretto"	=>	"Y",
		))->rowNumber();
	}
	
	public static function gTipoPagina($tipo)
	{
		$p = new PagesModel();
		
		return $p->clear()->where(array(
			"attivo"	=>	"Y",
			"tipo_pagina"		=>	sanitizeAll($tipo),
			"principale"	=>	"Y",
		))->field("id_page");
	}
	
	public static function disponibilita($idPage = 0)
	{
		$c = new CombinazioniModel();
		
		$res = $c->clear()->select("max(giacenza) as GIACENZA")->where(array(
			"id_page"	=>	(int)$idPage
		))->send();
		
		if (count($res) > 0)
			return (int)$res[0]["aggregate"]["GIACENZA"];
		
		return 0;
	}
	
	public static function gXmlProdottiGoogle($p = null)
	{
		$c = new CategoriesModel();
		
		if (!isset($p))
		{
			$p = new PagesModel();
			$p->clear();
		}
		
		$m = new MarchiModel();
		
		$idShop = $c->getShopCategoryId();
		
		$children = $c->children($idShop, true);
		
		$catWhere = "in(".implode(",",$children).")";
		$res = $p->select("distinct pages.codice_alfa,pages.*,categories.*,contenuti_tradotti.*,contenuti_tradotti_categoria.*")->aWhere(array(
			"in" => array("-id_c" => $children),
			"pages.attivo"	=>	"Y",
			"acquistabile"	=>	"Y",
		))->inner("categories")->on("categories.id_c = pages.id_c")
			->left("contenuti_tradotti")->on("contenuti_tradotti.id_page = pages.id_page and contenuti_tradotti.lingua = '".sanitizeDb(Params::$lang)."'")
			->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_page = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")
			->orderBy("pages.title")->send();
		
		$arrayProdotti = array();
		
		foreach ($res as $r)
		{
			$giacenza = self::disponibilita($r["pages"]["id_page"]);
			$outOfStock = v("attiva_giacenza") ? "out of stock" : "in stock";
			
			$prezzoMinimo = $p->prezzoMinimo($r["pages"]["id_page"]);
			
			$temp = array(
				"g:id"	=>	$r["pages"]["id_page"],
				"g:title"	=>	htmlentitydecode(field($r,"title")),
				"g:description"	=>	htmlentitydecode(field($r,"description")),
				"g:google_product_category"	=>	htmlentitydecode(cfield($r,"title")),
				"g:link"	=>	Url::getRoot().getUrlAlias($r["pages"]["id_page"]),
				"g:price"	=>	number_format(calcolaPrezzoIvato($r["pages"]["id_page"],$prezzoMinimo),2,".",""). " EUR",
				"g:availability"	=>	$giacenza > 0 ? "in stock" : $outOfStock,
			);
			
			if ($r["pages"]["immagine"])
				$temp["g:image_link"] = Url::getRoot()."thumb/dettagliobig/".$r["pages"]["immagine"];
			
			if ($r["pages"]["id_marchio"])
			{
				$marchio = $m->clear()->addJoinTraduzione()->where(array(
					"id_marchio"	=>	(int)$r["pages"]["id_marchio"],
				))->first();
				
				if (!empty($marchio))
					$temp["g:brand"] = htmlentitydecode(mfield($marchio, "titolo"));
			}
			
			if (isset($_GET["fbk"]))
				$temp["condition"] = "new";
			
			if ($p->inPromozione($r["pages"]["id_page"], $r))
				$temp["g:sale_price"] = number_format(calcolaPrezzoFinale($r["pages"]["id_page"], $prezzoMinimo),2,".",""). " EUR";
			
			$arrayProdotti[] = $temp;
		}
		
		return $arrayProdotti;
	}
	
	public function tipopagina($record)
	{
		if (isset(self::$tipiPagina[$record["pages"]["tipo_pagina"]]))
			return self::$tipiPagina[$record["pages"]["tipo_pagina"]];
		
		return "";
	}
	
	public function addJoinTraduzionePagina()
	{
		$this->inner("categories")->on("categories.id_c = pages.id_c")
			->left("contenuti_tradotti")->on("contenuti_tradotti.id_page = pages.id_page and contenuti_tradotti.lingua = '".sanitizeDb(Params::$lang)."'")
			->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_page = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'");
		
		if (!$this->select)
			$this->select("distinct pages.codice_alfa,pages.*,categories.*,contenuti_tradotti.*,contenuti_tradotti_categoria.*");
		
		return $this;
	}
	
	public static function listaImmaginiPagina()
	{
		if (isset(self::$arrayImmagini))
			return self::$arrayImmagini;
		
		$p = new PagesModel();
		$i = new ImmaginiModel();
		
		// Immagine principale
		$strImm = $p->clear()->select("distinct codice_alfa,pages.id_page,pages.immagine")->toList("id_page", "immagine")->send();
		
		$struttImmagini = array();
		
		foreach ($strImm as $idPage => $immagine)
		{
			if (trim($immagine))
				$struttImmagini[$idPage] = array($immagine);
		}
		
		// Altre immagini
		$elencoImmagini = $i->clear()->select("id_page,immagine")->orderBy("id_order")->send(false);
		
		foreach ($elencoImmagini as $row)
		{
			if (isset($struttImmagini[$row["id_page"]]))
				$struttImmagini[$row["id_page"]][] = $row["immagine"];
			else
				$struttImmagini[$row["id_page"]] = array($row["immagine"]);
		}
		
		self::$arrayImmagini = $struttImmagini;
		
		return self::$arrayImmagini;
	}
	
	/* Importa la riga dell'offerta nella fattura */
    public function aggiungiaprodotto($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && isset($_GET["id_pcorr"]) && isset($_GET["pcorr_sec"]))
		{
			$pp = new PagespagesModel();
			
			$pp->setValues(array(
				"id_page"	=>	(int)$_GET["id_pcorr"],
				"id_corr"	=>	(int)$id,
				"section"	=>	$_GET["pcorr_sec"],
			), "sanitizeAll");
			
			$pp->insert();
		}
    }
    
    public static function punteggio($id)
    {
		$f = new FeedbackModel();
		
		return $f->clear()->where(array(
			"id_page"	=>	(int)$id,
			"is_admin"	=>	0,
			"attivo"	=>	1,
		))->getAvg("voto");
    }
    
    public static function numeroFeedback($id)
    {
		$f = new FeedbackModel();
		
		return $f->clear()->where(array(
			"id_page"	=>	(int)$id,
			"is_admin"	=>	0,
			"attivo"	=>	1,
		))->rowNumber();
    }
    
    public static function hasFeedback($id)
    {
		if (!isset(self::$pagineConFeedback))
		{
			$f = new FeedbackModel();
			
			self::$pagineConFeedback = $f->clear()->select("distinct id_page")->toList("id_page")->send();
		}
		
		if (in_array($id, self::$pagineConFeedback))
			return true;
		
		return false;
    }
    
    public static function getRichSnippet($id)
    {
		$pm = new PagesModel();
		
		$p = $pm->clear()->addJoinTraduzionePagina()->where(array(
			"pages.id_page"	=>	(int)$id,
		))->first();
		
		$i = new ImmaginiModel();
		
		$snippetArray = array();
		
		if (!empty($p))
		{
			$images = array();
			
			if ($p["pages"]["immagine"])
				$images[] = Url::getFileRoot()."thumb/dettagliobig/".$p["pages"]["immagine"];
			
			$altreImmagini = $i->clear()->where(array("id_page" => (int)$id))->orderBy("id_order")->send(false);
			
			foreach ($altreImmagini as $imm)
			{
				$images[] = Url::getFileRoot()."thumb/dettagliobig/".$imm["immagine"];
			}
			
			$snippetArray = array(
				"@context"	=>	"https://schema.org/",
				"@type"		=>	"Product",
				"name"		=>	sanitizeJs(field($p, "title")),
			);
			
			if (!empty($images))
				$snippetArray["image"] = $images;
			
			$snippetArray["description"] = sanitizeJs(strip_tags(htmlentitydecode(field($p, "description"))));
			
			if ($p["pages"]["codice"])
				$snippetArray["sku"] = $p["pages"]["codice"];
			
			if (v("usa_marchi") && $p["pages"]["id_marchio"])
			{
				$m = new MarchiModel();
				
				$marchio = $m->clear()->addJoinTraduzione()->where(array(
					"id_marchio"	=>	(int)$p["pages"]["id_marchio"],
				))->first();
				
				if (!empty($marchio))
				{
					$snippetArray["brand"] = array(
						"@type"	=>	"Brand",
						"name"	=>	 sanitizeJs(mfield($marchio, "titolo")),
					);
				}
			}
			
			if (v("abilita_feedback") && self::hasFeedback((int)$id))
			{
				$fm = new FeedbackModel();
				
				$feedback = $fm->clear()->where(array(
					"id_page"	=>	(int)$id,
					"is_admin"	=>	0,
					"attivo"	=>	1,
				))->orderBy("feedback.voto desc")->limit(1)->send(false);
				
				if (count($feedback) > 0)
				{
					$snippetArray["review"] = array(
						"@type"	=>	"Review",
						"reviewRating"	=>	array(
							"@type"	=>	"Rating",
							"ratingValue"	=>	$feedback[0]["voto"],
						),
						"author"	=>	array(
							"@type"	=>	"Person",
							"name"	=>	sanitizeJs($feedback[0]["autore"]),
						),
					);
				}
				
				$snippetArray["aggregateRating"] = array(
					"@type"	=>	"AggregateRating",
					"ratingValue"	=>	number_format(self::punteggio((int)$id),1,".",""),
					"reviewCount"	=>	self::numeroFeedback((int)$id),
				);
			}
		}
		
		return $snippetArray;
    }
    
    public static function getTagCanonical($id)
    {
		$pm = new PagesModel();
		
		$idP = $pm->clear()->where(array(
			"pages.id_page"	=>	(int)$id,
		))->field("id_p");
		
		if ($idP)
			return '<link rel="canonical" href="'.Url::getRoot().getUrlAlias($idP).'" />';
		
		return "";
    }
}
