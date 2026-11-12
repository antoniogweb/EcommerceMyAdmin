<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Totale");?>:</span> <?php echo setPriceReverse(($p["cart"]["quantity"] * $prezzoUnitario) + $prezzoUnitarioFisso);?> €
