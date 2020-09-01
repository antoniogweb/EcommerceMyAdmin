<?php 

// All EasyGiant code is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
// See COPYRIGHT.txt and LICENSE.txt.

if (!defined('EG')) die('Direct access not allowed!');


//class containing all the PHP files that have to be loaded at the beginning of the EasyGiant execution
//the files have to be saved in Application/Include
//all the files have to be PHP files!!
class Autoload
{

	public static $files = array(
		'functions.php',
		'user.php',
		'parametri.php',
		'language.php',
	);

}