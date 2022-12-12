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

class PromozioniController extends BaseController {
	
	public $orderBy = "promozioni.dal desc,promozioni.al desc,promozioni.id_p desc";
	
	public $argKeys = array(
		'attivo:sanitizeAll'=>'tutti',
		'tipo:sanitizeAll'=>'tutti',
		'fonte:sanitizeAll'=>'MANUALE',
		'codice:sanitizeAll'=>'tutti',
	);
	
	public $useEditor = true;
	
	public $sezionePannello = "ecommerce";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->s["admin"]->check();
		
		$this->model("PromozionicategorieModel");
		$this->model("CategorieModel");
		$this->model("PromozionipagineModel");
		$this->model("PagesModel");
		$this->model("PromozionitipiclientiModel");
	}
	
	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("[[ledit]];promozioni.titolo;","promozioni.codice","promozioni.dal","promozioni.al");
		$this->mainHead = "Titolo,Codice promozione,Dal,Al";
		$this->filters = array("codice",array("attivo",null,$this->filtroAttivo));
		
		if (v("attiva_promo_sconto_assoluto"))
		{
			$this->mainFields[] = "promozioni.tipo_sconto";
			$this->mainHead .= ",Tipo sconto";
			$this->filters[] = array("tipo",null,array(
				"tutti"	=>	"Tipo sconto"
			) + array("PERCENTUALE"=>"PERCENTUALE","ASSOLUTO"=>"ASSOLUTO"));
			
			$this->filters[] = array("fonte",null,array(
				"tutti"	=>	"Fonte"
			) + array("MANUALE"=>"Manuale","GIFT_CARD"=>"Gift Card"));
		}
		
		$this->mainFields[] = "sconto";
		$this->mainFields[] = "PromozioniModel.getNUsata|promozioni.id_p";
		$this->mainFields[] = "getYesNo|promozioni.attivo";
		$this->mainHead .= ",Valore sconto,N° usata,Attiva?";
		
		if (v("attiva_promo_sconto_assoluto"))
		{
			$this->mainFields[] = "ordine";
			$this->mainHead .= ",Ordine";
		}
		
		$this->m[$this->modelName]->select("promozioni.*,orders.id_o")
			->left(array("righe"))
			->left("orders")->on("righe.id_o = orders.id_o")
			->where(array(
				'lk'	=>	array(
					'codice'	=>	$this->viewArgs['codice'],
				),
				'attivo'	=>	$this->viewArgs['attivo'],
				'tipo_sconto'=>	$this->viewArgs['tipo'],
			))->orderBy($this->orderBy)->convert();
		
		if ($this->viewArgs["fonte"] != "tutti")
		{
			if ($this->viewArgs["fonte"] == "MANUALE")
				$this->m[$this->modelName]->aWhere(array(
					"id_r"	=>	0,
				));
			else
			{
				$this->m[$this->modelName]->aWhere(array(
					"ne"	=>	array(
						"id_r"	=>	0,
					),
				));
				
				$this->addBulkActions = false;
				$this->colProperties = array();
			}
		}
		
		$this->m[$this->modelName]->save();
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$record = $this->m[$this->modelName]->selectId((int)$id);
		
		$this->_posizioni['main'] = 'class="active"';
		
		$campi = 'titolo,codice,attivo,dal,al';
		
		if (v("attiva_promo_sconto_assoluto"))
			$campi .= ',tipo_sconto,tipo_credito';
		
		$campi .= ',sconto,sconto_valido_sopra_euro,numero_utilizzi,numero_utilizzi_per_email';
		
		$this->m[$this->modelName]->setValuesFromPost($campi);
		
		if (!empty($record) && $record["id_r"])
		{
			$campiDisabilitati = "codice,sconto,tipo_sconto,tipo_credito";
			$this->disabledFields = $campiDisabilitati;
			$this->m[$this->modelName]->delFields($campiDisabilitati);
		}
		
		parent::form($queryType, $id);
		
		$data["record"] = $record;
		
		$this->append($data);
	}
	
	public function tipi($id = 0)
	{
		$this->_posizioni['tipi'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_p";
		
		$this->m['PromozionitipiclientiModel']->setFields('id_tipo_cliente','sanitizeAll');
		$this->m['PromozionitipiclientiModel']->values['id_p'] = $clean['id'];
		$this->m['PromozionitipiclientiModel']->updateTable('insert,del');
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "PromozionitipiclientiModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("tipi_clienti.titolo");
		$this->mainHead = "Tipo cliente";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"tipi/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("promozioni_tipi_clienti.*,tipi_clienti.*")->inner(array("tipo_cliente"))->orderBy("tipi_clienti.titolo")->where(array("id_p"=>$clean['id']))->convert()->save();
		
		$this->tabella = "promozioni";
		
		parent::main();
		
		$data["listaTipi"] = TipiclientiModel::g()->selectTipiCliente();
		
		$data["titoloRecord"] = $this->m["PromozioniModel"]->titolo($clean['id']);
		
		$data["record"] = $this->m["PromozioniModel"]->selectId($clean['id']);
		
		$this->append($data);
	}
	
	public function categorie($id = 0)
	{
		$this->_posizioni['categorie'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_p";
		
		$this->m['PromozionicategorieModel']->setFields('id_c','sanitizeAll');
		$this->m['PromozionicategorieModel']->values['id_p'] = $clean['id'];
		$this->m['PromozionicategorieModel']->updateTable('insert,del');
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "PromozionicategorieModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("CategoriesModel.indentNoHtml|promozioni_categorie.id_c");
		$this->mainHead = "Categoria";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"categorie/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("promozioni_categorie.*")->inner(array("categoria"))->orderBy("categories.lft")->where(array("id_p"=>$clean['id']))->convert()->save();
		
		$this->tabella = "promozioni";
		
		parent::main();
		
		$data["listaCategorie"] = $this->m["CategorieModel"]->buildSelect();
		
		$data["titoloRecord"] = $this->m["PromozioniModel"]->titolo($clean['id']);
		
		$data["record"] = $this->m["PromozioniModel"]->selectId($clean['id']);
		
		$this->append($data);
	}
	
	public function pagine($id = 0)
	{
		$this->_posizioni['pagine'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_p";
		
		$this->m['PromozionipagineModel']->setFields('id_page','sanitizeAll');
		$this->m['PromozionipagineModel']->values['id_p'] = $clean['id'];
		$this->m['PromozionipagineModel']->updateTable('insert,del');
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "PromozionipagineModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("pages.title");
		$this->mainHead = "Prodotto";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"pagine/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("pages.title,promozioni_pages.*")->inner(array("pagina"))->orderBy("pages.title")->where(array("id_p"=>$clean['id']))->convert()->save();
		
		$this->tabella = "promozioni";
		
		parent::main();
		
		$data["listaProdotti"] = array();
		
		$idP = $this->m["CategorieModel"]->clear()->where(array("section"=>Parametri::$nomeSezioneProdotti))->field("id_c");
		$children = $this->m["CategorieModel"]->children((int)$idP, true);

		$res = $this->m['PagesModel']->clear()->where(array(
			"attivo" => "Y",
			"principale"=>"Y",
			"in" => array("-id_c" => $children),
		))->orderBy("id_order")->send();
		foreach ($res as $r)
		{
			$data["listaProdotti"][$r["pages"]["id_page"]] = $r["pages"]["codice"] . " - " . $r["pages"]["title"];
		}
		
		$data["titoloRecord"] = $this->m["PromozioniModel"]->titolo($clean['id']);
		
		$data["record"] = $this->m["PromozioniModel"]->selectId($clean['id']);
		
		$this->append($data);
	}
	
	public function invii($id = 0)
	{
		$this->model("EventiretargetingelementiModel");
		
		$this->_posizioni['invii'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->tabella = "promozioni";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_p";
		
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		$this->addBulkActions = false;
		
		$this->colProperties = array();
		
		$this->modelName = "EventiretargetingelementiModel";
		
		$this->mainFields = array("cleanDateTime", "eventi_retargeting_elemento.email", "mail_ordini.oggetto", "inviata");
		$this->mainHead = "Data,Email,Oggetto,Inviata";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"invii/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("*")->inner(array("mail"))->orderBy("eventi_retargeting_elemento.data_creazione desc")->where(array(
			"id_elemento"		=>	$clean['id'],
			"tabella_elemento"	=>	"promozioni",
			"duplicato"			=>	0,
		))->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["PromozioniModel"]->titolo($clean['id']);
		
		$data["record"] = $this->m["PromozioniModel"]->selectId($clean['id']);
		
		$this->append($data);
	}
	
	public function ordini($id = 0)
	{
		$this->model("OrdiniModel");
		
		$this->_posizioni['ordini'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_p";
		
		$this->mainButtons = "";
		
		$this->modelName = "OrdiniModel";
		$this->addBulkActions = false;
		$this->colProperties = array();
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array("vedi","smartDate|orders.data_creazione","orders.nome_promozione","statoOrdineBreve|orders.stato","totaleCrudPieno", "totaleCrud");
		$this->mainHead = "Ordine,Data,Promoz.,Stato,Totale pieno,Totale scontato";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"ordini/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("orders.*")->orderBy("orders.id_o desc")->where(array("id_p"=>$clean['id']))->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["PromozioniModel"]->titolo($clean['id']);
		
		$data["record"] = $this->m["PromozioniModel"]->selectId($clean['id']);
		
		$this->append($data);
	}
}
