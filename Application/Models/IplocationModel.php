<?php

if (!defined('EG')) die('Direct access not allowed!');

class IplocationModel extends Model_Tree {

	public function __construct() {
		$this->_tables = 'ip_location';
		$this->_idFields = 'id_ip_location';

		parent::__construct();
	}

}
