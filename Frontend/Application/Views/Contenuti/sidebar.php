<?php if (!defined('EG')) die('Direct access not allowed!'); ?>


<div class="sidebar">

	<div class="widget widget_recent_entries">
		<h3><?php echo gtext("Articoli recenti");?></h3>
		<div class="widget-line"></div>

		<ul>
		<?php foreach ($ultimiArticoli as $p) { ?>

		<li><?php $urlAlias = getUrlAlias($p["pages"]["id_page"]); ?>
			<a href="<?php echo $this->baseUrl."/".$urlAlias;?>"><?php echo $p["pages"]["title"];?></a>
		</li>

		<?php } ?>
		</ul>
	</div>

	<div class="widget widget_categories">
		<?php if (count($categorieBlog) > 0) { ?>
		<h3><?php echo gtext("Categorie");?></h3>
		<div class="widget-line"></div>
		<ul>
			<?php foreach ($categorieBlog as $cat) {
				$urlAliasCategoria = getCategoryUrlAlias($cat["categories"]["id_c"]);
			?>
				<li><a href="<?php echo $this->baseUrl."/$urlAliasCategoria"?>"><?php echo $cat["categories"]["title"];?></a></li>
			<?php } ?>
		</ul>
		<?php } ?>
	</div>

	
</div>
