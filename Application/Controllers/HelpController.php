<?php

// EcommerceMyAdmin is a PHP CMS based on MvcMyLibrary
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

class HelpController extends BaseController
{
	public $setAttivaDisattivaBulkActions = false;
	
	public $argKeys = array();
	
	public $sezionePannello = "utenti";
	
	public $orderBy = "id_order";
	
	public $tabella = "help";
	
	function __construct($model, $controller, $queryString, $application, $action) {
		
		parent::__construct($model, $controller, $queryString, $application, $action);
		
		$this->model("HelpitemModel");
		$this->model("HelpuserModel");
		
		$this->s["admin"]->check();
	}

	public function main()
	{
		$this->shift();
		
		$this->mainFields = array("help.titolo", "help.controlleraction", "help.tag");
		$this->mainHead = "Titolo,Controller/Action,Tag";
		
		$this->m[$this->modelName]->clear()
				->where(array(
// 					"lk" => array('titolo' => $this->viewArgs['cerca']),
				))
				->orderBy("id_order")->save();
		
		parent::main();
	}

	public function form($queryType = 'insert', $id = 0)
	{
		$fields = "titolo,controlleraction,tag,larghezza,descrizione";
		
		$this->_posizioni['main'] = 'class="active"';
		
		$this->m[$this->modelName]->setValuesFromPost($fields);
		
		parent::form($queryType, $id);
		
		$data["useEditor"] = true;
		
		$this->append($data);
	}
	
	public function elementi($id = 0)
	{
		$this->_posizioni['elementi'] = 'class="active"';
		
		$data["orderBy"] = $this->orderBy = "id_order";
		
		$data["ordinaAction"] = "ordinaelementi";
		
		$this->shift(1);
		
		$data['id'] = $clean['id'] = $this->id = (int)$id;
		$this->id_name = "id_help";
		
		$this->mainButtons = "ldel";
		
		$this->modelName = "HelpitemModel";
		
		$this->mainFields = array("edit", "help_item.selettore", "help_item.variabile");
		$this->mainHead = "Titolo,Selettore,Variabile";
		
		$this->scaffoldParams = array('popup'=>true,'popupType'=>'inclusive','recordPerPage'=>2000000,'mainMenu'=>'back','mainAction'=>"elementi/".$clean['id'],'pageVariable'=>'page_fgl');
		
		$this->m[$this->modelName]->clear()->select("*")->orderBy("help_item.id_order")->where(array("id_help"=>$clean['id']))->convert()->save();
		
		parent::main();
		
		$data["titoloRecord"] = $this->m["HelpModel"]->where(array("id_help"=>$clean['id']))->field("titolo");
		
		$this->append($data);
	}
	
	public function ordinaelementi()
	{
		$this->orderBy = "id_order";
		$this->modelName = "HelpitemModel";
		
		parent::ordina();
	}
	
	public function mostranascondi($id = 0, $valore = 0)
	{
		$this->clean();
		
		if ((int)$valore === 0 || (int)$valore === 1)
		{
			if (!$valore)
			{
				$this->m["HelpuserModel"]->setValues(array(
					"id_help"	=>	(int)$id,
					"id_user"	=>	User::$id,
				));
				
				$this->m["HelpuserModel"]->insert();
			}
			else
				$this->m["HelpuserModel"]->del(null, "id_help = ".(int)$id." AND id_user = ".(int)User::$id);
		}
	}
	
	public function pdf($idHelp = 0, $controller = "")
	{
		$this->clean();
		
		$this->m["HelpModel"]->clear()->select("*")->inner(array("elementi"))->orderBy("help_item.id_order");
		
		if ($idHelp)
			$this->m["HelpModel"]->aWhere(array(
				"id_help"	=>	(int)$idHelp,
			));
		else if ($controller)
			$this->m["HelpModel"]->aWhere(array(
				"lk"	=>	array(
					"controlleraction"	=>	sanitizeAll(rtrim($controller,"/")."/"),
				)
			));
		
		$elementi = $this->m["HelpModel"]->send();
		
		require_once(ROOT."/External/libs/vendor/autoload.php");

		ob_start();
		include(ROOT."/Application/Views/Help/pdf.php");
		$content = ob_get_clean();
		
		$params = [
			'mode' => '',
			'format' => 'A4',
			'default_font_size' => "9",
			'default_font' => "",
			'margin_left' => "6",
			'margin_right' => "6",
			'margin_top' => "5",
			'margin_bottom' => "10",
			'margin_header' => "0",
			'margin_footer' => "2",
			'orientation'	=>	"P",
		];
		
		$html2pdf = new \Mpdf\Mpdf($params);
		
		$html2pdf->setDefaultFont('Arial');
		
		$html2pdf->WriteHTML($content);
		
		$html2pdf->Output("guida.pdf","I");
	}
}
