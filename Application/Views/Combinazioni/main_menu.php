<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php $listini = CombinazionilistiniModel::elencoListini();?>
<?php foreach ($listini as $l) {
	$temp = $this->viewArgs;
	$temp["listino"] = $l;
	$titoloListino = $l == "W" ? "Mondo" : findTitoloDaCodice($l);
?>
<a style="margin-left:10px;" href="<?php echo $this->baseUrl."/combinazioni/main".Url::createUrl($temp);?>" class="btn btn-<?php if ($this->viewArgs["listino"] == $l) { ?>info<?php } else { ?>default<?php } ?> pull-right">Listino <?php echo $titoloListino;?></a>
<?php } ?>

<?php
$temp = $this->viewArgs;
$temp["listino"] = "tutti";
?>
<a href="<?php echo $this->baseUrl."/combinazioni/main".Url::createUrl($temp);?>" class="btn btn-<?php if ($this->viewArgs["listino"] == "tutti") { ?>info<?php } else { ?>default<?php } ?> pull-right">Listino Italia</a>

<?php echo $menu; ?>
