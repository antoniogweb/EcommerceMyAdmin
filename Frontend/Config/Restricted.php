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



//RESRICTED ACCESS PARAMETERS

//define the hash algoritm to be used in order to protect your password
//only md5 and sha1 are supported
if (!defined('PASSWORD_HASH'))
	define('PASSWORD_HASH','sha1');



//ADMINISTRATOR USERS LOGIN DIRECTIVES:

//time that has to pass after a login failure before the user is allowed to try to login another time (in seconds)
define('ADMIN_TIME_AFTER_FAILURE','5');

//redirect to panel when successfully logged in:
define('ADMIN_PANEL_CONTROLLER', 'panel');
define('ADMIN_PANEL_MAIN_ACTION', 'main');

//redirect to login form if access not allowed:
define('ADMIN_USERS_CONTROLLER', 'users');
define('ADMIN_USERS_LOGIN_ACTION', 'login');

//admin cookie:
define('ADMIN_COOKIE_NAME','uid');
define('ADMIN_SESSION_EXPIRE', '86400');
define('ADMIN_COOKIE_PATH', '/');
define('ADMIN_COOKIE_DOMAIN', '');
define('ADMIN_COOKIE_SECURE', false);

//tables:
define('ADMIN_USERS_TABLE','adminusers');
define('ADMIN_GROUPS_TABLE','admingroups');
define('ADMIN_SESSIONS_TABLE','adminsessions');
define('ADMIN_MANYTOMANY_TABLE','adminusers_groups');
define('ADMIN_ACCESSES_TABLE','accesses');

//hijacking checks
define('ADMIN_HIJACKING_CHECK',true); //can be true or false
//session hijacking
//set ADMIN_ON_HIJACKING_EVENT equal to 'forceout' if you want to cause the logout of the user if there is the suspect of a session hijacking
//set ADMIN_ON_HIJACKING_EVENT equal to 'redirect' if you want to redirect the user to the ADMIN_HIJACKING_ACTION (see later) if there is the suspect of a session hijacking
define('ADMIN_ON_HIJACKING_EVENT','forceout');  //it can be 'forceout' or 'redirect'
//only if ADMIN_ON_HIJACKING_EVENT = 'redirect'
//redirect the user to ADMIN_USERS_CONTROLLER/ADMIN_HIJACKING_ACTION if there is the suspect of a session hijacking
define('ADMIN_HIJACKING_ACTION','retype');


//REGISTERED USERS LOGIN DIRECTIVES:

//set REG_ALLOW_MULTIPLE_ACCESSES to true if you want that the same user could be logged from different clients at the same time.
//If it is false, when a user makes the login with a client all the other sessions of the same users will be deleted
define("REG_ALLOW_MULTIPLE_ACCESSES", true);

//only valid if REG_ALLOW_MULTIPLE_ACCESSES is equal to true
//set the maximum number of sessions that could be active using the same account (for registered users)
//if the number of session is greater that REG_MAX_CLIENT_SESSIONS then the oldest are deleted
//set it to 0 if you want no limits in the number of sessions
define("REG_MAX_CLIENT_SESSIONS", 3);

//REGISTERED USERS LOGIN DIRECTIVES:

//time that has to pass after a login failure before the user is allowed to try to login another time (in seconds)
define('REG_TIME_AFTER_FAILURE','5');

//redirect to home when successfully logged in:
define('REG_PANEL_CONTROLLER', 'home');
define('REG_PANEL_MAIN_ACTION', 'index');

//redirect to login form if access not allowed:
define('REG_USERS_CONTROLLER', 'regusers');
define('REG_USERS_LOGIN_ACTION', 'login');

//registered cookie:
//NB: REG_COOKIE_NAME must be different from ADMIN_COOKIE_NAME!!!
define('REG_COOKIE_NAME','uidr');
define('REG_SESSION_EXPIRE', '86400');
define('REG_COOKIE_PATH', '/');
define('REG_COOKIE_DOMAIN', '');
define('REG_COOKIE_SECURE', false);

//tables:
define('REG_USERS_TABLE','regusers');
define('REG_GROUPS_TABLE','reggroups');
define('REG_SESSIONS_TABLE','regsessions');
define('REG_MANYTOMANY_TABLE','regusers_groups');
define('REG_ACCESSES_TABLE','regaccesses');

//hijacking checks
define('REG_HIJACKING_CHECK',true); //can be true or false
//session hijacking
//set ADMIN_ON_HIJACKING_EVENT equal to 'forceout' if you want to cause the logout of the user if there is the suspect of a session hijacking
//set ADMIN_ON_HIJACKING_EVENT equal to 'redirect' if you want to redirect the user to the ADMIN_HIJACKING_ACTION (see later) if there is the suspect of a session hijacking
define('REG_ON_HIJACKING_EVENT','forceout');  //it can be 'forceout' or 'redirect'
//only if ADMIN_ON_HIJACKING_EVENT = 'redirect'
//redirect the user to ADMIN_USERS_CONTROLLER/ADMIN_HIJACKING_ACTION if there is the suspect of a session hijacking
define('REG_HIJACKING_ACTION','retype');
