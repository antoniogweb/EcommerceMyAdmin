<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_tutte_le_categorie")) { ?>
<li class="<?php echo tm($tm, "categories");?>"><a href="<?php echo $this->baseUrl."/categories/main/1";?>"><i class="fa fa-folder-open"></i> <span><?php echo gtext("Sezioni sito");?></span></a></li>
<?php } ?>
