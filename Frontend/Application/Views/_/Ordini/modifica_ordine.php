<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if ($islogged)
{
	$breadcrumb = array(
		gtext("Home") 		=> $this->baseUrl,
		gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
		gtext("Ordini effettuati")	=>	$this->baseUrl."/ordini-effettuati",
		gtext("Resoconto Ordine") => $this->baseUrl."/resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]."?n=y",
		gtext("Modifica ordine")." ".$ordine["id_o"] => "",
	);
}
else
{
	$breadcrumb = array(
		gtext("Home") 		=> $this->baseUrl,
		gtext("Resoconto Ordine") => $this->baseUrl."/resoconto-acquisto/".$ordine["id_o"]."/".$ordine["cart_uid"]."?n=y",
		gtext("Modifica ordine")." ".$ordine["id_o"] => "",
	);
}

$titoloPagina = gtext("Modifica dell'ordine")." ".$ordine["id_o"];

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "ordini";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>

<div class="uk-overflow-auto">
	<h2><?php echo gtext("Dettagli dell'ordine");?>:</h2>
	<table class="uk-table uk-table-divider uk-table-striped uk-table-small">
		<tr>
			<td><?php echo gtext("Ordine", false); ?>:</td>
			<td><b>#<?php echo $ordine["id_o"];?></b></td>
		</tr>
		<tr>
			<td><?php echo gtext("Data", false); ?>:</td>
			<td><b><?php echo smartDate($ordine["data_creazione"]);?></b></td>
		</tr>
		<tr>
			<td><?php echo gtext("Totale", false); ?>:</td>
			<td><b>&euro; <?php echo setPriceReverse($ordine["total"]);?></b></td>
		</tr>
		<tr>
			<td><?php echo gtext("Stato ordine", false); ?>:</td>
			<td><b><?php echo statoOrdine($ordine["stato"]);?></b></td>
		</tr>
	</table>
</div>
<h2 id="form_main"><?php echo gtext("Modifica pagamento e indirizzo di spedizione", false); ?>:</h2>

<?php echo $notice; ?>

<form class="" action="<?php echo $this->baseUrl."/ordini/modifica/".$ordine["id_o"]."/".$ordine["cart_uid"];?>#form_main" method="POST">
	
	<div class="uk-margin">
		<label class="uk-form-label"><?php echo gtext("Metodo di pagamento");?> *</label>
		<div class="uk-form-controls">
			<?php echo Html_Form::select("pagamento",$ordine["pagamento"], OrdiniModel::$pagamenti, "uk-select class_pagamento", null, "yes");?>
		</div>
	</div>
	
	<div class="uk-margin">
		<label class="uk-form-label"><?php echo gtext("Indirizzo di spedizione");?> *</label>
		<div class="uk-form-controls">
			<?php echo Html_Form::select("id_spedizione",$ordine["id_spedizione"], $tendinaIndirizzi, "uk-select class_id_spedizione", null, "yes");?>
		</div>
	</div>
	
	<input class="uk-button uk-button-primary" type="submit" name="updateAction" value="<?php echo gtext("Modifica dati", false);?>" />
	
	<a class="uk-button uk-button-default" href="<?php echo $this->baseUrl."/gestisci-spedizione/0?cart_uid=".$ordine["cart_uid"];?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi un nuovo indirizzo");?></a>
</form>

<?php
if (isset($islogged))
	include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
?>
