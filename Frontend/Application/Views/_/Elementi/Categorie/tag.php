<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php
$urlAlias = TagModel::getUrlAlias($p["tag"]["id_tag"]);
?>

<div class="uk-margin-medium-bottom">
	<a href="<?php echo $this->baseUrl."/".$urlAlias;?>" class="uk-card"><img src="<?php echo $this->baseUrlSrc."/thumb/tag/".$p["tag"]["immagine"];?>" alt="<?php echo altUrlencode(tagfield($p, "titolo"));?>" /></a>
	<h2 class="uk-text-default uk-margin-small-top"><a href="<?php echo $this->baseUrl."/".$urlAlias;?>"><?php echo tagfield($p, "titolo");?></a></h2>
</div>

