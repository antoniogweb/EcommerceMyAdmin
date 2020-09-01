<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<figure class="woocommerce-product-gallery__wrapper">
	<div data-thumb="<?php echo $this->baseUrlSrc."/thumb/dettaglio/".$p["pages"]["immagine"];?>" data-thumb-alt="" class="woocommerce-product-gallery__image"><a href="<?php echo $this->baseUrlSrc."/images/contents/".$p["pages"]["immagine"];?>"><img width="600" height="600" src="<?php echo $this->baseUrlSrc."/thumb/dettagliobig/".$p["pages"]["immagine"];?>" class="wp-post-image" alt="" title="cube-01" data-caption="" data-src="<?php echo $this->baseUrlSrc."/images/contents/".$p["pages"]["immagine"];?>" data-large_image="<?php echo $this->baseUrlSrc."/images/contents/".$p["pages"]["immagine"];?>" data-large_image_width="1000" data-large_image_height="1000" /></a></div>
	<?php foreach ($altreImmagini as $imm) { ?>
	<div data-thumb="<?php echo $this->baseUrlSrc."/thumb/dettaglio/".$imm["immagine"];?>" data-thumb-alt="" class="woocommerce-product-gallery__image"><a href="<?php echo $this->baseUrlSrc."/images/contents/".$imm["immagine"];?>"><img width="600" height="600" src="<?php echo $this->baseUrlSrc."/thumb/dettagliobig/".$imm["immagine"];?>" class="wp-post-image" alt="" title="cube-01" data-caption="" data-src="<?php echo $this->baseUrlSrc."/images/contents/".$imm["immagine"];?>" data-large_image="<?php echo $this->baseUrlSrc."/images/contents/".$imm["immagine"];?>" data-large_image_width="1000" data-large_image_height="1000" /></a></div>
	<?php } ?>
</figure>
