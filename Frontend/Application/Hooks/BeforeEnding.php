<?php

// All EasyGiant code is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
// See COPYRIGHT.txt and LICENSE.txt.

if (!defined('EG')) die('Direct access not allowed!');

$mysqli = Db_Mysqli::getInstance();

if (count($mysqli->queries) > 0 && v("salva_conteggio_query"))
	ConteggioqueryModel::aggiungi(count($mysqli->queries) + 1);

if (isset($_GET["debug1234"]))
	print_r($mysqli->queries);
