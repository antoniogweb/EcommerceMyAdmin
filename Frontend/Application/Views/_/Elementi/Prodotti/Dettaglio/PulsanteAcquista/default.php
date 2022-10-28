<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$classeDivPulsanteAcquista = isset($classeDivPulsanteAcquista) ? $classeDivPulsanteAcquista : "uk-width-1-1 uk-width-2-3@m";
$classePulsanteAcquista = isset($classePulsanteAcquista) ? $classePulsanteAcquista : "uk-width-1-1 uk-button uk-button-secondary";
?>
<?php if (!idCarrelloEsistente() && !ProdottiModel::isGiftCart((int)$p["pages"]["id_page"])) { ?>
<div class="<?php echo $classeDivPulsanteAcquista;?>">
	<div class="<?php echo $classePulsanteAcquista;?> spinner uk-hidden" uk-spinner="ratio: .70"></div>
	<button id="acquista" class="<?php echo $classePulsanteAcquista;?> acquista_prodotto"><?php echo gtext("Acquista ora");?></button>
</div>
<?php } ?>
