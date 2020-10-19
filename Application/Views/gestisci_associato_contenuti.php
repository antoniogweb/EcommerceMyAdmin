<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p>
	<?php foreach ($tipoContenuti as $tipo) { ?>
	<a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/contenuti/form/insert";?>?partial=Y&nobuttons=Y&tipo=<?php echo $tipo;?>&id_page=<?php echo $id_page;?>">Aggiungi tipo <?php echo $tipo;?></a>
	<?php } ?>
</p>

