<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (!isset($id_categoria))
	$id_categoria = 0;

if (!isset($idMarchio))
	$idMarchio = 0;

if (!isset($idTag))
	$idTag = 0;
?>
<div id="filtri-categoria" <?php if (User::$isMobile) { ?>uk-offcanvas<?php } ?> class="uk-width-1-4 uk-padding-remove uk-margin-remove <?php if (!User::$isMobile) { ?>uk-overflow-auto<?php } ?>" uk-accordion="multiple: true; targets: &gt; .js-accordion-section" style="flex-basis: auto">
	<?php if (User::$isMobile) { ?>
	<div class="uk-offcanvas-bar">
	<div class="uk-margin-large-bottom">
		<button class="uk-offcanvas-close" type="button" uk-close></button>
	</div>
	<?php } ?>
	<?php if (count($elencoMarchiFull) > 0) { ?>
	<section class="js-accordion-section uk-open">
		<h4 class="uk-accordion-title uk-margin-remove"><?php echo gtext("Marchio")?></h4>
		<div class="uk-accordion-content">
			<ul class="uk-list uk-list-divider">
				<li class="cat-item cat-item-49 <?php if ($idMarchio == 0) { ?>uk-text-bold<?php } ?>">
					<a class="uk-text-meta uk-text-xsmall" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, 0, $id_categoria);?>"><?php echo gtext("Tutti");?></a>
				</li>
				<?php foreach ($elencoMarchiFull as $m) { ?>
				<li class="<?php if ($m["marchi"]["id_marchio"] == $idMarchio) { ?>uk-text-bold<?php } ?>">
					<a class="uk-text-meta uk-text-xsmall" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $m["marchi"]["id_marchio"], $id_categoria);?>">
						<?php echo mfield($m,"titolo");?>
					</a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</section>
	<?php } ?>
	
	<?php if (count($elencoTagFull) > 0) { ?>
	<section class="uk-margin-large-top js-accordion-section uk-open">
		<h4 class="uk-accordion-title uk-margin-remove"><?php echo gtext("Linea")?></h4>
		<div class="uk-accordion-content">
			<ul class="uk-list uk-list-divider">
				<li class="cat-item cat-item-49 <?php if ($idTag == 0) { ?>uk-text-bold<?php } ?>">
					<a class="uk-text-meta uk-text-xsmall" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio(0, $idMarchio, $id_categoria);?>"><?php echo gtext("Tutti");?></a>
				</li>
				<?php foreach ($elencoTagFull as $m) { ?>
				<li class="<?php if ($m["tag"]["id_tag"] == $idTag) { ?>uk-text-bold<?php } ?>">
					<a class="uk-text-meta uk-text-xsmall" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($m["tag"]["id_tag"], $idMarchio, $id_categoria);?>">
						<?php echo tagfield($m,"titolo");?>
					</a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</section>
	<?php } ?>
	
	<section class="uk-margin-large-top js-accordion-section uk-open">
		<h4 class="uk-accordion-title uk-margin-remove"><?php echo gtext("Categoria")?></h4>
		<div class="uk-accordion-content">
			<ul class="uk-list uk-list-divider">
				<li class="<?php if ($datiCategoria["categories"]["id_c"] == $idShop) { ?>uk-text-bold<?php } ?>">
					<a class="uk-text-meta uk-text-xsmall" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $idShop);?>"><?php echo gtext("Tutti");?></a>
				</li>
				<?php foreach ($elencoCategorieFull as $c) {
					$figlie = categorieFiglie($c["categories"]["id_c"]);
					$figlieIds = CategoriesModel::resultToIdList($figlie);
				?>
				<li class="<?php if ($datiCategoria["categories"]["id_c"] == $c["categories"]["id_c"]) { ?>uk-text-bold<?php } ?>" <?php if (count($figlie) > 0) { ?>uk-accordion<?php } ?>>
					<?php if (count($figlie) > 0) { ?>
						<div class="<?php if (in_array($id_categoria,$figlieIds)) { ?>uk-open<?php } else { ?>uk-close<?php } ?>">
					<?php } ?>
						<a class="uk-text-meta uk-text-xsmall <?php if (count($figlie) > 0) { ?>uk-accordion-title"<?php } ?>" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $c["categories"]["id_c"]);?>">
							<?php echo cfield($c, "title");?>
						</a>
						<?php if (count($figlie) > 0) { ?>
						<ul class='uk-list uk-margin-left uk-accordion-content'>
							<?php foreach ($figlie as $fg) { ?>
							<li class="<?php if ($datiCategoria["categories"]["id_c"] == $fg["categories"]["id_c"]) { ?>uk-text-bold<?php } ?>">
								<a class="uk-text-meta uk-text-xsmall" href="<?php echo $this->baseUrl."/".CategoriesModel::getUrlAliasTagMarchio($idTag, $idMarchio, $fg["categories"]["id_c"]);?>">
									<?php echo cfield($fg, "title");?>
								</a>
							</li>
							<?php } ?>
						</ul>
						<?php } ?>
					<?php if (count($figlie) > 0) { ?></div><?php } ?>
				</li>
				<?php } ?>
			</ul>
		</div>
	</section>
	<?php if (User::$isMobile) { ?></div><?php } ?>
</div>
