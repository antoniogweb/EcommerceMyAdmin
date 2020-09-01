<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div style="display:none;" class="dati_variante">
	<span class="id_combinazione">0</span>
	<span class="codice_combinazione"></span>
	<span class="prezzo_combinazione"></span>
	<span class="immagine_combinazione"></span>
	<span class="prezzo_pieno_combinazione"></span>
</div>
<div style="display:none;" class="main_price"><?php echo setPriceReverse(calcolaPrezzoFinale($el["pages"]["id_page"], $el["pages"]["price"]));?></div>
<div style="display:none;" class="main_immagine"><?php echo $firstImage;?></div>
<div style="display:none;" class="main_codice"><?php echo $el["pages"]["codice"];?></div>
<div style="display:none;" class="main_price_pieno"><?php echo setPriceReverse(calcolaPrezzoIvato($el["pages"]["id_page"], $el["pages"]["price"]));?></div>
