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

class BlogModel extends PagesModel {
	
	public $hModelName = "BlogcatModel";
	
	public function __construct() {
		
		parent::__construct();
		
		$this->uploadFields["video_thumb"] = array(
			"type"	=>	"image",
			"path"	=>	"images/video",
// 				"mandatory"	=>	true,
			"allowedExtensions"	=>	'png,jpg,jpeg,gif',
			'allowedMimeTypes'	=>	'',
			"createImage"	=>	true,
			"maxFileSize"	=>	3000000,
// 				"clean_field"	=>	"clean_immagine",
			"Content-Disposition"	=>	"inline",
			"thumb"	=> array(
				'imgWidth'		=>	400,
				'imgHeight'		=>	400,
				'defaultImage'	=>  null,
				'cropImage'		=>	'no',
			),
		);
		
	}
	
	public function setFilters()
	{

	}
}
