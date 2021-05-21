<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "prezzi") { ?>

<a class="iframe btn btn-primary pull-right" href="<?php echo $this->baseUrl."/corrierispese/form/insert";?>?partial=Y&nobuttons=Y&id_corriere=<?php echo $id;?>">Aggiungi NAZIONE</a>

<p>
	<?php
	$nazioneTrovata = false;
	foreach ($elencoNazioniCorrieri as $codice) {
		if ($this->viewArgs["nazione"] == $codice)
			$nazioneTrovata = true;
		$temp = $this->viewArgs;
		$temp["nazione"] = $codice;
	?>
	<a style="margin-right:5px;" href="<?php echo $this->baseUrl."/corrieri/prezzi/$id".Url::createUrl($temp);?>" class="btn btn-<?php echo ($this->viewArgs["nazione"] == $codice) ? "primary" : "default";?>">
		<?php if ($codice != "W") { ?>
			<?php echo findTitoloDaCodice($codice, "Tutte le nazioni");?>
		<?php } else { ?>
			<?php echo "Tutte le nazioni";?>
		<?php } ?>
	</a>
	<?php } ?>

	<?php if ($nazioneTrovata) { ?>
	<a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/corrierispese/form/insert";?>?partial=Y&nobuttons=Y&id_corriere=<?php echo $id;?>&nazione=<?php echo $this->viewArgs["nazione"];?>&procedi=1">Aggiungi scaglione</a>
	<?php } ?>
</p>

<?php } ?>
