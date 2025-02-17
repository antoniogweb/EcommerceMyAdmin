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

class ControllersModel extends GenericModel
{
	public static $elencoControllerAttivi = null;
	public static $controllerPermessi = null;
	public static $controllerPermessiPrincipali = null;
	
	public function __construct() {
		$this->_tables = 'controllers';
		$this->_idFields = 'id_controller';
		
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'gruppi' => array("HAS_MANY", 'GroupscontrollersModel', 'id_controller', null, "CASCADE"),
		);
    }
	
	public function sistemaVisibilita()
	{
		$res = $this->clear()->where(array(
			"codice_padre"	=>	"",
		))->send();
		
		foreach ($res as $record)
		{
			if ($record["controllers"]["condizioni"])
			{
				$visibile = $record["controllers"]["visibile"];
				
				$statoCorretto = VariabiliModel::verificaCondizioni($record["controllers"]["condizioni"]) ? 1 : 0;
				
				if ((int)$visibile !== (int)$statoCorretto)
				{
					$this->sValues(array(
						"visibile"	=>	$statoCorretto,
					));
					
					$this->update($record["controllers"]["id_controller"]);
				}
			}
		}
		
		$this->notice = "";
	}
	
	public function bulkaggiungiagruppo($record)
    {
		return "<i data-azione='aggiungiagruppo' title='".gtext("Aggiungi al gruppo")."' class='bulk_trigger help_trigger_aggiungi_al_gruppo fa fa-plus-circle text text-primary'></i>";
    }
    
    public function aggiungiagruppo($id)
    {
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && isset($_GET["id_group"]))
		{
			$gc = new GroupscontrollersModel();
			
			$gc->sValues(array(
				"id_group"		=>	(int)$_GET["id_group"],
				"id_controller"	=>	(int)$id,
			), "sanitizeDb");
			
			$gc->insert();
		}
    }
    
    public static function getControlliPrincipaliAbilitati($soloListaController = true, $pannello = null)
    {
		$uModel = new UsersModel();
		
		$uModel->clear()->select("controllers.*")
				->inner("adminusers_groups")->on("adminusers.id_user = adminusers_groups.id_user")
				->inner("admingroups_controllers")->on("adminusers_groups.id_group = admingroups_controllers.id_group")
				->inner("controllers")->on("admingroups_controllers.id_controller = controllers.id_controller")
				->where(array(
					"adminusers.id_user"	=>	(int)User::$id,
					"controllers.attivo"	=>	1,
				))
				->orderBy("controllers.id_order");
		
		if ($soloListaController)
			$uModel->select("controllers.codice")->toList("controllers.codice");
		
		if ($pannello)
			$uModel->aWhere(array(
				"controllers.pannello"	=>	sanitizeAll($pannello),
			));
		
		$res = $uModel->send();
		
// 		echo $uModel->getQuery();
		
		return $res;
    }
    
    public static function getControllerAbilitati($soloPrincipali = false)
    {
		if (!isset(self::$controllerPermessi) || !isset(self::$controllerPermessiPrincipali))
		{
			$cModel = new ControllersModel();
			
			self::$controllerPermessiPrincipali = self::getControlliPrincipaliAbilitati();
			
			if (count(self::$controllerPermessiPrincipali) > 0)
				self::$controllerPermessi = $cModel->clear()->select("controllers.codice")->where(array(
					"controllers.attivo"	=>	1,
					"OR"	=>	array(
						"in"	=>	array(
							"codice"	=>	sanitizeAllDeep(self::$controllerPermessiPrincipali),
						),
						" in"	=>	array(
							"codice_padre"	=>	sanitizeAllDeep(self::$controllerPermessiPrincipali),
						),
					),
				))->toList("controllers.codice")->orderBy("id_order")->send();
			else
				self::$controllerPermessi = array();
// 			echo $cModel->getQuery();
		}
		
		if ($soloPrincipali)
			return self::$controllerPermessiPrincipali;
		else
			return self::$controllerPermessi;
    }
    
    // Controlla l'accesso al controller
    public static function checkAccessoAlController($controllers)
    {
		if (!v("attiva_gruppi_admin"))
			return true;
		
		$controllersFinali = ControllersModel::getControllerAbilitati();
		
		if ((int)count($controllersFinali) === 0)
			return true;
		
		// Controllo che sia un controller non mappato
		$elencoAttivi = ControllersModel::getElencoControllers();
		
		$arrayBool = array();
		
		foreach ($controllers as $c)
		{
			$arrayBool[] = !in_array($c, $elencoAttivi) ? true : false;
		}
		
		$arrayBool = array_unique($arrayBool);
		
		if ((int)count($arrayBool) === 1 && $arrayBool[0])
			return true;
		
		// Controllo che sia un controller dell'utente attivo
		$arrayBool = array();
		
		foreach ($controllers as $c)
		{
			$arrayBool[] = in_array($c, $controllersFinali) ? true : false;
		}
		
		$arrayBool = array_unique($arrayBool);
		
		$res = ((int)count($arrayBool) === 1 && $arrayBool[0]) ? true : false;
		
		return $res;
    }
    
    // Restituisce tutti i controller attivi
    public static function getElencoControllers()
    {
		if (isset(self::$elencoControllerAttivi))
			return self::$elencoControllerAttivi;
		
		$cModel = new ControllersModel();
		
		self::$elencoControllerAttivi = $cModel->clear()->where(array(
			"controllers.attivo"	=>	1,
		))->toList("codice")->send();
		
		return self::$elencoControllerAttivi;
    }
}
