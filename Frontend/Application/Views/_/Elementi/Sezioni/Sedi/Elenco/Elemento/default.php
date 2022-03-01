<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$urlAlias = getUrlAlias($p["pages"]["id_page"]);
$urlAliasCategoria = getCategoryUrlAlias($p["categories"]["id_c"]);
?>
<div class="uk-card uk-card-default uk-card-small" style="box-shadow: none;">
	<div class="uk-card-header">
		<h6 class="uk-margin-remove-bottom uk-text-bold"><a class="uk-link uk-text-secondary" href="<?php echo $this->baseUrl."/$urlAliasCategoria";?>"><?php echo cfield($p, "title");?></a></h6>
		<p class="uk-text-meta uk-margin-remove uk-text-small"><time datetime="<?php echo date("Y-m-d H:i:s", strtotime($p["pages"]["data_news"]));?>"><?php echo traduci(date("d M Y", strtotime($p["pages"]["data_news"])));?></time></p>
	</div>
	<div class="uk-card-body">
		<h4 class="uk-margin-small-bottom uk-text-bold"><?php echo field($p, "title");?></h4>
	</div>
</div>
