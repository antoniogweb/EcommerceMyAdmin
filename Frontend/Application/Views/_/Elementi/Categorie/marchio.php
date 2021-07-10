<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php
$urlAlias = getMarchioUrlAlias($p["marchi"]["id_marchio"], v("attiva_pagina_produttore"));
?>

<article class="uk-transition-toggle">
	<a href="<?php echo $this->baseUrl."/".$urlAlias;?>"><img src="<?php echo $this->baseUrlSrc."/thumb/famiglia/".$p["marchi"]["immagine"];?>" alt="<?php echo urlencode(mfield($p, "titolo"));?>" /></a>
</article>

