<?php

class Nexi
{
	public $ALIAS = "";
	public $CHIAVESEGRETA = "";
	public $requestUrl = "";
	public $merchantServerUrl = "";
	public $okUrl = "";
	public $errorUrl = "";
	public $notifyUrl = "";
	public $statoNotifica = "";
	public $statoCheckOrdine = "";
	
	private $urlPagamento = null;
	private $logFile = "";
	private $ordine = null;
	
	public function __construct($ordine = array())
	{
		$pagamento = PagamentiModel::g(false)->where(array(
			"codice"	=>	"carta_di_credito"
		))->record();
		
		$this->CHIAVESEGRETA = $pagamento["chiave_segreta"];
		$this->ALIAS = $pagamento["alias_account"];
// 		echo $this->CHIAVESEGRETA;
		if ((int)$pagamento["test"])
			$this->requestUrl = "https://int-ecommerce.nexi.it/ecomm/ecomm/DispatcherServlet";
		else
			$this->requestUrl = "https://ecommerce.nexi.it/ecomm/ecomm/DispatcherServlet";
		
		$this->merchantServerUrl = Domain::$name;
		
		if (!empty($ordine) && isset($ordine["cart_uid"]))
		{
			$this->okUrl = "grazie-per-l-acquisto-carta?cart_uid=".$ordine["cart_uid"];
			$this->notifyUrl = Url::getRoot()."notifica-pagamento-carta?cart_uid=".$ordine["cart_uid"];
			$this->errorUrl = "";
			$this->ordine = $ordine;
		}
		
		$this->logFile = ROOT."/Logs/.ipncarta_results.log";
	}
	
	public function getPulsantePaga()
	{
		if (!$this->urlPagamento)
			$this->getUrlPagamento();
		
		$urlPagamento = $this->urlPagamento;
		
		$path = tpf("/Elementi/Pagamenti/Pulsanti/nexi.php");
		
		if (file_exists($path))
		{
			ob_start();
			include $path;
			$pulsante = ob_get_clean();
		}
		
		return $pulsante;
	}
	
	public function getUrlPagamento()
	{
		$notifyUrl = $this->notifyUrl;
		$importo = str_replace(".","",$this->ordine["total"]);
		
		// Parametri facoltativi
		$facoltativi = array(
			'mail' => $this->ordine["email"],
			'languageId' => "ITA",
			'descrizione' => "Ordine ".$this->ordine["id_o"],
			"nome"	=>	$this->ordine["nome"],
			"cognome"	=>	$this->ordine["cognome"],
			'OPTION_CF' => $this->ordine["codice_fiscale"],
			'urlpost' => $notifyUrl,
		);
		
		$this->urlPagamento = $this->creaUrlPagamento($this->ordine["id_o"], $importo, "EUR", $facoltativi);
		
		return $this->urlPagamento;
	}
	
	public function creaUrlPagamento($codiceTransazione, $importo, $divisa = "EUR", $facoltativi = array())
	{
		$codTrans = $codiceTransazione;

		// Calcolo MAC
		$mac = sha1('codTrans=' . $codTrans . 'divisa=' . $divisa . 'importo=' . $importo . $this->CHIAVESEGRETA);

		// Parametri obbligatori
		$obbligatori = array(
			'alias' => $this->ALIAS,
			'importo' => $importo,
			'divisa' => $divisa,
			'codTrans' => $codTrans,
			'url' => $this->merchantServerUrl . "/" . $this->okUrl,
			'url_back' => $this->merchantServerUrl . "/" . $this->errorUrl,
			'mac' => $mac,   
		);

		$requestParams = array_merge($obbligatori, $facoltativi);

		$aRequestParams = array();
		foreach ($requestParams as $param => $value) {
			$aRequestParams[] = $param . "=" . $value;
		}

		$stringRequestParams = implode("&", $aRequestParams);

		return $this->requestUrl . "?" . $stringRequestParams;
	}
	
	public function scriviLog($success, $scriviSuFileLog = true)
	{
		$hostname = gethostbyaddr ( $_SERVER ['REMOTE_ADDR'] );
		
		$text = '[' . date ( 'm/d/Y g:i A' ) . '] - ';
		// Success or failure being logged?
		if ($success)
			$this->statoNotifica = $text . 'SUCCESS:' . $this->statoNotifica . "!\n";
		else
			$this->statoNotifica = $text . 'FAIL: ' . $this->statoNotifica . "!\n";
		
		$this->statoNotifica .= "[From:" . $hostname . "|" . $_SERVER ['REMOTE_ADDR'] . "]REQUEST Vars Received:\n";
		
		foreach ( $_REQUEST as $key => $value ) {
			$this->statoNotifica .= "REQUEST:$key=$value \n";
		}
		
		if ($this->statoCheckOrdine)
			$this->statoNotifica .= "CHECK ORDINE:".$this->statoCheckOrdine."\n";
		
		if ($scriviSuFileLog)
		{
			// Write to log
			$fp = fopen ( $this->logFile , 'a+' );
			fwrite ( $fp, $this->statoNotifica . "\n\n" );
			fclose ( $fp ); // close file
			chmod ( $this->logFile , 0600 );
			$this->statoNotifica = "";
		}
		else
			return $this->statoNotifica;
		
	}
	
	public function validate($scriviSuFileLog = true)
	{
		// Controllo che ci siano tutti i parametri di ritorno obbligatori per calcolare il MAC
		$requiredParams = array('codTrans', 'esito', 'importo', 'divisa', 'data', 'orario', 'codAut', 'mac');
		foreach ($requiredParams as $param) {
			if (!isset($_REQUEST[$param])) {
				$this->statoNotifica = 'Parametro mancante ' . $param;
				$this->scriviLog(false, $scriviSuFileLog);
				return false;
			}
		}
		
		// Calcolo MAC con i parametri di ritorno
		$macCalculated = sha1('codTrans=' . $_REQUEST['codTrans'] .
			'esito=' . $_REQUEST['esito'] .
			'importo=' . $_REQUEST['importo'] .
			'divisa=' . $_REQUEST['divisa'] .
			'data=' . $_REQUEST['data'] .
			'orario=' . $_REQUEST['orario'] .
			'codAut=' . $_REQUEST['codAut'] .
			$this->CHIAVESEGRETA
		);

		// Verifico corrispondenza tra MAC calcolato e MAC di ritorno
		if ($macCalculated != $_REQUEST['mac']) {
			$this->statoNotifica = 'Errore MAC: ' . $macCalculated . ' non corrisponde a ' . $_REQUEST['mac'];
			$this->scriviLog(false, $scriviSuFileLog);
			return false;
		}

		// Nel caso in cui non ci siano errori gestisco il parametro esito
		if ($_REQUEST['esito'] == 'OK') {
			$this->statoNotifica = 'OK, pagamento avvenuto, preso riscontro';
			$this->scriviLog(true, $scriviSuFileLog);
			return true;
		} else {
			$this->statoNotifica = 'KO, pagamento non avvenuto, preso riscontro';
			$this->scriviLog(false, $scriviSuFileLog);
		}
		
		return false;
	}
	
	public function redirect()
	{
		return true;
	}
	
	public function success()
	{
		if (isset($_REQUEST['esito']) && strcmp($_REQUEST['esito'],"OK") === 0)
			return true;
		
		return false;
	}
	
	public function checkOrdine()
	{
		$importo = str_replace(".","",$this->ordine["total"]);
		$amount = isset($_REQUEST["importo"]) ? $_REQUEST["importo"] : 0;
		$codTrans = isset($_REQUEST["codTrans"]) ? $_REQUEST["codTrans"] : 0;
		
		if (strcmp($amount,$importo) === 0 && strcmp($codTrans,$this->ordine["id_o"]) === 0)
			return true;
		
		$this->statoCheckOrdine = "ORDINE NON TORNA:\n";
		$this->statoCheckOrdine .= "DOVUTO: $importo - PAGATO: $importo \n";
		$this->statoCheckOrdine .= "COD TRANS: $codTrans - COD TRANS ORDINE: ".$this->ordine["id_o"]." \n";
		
		$this->statoNotifica = 'OK, pagamento non corretto';
		$this->scriviLog(false, $scriviSuFileLog);
		
		return false;
	}
	
}