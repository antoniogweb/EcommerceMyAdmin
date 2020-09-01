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

class ImpostazioniModel extends GenericModel {

	public static $valori = null;
	
	public function __construct() {
		$this->_tables='impostazioni';
		$this->_idFields='id_imp';

// 		$this->_idOrder = 'id_order';
		$this->_lang = 'It';
		
		parent::__construct();
		
	}
	
	public function getImpostazioni()
	{
		$res = $this->send();
		
		if (count($res) > 0)
		{
			self::$valori = $res[0]["impostazioni"];
		}
	}
	
	public function setFormStruct()
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'mail_invio_ordine'		=>	array(
					'labelString'=>	"Mail a cui inviare l'avviso di nuovo ordine",
				),
				'mail_invio_conferma_pagamento'		=>	array(
					'labelString'=>	"Mail a cui inviare l'avviso che l'ordine è stato pagato (paypal, carta di credito)",
				),
				'smtp_from'		=>	array(
					'labelString'=>	'Campo DA (FROM) nelle mail di sistema',
				),
				'smtp_nome'		=>	array(
					'labelString'=>	'Campo NOME (FROM NAME) nelle mail di sistema',
				),
			),
		);
	}
}
