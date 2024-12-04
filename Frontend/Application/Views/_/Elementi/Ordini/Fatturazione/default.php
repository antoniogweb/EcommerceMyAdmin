<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($islogged && !$mostraCampiFatturazione) {
	if (!empty($erroriInvioOrdine))
		User::$dettagli = array_merge(User::$dettagli, $values);
	
	if (!isset($classePulsanteModificaDati))
		$classePulsanteModificaDati = "uk-button uk-button-primary uk-button-small";
	
	include(tpf(ElementitemaModel::p("CHECKOUT_DATI_FATT","", array(
		"titolo"	=>	"Sezione dati di fatturazione in checkout",
		"percorso"	=>	"Elementi/Ordini/DatiFatturazione",
	))));
	?>
	<div class="<?php if (!User::$dettagli["completo"]) { ?>mostra_solo_dati_incompleti<?php } else { ?>uk-hidden<?php } ?>">
		<?php if (!User::$dettagli["completo"]) { ?><span class="uk-text-primary"><?php echo gtext("Si prega di completare i dati di fatturazione");?></span><?php } ?>
		<?php include(tpf("Regusers/form_dati_cliente.php"));?>
	</div>
<?php } else { ?>
	<?php include(tpf("Regusers/form_dati_cliente.php"));?>
<?php } ?>
