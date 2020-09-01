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

class Users_CheckAdmin {
	
	public static $idUserFieldName = "id_user";
	public static $usernameFieldName = "username";
	public static $passwordFieldName = "password";
	public static $statusFieldName = "has_confirmed";
	public static $statusFieldActiveValue = "0";
	public static $idGroupFieldName = "id_group";
	public static $groupsFieldName = "name";
	
	public static $usersModel = null;
	public static $groupsModel = null;
	public static $sessionsModel = null;
	public static $accessesModel = null;
	
	public $status = array();
	
	private $users;
	private $groups;
	private $sessions;
	private $accesses;
	
	protected $lastAccessId = null;
	
	protected $_sessionsTable; //table containing all the sessions
	protected $_usersTable;	//table containing all the users
	protected $_groupsTable; //table containing all the groups
	protected $_manyToManyTable; //table for many to many relationships
	protected $_accessesTable; //table containing all the accesses to admin side
	protected $uid = null;
	protected $_token = null; //token used in order to defense against CSRF (cross sire request forgeries)
	protected $_login; //login action
	protected $_main; //main action
	protected $_retype; //retype (the password) action
	protected $_db; //reference to the database layer class
	protected $_params = array(); //the parameters of the object

	public function __construct($params = null) {
		$this->_params = $params;

		$this->_sessionsTable = $params['sessionsTable'];
		$this->_usersTable = $params['usersTable'];
		$this->_groupsTable = $params['groupsTable'];
		$this->_manyToManyTable = $params['manyToManyTable'];
		$this->_accessesTable = $params['accessesTable'];
		$this->_login = Url::getRoot(null) . $params['users_controller'] . '/' . $params['users_login_action'] ;
		$this->_main = Url::getRoot(null) . $params['panel_controller'] . '/' . $params['panel_main_action'] ;
		$this->_retype = Url::getRoot(null) . $params['users_controller'] . '/' . $params['hijacking_action'] ;
		$this->_db = Factory_Db::getInstance($params['database_type']);
		
		if (isset(self::$usersModel))
			$this->users = new self::$usersModel();
		
		if (isset(self::$sessionsModel))
			$this->sessions = new self::$sessionsModel();
		
		if (isset(self::$accessesModel))
			$this->accesses = new self::$accessesModel();
		
		if (isset(self::$groupsModel))
			$this->groups = new self::$groupsModel();
	}

	private function acquireCookie() { #obtain cookie
		#cookie
		$this->uid = NULL;
		global $_COOKIE;
		
		if (isset($_GET[$this->_params['cookie_name']]) and Params::$allowSessionIdFromGet)
		{
			$this->uid = sanitizeAlnum($_GET[$this->_params['cookie_name']]);
		}
		else if (isset($_COOKIE[$this->_params['cookie_name']]))
		{
			$this->uid = sanitizeAlnum($_COOKIE[$this->_params['cookie_name']]);
		}
		else
		{
			$this->uid = null;
		}
// 		$this->uid = isset($_COOKIE[$this->_params['cookie_name']]) ? sanitizeAlnum($_COOKIE[$this->_params['cookie_name']]) : null;
	}

	public function setParam($key, $value)
	{
		$this->_params[$key] = $value;
	}

	public function getLastAccessId()
	{
		return $this->lastAccessId;
	}
	
	public function getUid()
	{
		return $this->uid;
	}
	
	private function cleanSessions()
	{
		#cancello le sessioni scadute
		
		if (isset(self::$sessionsModel))
		{
			$row = $this->sessions->clear()->where(array(
				"uid"	=>	$this->uid,
			))->send();
		}
		else
			$row = $this->_db->select($this->_sessionsTable,'creation_date',"uid='".$this->uid."'");
		
		if ($row)
		{
			if ($row[0][$this->_sessionsTable]['creation_date'])
			{
				if($row[0][$this->_sessionsTable]['creation_date'] + $this->_params['session_expire'] <= time())
				{
					setcookie($this->_params['cookie_name'],'',time()-3600,$this->_params['cookie_path']);
				}
			}
		}
		$this->_db->del($this->_sessionsTable,"creation_date + " . $this->_params['session_expire'] . " <= ".time());
	}

	public function checkStatus()
	{ #controlla se l'utente è già loggato
		$this->acquireCookie(); #ottengo il cookie
		$this->cleanSessions(); #elimino le sessioni vecchie
		
		if (isset(self::$usersModel))
		{
			$row=$this->users->clear()->select($this->_usersTable.".*,".$this->_sessionsTable.".*")->inner($this->_sessionsTable)->on($this->_sessionsTable.".".self::$idUserFieldName." = ".$this->_usersTable.".".self::$idUserFieldName)->where(array(
				$this->_sessionsTable.".uid"=>	$this->uid,
			))->send(false);
// 			print_r($row);
		}
		else
			$row=$this->_db->select($this->_usersTable.','.$this->_sessionsTable,$this->_usersTable.'.'.self::$idUserFieldName.','.self::$usernameFieldName.',token,user_agent',$this->_usersTable.".".self::$idUserFieldName."=".$this->_sessionsTable.".".self::$idUserFieldName." and uid='".$this->uid."'",null,null,null,array(),array(),array(),false);
		
// 		$row=$this->_db->select($this->_usersTable.','.$this->_sessionsTable,$this->_usersTable.'.'.self::$idUserFieldName.','.self::$usernameFieldName.',token,user_agent',$this->_usersTable.".".self::$idUserFieldName."=".$this->_sessionsTable.".".self::$idUserFieldName." and uid='".$this->uid."'");

		if (count($row) === 1 and $row !== false)
		{
			$this->status['user']=$row[0][self::$usernameFieldName];
			$this->status['status']='logged';
			$this->status['id_user']=$row[0][self::$idUserFieldName];
			$this->status['user_agent'] = $row[0]['user_agent'];
			$this->status['token'] = $row[0]['token'];
			$this->obtainGroups();
		} else {
			$this->status['user']='sconosciuto';
			$this->status['status']='not-logged';
			$this->status['id_user']='';
			$this->status['user_agent']='';
			$this->status['token'] = '';
			$this->status['groups'] = array();
		}
	}

	public function redirect($val,$time = 3) { #fa il redirect dell'utente
		if ($val === 'logged') {
			header('Refresh: '.$time.';url='.$this->_main);
			if ($time !== 0) echo "You are already logged, ".$this->status['user']."..";
		} else if ($val === 'accepted') {
			header('Refresh: '.$time.';url='.$this->_main);
			if ($time !== 0) echo "Hi ".$this->status['user']."..";
		} else if ($val === 'login-error') {
			header('Refresh: '.$time.';url='.$this->_login);
			if ($time !== 0) echo "Wrong username or password...";
		} else if ($val === 'not-logged') {
			header('Refresh: '.$time.';url='.$this->_login);
			if ($time !== 0) echo "Limited access... sorry";
		} else if ($val === 'not-authorized') {
			header('Refresh: '.$time.';url='.$this->_main);
			if ($time !== 0) echo "Your account doesn't allow you to manage this page.. sorry!";
		} else if ($val === 'stolen') {
			header('Refresh: '.$time.';url='.$this->_login);
			if ($time !== 0) echo "Your session have been probably intercepted! Please login another time.";
		} else if ($val === 'retype') {
			header('Refresh: '.$time.';url='.$this->_retype);
			if ($time !== 0) echo "Your session have been probably intercepted. Please type your password another time.";
		} else if ($val === 'wait') {
			header('Refresh: '.$time.';url='.$this->_login);
			if ($time !== 0) echo "You have to wait ".$this->_params['time_after_failure']." seconds before you can try to login another time";
		}
		exit;
	}

	//obtain the group of the user
	public function obtainGroups()
	{
		if (isset(self::$groupsModel))
		{
			$groups = $this->groups->clear()
				->inner($this->_manyToManyTable)->on($this->_groupsTable.'.'.self::$idGroupFieldName.'='.$this->_manyToManyTable.'.'.self::$idGroupFieldName)
				->inner($this->_usersTable)->on($this->_usersTable.'.'.self::$idUserFieldName.'='.$this->_manyToManyTable.'.'.self::$idUserFieldName)
				->where(array(
					$this->_usersTable.'.'.self::$idUserFieldName	=>	$this->status['id_user']
				))->send();
		}
		else
		{
			$tables = $this->_usersTable.','.$this->_groupsTable.','.$this->_manyToManyTable;
			$fields = $this->_groupsTable.'.'.self::$groupsFieldName;
			$where = $this->_usersTable.'.'.self::$idUserFieldName.'='.$this->_manyToManyTable.'.'.self::$idUserFieldName.' and '.$this->_groupsTable.'.'.self::$idGroupFieldName.'='.$this->_manyToManyTable.'.'.self::$idGroupFieldName.' and '.$this->_usersTable.'.'.self::$idUserFieldName.'='.$this->status['id_user'];
			$groups = $this->_db->select($tables,$fields,$where);
		}
		
// 		$tables = $this->_usersTable.','.$this->_groupsTable.','.$this->_manyToManyTable;
// 		$fields = $this->_groupsTable.'.'.self::$groupsFieldName;
// 		$where = $this->_usersTable.'.'.self::$idUserFieldName.'='.$this->_manyToManyTable.'.'.self::$idUserFieldName.' and '.$this->_groupsTable.'.'.self::$idGroupFieldName.'='.$this->_manyToManyTable.'.'.self::$idGroupFieldName.' and '.$this->_usersTable.'.'.self::$idUserFieldName.'='.$this->status['id_user'];
// 		$groups = $this->_db->select($tables,$fields,$where);
		
		$this->status['groups'] = array();
		foreach ($groups as $group)
		{
			$this->status['groups'][] = $group[$this->_groupsTable][self::$groupsFieldName];
		}
	}

	//$groups: string with name of groups separated by comma; ex: base,root,users
	public function checkAccess($groups)
	{
		$groupsArray = explode (',',$groups);
		foreach ($this->status['groups'] as $group)
		{
			if (in_array($group,$groupsArray)) return true; 
		}
		return false;
	}

	//check that the user is logged and, if present, check the group of the user (if loggeg)
	//$groups: comma-separated list of groups whose users can access the page
	//$time: time before the redirect is carried out
	public function check($groups  = null, $time = 0)
	{
		$this->checkStatus();
		if (strcmp($this->status['status'],'not-logged') === 0)
		{
			$this->redirect('not-logged',$time);
		}
		else if (strcmp($this->status['status'],'logged') === 0)
		{
			if ($this->_params['hijacking_check'])
			{
				if (!$this->checkHijacking())
				{
					if ($this->_params['on_hijacking_event'] === 'forceout')
					{
						$this->logout();
						$this->redirect('stolen',$time);
					}
					else if ($this->_params['on_hijacking_event'] === 'redirect')
					{
						$this->redirect('retype',$time);
					}
				}
			}
// 			$this->obtainGroups();
			if (isset($groups))
			{
				$permission = $this->checkAccess($groups);
				if (!$permission) $this->redirect('not-authorized',$time);
			}
		}
	}

	//check if someone have stolen your uid
	private function checkHijacking()
	{
		if (array_key_exists('user_agent',$this->status))
		{
			if (strcmp($this->status['user_agent'],'') !== 0)
			{
				if (strcmp($this->status['user_agent'],getUserAgent()) === 0)
				{
					return true;
				}
			}
		}
		return false;
	}

	//check CSRF
	//$token: token to check
	public function checkCSRF($token)
	{
		if (strcmp($this->status['token'],'') !== 0)
		{
			if (strcmp($this->status['token'],$token) === 0)
			{
				return true;
			}
		}
		return false;
	}

	//get an array containing all the users currently logged
	public function getUsersLogged()
	{
		$usersLogged = array();
		if (isset(self::$usersModel))
		{
			$data=$this->users->clear()->inner($this->_sessionsTable)->on($this->_sessionsTable.".".self::$idUserFieldName." = ".$this->_usersTable.".".self::$idUserFieldName)->send();
// 			print_r($row);
		}
		else
			$data=$this->_db->select($this->_usersTable.','.$this->_sessionsTable,'DISTINCT '.$this->_usersTable.'.'.self::$usernameFieldName,$this->_usersTable.".".self::$idUserFieldName."=".$this->_sessionsTable.".".self::$idUserFieldName);
		
// 		$data=$this->_db->select($this->_usersTable.','.$this->_sessionsTable,'DISTINCT '.$this->_usersTable.'.'.self::$usernameFieldName,$this->_usersTable.".".self::$idUserFieldName."=".$this->_sessionsTable.".".self::$idUserFieldName);
		
		foreach ($data as $row)
		{
			$usersLogged[] = $row[$this->_usersTable][self::$usernameFieldName];
		}
		
		$usersLogged = array_unique($usersLogged);
		
		return $usersLogged;
	}
	
	//get the password of the current user
	public function getPassword()
	{
		if (isset(self::$usersModel))
		{
			$row=$this->users->clear()->where(array(
				self::$idUserFieldName	=>	$this->status['id_user'],
			))->send();
		}
		else
			$row=$this->_db->select($this->_usersTable,self::$passwordFieldName,self::$idUserFieldName."=".$this->status['id_user']);
		
		if ($row !== false)
		{
			return $row[0][$this->_usersTable][self::$passwordFieldName];
		}
		else
		{
			return false;
		}
	}

	private function checkPassword($user,$pwd) { #check username and password

		if (!in_array($this->_params['password_hash'],Params::$allowedHashFunc))
		{
			throw new Exception('Error in '.__METHOD__.' : the hash func has to be '.implode(' or ',Params::$allowedHashFunc));
		}
		//calculate the hash of the password
		$pwd = call_user_func($this->_params['password_hash'],$pwd);
		
		if (isset(self::$usersModel))
		{
			$row=$this->users->clear()->where(array(
				self::$usernameFieldName	=>	$user,
				self::$passwordFieldName	=>	$pwd,
				self::$statusFieldName		=>	self::$statusFieldActiveValue
			))->send();
		}
		else
			$row=$this->_db->select($this->_usersTable,$this->_usersTable.'.'.self::$idUserFieldName.','.self::$usernameFieldName.','.self::$passwordFieldName.'',self::$usernameFieldName."=\"".$user."\" and ".self::$passwordFieldName."=\"".$pwd."\" and ".self::$statusFieldName."='".self::$statusFieldActiveValue."'");
		
// 		$row=$this->_db->select($this->_usersTable,$this->_usersTable.'.'.self::$idUserFieldName.','.self::$usernameFieldName.','.self::$passwordFieldName.'',self::$usernameFieldName."=\"".$user."\" and ".self::$passwordFieldName."=\"".$pwd."\" and ".self::$statusFieldName."='".self::$statusFieldActiveValue."'");
		
		if (count($row) === 1 and $row !== false)
		{
			$this->status['user'] = $row[0][$this->_usersTable][self::$usernameFieldName];
			$this->status['status'] = 'accepted';
			$this->status['id_user'] = $row[0][$this->_usersTable][self::$idUserFieldName];
		}
		else
		{
			$this->status['user'] = 'unknown';
			$this->status['status'] = 'login-error';
			$this->status['id_user'] = '';
			
			if (isset(self::$usersModel))
			{
				$res = $this->users->clear()->select(self::$idUserFieldName)->where(array(
					self::$usernameFieldName	=>	$user,
				))->send(false);
			
				if (count($res) > 0)
				{
					$this->users->setValues(array(
						"last_failure"	=>	time(),
					));
					
					$this->users->update($res[0][self::$idUserFieldName]);
				}
			}
			else
			{
				if ($this->_db->recordExists($this->_usersTable,self::$usernameFieldName,$user))
				{
					$this->_db->update($this->_usersTable,'last_failure',array(time()),self::$usernameFieldName.'="'.$user.'"');
				}
			}
		}
	}

	//check that enough time is passed since the last failure of the user
	private function checkLastFailure($user)
	{
		//current time
		$now = time();
		//max time
		$max = $now - $this->_params['time_after_failure'];
		
// 		$data = $this->_db->select($this->_usersTable,'last_failure',self::$usernameFieldName.'="'.$user.'"');
		
		if (isset(self::$usersModel))
			$data = $this->users->clear()->where(array(
				self::$usernameFieldName	=>	$user,
			))->send();
		else
			$data = $this->_db->select($this->_usersTable,'last_failure',self::$usernameFieldName.'="'.$user.'"');
		
		if (count($data) === 1 and $data !== false)
		{
			if ($data[0][$this->_usersTable]['last_failure'] < $max)
			{
				return true;
			}
			return false;
		}
		else
		{
			return true;
		}
	}

	public function login($user,$pwd)
	{
		$user = sanitizeAll($user);
		$this->checkStatus();
		//check if already logged
		if ($this->status['status'] === 'logged')
		{
// 			$this->redirect('logged');
			return 'logged';
		}
		else
		{
			if ($this->checkLastFailure($user))
			{
				$this->checkPassword($user,$pwd);
				if ($this->status['status']==='accepted')
				{
					$this->uid = md5(randString(10).uniqid(mt_rand(),true));
					$this->_token = md5(randString(12));
					$userAgent = getUserAgent();
					
					//set the expiration time
					$expirationTime = $this->_params['cookie_permanent'] ? time() + $this->_params['session_expire'] : 0;
					
					$this->_db->insert($this->_sessionsTable,self::$idUserFieldName.',uid,token,creation_date,user_agent',array($this->status['id_user'],$this->uid,$this->_token,time(),$userAgent));
					setcookie($this->_params['cookie_name'],$this->uid,$expirationTime,$this->_params['cookie_path']); #magic cookie
					$this->updateAccesses();
					
					if (!$this->_params['allow_multiple_accesses'])
					{
						$this->_db->del($this->_sessionsTable,self::$idUserFieldName.'='.$this->status['id_user']." AND uid != '".$this->uid."'");
// 						$this->_db->del($this->_sessionsTable,self::$idUserFieldName.'='.$this->status['id_user'].' AND uid != "'.$this->uid.'"');
					}
					else
					{
						//check the maximum number of sessions for the same user
						$maxAllowedSessionNumber = (int)$this->_params['max_client_sessions'];
						
						if ($maxAllowedSessionNumber > 0)
						{
// 							$rows = $this->_db->select($this->_sessionsTable,"creation_date",self::$idUserFieldName.'='.$this->status['id_user'],null,"creation_date desc",$maxAllowedSessionNumber);
							
							if (isset(self::$sessionsModel))
							{
								$rows = $this->sessions->clear()->where(array(
									self::$idUserFieldName	=>	$this->status['id_user'],
								))->orderBy("creation_date desc")->limit($maxAllowedSessionNumber)->send();
							}
							else
								$rows = $this->_db->select($this->_sessionsTable,"creation_date",self::$idUserFieldName.'='.$this->status['id_user'],null,"creation_date desc",$maxAllowedSessionNumber);
							
							if (count($rows))
							{
								$beforeTime = $rows[count($rows)-1][$this->_sessionsTable]["creation_date"];
								
								$this->_db->del($this->_sessionsTable,self::$idUserFieldName.'='.$this->status['id_user'].' AND creation_date < '.$beforeTime);
							}
						}
					}
// 					$this->redirect('accepted');
					return 'accepted';
				}
				else if ($this->status['status']==='login-error')
				{
// 					$this->redirect('login-error');
					return 'login-error';
				}
			}
			else
			{
// 				$this->redirect('wait');
				return 'wait';
			}
		}
// 		$this->redirect('login-error');
		return 'login-error';
	}

	private function updateAccesses()
	{
		if (strcmp($this->_accessesTable,"") !== 0)
		{
			$ip=getIp(); #ip
			$date=date('d'). "-" . date('m') . "-" . date('Y'); #date
			$ora=date('H') . ":" . date('i'); #time
			$values=array($ip,$date,$ora,$this->status['user']);
			$res=$this->_db->insert($this->_accessesTable,'ip,data,ora,'.self::$usernameFieldName,$values);
			$this->lastAccessId = $this->_db->lastId();
		}
	}

	//force out an user
	//$id: the id of the user
	public function forceOut($id)
	{
		$id = (int)$id;
		if ($this->_db->del($this->_sessionsTable,'id_user='.$id))
		{
			return true;
		}
		return false;
	}

	public function logout()
	{
		$this->checkStatus();
		if ($this->status['status'] === 'logged')
		{
			setcookie ($this->_params['cookie_name'], "", time() - 3600,$this->_params['cookie_path']);
			
			if (!$this->_params['allow_multiple_accesses'])
			{
				$delClause = self::$idUserFieldName.'='.$this->status['id_user'];
			}
			else
			{
				$delClause = "uid = '".$this->uid."'";
// 				$delClause = 'uid = "'.$this->uid.'"';
			}
			
			if ($this->_db->del($this->_sessionsTable, $delClause))
			{
				return 'was-logged';
			} 
			else 
			{
				return 'error';
			}
		}
		else
		{
			return 'not-logged';
		}
	}

}
