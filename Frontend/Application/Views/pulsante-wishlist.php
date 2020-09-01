<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="blocco_wish">
	<a href="<?php echo $this->baseUrl."/wishlist/elimina/".$p["pages"]["id_page"];?>" class="in_wishlist azione_wishlist <?php if (!WishlistModel::isInWishlist($p["pages"]["id_page"])) { echo "display_none"; }?>">- Elimina dalla wishlist</a>
	<a href="<?php echo $this->baseUrl."/wishlist/aggiungi/".$p["pages"]["id_page"];?>" class="not_in_wishlist azione_wishlist <?php if (WishlistModel::isInWishlist($p["pages"]["id_page"])) { echo "display_none"; }?>">+ Aggiungi alla wishlist</a>
</div>
