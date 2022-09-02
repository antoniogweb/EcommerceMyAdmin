<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="uk-section uk-section-small">
	<div class="uk-container">
		<div class="uk-text-center">
			<div class="" uk-grid>
				<div class="uk-width-expand">
					<?php if (count($pages) > 0) { ?>
						<div class="uk-card-small uk-grid-column uk-child-width-1-3@s uk-text-center" uk-grid>
							<?php foreach ($pages as $p) {
								include(tpf($itemFile));
							} ?>
						</div>
						<?php if (isset($pageList) && isset($rowNumber) && isset($elementsPerPage)) { ?>
							<?php if ($rowNumber > $elementsPerPage) { ?>
							<ul class="uk-pagination uk-flex-right uk-margin-medium-top">
								<?php echo $pageList;?>
							</ul>
							<?php } ?>
						<?php } ?>
					<?php } else { ?>
						<article class="uk-article">
							<p class="uk-article-meta">
								<?php echo isset($descrizioneNoProdotti) ? $descrizioneNoProdotti : gtext("Nessun elemento trovato");?>
							</p>
						</article>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>
 
