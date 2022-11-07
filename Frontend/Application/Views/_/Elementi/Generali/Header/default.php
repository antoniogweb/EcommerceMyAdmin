<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!partial()) { ?>
<header>
	<?php include(tpf("/Elementi/header_fascia_demo.php"));?>
	<div class="">
		<div class="uk-navbar-container tm-toolbar-container uk-navbar-transparent">
			<div class="uk-container top_nav" uk-navbar>
				<div class="uk-navbar-left uk-visible@s">
					<nav>
						<ul class="uk-text-small uk-navbar-nav">
							<li>
								<span class="uk-margin-small-right" uk-icon="icon: receiver; ratio: .75;"></span><span class="tm-pseudo"><?php echo v("telefono_aziendale");?></span>
							</li>
						</ul>
					</nav>
				</div>
				<?php if (isset($avvisi) && count($avvisi) > 0) { ?>
				<div class="uk-navbar-right">
					<nav>
						<div class="uk-slider-container uk-position-relative" tabindex="-1" uk-slider="autoplay: true">
							<ul class="uk-text-small uk-slider-items uk-child-width-1-1@s uk-child-width-1-1@">
								<?php foreach ($avvisi as $avv) { ?>
								<li class="uk-text-center uk-text-small"><?php echo htmlentitydecode(field($avv, "description"));?></li>
								<?php } ?>
							</ul>
						</div>
					</nav>
				</div>
				<?php } ?>
				<div class="uk-navbar-right uk-visible@s">
					<nav>
						<ul class="uk-navbar-nav">
							<?php include(tpf("/Elementi/social_list.php"));?>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
	<div class=" uk-navbar-container es-navbar-container" uk-sticky="cls-active: tm-navbar-container-fixed">
		<div class="uk-container" uk-navbar>
			<div class="uk-navbar-left">
				<button class="uk-navbar-toggle uk-hidden@m" uk-toggle="target: #nav-offcanvas" uk-navbar-toggle-icon></button><a class="uk-navbar-item uk-logo" href="<?php echo $this->baseUrl;?>"><?php echo i("__LOGO__");?></a>
			</div>
			<div class="uk-navbar-center">
				<nav class="uk-visible@m">
					<ul class="uk-navbar-nav">
						<?php echo $menu;?>
					</ul>
				</nav>
			</div>
			<div class="uk-navbar-right">
				<?php
				include(tpf(ElementitemaModel::p("HEADER_ICONE_DX","", array(
					"titolo"	=>	"Icone sulla destra nell'header",
					"percorso"	=>	"Elementi/Generali/HeaderIconeUserCarrello",
				))));
				?>
			</div>
		</div>
	</div>
</header>
<?php } ?>
 
