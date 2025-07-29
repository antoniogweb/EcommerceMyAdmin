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

class TraduzionicorrezioniModel extends GenericModel
{
	public static $correzioni = null;
	
	public static $tipoCorrezione = array();
	
	public function __construct() {
		$this->_tables='traduzioni_correzioni';
		$this->_idFields='id_t_c';
		
		parent::__construct();
		
		self::$tipoCorrezione = array(
			0 => gtext('Intero testo'),
			2 => gtext('Porzione di testo'),
		);
	}

	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'parola_tradotta_da_correggere'	=>	array(
					'labelString'=>	'Testo da tradurre',
				),
				'parola_tradotta_corretta'	=>	array(
					'labelString'=>	'Testo tradotto',
				),
				'successivo'	=>	array(
					'type'		=>	'Select',
					'labelString'=>	'Match',
					'entryClass'	=>	'form_input_text help_attivo',
					'options'	=>	self::$tipoCorrezione,
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se impostato 'Intero testo' viene usata questa traduzione nel caso il testo da tradurre sia esattamente uguale a quello impostato.")."<br />".gtext("Se impostato 'Porzione di testo' viene usata questa traduzione ogni volta che si trova la porzione di testo indicata nel testo da tradurre.")."</div>"
					),
					'reverse'	=>	'yes',
				),
				'lingua'	=>	array(
					"type"	=>	"Select",
					"options"	=>	gtextDeep(LingueModel::getSelectLingueNonPrincipali()),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
		);
	}
	
	public function getTitoloDaCodice($record)
	{
		return gtext(LingueModel::g()->clear()->select("titolo")->where(array(
			"codice"	=>	sanitizeAll($record["traduzioni_correzioni"]["lingua"]),
		))->field("descrizione"));
	}
	
	public static function getCorrezioni()
	{
		if (!isset(self::$correzioni))
		{
			$correzioni = TraduzionicorrezioniModel::g(false)->clear()->send(false);

			foreach ($correzioni as $c)
			{
				self::$correzioni[$c["successivo"]][$c["lingua"]][$c["parola_tradotta_da_correggere"]] = $c["parola_tradotta_corretta"];
			}
		}
	}

	public static function correggi($lingua, $testo, $successivo = 1)
	{
		self::getCorrezioni();

		if (isset(self::$correzioni[$successivo][$lingua]))
		{
			foreach (self::$correzioni[$successivo][$lingua] as $daCorreggere => $corretta)
			{
				$testo = str_replace($daCorreggere, $corretta, $testo);
			}
		}

		return $testo;
	}
	
	public function tipoCrud($record)
	{
		$idTipo = $record["traduzioni_correzioni"]["successivo"];
		
		if (isset(self::$tipoCorrezione[$idTipo]))
			return gtext(self::$tipoCorrezione[$idTipo]);
		
		return "";
	}
}
