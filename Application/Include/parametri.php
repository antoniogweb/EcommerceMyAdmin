<?php
// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2025  Antonio Gallo (info@laboratoriolibero.com)
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

class Parametri
{
	public static $useSMTP = false;

	public static $SMTPHost       = ""; 		// SMTP server
	public static $SMTPPort       = 25; 		// SMTP port
	public static $SMTPUsername   = "";     // SMTP server username
	public static $SMTPPassword   = "";            // SMTP server password

	public static $mailFrom = "";
	public static $mailFromName = "";
	public static $mailInvioOrdine = "";
	public static $mailInvioConfermaPagamento = "";
	public static $nomeNegozio = "";
	public static $mailReplyTo = "";
	
	public static $useHtmlExtension = true;
	public static $hideNotAllowedNodesInLists = true;
	
	public static $cartellaImmaginiGeneriche = "images/generiche";
	public static $cartellaImmaginiContenuti = "images/contents";
	public static $cartellaImmaginiNews = "images/news";
	public static $cartellaFatture = "admin/media/Fatture";
	
	public static $nomeSezioneProdotti = "prodotti";
	
	public static $hierarchicalRootTitle = "-- root --";
	
	public static $maxUploadSize = "8000000"; //usato lato server
	public static $uploadifyMaxUploadSize = "4MB"; //usato da plugin uploadify
	
	public static $durataCarrello = 31536000; //in secondi
	public static $durataWishlist = 31536000; //in secondi
	public static $ivaInclusa = false;
	public static $iva = 22; //in %
	public static $speseSpedizione = 5; //in euro
	
	public static $confirmTime = 86400; //tempo che l'utente ha per cambiare la password dall'invio della mail
	
	public static $metodiPagamento = "bonifico,contrassegno,paypal,carta_di_credito"; //metodi di pagamento accettati
	
	//paypal
	public static $useSandbox = true; //true or false
	public static $paypalSeller = ""; //the true PayPal seller account
	public static $paypalSandBoxSeller = ""; //the seller in sandbox (for the test)
	
}
