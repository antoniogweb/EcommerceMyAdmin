<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<footer>
	<section class="uk-section uk-section-secondary uk-section-small uk-light">
		<div class="uk-container">
			<div class="uk-grid-medium uk-child-width-1-1 uk-child-width-1-4@m" uk-grid>
				<div>
					<a class="uk-logo" href="<?php echo $this->baseUrl;?>">
						<?php echo i("__LOGO_BLACK__");?>
					</a>
					<p class="uk-text-small"><?php echo t("Testo copy footer");?></p>
					<?php include(tpf("/Elementi/social_list.php"));?>
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
