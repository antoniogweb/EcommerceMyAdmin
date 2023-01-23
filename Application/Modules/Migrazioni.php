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

class Migrazioni
{
	public static function up($mostra = 0)
	{
		if (@!is_dir(ROOT.'/Logs'))
		{
			mkdir(ROOT.'/Logs');
			chmod(ROOT.'/Logs', 0777);
			$fp = fopen(ROOT.'/Logs/index.html', 'w');
			fclose($fp);
			
			$fp = fopen(ROOT.'/Logs/.htaccess', 'w');
			fwrite($fp, 'deny from all');
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
		
		ob_start();
		
// 			echo $newVersion."<br />";
// 		$mysqli = Db_Mysqli::getInstance();
		$mysqli = Factory_Db::getInstance(DATABASE_TYPE);
// 			print_r($migrazioni);
		
		foreach ($migrazioni as $numero => $file)
		{
			$numero = (int)$numero;
			
			if(((int)$numero > (int)$version) && file_exists($file))
			{
				$sql = file_get_contents($file);
				
				if (!$mostra)
				{
					if ($mysqli->query($sql))
					{
						fwrite($hand,date("Y-m-d H:i:s")." APPLICATA MIGRAZIONE ".$numero.".sql\n");
						echo "APPLICATA MIGRAZIONE ".$numero.".sql<br />\n";
					}
					else
					{
						$errori = $mysqli->getError();
						
						if (is_array($errori))
							print_r($errori);
						else
							echo $errori;
						
						fwrite($hand,date("Y-m-d H:i:s")." ERRORE MIGRAZIONE ".$numero.".sql\n");
						echo "ERRORE MIGRAZIONE ".$numero.".sql<br />\n";
					}
				}
				else
					echo $sql."<br />";
				
				$newVersion = $numero;
			}
		}
		
		if ($newVersion > $version)
		{
			if (!$mostra)
			{
				$mysqli->query("update variabili set valore = ".(int)$newVersion." where chiave='db_version';");
				fwrite($hand,date("Y-m-d H:i:s")." DATA BASE AGGIORNATO ALLA MIGRAZIONE ".$newVersion.".sql\n");
				echo "DATA BASE AGGIORNATO ALLA MIGRAZIONE ".$newVersion.".sql<br />\n";
				VariabiliModel::$valori["db_version"] = (int)$newVersion;
				
				// Rigenero l'albero delle categorie
				$cModel = new CategoriesModel();
				$cModel->callRebuildTree();
			}
			else
				echo "<br />NUOVA VERSIONE: $newVersion\n";
			
			$version = $newVersion;
		}
		
		RoutineaggiornamentoModel::esegui();
		
		$esitoMigrazioni = ob_get_clean();
		
		fwrite($hand,date("Y-m-d H:i:s")." STOP MIGRAZIONI\n");
		fwrite($hand,"\n");
		fclose($hand);
		
		return $esitoMigrazioni;
	}
}
