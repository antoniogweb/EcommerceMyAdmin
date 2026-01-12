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

class PanelController extends BaseController {

	public function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->session('admin');
		
		$this->s['admin']->check();
	}

	public function main($tipo = "sito")
	{
		$this->clean();
		
		if (LingueModel::permettiCambioLinguaBackend())
		{
			if (!isset($_COOKIE["backend_lang"]) || (string)Params::$lang !== (string)$_COOKIE["backend_lang"])
			{
				$time = time() + 3600*24*365*10;
				setcookie("backend_lang",Params::$lang,$time,"/");
			}
		}
		
		$clean["tipo"] = encodeUrl(basename((string)$tipo));
		
		if (!is_string($tipo) || !in_array($clean["tipo"], array_keys(App::$pannelli)) || !file_exists($this->theme->viewPath('header_'.$clean["tipo"])))
			$clean["tipo"] = "sito";
		
		$this->load('header_'.$clean["tipo"]);
		$data["sezionePannello"] = $clean["tipo"];
		
		$this->append($data);
		
		$this->load('footer','last');
		$this->load('panel');
	}
	
	public function salvasidebar($tipo = 1)
	{
		$this->clean();
		
		$time = time() + 3600*24*365*10;
		
		if ((int)$tipo === 1)
			Cookie::set("tipo_sidebar", 1, $time, "/", true, 'Lax');
		else
			Cookie::set("tipo_sidebar", 2, $time, "/", true, 'Lax');
	}
	
	public function salvaopzione()
	{
		$this->clean();
		
		$app = $this->request->post("app","","forceAlNum");
		$controller = $this->request->post("controller","","forceAlNum");
		$action = $this->request->post("action","","forceAlNum");
		$idRecord = $this->request->post("id_record",0,"forceInt");
		$valore = $this->request->post("valore","");
		
		$action = forceAlNum(rtrim($action, "/"));
		
		if (!preg_match('/^[a-zA-Z_0-9\-\_\,]$/',$valore))
		{
			$where = array(
				"id_user"		=>	(int)User::$id,
				"app"			=>	sanitizeAll($app),
				"controller"	=>	sanitizeAll($controller),
				"action"		=>	sanitizeAll($action),
				"id_record"		=>	(int)$idRecord,
			);
			
			$this->m("UsersopzioniModel")->sValues(array(
				"id_user"		=>	(int)User::$id,
				"app"			=>	$app,
				"controller"	=>	$controller,
				"action"		=>	$action,
				"id_record"		=>	$idRecord,
				"valore"		=>	$valore,
			));
			
			$idOpzione = (int)$this->m("UsersopzioniModel")->clear()->select("id_adminuser_opzione")->where($where)->field("id_adminuser_opzione");
			
			if ($idOpzione)
				$this->m("UsersopzioniModel")->update($idOpzione);
			else
				$this->m("UsersopzioniModel")->insert();
		}
	}
}
