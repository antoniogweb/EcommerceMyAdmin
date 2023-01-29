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

use PHPUnit\Framework\TestCase;

define('APP_CONSOLE', true);

require_once(dirname(__FILE__) . "/../../index.php");
require_once(LIBRARY . '/External/libs/vendor/autoload.php');

Params::$lang = "it";
ImpostazioniModel::init();

final class PagesModelTest extends TestCase
{
	public function testCampoCerca(): void
    {
		$p = new PagesModel();
		
		$p->db->beginTransaction();
		
		$lId = Tests::creaPagina("prodotti");
		
		$record = $p->clear()->selectId((int)$lId);
		
		// Controllo la creazione della pagina
		$this->assertNotEmpty($record, "Check creazione pagina");
		
		if (v("mostra_filtro_ricerca_libera_in_magazzino"))
		{
			$this->assertTrue((string)$record["campo_cerca"] !== "", "check creazione campo cerca");
			
			$codiceMotoreRicerca = MotoriricercaModel::getCodiceAttivo();
			
			if ($codiceMotoreRicerca == "INTERNO")
			{
				$numeroRicerche = PagesricercaModel::g()->clear()->where(array(
					"id_page"	=>	(int)$lId,
				))->rowNumber();
				
				$this->assertTrue((int)$numeroRicerche === 5, "check inserimento righe di tabella pages_ricerca");
			}
		}
		
		$p->del((int)$lId);
		
		$numero = $p->clear()->whereId((int)$lId)->rowNumber();
		
		// Controllo l'eliminazione della pagina
		$this->assertTrue((int)$numero === 0, "check eliminazione pagina");
		
		$numeroRicerche = PagesricercaModel::g()->clear()->where(array(
			"id_page"	=>	(int)$lId,
		))->rowNumber();
		
		$this->assertTrue((int)$numeroRicerche === 0, "check eliminazione righe di tabella pages_ricerca");
		
		$p->db->commit();
    }
    
    public function testProdottoSenzaVarianti(): void
    {
		$p = new PagesModel();
		
		$p->db->beginTransaction();
		
		$c = new CombinazioniModel();
		
		$lId = Tests::creaPagina("prodotti");
		
		$numeroCombinazioni = $c->clear()->where(array(
			"id_page"	=>	(int)$lId,
		))->rowNumber();
		
		$this->assertTrue((int)$numeroCombinazioni === 1, "check creazione combinazione");
		
		$numeroCombinazioniCanoniche = $c->clear()->where(array(
			"id_page"	=>	(int)$lId,
			"canonical"	=>	1,
		))->rowNumber();
		
		$this->assertTrue((int)$numeroCombinazioniCanoniche === 1, "check creazione combinazione canonica");
		
		$p->del((int)$lId);
		
		$numeroCombinazioni = $c->clear()->where(array(
			"id_page"	=>	(int)$lId,
		))->rowNumber();
		
		$this->assertTrue((int)$numeroCombinazioni === 0, "check eliminazione combinazione canonica");
		
		$p->db->commit();
    }
    
    public function testProdottoConVarianti(): void
    {
		$p = new PagesModel();
		
		$p->db->beginTransaction();
		
		$c = new CombinazioniModel();
		$ca = new CombinazionialiasModel();
		$a = new AttributiModel();
		$av = new AttributivaloriModel();
		$pa = new PagesattributiModel();
		
		$prezzo = 20.00;
		$prezzoScontato = 15.00;
		
		$idPage = Tests::creaPagina("prodotti", array(
			"price_ivato"	=>	$prezzo,
			"id_iva"		=>	1,
			"in_promozione"	=>	"Y",
			"dal"			=>	date("Y-m-d"),
			"al"			=>	date("Y-m-d"),
			"tipo_sconto"	=>	"ASSOLUTO",
			"prezzo_promozione_ass_ivato"	=>	$prezzoScontato,
		));
		
		$numeroVarianti = 2;
		
		$idsA = Tests::creaAttributi($numeroVarianti,3);
		
		$col = 1;
		
		$idsPa = [];
		
		// Aggiungo le varianti
		foreach ($idsA as $idA)
		{
			$pa->sValues(array(
				"id_page"	=>	$idPage,
				"id_a"		=>	$idA,
				"colonna"	=>	$col,
			));
			
			$pa->insert();
			
			$idsPa[] = $pa->lId;
			
			$col++;
		}
		
		### CONTROLLO IL NUMERO DI COMBINAZIONI ###
		$numeroCombinazioniAspettate = pow(3,2);
		
		$numeroCombinazioniReali = $c->clear()->where(array(
			"id_page"	=>	(int)$idPage,
		))->rowNumber();
		
		$this->assertTrue((int)$numeroCombinazioniAspettate === (int)$numeroCombinazioniReali, "check creazione delle combinazioni");
		
		$numeroCombinazioniCanoniche = $c->clear()->where(array(
			"id_page"	=>	(int)$idPage,
			"canonical"	=>	1,
		))->rowNumber();
		
		$this->assertTrue((int)$numeroCombinazioniCanoniche === 1, "check creazione combinazione canonica");
		
		### CONTROLLO IL NUMERO DI ALIAS DELLE COMBINAZIONI ###
		if (VariabiliModel::combinazioniLinkVeri())
		{
			$numeroCombinazioniAliasReali = $ca->clear()->where(array(
				"id_page"	=>	$idPage,
			))->rowNumber();
			
			$numeroCombinazioniAliasAspettate = $numeroCombinazioniAspettate * Tests::numeroLingueAttive();
			
			$this->assertTrue((int)$numeroCombinazioniAliasAspettate === (int)$numeroCombinazioniAliasReali, "check creazione combinazioni alias");
		}
		
		### CONTROLLO I PREZZI SCONTATI ###
		$numeroCombinazioniNonScontate = $c->clear()->where(array("id_page"=>(int)$idPage))->sWhere(array("(price_ivato != ? or price_scontato_ivato != ?)", array($prezzo, $prezzoScontato)))->rowNumber();
		
		$this->assertTrue((int)$numeroCombinazioniNonScontate === 0, "check che le combinazioni siano scontate");
		
		$idC = CategoriesModel::g(false)->getIdFromSection("prodotti");
		
		PagesModel::$arrayIdCombinazioni = array();
		
		### ELIMINO LA PROMO ###
		$p->sValues(array(
			"title"	=>	Tests::getTitolo(),
			"alias"	=>	"",
			"id_c"	=>	$idC,
			"in_promozione"	=>	"N",
		));
		
		$p->update((int)$idPage);
		
		### CONTROLLO NO PREZZI SCONTATI ###
		$numeroCombinazioniScontate = $c->clear()->where(array("id_page"=>(int)$idPage))->sWhere("price_ivato != price_scontato_ivato")->rowNumber();
		
		$this->assertTrue((int)$numeroCombinazioniScontate === 0, "check che le combinazioni non siano scontate");
		
		### ELIMINO UNA ALLA VOLTA LE VARIANTI ###
		foreach ($idsPa as $idPa)
		{
			$pa->del((int)$idPa);
			
			$numeroVarianti--;
			
			### CONTROLLO IL NUMERO DI COMBINAZIONI ###
			$numeroCombinazioniAspettate = pow(3,$numeroVarianti);
			
			$numeroCombinazioniReali = $c->clear()->where(array(
				"id_page"	=>	(int)$idPage,
			))->rowNumber();
			
			$this->assertTrue((int)$numeroCombinazioniAspettate === (int)$numeroCombinazioniReali, "check creazione delle combinazioni");
			
			$numeroCombinazioniCanoniche = $c->clear()->where(array(
				"id_page"	=>	(int)$idPage,
				"canonical"	=>	1,
			))->rowNumber();
			
			$this->assertTrue((int)$numeroCombinazioniCanoniche === 1, "check creazione combinazione canonica");
		}
		
		### INIZIO ELIMINAZIONE OGGETTI ###
		$p->del((int)$idPage);
		
		### CONTROLLO CHE ABBIA ELIMINATO LE COMBINAZIONI ###
		$numeroCombinazioniReali = $c->clear()->where(array(
			"id_page"	=>	(int)$idPage,
		))->rowNumber();
		
		$this->assertTrue((int)$numeroCombinazioniReali === 0, "check eliminazione delle combinazioni");
		
		### CONTROLLO CHE ABBIA ELIMINATO GLI ALIAS DELLE COMBINAZIONI ###
		$numeroCombinazioniAliasReali = $ca->clear()->where(array(
			"id_page"	=>	$idPage,
		))->rowNumber();
		
		$this->assertTrue((int)$numeroCombinazioniAliasReali === 0, "check eliminazione delle combinazioni alias");
		
		Tests::eliminaAttributi($idsA);
		
		$p->db->commit();
    }
}

