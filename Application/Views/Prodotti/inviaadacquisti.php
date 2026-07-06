<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php $page = PagesModel::g(false)->select("ok_acquisti,prodotto_generico")->whereId((int)$id)->addWhereOkAcqusti()->record(); ?>
<?php if (!empty($page) && isProdotto($id)) {
	$combDaImportare = MagazzinoarticoliModel::combinazioniDaImportare((int)$id);
?>
	<?php if (count($combDaImportare) > 0) { ?>
		<div class="text-warning"><?php echo gtext("Numero combinazioni da inviare ad acquisti:")." <b>".count($combDaImportare)."</b>";?></div>
	<?php } ?>
	<a class="label label-success ajlink" href="<?php echo Url::getRoot()."prodotti/inviaadacquisti/".(int)$id;?>"><i class='fa fa-send'></i> <?php echo gtext("Invia ad acquisti");?></a>
<?php } ?>