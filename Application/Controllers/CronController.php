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

class CronController extends BaseController {

	function __construct($model, $controller, $queryString) {
		parent::__construct($model, $controller, $queryString);
		
// 		$this->clean();
		
		ini_set("memory_limit","512M");
		ini_set("max_execution_time","300");
	}
	
	public function migrazioni($c = "", $mostra = 0)
	{
// 		$this->clean();
		
		if (is_string($c) && v("codice_cron") && $c == v("codice_cron"))
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
			$mysqli = Db_Mysqli::getInstance();
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
							echo "APPLICATA MIGRAZIONE ".$numero.".sql<br />";
						}
						else
						{
							echo $mysqli->getError()."<br />";
							fwrite($hand,date("Y-m-d H:i:s")." ERRORE MIGRAZIONE ".$numero.".sql\n");
							echo "ERRORE MIGRAZIONE ".$numero.".sql<br />";
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
					echo "DATA BASE AGGIORNATO ALLA MIGRAZIONE ".$newVersion.".sql<br />";
					VariabiliModel::$valori["db_version"] = (int)$newVersion;
					
					// Rigenero l'albero delle categorie
					$cModel = new CategoriesModel();
					$cModel->callRebuildTree();
				}
				else
					echo "<br />NUOVA VERSIONE: $newVersion";
				
				$version = $newVersion;
			}
			
			RoutineaggiornamentoModel::esegui();
			
			$data["esitoMigrazioni"] = ob_get_clean();
			$data["titoloPagina"] = gtext("Esito migrazioni");
			
			$this->append($data);
			$this->load("output");
			
			fwrite($hand,date("Y-m-d H:i:s")." STOP MIGRAZIONI\n");
			fwrite($hand,"\n");
			fclose($hand);
		}
	}
}
