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

class ListeregaloController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array(
		'dal:sanitizeAll'=>'tutti',
		'al:sanitizeAll'=>'tutti',
		'titolo:sanitizeAll'=>'tutti',
		'id_c:sanitizeAll'=>'tutti',
	);
	
	public $tabella = "liste regalo";
	
	public $sezionePannello = "ecommerce";
	
	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		if (!v("attiva_liste_regalo"))
			die();
		
		$this->model("ListeregalotipiModel");
		$this->model("ListeregalopagesModel");
		$this->model("ListeregalolinkModel");
		$this->model("OrdiniModel");
	}
	
	public function main()
	{
		$this->shift();
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>100, 'mainMenu'=>'add');
		$this->mainFields = array("cliente", "liste_regalo.titolo", "liste_regalo_tipi.titolo", "liste_regalo.codice", "liste_regalo.nome_bambino", "liste_regalo.genitore_1", "cleanDateTime", "liste_regalo.data_scadenza", "liste_regalo.attivo");
		$this->mainHead = "Cliente,Titolo,Tipo,Codice,Nome Bimbo/a,Genitore 1,Creazione,Scadenza,Attivo";
		
		SlideModel::$YN["tutti"] = gtext("Attiva / NON attiva");
		
		$filtri = array("dal","al","titolo",array("attivo",null,SlideModel::$YN));
		$this->filters = $filtri;
		
		$this->m[$this->modelName]->clear()
			->select("*")
			->where(array(
				"lk"	=>	array(
					"n!concat(regusers.ragione_sociale,' ',regusers.username,' ',regusers.nome,' ',regusers.cognome,' ',regusers.nome,' ',regusers.username,' ',regusers.ragione_sociale,' ',coalesce(liste_regalo.titolo,''),' ',liste_regalo.codice,' ',coalesce(liste_regalo.nome_bambino,''),' ',coalesce(liste_regalo.genitore_1,''),' ',coalesce(liste_regalo.genitore_2,''),' ',coalesce(liste_regalo.email,''))"	=>	$this->viewArgs["titolo"],
				),
				'liste_regalo.attivo'	=>	$this->viewArgs['attivo'],
			))
			->inner(array("tipo", "cliente"))
			->orderBy("liste_regalo.id_lista_regalo desc");
		
		$this->m[$this->modelName]->setDalAlWhereClause($this->viewArgs['dal'], $this->viewArgs['al'], "data_scadenza");
		
		if ($this->viewArgs["id_c"] != "tutti")
		{
			$this->m[$this->modelName]->inner(array("regali"))->aWhere(array(
				"liste_regalo_pages.id_c"	=>	(int)$this->viewArgs["id_c"],
			))->groupBy("liste_regalo.id_lista_regalo");
		}
		
		$this->m[$this->modelName]->convert()->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$lista = $this->m['ListeregaloModel']->selectId((int)$id);
		
		$idTipoLista = !empty($lista) ? $lista["id_lista_tipo"] : $this->request->post("id_lista_tipo",0,"forceInt");
		
		$selectTipi = ListeregalotipiModel::getSelectTipi($idTipoLista);
		
		if (!$idTipoLista)
			$idTipoLista = count($selectTipi) > 0 ? key($selectTipi) : 0;
		
		$tipoLista = $this->m["ListeregalotipiModel"]->selectId((int)$idTipoLista);
		
		if (!empty($tipoLista))
		{
			$fields = 'id_lista_tipo,id_user,titolo,codice,data_scadenza,attivo';
			
			if ($tipoLista["campi"])
				$fields .= ','.$tipoLista["campi"];
		}
		else
			$fields = 'id_lista_tipo,id_user,titolo,codice,data_scadenza,attivo,nome_bambino,genitore_1,genitore_2,sesso,data_nascita,data_battesimo';
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		$disabledFields = "codice";
		
		if (!empty($lista))
			$disabledFields .= ",id_lista_tipo,id_user";
		
		$this->disabledFields = $disabledFields;
		$this->m['ListeregaloModel']->delFields($disabledFields);
		
		$this->functionsIfFromDb = array(
			"data_nascita"		=>	"svuotaData",
			"data_battesimo"	=>	"svuotaData",
		);
		
		$this->m['ListeregaloModel']->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		$this->menuLinks = "back,save,vai_alla_lista";
		
		parent::form($queryType, $id);
	}
	
	public function pagine($id = 0)
	{
		$this->_posizioni['pagine'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $data["id"] = $this->id = (int)$id;
		$this->id_name = "id_lista_regalo";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "ListeregalopagesModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array(
			'primaImmagineCarrelloCrud',
			"pages.title",
			"variante",
			"combinazioni.codice",
			"prezzo",
			"quantita",
			"ordini",
			"noteCrud",
			"statoElementoCrud",
		);
		
		$this->mainHead = "Immagine,Prodotto,Variante,Codice,Prezzo (€),Quantità desiderata,Regalati,Note,Stato";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back,save_regali','mainAction'=>"pagine/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("liste_regalo_pages.*,pages.*,combinazioni.*")->inner(array("pagina","combinazione"))->orderBy("liste_regalo_pages.id_lista_regalo_page")->where(array("id_lista_regalo"=>$clean['id']))->save();
		
		$this->colProperties = array(
			array(
				'width'	=>	'60px',
			),
			array(
				'width'	=>	'80px',
			),
		);
		
		$this->inverseColProperties = array(
			array(
				'width'	=>	'1%',
				'class'	=>	'ldel',
			),
			null,
			array(
				'width'	=>	'20%',
			),
		);
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["ListeregaloModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
	public function righe($id = 0)
	{
		$this->_posizioni['righe'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $data["id"] = $this->id = (int)$id;
		$this->id_name = "id_lista_regalo";
		
		$this->mainButtons = "";
		$this->addBulkActions = false;
		$this->modelName = "RigheModel";
		
		$this->mainFields = array(
			'thumbCrud',
			"righe.title",
			"variante",
			"righe.quantity",
			'OrdiniModel.getNome|orders.id_o',
			'orders.email',
			"smartDate|orders.data_creazione",
			"statoordinelabel",
			"noteCrud",
		);
		
		$this->mainHead = "Immagine,Prodotto,Variante,Quantità,Cliente,Email,Data,Stato ordine,Note";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"righe/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m($this->modelName)->select("orders.*,righe.*")
			->inner("orders")->on("orders.id_o = righe.id_o")
			->orderBy("righe.data_creazione desc")->where(array("orders.id_lista_regalo"=>$clean['id']))->save();
		
		$this->colProperties = array(
			array(
				'width'	=>	'100px',
			),
		);
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["ListeregaloModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
	public function inviti($id = 0)
	{
		$this->_posizioni['inviti'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $data["id"] = $this->id = (int)$id;
		$this->id_name = "id_lista_regalo";
		
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		$this->addBulkActions = false;
		$this->colProperties = array();
		
		$this->modelName = "ListeregalolinkModel";
		
		$this->m[$this->modelName]->updateTable('del');
		
		$this->mainFields = array(
			'liste_regalo_link.nome',
			'liste_regalo_link.cognome',
			'liste_regalo_link.email',
			'inviata',
			'ultimoinvito',
			'invia',
		);
		
		$this->mainHead = "Nome,Cognome,Email,Stato invio,Data ultimo invito,Invia nuovamente";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"inviti/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->orderBy("id_lista_regalo_link desc")->where(array("id_lista_regalo"=>$clean['id']))->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["ListeregaloModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
	public function salvapagine()
	{
		Params::$setValuesConditionsFromDbTableStruct = false;
		CombinazioniModel::$aggiornaAliasAdInserimento = false;
		
		if (v("usa_transactions"))
			$this->m["ListeregalopagesModel"]->db->beginTransaction();
		
		$this->clean();
		
		$valori = $this->request->post("valori","[]");
		
		$valori = json_decode($valori, true);
		
		foreach ($valori as $v)
		{
			if ((int)$v["quantity"] > 0)
			{
				$this->m["ListeregalopagesModel"]->sValues(array(
					"quantity"	=>	(int)$v["quantity"],
				));
				
				$this->m["ListeregalopagesModel"]->update($v["id_riga"]);
			}
			else
				$this->m["ListeregalopagesModel"]->del($v["id_riga"]);
		}
		
		if (v("usa_transactions"))
			$this->m["ListeregalopagesModel"]->db->commit();
	}
	
	public function ordini($id = 0)
	{
		$this->_posizioni['ordini'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_lista_regalo";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "OrdiniModel";
		$this->addBulkActions = false;
		$this->colProperties = array();
		
		$this->mainFields = array("vediDaListe",'OrdiniModel.getNome|orders.id_o','orders.email',"smartDate|orders.data_creazione","orders.nome_promozione","statoordinelabel","totaleCrud", "dedicaCrud");
		$this->mainHead = "Ordine,Cliente,Email,Data,Promoz.,Stato,Totale,Dedica";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"ordini/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("orders.*")->orderBy("orders.id_o desc")->where(array("id_lista_regalo"=>$clean['id']))->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["ListeregaloModel"]->titolo($clean['id']);
		
		$this->append($data);
	}
	
	public function invii($id = 0)
	{
		$this->model("EventiretargetingelementiModel");
		
		$this->_posizioni['invii'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_p";
		
		$this->queryActions = $this->bulkQueryActions = "";
		$this->mainButtons = "";
		$this->addBulkActions = false;
		
		$this->colProperties = array();
		
		$this->modelName = "EventiretargetingelementiModel";
		
		$this->mainFields = array("cleanDateTime", "eventi_retargeting_elemento.email", "mail_ordini.oggetto", "inviata", "dettagliElementoCrud");
		$this->mainHead = "Data,Email,Oggetto,Inviata,Ordine";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"invii/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->select("*")
			->inner(array("mail"))
			->inner("liste_regalo_email")->on("eventi_retargeting_elemento.id_elemento = liste_regalo_email.id_lista_regalo_email")
			->orderBy("eventi_retargeting_elemento.data_creazione desc")
			->where(array(
				"liste_regalo_email.id_lista_regalo"		=>	$clean['id'],
				"tabella_elemento"	=>	"liste_regalo_email",
				"duplicato"			=>	0,
			))
			->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["ListeregaloModel"]->titolo($clean['id']);
		
		$data["record"] = $this->m["ListeregaloModel"]->selectId($clean['id']);
		
		$this->append($data);
	}
	
	protected function aggiungiUrlmenuScaffold($id)
	{
		$this->scaffold->mainMenu->links["vai_alla_lista"]["absolute_url"] = Domain::$publicUrl."/it_it/contenuti/listaregalo/?codice_lista=".$this->m[$this->modelName]->whereId((int)$id)->field("codice");
	}
}
