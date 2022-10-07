<?php if (!defined('EG')) die('Direct access not allowed!');
if (v("codice_gtm_analytics"))
{
	// Guida di riferimento Google
	// https://developers.google.com/analytics/devguides/collection/gtagjs/enhanced-ecommerce
	echo htmlentitydecode(v("codice_gtm_analytics"));

	if (isset($nomePaginaPerTracking) && $nomePaginaPerTracking)
	{
		$itemGtag = array(
			array(
				"item_id"	=>	v("usa_sku_come_id_item") ? $codicePerTracking : $idPaginaPerTracking,
				"item_name"	=>	sanitizeJs(htmlentitydecode($nomePaginaPerTracking)),
			),
		);
	?>
	<?php if (isProdotto($idPaginaPerTracking)) { ?>
	<script>
		gtag('event', 'view_item', {
			"items": <?php echo json_encode($itemGtag);?>,
			"currency": "EUR",
			"value": <?php echo number_format(calcolaPrezzoFinale($idPaginaPerTracking, prezzoMinimo($idPaginaPerTracking)),2,".","");?>
		});
	</script>
	<?php } ?>
	<?php if ($codiceConversioneGoogle) {
		echo $codiceConversioneGoogle;
	} ?>
	
	<?php }

	if (isset($idOrdineGtm))
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
					"id"	=>	v("usa_sku_come_id_item") ? $ro["codice"] : $ro["id_page"],
	// 				"sku"	=>	$ro["codice"],
					"name"	=>	sanitizeJs(htmlentitydecode($ro["title"])),
					"category"	=>	sanitizeJs(htmlentitydecode($catGTM)),
					"price"	=>	v("prezzi_ivati_in_carrello") ? $ro["prezzo_finale_ivato"] : $ro["prezzo_finale"],
					"quantity"	=>	$ro["quantity"],
				);
			}
			
			$purchase = array(
				"transaction_id"	=>	$ordineGTML["id_o"],
				"affiliation"		=>	sanitizeJs(Parametri::$nomeNegozio),
				"value"				=>	$ordineGTML["total"],
				"currency"			=>	"EUR",
				"tax"				=>	$ordineGTML["iva"],
				"shipping"			=>	v("prezzi_ivati_in_carrello") ? $ordineGTML["spedizione_ivato"] : $ordineGTML["spedizione"],
				"items"				=>	$tempRigheGTM,
			);
			?>
			<script>
				gtag('event', 'purchase', <?php echo json_encode($purchase);?>);
			</script>
			<?php
			$o->setValues(array(
				"inviato_gtm"	=>	1,
				"data_gtm"		=>	date("Y-m-d H:i:s"),
			));
			
			$o->update((int)$idOrdineGtm);
			
			if (v("campo_send_to_google_ads"))
			{
				$purchaseAds = $purchase;
				
				$purchaseAds["transaction_id"] = $ordineGTML["id_o"]."-ADS";
				
				$purchaseAds["send_to"] = v("campo_send_to_google_ads");
				
				if (v("codice_account_merchant"))
					$purchaseAds["aw_merchant_id"] = v("codice_account_merchant");
			?>
			<script>
				gtag('event', 'purchase', <?php echo json_encode($purchaseAds);?>);
			</script>
			<?php
			}
			?>
		<?php } ?>
	<?php }
	
	if ($this->controller == "ordini" && $this->action == "index")
	{
		$gtmActionName = "begin_checkout";
		
		echo "\n";
		
		$items = array(
			"currency"	=>	"EUR",
			"value"		=>	number_format(getSubTotalN(true),2,".",""),
			"items"		=>	array(),
		);
		
		foreach ($pages as $p)
		{
			$items["items"][] = array(
				"item_id"	=>	v("usa_sku_come_id_item") ? $p["cart"]["codice"] : $p["cart"]["id_page"],
				"item_name"	=>	sanitizeJs(htmlentitydecode($p["cart"]["title"])),
				"quantity"	=>	$p["cart"]["quantity"],
				"price"		=>	v("prezzi_ivati_in_carrello") ? $p["cart"]["price_ivato"] : $p["cart"]["price"],
			);
		}
		?>
		<script>
// 			var checkout_items = <?php echo json_encode($items);?>
			
			gtag('event', '<?php echo $gtmActionName;?>', <?php echo json_encode($items);?>);
		</script>
		<?php
	}
	?>
<?php } ?>
