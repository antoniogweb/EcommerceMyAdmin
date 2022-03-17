<?php

// EasyGiant is a PHP framework for creating and managing dynamic content
//
// Copyright (C) 2009 - 2011  Antonio Gallo
// See COPYRIGHT.txt and LICENSE.txt.
//
// This file is part of EasyGiant
//
// EasyGiant is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// EasyGiant is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with EasyGiant.  If not, see <http://www.gnu.org/licenses/>.

if (!defined('EG')) die('Direct access not allowed!');

class Lang_It_UploadStrings extends Lang_ResultStrings {

	public $string = array(
		"error" => "<div class='alert'>Errore: verificare i permessi del file/directory</div>\n",
		"executed" => "<div class='executed'>Operazione eseguita!</div>\n",
		"not-child" => "<div class='alert'>La cartella selezionata non &egrave una sotto directory della directory base</div>\n",
		"not-dir" => "<div class='alert'>La cartella selezionata non &egrave una directory</div>\n",
		"not-empty" => "<div class='alert'>La cartella selezionata non &egrave vuota</div>\n",
		"no-folder-specified" => "<div class='alert'>Non &egrave stata specificata alcuna cartella</div>\n",
		"no-file-specified" => "<div class='alert'>Non &egrave stato specificato alcun file</div>\n",
		"not-writable" => "<div class='alert'>La cartella non &egrave scrivibile</div>\n",
		"not-writable-file" => "<div class='alert'>Il file non &egrave scrivibile</div>\n",
		"dir-exists" => "<div class='alert'>Esiste gi&agrave una directory con lo stesso nome</div>\n",
		"no-upload-file" => "<div class='alert'>Non c'&egrave alcun file di cui fare l'upload</div>\n",
		"size-over" => "<div class='alert'>La dimensione del file &egrave troppo grande</div>\n",
		"not-allowed-ext" => "<div class='alert'>L'estensione del file che vuoi caricare non &egrave consentita</div>\n",
		"not-allowed-mime-type" => "<div class='alert'>Il tipo MIME del file che vuoi caricare non &egrave consentito</div>\n",
		"file-exists" => "<div class='alert'>Esiste gi&agrave un file con lo stesso nome</div>\n",
	);
	
}
