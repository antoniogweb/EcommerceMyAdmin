<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php
if (!isset($baseUrl))
	$baseUrl = $this->baseUrl."/";
?>
<?php if (strcmp($tipoOutput,"mail_al_negozio") === 0 || strcmp($tipoOutput,"mail_al_cliente") === 0) { ?>
<h1><?php echo gtext("Resoconto dell'ordine");?></h1>
<?php } ?>

<?php if (strcmp($tipoOutput,"web") === 0) { ?>
<!--<div class="for_print">
	<a href="#" class="stampa_pagina">Stampa</a>
</div>-->
<?php } ?>

<?php include(tpf("Elementi/Ordini/resoconto_top.php"));?>


<?php
$idListaRegalo = $ordine["id_lista_regalo"];
include(tpf(ElementitemaModel::p("AVVISO_LISTA_SELEZIONATA","", array(
	"titolo"	=>	"Avviso quando hai una lista selezionata",
	"percorso"	=>	"Elementi/ListaRegalo/AvvisoCarrelloCheckout",
))));
?>

<?php include(tpf("Elementi/Ordini/resoconto_acquisto_dettagli_generali.php"));?>

<?php include(tpf("Elementi/Ordini/resoconto_pagamento_top.php"));?>

<?php include(tpf("Ordini/resoconto_pagamento.php"));?>

<h2 class="uk-heading-bullet"><?php echo gtext("Dettagli ordine", false); ?>:</h2>

<?php include(tpf("Ordini/resoconto_prodotti.php"));?>

<?php
include(tpf(ElementitemaModel::p("RESOCONTO_TOTALI","", array(
	"titolo"	=>	"Totali ordine",
	"percorso"	=>	"Elementi/Ordini/Resoconto/Totali",
))));
?>

<?php
include(tpf(ElementitemaModel::p("RESOCONTO_REGALO","", array(
	"titolo"	=>	"Dedica e firma nel resoconto dell'ordine",
	"percorso"	=>	"Elementi/Ordini/Resoconto/Regalo",
))));
?>

<?php if (trim($ordine["note"])) { ?>
<h2 class="uk-heading-bullet"><?php echo gtext("Note d'acquisto");?></h2>
<?php echo nl2br($ordine["note"]);?>
<br /><br />
<?php } ?>

<h2 class="uk-heading-bullet"><?php echo gtext("Dati di fatturazione", false); ?></h2>

<?php
include(tpf(ElementitemaModel::p("RESOCONTO_FATTURAZIONE","", array(
	"titolo"	=>	"Dati di fatturazione nel resoconto dell'ordine",
	"percorso"	=>	"Elementi/Ordini/Resoconto/Fatturazione",
))));
?>

<?php if ($ordine["da_spedire"] && ($ordine["indirizzo_spedizione"] || $ordine["citta_spedizione"])) { ?>
<h2 class="uk-heading-bullet"><?php echo gtext("Dati di spedizione", false); ?></h2>

<?php
include(tpf(ElementitemaModel::p("RESOCONTO_SPEDIZIONE","", array(
	"titolo"	=>	"Dati di fatturazione nel resoconto dell'ordine",
	"percorso"	=>	"Elementi/Ordini/Resoconto/Spedizione",
))));
?>
<?php } ?>
<br /><br />
<?php if (strcmp($tipoOutput,"mail_al_cliente") === 0 ) { ?>
<p><?php echo gtext("Può controllare in qualsiasi momento i dettagli dell'ordine al", false); ?> <a href="<?php echo $baseUrl."resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]."/".$ordine["admin_token"];?>?n=y"><?php echo gtext("seguente indirizzo web", false); ?></a>.</p>
<?php } ?>

<?php
if (isset($isFromAreariservata))
	include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
?> 
