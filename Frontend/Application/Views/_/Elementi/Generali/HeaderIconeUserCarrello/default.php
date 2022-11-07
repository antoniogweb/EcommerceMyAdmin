<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<a class="" href="#"><span class="uk-icon uk-text-meta"><?php include tpf("Elementi/Icone/Svg/cerca.svg");?></span></a>
<?php include(tpf("/Elementi/header_search_box.php"));?>

<a class="uk-margin-left uk-visible@m es-navbar-button" href="<?php echo $this->baseUrl."/wishlist/vedi"?>">
	<span class="uk-icon uk-text-meta"><?php include tpf("Elementi/Icone/Svg/cuore.svg");?></span>
	<span class="uk-badge link_wishlist_num_prod <?php if ((int)$prodInWishlist === 0) { ?>uk-hidden<?php } ?>"><?php echo $prodInWishlist;?></span>
</a>

<a class="uk-margin-left uk-visible@m uk-link-muted es-navbar-button" href="<?php if ($islogged) { ?><?php echo $this->baseUrl."/area-riservata";?><?php } else { ?><?php echo $this->baseUrl."/regusers/login";?><?php } ?>"><span class="uk-icon <?php if ($islogged) { echo "uk-text-primary";?><?php } else { ?>uk-text-meta<?php } ?>"><?php include tpf("Elementi/Icone/Svg/user.svg");?></span></a>
<?php
$ukdropdown = "pos: bottom-right; offset: 10; delay-hide: 200;";
include(tpf("/Elementi/header_user_box.php"));?>

<a class="uk-margin-left uk-link-muted es-navbar-button" href="<?php echo $this->baseUrl."/carrello/vedi"?>" uk-toggle="target: #cart-offcanvas" onclick="return false">
	<span class="uk-icon uk-text-meta"><?php include tpf("Elementi/Icone/Svg/carrello.svg");?></span>
	<span class="uk-badge link_carrello_num_prod <?php if ((int)$prodInCart === 0) { ?>uk-hidden<?php } ?>"><?php echo $prodInCart;?></span>
</a>
