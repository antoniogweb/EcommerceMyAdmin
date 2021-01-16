<?php
if (!defined('EG')) die('Direct access not allowed!');

function statoOrdine($type)
{
	if (isset(OrdiniModel::$stati[$type]))
		return OrdiniModel::$stati[$type];
	
	return $type;
}

function statoOrdineBreve($type)
{
	if (isset(OrdiniModel::$stati[$type]))
		return OrdiniModel::$stati[$type];
	
	return $type;
}

function labelStatoOrdine($type)
{
	if (isset(OrdiniModel::$labelStati[$type]))
		return OrdiniModel::$labelStati[$type];
	
	return $type;
}

function metodoPagamento($type)
{
	switch ($type)
	{
		case "bonifico":
			return gtext("Bonifico bancario", false);
			break;
		case "contrassegno":
			return gtext("Contrassegno (pagamento alla consegna)", false);
			break;
		case "paypal":
			return gtext("Pagamento online tramite PayPal", false);
			break;
		case "carta_di_credito":
			return gtext("Pagamento online tramite carta di credito", false);
			break;
		default:
			return gtext("Nessuno", false);
			break;
	}
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

function getUrlAlias($id_page)
{
	$clean["id_page"] = (int)$id_page;
	
	$p = new PagesModel();
	
	return $p->getUrlAlias($clean["id_page"]);
}

function getCategoryUrlAlias($id_c)
{
	$clean["id_c"] = (int)$id_c;
	
	$p = new CategoriesModel();
	
	return $p->getUrlAlias($clean["id_c"]);
}

function getMarchioUrlAlias($id_c)
{
	$clean["id_c"] = (int)$id_c;
	
	$p = new MarchiModel();
	
	return $p->getUrlAlias($clean["id_c"]);
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
	$url = utf8_decode(html_entity_decode($url,ENT_QUOTES,'UTF-8'));
	
	$temp = null;
	for ($i=0;$i<strlen($url); $i++)
	{
		if (strcmp($url[$i],' ') === 0)
		{
			$temp .= '-';
		}
		else
		{
			if (preg_match('/^[a-zA-Z_0-9\-]$/',$url[$i]))
			{
				$temp .= $url[$i];
			}
			else
			{
				$temp .= '-';
			}
		}
	}

	$temp = str_replace("--","-",$temp);
	$temp = str_replace("--","-",$temp);
	
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

function getYesNoUtenti($input)
{
	switch($input)
	{
		case 0:
			$output = 'sì';
			break;
		case 1:
			$output = 'no';
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
			$output = 'sì';
			break;
		case 'N':
			$output = 'no';
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
		return "sì";
	}
	return "no";
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

function smartDate($uglyDate = null)
{
	return date('d-m-Y',strtotime($uglyDate));
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
			if ((int)$dateArray[0] >= 1970 or (int)$dateArray[0] <= 2030)
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
			if ((int)$dateArray[0] >= 1970 and (int)$dateArray[0] <= 2030)
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

function getPrezzoScontatoN($conSpedizione = false, $ivato = 0)
{
	$c = new CartModel();
	$totale = $c->totaleScontato($conSpedizione);
	
// 	IvaModel::getAliquotaEstera();
// 	
// 	if (isset(IvaModel::$titoloAliquotaEstera))
// 		$ivato = 0;
	
	if ($ivato)
		$totale += $c->iva(false);
	
	return $totale;
}

function getPrezzoScontato($ivato = 0)
{
	return setPriceReverse(getPrezzoScontatoN(false, $ivato));
}

function getSpedizioneN()
{
	if (!v("prezzi_ivati_in_carrello"))
		$subtotale = getPrezzoScontatoN(false);
	else
		$subtotale = getPrezzoScontatoN(false, true);
	
	// Se il totale è sopra la soglia delle spedizioni gratuite, le spese di spedizione sono 0
	if (ImpostazioniModel::$valori["spedizioni_gratuite_sopra_euro"] > 0 && $subtotale >= ImpostazioniModel::$valori["spedizioni_gratuite_sopra_euro"])
		return 0;
	
	$c = new CartModel();
	$corr = new CorrieriModel();
	$corrSpese = new CorrierispeseModel();
	
	$peso = $c->getPesoTotale();
	
	$corriere = array();
	
	$nazione = User::getSpedizioneDefault();
	
	if (isset($_POST["nazione_spedizione"]))
		$nazione = $_POST["nazione_spedizione"];
	
	if (isset($_POST["id_corriere"]))
		$corriere = $corr->selectId((int)$_POST["id_corriere"]);
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

function getIvaN()
{
	if (Parametri::$ivaInclusa)
	{
		return 0;
	}
	else
	{
		$c = new CartModel();
		$iva = $c->iva();
// 		$iva = $iva + (getSpedizioneN() * Parametri::$iva)/100;
		return $iva;
	}
}

function getIva()
{
	return setPriceReverse(getIvaN());
}

function getTotalN()
{
	$cifre = v("cifre_decimali");
	$totalConSpedizione = getPrezzoScontatoN(true);
	$iva = getIvaN();
	
// 	return $iva;
	return number_format($totalConSpedizione,$cifre,".","") + number_format($iva,$cifre,".","");
}

function getTotal()
{
	return setPriceReverse(getTotalN());
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

function hasCombinations($id_page)
{
	$clean['id_page'] = (int)$id_page;
	
	$p = new PagesModel();

	return $p->hasCombinations($clean['id_page']);
}

class Domain
{

	static public $name;
	static public $publicUrl;
	static public $parentRoot;
	static public $adminRoot;
	static public $adminName;
	static public $currentUrl;
	
}

function htmlentitydecode($value)
{
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
	
	$p = new PagesModel();
	
	return $p->isProdotto($clean['id_page']);
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

function cfield($p, $field)
{
	if (isset($p["contenuti_tradotti_categoria"][$field]) and strcmp($p["contenuti_tradotti_categoria"][$field],"") !== 0)
		return $p["contenuti_tradotti_categoria"][$field];
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
	
// 	if (isset($p["contenuti_tradotti"][$field]) and strcmp($p["contenuti_tradotti"][$field],"") !== 0)
// 	{
// 		return $p["contenuti_tradotti"][$field];
// 	}
// 	else
// 	{
// 		if (isset($p["attributi"][$field]))
// 			return $p["attributi"][$field];
// 	}
// 	
// 	return "";
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

function carfield($p, $field)
{
	if (isset($p["caratteristiche_tradotte"][$field]) and strcmp($p["caratteristiche_tradotte"][$field],"") !== 0)
	{
		return $p["caratteristiche_tradotte"][$field];
	}
	else
	{
		if (isset($p["caratteristiche"][$field]))
			return $p["caratteristiche"][$field];
	}
	
	return "";
}

function carvfield($p, $field)
{
	if (isset($p["caratteristiche_valori_tradotte"][$field]) and strcmp($p["caratteristiche_valori_tradotte"][$field],"") !== 0)
		return $p["caratteristiche_valori_tradotte"][$field];
	else
	{
		if (isset($p["caratteristiche_valori"][$field]))
			return $p["caratteristiche_valori"][$field];
	}
	
	return "";
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

function genericField($p, $field, $table)
{
	if (isset($p["contenuti_tradotti"][$field]) and strcmp($p["contenuti_tradotti"][$field],"") !== 0)
		return $p["contenuti_tradotti"][$field];
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
	
	$res = $c->clear()->select("categories.*, contenuti_tradotti_categoria.*")->left("contenuti_tradotti as contenuti_tradotti_categoria")->on("contenuti_tradotti_categoria.id_c = categories.id_c and contenuti_tradotti_categoria.lingua = '".sanitizeDb(Params::$lang)."'")->where(array(
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

function attivaModuli($string, $obj = null)
{
	$string = preg_replace_callback('/(\[baseUrlSrc\])/', 'getBaseUrlSrc' ,$string);
	$string = preg_replace_callback('/(\[baseUrl\])/', 'getBaseUrl' ,$string);
// 	$string = preg_replace_callback('/\[(testo\=)([0-9]{1,})\]/', 'getTesto' ,$string);
	$string = preg_replace_callback('/\[testo (.*?)\]/', 'getTesto' ,$string);
	$string = preg_replace_callback('/\[immagine (.*?)\]/', 'getImmagine' ,$string);
	$string = preg_replace_callback('/\[link (.*?)\]/', 'getLink' ,$string);
	$string = preg_replace_callback('/\[video (.*?)\]/', 'getVideo' ,$string);
	
	if ($obj)
	{
		$string = preg_replace_callback('/\[slide\]/', array($obj,'getSlide') ,$string);
		
		$string = preg_replace_callback('/\[prodotti\]/', array($obj,'getProdotti') ,$string);
		$string = preg_replace_callback('/\[prodotti-in-evidenza\]/', array($obj,'getProdottiInEvidenza') ,$string);
		$string = preg_replace_callback('/\[slide_prodotto\]/', array($obj,'getSlideProdotto') ,$string);
		$string = preg_replace_callback('/\[carrello_prodotto\]/', array($obj,'getCarrelloProdotto') ,$string);
		$string = preg_replace_callback('/\[news-in-evidenza\]/', array($obj,'getNewsInEvidenza') ,$string);
		$string = preg_replace_callback('/\[team\]/', array($obj,'getTeam') ,$string);
		
		if (defined("FASCE_TAGS"))
		{
			foreach (FASCE_TAGS as $reg => $metodo)
			{
				$string = preg_replace_callback('/\['.$reg.'\]/', array($obj,$metodo) ,$string);
			}
		}
	}
	
	return $string;
}

function getBaseUrlSrc($matches)
{
	return rtrim(Url::getFileRoot(),"/");
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

function getVideo($matches, $tags = null, $tipo = "TESTO")
{
	return getTesto($matches, $tags, "VIDEO");
}

function getTesto($matches, $tags = null, $tipo = "TESTO")
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
	
	if (count($testo) > 0)
	{
		$clean["id"] = (int)$testo["id_t"];
		
		$iconaEdit = User::$adminLogged ? "<span rel='".$clean["id"]."' title='modifica il testo' class='edit_blocco_testo' href='#'><img src='".Url::getFileRoot()."admin/Public/Img/Icons/elementary_2_5/edit.png' /></span>" : null;
		
		$t = strcmp($tags,"") !== 0 ? strip_tags(htmlentitydecode($testo["valore"]),$tags) : htmlentitydecode($testo["valore"]);
		
		$alt = $testo["alt"] ? 'alt="'.$testo["alt"].'"' : "";
		
		if ($testo["immagine"])
			$t .= "<img src='".Domain::$publicUrl."/thumb/widget/".$testo["id_t"]."/".$testo["immagine"]."' $alt/>";
		
		$urlLink = $target = "";
		
		if ($testo["id_contenuto"] || $testo["id_categoria"]|| $testo["url_link"])
		{
			$target = "";
			
			if ($testo["target_link"] == "ESTERNO")
				$target = "target='_blank'";
			
			if ($testo["id_contenuto"])
				$urlLink = Url::getRoot().getUrlAlias($testo["id_contenuto"], sanitizeAll(getLinguaIso()));
			else if ($testo["id_categoria"])
				$urlLink = Url::getRoot().getCategoryUrlAlias($testo["id_categoria"], sanitizeAll(getLinguaIso()));
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
		
		$path = tp() . "/Contenuti/Elementi/Widget/".strtolower($tipo).".php";
		
		if (file_exists($path))
		{
			ob_start();
			include $path;
			$t = ob_get_clean();
		}
		
		if (User::$adminLogged)
		{
			return "<div class='blocco_testo'>".$t."$iconaEdit</div>";
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
			$t->values = array(
				"valore"	=>	$clean["chiave"],
			);
		}
		
		$t->values["chiave"] = $clean["chiave"];
		$t->values["lingua"] = sanitizeDb($lingua);
		$t->values["tipo"] = sanitizeDb($tipo);
		
		if ($t->insert())
			return getTesto($matches, $tags, $tipo);
	}
	
	return "";
}

//chiama la traduzione di un blocco di testo
function testo($chiave, $tags = null)
{
	return getTesto(array("",$chiave),$tags);
}

//chiama la traduzione di un blocco di testo
function t($chiave, $tags = null)
{
	return getTesto(array("",$chiave),$tags);
}

//chiama la traduzione di un blocco immagine
function i($chiave, $tags = null)
{
	return getTesto(array("",$chiave),$tags, "IMMAGINE");
}

//chiama la traduzione di un blocco immagine
function l($chiave, $tags = null)
{
	return getTesto(array("",$chiave),$tags, "LINK");
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

function calcolaPrezzoFinale($idPage, $prezzoIntero, $checkPromo = true)
{
	$c = new CartModel();
	$prezzoFinale = $c->calcolaPrezzoFinale($idPage, $prezzoIntero, 1, $checkPromo);
	
	if (ImpostazioniModel::$valori["esponi_prezzi_ivati"] == "Y")
	{
		$p = new PagesModel();
		$iva = $p->getIva($idPage);
		$prezzoFinale = $prezzoFinale + ($prezzoFinale * (float)$iva / 100);
	}
	
	return $prezzoFinale;
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
		
		$prezzoIntero = $prezzoIntero + ($prezzoIntero * (float)$iva / 100);
	}
	
	return $prezzoIntero;
}

function syncMailchimp($data)
{
    $apiKey = ImpostazioniModel::$valori["mailchimp_api_key"];
    $listId = ImpostazioniModel::$valori["mailchimp_list_id"];

    $memberId = md5(strtolower($data['email']));
    $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

    $json = json_encode([
        'email_address' => $data['email'],
        'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
        'merge_fields'  => [
            'FNAME'     => $data['firstname'],
            'LNAME'     => $data['lastname']
        ]
    ]);

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                                                                 

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
    }
}

function prodottiMarchio($idMarchio)
{
	$p = new PagesModel();
	
	return $p->clear()->where(array(
		"id_marchio"	=>	(int)$idMarchio,
	))->rowNumber();
}

function traduci($string)
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

function numeroProdottiCategoria($id_c)
{
	$c = new CategoriesModel();
	
	return $c->numeroProdotti($id_c);
}

function getShopCategoryId()
{
	$c = new CategoriesModel();
	
	return $c->getShopCategoryId();
}

function categorieFiglie($id_c)
{
	$c = new CategoriesModel();
	
	return $c->categorieFiglie($id_c);
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
	if (isset($tm[$controller]))
		return "active";
	
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

function tp()
{
	$subfolder = isset(Params::$viewSubfolder) ? DS . Params::$viewSubfolder : "";
	
	return Domain::$parentRoot."/Application/Views$subfolder";
}

function tpf($filePath = "")
{
	$subfolder = isset(Params::$viewSubfolder) ? DS . Params::$viewSubfolder : "";
	
	$subFolderFullPath = Domain::$parentRoot."/Application/Views$subfolder"."/".ltrim($filePath,"/");
	
	if (isset(Params::$viewSubfolder) && file_exists($subFolderFullPath))
		return $subFolderFullPath;
	
	return Domain::$parentRoot."/Application/Views/_/".ltrim($filePath,"/");
}

function singPlu($numero, $sing, $plu)
{
	if ($numero == 1)
		return $sing;
	else
		return $plu;
}

function aToX($struct, $key = "", $cdata = true)
{
	$xml = "";
	
	foreach ($struct as $kk => $value)
	{
		if (!is_numeric($kk))
		{
			if (is_array($value))
			{
				if (array_values($value) === $value)
					$xml .= aToX($value, $kk, $cdata);
				else
					$xml .= "<$kk>".aToX($value, $kk, $cdata)."</$kk>"; 
			}
			else
				$xml .= "<$kk>".cXmlC($value, $cdata)."</$kk>";
		}
		else
		{
			if (is_array($value))
				$xml .= "<$key>".aToX($value, "", $cdata)."</$key>";
		}
	}
	
	return $xml;
}

function cXmlC($t, $cdata = false)
{
// 	$t = str_replace(">","&gt;",$t);
// 	$t = str_replace("°","",$t);
// 	$t = str_replace("&","&amp;",$t);
// 	$t = str_replace("<","&lt;",$t);
	
	if ($cdata)
		$t = "<![CDATA[".$t."]]>";
	
	return $t;
}

function sanitizeJs($jsString)
{
	$result = strtr($jsString, array('\\' => '\\\\', "'" => "\\'", '"' => '\\"', "\r" => '\\r', "\n" => '\\n' ));
	
	return $result;
}

function parent($file)
{
	$file = str_replace(tp(),"",$file);
	
	return ROOT."/Application/Views/_".$file;
}
