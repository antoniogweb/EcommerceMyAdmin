<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-container uk-container-small uk-margin-medium-bottom uk-background-default uk-padding-medium">
    <h3 class="uk-margin-remove uk-text-large uk-text-bold uk-text-center uk-text-uppercase"><?php echo gtext("FAQ");?></h3>

    <ul class="uk-list uk-list-divider" uk-accordion="multiple: true">
		<?php foreach ($pages as $p) { ?>
		<li>
			<a class="uk-accordion-title" href="#"><?php echo field($p, "title");?></a>
			<div class="uk-accordion-content">
				<p><?php echo htmlentitydecode(field($p, "description"));?></p>
			</div>
		</li>
		<?php } ?>
    </ul>
</div> 
