<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id_page".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<?php if (v("usa_tag") && v("tag_in_blog")) { ?>
	<li <?php echo $posizioni['tag'];?>><a class="help_tag_prodotto" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/tag/$id_page".$this->viewStatus;?>"><?php echo gtext("Tag");?></a></li>
	<?php } ?>
	<?php if (v("contenuti_in_blog")) { ?>
	<li <?php echo $posizioni['testi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/testi/$id_page".$this->viewStatus;?>"><?php echo gtext("Contenuti");?></a></li>
	<?php } ?>
	<li <?php echo $posizioni['immagini'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/immagini/$id_page".$this->viewStatus;?>"><?php echo gtext("Immagini");?></a></li>
	<li <?php echo $posizioni['meta'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/meta/$id_page".$this->viewStatus;?>"><?php echo gtext("Meta");?></a></li>
	<?php if (v("correlati_in_prodotti")) { ?>
	<li <?php echo $posizioni['prod_corr'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/correlati/$id_page".$this->viewStatus;?>"><?php echo gtext("Notizie correlate");?></a></li>
	<?php } ?>
	<?php if (v("mostra_link_in_blog")) { ?>
	<li <?php echo $posizioni['link'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/link/$id_page".$this->viewStatus;?>"><?php echo gtext("Link");?></a></li>
	<?php } ?>
	<?php if (v("abilita_visibilita_pagine")) { ?>
	<li <?php echo $posizioni['lingue'];?>><a href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/lingue/$id_page".$this->viewStatus;?>"><?php echo gtext("Lingue");?></a></li>
	<?php } ?>
</ul>


<?php } else { ?>



<?php } ?>

<div style="clear:left;"></div>
