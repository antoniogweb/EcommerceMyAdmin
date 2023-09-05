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

class Gls extends Spedizioniere
{ 
	public function gCampiForm()
	{
		return 'titolo,modulo,attivo';
	}
	
	public function setConditions(SpedizioninegozioModel $spedizione)
	{
// 		$spedizione->addStrongCondition("update",'checkNotEmpty',"ragione_sociale_2");
	}
	
// 	// Chiama i server del corriere e salva le informazioni del tracking nella spedizione
// 	public function getInfo($idSpedizione)
// 	{
// 		
// 	}
// 	
// 	public function consegnata($idSpedizione)
// 	{
// 		return true;
// 	}
// 	
// 	// Recupera le ultime informazioni del tracking salvate e verifica se la spedizione è stata impostata in errore
// 	public function inErrore($idSpedizione)
// 	{
// 		return true;
// 	}
}
