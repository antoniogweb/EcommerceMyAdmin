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

class ApplicazioniModel extends GenericModel {
	
	public function __construct() {
		$this->_tables='applicazioni';
		$this->_idFields='id_applicazione';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
    
	public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'attivo'	=>	self::$entryAttivo,
			),
		);
	}
	
	public function attivo($record)
	{
		return $record[$this->_tables]["attivo"] ? gtext("Sì") : gtext("No");
	}
	
	public static function carica()
	{
		$a = new ApplicazioniModel();
		
		$applicazioni = $a->clear()->where(array(
			"attivo"	=>	1,
		))->orderBy("id_order")->toList("codice")->send();
		
		define ("APPS", $applicazioni);
	}
	
	public function daaggiornare($record)
	{
		if (file_exists(ROOT."/Application/Apps/".ucfirst($record["applicazioni"]["codice"])."/DB/Migrazioni"))
		{
			$files = scandir(ROOT."/Application/Apps/".ucfirst($record["applicazioni"]["codice"])."/DB/Migrazioni", SCANDIR_SORT_DESCENDING);
			$ultimaMigrazione = $files[0];
			$migrationNum = (int)basename($ultimaMigrazione, '.sql');
			
			if ($migrationNum > (int)$record["applicazioni"]["db_version"])
				return "<a class='iframe' title='".gtext("Aggiorna il database")."' href='".Url::getRoot()."applicazioni/migrazioni/".v("codice_cron")."/".$record["applicazioni"]["id_applicazione"]."?partial=Y'><i class='fa fa-refresh text text-warning'></i></a>";
		}
		
		return "";
	}
	
	public static function variabiliGestibili($id)
	{
		$a = new ApplicazioniModel();
		
		return $a->where(array(
			"id_applicazione"	=>	(int)$id,
		))->field("variabili_gestibili");
	}
	
	public function migrazioni($c = "", $id = 0)
	{
		if ($id && is_string($c) && v("codice_cron") && $c == v("codice_cron"))
		{
			$record = $this->selectId((int)$id);
			
			if (empty($record))
				return;
			
			$appPath = ROOT."/Application/Apps/".ucfirst($record["codice"]);
			
			if (@!is_dir($appPath.'/DB/Migrazioni'))
				return;
			
			if (@!is_dir($appPath.'/Logs'))
			{
				mkdir($appPath.'/Logs');
				
				$fp = fopen($appPath.'/Logs/.htaccess', 'w');
				fwrite($fp, 'deny from all');
				fclose($fp);
			}
			
			$hand = fopen($appPath.'/Logs/migrazioni.txt','a+');
			
			fwrite($hand,"\n");
			fwrite($hand,date("Y-m-d H:i:s")." START MIGRAZIONI\n");
			
			$newVersion = $version = $record["db_version"];
			
			$migrazioni = array();
			foreach (glob($appPath."/DB/Migrazioni/*.sql") as $sqlfile) {    
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
				$mysqli->query("update applicazioni set db_version = ".(int)$newVersion." where id_applicazione='".(int)$id."';");
				fwrite($hand,date("Y-m-d H:i:s")." DATA BASE AGGIORNATO ALLA MIGRAZIONE ".$newVersion.".sql\n");
				echo "DATA BASE AGGIORNATO ALLA MIGRAZIONE ".$newVersion.".sql<br />";
				
				$version = $newVersion;
			}
			
			$esitoMigrazioni = ob_get_clean();
			$titoloPagina = gtext("Esito migrazioni applicazione")." ".$record["titolo"];
			
			return array($titoloPagina, $esitoMigrazioni);
			
			fwrite($hand,date("Y-m-d H:i:s")." STOP MIGRAZIONI\n");
			fwrite($hand,"\n");
			fclose($hand);
		}
	}
}
