<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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

class FormModel extends GenericModel {

	public function setFormStruct($id = 0)
	{
		$record = $this->selectId((int)$id);
		
		$idUser = (!empty($record)) ? $record["id_user"] : 0;
		
		if (isset($_POST["id_user"]) && (int)$_POST["id_user"] && RegusersModel::g()->whereId((int)$_POST["id_user"])->rowNumber())
			$idUser = $_POST["id_user"];
		
		$linkAggiungi = ""; //"<a class='iframe link_aggiungi' href='".Url::getRoot()."regusers/form/insert/0?partial=Y&nobuttons=Y'><i class='fa fa-plus-square-o'></i> ".gtext("Crea nuovo")."</a>";
		
		$arrayAnonimo = (v("permetti_acquisto_anonimo") || ($id && !$idUser)) ? array("0" => gtext("-- nessun cliente selezionato --")) : array();
		
		$opzioniIdUser = $arrayAnonimo + $this->selectUtenti($idUser, v("utilizza_ricerca_ajax_su_select_2_clienti"));
		
		$opzioniIdSpedizione = array("0" => gtext("-- non specificato --")) + $this->getTendinaIndirizzi($idUser);
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'username'		=>	array(
					'labelString'=>	'Email',
					'className'	=>	'for_print form-control',
					'attributes'=>	'autocomplete="new-password"',
				),
				'has_confirmed'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Utente attivo',
					'className'	=>	'for_print form-control',
					'options'	=>	array('sì'=>'0','no'=>'1'),
				),
				'stato'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Stato',
					'className'	=>	'for_print form-control',
					'options'	=>	OrdiniModel::$stati,
// 					'options'	=>	array('pending'=>'In lavorazione','completed'=>'Completo','deleted'=>'Eliminato'),
					'reverse'	=>	"yes",
				),
				'pagamento'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Tipo di pagamento',
					'className'	=>	'for_print form-control',
					'options'	=>	OrdiniModel::$elencoPagamenti,
// 					'options'	=>	array('pending'=>'In lavorazione','completed'=>'Completo','deleted'=>'Eliminato'),
					'reverse'	=>	"yes",
				),
				'password'			=>	array(
					'type'	=>	'Password',
					'entryClass'	=>	'for_print form_input_text',
					'className'		=> 'form-control',
					'attributes'=>	'autocomplete="new-password"',
				),
				'confirmation'		=>	array(
					'labelString'	=>	'Conferma la password',
					'type'			=>	'Password',
					'entryClass'	=>	'for_print form_input_text',
					'className'		=> 'form-control',
					'attributes'=>	'autocomplete="new-password"',
				),
				'tipo_cliente'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Tipologia cliente',
					'options'	=>	array("privato"=>"Privato","azienda"=>"Azienda","libero_professionista"=>"Libero professionista"),
					'className'	=>	'radio_cliente for_print form-control',
					'reverse'	=>	'yes',
				),
				'nome'		=>	array(
					'labelString'=>	'Nome',
					'entryClass'	=>	'nome form_input_text',
					'className'	=>	'for_print form-control',
				),
				'cognome'		=>	array(
					'labelString'=>	'Cognome',
					'entryClass'	=>	'cognome form_input_text',
					'className'	=>	'for_print form-control',
				),
				'ragione_sociale'		=>	array(
					'labelString'=>	'Ragione sociale',
					'entryClass'	=>	'ragione_sociale form_input_text',
					'className'	=>	'for_print form-control',
					'wrap'		=>	array(
						null,null,null,null,
						"<span class='for_screen'>;;value;;</span>"
					),
				),
				'ragione_sociale_2'		=>	array(
					'labelString'=>	'Estensione Ragione sociale',
					'entryClass'	=>	'ragione_sociale_2 form_input_text',
					'className'	=>	'for_print form-control',
					'wrap'		=>	array(
						null,null,null,null,
						"<span class='for_screen'>;;value;;</span>"
					),
				),
				'p_iva'		=>	array(
					'labelString'=>	'Partiva iva',
					'entryClass'	=>	'p_iva form_input_text',
					'className'	=>	'for_print form-control',
				),
				'codice_fiscale'		=>	array(
					'labelString'=>	'Codice fiscale',
					'className'	=>	'for_print form-control',
				),
				'indirizzo'		=>	array(
					'labelString'=>	'Indirizzo',
					'className'	=>	'for_print form-control',
				),
				'cap'		=>	array(
					'labelString'=>	'Cap',
					'className'	=>	'for_print form-control',
				),
				'provincia'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Provincia',
					'className'	=>	'for_print form-control',
					'options'	=>	ProvinceModel::g()->selectTendina(),
					'reverse' => 'yes',
					"entryClass"	=>	"form_input_text box_provincia",
				),
				'dprovincia'		=>	array(
					'labelString'=>	'Provincia',
					'className'	=>	'for_print form-control',
					"entryClass"	=>	"form_input_text box_dprovincia",
				),
				'citta'		=>	array(
					'labelString'=>	'Città',
					'className'	=>	'for_print form-control',
				),
				'telefono'		=>	array(
					'labelString'=>	'Telefono',
					'className'	=>	'for_print form-control',
				),
				'email'		=>	array(
					'labelString'=>	'Email',
					'className'	=>	'for_print form-control',
				),
				'indirizzo_spedizione'		=>	array(
					'labelString'	=>	'Indirizzo di spedizione',
					'className'		=>	'indirizzo_spedizione for_print form-control',
				),
				'id_classe'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Classe di sconto',
					'options'	=>	$this->selectClasseSconto(),
					'reverse' => 'yes',
				),
				'nazione_spedizione'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectNazione(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'provincia_spedizione'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Provincia spedizione',
					'className'	=>	'for_print form-control',
					'options'	=>	ProvinceModel::g()->selectTendina(),
					'reverse' => 'yes',
					"entryClass"	=>	"form_input_text provincia_spedizione",
				),
				'dprovincia_spedizione'		=>	array(
					'labelString'=>	'Provincia spedizione',
					'className'	=>	'for_print form-control',
					"entryClass"	=>	"form_input_text dprovincia_spedizione",
				),
				'nazione'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectNazione(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'id_ruolo'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectRuoli(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'labelString'=>	'Ruolo',
				),
				'id_tipo_azienda'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectTipiAziende(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'labelString'=>	'Tipo azienda',
				),
				'id_user'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Cliente",
					"options"	=>	$opzioniIdUser,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'entryAttributes'	=>	array(
						"select2"	=>	VariabiliModel::getUrlAjaxClienti(),
					),
					'wrap'	=>	array(null,null,"$linkAggiungi<div>","</div>"),
				),
				'id_spedizione'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Spedizione",
					"options"	=>	$opzioniIdSpedizione,
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
				),
				'id_iva'		=>	array(
					'type'		=>	'Select',
					'entryClass'	=>	'form_input_text help_iva',
					'labelString'=>	'Aliquota Iva',
					'options'	=>	$this->selectIva(),
					'reverse' => 'yes',
				),
				'id_corriere'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Corriere (Espresso / Standard / ...)',
					'options'	=>	CorrieriModel::g(false)->selectTendina(),
					'reverse' => 'yes',
				),
				'id_spedizioniere'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Spedizioniere (GLS / BRT / ...)',
					'options'	=>	SpedizionieriModel::g(false)->selectTendina(),
					'reverse' => 'yes',
				),
				'id_p'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Promo / Coupon',
					'options'	=>	$this->selectCouponUsabile($id),
					'reverse' => 'yes',
					'entryAttributes'	=>	array(
						"select2"	=>	"",
					),
				),
// 				'id_user'	=>	array(
// 					'type'		=>	'Hidden'
// 				),
				'lingua'	=>	array(
					"type"	=>	"Select",
					"options"	=>	LingueModel::getValori(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'note'		=>	array(
					'labelString'=>	'Note cliente',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Sono note inserite dal cliente o che il cliente può vedere nella pagina dell'ordine.")."</div>"
					),
				),
				'note_interne'		=>	array(
					'labelString'=>	'Note interne',
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Sono note visibili solo dal backend (il cliente non le vedrà mai).")."</div>"
					),
				),
				'agente'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Il cliente è un agente?',
					'className'	=>	'for_print form-control',
					'options'	=>	array(
						"0"	=>	"No",
						"1"	=>	"Sì",
					),
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se impostato su sì, si attiverà una nuova scheda per la gestione dei codici coupon dell'agente.")."</div>"
					),
					'reverse'	=>	"yes",
				),
				'tipologia'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Tipologia spedizione',
					'className'	=>	'for_print form-control',
					'options'	=>	array(
						SpedizioninegozioModel::TIPOLOGIA_PORTO_FRANCO	=>	"Tutto a carico del negozio",
						SpedizioninegozioModel::TIPOLOGIA_PORTO_FRANCO_CONTRASSEGNO	=>	"Spedizione in contrassegno",
					),
					'reverse'	=>	"yes",
				),
				'id_regione'		=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Regione',
					'options'	=>	$this->selectRegione(),
					'reverse' => 'yes',
				),
			),
		);
		
		// Override la struttura del form
		$this->overrideFormStruct();
	}
	
	public function selectRuoli($frontend = false)
	{
		$r = new RuoliModel();
		
		return $r->selectTipi(false);
	}
	
	public function selectTipiAziende($frontend = false)
	{
		$r = new TipiaziendaModel();
		
		return $r->selectTipi(false);
	}
	
	public function selectClasseSconto()
	{
		$cl = new ClassiscontoModel();
		
		return array("0" => "-- Seleziona --") + $cl->clear()->select("id_classe,concat(titolo,' (',sconto,' %)') as label")->orderBy("sconto")->toList("id_classe","aggregate.label")->send();
	}
	
	public function selectCouponUsabile($id = 0)
	{
		if ($this->_tables != "orders")
			return [];
		
		$p = new PromozioniModel();
		
		$res = $p->clear()->where(array(
// 			"FONTE"	=>	"MANUALE",
// 			"id_r"	=>	0,
			"attivo"=>	"Y",
			"lte"	=>	array(
				"dal"	=>	date("Y-m-d"),
			),
			"gte"	=>	array(
				"al"	=>	date("Y-m-d"),
			),
		))->sWhere("numero_utilizzi > 0")->send();
		
		$selectTendina = array();
		
		$tabellaUtilizzoPromo = PromozioniModel::gTabellaPromoEuroUsati($id);
		
// 		print_r($tabellaUtilizzoPromo);die();
		
		foreach ($res as $r)
		{
			$valoreTendina = $r["promozioni"]["fonte"]." - ".$r["promozioni"]["titolo"]." (".$r["promozioni"]["codice"].")";
			
			if ($r["promozioni"]["email"])
				$valoreTendina .= " - ".$r["promozioni"]["email"];
			
// 			if ($r["promozioni"]["tipo_sconto"] == "ASSOLUTO" && PromozioniModel::gNumeroEuroRimasti($r["promozioni"]["id_p"], $id) <= 0)
// 				continue;
			
			if ($r["promozioni"]["tipo_sconto"] == "ASSOLUTO" && $r["promozioni"]["tipo_credito"] != "INFINITO" && isset($tabellaUtilizzoPromo[$r["promozioni"]["id_p"]]) && $tabellaUtilizzoPromo[$r["promozioni"]["id_p"]] >= $r["promozioni"]["sconto"])
				continue;
			
			$selectTendina[$r["promozioni"]["id_p"]] = $valoreTendina;
		}
		
		if ($id)
		{
			$ordine = $this->selectId($id);
			
			if (!empty($ordine) && isset($ordine["id_p"]) && $ordine["id_p"])
			{
				$promo = PromozioniModel::g(false)->selectId((int)$ordine["id_p"]);
				
				if (!empty($promo) && !isset($selectTendina[$ordine["id_p"]]))
					$selectTendina[$ordine["id_p"]] = $r["promozioni"]["fonte"]." - ".$promo["titolo"]." (".$promo["codice"].")";
			}
		}
		
		return array("0" => gtext("-- Seleziona --")) + $selectTendina;
	}
}
