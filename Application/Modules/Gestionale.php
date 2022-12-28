<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
//
// Copyright (C) 2009 - 2022  Antonio Gallo (info@laboratoriolibero.com)
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
	
	// elemento => metodoDaChiamare
	public static $tabellaElementi = array(
		"ordine"	=>	"inviaOrdine",
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
		return "<span class='text text-danger text-bold'>".sprintf(gtext("Errore nell'invio dell'ordine %s inviato a"), $ordine["id_o"])." verso ".$this->titolo()."</span>";
	}
	
	public function descInviaAlGestionale($ordine)
	{
		$html = "";
		
		if (method_exists($this, "inviaOrdine"))
			$html .= "<a class='badge' href='".Url::getRoot()."gestionali/invia/ordine/".$ordine["id_o"]."'>".gtext("Invia l'ordine a")." ".$this->titolo()."</a>";
		
		return $html;
	}
	
	public function specchiettoOrdine($ordine)
	{
		$html = "";
		
		if (OrdiniModel::statoGestionale($ordine) > 0)
			$html .= $this->descOrdineInviato($ordine);
		else if (OrdiniModel::statoGestionale($ordine) < 0)
			$html .= $this->descOrdineErrore($ordine);
		else
			$html .= $this->descInviaAlGestionale($ordine);
		
		return $html;
	}
	
	public function infoOrdine($id_o)
	{
		$oModel = new OrdiniModel();
		$rModel = new RigheModel();
		
		$ordine = $oModel->clear()->select("id_o,data_creazione,nome,cognome,ragione_sociale,p_iva,codice_fiscale,indirizzo,cap,provincia,dprovincia,nazione,citta,telefono,email,pagamento,accetto,total,total_pieno,tipo_cliente,stato,subtotal,subtotal_ivato,spedizione,spedizione_ivato,costo_pagamento,costo_pagamento_ivato,iva,registrato,id_user,prezzo_scontato,prezzo_scontato_ivato,codice_promozione,nome_promozione,usata_promozione,id_p,peso,id_iva,id_iva_estera,stringa_iva_estera,aliquota_iva_estera,iva_spedizione,indirizzo_spedizione,cap_spedizione,provincia_spedizione,dprovincia_spedizione,nazione_spedizione,citta_spedizione,telefono_spedizione,id_spedizione,id_corriere,pec,codice_destinatario,destinatario_spedizione,pagato,data_pagamento,note,da_spedire,tipo_ordine,nazione_navigazione,lingua,codice_gestionale,versione_api_gestionale,errore_gestionale,fonte")->whereId((int)$id_o)->record();
		
		$ordine = htmlentitydecodeDeep($ordine);
		
		if (!empty($ordine))
		{
			$righe = $rModel->clear()->select("id_r,data_creazione,title as titolo,attributi,codice,immagine,peso,quantity,price as prezzo,price_ivato as prezzo_ivato,prezzo_intero,prezzo_intero_ivato,prezzo_finale,prezzo_finale_ivato,gift_card,id_iva,iva,fonte")->where(array(
				"id_o"	=>	(int)$id_o,
			))->send(false);
			
			$righe = array_map('htmlentitydecodeDeep', $righe);
			
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
					"id_iva"	=>	$ordine["id_iva"],
					"iva"		=>	($ordine["spedizione_ivato"] - $ordine["spedizione"]),
					"fonte"		=>	$ordine["tipo_ordine"],
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
					"id_iva"	=>	$ordine["id_iva"],
					"iva"		=>	($ordine["costo_pagamento_ivato"] - $ordine["costo_pagamento"]),
					"fonte"		=>	$ordine["tipo_ordine"],
				);
			}
			
			$ordine["righe"] = $righe;
			
// 			print_r($ordine);
			
			return $ordine;
		}
	}
}
