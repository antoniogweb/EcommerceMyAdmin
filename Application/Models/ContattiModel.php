<?php

if (!defined('EG')) die('Direct access not allowed!');

class ContattiModel extends Model_Tree {

	public function __construct() {
		$this->_tables = 'contatti';
		$this->_idFields = 'id_contatto';

		$this->_lang = 'It';
		
		parent::__construct();
	}

}
