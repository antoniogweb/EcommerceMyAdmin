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

class CronController extends Controller {

	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);

		ini_set("memory_limit","512M");
	}
	
	public function migrazioni($c = "")
	{
		if (is_string($c) && v("codice_cron") && $c == v("codice_cron"))
		{
			if (@!is_dir(ROOT.'/Logs'))
			{
				mkdir(ROOT.'/Logs');
				chmod(ROOT.'/Logs', 0777);
				$fp = fopen(ROOT.'/Logs/index.html', 'w');
				fclose($fp);
			}
			
			$hand = fopen(ROOT.'/Logs/migrazioni.txt','a+');
			
			fwrite($hand,"\n");
			fwrite($hand,date("Y-m-d H:i:s")." START MIGRAZIONI\n");
			
			$newVersion = $version = v("db_version");
			
			$migrazioni = array();
			foreach (glob(ROOT."/DB/Migrazioni/*.sql") as $sqlfile) {    
				$migrationNum = basename($sqlfile, '.sql');
				$migrazioni[$migrationNum] =$sqlfile; 
			}
			
// 			echo $newVersion."<br />";
			$mysqli = Db_Mysqli::getInstance();
// 			print_r($migrazioni);
			
			foreach ($migrazioni as $numero => $file)
			{
				$numero = (int)$numero;
				
				if(((int)$numero > (int)$version) && file_exists($file))
				{
					$sql = file_get_contents($file);
					
					if ($mysqli->query($sql))
					{
						fwrite($hand,date("Y-m-d H:i:s")." APPLICATA MIGRAZIONE ".$numero.".sql\n");
						echo "APPLICATA MIGRAZIONE ".$numero.".sql<br />";
					}
					else
					{
						echo $mysqli->getError()."<br />";
						fwrite($hand,date("Y-m-d H:i:s")." ERRORE MIGRAZIONE ".$numero.".sql\n");
						echo "ERRORE MIGRAZIONE ".$numero.".sql<br />";
					}
					
					$newVersion = $numero;
				}
			}
			
			if ($newVersion > $version)
			{
				$mysqli->query("update variabili set valore = ".(int)$newVersion." where chiave='db_version';");
				fwrite($hand,date("Y-m-d H:i:s")." DATA BASE AGGIORNATO ALLA MIGRAZIONE ".$newVersion.".sql\n");
				echo "DATA BASE AGGIORNATO ALLA MIGRAZIONE ".$newVersion.".sql<br />";
				$version = $newVersion;
			}
			
			fwrite($hand,date("Y-m-d H:i:s")." STOP MIGRAZIONI\n");
			fwrite($hand,"\n");
			fclose($hand);
		}
	}
}
