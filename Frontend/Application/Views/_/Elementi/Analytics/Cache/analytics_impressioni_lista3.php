<?php if (!defined('EG')) die('Direct access not allowed!');

$items = array(
	"items"	=>	array(),
);

foreach ($pages as $p)
{
	$prezzoMinimo = prezzoMinimo($p["pages"]["id_page"]);
	$prezzoFinaleIvato = number_format(calcolaPrezzoFinale($p["pages"]["id_page"], $prezzoMinimo),2,".","");
	
	$items["items"][] = array(
		"id"	=>	v("usa_sku_come_id_item") ? $p["pages"]["codice"] : $p["pages"]["id_page"],
		"name"	=>	sanitizeJs(htmlentitydecode(field($p, "title"))),
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
<?php
