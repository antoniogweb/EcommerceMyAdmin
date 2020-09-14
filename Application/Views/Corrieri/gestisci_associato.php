<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "prezzi") { ?>

<?php
$nazioneTrovata = false;
foreach ($elencoNazioniCorrieri as $codice) {
	if ($this->viewArgs["nazione"] == $codice)
		$nazioneTrovata = true;
	$temp = $this->viewArgs;
	$temp["nazione"] = $codice;
?>
<a style="margin-left:5px;" href="<?php echo $this->baseUrl."/corrieri/prezzi/$id".Url::createUrl($temp);?>" class="btn btn-<?php echo ($this->viewArgs["nazione"] == $codice) ? "primary" : "default";?> pull-right">
	<?php if ($codice != "W") { ?>
		<?php echo findTitoloDaCodice($codice, "Tutte le nazioni");?>
	<?php } else { ?>
		<?php echo "Tutte le nazioni";?>
	<?php } ?>
</a>
<?php } ?>

<p>
	<a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/corrierispese/form/insert";?>?partial=Y&nobuttons=Y&id_corriere=<?php echo $id;?>">Aggiungi NAZIONE</a>
	
	<?php if ($nazioneTrovata) { ?>
	<a class="iframe btn btn-primary" href="<?php echo $this->baseUrl."/corrierispese/form/insert";?>?partial=Y&nobuttons=Y&id_corriere=<?php echo $id;?>&nazione=<?php echo $this->viewArgs["nazione"];?>">Aggiungi scaglione <?php echo strtoupper(findTitoloDaCodice($this->viewArgs["nazione"], "Tutte le nazioni"));?></a>
	<?php } ?>
</p>

<?php } ?>
