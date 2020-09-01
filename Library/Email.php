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

//class to send an e-mail
class Email {
	
	//sent to parameters (array)
	private $_sendTo = array();
	
	//cc parameters (array)
	private $_cc = array();
	
	//bcc parameters (array)
	private $_bcc = array();
	
	//the address of the sender
	private $_from = null;
	
	//subject (string)
	private $_subject = null;
	
	//charset
	private $_charset = "iso-8859-1";
	
	//Content-Transfer-Encoding
	private $_ctencoding = "7bit";
	
	//body
	private $_body = '';
	
	//headers
	private $_headers = null;
	
	//check flag. If _check = true than check the mail addresses
	private $_check = null;
	
	//regular expression to check each e-mail address
	private $_addressRegExp = null;
	
	//array containing all the errors encountered during the execution
	public $errorsArray = array();
	
	public function __construct($bool = true)
	{
		$this->_check = $bool;
	}
	
	//set the sentTo addresses array
	//$addresses: array of e-mail addresses or a string
	public function sendTo($addresses)
	{
		$this->_sendTo = explode(',',$addresses);
	}
	
	//set the subject
	public function subject($subject)
	{
		$this->_subject = $subject;
	}
	
	//set the cc addresses array
	//$addresses: array of e-mail addresses or a string
	public function cc($addresses)
	{
		$this->_cc = explode(',',$addresses);
	}

	//set the bcc addresses array
	//$addresses: array of e-mail addresses or a string
	public function bcc($addresses)
	{
		$this->_bcc = explode(',',$addresses);
	}

	//set the address of the sender
	public function from($address)
	{
		$this->_from = $address;
	}

	//set the charset
	public function charset($charset)
	{
		$this->_charset = $charset;
	}
	
	//set the Content-Transfer-Encoding
	public function ctencoding($ctencoding)
	{
		$this->_ctencoding = $ctencoding;
	}

	//set the text body
	public function body($body)
	{
		$this->_body = $body;
	}
	
	//set the address regular expression
	public function addressRegExp($regExp)
	{
		$this->_addressRegExp = $regExp;
	}

	//check if the mail address is valid
	public function isValidAddress($address)
	{
		
		if( preg_match( '/^[^<>]*<(.+)>$/', $address, $matches ) )
		{
			$address = $matches[1];
		}
		
		if (isset($this->_addressRegExp))
		{
			if (preg_match($this->_addressRegExp,$address))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			if (checkMail($address)) return true;
		}
		
		return false;
		
	}

	//check the addresses inside the $addresses array
	public function checkAddresses($addresses)
	{
		foreach ($addresses as $address)
		{
			if(!$this->isValidAddress($address)) return false;
		}
		return true;
	}

	//build the mail
	public function buildMail()
	{
		
		if (empty($this->_sendTo))
		{
			$this->errorsArray[] = 'no address specified';
			return false;
		}
		
		if ($this->_check)
		{
			if (!$this->checkAddresses($this->_sendTo))
			{
				$this->errorsArray[] = 'errors in the sendTo address validation';
				return false;
			}
			
			if (!empty($this->_cc))
			{
				if (!$this->checkAddresses($this->_cc))
				{
					$this->errorsArray[] = 'errors in the cc address validation';
					return false;
				}
			}

			if (!empty($this->_bcc))
			{
				if (!$this->checkAddresses($this->_bcc))
				{
					$this->errorsArray[] = 'errors in the bcc address validation';
					return false;
				}
			}
			
			if (isset($this->_from))
			{
				if (!$this->checkAddresses(array($this->_from)))
				{
					$this->errorsArray[] = 'errors in the from address validation';
					return false;
				}
			}
		}
		
		if (strcmp($this->_subject,'') === 0)
		{
			$this->errorsArray[] = 'no subject specified';
			return false;
		}
		
		$headers = null;
		if (isset($this->_from)) $headers .= "From: ".$this->_from."\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/plain; charset=\"".$this->_charset."\"\r\n";
		$headers .= "Content-Transfer-Encoding: ".$this->_ctencoding."\r\n";
		if (!empty($this->_cc)) $headers .= "CC: ".implode(',',$this->_cc)."\r\n";
		if (!empty($this->_bcc)) $headers .= "Bcc: ".implode(',',$this->_bcc)."\r\n";

		$this->_headers = $headers;

		return true;
		
	}
	
	public function send()
	{
		if (!$this->buildMail()) return false;
		
		$to = implode(',',$this->_sendTo);
		
		if (!@mail($to,$this->_subject,$this->_body,$this->_headers))
		{
			$this->errorsArray[] = 'error in the send process';
			return false;
		}	
		
		return true;
	}

}
