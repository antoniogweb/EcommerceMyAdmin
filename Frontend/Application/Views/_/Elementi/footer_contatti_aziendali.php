<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li>
	<div class="uk-text-muted"><span class="uk-margin-small-right " uk-icon="receiver"></span><span class="tm-pseudo"><?php echo v("telefono_aziendale");?></span></div>
</li>
<li>
	<a class="uk-link-muted" href="mailto:<?php echo v("email_aziendale");?>"><span class="uk-margin-small-right" uk-icon="mail"></span><span class="tm-pseudo"><?php echo v("email_aziendale");?></span></a>
</li>
<li>
	<div class="uk-text-muted"><span class="uk-margin-small-right" uk-icon="location"></span><span><?php echo v("indirizzo_aziendale");?></span></div>
</li>
