<?php if (!defined('EG')) die('Direct access not allowed!');

if (false && isset($idOrdineGtm))
{
	$o = new OrdiniModel();
	$r = new RigheModel();
	$c = new CategoriesModel();
	$p = new PagesModel();
	
	$ordineGTML = $o->selectId((int)$idOrdineGtm);
	
	if (!empty($ordineGTML) && !$ordineGTML["inviato_gtm"] && $ordineGTML["stato"] != "deleted")
	{
		$rOrdine = $r->clear()->where(array("id_o"=>(int)$idOrdineGtm))->send(false);
		
		$tempRigheGTM = array();
		foreach ($rOrdine as $ro)
		{
			$pagGTM = $p->clear()->selectId($ro["id_page"]);
			$catGTM = "";
			if (!empty($pagGTM))
				$catGTM = $c->clear()->where(array("id_c"=>$pagGTM["id_c"]))->field("title");
			
			$tempRigheGTM[] = array(
				"sku"	=>	$ro["codice"],
				"name"	=>	sanitizeJs(htmlentitydecode($ro["title"])),
				"category"	=>	sanitizeJs(htmlentitydecode($catGTM)),
				"price"	=>	v("prezzi_ivati_in_carrello") ? $ro["prezzo_finale_ivato"] : $ro["prezzo_finale"],
				"quantity"	=>	$ro["quantity"],
			);
		} ?>
		<script>
		window.dataLayer = window.dataLayer || [];
		dataLayer.push({
		'transactionId': '<?php echo $ordineGTML["id_o"];?>',
		'transactionAffiliation': '<?php echo Parametri::$nomeNegozio;?>',
		'transactionTotal': <?php echo $ordineGTML["total"];?>, 
		'transactionTax': <?php echo $ordineGTML["iva"];?>,
		'transactionShipping': <?php echo v("prezzi_ivati_in_carrello") ? $ordineGTML["spedizione_ivato"] : $ordineGTML["spedizione"];?>,
		'transactionProducts': <?php echo json_encode($tempRigheGTM);?>
		});
		function gtag(){
			dataLayer.push(arguments);
		}
		gtag('js', new Date());
		gtag('config', '<?php echo v("codice_gtm"); ?>');
		</script>
		
		<?php
		$o->setValues(array(
			"inviato_gtm"	=>	1,
			"data_gtm"		=>	date("Y-m-d H:i:s"),
		));
		
		$o->update((int)$idOrdineGtm);
		?>
	<?php } ?>
<?php } ?>
<?php if (v("codice_gtm_analytics")) {
// Guida di riferimento Google
// https://developers.google.com/analytics/devguides/collection/gtagjs/enhanced-ecommerce
?>
<?php echo htmlentitydecode(v("codice_gtm_analytics"));?>
<?php } ?>

<?php if ($nomePaginaPerTracking) {
	$itemGtag = array(
		array(
			"id"	=>	$idPaginaPerTracking,
			"name"	=>	sanitizeJs(htmlentitydecode($nomePaginaPerTracking)),
		),
	);
?>
<script>
	gtag('event', 'view_item', {
		"items": <?php echo json_encode($itemGtag);?>
	});
</script>
<?php } ?>
