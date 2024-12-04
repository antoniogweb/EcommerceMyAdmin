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

class CookiearchivioController extends BaseController
{
	public $setAttivaDisattivaBulkActions = true;
	
	public $argKeys = array(
		'titolo:sanitizeAll'=>'tutti',
		'attivo:sanitizeAll'=>'tutti'
	);
	
	public $sezionePannello = "utenti";

	public function main()
	{
		Helper_Menu::$htmlLinks["carica"] = array(
			"htmlBefore" => '',
			"htmlAfter" => '',
			"attributes" => 'role="button" class="btn btn-primary"',
			"class"	=>	"",
			'text'	=>	"Carica cookie",
			"classIconBefore"	=>	'<i class="fa fa-upload"></i>',
			'url'	=>	'carica',
		);
		
		$this->shift();

		$this->mainButtons = "ldel,ledit";
		
		$attivaDisattiva = array(
			"tutti"	=>	"Usato / Non usato",
			"1"		=>	"Usato",
			"0"		=>	"Non usato",
		);
		
		$this->filters = array("titolo", array(
			"attivo",null,$attivaDisattiva
		));
		
		$mainMenu = "add,carica";
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>200, 'mainMenu'=>$mainMenu);
		
		$this->mainFields = array("cookie_archivio.titolo", "cookie_archivio.dominio", "cookie_archivio.path", "cookie_archivio.durata", "cookie_archivio.servizio", "cookie_archivio.secure", "cookie_archivio.same_site", "cookie_archivio.cross_site", "cookie_archivio.note", "attivoCrud");
		$this->mainHead = "Titolo,Dominio,Path,Durata,Servizio,Secure,SameSite,CrossSite,Note,Usato";
		
		$this->m[$this->modelName]->clear()->where(array(
			"OR"	=>	array(
				"lk"	=>	array("titolo"	=>	$this->viewArgs["titolo"]),
				" lk"	=>	array("dominio"	=>	$this->viewArgs["titolo"]),
				"  lk"	=>	array("servizio"	=>	$this->viewArgs["titolo"]),
			),
			"attivo"	=>	$this->viewArgs["attivo"],
		))->orderBy("titolo")->convert()->save();
		
		$this->tabella = "archivio cookie terzi";
		
		$this->bulkQueryActions = "attiva,disattiva";
		
		$this->bulkActions = array(
			"checkbox_cookie_archivio_id_cookie_archivio"	=>	array("attiva","ATTIVA"),
			" checkbox_cookie_archivio_id_cookie_archivio"	=>	array("disattiva","DISATTIVA"),
		);
		
		parent::main();
	}
	
	public function form($queryType = 'insert', $id = 0)
	{
		$this->_posizioni['main'] = 'class="active"';
		
		$campi = 'titolo,dominio,path,durata,servizio,secure,same_site,cross_site,note';
		
		$this->m[$this->modelName]->setValuesFromPost($campi);
		
		parent::form($queryType, $id);
	}
	
	public function carica()
	{
		$this->shift();
		$domain = str_replace("/admin","",DOMAIN_NAME);
		
		if (isset($_POST["cookie"]))
		{
			$cookieTecnici = App::getCookieTecnici();
			
			// echo "<pre>";
			$lines = explode("\n", $_POST["cookie"]);
			
			foreach ($lines as $l)
			{
				$riga = explode("\t", $l);
				
				foreach ($cookieTecnici as $cookie => $struct)
				{
					if ($cookie == $riga[0] && $riga[2] == $domain)
						continue 2;
				}
				
				$durata = "";
				
				if (isset($riga[4]))
				{
					$tt = explode(".", $riga[4]);
					
					if (count($tt) > 0)
					{
						$timestamp = strtotime($tt[0]);
						$durata = CookiearchivioModel::durata($timestamp);
					}
				}
				
				$rigaValues = array(
					"titolo"	=>	$riga[0] ?? "",
					"dominio"	=>	$riga[2] ?? "",
					"path"		=>	$riga[3] ?? "",
					"durata"	=>	$durata,
					"servizio"	=>	$riga[2] ?? "",
					"secure"	=>	$riga[7] ?? "",
					"same_site"	=>	$riga[8] ?? "",
					"cross_site"=>	$riga[10] ?? "",
					"attivo"	=>	1,
				);
				
				if ($rigaValues["titolo"])
				{
					$recordCookie = $this->m[$this->modelName]->clear()->where(array(
						"titolo"	=>	sanitizeAll($rigaValues["titolo"]),
						"dominio"	=>	sanitizeAll($rigaValues["dominio"]),
					))->record();
					
					$this->m[$this->modelName]->sValues($rigaValues);
					
					if (empty($recordCookie))
						$this->m[$this->modelName]->insert();
					else
					{
						if ($recordCookie["durata"])
							$this->m[$this->modelName]->delFields("durata");
						
						$this->m[$this->modelName]->update($recordCookie["id_cookie_archivio"]);
					}
				}
			}
			
			$this->redirect("cookiearchivio/main");
		}
		
		$this->load("carica");
	}
}
