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

class ImpostazioniController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "utenti";

	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$this->menuLinks = "save";
		
		$fieldsEcommerce = "";
		
		if (v("attiva_menu_ecommerce"))
			$fieldsEcommerce = "usa_sandbox,paypal_seller,paypal_sandbox_seller,esponi_prezzi_ivati,mostra_scritta_iva_inclusa,spedizioni_gratuite_sopra_euro,redirect_immediato_a_paypal,manda_mail_fattura_in_automatico,";
		
		$fields = 'nome_sito,title_home_page,meta_description,keywords,iva,mail_invio_ordine,mail_invio_conferma_pagamento,analytics,smtp_from,smtp_nome,reply_to_mail,bcc,usa_smtp,smtp_host,smtp_port,smtp_user,smtp_psw,smtp_secure,'.$fieldsEcommerce.'mailchimp_list_id,mailchimp_api_key';
		
		if (v("campi_impostazioni"))
			$fields = v("campi_impostazioni");
			
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
		
		$this->setMenuClass("variabili");
	}
	
	public function tema()
	{
		if (!v("permetti_cambio_tema"))
			die();
		
		$data["elencoTemi"] = Tema::getElencoTemi();
		
		$this->setMenuClass("temi");
		
		$this->append($data);
		
		$this->load("tema");
	}
	
	public function attivatema($tema)
	{
		if (!v("permetti_cambio_tema"))
			die();
		
		$this->clean();
		
		if (Tema::check($tema))
		{
			VariabiliModel::setValore("theme_folder", $tema);
			
			if (isset($_COOKIE["demo_theme"]))
				setcookie ("demo_theme", "", time() - 3600,"/");
		}
	}
	
	public function variabili($id = 0)
	{
		$this->model("VariabiliModel");
		
		$this->_posizioni['variabili'] = 'class="active"';
		
		$this->shift(1);
		
		$clean['id'] = $data["id"] = (int)$id;
		
		$data["notice"] = null;
		
		if (v("lista_variabili_gestibili"))
		{
			$variabili = explode(",", v("lista_variabili_gestibili"));
			
			if (isset($_POST["updateAction"]))
			{
				foreach ($variabili as $v)
				{
					if (isset($_POST[$v]))
						VariabiliModel::setValore($v, $_POST[$v]);
				}
				
				$data["notice"] = "<div class='alert alert-success'>operazione eseguita!</div>";
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
	
	public function svuotacache()
	{
		$this->clean();
		
		if (defined("CACHE_FOLDER"))
		{
			Cache::$cacheFolder = Domain::$parentRoot."/".CACHE_FOLDER;
			Cache::$cacheMinutes = 0;
			Cache::$cleanCacheEveryXMinutes = 0;
			Cache::deleteExpired();
		}
		
		if (v("attiva_cache_immagini"))
		{
			$dir = Domain::$parentRoot."/thumb";
			
			GenericModel::eliminaCartella($dir);
		}
	}
}
