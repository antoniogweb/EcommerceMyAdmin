<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") {
	$spedizione = SpedizioninegozioModel::g()->left(array("spedizioniere"))->whereId((int)$id)->first();
	$ordini = SpedizioninegozioModel::g()->getOrdini((int)$id);
	$stile = SpedizioninegozioModel::g()->getStile($spedizione["spedizioni_negozio"]["stato"]);
	$titoloStato = SpedizioninegozioModel::g()->getTitoloStato($spedizione["spedizioni_negozio"]["stato"]);
// 	$listaRegalo = ListeregaloModel::g()->whereId((int)$spedizione["spedizioni_negozio"]["id_lista_regalo"])->record();
?>

<div class="box box-widget">
	<div class="box-body">
		<div class="row">
			<div class="col-lg-6">
				<table class="table table-striped">
					<tr>
						<td><?php echo gtext("Stato");?>:</td>
						<td><span style="<?php echo $stile;?>" class="label label-default"><?php echo $titoloStato;?></span></td>
					</tr>
					<tr>
						<td><?php echo gtext("ID Spedizione");?>:</td>
						<td><b><?php echo $spedizione["spedizioni_negozio"]["id_spedizione_negozio"];?></b></td>
					</tr>
					<?php if ($spedizione["spedizioni_negozio"]["numero_spedizione"]) { ?>
					<tr>
						<td><?php echo gtext("Numero spedizione corriere");?>:</td>
						<td><b class="label label-info"><?php echo $spedizione["spedizioni_negozio"]["numero_spedizione"];?></b></td>
					</tr>
					<?php } ?>
					<?php if (count($ordini) > 0) { ?>
					<tr>
						<td><?php echo gtext("Ordini");?>:</td>
						<td>
							<?php foreach ($ordini as $idO) { ?>
							<a class="label label-default" target="_blank" href="<?php echo $this->baseUrl."/ordini/vedi/".(int)$idO;?>">#<?php echo (int)$idO;?></a>
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
					<?php if ($spedizione["spedizioni_negozio"]["id_lista_regalo"]) { ?>
					<tr>
						<td><?php echo gtext("Lista regalo");?>:</td>
						<td>
							<?php echo ListeregaloModel::specchietto($spedizione["spedizioni_negozio"]["id_lista_regalo"]);?>
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<div class="col-lg-6">
				<?php if (SpedizioninegozioModel::aperto($id)) { ?>
				<a href="<?php echo $this->baseUrl."/spedizioninegozio/prontadainviare/".(int)$id."?partial=".$this->viewArgs["partial"];?>" class="btn btn-xs btn-warning make_spinner"><i class="fa fa-lock"></i> <?php echo gtext("Imposta la spedizione come pronta per l'invio a")?> <?php echo SpedizionieriModel::g(false)->titolo($spedizione["spedizioni_negozio"]["id_spedizioniere"]);?></a>
				<?php } else if (SpedizioninegozioModel::pronta($id)) {?>
				<a href="<?php echo $this->baseUrl."/spedizioninegozio/apri/".(int)$id."?partial=".$this->viewArgs["partial"];?>" class="btn btn-xs btn-default make_spinner"><i class="fa fa-unlock"></i> <?php echo gtext("Porta nuovamente allo stato aperto")?></a>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php if ($spedizione["spedizioni_negozio"]["errore_invio"]) { ?>
<div class="alert alert-danger">
	<i class="fa fa-exclamation-triangle"></i> <i><?php echo gtext("Errori invio");?>: <?php echo sanitizeHtml($spedizione["spedizioni_negozio"]["errore_invio"]);?></i>
</div>
<?php } ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<?php if (SpedizioninegozioModel::legataAdOrdineOLista((int)$id)) { ?>
	<li <?php echo $posizioni['righe'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/righe/$id".$this->viewStatus;?>"><?php echo gtext("Righe ordine");?></a></li>
	<?php } ?>
	<li <?php echo $posizioni['eventi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/eventi/$id".$this->viewStatus;?>"><?php echo gtext("Cronologia eventi");?></a></li>
</ul>

<div style="clear:left;"></div>
<?php } ?>
