<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("permetti_di_disattivare_promo_al_carrello")) { ?>
<div class="uk-text-left"><span class="uk-text-danger uk-text-small"><?php echo gtext("Disattiva coupon");?></span> <a data-random="<?php echo md5(randString(10).microtime().uniqid(mt_rand(),true));?>" class="uk-text-danger uk-text-small elimina_coupon" href=""><span uk-icon="icon: trash;ratio: 0.8;"></span></a></div>
<?php } ?>
