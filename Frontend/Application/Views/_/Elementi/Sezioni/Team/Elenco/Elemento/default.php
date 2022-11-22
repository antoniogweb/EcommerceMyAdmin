<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div>
	<div class="uk-flex uk-flex-center"><img class="radius-small" src="<?php echo $this->baseUrlSrc."/thumb/team/".$p["pages"]["immagine"];?>" alt="<?php echo altUrlencode(field($p, "title"));?>"/></div>
	<div class="uk-margin-small uk-text-center uk-text-primary"><?php echo field($p, "title");?></div>
</div>
