<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$classeDivPulsanteAggiungiAllaLista = isset($classeDivPulsanteAggiungiAllaLista) ? $classeDivPulsanteAggiungiAllaLista : "uk-margin-small uk-width-1-1 uk-width-2-3@m";
$classePulsanteAggiungiAllaLista = isset($classePulsanteAggiungiAllaLista) ? $classePulsanteAggiungiAllaLista : "uk-width-1-1 uk-button uk-button-primary";
$TestoPulsanteAggiungiAllaLista = isset($TestoPulsanteAggiungiAllaLista) ? $TestoPulsanteAggiungiAllaLista : "Aggiungi alla lista regalo";
?>

<div class="<?php echo $classeDivPulsanteAggiungiAllaLista;?>">
	<div class="<?php echo $classePulsanteAggiungiAllaLista;?> spinner uk-hidden" uk-spinner="ratio: .70"></div>
	<a id-c="<?php echo PagesModel::$IdCombinazione?>" id-page="<?php echo $p["pages"]["id_page"];?>" class="<?php echo $classePulsanteAggiungiAllaLista;?> <?php if ($aggiuntaDiretta) { ?>aggiungi_alla_lista pulsante_lista<?php } ?>" <?php if (!$aggiuntaDiretta) { ?>uk-toggle="target: #modale-scelta-lista"<?php } ?> href="#">
		<span>
			<?php echo $TestoPulsanteAggiungiAllaLista; ?>
		</span>
	</a>
</div>
