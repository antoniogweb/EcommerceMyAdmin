<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<ul class="uk-iconnav">
	<?php if (v("facebook_link")) { ?><li><a target="_blank" href="<?php echo v("facebook_link")?>" title="Facebook"><span class="uk-icon uk-text-meta"><?php
	include(tpf(ElementitemaModel::p("ICONA_FACEBOOK","", array(
		"titolo"	=>	"Icona facebook",
		"percorso"	=>	"Elementi/Icone/Facebook",
	))));
	?></span></a></li><?php } ?>
	<?php if (v("twitter_link")) { ?><li><a target="_blank" href="<?php echo v("twitter_link")?>" title="Twitter"><span class="uk-icon uk-text-meta"><?php
	include(tpf(ElementitemaModel::p("ICONA_TWITTER","", array(
		"titolo"	=>	"Icona twitter",
		"percorso"	=>	"Elementi/Icone/Twitter",
	))));
	?></span></a></li><?php } ?>
	<?php if (v("youtube_link")) { ?><li><a target="_blank" href="<?php echo v("youtube_link")?>" title="YouTube" uk-icon="youtube"></a></li><?php } ?>
	<?php if (v("instagram_link")) { ?><li><a target="_blank" href="<?php echo v("instagram_link")?>" title="Instagram" uk-icon="instagram"></a></li><?php } ?>
</ul> 
