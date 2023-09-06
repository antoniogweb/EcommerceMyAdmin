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
		<a href="<?php echo $this->baseUrl."/spedizioninegozio/invia/".(int)$id."?partial=".$this->viewArgs["partial"];?>" class="btn btn-warning pull-right make_spinner"><i class="fa fa-paper-plane-o"></i> <?php echo gtext("Invia a")?> <?php echo SpedizionieriModel::g(false)->titolo($spedizione["spedizioni_negozio"]["id_spedizioniere"]);?></a>
		<?php } ?>
		
		<span class="info-box-text">
			<?php echo gtext("Stato");?>: <span style="<?php echo $stile;?>" class="label label-default"><?php echo $titoloStato;?></span>
		</span>
		<span class="info-box-text">
			<?php echo gtext("ID Spedizione");?>: <b><?php echo $spedizione["spedizioni_negozio"]["id_spedizione_negozio"];?></b>
			<br />
			<?php echo gtext("Ordine");?>: 
			<?php foreach ($ordini as $idO) { ?>
			<a class="label label-default" target="_blank" href="<?php echo $this->baseUrl."/ordini/vedi/".(int)$idO;?>">#<?php echo (int)$idO;?></a>
			<?php } ?>
		</span>
		<span class="info-box-text"></span>
	</div>
</div>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Dettagli");?></a></li>
	<li <?php echo $posizioni['righe'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/righe/$id".$this->viewStatus;?>"><?php echo gtext("Righe ordine");?></a></li>
	<li <?php echo $posizioni['eventi'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/eventi/$id".$this->viewStatus;?>"><?php echo gtext("Cronologia eventi");?></a></li>
</ul>

<div style="clear:left;"></div>
<?php } ?>