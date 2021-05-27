<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="nav-offcanvas" uk-offcanvas="overlay: true">
	<aside class="uk-offcanvas-bar uk-padding-remove">
		<div class="uk-card uk-card-small tm-shadow-remove">
			<header class="uk-card-header uk-flex uk-flex-middle">
				<button class="uk-offcanvas-close" type="button" uk-close></button>
				<div>
					<div class="uk-margin-medium"><a href="<?php echo $this->baseUrl;?>"><?php echo i("__LOGO__");?></a></div>
					<div class="uk-text-muted uk-text-bold"><span class="uk-margin-small-right" uk-icon="receiver"></span> <?php echo v("telefono_aziendale");?></div>
					<div class="uk-text-xsmall uk-text-muted">
						<span class="uk-margin-small-right" uk-icon="location"></span><span><?php echo v("indirizzo_aziendale");?>
					</div>
				</div>
			</header>
			<nav class="uk-card-small uk-card-body">
				<?php
				if (!isset($id_categoria))
					$id_categoria = 0;
				?>
				<ul class="uk-nav-default uk-nav-parent-icon uk-list-divider" uk-nav>
					<li class="<?php if ($id_categoria == $idShop) { ?>uk-active<?php } ?>">
						<a href="<?php echo $this->baseUrl."/".getCategoryUrlAlias($idShop);?>"><?php echo gtext("Shop");?></a>
					</li>
					<?php foreach ($elencoCategorieFull as $c) {
						$figlie = categorieFiglie($c["categories"]["id_c"]);
						$figlieIds = CategoriesModel::resultToIdList($figlie);
					?>
					<li class="<?php if (count($figlie) > 0) { ?>uk-parent <?php if (in_array($id_categoria,$figlieIds)) { ?>uk-open<?php } ?><?php } ?> <?php if ($id_categoria == $c["categories"]["id_c"]) { ?>uk-active<?php } ?>">
						<a href="<?php echo $this->baseUrl."/".getCategoryUrlAlias($c["categories"]["id_c"]);?>"><?php echo cfield($c, "title");?></a>
						<?php if (count($figlie) > 0) { ?>
							<ul class="uk-nav-sub uk-list-divider">
								<?php foreach ($figlie as $fg) { ?>
								<li class="<?php if ($id_categoria == $fg["categories"]["id_c"]) { ?>uk-active<?php } ?>"><a href="<?php echo $this->baseUrl."/".getCategoryUrlAlias($fg["categories"]["id_c"]);?>"><?php echo cfield($fg, "title");?></a></li>
								<?php } ?>
							</ul>
						<?php } ?>
					</li>
					<?php } ?>
				</ul>
			</nav>
			<nav class="uk-card-body">
				<ul class="uk-iconnav uk-flex-center">
					<?php include(tpf("/Elementi/social_list.php"));?>
				</ul>
			</nav>
		</div>
	</aside>
</div>
