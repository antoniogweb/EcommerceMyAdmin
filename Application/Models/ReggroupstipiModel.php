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

class ReggroupstipiModel extends GenericModel {
	
	public static $tipologie = array(
		"CO"	=>	"Tipo contenuto",
		"DO"	=>	"Tipo documento",
	);
	
	public function __construct() {
		$this->_tables='reggroups_tipi';
		$this->_idFields='id_rgt';
		
		$this->orderBy = 'id_order desc';
		
		$this->_lang = 'It';
		$this->_idOrder = 'id_order';
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'gruppo' => array("BELONGS_TO", 'ReggroupsModel', 'id_group',null,"CASCADE"),
        );
    }
	
	public function tipologiacontenuto($record)
	{
		return isset(self::$tipologie[$record["reggroups_tipi"]["tipo"]]) ? self::$tipologie[$record["reggroups_tipi"]["tipo"]] : $record["reggroups_tipi"]["tipo"];
	}
	
	public function categoriacontenuto($record)
	{
		if ($record["reggroups_tipi"]["tipo"] == "CO")
			$c = new TipicontenutoModel();
		else if ($record["reggroups_tipi"]["tipo"] == "DO")
			$c = new TipidocumentoModel();
		
		if (isset($c))
			return $c->titolo((int)$record["reggroups_tipi"]["id_tipo"]);
		
		return "";
	}
	
	public function del($id = null, $where = null)
	{
		$record = $this->selectId((int)$id);
		
		if (empty($record))
			return;
		
		if (parent::del($id, $where))
			$this->elabora($id, "DELETE", $record);
	}
	
	public function elaboraTutto($azione = "INSERT", $idEl = 0)
	{
		$res = $this->clear()->send(false);
		
		foreach ($res as $r)
		{
			$this->elabora($r["id_rgt"], $azione, $r, $idEl);
		}
	}
	
	public function elabora($id, $azione = "INSERT", $record = null, $idEl = 0)
	{
		if (!v("attiva_reggroups_tipi"))
			return;
		
		$record = $record ? $record : $this->selectId((int)$id);
		
		if (empty($record))
			return;
		
		$rg = new ReggroupsModel();
		
		$gruppo = $rg->selectId($record["id_group"]);
		
		if (empty($gruppo))
			return;
		
		if ($record["tipo"] == "CO")
		{
			$m = new ContenutiModel();
			$rgc = new ReggroupscontenutiModel();
			
			$idsC = $m->clear()->where(array(
				"id_tipo"	=>	(int)$record["id_tipo"],
			))->toList("id_cont")->send();
			
			if (v("usa_transactions"))
				$this->db->beginTransaction();
			
			foreach ($idsC as $idC)
			{
				if (!$idEl || (int)$idEl === (int)$idC)
				{
					if ($azione == "INSERT")
					{
						$rgc->setValues(array(
							"id_group"	=>	(int)$record["id_group"],
							"id_cont"	=>	(int)$idC,
						));
						
						$rgc->pInsert();
					}
					else
					{
						$rgc->del(null, "id_group = ".(int)$record["id_group"]." and id_cont = ".(int)$idC);
					}
				}
			}
			
			if (v("usa_transactions"))
				$this->db->commit();
		}
		else if ($record["tipo"] == "DO")
		{
			$d = new DocumentiModel();
			$rgd = new ReggroupsdocumentiModel();
			
			$idsD = $d->clear()->where(array(
				"id_tipo_doc"	=>	(int)$record["id_tipo"],
			))->toList("id_doc")->send();
			
			if (v("usa_transactions"))
				$this->db->beginTransaction();
			
			foreach ($idsD as $idD)
			{
				if (!$idEl || (int)$idEl === (int)$idD)
				{
					if ($azione == "INSERT")
					{
						$rgd->setValues(array(
							"id_group"	=>	(int)$record["id_group"],
							"id_doc"	=>	$idD,
						));
						
						$rgd->pInsert();
					}
					else
					{
						$rgd->del(null, "id_group = ".(int)$record["id_group"]." and id_doc = ".(int)$idD);
					}
				}
			}
			
			if (v("usa_transactions"))
				$this->db->commit();
		}
	}
}
