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

//class to manage upload files
class Files_Upload
{

	const DS = DIRECTORY_SEPARATOR;

	private $base = null; //root directory
	private $directory = null; //current directory. Path relative to the base directory (Files_Upload::base)
	private $parentDir = null; //parent folder
	private $subDir = array(); //subdirectories of the current directory
	private $relSubDir = array(); //subfolders of $this->directory. The path starts from the $base folder
	private $files = array(); //files inside the current directory
	private $relFiles = array(); //files inside $this->directory. The path starts from the $base directory
	private $params; //class parameters
	private $pattern = null; //the pattern for the preg_match function

	protected $_resultString; //reference to the class uploadStrings containing all the result strings
	
	public $fileName = null; //the name of the last file that has been uploaded
	public $notice = null; //the result string of the operation
	
	public $ext = null; //the extension of the last file that has been uploaded
	public $mimeType = null; //the mime type of the last file that has been uploaded
	
	public function __construct($base,$params = null, $directory = null) {

		$this->base = $this->addTrailingSlash($base);

		//set the match pattern
		$tmp = str_replace(self::DS,'\\'.self::DS,$this->base);
		$this->pattern = "/^(".$tmp.")/";
		
		$defaultParams = array(
			'filesPermission'				=>	0777,
			'changeFilePermission'			=>	false,
			'delFolderAction'				=>	'delFolderAction',
			'delFileAction'					=>	'delFileAction',
			'createFolderAction'			=>	'createFolderAction',
			'uploadFileAction'				=>	'uploadFileAction',
			'maxFileSize' 					=>	3000000,
			'language' 						=>	'En',
			'allowedExtensions'				=>	'jpg,jpeg,png,gif,txt',
			'allowedMimeTypes'				=>	'',
			'fileUploadKey' 				=>	'userfile',
			'fileUploadBehaviour'			=>	'add_token', //can be none or add_token
			'fileUploadBeforeTokenChar'		=>	'_',
			'functionUponFileNane'			=>	'none',
			'createImage'					=>	false,
		);

		//set the $this->scaffold->params array
		if (is_array($params))
		{
			foreach ($params as $key => $value)
			{
				$defaultParams[$key] = $value;
			}
		}
		$this->params = $defaultParams;

		//instantiate the $_resultString object
		$stringClass = 'Lang_'.$this->params['language'].'_UploadStrings';
		if (!class_exists($stringClass))
		{
			$stringClass = 'Lang_En_UploadStrings';
		}
		$this->_resultString = new $stringClass();

		$this->setDirectory($directory);

	}

	//set a new value for one element of the $params array
	public function setParam($key,$value)
	{
		if (array_key_exists($key,$this->params))
		{
			$this->params[$key] = $value;
		}
	}

	//change a resulting string
	public function setString($key,$value)
	{
		$this->_resultString->string[$key] = $value;
	}
	
	//obtain the current directory
	public function setDirectory($directory = null)
	{	
		$relDir = (strcmp($directory,"") !== 0) ? $this->addTrailingSlash($directory) : null;
		$absDir = $this->addTrailingSlash($this->base.$directory);
		
		if (@is_dir($absDir))
		{
			if ($this->isValidFolder($absDir))
			{
				$this->directory = $relDir;
				return true;
			}
			else
			{
				$this->notice = $this->_resultString->getString('not-child');
			}
		}
		else
		{
			$this->directory = null;
			$this->notice = $this->_resultString->getString('not-dir');
		}
		return false;
	}
	
	//check if $folder is a folder and is subfolder of $this->base
	public function isValidFolder($folder)
	{
		if (@is_dir($folder))
		{
			$folder = $this->addTrailingSlash(realpath($folder));
			if ($this->isMatching($folder)) return true; 
		}
		return false;
	}

	public function isMatching($path)
	{
		if (preg_match($this->pattern,$path))
		{
			if (strstr($path,'..')) return false;
			return true;
		}
		return false;
	}

	public function getDirectory() {
		return $this->directory;
	}

	public function getBase()
	{
		return $this->base;
	}

	public function setBase($path)
	{
		$this->base = $this->addTrailingSlash($path);

		//set the match pattern
		$tmp = str_replace(self::DS,'\\'.self::DS,$this->base);
		$this->pattern = "/^(".$tmp.")/";
	}

	public function getSubDir() {
		return $this->subDir;
	}
	
	public function getRelSubDir()
	{
		return $this->relSubDir;
	}

	public function getFiles() {
		return $this->files;
	}

	public function getRelFiles()
	{
		return $this->relFiles;
	}

	public function getParentDir() {
		return $this->parentDir;
	}

	//add the trailing slash to the string
	protected function addTrailingSlash($string)
	{
		$finalChar = $string[strlen($string) - 1];
		if (strcmp($finalChar,self::DS) !== 0)
		{
			return $string.self::DS;
		}
		return $string;
	}

	protected function urlDeep($dir) { #funzione per creare l'indirizzo completo della cartella all'interno della quale voglio entrare
		#$dir:cartella all'interno della quale voglio entrare
		return $this->base.$this->directory.$dir.self::DS;
	}

	public function listFiles() { #creo la lista di file e cartelle all'interno della directory corrente
		$this->subDir = $this->relSubDir = $this->files = $this->relFiles = array();
		
		$items = scandir($this->base.$this->directory);
		foreach( $items as $this_file ) {
			if( strcmp($this_file,".") !== 0 && strcmp($this_file,"..") !== 0 ) {
				if (@is_dir($this->urlDeep($this_file))) {
					$this->subDir[] = $this_file;
					$this->relSubDir[] = $this->directory.$this_file;
				} else {
					$this->files[] = $this_file;
					$this->relFiles[] = $this->directory.$this_file;
				}
			}
		}
		//get the parent dir
		$this->parentDir();
	}

	//get the extension of the file
	public function getFileExtension($file)
	{
		if (strstr($file,'.'))
		{
			$extArray = explode('.', $file);
			return strtolower(end($extArray));
		}
		return '';
	}

	//get the file name without the extension
	public function getNameWithoutFileExtension($file)
	{
		if (strstr($file,'.'))
		{
			$copy = explode('.', $file);
			array_pop($copy);
			return implode('.',$copy);
		}
		return $file;
	}

	//get a not existing file name if the one retrieved from the upload process already exists in the current directory
	public function getUniqueName($file,$int = 0)
	{
		$fileNameWithoutExt = $this->getNameWithoutFileExtension($file);
		$extension = $this->getFileExtension($file);
		$token = $int === 0 ? null : $this->params['fileUploadBeforeTokenChar'].$int;

		$dotExt = strcmp($extension,'') !== 0 ? ".$extension" : null;
		
		$newName = $fileNameWithoutExt.$token.$dotExt;
		if (!file_exists($this->base.$this->directory.$newName))
		{
			return $newName;
		}
		else
		{
			return $this->getUniqueName($file,$int+1);
		}
		
	}

	//get a not existing folder name
	public function getUniqueFolderName($folder,$int = 0)
	{
		$token = $int === 0 ? null : $this->params['fileUploadBeforeTokenChar'].$int;
		
		$newName = $folder.$token;
		if (!@is_dir($this->base.$this->directory.$newName))
		{
			return $newName;
		}
		else
		{
			return $this->getUniqueFolderName($folder,$int+1);
		}
		
	}
	
	protected function parentDir() { #individuo la cartella madre
	
		$folders = explode(self::DS,$this->directory);
		array_pop($folders);
		array_pop($folders);
		$parent = implode(self::DS,$folders);
		$parent = (strcmp($parent,"") !== 0) ? $this->addTrailingSlash($parent) : null;

		if ($this->isValidFolder($this->base.$parent))
		{
			$this->parentDir = $parent;
		}
		else
		{
			$this->parentDir = null;
		}
	}

	//create the $name subfolder of the $this->directory folder
	public function createFolder($name) { #funzione per creare una cartella nella directory corrente
		$name = basename($name);
		if (strcmp(trim($name),'') !== 0)
		{
			if (is_writable($this->base.$this->directory))
			{
				$path = $this->base.$this->directory.$name;
				
				if ($this->isMatching($path))
				{
					if (!file_exists($path))
					{
						if (@mkdir($path,$this->params['filesPermission']))
						{
							@chmod($path, $this->params['filesPermission']);
							$this->notice = $this->_resultString->getString('executed');
							return true;
						}
						else
						{
							$this->notice = $this->_resultString->getString('error');
						}
					}
					else
					{
						$this->notice = $this->_resultString->getString('dir-exists');
					}
				}
				else
				{
					$this->notice = $this->_resultString->getString('not-child');
				}
			}
			else
			{
				$this->notice = $this->_resultString->getString('not-writable');
			}
		}
		else
		{
			$this->notice = $this->_resultString->getString('no-folder-specified');
		}
		return false;
	}

	//check if the $name folder is empty or not
	public function isEmpty($name)
	{
		$items = scandir($name);
		foreach( $items as $this_file ) {
			if( strcmp($this_file,".") !== 0 && strcmp($this_file,"..") !== 0 ) {
				return false;
			}
		}
		return true;
	}

	public function removeFile($name)
	{
		$name = basename($name);
		if (strcmp(trim($name),'') !== 0)
		{
			$path = $this->base.$this->directory.$name;
			if ($this->isMatching($path))
			{
				if ($this->removeAbsFile($path)) return true;
			}
			else
			{
				$this->notice = $this->_resultString->getString('not-child');
			}
		}
		else
		{
			$this->notice = $this->_resultString->getString('no-file-specified');
		}
		return false;
	}

	//remove the $name file
	protected function removeAbsFile($name)
	{
		if (strcmp(trim($name),'') !== 0)
		{
			if (is_writable($name))
			{
				if (@unlink($name))
				{
					$this->notice = $this->_resultString->getString('executed');
					return true;
				}
				else
				{
					$this->notice = $this->_resultString->getString('error');
				}
			}
			else
			{
				$this->notice = $this->_resultString->getString('not-writable-file');
			}
		}
		else
		{
			$this->notice = $this->_resultString->getString('no-file-specified');
		}
		return false;
	}

	public function removeFolder($name)
	{
		$name = basename($name);
		if (strcmp(trim($name),'') !== 0)
		{
			$dir = $this->base.$this->directory.$name;
			if ($this->isMatching($dir))
			{
				if ($this->removeAbsFolder($dir)) return true;
			}
			else
			{
				$this->notice = $this->_resultString->getString('not-child');
			}
		}
		else
		{
			$this->notice = $this->_resultString->getString('no-folder-specified');
		}
		return false;
	}
	
	//remove the $name folder
	protected function removeAbsFolder($name) {
		if (strcmp(trim($name),'') !== 0) {
			if (is_writable($name))
			{
				if ($this->isEmpty($name))
				{
					if (@rmdir($name))
					{
						$this->notice = $this->_resultString->getString('executed');
						return true;
					}
					else
					{
						$this->notice = $this->_resultString->getString('error');
					}
				}
				else
				{
					$this->notice = $this->_resultString->getString('not-empty');
				}
			}
			else
			{
				$this->notice = $this->_resultString->getString('not-writable');
			}
		}
		else
		{
			$this->notice = $this->_resultString->getString('no-folder-specified');
		}
		return false;
	}

	//remove all the files that are not inside the $list argument
	public function removeFilesNotInTheList($list = array())
	{
		$this->listFiles();
		$files = $this->getFiles();
		foreach ($files as $file)
		{
			if (!in_array($file,$list))
			{
				$this->removeFile($file);
			}
		}
	}

	//upload a file in the current directory
	//$fileName: name of the file
	public function uploadFile($fileName = null)
	{
		$userfile = $this->params['fileUploadKey'];
		
		if(strcmp(trim($_FILES[$userfile]["name"]),"") !== 0)
		{
			$nameFromUpload = basename($_FILES[$userfile]["name"]);

			$ext = $this->getFileExtension($nameFromUpload);
			$nameWithoutExtension = $this->getNameWithoutFileExtension($nameFromUpload);

			$dotExt = strcmp($ext,'') !== 0 ? ".$ext" : null;

			//check if the "functionUponFileNane" function exists
			if (!function_exists($this->params['functionUponFileNane'])) {
				throw new Exception('Error in <b>'.__METHOD__.'</b>: function <b>'.$this->params['functionUponFileNane']. '</b> does not exist');
			}

			//check if the fileinfo extension is loaded
			if (strcmp($this->params['allowedMimeTypes'],'') !== 0 and !extension_loaded('fileinfo')) {
				throw new Exception('Error in <b>'.__METHOD__.'</b>: no MIME type check is possible because the <b>fileinfo</b> extension is not loaded');
			}
			
			$nameWithoutExtension = call_user_func($this->params['functionUponFileNane'],$nameWithoutExtension);
			
			$fileName = isset($fileName) ? $fileName.$dotExt : $nameWithoutExtension.$dotExt;
			
			$this->fileName = $fileName;
			$this->ext = $ext;
			
			switch($this->params['fileUploadBehaviour'])
			{
				case 'none':
					break;
				case 'add_token':
					$this->fileName = $this->getUniqueName($this->fileName);
					$fileName = $this->fileName;
					break;
			}
		
			if(@is_uploaded_file($_FILES[$userfile]["tmp_name"])) {
				if ($_FILES[$userfile]["size"] <= $this->params['maxFileSize'])
				{
					//check the extension of the file
					$AllowedExtensionsArray = explode(',',$this->params['allowedExtensions']);
					
					if (strcmp($this->params['allowedExtensions'],'') === 0 or in_array($ext,$AllowedExtensionsArray))
					{
						if (strcmp($this->params['allowedMimeTypes'],'') !== 0)
						{
							//get the MIME type of the file
							$finfo = finfo_open(FILEINFO_MIME_TYPE);
							$MIMEtype = finfo_file($finfo, $_FILES[$userfile]["tmp_name"]);
							$this->mimeType = $MIMEtype;
							finfo_close($finfo);
						}
						
						$AllowedMimeTypesArray = explode(',',$this->params['allowedMimeTypes']);
						
						if (strcmp($this->params['allowedMimeTypes'],'') === 0 or in_array($MIMEtype,$AllowedMimeTypesArray))
						{
							//check if the file doesn't exist
							if (!file_exists($this->base.$this->directory.$fileName))
							{
								if (@move_uploaded_file($_FILES[$userfile]["tmp_name"],$this->base.$this->directory.$fileName))
								{
									if ($this->params['createImage'])
									{
										//create the image
										$basePath = $this->base.$this->directory;
										$thumb = new Image_Gd_Thumbnail($basePath);
										$thumb->render($fileName,$this->base.$this->directory.$fileName);
									}

									if ($this->params['changeFilePermission'])
									{
										@chmod($this->base.$this->directory.$fileName, $this->params['filesPermission']);
									}
									$this->notice = $this->_resultString->getString('executed');
									return true;
								}
								else
								{
									$this->notice = $this->_resultString->getString('error');
								}
							}
							else
							{
								$this->notice = $this->_resultString->getString('file-exists');
							}
						}
						else
						{
							$this->notice = $this->_resultString->getString('not-allowed-mime-type');
						}
					}
					else
					{
						$this->notice = $this->_resultString->getString('not-allowed-ext');
					}
				}
				else
				{
					$this->notice = $this->_resultString->getString('size-over');
				}
			}
			else
			{
				$this->notice = $this->_resultString->getString('no-upload-file');
			}
		}
		else
		{
			$this->notice = $this->_resultString->getString('no-upload-file');
		}
		return false;
	}

	//update the folder tree
	public function updateTree() {

		if (isset($_POST[$this->params['delFolderAction']])) {
			$this->removeFolder($_POST[$this->params['delFolderAction']]);
		}

		if (isset($_POST[$this->params['delFileAction']])) {
			$this->removeFile($_POST[$this->params['delFileAction']]);
		}

		if (isset($_POST[$this->params['createFolderAction']])) {
			$this->createFolder($_POST['folderName']);
		}

		if (isset($_POST[$this->params['uploadFileAction']])) {
			$this->uploadFile();
		}

	}
}
