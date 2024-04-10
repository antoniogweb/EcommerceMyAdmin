<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-hidden@m uk-text-left">
	<a class="uk-icon-button uk-text-danger remove cart_item_delete_link cart_item_delete_mobile_link" title="<?php echo gtext("elimina il prodotto dal carrello", false);?>" href="#"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/close.svg");?></span></a>
</div>
<?php if ($p["cart"]["immagine"]) { ?>
<?php if (!$p["cart"]["id_p"]) { ?><a href="<?php echo $this->baseUrl."/".$urlAliasProdotto;?>"><?php } ?>
	<img src="<?php echo $this->baseUrlSrc."/thumb/carrello/".$p["cart"]["immagine"];?>" />
<?php if (!$p["cart"]["id_p"]) { ?></a><?php } ?>
<?php } ?>
