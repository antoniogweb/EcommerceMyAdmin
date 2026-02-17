<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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

$retryDate = null;
$throttleFile = null;

if (isset($ip) && trim($ip))
{
	$ip = trim($ip);
	
	$f = ROOT."/admin/Logs/Jail/Throttle/$ip";
	
	if (@is_file($f))
	{
		$retryDate = trim((string)file_get_contents($f));
		$throttleFile = $f;
	}
}
if (!isset($retryDate) && isset($subIp) && trim($subIp))
{
	$subIp = trim($subIp);
	
	$f = ROOT."/admin/Logs/Jail/Throttle/$subIp";
	
	if (@is_file($f))
	{
		$retryDate = trim((string)file_get_contents($f));
		$throttleFile = $f;
	}
}
if (!isset($retryDate) && isset($botName) && trim($botName))
{
	$botName = trim($botName);
	
	$f = ROOT."/admin/Logs/Jail/Throttle/$botName";
	
	if (@is_file($f))
	{
		$retryDate = trim((string)file_get_contents($f));
		$throttleFile = $f;
	}
}

if (isset($retryDate) && $retryDate !== '')
{
	$ts = strtotime($retryDate);
	
	if ($ts !== false && $ts > time())
	{
		http_response_code(429);
		header('Retry-After: ' . $retryDate);
		header('Content-Type: application/json');
		header('Cache-Control: no-store');
		
		echo json_encode([
			'error' => 'Too Many Requests',
			'message' => 'Throttle active. Retry after the date indicated in Retry-After.',
			'retry_after' => $retryDate
		]);
		
		exit;
	}
	else
	{
		if ($throttleFile && @is_file($throttleFile))
			@unlink($throttleFile);
	}
}