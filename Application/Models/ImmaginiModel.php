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

class ImmaginiModel extends Model_Tree {

	public function __construct() {

		$this->_tables='immagini';
		$this->_idFields='id_immagine';

		$this->orderBy = 'immagini.id_order';

		$this->_lang = 'It';
		$this->_idOrder = 'id_order';

		parent::__construct();

		$this->files->setBase(Domain::$parentRoot.'/'.Parametri::$cartellaImmaginiContenuti);
	}

	public function getIdContenuto($id_immagine)
	{
		$clean['id_immagine'] = (int)$id_immagine;
		$res = $this->clear()->select('id_page')->where(array('id_immagine'=>$clean['id_immagine']))->toList('id_page')->send();

		$this->clear();
		if (count($res) >0)
		{
			return (int)$res[0];
		}
		return 0;
	}

	public function getFileName($id_immagine)
	{
		$clean['id_immagine'] = (int)$id_immagine;
		$res = $this->clear()->select('immagine')->where(array('id_immagine'=>$clean['id_immagine']))->toList('immagine')->send();

		$this->clear();
		if (count($res) >0)
		{
			return $res[0];
		}
		return '';
	}

	public function getFirstImage($id_page)
	{
		$clean['id_page'] = (int)$id_page;

		$p = new PagesModel();
		$record = $p->selectId($clean['id_page']);
		
		if (count($record) > 0 and strcmp($record["immagine"],"") !== 0)
		{
			return $record["immagine"];
			
		}
		
		$res = $this->select('immagine')->where(array('id_page'=>$clean['id_page']))->toList('immagine')->limit(1)->send();
		
		if (count($res) > 0)
		{
			return $res[0];
		}
		return '';
	}
	
	public function imageExists($immagine, $id_page)
	{
		$clean["immagine"] = sanitizeAll($immagine);
		$clean["id_page"] = sanitizeAll($id_page);
		
		$p = new PagesModel();
		
		$res1 = $this->select('immagine')->where(array('immagine'=>$clean['immagine'],"id_page"=>$clean["id_page"]))->toList('immagine')->limit(1)->send();
		$res2 = $p->select('immagine')->where(array('immagine'=>$clean['immagine'],"id_page"=>$clean["id_page"]))->toList('immagine')->limit(1)->send();
// 		echo $this->getQuery();
		if (count($res1) > 0 or count($res2) > 0)
		{
			return true;
		}
		return false;
	}
	
	//duplica le immagini della pagina avente id uguale a $from_id alla pagina avente id uguale a $to_id 
	public function duplica($from_id, $to_id)
	{
		$clean["from_id"] = (int)$from_id;
		$clean["to_id"] = (int)$to_id;
		
		$res = $this->clear()->where(array("id_page"=>$clean["from_id"]))->orderBy("id_order")->send();
		
		foreach ($res as $r)
		{
			$this->values = array();
			$this->values["immagine"] = $r["immagini"]["immagine"];
			$this->values["id_page"] = $clean["to_id"];
			
			$this->sanitize();
			$this->insert();
		}
	}
	
	public function del($id_immagine = null, $whereClause = null)
	{
		$clean['id_immagine'] = (int)$id_immagine;

		$record = $this->selectId($clean['id_immagine']);
		
		if (count($record) > 0)
		{
			$fileName = $this->getFileName($clean['id_immagine']);
			
			if (strcmp($fileName,'') !== 0)
			{
				//controllo che il file non sia duplicato
				$res = $this->clear()->where(array("immagine"=>sanitizeAll($fileName)))->send();
				
				if (count($res) > 1)
				{
					parent::del($clean['id_immagine']);
				}
				else
				{
					$tree = new Files_Upload(Domain::$parentRoot.'/'.Parametri::$cartellaImmaginiContenuti);
					if (file_exists(Domain::$parentRoot.'/'.Parametri::$cartellaImmaginiContenuti."/".$fileName))
					{
						if ($tree->removeFile($fileName))
						{
							parent::del($clean['id_immagine']);
						}
						else
							parent::del($clean['id_immagine']);
					}
					else
					{
						parent::del($clean['id_immagine']);
					}
				}
			}
			else
			{
				parent::del($clean['id_immagine']);
			}
		}
	}
	
}
