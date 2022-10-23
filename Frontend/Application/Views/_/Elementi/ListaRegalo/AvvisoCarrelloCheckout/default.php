<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_liste_regalo") && ((int)User::$idLista || isset($idListaRegalo))) { ?>
<?php
$idLista = isset($idListaRegalo) ? (int)$idListaRegalo : (int)User::$idLista;

$lista = ListeregaloModel::listeUtenteModel(0, $idLista)->record();?>
<dl class="uk-description-list uk-text-left uk-margin-medium-bottom">
    <dt class="uk-text-danger">
		<?php if (isset($idListaRegalo)) { ?>
		<?php echo gtext("Attenzione: hai acquistato i seguenti prodotti partendo dalla lista");?>
		<?php } else { ?>
		<?php echo gtext("Attenzione: hai inserito nel carrello i prodotti partendo dalla lista");?>
		<?php } ?>
		<b><?php echo $lista["titolo"];?></b>
	</dt>
    <dd><?php echo gtext("I prodotti acquistati verranno consegnati al titolare della lista suddetta.")?></dd>
    <dd><?php echo gtext("Se si desidera consegnare personalmente i regali, si prega di passare a ritirarli in negozio.")?></dd>
</dl>
<?php } ?>
