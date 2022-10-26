<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_liste_regalo") && ((int)User::$idLista || isset($idListaRegalo))) { ?>
<?php
$idLista = isset($idListaRegalo) ? (int)$idListaRegalo : (int)User::$idLista;

$lista = ListeregaloModel::listeUtenteModel(0, $idLista)->record();?>
<div class="uk-panel uk-background-muted uk-padding-small uk-position-relative uk-margin-medium-bottom">
	<?php if (!isset($ordine)) { ?>
	<a title="<?php echo gtext("Disattiva l'acquisto nella lista.");?>"  class="disattiva_acquisto_lista uk-position-small uk-position-top-right uk-text-danger uk-flex uk-flex-middle uk-text-small" href="#"><span uk-icon="icon: close; ratio: 1"></span></a>
	<?php } ?>
	<dl class="uk-description-list uk-text-left uk-margin-remove-top">
		<dt class="uk-text-danger">
			<?php if (isset($idListaRegalo)) { ?>
			<?php echo gtext("Attenzione: hai acquistato i seguenti prodotti partendo dalla lista");?>
			<?php } else { ?>
			<?php echo gtext("Attenzione: hai inserito nel carrello i prodotti partendo dalla lista");?>
			<?php } ?>
			<b><?php echo $lista["titolo"];?></b>
		</dt>
		<dd class="uk-text-small"><?php echo gtext("I prodotti acquistati verranno automaticamente consegnati al titolare della lista suddetta.")?></dd>
		<dd class="uk-text-small"><?php echo gtext("Se si desidera consegnare personalmente i regali, si prega di passare a ritirarli in negozio.")?></dd>
	</dl>
</div>
<?php } ?>
