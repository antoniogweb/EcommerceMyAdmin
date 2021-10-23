<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!partial()) { ?>
<footer>
	<section class="uk-section uk-section-secondary uk-section-small uk-light">
		<div class="uk-container">
			<div class="uk-grid-medium uk-child-width-1-1 uk-child-width-1-4@m" uk-grid>
				<div>
					<a class="uk-logo" href="<?php echo $this->baseUrl;?>">
						<?php echo i("__LOGO_BLACK__");?>
					</a>
					<p class="uk-text-small"><?php echo t("Testo copy footer");?></p>
					<ul class="uk-iconnav">
						<?php include(tpf("/Elementi/social_list.php"));?>
					</ul>
				</div>
				<!--<div>
					<nav class="uk-grid-small uk-child-width-1-2" uk-grid>-->
						<div>
							<ul class="uk-nav uk-nav-default">
								<?php include(tpf("/Elementi/footer_link_privacy.php"));?>
							</ul>
						</div>
						<div>
							<ul class="uk-nav uk-nav-default">
								<?php include(tpf("/Elementi/footer_link_contenuti.php"));?>
							</ul>
						</div>
					<!--</nav>
				</div>-->
				<div>
					<ul class="uk-list uk-text-small">
						<?php include(tpf("/Elementi/footer_contatti_aziendali.php"));?>
					</ul>
				</div>
			</div>
		</div>
	</section>
</footer>

<?php include(tpf("/Elementi/menu-offcanvas.php"));?>

<div id="cart-offcanvas" uk-offcanvas="overlay: true; flip: true">
	<aside class="uk-offcanvas-bar uk-padding-remove carrello_secondario">
		<?php include(tpf("/Cart/ajax_cart.php"));?>
	</aside>
</div>

<?php } ?>
