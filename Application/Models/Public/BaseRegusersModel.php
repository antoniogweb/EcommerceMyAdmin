<?php

if (!defined('EG')) die('Direct access not allowed!');

class BaseRegusersModel extends Model_Tree
{
	use CommonModel;
	
	public function __construct()
	{
		$this->_tables='regusers';
		$this->_idFields='id_user';
		
// 		$this->orderBy = 'regusers.id_user desc';
		
		parent::__construct();
		
		$this->_resultString->string["executed"] = "<div class='".v("alert_success_class")."'>".gtext("operazione eseguita!")."</div>\n";
	}
	
	public function insert()
	{
		$this->values['forgot_token'] = $this->getUniqueToken(md5(randString(20).microtime().uniqid(mt_rand(),true)));
		
		if (v("conferma_registrazione"))
			$this->values["has_confirmed"] = 1;
		
		$this->values["lingua"] = Params::$lang;
		
		$this->values["confirmation_time"] = time();
		
		if (!User::$nazioneNavigazione)
			User::$nazioneNavigazione = v("nazione_default");
		
		$this->values["nazione_navigazione"] = User::$nazioneNavigazione;
		
		if ($this->controllaCF(v("insert_account_cf_obbligatorio")) && $this->controllaPIva(v("insert_account_p_iva_obbligatorio")))
			return parent::insert();
		
		return false;
	}
	
	public function update($id = null, $where = null)
	{
		$clean["id"] = (int)$id;
		
		if ($this->controllaCF() && $this->controllaPIva())
			return parent::update($clean["id"]);
		
		return false;
	}
	
	public function pUpdate($id)
	{
		$clean["id"] = (int)$id;
		
		return parent::update($clean["id"]);
	}

	//get a unique token
	public function getUniqueToken($forgotToken)
	{
		$clean["forgotToken"] = sanitizeAll($forgotToken);
		
		$res = $this->clear()->where(array("forgot_token"=>$clean["forgotToken"]))->send();
		
		if (count($res) > 0)
		{
			$nForgotToken = md5(randString(10).microtime().uniqid(mt_rand(),true));
			return $this->getUniqueToken($nForgotToken);
		}
		
		return $clean["forgotToken"];
	}
	
	public function setPasswordCondition()
	{
		$evidenzia = Output::$html ? "<span class='evidenzia'>class_password</span><span class='evidenzia'>class_confirmation</span>" : "";
		
		$this->addStrongCondition("both",'checkEqual',"password,confirmation|<b>".gtext("Le due password non coincidono")."</b>$evidenzia");
		
		$evidenzia = Output::$html ? "<span class='evidenzia'>class_password</span>" : "";
		
		$this->addStrongCondition("both",'checkMatch|/^[a-zA-Z0-9\_\-\!\,\.]+$/',"password|".gtext("Solo i seguenti caratteri sono permessi per la password").":<ul><li>".gtext("Tutte le lettere, maiuscole o minuscole")." (a, A, b, B, ...)</li><li>".gtext("Tutti i numeri")." (0,1,2,...)</li><li>".gtext("I seguenti caratteri").": <b>_ - ! , .</b></li></ul>$evidenzia");
	}
	
	public function getIndirizzoSpedizionePerAdd($id_user)
	{
		$sp = new SpedizioniModel();
		
		$indirizzo = $sp->where(array(
			"id_user"	=>	(int)$id_user,
			"da_usare"	=>	"1",
		))->record();
		
		if (!empty($indirizzo))
			return $indirizzo["id_spedizione"];
		
		$idSpedUltimoOrdine = $this->getIdSpedizioneUsatoNellUltimoOrdine($id_user);
		
		if ($idSpedUltimoOrdine> 0)
			return $idSpedUltimoOrdine;
		
		$indirizzi = $sp->where(array(
			"id_user"	=>	(int)$id_user,
		))->orderBy("id_spedizione desc")->limit(1)->send(false);
		
		if (count($indirizzi) > 0)
			return $indirizzi[0]["id_spedizione"];
		
		return 0;
	}
	
	public function getIdSpedizioneUsatoNellUltimoOrdine($id_user)
	{
		$sp = new SpedizioniModel();
		
		$indirizzi = $sp->where(array(
			"id_user"	=>	(int)$id_user,
			"ultimo_usato"	=>	"Y",
		))->record();
		
		if (!empty($indirizzi))
			return $indirizzi["id_spedizione"];
		
		return 0;
	}
	
	public function getTendinaIndirizzi($id_user)
	{
		$sp = new SpedizioniModel();
		
		$indirizzi = $sp->where(array(
			"id_user"	=>	(int)$id_user,
		))->orderBy("indirizzo_spedizione")->send(false);
		
		$arraySelect = array();
		
		foreach ($indirizzi as $i)
		{
			$arraySelect[$i["id_spedizione"]] = $i["indirizzo_spedizione"]." ".$i["cap_spedizione"]." ".$i["citta_spedizione"];
		}
		
		return $arraySelect;
	}
	
	public function setConditions($tipo_cliente, $queryType = "insert", $pec = "", $codiceDestinatario = "")
	{
		$campiObbligatoriAggiuntivi = "";
		
		if (strcmp($tipo_cliente,"privato") !== 0 && (v("insert_account_sdi_pec_obbligatorio") || $queryType == "update") && isset($_POST["nazione"]) && $_POST["nazione"] == "IT")
		{
			if (trim($codiceDestinatario) == "")
				$campiObbligatoriAggiuntivi .= ",codice_destinatario";
			
			if (trim($pec) == "")
				$campiObbligatoriAggiuntivi .= ",pec";
			
			if (trim($pec) != "" || trim($codiceDestinatario) != "")
				$campiObbligatoriAggiuntivi = "";
		}
		
		$campoObbligatoriProvincia = "dprovincia";
		
		if (isset($_POST["nazione"]) && $_POST["nazione"] == "IT")
			$campoObbligatoriProvincia = "provincia";
		
		$campiObbligatoriComuni = "tipo_cliente";
		
		if (v("insert_account_indirizzo_obbligatorio") || $queryType == "update")
			$campiObbligatoriComuni .= ",indirizzo";
		
		if (v("insert_account_citta_obbligatoria") || $queryType == "update")
			$campiObbligatoriComuni .= ",citta";
		
		if (v("insert_account_telefono_obbligatorio") || $queryType == "update")
			$campiObbligatoriComuni .= ",telefono";
		
		if (v("insert_account_nazione_obbligatoria") || $queryType == "update")
			$campiObbligatoriComuni .= ",nazione";
		
		if (v("insert_account_provincia_obbligatoria") || $queryType == "update")
			$campiObbligatoriComuni .= ",$campoObbligatoriProvincia";
		
		if (v("insert_account_cap_obbligatorio") || $queryType == "update")
			$campiObbligatoriComuni .= ",cap";
		
		if (isset($_POST["nazione"]) && $_POST["nazione"] == "IT" && (v("insert_account_cf_obbligatorio") || $queryType == "update"))
			$campiObbligatoriComuni .= ",codice_fiscale";
		
		$campoPIva = "";
		
		if (isset($_POST["nazione"]) && in_array($_POST["nazione"], NazioniModel::elencoNazioniConVat()) && (v("insert_account_p_iva_obbligatorio") || $queryType == "update") && $tipo_cliente != "privato")
			$campoPIva = "p_iva,";
		
		$campiObbligatoriConfermaAccount = "";
		
		if (v("account_attiva_conferma_username"))
			$campiObbligatoriConfermaAccount .= ",conferma_username";
		
		if (v("account_attiva_conferma_password"))
			$campiObbligatoriConfermaAccount .= ",confirmation";
		
		$campiNominativi = "";
		
		if (v("insert_account_nominativo_obbligatorio"))
			$campiNominativi = ($tipo_cliente != "azienda") ? ",nome,cognome" : ",ragione_sociale";
		
		$campiObbligatori = $campiObbligatoriComuni.$campiNominativi.",".$campoPIva."username".$campiObbligatoriAggiuntivi;
		
		if ($queryType == "insert")
			$campiObbligatori .= $campiObbligatoriConfermaAccount.",accetto,password";
		
		$this->addStrongCondition("both",'checkNotEmpty',$campiObbligatori);
		
		$evidenziaEmail = Output::$html ? "<div class='evidenzia'>class_username</div>" : "";
		
		$this->addStrongCondition("both",'checkMail',"username|".gtext("Si prega di ricontrollare <b>l'indirizzo Email</b>").$evidenziaEmail);
		
		$evidenziaPec = Output::$html ? "<div class='evidenzia'>class_pec</div>" : "";
		
		$this->addSoftCondition("both",'checkMail',"pec|".gtext("Si prega di ricontrollare <b>l'indirizzo Pec</b>").$evidenziaPec);
		
		$evidenziaCD = Output::$html ? "<div class='evidenzia'>class_codice_destinatario</div>" : "";
		
		$this->addSoftCondition("both",'checkLength|7',"codice_destinatario|".gtext("Si prega di ricontrollare <b>il Codice Destinatario</b>").$evidenziaCD);
		
		if (strcmp($queryType,"insert") === 0)
		{
			if (v("account_attiva_conferma_username"))
			{
				$evidenziaConfermaUSer = Output::$html ? "<div class='evidenzia'>class_conferma_username</div>" : "";
				
				$this->addStrongCondition("both",'checkMail',"conferma_username|".gtext("Si prega di ricontrollare il campo <b>conferma dell'indirizzo Email</b>").$evidenziaConfermaUSer);
				
				$evidenziaEqual = Output::$html ? "<div class='evidenzia'>class_username</div><div class='evidenzia'>class_conferma_username</div>" : "";
				
				$this->addStrongCondition("both",'checkEqual',"username,conferma_username|<b>".gtext("I due indirizzi email non corrispondono")."</b>$evidenziaEqual");
			}
			
			$evidenziaAccetto = Output::$html ? "<div class='evidenzia'>class_accetto</div>" : "";
			
			$this->addStrongCondition("both",'checkIsStrings|accetto',"accetto|<b>".gtext("Si prega di accettare le condizioni di privacy")."</b>$evidenziaAccetto");
		}
		
		$this->addStrongCondition("both",'checkIsStrings|privato,azienda,libero_professionista',"tipo_cliente|<b>".gtext("Si prega di indicare se siete un privato o un'azienda")."</b>");
		
		$this->addSoftCondition("both",'checkLength|300',"indirizzo_spedizione|<b>L'indirizzo di spedizione non può superare i 300 caratteri</b><div class='evidenzia'>class_indirizzo_spedizione</div>");
		
		$evidenziaE = Output::$html ? "<span class='evidenzia'>class_username</span><div class='evidenzia'>class_email</div><div class='evidenzia'>class_conferma_email</div>" : "";
		
		if (Output::$html)
			$this->databaseConditions['insert'] = array(
				"checkUnique"		=>	"username|".gtext("La sua E-Mail è già presente nel nostro sistema, significa che è già registrato nel nostro sito web.<br />Se non ricorda la password può impostarne una nuova al seguente")." <a href='".Url::getRoot()."password-dimenticata'>".gtext("indirizzo web")."</a>$evidenziaE",
			);
		else
			$this->databaseConditions['insert'] = array(
				"checkUnique"		=>	"username|".gtext("La sua E-Mail è già presente nel nostro database, significa che è già registrato nel nostro sistema.<br /> Se non ricorda la password può impostarne una nuova."),
			);
		
		$evidenziaEU = Output::$html ? "<span class='evidenzia'>class_username</span><div class='evidenzia'>class_email</div><div class='evidenzia'>class_conferma_email</div>" : "";
		
		$this->databaseConditions['update'] = array(
			"checkUniqueCompl"		=>	"username|".gtext("Questa E-Mail è già usata da un altro utente e non può quindi essere scelta").$evidenziaEU,
		);
		
		$naz = new NazioniModel();
		
		$codiciNazioniAttive = implode(",",$naz->selectCodiciAttivi());
		
		if (v("insert_account_nazione_obbligatoria") || $queryType == "update")
			$this->addStrongCondition("both",'checkIsStrings|'.$codiciNazioniAttive,"nazione|".gtext("<b>Si prega di selezionare una nazione tra quelle permesse</b>"));
		
		if (strcmp($queryType,"insert") === 0)
		{
			if (v("account_attiva_conferma_password"))
			{
				$evidenziaP = Output::$html ? "<span class='evidenzia'>class_password</span><span class='evidenzia'>class_confirmation</span>" : "";
				
				$this->addStrongCondition("both",'checkEqual',"password,confirmation|<b>".gtext("Le due password non coincidono")."</b>".$evidenziaP);
			}
			
			$evidenziaPC = Output::$html ? "<span class='evidenzia'>class_password</span>" : "";
			
			$this->addStrongCondition("both",'checkMatch|/^[a-zA-Z0-9\_\-\!\,\.]+$/',"password|".gtext("Solo i seguenti caratteri sono permessi per la password").":<ul><li>".gtext("Tutte le lettere, maiuscole o minuscole")." (a, A, b, B, ...)</li><li>".gtext("Tutti i numeri")." (0,1,2,...)</li><li>".gtext("I seguenti caratteri").": <b>_ - ! , .</b></li></ul>$evidenziaPC");
		}
		
		$evidenziaT = Output::$html ? "<div class='evidenzia'>class_telefono</div>" : "";
		
		$this->addSoftCondition("both","checkMatch|/^[0-9\s]+$/","telefono|".gtext("Si prega di controllare che il campo <b>telefono</b> contenga solo cifre numeriche")."$evidenziaT");
		
		if (isset($_POST["nazione"]) && $_POST["nazione"] == "IT")
		{
			$evidenziaCAP = Output::$html ? "<div class='evidenzia'>class_cap</div>" : "";
			
			$this->addSoftCondition("both","checkMatch|/^[0-9]+$/","cap|".gtext("Si prega di controllare che il campo <b>cap</b> contenga solo cifre numeriche").$evidenziaCAP);
			
			$evidenziaCF = Output::$html ? "<div class='evidenzia'>class_codice_fiscale</div>" : "";
			
			$this->addSoftCondition("both","checkMatch|/^[0-9a-zA-Z]+$/","codice_fiscale|".gtext("Si prega di controllare il campo <b>Codice Fiscale</b>").$evidenziaCF);
			
			$evidenziaPIVA = Output::$html ? "<div class='evidenzia'>class_p_iva</div>" : "";
			
			$this->addSoftCondition("both","checkMatch|/^[0-9a-zA-Z]+$/","p_iva|".gtext("Si prega di controllare il campo <b>Partita Iva").$evidenziaPIVA);
		}
	}
	
	public function deleteAccount($idUser)
	{
		$user = $this->selectId($idUser);
		
		if (!empty($user))
		{
			$this->query("delete from spedizioni where id_user = ".(int)$idUser);
			$this->query("delete from regusers_groups where id_user = ".(int)$idUser);
			$this->query("update orders set id_user = 0 where id_user = ".(int)$idUser);
			$this->query("delete from regusers where id_user = ".(int)$idUser);
		}
	}
	
	// Iscrivi a newsletter l'utente
	public function iscriviANewsletter($id_user)
	{
		$clean["id_user"] = (int)$id_user;
		
		$record = $this->clear()->where(array("id_user"=>$clean["id_user"]))->record();
		
		if (!empty($record))
		{
			// Iscrizione alla newsletter
			if (ImpostazioniModel::$valori["mailchimp_api_key"] && ImpostazioniModel::$valori["mailchimp_list_id"])
			{
				$dataMailChimp = array(
					"email"	=>	$record["username"],
					"status"=>	"subscribed",
				);
				
				if ($record["tipo_cliente"] == "azienda")
				{
					$dataMailChimp["firstname"] = $record["ragione_sociale"];
					$dataMailChimp["lastname"] = $record["ragione_sociale"];
				}
				else
				{
					$dataMailChimp["firstname"] = $record["nome"];
					$dataMailChimp["lastname"] = $record["cognome"];
				}
				
				$code = syncMailchimp($dataMailChimp);
				
// 				echo $code;
			}
		}
	}
}
