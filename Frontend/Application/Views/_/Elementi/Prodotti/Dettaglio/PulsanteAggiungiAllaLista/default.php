<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$classeDivPulsanteAggiungiAllaLista = isset($classeDivPulsanteAggiungiAllaLista) ? $classeDivPulsanteAggiungiAllaLista : "uk-margin-small uk-width-1-1 uk-width-2-3@m";
$classePulsanteAggiungiAllaLista = isset($classePulsanteAggiungiAllaLista) ? $classePulsanteAggiungiAllaLista : "uk-width-1-1 uk-button uk-button-primary";
?>

<div class="<?php echo $classeDivPulsanteAggiungiAllaLista;?>">
	<div class="<?php echo $classePulsanteAggiungiAllaLista;?> spinner uk-hidden" uk-spinner="ratio: .70"></div>
	<a name="add-to-cart" id-c="<?php echo PagesModel::$IdCombinazione?>" id-page="<?php echo $p["pages"]["id_page"];?>" class="<?php echo $classePulsanteAggiungiAllaLista;?> aggiungi_alla_lista pulsante_lista single_add_to_cart_button" href="#">
		<span>
			<?php echo gtext("Aggiungi alla lista regalo", false); ?>
		</span>
	</a>
</div>
