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

class UsersopzioniModel extends GenericModel
{
	public static $opzioni = null;
	public static $sApp = "";
	public static $sController = "";
	public static $sAction = "";
	
	public function __construct() {
		$this->_tables='adminusers_opzioni';
		$this->_idFields='id_adminuser_opzione';
		
		parent::__construct();
	}
	
	public function relations() {
		return array(
			'user' => array("BELONGS_TO", 'UsersModel', 'id_user',null,"CASCADE"),
		);
    }
    
    public static function gOpz($id = 0, $default = "", $acceptedValues = array())
	{
		$uoModel = new UsersopzioniModel();
		
		if (!isset(self::$opzioni))
		{
			self::$opzioni = array();
			
			$res = $uoModel->clear()->where(array(
				"id_user"		=>	(int)User::$id,
				"app"			=>	sanitizeAll(self::$sApp),
				"controller"	=>	sanitizeAll(self::$sController),
				"action"		=>	sanitizeAll(self::$sAction),
			))->send(false);
			
			foreach ($res as $r)
			{
				self::$opzioni[$r["app"]][$r["controller"]][$r["action"]][$r["id_record"]] = $r["valore"];
			}
		}
		
		$valore =  self::$opzioni[self::$sApp][self::$sController][self::$sAction][$id] ?? $default;
		
		if (in_array($valore, $acceptedValues))
			return $valore;
		
		return $default;
	}
}
