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

class ImpostazioniController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "utenti";

	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$this->menuLinks = "save";
		
		$this->m[$this->modelName]->setValuesFromPost('nome_sito,title_home_page,meta_description,keywords,iva,mail_invio_ordine,mail_invio_conferma_pagamento,analytics,smtp_from,smtp_nome,bcc,usa_smtp,smtp_host,smtp_port,smtp_user,smtp_psw,usa_sandbox,paypal_seller,paypal_sandbox_seller,esponi_prezzi_ivati,mostra_scritta_iva_inclusa,spedizioni_gratuite_sopra_euro,redirect_immediato_a_paypal,manda_mail_fattura_in_automatico,mailchimp_list_id,mailchimp_api_key');
		
		parent::form($queryType, $id);
	}
	
	public function variabili($id = 0)
	{
		$this->model("VariabiliModel");
		
		$this->_posizioni['variabili'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $data["id"] = (int)$id;
		
		if (v("lista_variabili_gestibili"))
		{
			$variabili = explode(",", v("lista_variabili_gestibili"));
			
			if (isset($_POST["updateAction"]))
			{
				foreach ($variabili as $v)
				{
					if (isset($_POST[$v]))
					{
						VariabiliModel::setValore($v, $_POST[$v]);
					}
				}
			}
		}
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'save','mainAction'=>"variabili/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->mainView = "variabili";
		
		$form = new Form_Form("/impostazioni/variabili/".$clean['id'],array("updateAction"=>"Salva"), "POST");
		
		$entries = array();
		$values = array();
		
		VariabiliModel::ottieniVariabili();
		
		if (v("lista_variabili_gestibili"))
		{
			$struct = $this->m["VariabiliModel"]->strutturaForm();
			
			foreach ($variabili as $v)
			{
				if (isset($struct[$v]))
					$entries[$v] = $struct[$v];
				else
					$entries[$v] = array();
				
				$entries[$v]["className"] = "form-control";
				
				$values[$v] = v($v);
			}
		}
		
		$form->setEntries($entries);
		
		$data["formVariabili"] = $form->render($values);
		
		parent::main();
		
		$data["titoloRecord"] = "Impostazioni";
		
		$this->append($data);
	}
}
