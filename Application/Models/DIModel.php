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

if (!defined('EG')) die('Direct access not allowed!');

trait DIModel
{
	public function getNomeCampoClasse()
	{
		return "modulo";
	}
	
	public function setTokenSicurezza($id)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && !trim($record["token_sicurezza"]))
		{
			$this->sValues(array(
				"token_sicurezza"	=>	randomToken(),
			));
			
			$this->update((int)$id);
		}
	}
	
	public function attivo($record)
	{
		return $record[$this->_tables]["attivo"] ? "<i class='fa fa-check text text-success'></i>" : "<i class='fa fa-ban text text-danger'></i>";
	}
	
	public function edit($record)
	{
		return "<span class='data-record-id' data-primary-key='".$record[$this->_tables][$this->_idFields]."'>".$record[$this->_tables][$this->campoTitolo]."</span>";
	}
	
	public function checkModulo($codice, $token = "")
	{
		return $this->clear()->where(array(
			"codice"	=>	sanitizeDb((string)$codice),
			"attivo"	=>	1,
			"OR"	=>	array(
				"usa_token_sicurezza"	=>	0,
				"AND"	=>	array(
					"token_sicurezza"	=>	sanitizeDb((string)$token),
					"ne"	=>	array(
						"token_sicurezza"	=>	"",
					)
				)
			)
		))->rowNumber();
	}
	
	public static function getModuloPadre()
	{
		$className = get_called_class();
		$c = new $className;
		
		require_once(LIBRARY."/Application/Modules/".$c->classeModuloPadre.".php");
		
		$objectReflection = new ReflectionClass($c->classeModuloPadre);
		return $objectReflection->newInstanceArgs();
	}
	
	public static function findModulePath($folder, $className)
	{
		if (file_exists(LIBRARY."/Application/Modules/".$folder."/".$className.".php"))
			return LIBRARY."/Application/Modules/".$folder."/".$className.".php";
		else
		{
			foreach (APPS as $app)
			{
				$path = LIBRARY."/Application/Apps/".ucfirst($app)."/Modules/$folder/$className.php";
				
				if (file_exists($path))
					return $path;
			}
		}
		
		return "";
	}
	
	public static function getModulo($codice = null, $forza = false)
	{
		$className = get_called_class();
		$c = new $className;
		
		if (!isset(self::$modulo) || $forza)
		{
			$nomeCampoClasse = $c->getNomeCampoClasse();
			
			if (isset($codice))
				$attivo = $c->clear()->where(array(
					"codice"	=>	sanitizeDb($codice),
				))->record();
			else
				$attivo = $c->clear()->where(array(
					"attivo"	=>	1,
				))->record();
			
			if (!empty($attivo))
			{
				$path = $c->findModulePath($c->cartellaModulo, $attivo[$nomeCampoClasse]);
				
				if ($path != "" && file_exists($path))
				{
					require_once(LIBRARY."/Application/Modules/".$c->classeModuloPadre.".php");
					require_once($path);
					
					$objectReflection = new ReflectionClass($attivo[$nomeCampoClasse]);
					$object = $objectReflection->newInstanceArgs(array($attivo));
					
					self::$modulo = $object;
				}
			}
		}
		
		return $c;
	}
	
	// Applica il metodo $metodo con argomenti $argomenti a tutti i moduli attivi
	public static function applicaMetodoATuttiIModuli($metodo, $argomenti = array())
	{
		$className = get_called_class();
		$c = new $className;
		
		$attivi = $c->clear()->where(array(
			"attivo"	=>	1,
		))->send(false);
		
		$nomeCampoClasse = $c->getNomeCampoClasse();
		
		$returnArray = [];
		
		foreach ($attivi as $attivo)
		{
			$path = $c->findModulePath($c->cartellaModulo, $attivo[$nomeCampoClasse]);
			
			if (file_exists($path))
			{
				require_once(LIBRARY."/Application/Modules/".$c->classeModuloPadre.".php");
				require_once($path);
				
				$objectReflection = new ReflectionClass($attivo[$nomeCampoClasse]);
				$object = $objectReflection->newInstanceArgs(array($attivo));
				
				if ($object && method_exists($object, $metodo) && $object->isAttivo())
					$returnArray[] = call_user_func_array(array($object, $metodo), $argomenti);
			}
		}
		
		return $returnArray;
	}
	
	public function __call($metodo, $argomenti)
	{
		if (isset(self::$modulo) && method_exists(self::$modulo, $metodo))
			return call_user_func_array(array(self::$modulo, $metodo), $argomenti);

		return false;
	}
	
	public function moduleFormStruct($id)
	{
		$record = $this->selectId((int)$id);
		
		if (!empty($record) && $record["codice"])
			self::getModulo($record["codice"])->editFormStruct($this, $record);
	}
}
