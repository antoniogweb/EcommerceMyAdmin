<?php

class Nexi
{
	public $ALIAS = "";
	public $CHIAVESEGRETA = "";
	public $requestUrl = "";
	public $merchantServerUrl = "";
	public $okUrl = "";
	public $errorUrl = "";
	public $statoNotifica = "";
	private $logFile = "";
	
	public function __construct($okUrl = "", $errorUrl = "")
	{
// 		PagamentiModel::g(false)->where(array(
// 			"codice"	=>	"carta_di_credito"
// 		))->record();
		
		$this->CHIAVESEGRETA = ImpostazioniModel::$valori["nexi_chiave_segreta"];
		$this->ALIAS = ImpostazioniModel::$valori["nexi_alias"];
// 		echo $this->CHIAVESEGRETA;
		if (ImpostazioniModel::$valori["usa_nexi_test"] == "Y")
			$this->requestUrl = "https://int-ecommerce.nexi.it/ecomm/ecomm/DispatcherServlet";
		else
			$this->requestUrl = "https://ecommerce.nexi.it/ecomm/ecomm/DispatcherServlet";
		
		$this->merchantServerUrl = Domain::$name;
		
		$this->okUrl = $okUrl;
		$this->errorUrl = $errorUrl;
		
		$this->logFile = ROOT."/Logs/.ipncarta_results.log";
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
				$this->statoNotifica = 'Paramentro mancante ' . $param;
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
			$this->scriviLog(true, $scriviSuFileLog);
		}
		
		return false;
	}
	
}
