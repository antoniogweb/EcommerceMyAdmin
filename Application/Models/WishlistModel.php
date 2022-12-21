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

class WishlistModel extends Model_Tree {
	
	public static $pagesInWishlist = null;
	public static $deletedExpired = false;
	
	public function __construct() {
		$this->_tables='wishlist';
		$this->_idFields='id_wishlist';
		
		$this->_idOrder = 'id_order';
		
		$this->orderBy = 'wishlist.id_wishlist';
		$this->_lang = 'It';
		
		parent::__construct();

		$this->deleteExpired();
	}

	public function relations() {
        return array(
			'pagina' => array("BELONGS_TO", 'PagineModel', 'id_page',null,"CASCADE","Si prega di selezionare la pagina"),
        );
    }
    
	public function deleteExpired()
	{
		if (!self::$deletedExpired)
		{
			$limit = time() - Parametri::$durataWishlist; 
			$this->db->del('wishlist','creation_time < '.$limit);
			self::$deletedExpired = true;
		}
	}
	
	public function emptyWishlist()
	{
		$clean["wishlist_uid"] = sanitizeAll(User::$wishlist_uid);
		
// 		$this->del(null, "wishlist_uid = '" . $clean["wishlist_uid"] . "'");
		$this->del(null, array(
			"wishlist_uid"	=>	$clean["wishlist_uid"],
		));
	}
	
	public function delete($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		$clean["wishlist_uid"] = sanitizeAll(User::$wishlist_uid);
		
// 		$sql = "id_page = " . $clean["id_page"] . " AND wishlist_uid = '" . $clean["wishlist_uid"] . "'";
		
// 		return $this->del(null, $sql);
		return $this->del(null, array(
			"id_page"		=>	$clean["id_page"],
			"wishlist_uid"	=>	$clean["wishlist_uid"],
		));
	}
	
	public function add($id_page = 0)
	{
		$clean["id_page"] = (int)$id_page;
		$clean["wishlist_uid"] = sanitizeAll(User::$wishlist_uid);
		
		$p = new PagesModel();

		$rPage = $p->clear()->where(array("id_page"=>$clean["id_page"],"attivo"=>"Y"))->send();
		
		if (count($rPage) > 0)
		{
			$res = $this->clear()->where(array("id_page"=>$clean["id_page"],"wishlist_uid"=>$clean["wishlist_uid"]))->send();
			
			if (count($res) === 0)
			{
				$this->setValues(array(
					"id_page"		=>	$clean["id_page"],
					"wishlist_uid"	=>	$clean["wishlist_uid"],
					"creation_time"	=>	$this->getCreationTime(),
				));
				
				return $this->insert();
			}
		}
		
		return false;
	}
	
	public function getCreationTime()
	{
		$clean["wishlist_uid"] = sanitizeAll(User::$wishlist_uid);
		
		$res = $this->clear()->where(array("wishlist_uid"=>$clean["wishlist_uid"]))->send();
		
		if (count($res) > 0)
		{
			return $res[0]["wishlist"]["creation_time"];
		}
		
		return time();
	}
	
	public static function isInWishlist($id_page)
	{
		if (!isset(self::$pagesInWishlist))
		{
			self::$pagesInWishlist = array();
			
			$clean["wishlist_uid"] = sanitizeAll(User::$wishlist_uid);
			
			$wl = new WishlistModel();
			
			$res = $wl->clear()->where(array("wishlist_uid"=>$clean["wishlist_uid"]))->send(false);
			
			foreach ($res as $w)
			{
				self::$pagesInWishlist[] = $w["id_page"];
			}
		}
		
		return in_array($id_page, self::$pagesInWishlist) ? true : false;
	}
	
	public function recordExists($id_page)
	{
		$clean["id_page"] = (int)$id_page;
		$clean["wishlist_uid"] = sanitizeAll(User::$wishlist_uid);
		
		$res = $this->clear()->where(array("id_page"=>$clean["id_page"],"wishlist_uid"=>$clean["wishlist_uid"]))->send();
		
		if (count($res) > 0)
		{
			return true;
		}
		return false;
	}
	
	//numero prodotti nel carrello
	public function numberOfItems()
	{
		$clean["wishlist_uid"] = sanitizeAll(User::$wishlist_uid);
		
		$res = $this->clear()->select("count(id_wishlist) as q")->inner(array("pagina"))->where(array("wishlist_uid"=>$clean["wishlist_uid"]))->groupBy("wishlist_uid")->send();
		
		if (count($res) > 0)
		{
			return $res[0]["aggregate"]["q"];
		}

		return "0";
	}
	
	public function getProdotti()
	{
		$clean["wishlist_uid"] = sanitizeAll(User::$wishlist_uid);
		
		return $this->clear()->select("wishlist.*,pages.*,categories.*,contenuti_tradotti.*,contenuti_tradotti_categoria.*")->inner("pages")->on("wishlist.id_page = pages.id_page")
			->left("contenuti_tradotti")->on("contenuti_tradotti.id_page = pages.id_page and contenuti_tradotti.lingua = '".sanitizeDb(Params::$lang)."'")
			->inner("categories")->on("categories.id_c = pages.id_c")
			->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")
			->where(array("wishlist_uid"=>$clean["wishlist_uid"]))->orderBy("wishlist.id_order ASC, id_wishlist ASC")->send();
	}
}
