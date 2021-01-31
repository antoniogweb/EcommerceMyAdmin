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

trait CommonModel {
	
	public function controllaCF($controlla = 1)
	{
		if ($controlla)
		{
			if (isset($this->values["codice_fiscale"]) && isset($this->values["tipo_cliente"]) && isset($_POST["nazione"]) && $_POST["nazione"] == "IT")
			{
				if ($this->values["tipo_cliente"] == "privato" || $this->values["tipo_cliente"] == "libero_professionista")
				{
					if (!codiceFiscale($this->values["codice_fiscale"]))
					{
						$this->notice = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare il campo <b>Codice Fiscale</b>")."</div><span class='evidenzia'>class_codice_fiscale</span>".$this->notice;
						$this->result = false;
						return false;
					}
				}
			}
		}
		
		return true;
	}
	
	public function controllaPIva($controlla = 1)
	{
		if (v("controlla_p_iva") && $controlla)
		{
			if (isset($this->values["p_iva"]) && isset($this->values["tipo_cliente"]) && isset($_POST["nazione"]) && $this->values["tipo_cliente"] != "privato")
			{
				include(ROOT."/admin/External/ddeboervatin/vendor/autoload.php");
				
				$validator = new Ddeboer\Vatin\Validator();
				
				if ($validator->isValidCountryCode($_POST["nazione"]))
				{
					$stringa = substr($this->values["p_iva"],0,2) == $_POST["nazione"] ? $this->values["p_iva"] : $_POST["nazione"].$this->values["p_iva"];
					
					$res = $validator->isValid($stringa);
					
					if (!$res)
					{
						$this->notice = "<div class='".v("alert_error_class")."'>".gtext("Si prega di controllare il campo <b>Partita Iva</b>")."</div><span class='evidenzia'>class_p_iva</span>".$this->notice;
						$this->result = false;
						return false;
					}
				}
			}
		}
		
		return true;
	}
	
	public static function getUrlContenuto($p)
	{
		$className = get_called_class();
		
		$model = new $className();
		
		$tableName = $model->table();
		
		$url = "";
		if ($p[$tableName]["link_id_page"])
			$url = Url::getRoot().getUrlAlias($p[$tableName]["link_id_page"]);
		else if ($p[$tableName]["link_id_c"])
			$url = Url::getRoot().getCategoryUrlAlias($p[$tableName]["link_id_c"]);
		else if ($p[$tableName]["link_id_marchio"])
			$url = Url::getRoot().getMarchioUrlAlias($p[$tableName]["link_id_marchio"]);
		else if ($p[$tableName]["link_id_tag"])
			$url = Url::getRoot().TagModel::getUrlAlias($p[$tableName]["link_id_tag"]);
		else if (field($p, "url") && $tableName == "pages")
			$url = checkHttp(field($p, "url"));
		
		return $url;
	}
}
