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

function ErrorReporting() {
	if (RUNTIME_CONFIGURATION === true)
	{
		error_reporting(ERROR_REPORTING_DIRECTIVE);
		if (DISPLAY_ERRORS === 'On') {
			ini_set('display_errors','On');
		} else {
			ini_set('display_errors','Off');
		}

		if (ERROR_REPORTING_FILE === true)
		{
			if (LOG_ERROR_FILE === 'default')
			{
				ini_set('error_log',ROOT.DS.'Logs/Errors.log');
			} else {
				ini_set('error_log',LOG_ERROR_FILE);
			}
		}
	}
}
