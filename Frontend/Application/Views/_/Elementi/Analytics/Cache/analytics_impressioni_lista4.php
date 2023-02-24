<?php if (!defined('EG')) die('Direct access not allowed!');

$items = array(
	"items"	=>	array(),
);

if (isset($item_list_id) && isset($item_list_name))
{
	$items["item_list_id"] = $item_list_id;
	$items["item_list_name"] = $item_list_name;
}

if (!isset($pages))
	$pages = PagesModel::getPageDetailsList($arrayIdsPages, Params::$lang);

foreach ($pages as $p)
{
// 			$prezzoMinimo = prezzoMinimo($p["pages"]["id_page"]);
	$prezzoMinimo = (isset($p["combinazioni"]["price"]) && !User::$nazione) ? $p["combinazioni"]["price"] : prezzoMinimo($p["pages"]["id_page"]);
	
	$prezzoFinaleIvato = number_format(calcolaPrezzoFinale($p["pages"]["id_page"], $prezzoMinimo, true, !v("prezzi_ivati_in_carrello")),2,".","");
	
	$items["items"][] = array(
		"item_id"	=>	v("usa_sku_come_id_item") ? $p["pages"]["codice"] : $p["pages"]["id_page"],
		"item_name"	=>	sanitizeJs(htmlentitydecode(field($p, "title"))),
		"quantity"	=>	1,
		"price"		=>	$prezzoFinaleIvato,
	);
}
?>
<script>
	gtag('event', 'view_item_list', <?php echo json_encode($items);?>);
	
	<?php if (v("debug_js")) { ?>
	console.log(<?php echo json_encode($items);?>);
	<?php } ?>
</script>

