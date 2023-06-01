<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2023  Antonio Gallo (info@laboratoriolibero.com)
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

class Gestionale
{
	protected $params = "";
	
	public static $variabili = null;
	
	// elemento => metodoDaChiamare
	public static $tabellaElementi = array(
		"ordine"	=>	array(
			"metodo"			=>	"inviaOrdine",
			"metodo_annulla"	=>	"annullaOrdine",
			"bak_route"	=>	"ordine",
		),
	);
	
	public function __construct($record)
	{
		$this->params = htmlentitydecodeDeep($record);
	}
	
	public function getParams()
	{
		return $this->params;
	}
	
	public function gParam1Label()
	{
		return "Param 1";
	}
	
	public function gParam2Label()
	{
		return "Param 2";
	}
	
	public function gCampiForm()
	{
		return 'titolo,attivo';
	}
	
	public function titolo()
	{
		return $this->params["titolo"];
	}
	
	public function descOrdineInviato($ordine)
	{
		return "<span class='text text-success text-bold'>".sprintf(gtext("Ordine %s inviato a"), $ordine["id_o"])." ".$this->titolo()."</span>";
	}
	
	public function descOrdineErrore($ordine)
	{
		return "<span class='text text-danger text-bold'>".sprintf(gtext("Errore nell'invio dell'ordine %s inviato a"), $ordine["id_o"])." ".$this->titolo()."</span><pre>".$ordine["errore_gestionale"]."</pre>";
	}
	
	public function descAnnullaInvioAlGestionale($ordine, $testo = "Annulla l'invio a")
	{
		$html = "";
		
		if (method_exists($this, "annullaOrdine"))
			$html .= "<a style='margin-left:10px;' class='label label-danger' href='".Url::getRoot()."gestionali/annullainvio/ordine/".$ordine["id_o"]."'><i class='fa fa-trash'></i> ".gtext($testo)." ".$this->titolo()."</a>";
		
		return $html;
	}
	
	public function descInviaAlGestionale($ordine, $testo = "Invia l'ordine a")
	{
		$html = "";
		
		if (method_exists($this, "inviaOrdine"))
			$html .= "<a class='label label-primary' href='".Url::getRoot()."gestionali/invia/ordine/".$ordine["id_o"]."'>".gtext($testo)." ".$this->titolo()."</a>";
		
		return $html;
	}
	
	public function specchiettoOrdine($ordine)
	{
		$html = "";
		
		if (OrdiniModel::statoGestionale($ordine) > 0)
			$html .= $this->descOrdineInviato($ordine).$this->descAnnullaInvioAlGestionale($ordine);
		else if (OrdiniModel::statoGestionale($ordine) < 0)
			$html .= $this->descOrdineErrore($ordine).$this->descInviaAlGestionale($ordine).$this->descAnnullaInvioAlGestionale($ordine);
		else
			$html .= $this->descInviaAlGestionale($ordine);
		
		return $html;
	}
	
	public function codiceGestionale(GenericModel $model, $id = 0)
	{
		if (!$id)
			return 0;
		
		return $model->clear()->select($model->getPrimaryKey())->where(array(
			$model->getPrimaryKey()	=>	(int)$id
		))->field("codice_gestionale");
	}
	
	protected function impostaErrori($id_o, $arrayErrori)
	{
		$oModel = new OrdiniModel();
		
		$oModel->sValues(array(
			"errore_gestionale"	=>	json_encode($arrayErrori),
		));
		
		$oModel->pUpdate((int)$id_o);
	}
	
	public function getVariabile($titolo)
	{
		if (!isset(self::$variabili))
			self::$variabili = GestionalivariabiliModel::g()->clear()->where(array(
				"codice_gestionale"	=>	sanitizeAll($this->params["codice"])
			))->toList("titolo", "valore")->send();
		
		if (array_key_exists($titolo, self::$variabili))
			return self::$variabili[$titolo];
		
		return "";
	}
	
	public function checkCampi($ordine, $campi)
	{
		$arrayErrori = [];
		
		if (is_array($campi))
		{
			foreach ($campi as $campo)
			{
				if (!$ordine[$campo])
					$arrayErrori[] = array(sprintf(gtext("Campo %s non presente"),$campo));
			}
		}
		
		return $arrayErrori;
	}
	
	// restituisce un output pulito dell'ordine con la testata e le righe e i codici del gestionale già estratti
	public function infoOrdine($id_o)
	{
		$oModel = new OrdiniModel();
		$rModel = new RigheModel();
		
		$ordine = $oModel->clear()->select("id_o,data_creazione,nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,dprovincia,nazione,citta,telefono,email,pagamento,accetto,total,total_pieno,tipo_cliente,stato,subtotal,subtotal_ivato,spedizione,spedizione_ivato,costo_pagamento,costo_pagamento_ivato,iva,registrato,id_user,prezzo_scontato,prezzo_scontato_ivato,codice_promozione,nome_promozione,usata_promozione,id_p,peso,id_iva,id_iva_estera,stringa_iva_estera,aliquota_iva_estera,iva_spedizione,indirizzo_spedizione,cap_spedizione,provincia_spedizione,dprovincia_spedizione,nazione_spedizione,citta_spedizione,telefono_spedizione,id_spedizione,id_corriere,pec,codice_destinatario,destinatario_spedizione,pagato,data_pagamento,note,da_spedire,tipo_ordine,nazione_navigazione,lingua,codice_gestionale,versione_api_gestionale,errore_gestionale,fonte,euro_promozione,tipo_promozione")->whereId((int)$id_o)->record();
		
		if (!empty($ordine))
		{
			$ordine = htmlentitydecodeDeep($ordine);
			
			$ordine["codice_iva"] = $this->codiceGestionale(new IvaModel, $ordine["id_iva"]);
			$pagamento = PagamentiModel::g(false)->where(array(
				"codice" => sanitizeAll($ordine["pagamento"])
			))->record();
			
			if (!empty($pagamento))
			{
				$ordine["id_pagamento"] = $pagamento["id_pagamento"];
				$ordine["codice_pagamento"] = $pagamento["codice_gestionale"];
				$ordine["codice_pagamento_pa"] = $pagamento["codice_pagamento_pa"];
			}
			
			$ordine["pagato_finale"] = OrdiniModel::isPagato((int)$id_o) ? 1 : 0;
			$ordine["nominativo"] = OrdiniModel::getNominativo($ordine);
			
			$ordine["totale_prodotti_non_ivato"] = $ordine["total"] - $ordine["iva"] - $ordine["spedizione"] - $ordine["costo_pagamento"];
			$ordine["totale_prodotti_ivato"] = $ordine["total"] - $ordine["spedizione_ivato"] - $ordine["costo_pagamento_ivato"];
			
			$idIva = $ordine["id_iva_estera"] ? $ordine["id_iva_estera"] : $ordine["id_iva"];
			
			$ordine["valore_iva"] = IvaModel::g(false)->getValore($idIva);
			
			$righe = $rModel->clear()->select("id_r,data_creazione,title as titolo,attributi,codice,immagine,peso,quantity,price as prezzo,price_ivato as prezzo_ivato,prezzo_intero,prezzo_intero_ivato,prezzo_finale,prezzo_finale_ivato,gift_card,id_iva,iva,fonte,gtin,mpn,id_page")->where(array(
				"id_o"	=>	(int)$id_o,
			))->send(false);
			
			$arrayRighe = [];
			
			foreach ($righe as $r)
			{
				$temp = $r;
				$temp["codice_iva"] = $this->codiceGestionale(new IvaModel, $r["id_iva"]);
				
				$arrayRighe[] = $temp;
			}
			
			$righe = $arrayRighe;
			
			$righe = array_map('htmlentitydecodeDeep', $righe);
			
			$numeroProdotti = 0;
			
			foreach ($righe as $r)
			{
				$numeroProdotti += $r["quantity"];
			}
			
			$ordine["numero_prodotti"] = $numeroProdotti;
			
			if ($ordine["spedizione"] > 0)
			{
				$righe[] = array(
					"id_r"	=>	-1,
					"titolo"	=>	gtext("Spedizione"),
					"attributi"	=>	"",
					"codice"	=>	"SPEDIZIONE",
					"immagine"	=>	"",
					"peso"		=>	0,
					"quantity"	=>	1,
					"prezzo"	=>	$ordine["spedizione"],
					"prezzo_ivato"	=>	$ordine["spedizione_ivato"],
					"prezzo_intero"	=>	$ordine["spedizione"],
					"prezzo_intero_ivato"	=>	$ordine["spedizione_ivato"],
					"prezzo_finale"	=>	$ordine["spedizione"],
					"prezzo_finale_ivato"	=>	$ordine["spedizione_ivato"],
					"gift_card"	=>	0,
					"id_iva"	=>	$idIva,
					"iva"		=>	$ordine["valore_iva"],
					"fonte"		=>	$ordine["tipo_ordine"],
					"codice_iva"=>	$this->codiceGestionale(new IvaModel, $idIva),
				);
			}
			
			if ($ordine["costo_pagamento"] > 0)
			{
				$righe[] = array(
					"id_r"	=>	-1,
					"titolo"	=>	gtext("Spedizione"),
					"attributi"	=>	"",
					"codice"	=>	"SPEDIZIONE",
					"immagine"	=>	"",
					"peso"		=>	0,
					"quantity"	=>	1,
					"prezzo"	=>	$ordine["costo_pagamento"],
					"prezzo_ivato"	=>	$ordine["costo_pagamento_ivato"],
					"prezzo_intero"	=>	$ordine["costo_pagamento"],
					"prezzo_intero_ivato"	=>	$ordine["costo_pagamento_ivato"],
					"prezzo_finale"	=>	$ordine["costo_pagamento"],
					"prezzo_finale_ivato"	=>	$ordine["costo_pagamento_ivato"],
					"gift_card"	=>	0,
					"id_iva"	=>	$idIva,
					"iva"		=>	$ordine["valore_iva"],
					"fonte"		=>	$ordine["tipo_ordine"],
					"codice_iva"=>	$this->codiceGestionale(new IvaModel, $idIva),
				);
			}
			
			if ($ordine["euro_promozione"] > 0 && $ordine["tipo_promozione"] == "ASSOLUTO")
			{
				$costoNonIvato = number_format($ordine["euro_promozione"] / (1 + ($ordine["valore_iva"] / 100)),v("cifre_decimali"),".","");
				
				$righe[] = array(
					"id_r"	=>	-1,
					"titolo"	=>	gtext("Coupon"),
					"attributi"	=>	"",
					"codice"	=>	$ordine["codice_promozione"],
					"immagine"	=>	"",
					"peso"		=>	0,
					"quantity"	=>	1,
					"prezzo"	=>	(-1)*$costoNonIvato,
					"prezzo_ivato"	=>	(-1)*$ordine["euro_promozione"],
					"prezzo_intero"	=>	(-1)*$costoNonIvato,
					"prezzo_intero_ivato"	=>	(-1)*$ordine["euro_promozione"],
					"prezzo_finale"	=>	(-1)*$costoNonIvato,
					"prezzo_finale_ivato"	=>	(-1)*$ordine["euro_promozione"],
					"gift_card"	=>	0,
					"id_iva"	=>	$idIva,
					"iva"		=>	$ordine["valore_iva"],
					"fonte"		=>	$ordine["tipo_ordine"],
					"codice_iva"=>	$this->codiceGestionale(new IvaModel, $idIva),
				);
			}
			
			$ordine["righe"] = $righe;
			
			$ordine["pagamenti"] = array(array(
				"data_pagamento"	=>	$ordine["data_pagamento"] ? date("Y-m-d", strtotime($ordine["data_pagamento"])) : "",
				"importo"	=>	$ordine["total"],
			));
			
// 			print_r($ordine);
			
			return $ordine;
		}
	}
	
	public function annullaOrdine($idO)
	{
		$oModel = new OrdiniModel();
		
		$oModel->sValues(array(
			"codice_gestionale"	=>	"",
			"errore_gestionale"	=>	"",
		));
		
		$oModel->pUpdate((int)$idO);
	}
}
