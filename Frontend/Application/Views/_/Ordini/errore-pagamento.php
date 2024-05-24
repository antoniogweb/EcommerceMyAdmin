<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$titoloPagina = gtext("Errore nella trasazione");
$noNumeroProdotti = true;
include(tpf("/Elementi/Pagine/page_top.php"));
?>
<div class="uk-text-left">
	<p><?php echo gtext("La transazione dell'ordine")?> #<?php echo $ordine["id_o"];?> <?php echo gtext("non è andata a buon fine.")?></p>
	
	<p><a class="uk-button uk-button-text" href="<?php echo $this->baseUrl."/resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]."/".$ordine["admin_token"]."?n=y";?>"><?php echo gtext("Torna all'ordine");?></a></p>
</div>
<?php
include(tpf("/Elementi/Pagine/page_bottom.php"));
