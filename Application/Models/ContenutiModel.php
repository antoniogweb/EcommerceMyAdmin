<?php

// EcommerceMyAdmin is a PHP CMS based on EasyGiant
//
// Copyright (C) 2009 - 2020  Antonio Gallo (info@laboratoriolibero.com)
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

class ContenutiModel extends GenericModel {

	public function __construct() {
		$this->_tables='contenuti';
		$this->_idFields='id_cont';
		
		$this->_lang = 'It';
		
		$this->_idOrder = 'id_order';
		
		$this->addStrongCondition("both",'checkNotEmpty',"titolo");
		
		$this->uploadFields = array(
			"immagine_1"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/contenuti",
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg,gif',
				'allowedMimeTypes'	=>	'',
				"createImage"	=>	false,
				"maxFileSize"	=>	6000000,
// 				"clean_field"	=>	"clean_immagine",
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	300,
					'imgHeight'		=>	300,
					'defaultImage'	=>  null,
					'cropImage'		=>	'no',
				),
			),
			"immagine_2"	=>	array(
				"type"	=>	"image",
				"path"	=>	"images/contenuti",
// 				"mandatory"	=>	true,
				"allowedExtensions"	=>	'png,jpg,jpeg,gif',
				'allowedMimeTypes'	=>	'',
				"createImage"	=>	false,
				"maxFileSize"	=>	6000000,
// 				"clean_field"	=>	"clean_immagine",
				"Content-Disposition"	=>	"inline",
				"thumb"	=> array(
					'imgWidth'		=>	300,
					'imgHeight'		=>	300,
					'defaultImage'	=>  null,
					'cropImage'		=>	'no',
				),
			),
		);
		
		parent::__construct();
	}
	
	public function relations() {
        return array(
			'page' => array("BELONGS_TO", 'PagesModel', 'id_page',null,"CASCADE"),
			'category' => array("BELONGS_TO", 'CategoriesModel', 'id_c',null,"CASCADE"),
			'tipo' => array("BELONGS_TO", 'TipicontenutoModel', 'id_tipo',null,"CASCADE"),
			'groups' => array("MANY_TO_MANY", 'ReggroupsModel', 'id_group', array("ReguserscontenutiModel","id_cont","id_group"), "CASCADE"),
        );
    }
    
    public function setFormStruct()
	{
		$tipo = (isset($_GET["tipo"]) && in_array($_GET["tipo"], array("FASCIA","GENERICO","MARKER"))) ? $_GET["tipo"] : "FASCIA";
		
		$this->formStruct = array
		(
			'entries' 	=> 	array(
				'lingua'	=>	array(
					"type"	=>	"Select",
					"options"	=>	$this->selectLingua(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'id_tipo'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Tipo contenuto",
					"options"	=>	$this->selectTipo($tipo),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'target'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Target link",
					"options"	=>	array(
						"STESSO_TAB"	=>	"Apri nella stessa scheda",
						"NUOVO_TAB"		=>	"Apri in una nuova scheda",
					),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
				'link_contenuto'	=>	array(
					"type"	=>	"Select",
					"labelString"	=>	"Link al contenuto",
					"options"	=>	$this->selectLinkContenuto(),
					"reverse"	=>	"yes",
					"className"	=>	"form-control",
				),
			),
			
			'enctype'	=>	'multipart/form-data',
		);
	}
	
	public function selectTipo($tipo = null)
	{
		$t = new TipicontenutoModel();
		
		$t->clear()->orderBy("id_order")->toList("id_tipo","titolo");
		
// 		if ($tipo == "FASCIA")
			$t->where(array(
				"tipo"	=>	sanitizeDb($tipo),
			));
// 		else
// 			$t->where(array(
// 				"ne"	=>	array("tipo"	=>	"FASCIA"),
// 			));
		
		return $t->send();
	}
	
    public function titoloContenuto($record)
	{
		return "<a class='iframe action_iframe' href='".Url::getRoot()."contenuti/form/update/".$record["contenuti"]["id_cont"]."?partial=Y&nobuttons=Y&tipo=".$record["contenuti"]["tipo"]."'>".$record["contenuti"]["titolo"]."</a>";
	}
	
	public function attivo($record)
	{
		if ($record["contenuti"]["attivo"] == "No")
			return "No";
		
		return "Sì";
	}
	
	public function immagini($record)
	{
		$html = "";
		
		if ($record["contenuti"]["immagine_1"] && file_exists(Domain::$parentRoot."/images/contenuti/".$record["contenuti"]["immagine_1"]))
			$html .= "<a target='_blank' href='".Domain::$name."/images/contenuti/".$record["contenuti"]["immagine_1"]."'><img src='".Url::getRoot()."contenuti/thumb/immagine_1/".$record["contenuti"]["id_cont"]."' /></a>";
		
		if ($record["contenuti"]["immagine_2"] && file_exists(Domain::$parentRoot."/images/contenuti/".$record["contenuti"]["immagine_2"]))
			$html .= "<a target='_blank' href='".Domain::$name."/images/contenuti/".$record["contenuti"]["immagine_2"]."'><img style='margin-top:5px;' src='".Url::getRoot()."contenuti/thumb/immagine_2/".$record["contenuti"]["id_cont"]."' /></a>";
		
		return $html;
	}
	
	public function update($id = NULL, $whereClause = NULL)
	{
		if ($this->upload("update"))
			return parent::update($id, $whereClause);
	}
	
	public function insert()
	{
		if ($this->upload("insert"))
			return parent::insert();
	}
	
	//duplica i contenuti
	public function duplica($from_id, $to_id)
	{
		$clean["from_id"] = (int)$from_id;
		$clean["to_id"] = (int)$to_id;
		
		$res = $this->clear()->where(array("id_page"=>$clean["from_id"]))->orderBy("id_order")->send(false);
		
		foreach ($res as $r)
		{
			$this->setValues($r, "sanitizeDb");
			$this->setValue("id_page", $to_id);
			
			unset($this->values["id_cont"]);
			unset($this->values["data_creazione"]);
			unset($this->values["id_order"]);
// 			print_r($this->values);
			parent::insert();
		}
		
// 		print_r($this->db->queries);
	}
	
	public function elaboraContenuti($idPage, $idC = 0, $obj = null)
	{
		// Estraggo le fasce
		$this->clear()->select("*")->inner(array("tipo"))->where(array(
			"OR"	=>	array(
				"lingua" => "tutte",
				" lingua" => sanitizeDb(Params::$lang),
			),
			"tipo"	=>	"FASCIA",
		))->orderBy("contenuti.id_order");
		
		if (!User::$adminLogged)
			$this->aWhere(array(
				"attivo"	=>	"Y",
			));
		
		if ($idPage)
			$this->aWhere(array(
				"contenuti.id_page"	=>	(int)$idPage,
			));
		else
			$this->aWhere(array(
				"contenuti.id_c"	=>	(int)$idC,
			));
		
		$fasce = $this->send();
		
		$htmlFinale = "";
		
		if (count($fasce) > 0)
		{
			$htmlFinale = "<div class='blocco_fasce_contenuto'>";
			
			foreach ($fasce as $f)
			{
				$html = htmlentitydecode($f["tipi_contenuto"]["descrizione"]);
				
				$html = preg_replace('/\[testo (.*?)\]/', '[testo ${1}_'.$f["contenuti"]["id_cont"].']' ,$html);
				$html = preg_replace('/\[immagine (.*?)\]/', '[immagine ${1}_'.$f["contenuti"]["id_cont"].']' ,$html);
				$html = preg_replace('/\[link (.*?)\]/', '[link ${1}_'.$f["contenuti"]["id_cont"].']' ,$html);
				$html = preg_replace('/\[video (.*?)\]/', '[video ${1}_'.$f["contenuti"]["id_cont"].']' ,$html);
				
				$htmlFinale .= "<div id='".$f["contenuti"]["id_cont"]."' class='fascia_contenuto'>";
				
				if (User::$adminLogged)
				{
					$htmlFinale .= "<div class='titolo_fascia'>Fascia: <b>".$f["contenuti"]["titolo"]."</b>";
					
					$htmlFinale .= " - LINGUA: <b>".strtoupper($f["contenuti"]["lingua"])."</b>";
					
					$htmlFinale .= "<a class='iframe' href='".Url::getFileRoot()."admin/contenuti/form/update/".$f["contenuti"]["id_cont"]."?partial=Y'><img src='".Url::getFileRoot()."Public/Img/Icons/elementary_2_5/edit.png' /></a>";
					
					$htmlFinale .= "</div>";
				}
				
				$htmlFinale .= attivaModuli($html, $obj)."</div>";
			}
			
			$htmlFinale .= "</div>";
		}
		
		return $htmlFinale;
	}
}
