<?php
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
