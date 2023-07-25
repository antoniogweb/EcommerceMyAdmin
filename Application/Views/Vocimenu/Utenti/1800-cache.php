<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (defined("CACHE_FOLDER") || defined("SAVE_CACHE_HTML") || v("attiva_cache_immagini") || defined("CACHE_METHODS_TO_FILE") || v("attiva_interfaccia_opcache")) { ?>
<li class="treeview">
	<a href="#">
		<i class="fa fa-bar-chart"></i>
		<span><?php echo gtext("Gestione cache")?></span>
	</a>
	<ul class="treeview-menu">
		<?php if (defined("CACHE_FOLDER")) { ?>
		<li><a class="svuota_cache" href="<?php echo $this->baseUrl."/cache/svuotacache";?>"><i class="fa fa-trash"></i> <?php echo gtext("Svuota cache database");?></a></li>
		<?php } ?>
		<?php if (defined("SAVE_CACHE_HTML")) { ?>
		<li><a class="svuota_cache" href="<?php echo $this->baseUrl."/cache/svuotacachetemplate";?>"><i class="fa fa-trash"></i> <?php echo gtext("Svuota cache HTML");?></a></li>
		<?php } ?>
		<?php if (v("attiva_cache_immagini")) { ?>
		<li><a class="svuota_cache" href="<?php echo $this->baseUrl."/cache/svuotacacheimmagini";?>"><i class="fa fa-trash"></i> <?php echo gtext("Svuota cache immagini");?></a></li>
		<?php } ?>
		<?php if (defined("CACHE_METHODS_TO_FILE")) { ?>
		<li><a class="svuota_cache" href="<?php echo $this->baseUrl."/cache/svuotacachemetodi";?>"><i class="fa fa-trash"></i> <?php echo gtext("Svuota cache metodi");?></a></li>
		<?php } ?>
		<?php if (v("attiva_interfaccia_opcache")) { ?>
		<li><a class="iframe" href="<?php echo $this->baseUrl."/opcache/index";?>"><i class="fa fa-area-chart"></i> <span><?php echo gtext("Statistiche OPcache");?></span></a></li>
		<?php } ?>
	</ul>
</li>
<?php } ?>
