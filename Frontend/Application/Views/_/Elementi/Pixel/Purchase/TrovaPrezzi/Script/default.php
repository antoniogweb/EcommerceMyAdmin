<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (App::cookieTerziApprovati()) { ?>
<script type="text/javascript">
	window._tpt = window._tpt || [];
	window._tpt.push({ event: "setEmail", email: '<?php echo $strutturaOrdine["email"];?>' });
	<?php
	foreach ($righe as $r)
	{
		if ($r["id_r"] > 0)
		{
		?>
			window._tpt.push({ event: "addItem", sku: '<?php echo $r["codice"];?>', product_name: '<?php echo sanitizeJs(F::meta($r["titolo"]));?>' });
		<?php
		}
	}
	?>
</script>
<?php } ?>

<script type="text/javascript">
	window._tpt = window._tpt || [];
	window._tpt.push({ event: "setAccount", id: '<?php echo $this->params["key_1"];?>' });
	window._tpt.push({ event: "setOrderId", order_id: '<?php echo $strutturaOrdine["id_o"];?>' });
	window._tpt.push({ event: "setAmount", amount: '<?php echo $strutturaOrdine["prezzo_scontato_ivato"];?>' });
	window._tpt.push({ event: "orderSubmit"});
</script>
