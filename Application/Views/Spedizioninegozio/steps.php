<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") {
	$spedizione = SpedizioninegozioModel::g()->left(array("spedizioniere"))->whereId((int)$id)->first();
	$ordini = SpedizioninegozioModel::g()->getOrdini((int)$id);
	$stile = SpedizioninegozioModel::g()->getStile($spedizione["spedizioni_negozio"]["stato"]);
	$titoloStato = SpedizioninegozioModel::g()->getTitoloStato($spedizione["spedizioni_negozio"]["stato"]);
?>

<div class="info-box">
	<span class="info-box-icon" style="<?php echo $stile;?>"><i class="fa fa-truck"></i></span>
	<div class="info-box-content">
		<?php if (SpedizioninegozioModel::aperto($id)) { ?>
		<a href="<?php echo $this->baseUrl."/spedizioninegozio/prontadainviare/".(int)$id."?partial=".$this->viewArgs["partial"];?>" class="btn btn-warning pull-right make_spinner"><i class="fa fa-lock"></i> <?php echo gtext("Imposta la spedizione come pronta per l'invio a")?> <?php echo SpedizionieriModel::g(false)->titolo($spedizione["spedizioni_negozio"]["id_spedizioniere"]);?></a>
		<?php } else if (SpedizioninegozioModel::pronta($id)) {?>
		<a href="<?php echo $this->baseUrl."/spedizioninegozio/apri/".(int)$id."?partial=".$this->viewArgs["partial"];?>" class="btn btn-default pull-right make_spinner"><i class="fa fa-unlock"></i> <?php echo gtext("Porta nuovamente allo stato aperto")?></a>
		<?php } ?>
		
		<span class="info-box-text">
			<?php echo gtext("Stato");?>: <span style="<?php echo $stile;?>" class="label label-default"><?php echo $titoloStato;?></span> - <?php echo gtext("ID Spedizione");?>: <b><?php echo $spedizione["spedizioni_negozio"]["id_spedizione_negozio"];?></b>
		</span>
		<span class="info-box-text">
			
			<?php if ($spedizione["spedizioni_negozio"]["numero_spedizione"]) { ?>
			<?php echo gtext("Numero spedizione corriere");?>: <b class="label label-info"><?php echo $spedizione["spedizioni_negozio"]["numero_spedizione"];?></b><br />
			<?php } ?>
			
			<?php echo gtext("Ordine");?>: 
			<?php foreach ($ordini as $idO) { ?>
			<a class="label label-default" target="_blank" href="<?php echo $this->baseUrl."/ordini/vedi/".(int)$idO;?>">#<?php echo (int)$idO;?></a>
			<?php } ?>
		</span>
		<span class="info-box-text"></span>
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
