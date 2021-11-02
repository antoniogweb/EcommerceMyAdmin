<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("codice_verifica_fbk")) {
	echo htmlentitydecode(v("codice_verifica_fbk"));
} ?>
<?php if (v("codice_fbk")) { ?>
	<!-- Facebook Pixel Code -->
	<script>
		<?php echo htmlentitydecode(v("codice_fbk")); ?>
		fbq('track', 'PageView');
		
		<?php
		$arrayProprieta = array();
		if (isset($idOrdineGtm))
		{
			$o = new OrdiniModel();
			$r = new RigheModel();
			$c = new CategoriesModel();
			$p = new PagesModel();
			
			$ordineGTML = $o->selectId((int)$idOrdineGtm);
			
			if (!empty($ordineGTML) && !$ordineGTML["inviato_fbk"] && $ordineGTML["stato"] != "deleted")
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
						"id"	=>	$ro["id_page"],
						"quantity"	=>	$ro["quantity"],
					);
				}
				
				$arrayProprieta = array(
					"value"		=>	$ordineGTML["total"],
					"currency"	=>	"EUR",
					"content_type"	=>	"product",
					"contents"	=>	$tempRigheGTM,
				);
				?>
				fbq('track', 'Purchase', <?php echo json_encode($arrayProprieta);?>);
				<?php
				
				$o->setValues(array(
					"inviato_fbk"	=>	1,
					"data_fbk"		=>	date("Y-m-d H:i:s"),
				));
				
				$o->update((int)$idOrdineGtm);
			}
			else
			{
			?>
			
			<?php
			}
		}
		else
		{
			if ($nomePaginaPerTracking)
				$arrayProprieta["content_name"] = sanitizeJs(htmlentitydecode($nomePaginaPerTracking));
			
			if (isset($isPage) && $idPaginaPerTracking && isProdotto($idPaginaPerTracking)) {
				$arrayProprieta["content_ids"] = array($idPaginaPerTracking);
				$arrayProprieta["content_type"] = "product";
				?>
				fbq('track', 'ViewContent', <?php echo json_encode($arrayProprieta);?>);
			<?php } else if (!empty($arrayProprieta)){ ?>
				fbq('track', 'ViewContent',  <?php echo json_encode($arrayProprieta);?>);
			<?php } else if ($this->controller == "ordini" && $this->action == "index") {
			
				$tempRigheGTM = array();
				
				foreach ($pages as $p)
				{
					$tempRigheGTM[] = array(
						"id"	=>	$p["cart"]["id_page"],
						"quantity"	=>	$p["cart"]["quantity"],
					);
				}
				
				$arrayProprieta = array(
					"content_type"	=>	"product",
					"currency"		=>	"EUR",
					"contents"		=>	$tempRigheGTM,
					"num_items"		=>	count($pages),
				);
			?>
				fbq('track', 'InitiateCheckout', <?php echo json_encode($arrayProprieta);?>);
			<?php } else { ?>
				
			<?php } ?>
		<?php } ?>
	</script>
	<?php if (v("codice_fbk_noscript")) {
		echo htmlentitydecode(v("codice_fbk_noscript"));
	} ?>
<?php } ?>
