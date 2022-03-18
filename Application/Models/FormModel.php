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

class FormModel extends GenericModel {

	public function setFormStruct($id = 0)
	{

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
					'labelString'=>	'Provincia',
					'className'	=>	'for_print form-control',
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
					'type'		=>	'Hidden'
				),
				'lingua'	=>	array(
					"type"	=>	"Select",
					"options"	=>	LingueModel::getValori(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
		);

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

}
