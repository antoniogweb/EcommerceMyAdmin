<?php

// MvcMyLibrary is a PHP framework for creating and managing dynamic content
//
// Copyright (C) 2009 - 2011  Antonio Gallo
// See COPYRIGHT.txt and LICENSE.txt.
//
// This file is part of MvcMyLibrary
//
// MvcMyLibrary is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// MvcMyLibrary is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with MvcMyLibrary.  If not, see <http://www.gnu.org/licenses/>.

if (!defined('EG')) die('Direct access not allowed!');

//generic italian strings
class Lang_It_Generic extends Lang_En_Generic
{

	//English to Italian
	public $translations = array(
		'edit'		=>	'modifica',
		'delete'	=>	'cancella',
		'move up'	=>	'sposta in alto',
		'move down'	=>	'sposta in basso',
		'associate'	=>	'associa',
		'up'		=>	'su',
		'down'		=>	'giù',
		'link'		=>	'associa',
		'del'		=>	'cancella',
		'back'		=>	'torna',
		'Back'		=>	'Torna',
		'add a new record'	=>	'aggiungi una nuova riga',
		'Add'		=>	'Aggiungi',
		'back to the Panel'	=>	'torna al Pannello',
		'Panel'		=>	'Home',
		'previous'	=>	'precedente',
		'next'		=>	'successivo',
		'All'		=>	'Tutti',
		'pages'		=>	'pagine',
		'filter'	=>	'filtra',
		'clear the filter'	=>	'svuota il filtro',
		'Save'		=>	'Salva',
		'Actions'	=>	'Azioni di gruppo',
		'-- Select bulk action --' => '-- Seleziona azione --',
	);
	
	public function gtext($string)
	{
		if (array_key_exists($string,$this->translations))
		{
			return gtext($this->translations[$string], false);
		}

		return gtext($string);
	}

}
