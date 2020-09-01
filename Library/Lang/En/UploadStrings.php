<?php

// EasyGiant is a PHP framework for creating and managing dynamic content
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
// See COPYRIGHT.txt and LICENSE.txt.
//
// This file is part of EasyGiant
//
// EasyGiant is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// EasyGiant is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with EasyGiant.  If not, see <http://www.gnu.org/licenses/>.

if (!defined('EG')) die('Direct access not allowed!');

class Lang_En_UploadStrings extends Lang_ResultStrings {
	
	public $string = array(
		"error" => "<div class='alert'>Error: verify the permissions of the file/directory</div>\n",
		"executed" => "<div class='executed'>Operation executed!</div>\n",
		"not-child" => "<div class='alert'>The selected directory is not a child of the base directory</div>\n",
		"not-dir" => "<div class='alert'>The selected directory is not a directory</div>\n",
		"not-empty" => "<div class='alert'>The selected directory is not empty</div>\n",
		"no-folder-specified" => "<div class='alert'>No folder has been specified</div>\n",
		"no-file-specified" => "<div class='alert'>No file has been specified</div>\n",
		"not-writable" => "<div class='alert'>The folder is not writable</div>\n",
		"not-writable-file" => "<div class='alert'>The file is not writable</div>\n",
		"dir-exists" => "<div class='alert'>The directory is already present in the current folder</div>\n",
		"no-upload-file" => "<div class='alert'>There is no file to upload</div>\n",
		"size-over" => "<div class='alert'>The size of the file is too big</div>\n",
		"not-allowed-ext" => "<div class='alert'>The extension of the file you want to upload is not allowed</div>\n",
		"not-allowed-mime-type" => "<div class='alert'>The MIME type of the file you want to upload is not allowed</div>\n",
		"file-exists" => "<div class='alert'>The file is already present in the current folder</div>\n"
	);
	
}
