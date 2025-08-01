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

function statoOrdine($type)
{
// 	echo OrdiniModel::$stati[$type];
// 	print_r(OrdiniModel::$stati);
	if (isset(OrdiniModel::$stati[$type]))
		return v("attiva_gestione_stati_ordine") ? OrdiniModel::$stati[$type] : gtext(OrdiniModel::$stati[$type]);
	
	return $type;
}

function statoOrdineBreve($type)
{
	return statoOrdine($type);
}

function labelStatoOrdine($type)
{
	if (isset(OrdiniModel::$labelStati[$type]))
		return OrdiniModel::$labelStati[$type];
	
	return $type;
}

function metodoPagamento($type)
{
	if (isset(OrdiniModel::$pagamenti[$type]))
		return OrdiniModel::$pagamenti[$type];
	else
	{
		$res = PagamentiModel::g(true)->clear()->where(array(
			"codice"	=>	sanitizeAll($type),
		))->first();
		
		if ($res)
			return pfield($res, "titolo");
	}
	
	return gtext("Nessuno", false);
}

function getFirstImage($id_page)
{
// 	$p = new PagesModel();

	$clean['id_page'] = (int)$id_page;
	
	$i = new ImmaginiModel();
	
	return $i->getFirstImage($clean['id_page']);
}

function getImages($id_page)
{
	$clean['id_page'] = (int)$id_page;
	
	$i = new ImmaginiModel();
	
	return $i->clear()->where(array("id_page"=>$clean['id_page']))->orderBy("immagini.id_order desc")->toList("immagine")->send();
}

function getUrlAlias($id_page, $idC = 0)
{
	$clean["id_page"] = (int)$id_page;
	
	return Cache_Functions::getInstance()->load(new PagesModel())->getUrlAlias($clean["id_page"], null, $idC);
	
// 	$p = new PagesModel();
// 	
// 	return $p->getUrlAlias($clean["id_page"], null, $idC);
}

function getCategoryUrlAlias($id_c)
{
	$clean["id_c"] = (int)$id_c;
	
	return Cache_Functions::getInstance()->load(new CategoriesModel())->getUrlAlias($clean["id_c"]);
	
// 	$p = new CategoriesModel();
	
// 	return $p->getUrlAlias($clean["id_c"]);
}

function getMarchioUrlAlias($id_c, $paginaDettaglioMarchio = false)
{
	$clean["id_c"] = (int)$id_c;
	
	return Cache_Functions::getInstance()->load(new MarchiModel())->getUrlAlias($clean["id_c"], $paginaDettaglioMarchio);
	
// 	$p = new MarchiModel();
// 	
// 	return $p->getUrlAlias($clean["id_c"], $paginaDettaglioMarchio);
}

function getTitoloMarchio($idM)
{
	$m = new MarchiModel();
	
	return $m->titolo((int)$idM);
}

function getDocumenti($id_page)
{
	$clean["id_page"] = (int)$id_page;
	
	$p = new PagesModel();
	
	return $p->getDocumenti($clean["id_page"]);
}

function getCatNameForFilters($id)
{
	$clean["id"] = (int)$id;
	$c = new CategoriesModel();
// 	$children = $c->children($clean["id"],true);
// 	$cWhere = "(".implode(',',$children).")";
// 	$p = new PagesModel();
// 	$nChildren = $p->clear()->where(array("-id_c" => "in$cWhere"))->rowNumber();
// 
// 	return $c->indent($clean["id"],false)." (".$nChildren.")";

	return $c->indent($clean["id"],false,false);
}

function encodeUrl($url)
{
// 	$url = utf8_decode(html_entity_decode($url,ENT_QUOTES,'UTF-8'));
	
	$char = v("carattere_divisione_parole_permalink");
	
	$url = html_entity_decode($url,ENT_QUOTES,'UTF-8');
	$url = F::convertiLettereAccentate($url);
	$url = mb_convert_encoding($url, 'ISO-8859-1', 'UTF-8');
	
	$temp = "";
	for ($i=0;$i<strlen($url); $i++)
	{
		if (strcmp($url[$i],' ') === 0)
		{
			$temp .= $char;
		}
		else
		{
			if (preg_match('/^[a-zA-Z_0-9\-]$/',$url[$i]))
			{
				$temp .= $url[$i];
			}
			else
			{
				$temp .= $char;
			}
		}
	}

	$temp = str_replace($char.$char,$char,$temp);
	$temp = str_replace($char.$char,$char,$temp);
	
	if (strcmp($temp,"") === 0)
	{
		$temp = "a";
	}
	
	$temp = urlencode(strtolower($temp));
	return $temp;
}

function isValidImgName($imageName)
{
	if (preg_match('/^[a-zA-Z0-9_\-]+$/',$imageName))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function sanitizeFileName($imageName)
{
	$imageName = str_replace(' ','_',$imageName);

	if (preg_match('/^[a-zA-Z0-9_\@\-]+$/',$imageName) and strcmp(trim($imageName),'') !== 0)
	{
		return $imageName;
	}
	else
	{
		return 'file';
	}
}

function sanitizeFileNameUploadGenerico($imageName)
{
	$imageName = str_replace(' ','_',$imageName);

	if (preg_match('/^[a-zA-Z0-9_\@\-]+$/',$imageName) and strcmp(trim($imageName),'') !== 0)
	{
		return $imageName;
	}
	else
	{
		return encodeUrl($imageName);
	}
}

function getYesNoUtenti($input)
{
	switch($input)
	{
		case 0:
			$output = gtext('sì');
			break;
		case 1:
			$output = gtext('no');
			break;
		default:
			$output = 'undef';
	}
	return $output;
}

function getYesNo($input)
{
	switch($input)
	{
		case 'Y':
			$output = gtext('sì');
			break;
		case 'N':
			$output = gtext('no');
			break;
		default:
			$output = 'undef';
	}
	return $output;
}

function getYesNoPromozione($id_page)
{
	$clean["id_page"] = (int)$id_page;
	echo $clean["id_page"];
	$inProm = inPromozione($clean["id_page"]);
	
	if ($inProm)
	{
		return gtext("sì");
	}
	return gtext("no");
}

function accepted($fileName)
{
	if (preg_match(v("reg_expr_file"),$fileName))
	{
		return true;
	}
	return false;
}

function reverseData($data)
{
	$data = explode('-',$data);
	krsort($data);
	$data = implode('-',$data);
	return $data;
}

function smartDate($uglyDate = null, $format = "d-m-Y")
{
	return date($format,strtotime($uglyDate));
}

function smartDateSlash($uglyDate = null)
{
	return date('d/m/Y',strtotime($uglyDate));
}

function smartDateTime($time = null)
{
	return date('d-m-Y',$time);
}

function sharpen($img)
{
// 	ImageConvolution($img, array(array(-1, -1, -1), array(-1, 16, -1), array(-1, -1, -1)), 8, 0);

	return $img;
}

//$date:Y-m
function getTimeStamp($date)
{
	$temp = explode('-',$date);
// 	print_r($temp);
	return mktime(0, 0, 0, (int)$temp[1], 1, (int)$temp[0]);
}

//$date:Y-m-d
function getTimeStampComplete($date)
{
	$temp = explode('-',$date);
// 	print_r($temp);
	return mktime(0, 0, 0, (int)$temp[1], (int)$temp[2], (int)$temp[0]);
}

//$time: h:m:s
//$date:Y-m-d
function getTimeStampFull($time,$date)
{
	$temp = explode(':',$time);
	$temp2 = explode('-',$date);
// 	print_r($temp);
	return mktime((int)$temp[0], (int)$temp[1], (int)$temp[2], (int)$temp2[1], (int)$temp2[2], (int)$temp2[0]);
}

function isDate($date)
{
	if (preg_match('/^[0-9]{4}\-[0-9]{2}$/',$date))
	{
		$dateArray = explode('-',$date);
		if ((int)$dateArray[1] <= 12 and (int)$dateArray[1] >= 1 )
		{
			if ((int)$dateArray[0] >= 1970 or (int)$dateArray[0] <= 2150)
			{
				return true;
			}
		}
	}
	return false;
}

function isDateFull($date)
{
	if (preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/',$date))
	{
		$dateArray = explode('-',$date);
		if ((int)$dateArray[1] <= 12 and (int)$dateArray[1] >= 1 )
		{
			if ((int)$dateArray[0] >= 1970 and (int)$dateArray[0] <= 2150)
			{
				if ((int)$dateArray[2] >= 1 and (int)$dateArray[2] <= 31)
				{
					$time = getTimeStamp((int)$dateArray[0]."-".(int)$dateArray[1]);
					$nDays = date('t',$time);
					if ((int)$dateArray[2]<=$nDays)
					{
						return true;
					}
				}
			}
		}
	}
	return false;
}

function isNotPast($date)
{
	if (isDateFull($date))
	{
		$timeStamp = getTimeStampComplete($date);
		$currentTimeStamp = getTimeStampComplete(date("Y-m-d"));

		if ($timeStamp >= $currentTimeStamp)
		{
			return true;
		}
	}
	return false;
}

function setPrice($price)
{
	$price = str_replace(",",".",$price);
	return $price;
}

function setPriceReverse($price, $numeroCifre = 2)
{
	$price = number_format((float)$price,$numeroCifre,".","");
	return str_replace(".",",",$price);
}

function hasActiveCoupon($ido = null)
{
	if (isset(User::$coupon))
	{
		$c = new PromozioniModel();
		return $c->isActiveCoupon(User::$coupon, $ido);
	}
	
	return false;
}

function getNomePromozione()
{
	if (hasActiveCoupon())
	{
		$p = new PromozioniModel();
		
		$coupon = $p->getCoupon(User::$coupon);
		return $coupon["titolo"];
	}
	
	return "";
}

function getSubTotalN($ivato = 0)
{
	$c = new CartModel();
	$total = $c->total(false);
	
// 	IvaModel::getAliquotaEstera();
// 	
// 	if (isset(IvaModel::$titoloAliquotaEstera))
// 		$ivato = 0;
	
	if ($ivato)
		$total += $c->iva(false, true);
	
	return $total;
}

function getSubTotal($ivato = 0)
{
	return setPriceReverse(getSubTotalN($ivato));
}

function getPrezzoScontatoN($conSpedizione = false, $ivato = 0, $pieno = false, $conCrediti = true, $conCouponAssoluto = true)
{
	$c = new CartModel();
	$totale = $c->totaleScontato($conSpedizione, $pieno, $conCrediti, $conCouponAssoluto);
	
// 	IvaModel::getAliquotaEstera();
// 	
// 	if (isset(IvaModel::$titoloAliquotaEstera))
// 		$ivato = 0;
	
	if ($ivato)
		$totale += $c->iva($conSpedizione, $pieno, $conCrediti, $conCouponAssoluto);
	
	return $totale;
}

function getPrezzoScontato($ivato = 0)
{
	return setPriceReverse(getPrezzoScontatoN(false, $ivato));
}

function getPagamentoN()
{
	// Controllo se disattivare il costo del pagamento per ordini OFFLINE
	if (!App::$isFrontend && v("disattiva_costo_pagamento_ordini_offline"))
		return 0;
	
	return PagamentiModel::getCostoCarrello();
}

function getPagamento($ivato = false)
{
	if ($ivato)
		return setPriceReverse(getPagamentoN() * (1 + (CartModel::getAliquotaIvaSpedizione() / 100)));
	else
		return setPriceReverse(getPagamentoN());
}

function getSpedizioneN($pieno = null)
{
	// Controllo che sia attiva la spedizione
	if (!v("attiva_spedizione"))
		return 0;
	
	// Controllo se disattivare il costo della spedizione per ordini OFFLINE
	if (!App::$isFrontend && v("disattiva_costo_spedizione_ordini_offline"))
		return 0;
	
	if (!isset($pieno))
		$pieno = PromozioniModel::hasCouponAssoluto() ? true : false;
	
	if (!v("prezzi_ivati_in_carrello"))
		$subtotale = getPrezzoScontatoN(false, false, $pieno, false);
	else
		$subtotale = getPrezzoScontatoN(false, true, $pieno, false);
	
	$subtotale = number_format($subtotale, 2, ".", "");
	
	$nazione = User::getSpedizioneDefault();
	
	if (isset($_POST["nazione_spedizione"]))
		$nazione = $_POST["nazione_spedizione"];
	
	// Se il totale è sopra la soglia delle spedizioni gratuite, le spese di spedizione sono 0
	// if ((v("soglia_spedizione_gratuita_attiva_in_tutte_le_nazioni") || $nazione == v("nazione_default")) && ImpostazioniModel::$valori["spedizioni_gratuite_sopra_euro"] > 0 && $subtotale >= ImpostazioniModel::$valori["spedizioni_gratuite_sopra_euro"])
	if (NazioniModel::spedizioneGratuita($nazione, $subtotale))
		return 0;
	
	// Prendo le spese dall'account
	if (App::$isFrontend && User::$logged && isset(User::$dettagli["spese_spedizione"]) && User::$dettagli["spese_spedizione"] >= 0)
		return User::$dettagli["spese_spedizione"];
	
	$c = new CartModel();
	$corr = new CorrieriModel();
	$corrSpese = new CorrierispeseModel();
	
	$peso = $c->getPesoTotale();
	
	$corriere = array();
	
	// cerca il corriere dal carrello
	$idCorriereDaCarrello = $corr->getIdCorriereDaCarrello();
	
	if (isset($_POST["id_corriere"]))
		$corriere = $corr->selectId((int)$_POST["id_corriere"]);
	else if (v("scegli_il_corriere_dalla_categoria_dei_prodotti") && $idCorriereDaCarrello)
		$corriere = $corr->selectId((int)$idCorriereDaCarrello);
	else
	{
		$corrieri = $corr->clear()->select("distinct corrieri.id_corriere,corrieri.*")->inner("corrieri_spese")->using("id_corriere")->orderBy("corrieri.id_order")->send(false);
		
		if (count($corrieri) > 0)
			$corriere = $corrieri[0];
	}
	
	if (!empty($corriere))
		return $corrSpese->getPrezzo($corriere["id_corriere"],$peso, $nazione);
	
	return 0;
}

function getSpedizione($ivato = false)
{
// 	IvaModel::getAliquotaEstera();
// 	
// 	if (isset(IvaModel::$titoloAliquotaEstera))
// 		$ivato = 0;
	
	if ($ivato)
	{
		$ivaSpedizione = CartModel::getAliquotaIvaSpedizione();
		
		return setPriceReverse(getSpedizioneN() * (1 + ($ivaSpedizione / 100)));
	}
	else
		return setPriceReverse(getSpedizioneN());
}

function spedibile($idCorriere, $nazione)
{
	$corr = new CorrieriModel();
	
	return $corr->spedibile($idCorriere, $nazione);
}

function getIvaN($pieno = false)
{
	if (Parametri::$ivaInclusa)
	{
		return 0;
	}
	else
	{
		$c = new CartModel();
		$iva = $c->iva(true, $pieno);
// 		$iva = $iva + (getSpedizioneN() * Parametri::$iva)/100;
		return $iva;
	}
}

function getIva()
{
	return setPriceReverse(getIvaN());
}

function getTotalN($pieno = false)
{
// 	$cifre = v("cifre_decimali");
	$cifre = CartModel::getCifreCalcolo();
	
	$totalConSpedizione = getPrezzoScontatoN(true, 0, $pieno);
	$iva = getIvaN($pieno);
	
// 	return $iva;
	return number_format($totalConSpedizione,$cifre,".","") + number_format($iva,$cifre,".","");
}

function getTotal($pieno = false)
{
	return setPriceReverse(getTotalN($pieno));
}

function inPromozione($id_page, $page = null)
{
	$clean['id_page'] = (int)$id_page;
	
	$p = new PagesModel();

	return $p->inPromozione($clean['id_page'], $page);
}

function inPromozioneTot($id_page, $page = null)
{
	$clean['id_page'] = (int)$id_page;
	
	$p = new PagesModel();

	return $p->inPromozioneTot($clean['id_page'], $page);
}

function hasCombinations($id_page, $personalizzazioni = true)
{
	$clean['id_page'] = (int)$id_page;
	
	return Cache_Functions::getInstance()->load(new PagesModel())->hasCombinations($clean['id_page'], $personalizzazioni);
	
// 	$p = new PagesModel();

// 	return $p->hasCombinations($clean['id_page'], $personalizzazioni);
}

class Domain
{
	static public $pathSettati = false;
	
	static public $name;
	static public $publicUrl;
	static public $parentRoot;
	static public $adminRoot;
	static public $adminName;
	static public $currentUrl;
	
	public static function setPath()
	{
		if (self::$pathSettati)
			return;
		
		self::$parentRoot = FRONT;
		self::$adminRoot = LIBRARY;
		
		self::$pathSettati = true;
	}
	
	public static function setPathFromAdmin()
	{
		self::$name = self::$publicUrl = str_replace("/admin/","",Url::getFileRoot());
		
		self::$parentRoot = str_replace("/admin","",ROOT);
		
		self::$adminRoot = ROOT;
		self::$adminName = rtrim(Url::getFileRoot(), "/");
	}
}

function htmlentitydecode($value)
{
	$value = nullToBlank($value);
	return html_entity_decode($value, ENT_QUOTES, "UTF-8");
}

function htmlentitydecodeDeep($value) {
	return array_map('htmlentitydecode', $value);
}

//restituisci $numProdotti case random tra quelle presenti in $arrayProdotti
function getRandom($arrayProdotti, $numProdotti = 20)
{
	$res = array();
	$max = count($arrayProdotti)-1;

	for ($i=0;$i<=100;$i++)
	{
		$temp = rand(0,$max);
		if (!in_array($temp,$res))
		{
			$res[] = $temp;
		}
		if (count($res) >= $numProdotti)
		{
			break;
		}
	}
	$ret = array();
	foreach ($res as $value)
	{
		if (isset($arrayProdotti[$value]))
		{
			$ret[] = $arrayProdotti[$value];
		}
	}
	return $ret;
}

function calcolaPromozione($pieno, $scontato)
{
	if ($pieno > 0)
	{
		$pieno = (float)$pieno;
		$scontato = (float)$scontato;
		
		return round((($pieno-$scontato)/$pieno)*100);
	}
	else
	{
		return 0;
	}
}

function tagliaStringa($string, $num = 40)
{
	$string = strip_tags(htmlentitydecode($string));
	
	if (eg_strlen($string) > $num)
	{
		return mb_substr($string,0,$num)."...";
	}
	return sanitizeHtml($string);
}

function prezzoPromozione($p)
{
	$prezzo = calcolaPrezzoFinale($p["pages"]["id_page"], prezzoMinimo($p["pages"]["id_page"]));
	
	return $prezzo >= 0 ? setPriceReverse($prezzo) : "0,00";
}

function getPrincipale($id_page)
{
	$p = new PagesModel();
	
	return $p->getPrincipale((int)$id_page);
}

function isProdotto($id_page)
{
	$clean['id_page'] = (int)$id_page;
	
	return Cache_Functions::getInstance()->load(new PagesModel())->isProdotto($clean['id_page']);
	
// 	$p = new PagesModel();
// 	
// 	return $p->isProdotto($clean['id_page']);
}

function getField($en, $it)
{
	return strcmp($en,"") !== 0 ? $en : $it;
}

function field($p, $field)
{
	if (isset($p["contenuti_tradotti"][$field]) and strcmp($p["contenuti_tradotti"][$field],"") !== 0)
	{
		return $p["contenuti_tradotti"][$field];
	}
	else if (isset($p["pages"][$field]))
	{
		return $p["pages"][$field];
	}
	
	return "";
}

function cfield($p, $field, $tableTradotta = "contenuti_tradotti_categoria")
{
	if (isset($p[$tableTradotta][$field]) and strcmp($p[$tableTradotta][$field],"") !== 0)
		return $p[$tableTradotta][$field];
	else
	{
		if (isset($p["categories"][$field]))
			return $p["categories"][$field];
	}
	
	return "";
}

function mfield($p, $field)
{
	return genericField($p, $field, "marchi");
}

function dfield($p, $field)
{
	return genericField($p, $field, "documenti");
}

function contfield($p, $field)
{
	return genericField($p, $field, "contenuti");
}

function afield($p, $field)
{
	return genericField($p, $field, "attributi");
}

function avfield($p, $field)
{
	return genericField($p, $field, "attributi_valori");
}

function rfield($p, $field)
{
	if (isset($p["contenuti_tradotti"][$field]) and strcmp($p["contenuti_tradotti"][$field],"") !== 0)
	{
		return $p["contenuti_tradotti"][$field];
	}
	else
	{
		if (isset($p["ruoli"][$field]))
			return $p["ruoli"][$field];
	}
	
	return "";
}

function tcarfield($p, $field)
{
	return genericField($p, $field, "tipologie_caratteristiche", "tipologie_caratteristiche_tradotte");
}

function carfield($p, $field)
{
	return genericField($p, $field, "caratteristiche", "caratteristiche_tradotte");
}

function carvfield($p, $field)
{
	return genericField($p, $field, "caratteristiche_valori", "caratteristiche_valori_tradotte");
}

function persfield($p, $field)
{
	return genericField($p, $field, "personalizzazioni");
}

function tagfield($p, $field)
{
	return genericField($p, $field, "tag");
}

function fpfield($p, $field)
{
	return genericField($p, $field, "fasce_prezzo");
}

function pfield($p, $field)
{
	return genericField($p, $field, "pagamenti");
}

function sofield($p, $field)
{
	return genericField($p, $field, "stati_ordine");
}


function genericField($p, $field, $table, $tableTradotta = "contenuti_tradotti")
{
	if (isset($p[$tableTradotta][$field]) and strcmp($p[$tableTradotta][$field],"") !== 0)
		return $p[$tableTradotta][$field];
	else
	{
		if (isset($p[$table][$field]))
			return $p[$table][$field];
	}
	
	return "";
}

function fullcategory($idC)
{
	$c = new CategoriesModel();
	
	$res = $c->clear()->select("categories.*, contenuti_tradotti_categoria.*")
		->addJoinTraduzioneCategoria()
// 		->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")
		->where(array(
			"id_c"	=>	(int)$idC,
		))->send();
	
	if (count($res) > 0)
		return $res[0];
	
	return null;
}

function is($groups)
{
	$groupsArray = explode(",",$groups);
	
	foreach ($groupsArray as $group)
	{
		if (in_array($group,User::$groups))
		{
			return true;
		}
	}
	
	return false;
}

function m($string)
{
	return attivaModuli($string, null);
}

function attivaModuli($string, $obj = null)
{
	$string = preg_replace_callback('/(\[baseUrlSrc\])/', 'getBaseUrlSrc' ,$string);
	$string = preg_replace_callback('/(\[baseUrl\])/', 'getBaseUrl' ,$string);
	$string = preg_replace_callback('/\[themeFolder\]/', 'getThemeFolder' ,$string);
// 	$string = preg_replace_callback('/\[(testo\=)([0-9]{1,})\]/', 'getTesto' ,$string);
	
	$string = preg_replace_callback('/\[testoaw (.*?) attributi (.*?) wrap (.*?)\]/', 'getTesto' ,$string);
	$string = preg_replace_callback('/\[testoa (.*?) attributi (.*?)\]/', 'getTesto' ,$string);
	
	$string = preg_replace_callback('/\[testo (.*?)\]/', 'getTesto' ,$string);
	
	$string = preg_replace_callback('/\[immagine (.*?) attributi (.*?)\]/', 'getImmagine' ,$string);
	$string = preg_replace_callback('/\[link (.*?) attributi (.*?)\]/', 'getLink' ,$string);
	
	$string = preg_replace_callback('/\[immagine (.*?)\]/', 'getImmagine' ,$string);
	$string = preg_replace_callback('/\[link (.*?)\]/', 'getLink' ,$string);
	$string = preg_replace_callback('/\[video (.*?)\]/', 'getVideo' ,$string);
	$string = preg_replace_callback('/\[variabile (.*?)\]/', 'getVariabile' ,$string);
	$string = preg_replace_callback('/\[scelta-cookie\]/', array("PagesModel", "loadTemplateSceltaCookie"), $string);
	
	$string = preg_replace_callback('/\[INFO_ELIMINAZIONE\]/', 'getInfoEliminazione' ,$string);
	$string = preg_replace_callback('/\[INFO_ELIMINAZIONE_APPROVAZIONE\]/', 'getInfoEliminazioneApprovazione' ,$string);
	
	$string = preg_replace_callback('/\[LCAT_([0-9]{1,})_([0-9]{1,})(_(.*?))?\]/', 'getLinkCategoria' ,$string);
	$string = preg_replace_callback('/\[LPAG_([0-9]{1,})?\]/', 'getLinkPagina' ,$string);
	
	$string = preg_replace_callback('/\[ELENCO_COOKIE\]/', array("CookiearchivioModel", "elencoCookie") ,$string);
	
	$string = preg_replace('/\[anno-corrente\]/', date("Y") ,$string);
	
	if (!isset(VariabiliModel::$placeholders))
		VariabiliModel::setPlaceholders();
	
	foreach (VariabiliModel::$placeholders as $key => $value)
	{
		if ($value)
			$string = str_replace('['.$key.']', $value ,$string);
	}
	
	if ($obj)
	{
		$string = preg_replace_callback('/\[slide\]/', array($obj,'getSlide') ,$string);
		$string = preg_replace_callback('/\[slide-in-pagina\]/', array($obj,'getSlideInPagina') ,$string);
		$string = preg_replace_callback('/\[prodotti\]/', array($obj,'getProdotti') ,$string);
		$string = preg_replace_callback('/\[prodotti-in-evidenza\]/', array($obj,'getProdottiInEvidenza') ,$string);
		$string = preg_replace_callback('/\[slide_prodotto\]/', array($obj,'getSlideProdotto') ,$string);
		$string = preg_replace_callback('/\[carrello_prodotto\]/', array($obj,'getCarrelloProdotto') ,$string);
		$string = preg_replace_callback('/\[news-in-evidenza\]/', array($obj,'getNewsInEvidenza') ,$string);
		$string = preg_replace_callback('/\[team\]/', array($obj,'getTeam') ,$string);
		$string = preg_replace_callback('/\[categorie-carosello\]/', array($obj,'getCaroselloCategorie') ,$string);
		$string = preg_replace_callback('/\[fascia-categorie\]/', array($obj,'getCategorieFascia') ,$string);
		$string = preg_replace_callback('/\[testimonial\]/', array($obj,'getTestimonial') ,$string);
		$string = preg_replace_callback('/\[fascia-newsletter\]/', array($obj,'getFasciaNewsletter') ,$string);
		$string = preg_replace_callback('/\[fascia-faq\]/', array($obj,'getFaqInEvidenza') ,$string);
		$string = preg_replace_callback('/\[gallery\]/', array($obj,'getGalleryFascia') ,$string);
		$string = preg_replace_callback('/\[eventi\]/', array($obj,'getEventiFascia') ,$string);
		$string = preg_replace_callback('/\[form-contatti\]/', array($obj,'getFasciaFormContatti') ,$string);
		$string = preg_replace_callback('/\[marchi\]/', array($obj,'getFasciaMarchi') ,$string);
		$string = preg_replace_callback('/\[marchi-nuovi\]/', array($obj,'getFasciaMarchiNuovi') ,$string);
		$string = preg_replace_callback('/\[info-spedizioni\]/', array($obj,'getFasciaInfoSpedizioni') ,$string);
		$string = preg_replace_callback('/\[prodotti-in-promozione\]/', array($obj,'getFasciaProdottiInPromozione') ,$string);
		$string = preg_replace_callback('/\[carosello-marchi\]/', array($obj,'getFasciaCaroselloMarchi') ,$string);
		$string = preg_replace_callback('/\[chi-siamo\]/', array($obj,'getFasciaChiSiamo') ,$string);
		$string = preg_replace_callback('/\[pacco-regalo\]/', array($obj,'getFasciaPaccoRegalo') ,$string);
		$string = preg_replace_callback('/\[tag\]/', array($obj,'getFasciaTag') ,$string);
		$string = preg_replace_callback('/\[info-pagamenti\]/', array($obj,'getFasciaInfoPagamenti') ,$string);
		$string = preg_replace_callback('/\[servizi\]/', array($obj,'getFasciaServizi') ,$string);
		$string = preg_replace_callback('/\[info-spedizioni-resi\]/', array($obj,'getFasciaInfoSpedizioniResi') ,$string);
		$string = preg_replace_callback('/\[form-feedback\]/', array($obj,'getFasciaFormFeedback') ,$string);
		$string = preg_replace_callback('/\[sedi\]/', array($obj,'getFasciaSedi') ,$string);
		$string = preg_replace_callback('/\[progetti\]/', array($obj,'getFasciaProgetti') ,$string);
		$string = preg_replace_callback('/\[liste-regalo\]/', array($obj,'getFasciaListeRegalo') ,$string);
		$string = preg_replace_callback('/\[gift-card\]/', array($obj,'getFasciaGiftCard') ,$string);
		$string = preg_replace_callback('/\[prodotti_in_pagina\]/', array($obj,'getProdottiInPagina') ,$string);
		$string = preg_replace_callback('/\[partner\]/', array($obj,'getFasciaPartner') ,$string);
		$string = preg_replace_callback('/\[agenti\]/', array($obj,'getFasciaAgenti') ,$string);
		$string = preg_replace_callback('/\[elenco_documenti\]/', array($obj,'getFasciaDocumenti') ,$string);
		$string = preg_replace_callback('/\[storia\]/', array($obj,'getFasciaStoria') ,$string);
		
		if (defined("FASCE_TAGS"))
		{
			foreach (FASCE_TAGS as $reg => $metodo)
			{
				if (method_exists($obj,$metodo))
					$string = preg_replace_callback('/\['.$reg.'\]/', array($obj,$metodo) ,$string);
			}
		}
	}
	
	return $string;
}

function getInfoEliminazioneApprovazione($matches)
{
	if (isset($_GET[v("variabile_token_eliminazione")]) && trim($_GET[v("variabile_token_eliminazione")]))
	{
		$iModel = new IntegrazioniloginModel();
		
		$app = $iModel->clear()->where(array(
			"confirmation_code"	=>	sanitizeAll($_GET[v("variabile_token_eliminazione")]),
		))->sWhere("confirmation_code != ''")->record();
		
		if (!empty($app))
		{
			ob_start();
			include tpf("Elementi/Utenti/info_eliminazione_approvazione_app.php");
			$output = ob_get_clean();
			
			return $output;
		}
	}
}

function getInfoEliminazione($matches)
{
	if (isset($_GET[v("variabile_token_eliminazione")]) && trim($_GET[v("variabile_token_eliminazione")]))
	{
		$ru = new RegusersModel();
		
		$cliente = $ru->clear()->where(array(
			"deleted"	=>	"yes",
			"token_eliminazione"	=>	sanitizeAll($_GET[v("variabile_token_eliminazione")]),
		))->record();
		
		if (!empty($cliente))
		{
			ob_start();
			include tpf("Elementi/Utenti/info_eliminazione_account.php");
			$output = ob_get_clean();
			
			return $output;
		}
	}
	
	return "";
}

function getBaseUrlSrc($matches)
{
	return rtrim(Url::getFileRoot(),"/");
}

function getThemeFolder($matches)
{
	return v("theme_folder");
}

function getBaseUrl($matches)
{
	return rtrim(Url::getRoot(),"/");
}

function getImmagine($matches, $tags = null, $tipo = "TESTO")
{
	return getTesto($matches, $tags, "IMMAGINE");
}

function getLink($matches, $tags = null, $tipo = "TESTO")
{
	return getTesto($matches, $tags, "LINK");
}

function getVariabile($matches)
{
	$chiave = $matches[1];
	
	if (in_array($chiave, explode(",",v("variabili_gestibili_da_fasce"))))
		return v($chiave);
}

function getLinkPagina($matches)
{
	$idP = (int)$matches[1];

	$pModel = new PagesModel();

	$record = $pModel->clear()
		->addJoinTraduzione(null, "contenuti_tradotti", false)
		->select("pages.title, contenuti_tradotti.title")
		->where(array(
			"pages.id_page"	=>	(int)$idP,
		))->first();

	if (!empty($record))
	{
		$target = App::$isFrontend ? "" : "target='_blank'";

		$urlAlias = getUrlAlias((int)$idP);

		return "<a $target href='".F::getUrlPubblico().$urlAlias."'>".field($record, "title")."</a>";
	}

	return "";
}

function getLinkCategoria($matches)
{
	$idC = (int)$matches[1];
	$idMarchio = (int)$matches[2];

	$urlAlias = CategoriesModel::getUrlAliasTagMarchio(0, $idMarchio, $idC);

	if ($urlAlias)
	{
		$target = App::$isFrontend ? "" : "target='_blank'";

		if (isset($matches[4]))
			return "<a $target href='".F::getUrlPubblico().$urlAlias."'>".sanitizeAll($matches[4])."</a>";
		else if ($idMarchio)
		{
			$marchio = MarchiModel::getDataMarchio($idMarchio);

			if (!empty($marchio))
				return "<a $target href='".F::getUrlPubblico().$urlAlias."'>".$marchio["marchi"]["titolo"]."</a>";
		}
		else if ($idC)
		{
			$categoria = CategoriesModel::getDataCategoria($idC);

			if (!empty($categoria))
				return "<a $target href='".F::getUrlPubblico().$urlAlias."'>".cfield($categoria, "title")."</a>";
		}
	}

	return "";
}

function getVideo($matches, $tags = null, $tipo = "TESTO")
{
	return getTesto($matches, $tags, "VIDEO");
}

function getTesto($matches, $tags = null, $tipo = "TESTO", $cleanFlush = true, $ritornaElemento = false)
{
	$clean["chiave"] = sanitizeDb($matches[1]);
	
	$t = new TestiModel();
	
	if ($tipo == "TESTO" || $tipo == "LINK")
		$lingua = sanitizeAll(getLinguaIso());
	else
		$lingua = LingueModel::getPrincipale();
	
	$testo = $t->clear()->where(array(
		"chiave"=>$clean["chiave"],
		"lingua"	=>	$lingua,
	))->record();
	
// 	echo $t->getQuery()."<br />";
	
	if (count($testo) > 0)
	{
		if ($ritornaElemento)
			return $testo;
		
		$clean["id"] = (int)$testo["id_t"];
		
		$iconaEdit = User::$adminLogged ? "<span rel='".$clean["id"]."' title='modifica il testo' class='edit_blocco_testo' href='#'><i class='fa fa-pencil'></i></span>" : null;
		
		$tags = nullToBlank($tags);
		
		$t = strcmp($tags,"") !== 0 ? strip_tags(htmlentitydecode($testo["valore"]),$tags) : htmlentitydecode($testo["valore"]);
		
		$alt = $testo["alt"] ? 'alt="'.$testo["alt"].'"' : "";
		
		if ($testo["immagine"])
			$t .= "<img src='".Domain::$publicUrl."/thumb/widget/".$testo["id_t"]."/".$testo["immagine"]."' $alt/>";
		
		$urlLink = $target = "";
		
		if ($testo["id_contenuto"] || $testo["id_categoria"] || $testo["link_id_documento"] || $testo["url_link"])
		{
			$target = "";
			
			if ($testo["target_link"] == "ESTERNO")
				$target = "target='_blank'";
			
			if ($testo["id_contenuto"])
				$urlLink = Url::getRoot().getUrlAlias($testo["id_contenuto"], sanitizeAll(getLinguaIso()));
			else if ($testo["id_categoria"])
				$urlLink = Url::getRoot().getCategoryUrlAlias($testo["id_categoria"], sanitizeAll(getLinguaIso()));
			else if ($testo["link_id_documento"])
				$urlLink = Url::getRoot().DocumentiModel::getUrlAlias($testo["link_id_documento"]);
			else
				$urlLink = $testo["url_link"];
			
			if ($testo["testo_link"])
				$t = $testo["testo_link"];
			
// 			$t .= "<a $target class='link_testi' href='".$urlLink."'>".$testo["testo_link"]."</a>";
		}
		
		if ($urlLink)
		{
			$t = "<a $target class='link_testi' href='".$urlLink."'>".$t."</a>";
		}
		
		if ($testo["tag_elemento"])
			$t = "<".$testo["tag_elemento"]." ".htmlentitydecode($testo["attributi"]).">$t</".$testo["tag_elemento"].">";
		
		$path = tpf("Contenuti/Elementi/Widget/".strtolower($tipo).".php");
		
		if ($testo["template"])
			$path = tpf("Contenuti/Elementi/Widget/".ucfirst(strtolower($tipo))."/".$testo["template"]);
		
		if (file_exists($path))
		{
			ob_start();
			include $path;
			if ($cleanFlush)
				$t = ob_get_clean();
			else
				$t = ob_get_flush();
		}
		
		if (User::$adminLogged && TestiModel::$mostraIconaEdit)
		{
			$iconaEditTag = "";
			
			// Edit tag da frontend
			if (v("permetti_di_aggiungere_blocchi_da_frontend") && isset($matches[0]) && $matches[0] && ContenutiModel::$idContenuto)
			{
				$pathContext = tpf("Elementi/Admin/context_edit.php");
				ob_start();
				include $pathContext;
				$output = ob_get_clean();
				$iconaEditTag .= $output;
			}
			
			return "<".v("tag_blocco_testo")." class='blocco_testo'>".$t.$iconaEdit.$iconaEditTag."</".v("tag_blocco_testo").">";
		}
		else
		{
			return $t;
		}
	}
	else
	{
		$testoPrincipale = $t->clear()->where(array(
			"chiave"=>$clean["chiave"],
			"lingua"	=>	LingueModel::getPrincipale(),
		))->record();
		
		if (!empty($testoPrincipale))
		{
			$t->setValues($testoPrincipale, "sanitizeDb");
			
			unset($t->values["id_t"]);
		}
		else
		{
			if ($tipo == "IMMAGINE" && file_exists(LIBRARY."/Frontend/Public/Img/nofound.jpeg"))
				$t->values = array(
					"valore"	=>	sanitizeDb("<img width='200px' src='".Url::getFileRoot()."admin/Frontend/Public/Img/nofound.jpeg' />"),
				);
			else
				$t->values = array(
					"valore"	=>	$clean["chiave"],
				);
			
			if (ContenutiModel::$idContenuto)
				$t->values["id_cont"] = ContenutiModel::$idContenuto;
		}
		
		$t->values["chiave"] = $clean["chiave"];
		$t->values["lingua"] = sanitizeDb($lingua);
		$t->values["tipo"] = sanitizeDb($tipo);
		
		if (isset($matches[2]) && $matches[2])
			$t->values["attributi"] = sanitizeAll($matches[2]);
		
		if (isset($matches[3]) && $matches[3])
			$t->values["tag_elemento"] = sanitizeAll($matches[3]);
		
		if ($t->insert())
		{
			unset(Cache_Db::$cachedTables[array_search("testi", Cache_Db::$cachedTables)]);
			return getTesto($matches, $tags, $tipo, $cleanFlush);
		}
	}
	
	return "";
}

//chiama la traduzione di un blocco di testo
function testo($chiave, $tags = null)
{
	return getTesto(array("",$chiave),$tags);
}

//chiama la traduzione di un blocco di testo
function t($chiave, $tags = null, $attributi = null, $wrap = null)
{
	return getTesto(array("",$chiave, $attributi, $wrap),$tags);
}

//chiama la traduzione di un blocco immagine
function i($chiave, $tags = null, $attributi = null, $cleanFlush = true, $ritornaElemento = false)
{
	return getTesto(array("",$chiave, $attributi),$tags, "IMMAGINE", $cleanFlush, $ritornaElemento);
}

//chiama la traduzione di un blocco immagine
function l($chiave, $tags = null, $attributi = null)
{
	return getTesto(array("",$chiave, $attributi),$tags, "LINK");
}

function checkHttp($string)
{
// 	$protocol = Params::$useHttps ? "https" : "http";
	
	if (!stristr($string,"https://") and !stristr($string,"http://"))
	{
		if (Params::$useHttps)
			return "https://".$string;
		else
			return "http://".$string;
	}
	
	return $string;
}

function partial()
{
	if (isset($_GET["partial"]) and strcmp($_GET["partial"],"Y") === 0)
	{
		return true;
	}
	
	return false;
}

function nobuttons()
{
	if (isset($_GET["nobuttons"]) and strcmp($_GET["nobuttons"],"Y") === 0)
	{
		return true;
	}
	
	return false;
}

function nofiltri()
{
	if (isset($_GET["nofiltri"]) and strcmp($_GET["nofiltri"],"Y") === 0)
	{
		return true;
	}
	
	return false;
}

function showreport()
{
	if (isset($_GET["report"]) and strcmp($_GET["report"],"Y") === 0)
	{
		return true;
	}
	
	return false;
}

function skipIfEmpty()
{
	if (isset($_GET["skip"]) and strcmp($_GET["skip"],"Y") === 0)
	{
		return true;
	}
	
	return false;
}

function br2nl($string)
{
    return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
}

function br2dot($string)
{
    return preg_replace('/\<br(\s*)?\/?\>/i', ". ", $string);
}

function br2space($string)
{
    return preg_replace('/\<br(\s*)?\/?\>/i', " ", $string);
}

function getActive($tm, $section)
{
	return isset($tm[$section][0]) ? $tm[$section][0] : null;
}

function calcolaPrezzoSconto($prezzo, $sconto)
{
	return ($prezzo - (($prezzo * $sconto) /100));
}

function getLayers($idPage)
{
	$l = new LayerModel();
	
	return $l->clear()->where(array(
		"id_page" => (int)$idPage,
	))->orderBy("id_order")->send(false);
}

class Form
{
	static public $notice;
	static public $values;
	static public $tipo = "C"; // C: contatti, N: newsletter
	static public $valuesTipo = array();
	static public $valuesNotice = array();
	static public $fields = array(
		"C"	=>	"",
		"N"	=>	"",
	);
	static public $defaultValues = array();
	
	static public $tipi = array();
	
	static public function gTipi()
	{
		self::$tipi = array_keys(self::$fields);
	}
	
	static public function sValues($tipo, $values)
	{
		self::$values = $values;
		
		self::gTipi();
		
		foreach (self::$tipi as $t)
		{
			if ($t == $tipo)
				self::$valuesTipo[$t] = $values;
			else
			{
				$ae = new ArrayExt();
				
				self::$valuesTipo[$t] = $ae->subset(array(), self::$fields[$t]);
			}
		}
	}
	
	static public function sNotice($tipo, $notice)
	{
// 		if (!isset($notice))
// 			$notice = flash("notice_$tipo");
		
		self::$notice = $notice;
		
		self::gTipi();
		
		foreach (self::$tipi as $t)
		{
			if ($t == $tipo)
				self::$valuesNotice[$t] = $notice;
			else
				self::$valuesNotice[$t] = "";
		}
	}
	
	static public function gNotice()
	{
		if (isset(self::$valuesNotice[self::$tipo]))
			return self::$valuesNotice[self::$tipo];
		
		if (isset(self::$notice))
			return self::$notice;
		
		return "";
	}
	
	static public function gValue($key)
	{
		if (isset(self::$valuesTipo[self::$tipo][$key]))
			return self::$valuesTipo[self::$tipo][$key];
		
		if (isset(self::$values[$key]))
			return self::$values[$key];
		
		return "";
	}
}

function creaFormContatti($matches = "")
{
	ob_start();
	$tipoOutput = "mail_al_negozio";
	include tp()."/form-contatti.php";
	$output = ob_get_clean();
	
	return $output;
}

function pulsanteFattura($id_o)
{
	$clean["id_o"] = (int)$id_o;
	
	$fatt = new FattureModel();
	
	$res = $fatt->clear()->where(array("id_o"=>$clean["id_o"]))->send();
	
	if (count($res) > 0)
	{
		return "<a download title='scarica fattura' href='".Url::getRoot()."ordini/scaricafattura/".$clean["id_o"]."'><img src='".Url::getFileRoot()."admin/Public/Img/Icons/pdf.png' /></a>";
	}
}

function nomeNazione($codice)
{
	$n = new NazioniModel();
	
	return htmlentitydecode($n->where(array("iso_country_code"=>sanitizeDb($codice)))->field("titolo"));
}

function nomeProvincia($codice)
{
	$p = new ProvinceModel();
	
	return htmlentitydecode($p->where(array("codice_provincia"=>sanitizeDb($codice)))->field("provincia"));
}

function calcolaPrezzoFinale($idPage, $prezzoIntero, $checkPromo = true, $forzaNonIvato = false, $idC = 0)
{
	$p = new PagesModel();
	
	if (!$idC)
		$idC = PagesModel::$IdCombinazione ? PagesModel::$IdCombinazione : $p->getIdCombinazioneCanonical((int)$idPage);
	
	$c = new CartModel();
	$prezzoFinale = $c->calcolaPrezzoFinale($idPage, $prezzoIntero, 1, $checkPromo, true, $idC);
	
	if (ImpostazioniModel::$valori["esponi_prezzi_ivati"] == "Y" && !$forzaNonIvato)
	{
// 		$p = new PagesModel();
		$iva = $p->getIva($idPage);
		$prezzoFinaleNonIvato = $prezzoFinale;
		$prezzoFinale = $prezzoFinale + ($prezzoFinale * (float)$iva / 100);
		
		if (isset(IvaModel::$aliquotaEstera) && v("scorpora_iva_prezzo_estero") && v("mostra_prezzi_con_aliquota_estera"))
			list($prezzoFinale, $prezzoFinaleNonIvato) = IvaModel::ricalcolaPrezzo($prezzoFinale, $prezzoFinaleNonIvato, $iva, IvaModel::$aliquotaEstera);
	}
	
	return $prezzoFinale;
}

function getPercSconto($pieno, $scontato)
{
	if ($pieno > 0)
		return (($pieno - $scontato) / $pieno) * 100;
	
	return 0;
}

function getPercScontoF($pieno, $scontato)
{
	$sconto = getPercSconto($pieno, $scontato);
	
	if (round($sconto,1) > floor($sconto))
		return number_format($sconto,1,",","");
	
	return number_format($sconto,0,",","");
}

function calcolaPrezzoIvato($idPage, $prezzoIntero, $iva = null)
{
	if (ImpostazioniModel::$valori["esponi_prezzi_ivati"] == "Y")
	{
		if (!isset($iva))
		{
			$p = new PagesModel();
			$iva = $p->getIva($idPage);
		}
		
		$prezzoInteroNonIvato = $prezzoIntero;
		$prezzoIntero = $prezzoIntero + ($prezzoIntero * (float)$iva / 100);
		
		if (isset(IvaModel::$aliquotaEstera) && v("scorpora_iva_prezzo_estero") && v("mostra_prezzi_con_aliquota_estera"))
			list($prezzoIntero, $prezzoInteroNonIvato) = IvaModel::ricalcolaPrezzo($prezzoIntero, $prezzoInteroNonIvato, $iva, IvaModel::$aliquotaEstera);
	}
	
	return $prezzoIntero;
}

function syncMailchimp($data)
{
    $apiKey = ImpostazioniModel::$valori["mailchimp_api_key"];
    $listId = ImpostazioniModel::$valori["mailchimp_list_id"];

	return syncMailchimpKeys($data, $apiKey, $listId);
}

function syncMailchimpKeys($data, $apiKey, $listId)
{
    $memberId = md5(strtolower($data['email']));
    $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;
	
	$mergeFields = [
		'FNAME'     => isset($data['firstname']) ? $data['firstname'] : "",
		'LNAME'     => isset($data['lastname']) ? $data['lastname'] : "",
	];
	
	if (isset($data["mergeFields"]))
		$mergeFields = $data["mergeFields"];
	
    $json = json_encode([
        'email_address' => $data['email'],
        'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
        'merge_fields'  => $mergeFields,
    ]);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                                                                 
	
	if (v("curl_curlopt_interface"))
		curl_setopt($ch, CURLOPT_INTERFACE, v("curl_curlopt_interface"));
	
    $result = curl_exec($ch);
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
	
    return $httpCode;
}

/**
 * Function to create and display error and success messages
 * @access public
 * @param string session name
 * @param string message
 * @param string display class
 * @return string message
 */
function flash( $name = '', $message = '', $class = 'success fadeout-message' )
{
// 	print_r($_SESSION);
    //We can only do something if the name isn't empty
    if( !empty( $name ) )
    {
        //No message, create it
        if( !empty( $message ) && empty( $_SESSION[$name] ) )
        {
            if( !empty( $_SESSION[$name] ) )
            {
                unset( $_SESSION[$name] );
            }
            if( !empty( $_SESSION[$name.'_class'] ) )
            {
                unset( $_SESSION[$name.'_class'] );
            }

            $_SESSION[$name] = $message;
            $_SESSION[$name.'_class'] = $class;
        }
        //Message exists, display it
        elseif( !empty( $_SESSION[$name] ) && empty( $message ) )
        {
            $class = !empty( $_SESSION[$name.'_class'] ) ? $_SESSION[$name.'_class'] : 'success';
            $notice = $_SESSION[$name];
            unset($_SESSION[$name]);
            unset($_SESSION[$name.'_class']);
            return $notice;
        }
        
        return null;
    }
}

function prodottiMarchio($idMarchio)
{
	$p = new PagesModel();
	
	return $p->clear()->where(array(
		"id_marchio"	=>	(int)$idMarchio,
	))->rowNumber();
}

function traduci($string, $forzaEsteso = false)
{
	if (!$forzaEsteso)
	{
		$string = preg_replace('/(Jan)/', 'Gen',$string);
		$string = preg_replace('/(Feb)/', 'Feb',$string);
		$string = preg_replace('/(Mar)/', 'Mar',$string);
		$string = preg_replace('/(Apr)/', 'Apr',$string);
		$string = preg_replace('/(May)/', 'Mag',$string);
		$string = preg_replace('/(Jun)/', 'Giu',$string);
		$string = preg_replace('/(Jul)/', 'Lug',$string);
		$string = preg_replace('/(Aug)/', 'Ago',$string);
		$string = preg_replace('/(Sep)/', 'Set',$string);
		$string = preg_replace('/(Oct)/', 'Ott',$string);
		$string = preg_replace('/(Nov)/', 'Nov',$string);
		$string = preg_replace('/(Dec)/', 'Dic',$string);
	}
	
	$string = preg_replace('/(January)/', 'Gennaio',$string);
	$string = preg_replace('/(Genuary)/', 'Gennaio',$string);
	$string = preg_replace('/(February)/', 'Febbraio',$string);
	$string = preg_replace('/(March)/', 'Marzo',$string);
	$string = preg_replace('/(April)/', 'Aprile',$string);
	$string = preg_replace('/(May)/', 'Maggio',$string);
	$string = preg_replace('/(June)/', 'Giugno',$string);
	$string = preg_replace('/(July)/', 'Luglio',$string);
	$string = preg_replace('/(August)/', 'Agosto',$string);
	$string = preg_replace('/(September)/', 'Settembre',$string);
	$string = preg_replace('/(Settember)/', 'Settembre',$string);
	$string = preg_replace('/(October)/', 'Ottobre',$string);
	$string = preg_replace('/(Ottober)/', 'Ottobre',$string);
	$string = preg_replace('/(November)/', 'Novembre',$string);
	$string = preg_replace('/(December)/', 'Dicembre',$string);
	$string = preg_replace('/(Dicember)/', 'Dicembre',$string);

	$string = preg_replace('/(Friday)/', 'Venerdì',$string);
	$string = preg_replace('/(Saturday)/', 'Sabato',$string);
	$string = preg_replace('/(Sunday)/', 'Domenica',$string);
	$string = preg_replace('/(Monday)/', 'Lunedì',$string);
	$string = preg_replace('/(Tuesday)/', 'Martedì',$string);
	$string = preg_replace('/(Wednesday)/', 'Mercoledì',$string);
	$string = preg_replace('/(Thursday)/', 'Giovedì',$string);

	return $string;
}

function controllaPIVA($variabile){

	if($variabile=='')
		return false;

	//la p.iva deve essere lunga 11 caratteri
	if(strlen($variabile)!=11)
		return false;

	//la p.iva deve avere solo cifre
	if(!preg_match("/^[0-9]+$/", $variabile))
		return false;

	$primo=0;
	for($i=0; $i<=9; $i+=2)
			$primo+= ord($variabile[$i])-ord('0');

	for($i=1; $i<=9; $i+=2 ){
		$secondo=2*( ord($variabile[$i])-ord('0') );

		if($secondo>9)
			$secondo=$secondo-9;
		$primo+=$secondo;

	}
	if( (10-$primo%10)%10 != ord($variabile[10])-ord('0') )
		return false;

	return true;

}

function codiceFiscale($cf){
	if($cf=='')
		return false;

	if(strlen($cf)!= 16)
		return false;

	$cf=strtoupper($cf);
	if(!preg_match("/[A-Z0-9]+$/", $cf))
		return false;
	$s = 0;
	for($i=1; $i<=13; $i+=2){
		$c=$cf[$i];
		if('0'<=$c and $c<='9')
			$s+=ord($c)-ord('0');
		else
			$s+=ord($c)-ord('A');
	}

	for($i=0; $i<=14; $i+=2){
		$c=$cf[$i];
		switch($c){
			case '0':  $s += 1;  break;
			case '1':  $s += 0;  break;
			case '2':  $s += 5;  break;
			case '3':  $s += 7;  break;
			case '4':  $s += 9;  break;
			case '5':  $s += 13;  break;
			case '6':  $s += 15;  break;
			case '7':  $s += 17;  break;
			case '8':  $s += 19;  break;
			case '9':  $s += 21;  break;
			case 'A':  $s += 1;  break;
			case 'B':  $s += 0;  break;
			case 'C':  $s += 5;  break;
			case 'D':  $s += 7;  break;
			case 'E':  $s += 9;  break;
			case 'F':  $s += 13;  break;
			case 'G':  $s += 15;  break;
			case 'H':  $s += 17;  break;
			case 'I':  $s += 19;  break;
			case 'J':  $s += 21;  break;
			case 'K':  $s += 2;  break;
			case 'L':  $s += 4;  break;
			case 'M':  $s += 18;  break;
			case 'N':  $s += 20;  break;
			case 'O':  $s += 11;  break;
			case 'P':  $s += 3;  break;
			case 'Q':  $s += 6;  break;
			case 'R':  $s += 8;  break;
			case 'S':  $s += 12;  break;
			case 'T':  $s += 14;  break;
			case 'U':  $s += 16;  break;
			case 'V':  $s += 10;  break;
			case 'W':  $s += 22;  break;
			case 'X':  $s += 25;  break;
			case 'Y':  $s += 24;  break;
			case 'Z':  $s += 23;  break;
		}
	}

	if( chr($s%26+ord('A'))!=$cf[15] )
		return false;

	return true;
}

function v($chiave)
{
	return VariabiliModel::valore($chiave);
}

function vg($chiave, $valore = null)
{
	if (isset($valore))
		VariabiliModel::setValoreGlobaleTemporaneo($chiave, $valore);
	else
		return VariabiliModel::getValoreGlobaleTemporaneo($chiave);
}

function getIsoDate($date)
{
	$date = trim($date);
	
	$dateObj = null;

	if (preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/',$date))
	{
		return $date;
	}
	else if (preg_match('/^[0-9]{2}\-[0-9]{2}\-[0-9]{4}$/',$date))
	{
		$dateObj = DateTime::createFromFormat("d-m-Y", $date);
	}
	else if (preg_match('/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/',$date))
	{
		$dateObj = DateTime::createFromFormat("d/m/Y", $date);
	}
	else if (preg_match('/^[0-9]{2}\_[0-9]{2}\_[0-9]{4}$/',$date))
	{
		$dateObj = DateTime::createFromFormat("d_m_Y", $date);
	}

	if ($dateObj)
		return $dateObj->format("Y-m-d");

	return $date;
}

function selectAttributi($id_page)
{
	$p = new PagesModel();
	
	return $p->selectAttributi($id_page);
}

function selectPersonalizzazioni($id_page)
{
	$p = new PagesModel();
	
	return $p->selectPersonalizzazioni($id_page);
}

function numeroProdottiCategoria($id_c, $filtriSuccessivi = false)
{
	$c = new CategoriesModel();
	
	return $c->numeroProdotti($id_c, $filtriSuccessivi);
}

function numeroProdottiCategoriaFull($id_c, $filtriSuccessivi = false)
{
	$c = new CategoriesModel();
	
// 	$signature = isset(CategoriesModel::$arrayIdsPagineFiltrate["[categoria]"]) ? md5(implode(",",CategoriesModel::$arrayIdsPagineFiltrate["[categoria]"])) : "";
	
	$signature = CategoriesModel::getSignatureSuccessivi("[categoria]");
	
	return Cache_Functions::getInstance()->load(new CategoriesModel())->numeroProdottiFull($id_c, $filtriSuccessivi, $signature);
// 	return $c->numeroProdottiFull($id_c, $filtriSuccessivi);
}

function getShopCategoryId()
{
	$c = new CategoriesModel();
	
	return $c->getShopCategoryId();
}

function categorieFiglie($id_c, $select = "categories.*,contenuti_tradotti_categoria.*", $soloAttivi = true, $traduzione = true)
{
// 	$c = new CategoriesModel();
	
	return Cache_Functions::getInstance()->load(new CategoriesModel())->categorieFiglie($id_c, $select, $soloAttivi, $traduzione);
	
// 	return $c->categorieFiglie($id_c, $select, $soloAttivi, $traduzione);
}

function isImmediateChild($idCat, $idParent)
{
	$c = new CategoriesModel();
	
	return $c->isImmediateChild($idCat, $idParent);
}

function isChild($idCat, $idParent)
{
	$c = new CategoriesModel();
	
	return $c->isChild($idCat, $idParent);
}

function getAttributoDaCarrello($col, $idAcc = null)
{
	if (isset($_GET["id_cart"]))
	{
		$c = new CartModel();
		
		return $c->getAttributoDaCarrello((int)$_GET["id_cart"], $col, $idAcc);
	}
	else if (PagesModel::$IdCombinazione && !$idAcc)
	{
		$c = new CombinazioniModel();
		
		$combinazione = $c->selectId((int)PagesModel::$IdCombinazione);
		
		if (isset($combinazione[$col]))
			return $combinazione[$col];
	}
	
	return "";
}

function getPersonalizzazioneDaCarrello($col, $idAcc = null)
{
	if (isset($_GET["id_cart"]))
	{
		$c = new CartModel();
		
		return $c->getAttributoDaCarrello((int)$_GET["id_cart"], $col, $idAcc, "json_personalizzazioni");
	}
	
	return "";
}

function accessorioInCarrello($idAcc)
{
	if (isset($_GET["id_cart"]))
	{
		$c = new CartModel();
		
		return $c->accessorioInCarrello((int)$_GET["id_cart"], $idAcc);
	}
	
	return false;
}

function idCarrelloEsistente()
{
	if (isset($_GET["id_cart"]))
	{
		$c = new CartModel();
		
		return $c->idCarrelloEsistente((int)$_GET["id_cart"]);
	}
	
	return false;
}

function getQtaDaCarrello()
{
	if (isset($_GET["id_cart"]))
	{
		$c = new CartModel();
		
		return $c->getQtaDaCarrello((int)$_GET["id_cart"]);
	}
	
	return 1;
}

function giacenzaPrincipale($id_page)
{
	$p = new PagesModel();
	
	return $p->giacenzaPrincipale($id_page);
}

function checkGiacenza($idCart, $qty)
{
	$c = new CartModel();
	
	return $c->checkQtaFinale($idCart, $qty);
}

function checkQtaCartFull()
{
	$c = new CartModel();
	
	return $c->checkQtaFull();
}

function acquistabile($id_page)
{
	$p = new PagesModel();
	
	return $p->acquistabile($id_page);
}

function tm($tm, $controller)
{
	if (!is_array($controller))
		$controller = array($controller);
	
	foreach ($controller as $c)
	{
		if (isset($tm[$c]))
			return "active";
	}
	
	return "";
}

function findTitoloDaCodice($codice, $default = null)
{
	$n = new NazioniModel();
	
	return $n->findTitoloDaCodice($codice, $default);
}

function hasActivePages($id_c)
{
	$c = new CategoriesModel();
	
	return $c->hasActivePages($id_c);
}

function hexToRbg($hex)
{
	$split = str_split(ltrim($hex, "#"), 2);
	$r = hexdec($split[0]);
	$g = hexdec($split[1]);
	$b = hexdec($split[2]);
	return array($r, $g, $b);
}

function prezzoMinimo($id_page)
{
	$p = new PagesModel();
	
	return $p->prezzoMinimo($id_page);
}

function p($c, $prezzo)
{
// 	IvaModel::getAliquotaEstera();
	
	if (v("prezzi_ivati_in_carrello"))
	{
// 		$prezzo = number_format($prezzo, v("cifre_decimali"), ".", "");
		
		if (isset(IvaModel::$aliquotaEstera))
			return $prezzo * (1 + (IvaModel::$aliquotaEstera / 100));
		else
			return $prezzo * (1 + ($c["iva"] / 100));
	}
	else
	{
		return $prezzo;
	}
}

function tp($admin = false)
{
	$themeFolder = v("theme_folder");
	
	if (!$admin)
		$subfolder = $themeFolder ? DS . $themeFolder : "";
	else
		$subfolder = DS . "_";
	
	return Domain::$parentRoot."/Application/Views$subfolder";
}

function tpf($filePath = "", $public = false, $cachable = true, $stringaCache = "", $cachedTemplateFile = false)
{
	return Tema::tpf($filePath, $public, $cachable, $stringaCache, $cachedTemplateFile);
	
// 	$cache = Cache_Html::getInstance();
// 	
// 	$themeFolder = v("theme_folder");
// 	
// 	$subfolder = $themeFolder ? DS . $themeFolder : "";
// 	
// 	$subFolderFullPath = Domain::$parentRoot."/Application/Views$subfolder"."/".ltrim($filePath,"/");
// 	$subFolderFullPathPublic = Domain::$publicUrl."/Application/Views$subfolder"."/".ltrim($filePath,"/");
// 	
// 	if (file_exists($subFolderFullPath))
// 		return $public ? $subFolderFullPathPublic : $cache->saveDynamic($subFolderFullPath, $cachable, $stringaCache, $cachedTemplateFile);
// 	
// 	if ($themeFolder)
// 	{
// 		$subFolderFullPathParentFrontend = Domain::$parentRoot."/Application/Views/_/".ltrim($filePath,"/");
// 		$subFolderFullPathParentFrontendPublic = Domain::$publicUrl."/Application/Views/_/".ltrim($filePath,"/");
// 		
// 		if (file_exists($subFolderFullPathParentFrontend))
// 			return $public ? $subFolderFullPathParentFrontendPublic : $cache->saveDynamic($subFolderFullPathParentFrontend, $cachable, $stringaCache, $cachedTemplateFile);
// 	}
// 	
// 	if ($public)
// 		return Domain::$publicUrl."/admin/Frontend/Application/Views/_/".ltrim($filePath,"/");
// 	else
// 		return $cache->saveDynamic(Domain::$parentRoot."/admin/Frontend/Application/Views/_/".ltrim($filePath,"/"), $cachable, $stringaCache, $cachedTemplateFile);
}

function singPlu($numero, $sing, $plu)
{
	if ($numero == 1)
		return $sing;
	else
		return $plu;
}

function aToX($struct, $key = "", $cdata = true, $caratteri = false)
{
	$xml = "";
	
	foreach ($struct as $kk => $value)
	{
		if (!is_numeric($kk))
		{
			if (is_array($value))
			{
				if (array_values($value) === $value)
					$xml .= aToX($value, $kk, $cdata, $caratteri);
				else
					$xml .= "<$kk>".aToX($value, $kk, $cdata, $caratteri)."</$kk>\n"; 
			}
			else
				$xml .= "\n\t<$kk>".cXmlC($value, $cdata, $caratteri)."</$kk>";
		}
		else
		{
			if (is_array($value))
				$xml .= "<$key>".aToX($value, "", $cdata, $caratteri)."</$key>\n";
			else
				$xml .= "\n\t<$key>".cXmlC($value, $cdata, $caratteri)."</$key>";
		}
	}
	
	return $xml;
}

function cXmlC($t, $cdata = false, $caratteri = false)
{
	if ($caratteri)
	{
		$t = str_replace("&","&amp;",$t);
		$t = str_replace(">","&gt;",$t);
		$t = str_replace("°","",$t);
		$t = str_replace("<","&lt;",$t);
		$t = str_replace("'","&#39;",$t);
		$t = str_replace('"',"&quot;",$t);
	}
	
	if ($cdata)
		$t = "<![CDATA[".$t."]]>";
	
	return $t;
}

function sanitizeJs($jsString)
{
	$result = strtr($jsString, array('\\' => '\\\\', "'" => "\\'", '"' => '\\"', "\r" => '\\r', "\n" => '\\n' ));
	
	return $result;
}

function parent($file, $admin = false)
{
	$file = str_replace(tp($admin),"",$file);
	
	$parentFrontend = Domain::$parentRoot."/Application/Views/_".$file;
	
	if (!$admin && file_exists($parentFrontend))
		return $parentFrontend;
	
	return Domain::$parentRoot."/admin/Frontend/Application/Views/_".$file;
}

function prodottoCartesiano($input) {
	$result = array(array());

	foreach ($input as $key => $values) {
		$append = array();

		foreach($result as $product) {
			foreach($values as $item) {
				$product[$key] = $item;
				$append[] = $product;
			}
		}

		$result = $append;
	}

	return $result;
}

function altUrlencode($string)
{
	return F::alt($string);
}

function maggioreDiZero($numero)
{
	return $numero > 0 ? true : false;
}

function sanitizeHtmlLightCompat($stringa) {
	$charset = Params::$htmlentititiesCharset;
	$stringa=htmlspecialchars(nullToBlank($stringa),ENT_COMPAT,$charset);
	return $stringa;
}
