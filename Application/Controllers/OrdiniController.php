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

Helper_List::$filtersFormLayout["filters"]["lista_regalo"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"Lista regalo ..",
	),
);

Helper_List::$filtersFormLayout["filters"]["tipo_ordine"] = array(
	"type"	=>	"select",
	"attributes"	=>	array(
		"class"	=>	"form-control",
	),
);

Helper_List::$filtersFormLayout["filters"]["fattura"] = array(
	"type"	=>	"select",
	"attributes"	=>	array(
		"class"	=>	"form-control",
	),
);

Helper_List::$filtersFormLayout["filters"]["gestionale"] = array(
	"type"	=>	"select",
	"attributes"	=>	array(
		"class"	=>	"form-control",
	),
);

Helper_List::$filtersFormLayout["filters"]["stato_sped"] = array(
	"type"	=>	"select",
	"attributes"	=>	array(
		"class"	=>	"form-control",
	),
);

Helper_List::$filtersFormLayout["filters"]["dalc"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control data_field",
		"placeholder"	=>	"Dal (consegna) ..",
	),
	"wrap"	=>	array(
		'<div class="input-group date">','<span class="input-group-addon"><i class="fa fa-calendar"></i></span></div>'
	),
);

Helper_List::$filtersFormLayout["filters"]["alc"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control data_field",
		"placeholder"	=>	"Al (consegna) ..",
	),
	"wrap"	=>	array(
		'<div class="input-group date">','<span class="input-group-addon"><i class="fa fa-calendar"></i></span></div>'
	),
);

Helper_List::$filtersFormLayout["filters"]["numero_documento"] = array(
	"attributes"	=>	array(
		"class"	=>	"form-control",
		"placeholder"	=>	"Numero doc..",
	),
);

Helper_List::$filtersFormLayout["filters"]["nazione_spedizione"] = array(
	"type"	=>	"select",
	"attributes"	=>	array(
		"class"	=>	"form-control",
	),
);

class OrdiniController extends BaseController {
	
	public $sezionePannello = "ecommerce";
	
	public $addIntegrazioniInMain = false;
	
	public $tabella = "ordini";
	
	public $defaultAction = "vedi";
	
	public $campiForm = "";
	
	public $campiAggiuntiviOrdine = array();
	
	public static $selectFiltroTipo = array(
		"tutti"		=>	"Tipo cliente",
		"privato"	=>	"Privato",
		"libero_professionista"	=>	"Professionista",
		"azienda"	=>	"Azienda",
	);
	
	public $argKeys = array(
		'page:forceInt'=>1,
		'id_ordine:sanitizeAll'=>'tutti',
		'pagamento:sanitizeAll'=>'tutti',
		'stato:sanitizeAll'=>'tutti',
		'tipo_cliente:sanitizeAll'=>'tutti',
		'email:sanitizeAll'=>'tutti',
		'codice_fiscale:sanitizeAll'=>'tutti',
		'registrato:sanitizeAll'=>'tutti',
		'token:sanitizeAll'=>'token',
		'partial:sanitizeAll'=>'tutti',
		'id_comb:sanitizeAll'=>'tutti',
		'dal:sanitizeAll'=>'tutti',
		'al:sanitizeAll'=>'tutti',
		'nazione_utente:sanitizeAll'=>'tutti',
		'lista_regalo:sanitizeAll'=>'tutti',
		'id_lista_regalo:sanitizeAll'=>'tutti',
		'tipo_ordine:sanitizeAll'=>'tutti',
		'id_lista_insert:sanitizeAll'=>'tutti',
		'from:sanitizeAll'=>'tutti',
		'fattura:sanitizeAll'=>'tutti',
		'gestionale:sanitizeAll'=>'tutti',
		'prezzi:sanitizeAll'=>'I',
		'id_page:sanitizeAll'=>'tutti',
		'titolo:sanitizeAll'=>'tutti',
		'stato_sped:sanitizeAll'=>'tutti',
		'titolo_riga:sanitizeAll'=>'tutti',
		'dalc:sanitizeAll'=>'tutti',
		'alc:sanitizeAll'=>'tutti',
		'numero_documento:sanitizeAll'=>'tutti',
		'nazione_spedizione:sanitizeAll'=>'tutti',
	);
	
	public function __construct($model, $controller, $queryString = array(), $application = null, $action = null)
	{
		if (!v("prezzi_ivati_in_prodotti"))
			$this->argKeys['prezzi:sanitizeAll'] = 'NI';
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->session('admin');
		$this->model();

		$this->model("OrdiniModel");
		$this->model("RigheModel");
		$this->model("RegusersModel");
		$this->model("CorrieriModel");
		$this->model("MailordiniModel");
		$this->model("SpedizioniModel");
		
		$this->s['admin']->check();
		
		$this->m["FattureModel"]->checkFiles();
		
		$this->_topMenuClasses['ordini'] = array("active","in");
		$data['tm'] = $this->_topMenuClasses;
		
		$data["sezionePannello"] = "ecommerce";
		
		$this->append($data);
		
		$this->generaPosizioni();
	}

	public function main()
	{
		$this->shift();
		$this->m[$this->modelName]->cViewStatus = $this->viewStatus;
		
		$this->addBulkActions = false;
		
		$mainMenu = (v("permetti_ordini_offline") && !partial()) ? "add" : "";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>30, 'mainMenu'=>$mainMenu, 'modifyAction'=>'vedi');
		
		$this->mainFields = array(
			'linkcrud',
			'cleanDateTime',
			'OrdiniModel.getNome|orders.id_o',
			'orders.email',
			'orders.tipo_cliente',
			'OrdiniModel.getCFoPIva|orders.id_o',
			'orders.nome_promozione',
			'statoordinelabel',
			'totaleCrud',
		);
		
		$mainButtons = "ledit";
		
		if (v("permetti_ordini_offline"))
			$mainButtons = "ldel,ledit";
		
		$this->mainButtons = $mainButtons;
		
		$this->mainHead = 'N°,Data Ora,Nome/Rag.Soc,Email,Tipo,C.F./P.IVA,Promoz.,Stato,Tot.';
		
		if (v("attiva_ip_location"))
		{
			$this->mainFields[] = 'nazionenavigazione';
			$this->mainHead .= ',Nazione';
		}

		if (v("attiva_liste_regalo"))
		{
			$this->mainFields[] = 'listaregalo';
			$this->mainHead .= ',Lista regalo';
		}

		if (v("attiva_gestione_spedizioni"))
		{
			$this->mainFields[] = 'spedizioneCrud';
			$this->mainHead .= ',Spedizione';
		}
		
		$nazioniDiSpedizioneInOrdini = NazioniModel::getNazioniSpedizioneOrdini();
		
		if (count($nazioniDiSpedizioneInOrdini) > 1)
		{
			$this->mainFields[] = 'nazioneSpedizioneCrud';
			$this->mainHead .= ',Nazione';
		}
		
		if (v("permetti_ordini_offline"))
		{
			$this->mainFields[] = 'tipoOrdineCrud';
			$this->mainHead .= ',Tipo';
		}

		if (v("attiva_agenti"))
		{
			$this->mainFields[] = 'agenteCrud';
			$this->mainHead .= ',Agente';
		}
		
		$this->mainFields[] = 'infoGatewayCrud';
		$this->mainHead .= ',';
		
		$this->inverseColProperties = array(
			null,
			null,
			array(
				'width'	=>	'1%',
			),
		);
		
		if (v("fatture_attive"))
		{
			$this->mainFields[] = 'OrdiniModel.pulsanteFattura|orders.id_o';
			$this->mainHead .= ',Fatt.';
			$this->inverseColProperties[] = array(
				'width'	=>	'1%',
			);
		}
		
		// Colonna del gestionale
		if (GestionaliModel::getModulo()->integrazioneAttiva())
		{
			$this->mainFields[] = 'inviatoGestionaleCrud';
			$this->mainHead .= ',Gest.';
			$this->inverseColProperties[] = array(
				'width'	=>	'1%',
			);
		}
		
		$this->aggiungiintegrazioni();
		
		$this->m[$this->modelName]->clear()->restore(true)->orderBy("orders.data_creazione desc,orders.id_o desc");

		$where = array(
			'id_o'	=>	$this->viewArgs['id_ordine'],
			'stato'	=>	$this->viewArgs['stato'],
			'tipo_cliente'	=>	$this->viewArgs['tipo_cliente'],
			'pagamento'	=>	$this->viewArgs['pagamento'],
			'registrato'	=>	$this->viewArgs['registrato'],
			'nazione_navigazione'	=>	$this->viewArgs['nazione_utente'],
			'id_lista_regalo'	=>	$this->viewArgs['id_lista_regalo'],
			'tipo_ordine'	=>	$this->viewArgs['tipo_ordine'],
			'numero_documento'	=>	$this->viewArgs['numero_documento'],
		);
		
		$this->m[$this->modelName]->aWhere($where);
		
		if (v("nascondi_ordini_pending_in_admin") && $this->viewArgs['stato'] == "tutti")
		{
			$ordiniDaNascondereDiDefault = explode(",", v("stati_ordine_da_nascondere_in_admin"));
			
			$this->m[$this->modelName]->aWhere(array(
				"nin"	=>	array(
					"stato"	=>	sanitizeAllDeep($ordiniDaNascondereDiDefault),
				),
			));
		}
		
		if (strcmp($this->viewArgs['email'],'tutti') !== 0)
		{
			$where = array(
				"lk" => array('orders.email' => $this->viewArgs['email']),
			);

			$this->m[$this->modelName]->aWhere($where);
		}

		if (strcmp($this->viewArgs['codice_fiscale'],'tutti') !== 0)
		{
			$where = array(
				"OR"	=> array(
					"lk" => array('orders.codice_fiscale' => $this->viewArgs['codice_fiscale']),
					" lk" => array('orders.p_iva' => $this->viewArgs['codice_fiscale']),
					)
			);

			$this->m[$this->modelName]->aWhere($where);
		}
		
		if ($this->viewArgs['id_comb'] != "tutti" || $this->viewArgs['id_page'] != "tutti" || $this->viewArgs["stato_sped"] != "tutti" || $this->viewArgs["titolo_riga"] != "tutti")
		{
			$this->m[$this->modelName]->groupBy("orders.id_o")->inner("righe")->on("righe.id_o = orders.id_o");
			
			if ($this->viewArgs['id_comb'] != "tutti")
				$this->m[$this->modelName]->aWhere(array(
					"righe.id_c"	=>	$this->viewArgs['id_comb'],
				));
			
			if ($this->viewArgs['id_page'] != "tutti")
				$this->m[$this->modelName]->aWhere(array(
					"righe.id_page"	=>	$this->viewArgs['id_page'],
				));
			
			if ($this->viewArgs['stato_sped'] != "tutti")
			{
				$this->m[$this->modelName]
					->inner("spedizioni_negozio_righe")->on("spedizioni_negozio_righe.id_r = righe.id_r")
					->inner("spedizioni_negozio")->on("spedizioni_negozio_righe.id_spedizione_negozio = spedizioni_negozio.id_spedizione_negozio")
					->aWhere(array(
						"spedizioni_negozio.stato"	=>	$this->viewArgs['stato_sped'],
					));
			}
			
			if ($this->viewArgs['titolo_riga'] != "tutti")
				$this->m[$this->modelName]->aWhere(array(
					"    AND"	=>	RigheModel::getWhereClauseRicercaLibera($this->viewArgs['titolo_riga']),
				));
		}
		
		if ($this->viewArgs['dal'] != "tutti")
			$this->m[$this->modelName]->sWhere(array("DATE_FORMAT(data_creazione, '%Y-%m-%d') >= ?",array(getIsoDate($this->viewArgs['dal']))));
		
		if ($this->viewArgs['al'] != "tutti")
			$this->m[$this->modelName]->sWhere(array("DATE_FORMAT(data_creazione, '%Y-%m-%d') <= ?",array(getIsoDate($this->viewArgs['al']))));
		
		$this->m[$this->modelName]->setDalAlWhereClause($this->viewArgs['dalc'], $this->viewArgs['alc'], 'data_consegna');
		
		if (strcmp($this->viewArgs['lista_regalo'],'tutti') !== 0)
			$this->m[$this->modelName]->inner(array("lista"))->where(array(
				" OR"	=>	array(
					"lk"	=>	array(
						"liste_regalo.titolo"	=>	$this->viewArgs['lista_regalo'],
					),
					" lk"	=>	array(
						"liste_regalo.codice"	=>	$this->viewArgs['lista_regalo'],
					),
					
				),
			));
		
		if ($this->viewArgs['fattura'] != "tutti")
		{
			if ($this->viewArgs['fattura'] == "F")
				$this->m[$this->modelName]->inner(array("fatture"));
			else if ($this->viewArgs['fattura'] == "N")
				$this->m[$this->modelName]->left(array("fatture"))->sWhere("fatture.id_f IS NULL");
		}
		
		if ($this->viewArgs['gestionale'] != "tutti")
		{
			if ($this->viewArgs['gestionale'] == "I")
				$this->m[$this->modelName]->sWhere("(orders.codice_gestionale != '' or orders.inviato_al_gestionale = 1) and orders.errore_gestionale = ''");
			else if ($this->viewArgs['gestionale'] == "N")
				$this->m[$this->modelName]->sWhere("(orders.codice_gestionale = '' && orders.inviato_al_gestionale = 0)");
		}
		
		if ($this->viewArgs["titolo"] != "tutti")
		{
			$this->m[$this->modelName]->aWhere(array(
				"      AND"	=>	OrdiniModel::getWhereClauseRicercaLibera($this->viewArgs['titolo']),
			));
		}
		
		if ($this->viewArgs["nazione_spedizione"] != "tutti")
		{
			$this->m[$this->modelName]->aWhere(array(
				"   OR"	=>	array(
					"AND"	=>	array(
						"nazione_spedizione"	=>	$this->viewArgs["nazione_spedizione"],
						"id_lista_regalo"		=>	0,
					),
					" AND"	=>	array(
						"nazione_lista_regalo"	=>	$this->viewArgs["nazione_spedizione"],
						"ne"	=>	array(
							"id_lista_regalo"		=>	0,
						),
					),
				),
			));
		}
		
		$this->m[$this->modelName]->save();
		
		$filtroStato = array(
			"tutti"		=>	"Stato ordine",
		) + OrdiniModel::$stati;
		
		$this->filters = array("titolo","dal","al",'id_ordine','titolo_riga','email','codice_fiscale',array("tipo_cliente",null,self::$selectFiltroTipo),array("stato",null,$filtroStato));

		if (v("attiva_ip_location"))
			$this->filters[] = array("nazione_utente",null,$this->m[$this->modelName]->filtroNazioneNavigazione(new OrdiniModel()));

		if (v("attiva_liste_regalo"))
			$this->filters[] = "lista_regalo";

		if (v("permetti_ordini_offline"))
			$this->filters[] = array("tipo_ordine",null,array(
				"tutti"	=>	gtext("Tipo ordine"),
				"W"		=>	gtext("Ordini WEB"),
				"B"		=>	gtext("Ordini Backend"),
			));

		if (v("fatture_attive"))
			$this->filters[] = array("fattura",null,array(
				"tutti"	=>	gtext("Fatturazione"),
				"F"		=>	gtext("Fatturati"),
				"N"		=>	gtext("NON fatturato"),
			));

		// Colonna del gestionale
		if (GestionaliModel::getModulo()->integrazioneAttiva())
			$this->filters[] = array("gestionale",null,array(
				"tutti"	=>	gtext("Gestionale"),
				"I"		=>	gtext("Inviato"),
				"N"		=>	gtext("NON inviato"),
			));
		
		if (v("attiva_gestione_spedizioni"))
		{
			$filtroStato = array(
				"tutti"		=>	"Stato spedizione",
			) + $this->m("SpedizioninegoziostatiModel")->selectTendina(false);
			
			$this->filters[] = array("stato_sped",null,$filtroStato);
		}
		
		if (count($nazioniDiSpedizioneInOrdini) > 1)
		{
			$this->filters[] = array("nazione_spedizione",null,array("tutti" => "Nazione spedizione") + $nazioniDiSpedizioneInOrdini);
		}
		
		$this->getTabViewFields("main");

		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		if( !session_id() )
			session_start();
		
		$record = $this->m[$this->modelName]->selectId((int)$id);
		
		$this->m[$this->modelName]->addSoftCondition("both",'checkMail',"email|".gtext("Si prega di ricontrollare <b>l'indirizzo Email</b>").'<div rel="hidden_alert_notice" style="display:none;">email</div>');

		$idUser = !empty($record) ? (int)$record["id_user"] : 0;
		
		$lingua = $this->m["RegusersModel"]->getLingua((int)$idUser);
		
		$this->_posizioni['main'] = 'class="active"';
		
		$this->menuLinksInsert = partial() ? "save" : $this->menuLinksInsert;
		$this->menuLinks = "torna_ordine,save";
		
		$this->shift(2);
		
		$fields = 'tipo_cliente,nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,citta,telefono,email,indirizzo_spedizione,cap_spedizione,provincia_spedizione,nazione_spedizione,citta_spedizione,telefono_spedizione,stato,nazione,pec,codice_destinatario,pagamento,dprovincia,dprovincia_spedizione,note,note_interne,link_tracking';
		
		if (OpzioniModel::isAttiva("CAMPI_SALVATAGGIO_SPEDIZIONE", "destinatario_spedizione"))
			$fields .= ",destinatario_spedizione";
		
		if (OpzioniModel::isAttiva("CAMPI_FORM_CHECKOUT", "fattura"))
			$fields .= ",fattura";
		
		if (v("permetti_modifica_cliente_in_ordine"))
			$fields .= ",id_user,id_spedizione";
		
		if (v("attiva_liste_regalo"))
			$fields .= ",dedica,firma";
		
		if (v("permetti_ordini_offline") && (!$id || OrdiniModel::g()->isDeletable((int)$id)))
		{
			$fields .= ",id_corriere,id_p";
			
			$fields .= ",id_iva";
			$this->disabledFields = "id_iva";
		}
		
		if (v("attiva_gestione_spedizionieri"))
			$fields .= ",id_spedizioniere";
		
		if ($queryType == "insert" || (isset($record["tipo_ordine"]) && $record["tipo_ordine"] != "W"))
		{
			if (v("attiva_da_consegna_in_ordine"))
				$fields .= ",data_consegna";
			
			if (v("attiva_gestione_commessi"))
				$fields .= ",id_commesso";
		}
		
		if (VariabiliModel::attivaCodiceGestionale())
			$fields .= ",codice_gestionale_cliente,codice_gestionale_spedizione";
		
		if ($this->campiForm)
			$fields = $this->campiForm;
		
		$this->functionsIfFromDb = array(
			"data_consegna"	=>	"fakeDataToBlank",
		);
		
		$this->formDefaultValues = array(
			"data_consegna"	=>	"",
			"nazione"		=>	v("nazione_default"),
		);
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		$this->m[$this->modelName]->setValue("lingua", $lingua);
		
		if ($queryType == "insert" && $this->viewArgs['id_lista_insert'] != "tutti")
			$this->m[$this->modelName]->setValue("id_lista_regalo", $this->viewArgs['id_lista_insert']);
		
		if ($this->disabledFields)
			$this->m[$this->modelName]->delFields($this->disabledFields);
		
		foreach ($this->campiAggiuntiviOrdine as $k => $v)
		{
			$this->m[$this->modelName]->setValue($k, $v);
		}
		
		$this->getTabViewFields("form");
		
		parent::form($queryType, $id);
		
		$data["tipoSteps"] = "modifica";
		$data["ordine"] = $this->m["OrdiniModel"]->selectId((int)$id);
		$data["mail_altre"] = $this->m["MailordiniModel"]->estraiMailOrdine((int)$id, "ORDINE");
		
		$this->append($data);
	}
	
	protected function aggiungiUrlmenuScaffold($id)
	{
		$this->scaffold->mainMenu->links['stampa_pdf_ordine']['url'] = 'stampapdf/'.(int)$id;
		$this->scaffold->mainMenu->links['invia_pdf_ordine']['url'] = 'inviapdf/'.(int)$id;
	}
	
	public function integrazioni($id = 0)
	{
		Helper_Menu::$htmlLinks["torna_ordine"]["url"] = 'vedi/'.(int)$id;
		
		parent::integrazioni($id);
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["OrdiniModel"]->titolo((int)$id);
		
		$data["tipoSteps"] = "vedi";
		$this->append($data);
	}
	
	public function inviipdf($id)
	{
		$this->mainShift = 1;
		
		if (!v("permetti_ordini_offline") || OrdiniModel::tipoOrdine((int)$id) == "W")
			$this->redirect("ordini/vedi/".(int)$id);
		
		$this->m["OrdiniModel"]->checkAggiorna((int)$id);
		
		Helper_Menu::$htmlLinks["torna_ordine"]["url"] = 'vedi/'.(int)$id;
		
		$this->_posizioni['inviipdf'] = 'class="active"';
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_o";
		
		$mainModelName = $this->modelName;
		
		$this->modelName = "OrdinipdfModel";
		
		$this->addBulkActions = false;
		$this->colProperties = array();
		
		$this->mainFields = array("cleanDateTime", "linkPdfCrud", "inviatoCrud");
		$this->mainHead = "Data PDF,File PDF,Inviato";
		
		$pulsantiMenu = "torna_ordine";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>$pulsantiMenu,'mainAction'=>"inviipdf/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m($this->modelName)->where(array("id_o"=>$clean['id']))->orderBy("data_creazione desc")->convert()->save();
		
		$this->getTabViewFields("inviipdf");
		
		$this->mainButtons = "";
		
		parent::main();
		
		$data["id_lista_regalo"] = $this->m["OrdiniModel"]->whereId($clean['id'])->field("id_lista_regalo");
		$data["titoloRecord"] = $this->m[$mainModelName]->titolo($clean['id']);
		$data["tipoSteps"] = "modifica";
		$data["mail_altre"] = $this->m["MailordiniModel"]->estraiMailOrdine($clean["id"], "ORDINE");
		$data["ordine"] = $this->m["OrdiniModel"]->selectId($clean['id']);
		
		$this->append($data);
	}
	
	public function righe($id = 0)
	{
		Helper_List::$tableAttributes["class"] .= " gestione_righe_ordine";

		$this->mainShift = 1;
		
		if (!v("permetti_ordini_offline") || OrdiniModel::tipoOrdine((int)$id) == "W")
			$this->redirect("ordini/vedi/".(int)$id);
		
		$this->m["OrdiniModel"]->checkAggiorna((int)$id);
		
		Helper_Menu::$htmlLinks["torna_ordine"]["url"] = 'vedi/'.(int)$id;
		
		$this->_posizioni['righe'] = 'class="active"';
		
// 		$data["orderBy"] = $this->orderBy = "id_order";
		
		$this->shift(1);
		
		$clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_o";
		
		$this->mainButtons = "ldel";
		
		$mainModelName = $this->modelName;
		
		$this->modelName = "RigheModel";
		
		if (!OrdiniModel::g()->isDeletable((int)$id))
		{
			$this->addBulkActions = false;
			$this->colProperties = array();
		}
		
		$this->rowAttributes = array(
			"class"	=>	"listRow id_tipo_riga_;righe.id_riga_tipologia;",
		);
		
		$this->mainFields = array("immagineCrud", "titoloCrud", "attributiCrud", "codiceCrud", "prezzoInteroCrud", "scontoCrud", "prezzoScontatoCrud", "quantitaCrud", ";righe.iva;%", "evasaCrud", "acquistabileCrud");
		$this->mainHead = "Immagine,Articolo,Variante,Codice,Prezzo pieno,Sconto (%),Prezzo scontato,Quantità,Aliquota,Evasa,Acq.";
		
		$pulsantiMenu = "torna_ordine";
		
		if (OrdiniModel::g()->isDeletable((int)$id))
			$pulsantiMenu .= ",save_righe_ordini";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>$pulsantiMenu,'mainAction'=>"righe/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->clear()->select("righe.*,pages.attivo")->left("pages")->on("pages.id_page = righe.id_page")->left("righe_tipologie")->on("righe_tipologie.id_riga_tipologia = righe.id_riga_tipologia")->orderBy("righe_tipologie.id_order,righe.id_order")->where(array("id_o"=>$clean['id']))->convert()->save();
		
		$this->getTabViewFields("righe");
		
		Helper_Menu::$htmlLinks["save_righe_ordini"]["attributes"] .= " id-ordine='".(int)$id."'";
		
		parent::main();
		
		$data["id_lista_regalo"] = $this->m["OrdiniModel"]->whereId($clean['id'])->field("id_lista_regalo");
		$data["titoloRecord"] = $this->m[$mainModelName]->titolo($clean['id']);
		$data["tipoSteps"] = "modifica";
		$data["tipologie"] = $this->m("RighetipologieModel")->clear()->orderBy("id_order desc")->send(false);
		
		$data["mail_altre"] = $this->m["MailordiniModel"]->estraiMailOrdine($clean["id"], "ORDINE");
		
		$data["ordine"] = $this->m["OrdiniModel"]->selectId($clean['id']);
		
		$this->append($data);
	}
	
	public function setstato($id_o, $stato)
	{
		$this->shift(2);
		
		$this->clean();
		
		$res = $this->m["OrdiniModel"]->clear()->where(array("id_o"=>(int)$id_o))->send();
		
		if (isset(OrdiniModel::$stati[$stato]) && count($res) > 0)
		{
			$this->m["OrdiniModel"]->setValues(array(
				"stato"	=>	$stato,
			));
			
			if ($this->m["OrdiniModel"]->update((int)$id_o) && !isset($_GET["no_mail_stato"]))
			{
				switch ($stato)
				{
					case "pending":
						$this->m["OrdiniModel"]->mandaMail($id_o);
						break;
					case "completed":
						$this->m["OrdiniModel"]->mandaMailCompleted($id_o);
						break;
					case "closed":
						$this->m["OrdiniModel"]->mandaMailClosed($id_o);
						break;
					case "deleted":
						$this->m["OrdiniModel"]->mandaMailDeleted($id_o);
						break;
					default:
						$this->m["OrdiniModel"]->mandaMailStatoGenerico($id_o, $stato);
						break;
				}
			}
		}
		
		$this->redirect($this->applicationUrl.$this->controller."/".$this->defaultAction."/".(int)$id_o.$this->viewStatus);
	}
	
	public function vediresponse($cart_uid)
	{
		$this->model("OrdiniresponseModel");
		
		$data["responses"] = $this->m["OrdiniresponseModel"]->where(array(
			"cart_uid"	=>	$cart_uid,
		))->orderBy("id_order_gateway_response desc")->findAll(false);
		
		$this->append($data);
		$this->load("vedi_response");
	}
	
	public function vediscriptpixel($id_pixel_evento)
	{
		$this->model("PixeleventiModel");
		
		$data["record_evento"] = $this->m["PixeleventiModel"]->clear()->select("*")->inner(array("pixel"))->where(array(
			"id_pixel_evento"	=>	(int)$id_pixel_evento,
		))->first();
		
		$this->append($data);
		$this->load("vedi_script_pixel");
	}
	
	public function nonannullato($id_o)
	{
		$this->shift(1);
		
		$this->clean();
		
		$ordine = $this->m("OrdiniModel")->selectId((int)$id_o);
		
		if (!empty($ordine) && $ordine["annullato"] && $ordine["data_annullamento"] && date("Y-m-d", strtotime($ordine["data_annullamento"])) == date("Y-m-d"))
		{
			$this->m("OrdiniModel")->query(array(
				"update orders set annullato = 0, data_annullamento = NULL, time_annullamento = 0, time_annullamento_annullato = ? where id_o = ?",
				array(
					time(),
					(int)$id_o
				)
			));
		}
	}
	
	public function vedi($id_o)
	{
		@session_start();
		
		$this->shift(1);
		
// 		if (!$this->s['admin']->checkCsrf($this->viewArgs['token'])) $this->redirect('panel/main',2,'wrong token');
		
		$data['notice'] = null;
		
		$clean["id_o"] = $data["id"] = (int)$id_o;
// 		$clean["admin_token"] = $data["admin_token"] = sanitizeAll($admin_token);
		
		$data["notice_send"] = null;
		
		if (isset($_GET["action"]))
		{
			$this->m["OrdiniModel"]->mandaMail($clean["id_o"]);
			flash("notice_send",$this->m["OrdiniModel"]->notice);
			$this->redirect($this->applicationUrl.$this->controller."/vedi/".$clean["id_o"].$this->viewStatus);
		}
		
		if (isset($_GET["invia_fattura"]))
		{
			$this->m["OrdiniModel"]->mandaMailFattura($clean["id_o"]);
			flash("notice_send",$this->m["OrdiniModel"]->notice);
			$this->redirect($this->applicationUrl.$this->controller."/vedi/".$clean["id_o"].$this->viewStatus);
		}
		
		$res = $this->m["OrdiniModel"]->clear()
							->where(array("id_o" => $clean["id_o"]))
							->send();
		
		$data["righeOrdine"] = $this->m["RigheModel"]->clear()->where(array(
			"id_o"=>$clean["id_o"],
			"ne"		=>	array(
				"righe.acconto"	=>	1,
			),
		))->left("righe_tipologie")->on("righe_tipologie.id_riga_tipologia = righe.id_riga_tipologia")->orderBy("righe_tipologie.id_order,righe.id_order")->send();
		
		$this->helper("Menu",$this->applicationUrl.$this->controller,"panel");
		
		$this->h["Menu"]->links['edit']['url'] = 'form/update/'.$clean["id_o"];
		$this->h["Menu"]->links['edit']['title'] = "Modifica ordine";
		
		$this->h["Menu"]->links['manda_mail']['url'] = 'vedi/'.$clean["id_o"];
		$this->h["Menu"]->links['manda_mail']['class'] = 'mainMenuItem';
// 		$this->h["Menu"]->links['manda_mail']['text'] = 'Invia mail';
		$this->h["Menu"]->links['manda_mail']['queryString'] = '?n=y&action=send';
		$this->h["Menu"]->links['manda_mail']['icon'] = $this->baseUrl.'/Public/Img/Icons/mail_small.png';
		$this->h["Menu"]->links['manda_mail']['title'] = "Invia nuovamente la mail dell'ordine al cliente";
		
		$menuButtons = $this->viewArgs["from"] == "liste" ? "edit,manda_mail" : "back,edit,manda_mail";
		
		$data["menu"] = $this->h["Menu"]->render($menuButtons);
		
		if (count($res) > 0)
		{
			$data["integrazioni"] = IntegrazioniModel::getElencoPulsantiIntegrazione($res[0]["orders"]["id_o"], $this->controller);
			
			$this->_posizioni['main'] = 'class="active"';
			$data['posizioni'] = $this->_posizioni;
			
			$data["cliente"] = null;
			
			if ($res[0]["orders"]["id_user"] > 0)
			{
				$data["cliente"] = $this->m["RegusersModel"]->selectId($res[0]["orders"]["id_user"]);
			}
			
			$data["ordine"] = $res[0]["orders"];
			
			$data["tipoOutput"] = "web";
			
			$data["corriere"] = $this->m["CorrieriModel"]->selectId($data["ordine"]["id_corriere"]);
			
			$data["fatture"] = $this->m["FattureModel"]->clear()->where(array("id_o"=>$clean["id_o"]))->send();
			
			$data["mail_fatture"] =  $this->m["MailordiniModel"]->clear()->where(array(
				"id_o"	=>	$clean["id_o"],
				"tipo"=>	"F",
			))->orderBy("data_creazione desc")->send(false);
			
			$data["mail_altre"] = $this->m["MailordiniModel"]->estraiMailOrdine($clean["id_o"], "ORDINE");
			
// 			$data["mail_altre"] = $this->m["MailordiniModel"]->clear()->where(array(
// 				"id_o"	=>	$clean["id_o"],
// 				"tipologia"	=>	"ORDINE",
// 			))->orderBy("data_creazione desc")->send(false);
			
			$data["tipoSteps"] = "vedi";
			$this->append($data);
			
			$this->load('vedi');
		}
	}
	
	public function stampapdf($id = 0, $idPdf = 0)
	{
		if (!v("permetti_ordini_offline"))
			$this->responseCode(403);

		$this->clean();
		
		$values = $this->m("OrdinipdfModel")->generaORestituisciPdfOrdine($id, $idPdf);
		
		$folder = LIBRARY . "/media/Pdf";
		
		if (is_array($values) && !empty($values) && file_exists($folder."/".$values["filename"]))
		{
			header('Content-type: application/pdf');
			header('Content-Disposition: inline; filename='.$values["titolo"]);
			readfile($folder."/".$values["filename"]);
			
			$this->m("OrdinipdfModel")->eliminaPdfNonInviati();
		}
		else
			$this->responseCode(403);
	}
	
	public function inviapdf($id)
	{
		if (!v("permetti_ordini_offline"))
			$this->responseCode(403);
		
		$this->shift(1);
		
		$this->clean();
		
		if ($this->m("OrdinipdfModel")->inviaPdf($id))
			flash("notice", "<div class='alert alert-success'>".gtext("Email inviata correttamente")."</div>");
		
		$this->redirect($this->applicationUrl.$this->controller."/form/update/".(int)$id.$this->viewStatus);
	}
}
