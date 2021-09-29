<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php
$urlAlias = TagModel::getUrlAlias($p["tag"]["id_tag"]);
?>

<article class="uk-transition-toggle">
	<a href="<?php echo $this->baseUrl."/".$urlAlias;?>">
		<img src="<?php echo $this->baseUrlSrc."/thumb/tag/".$p["tag"]["immagine"];?>" alt="<?php echo urlencode(tagfield($p, "titolo"));?>" />
	</a>
</article>

