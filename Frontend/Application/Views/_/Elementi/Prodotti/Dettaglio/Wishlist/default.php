<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (!isset($classeLinkWishlist))
	$classeLinkWishlist = "uk-text-small uk-text-muted";
if (!isset($classeHeartWishlist))
	$classeHeartWishlist = "";
?>
<div id="whishlist" class="uk-margin blocco_wishlist">
	<div class="not_in_wishlist relative" style="<?php if (WishlistModel::isInWishlist($p["pages"]["id_page"])) { ?>display:none<?php } ?>;">
		<a class="wishlist azione_wishlist <?php echo $classeLinkWishlist;?>" href="<?php echo $this->baseUrl."/wishlist/aggiungi/".$p["pages"]["id_page"];?>">
			<span class="<?php echo $classeHeartWishlist;?>" uk-icon="icon: heart"></span> <?php echo gtext("Aggiungi alla lista dei desideri", false);?></span>
		</a>
	</div>
	
	<div class="in_wishlist relative" style="<?php if (!WishlistModel::isInWishlist($p["pages"]["id_page"])) { ?>display:none<?php } ?>;">
		<a class="<?php echo $classeLinkWishlist;?>" href="<?php echo $this->baseUrl."/wishlist/vedi";?>" rel="nofollow">
			<span class="uk-text-primary"><span class="<?php echo $classeHeartWishlist;?>" uk-icon="icon: heart"></span> <?php echo gtext("Sfoglia la lista dei desideri"); ?></span>
		</a>
	</div>
</div>
