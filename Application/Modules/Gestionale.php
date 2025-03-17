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

class Gestionale
{
	use Modulo;
	
// 	protected $params = "";
	
	public static $variabili = null;
	
	// elemento => metodoDaChiamare
	public static $tabellaElementi = array(
		"ordine"	=>	array(
			"metodo"			=>	"inviaOrdine",
			"metodo_annulla"	=>	"annullaOrdine",
			"bak_route"	=>	"ordine",
		),
	);
	
// 	public function __construct($record)
// 	{
// 		$this->params = htmlentitydecodeDeep($record);
// 	}
// 	
// 	public function getParams()
// 	{
// 		return $this->params;
// 	}
	
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
	
	public function titoloGestionale()
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
	// $estraiAcconto, se true mostra anche le righe con acconto
	public function infoOrdine($id_o, $estraiAcconto = false, $campiAggiuntiviRighe = "")
	{
		$oModel = new OrdiniModel();
		$orModel = new OrdiniivaripartitaModel();
		$rModel = new RigheModel();
		
		$ordine = $oModel->clear()->select("id_o,data_creazione,nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,dprovincia,nazione,citta,telefono,email,pagamento,accetto,total,total_pieno,tipo_cliente,stato,subtotal,subtotal_ivato,spedizione,spedizione_ivato,costo_pagamento,costo_pagamento_ivato,iva,registrato,id_user,prezzo_scontato,prezzo_scontato_ivato,codice_promozione,nome_promozione,usata_promozione,id_p,peso,id_iva,id_iva_estera,stringa_iva_estera,aliquota_iva_estera,iva_spedizione,indirizzo_spedizione,cap_spedizione,provincia_spedizione,dprovincia_spedizione,nazione_spedizione,citta_spedizione,telefono_spedizione,id_spedizione,id_corriere,pec,codice_destinatario,destinatario_spedizione,pagato,data_pagamento,note,da_spedire,tipo_ordine,nazione_navigazione,lingua,codice_gestionale,inviato_al_gestionale,codice_gestionale_cliente,codice_gestionale_spedizione,versione_api_gestionale,errore_gestionale,fonte,euro_promozione,tipo_promozione,euro_crediti,sorgente,id_lista_regalo,prezzi_ivati_in_carrello,prezzo_scontato_prodotti,prezzo_scontato_prodotti_ivato,data_documento")->whereId((int)$id_o)->record();
		
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
			
			$ordine["totale_prodotti_non_ivato"] = $ordine["prezzo_scontato_prodotti"];
			$ordine["totale_prodotti_ivato"] = $ordine["prezzo_scontato_prodotti_ivato"];
			
			$idIva = $ordine["id_iva_estera"] ? $ordine["id_iva_estera"] : $ordine["id_iva"];
			
			$ordine["valore_iva"] = IvaModel::g(false)->getValore($idIva);
			
			$campiRighe = "id_r,righe.data_creazione,title as titolo,attributi,codice,immagine,peso,quantity,price as prezzo,price_ivato as prezzo_ivato,prezzo_intero,prezzo_intero_ivato,prezzo_finale,prezzo_finale_ivato,gift_card,id_iva,iva,fonte,gtin,mpn,id_page,righe.acconto,righe.acconto,righe.id_riga_tipologia,prodotto_generico,sconto";
			
			if ($campiAggiuntiviRighe)
				$campiRighe .= ",$campiAggiuntiviRighe";
			
			$rModel->clear()->select($campiRighe)
				->left("righe_tipologie")->on("righe_tipologie.id_riga_tipologia = righe.id_riga_tipologia")
				->where(array(
					"id_o"	=>	(int)$id_o,
				));
			
			if (!$estraiAcconto)
				$rModel->aWhere(array(
					"ne"		=>	array(
						"righe.acconto"	=>	1,
					),
				));
			
			$righe = $rModel->orderBy("righe_tipologie.id_order,righe.id_order")->send(false);
			
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
			
			$ripartizioni = $orModel->clear()->where(array(
				"id_o"	=>	(int)$id_o,
			))->orderBy("aliquota_iva")->send(false);
			
			if (count($ripartizioni) === 0)
				$ripartizioni = array(
					array(
						"id_o"		=>	(int)$id_o,
						"id_iva"	=>	$idIva,
						"aliquota_iva"	=>	$ordine["valore_iva"],
						"ripartizione"	=>	1,
						"ripartizione_su_ivato"	=>	v("attiva_prezzi_ivati_in_carrello_per_utente_e_ordine") ? $ordine["prezzi_ivati_in_carrello"] : v("prezzi_ivati_in_carrello"),
					)
				);
			
			if ($ordine["spedizione"] > 0)
			{
				foreach ($ripartizioni as $ripartizione)
				{
					$valoriRipartizione = array(
						"ivato"		=>	$ordine["spedizione_ivato"] * $ripartizione["ripartizione"],
						"non_ivato"	=>	($ordine["spedizione_ivato"] * $ripartizione["ripartizione"]) / (1 + ($ripartizione["aliquota_iva"] / 100)),
						"id_iva"	=>	$ripartizione["id_iva"],
						"aliquota"	=>	$ripartizione["aliquota_iva"],
						"titolo"	=>	count($ripartizioni) > 1 ? gtext("Spedizione Iva") . " " . setPriceReverse($ripartizione["aliquota_iva"])."%" : gtext("Spedizione"),
						"codice"	=>	count($ripartizioni) > 1 ? "SPEDIZIONE ".setPriceReverse($ripartizione["aliquota_iva"]) : "SPEDIZIONE",
					);
					
					$righe[] = $this->addRipartizione($valoriRipartizione, $ordine);
				}
			}
			
			if ($ordine["costo_pagamento"] > 0)
			{
				foreach ($ripartizioni as $ripartizione)
				{
					$valoriRipartizione = array(
						"ivato"		=>	$ordine["costo_pagamento_ivato"] * $ripartizione["ripartizione"],
						"non_ivato"	=>	($ordine["costo_pagamento_ivato"] * $ripartizione["ripartizione"]) / (1 + ($ripartizione["aliquota_iva"] / 100)),
						"id_iva"	=>	$ripartizione["id_iva"],
						"aliquota"	=>	$ripartizione["aliquota_iva"],
						"titolo"	=>	count($ripartizioni) > 1 ? gtext("Pagamento Iva") . " " . setPriceReverse($ripartizione["aliquota_iva"])."%" : gtext("Pagamento"),
						"codice"	=>	count($ripartizioni) > 1 ?  "PAGAMENTO ".setPriceReverse($ripartizione["aliquota_iva"]) : "PAGAMENTO",
					);
					
					$righe[] = $this->addRipartizione($valoriRipartizione, $ordine);
				}
			}
			
			if ($ordine["euro_crediti"] > 0)
			{
				// $costoNonIvato = number_format($ordine["euro_crediti"] / (1 + ($ordine["valore_iva"] / 100)),v("cifre_decimali"),".","");
				foreach ($ripartizioni as $ripartizione)
				{
					$valoriRipartizione = array(
						"ivato"		=>	(-1) * $ordine["euro_crediti"] * $ripartizione["ripartizione"],
						"non_ivato"	=>	(-1) * ($ordine["euro_crediti"] * $ripartizione["ripartizione"]) / (1 + ($ripartizione["aliquota_iva"] / 100)),
						"id_iva"	=>	$ripartizione["id_iva"],
						"aliquota"	=>	$ripartizione["aliquota_iva"],
						"titolo"	=>	count($ripartizioni) > 1 ? gtext("Sconto crediti") . " " . setPriceReverse($ripartizione["aliquota_iva"])."%" : gtext("Sconto crediti"),
						"codice"	=>	count($ripartizioni) > 1 ?  "CREDITI ".setPriceReverse($ripartizione["aliquota_iva"]) : "CREDITI",
					);
					
					$righe[] = $this->addRipartizione($valoriRipartizione, $ordine);
				}
				
				// $righe[] = array(
				// 	"id_r"	=>	-1,
				// 	"titolo"	=>	gtext("Sconto crediti"),
				// 	"attributi"	=>	"",
				// 	"codice"	=>	"CREDITI",
				// 	"immagine"	=>	"",
				// 	"peso"		=>	0,
				// 	"quantity"	=>	1,
				// 	"prezzo"	=>	(-1)*$costoNonIvato,
				// 	"prezzo_ivato"	=>	(-1)*$ordine["euro_crediti"],
				// 	"prezzo_intero"	=>	(-1)*$costoNonIvato,
				// 	"prezzo_intero_ivato"	=>	(-1)*$ordine["euro_crediti"],
				// 	"prezzo_finale"	=>	(-1)*$costoNonIvato,
				// 	"prezzo_finale_ivato"	=>	(-1)*$ordine["euro_crediti"],
				// 	"gift_card"	=>	0,
				// 	"id_iva"	=>	$idIva,
				// 	"iva"		=>	$ordine["valore_iva"],
				// 	"fonte"		=>	$ordine["tipo_ordine"],
				// 	"codice_iva"=>	$this->codiceGestionale(new IvaModel, $idIva),
				// 	"acconto"	=>	0,
				// 	"id_riga_tipologia"	=>	0,
				// 	"prodotto_generico"	=>	0,
				// 	"sconto"	=>	0,
				// );
			}
			
			if ($ordine["euro_promozione"] > 0 && $ordine["tipo_promozione"] == "ASSOLUTO")
			{
				// $costoNonIvato = number_format($ordine["euro_promozione"] / (1 + ($ordine["valore_iva"] / 100)),v("cifre_decimali"),".","");
				foreach ($ripartizioni as $ripartizione)
				{
					$valoriRipartizione = array(
						"ivato"		=>	(-1) * $ordine["euro_promozione"] * $ripartizione["ripartizione"],
						"non_ivato"	=>	(-1) * ($ordine["euro_promozione"] * $ripartizione["ripartizione"]) / (1 + ($ripartizione["aliquota_iva"] / 100)),
						"id_iva"	=>	$ripartizione["id_iva"],
						"aliquota"	=>	$ripartizione["aliquota_iva"],
						"titolo"	=>	count($ripartizioni) > 1 ? gtext("Coupon") . " " . setPriceReverse($ripartizione["aliquota_iva"])."%" : gtext("Coupon"),
						"codice"	=>	count($ripartizioni) > 1 ?  $ordine["codice_promozione"]." ".setPriceReverse($ripartizione["aliquota_iva"]) : $ordine["codice_promozione"],
					);
					
					$righe[] = $this->addRipartizione($valoriRipartizione, $ordine);
				}
				
				// $righe[] = array(
				// 	"id_r"	=>	-1,
				// 	"titolo"	=>	gtext("Coupon"),
				// 	"attributi"	=>	"",
				// 	"codice"	=>	$ordine["codice_promozione"],
				// 	"immagine"	=>	"",
				// 	"peso"		=>	0,
				// 	"quantity"	=>	1,
				// 	"prezzo"	=>	(-1)*$costoNonIvato,
				// 	"prezzo_ivato"	=>	(-1)*$ordine["euro_promozione"],
				// 	"prezzo_intero"	=>	(-1)*$costoNonIvato,
				// 	"prezzo_intero_ivato"	=>	(-1)*$ordine["euro_promozione"],
				// 	"prezzo_finale"	=>	(-1)*$costoNonIvato,
				// 	"prezzo_finale_ivato"	=>	(-1)*$ordine["euro_promozione"],
				// 	"gift_card"	=>	0,
				// 	"id_iva"	=>	$idIva,
				// 	"iva"		=>	$ordine["valore_iva"],
				// 	"fonte"		=>	$ordine["tipo_ordine"],
				// 	"codice_iva"=>	$this->codiceGestionale(new IvaModel, $idIva),
				// 	"acconto"	=>	0,
				// 	"id_riga_tipologia"	=>	0,
				// 	"prodotto_generico"	=>	0,
				// 	"sconto"	=>	0,
				// );
			}
			
			$ordine["righe"] = $righe;
			
			$ordine["pagamenti"] = array(array(
				"data_pagamento"	=>	$ordine["data_pagamento"] ? date("Y-m-d", strtotime($ordine["data_pagamento"])) : "",
				"importo"	=>	$ordine["total"],
			));
			
// 			print_r($ordine);die();
			
			return $ordine;
		}
	}
	
	protected function addRipartizione($valori, $ordine)
	{
		return array(
			"id_r"	=>	-1,
			"titolo"	=>	$valori["titolo"],
			"attributi"	=>	"",
			"codice"	=>	$valori["codice"],
			"immagine"	=>	"",
			"peso"		=>	0,
			"quantity"	=>	1,
			"prezzo"	=>	$valori["non_ivato"],
			"prezzo_ivato"	=>	$valori["ivato"],
			"prezzo_intero"	=>	$valori["non_ivato"],
			"prezzo_intero_ivato"	=>	$valori["ivato"],
			"prezzo_finale"	=>	$valori["non_ivato"],
			"prezzo_finale_ivato"	=>	$valori["ivato"],
			"gift_card"	=>	0,
			"id_iva"	=>	$valori["id_iva"],
			"iva"		=>	$valori["aliquota"],
			"fonte"		=>	$ordine["tipo_ordine"],
			"codice_iva"=>	$this->codiceGestionale(new IvaModel, (int)$valori["id_iva"]),
			"acconto"	=>	0,
			"id_riga_tipologia"	=>	0,
			"prodotto_generico"	=>	0,
			"sconto"	=>	0,
			"riga_accessoria"	=>	0,
		);
	}
	
	public function annullaOrdine($idO)
	{
		$oModel = new OrdiniModel();
		
		$oModel->sValues(array(
			"codice_gestionale"		=>	"",
			"errore_gestionale"		=>	"",
			"inviato_al_gestionale"	=>	0,
		));
		
		$oModel->pUpdate((int)$idO);
	}
}
