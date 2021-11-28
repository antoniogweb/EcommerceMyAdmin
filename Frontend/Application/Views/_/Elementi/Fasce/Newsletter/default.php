<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="newsletter-form" class="uk-section uk-section-muted">
	<div class="uk-container">
		<div class="uk-margin-medium-bottom">
			<h2 class="uk-text-center uk-text-bold uk-margin-remove-top uk-margin-remove-bottom"><span><?php echo t("Iscriviti alla newsletter"); ?></span></h2>
			<div class="uk-child-width-1-3@m uk-text-center" uk-grid>
				<div></div>
				<div>
					<div class="uk-text-small"><?php echo t("Lorem ipsum dolor newsletter.");?></div>
				</div>
				<div></div>
			</div>
		</div>
		<?php include(tpf("Elementi/Pagine/form_newsletter.php"));?>
	</div>
</div> 
