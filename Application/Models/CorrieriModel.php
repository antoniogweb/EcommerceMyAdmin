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

class CorrieriModel extends GenericModel {

	public function __construct() {
		$this->_tables='corrieri';
		$this->_idFields='id_corriere';
		
		$this->_idOrder='id_order';
		
		$this->_lang = 'It';
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'prezzi' => array("HAS_MANY", 'CorrierispeseModel', 'id_corriere', null, "CASCADE"),
        );
    }
    
    public function setFormStruct($id = 0)
	{
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'visibile'	=>	array(
					'type'		=>	'Select',
					'options'	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
				),
				'ritiro_in_sede'	=>	array(
					'type'		=>	'Select',
					'options'	=>	self::$attivoSiNo,
					"reverse"	=>	"yes",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Se impostato su sì, gli ordini con questo corriere verranno impostati come ordini senza spedizione")."</div>"
					),
				),
				'stato_ordine'	=>	array(
					'type'		=>	'Select',
					'options'	=>	array("" => "-- non modificare lo stato dell'ordine --") + $this->statiOrdine(),
					"reverse"	=>	"yes",
					'wrap'		=>	array(
						null,
						null,
						"<div class='form_notice'>".gtext("Selezionare lo stato a cui impostare l'ordine dopo il pagamento se viene selezionato questo corriere ")."</div>"
					),
				),
			),
		);
	}
	
	public static function corriereEsistente($idCorriere)
	{
		$cModel = new CorrieriModel();
		
		return (int)$cModel->clear()->whereId((int)$idCorriere)->rowNumber();
	}
	
	// Restituisce 1 o 0 se il corriere è un ritiro in sede
	public static function ritiroInSede($idCorriere)
	{
		if (!v("attiva_campo_ritiro_in_sede_su_corrieri"))
			return false;
		
		$cModel = new CorrieriModel();
		
		return (int)$cModel->clear()->whereId((int)$idCorriere)->field("ritiro_in_sede");
	}
	
	public function statiOrdine()
	{
		OrdiniModel::setStatiOrdine();
		
		return OrdiniModel::$stati;
	}
	
    public function getIdsCorrieriNazione($nazione)
	{
		$clean["nazione"] = sanitizeAll($nazione);
		
		return $this->clear()->select("distinct corrieri.id_corriere")->inner(array("prezzi"))->where(array(
			"OR"	=>	array(
				"corrieri_spese.nazione"	=> $clean["nazione"],
				"-corrieri_spese.nazione"	=> "W",
			),
			"corrieri.attivo"	=>	"Y",
		))->toList("corrieri.id_corriere")->orderBy("corrieri.id_corriere")->send();
	}
	
	// Cerca tra i corrieri legati alle categorie nel carrello e prendi quello che ha le spese di spedizione maggiori
	public function getIdCorriereDaCarrello()
	{
		$clean["cart_uid"] = sanitizeAll(User::$cart_uid);
		
		$nazione = User::getSpedizioneDefault();
		
		if (isset($_POST["nazione_spedizione"]))
			$nazione = $_POST["nazione_spedizione"];
		
		$c = new CartModel();
		$corrSpese = new CorrierispeseModel();
		$peso = $c->getPesoTotale();
		
		$idCorrieri = $c->clear()->select("distinct categories.id_corriere")
			->inner("pages")->on("cart.id_page = pages.id_page")
			->inner("categories")->on("pages.id_c = categories.id_c")
			->where(array(
				"cart_uid"	=>	$clean["cart_uid"],
			))
			->sWhere("(categories.id_corriere is not null and categories.id_corriere != 0)")
			->toList("categories.id_corriere")
			->send();
		
		$idCorriereFinale = $spese = 0;
		
		if (count($idCorrieri) > 0)
		{
			foreach ($idCorrieri as $idCorriere)
			{
				$speseCorriere = $corrSpese->getPrezzo($idCorriere, $peso, $nazione);
				
				if ($speseCorriere >= $spese)
				{
					$spese = $speseCorriere;
					$idCorriereFinale = $idCorriere;
				}
			}
		}
		
		return $idCorriereFinale;
	}
	
	public function elencoCorrieri($soloVisibili = false)
	{
		$this->clear()->select("distinct corrieri.id_corriere,corrieri.*")->inner("corrieri_spese")->using("id_corriere")->where(array(
			"corrieri.attivo"	=>	"Y",
		))->orderBy("corrieri.id_order");
		
		if ($soloVisibili)
			$this->aWhere(array(
				"corrieri.visibile"	=>	1,
			));
		
		return $this->send(false);
	}
	
	public function spedibile($idCorriere, $nazione)
	{
		$elencoCorrieri = $this->elencoCorrieri();
		
		if (count($elencoCorrieri) > 0)
		{
			$idsCorrieri = $this->getIdsCorrieriNazione($nazione);
			
			if (in_array($idCorriere, $idsCorrieri))
				return true;
			
			return false;
		}
		
		return true;
	}
	
	public function selectTendina()
	{
		return array(0=>"Seleziona") + $this->orderBy("id_order")->toList("id_corriere","titolo")->send();
	}
	
	public function visibileCrud($record)
	{
		return $record["corrieri"]["visibile"] ? gtext("Sì") : gtext("No");
	}
}
