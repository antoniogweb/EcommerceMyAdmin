<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p>
	<?php
	$strIdTipo = "";
	foreach ($tipoContenuti as $tipo) { ?>
		<?php if (isset($tabContenuti) && count($tabContenuti) > 0 && $this->viewArgs["tipocontenuto"] != "tutti") {
			$recordTipoContenuto = TipicontenutoModel::getRecord($this->viewArgs["tipocontenuto"]);
			
			$strIdTipo = "&id_tipo=".$this->viewArgs["tipocontenuto"];
			
			if (!empty($recordTipoContenuto) && $recordTipoContenuto["tipo"] != $tipo)
				continue;
		} ?>
		<a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/contenuti/form/insert";?>?partial=Y&nobuttons=Y&tipo=<?php echo $tipo;?>&id_page=<?php echo $id_page;?><?php echo $strIdTipo;?>">
		Aggiungi
		<?php if (!isset($tabContenuti) || (int)count($tabContenuti) === 0) { ?>tipo <?php echo $tipo;?><?php } ?>
		</a>
	<?php } ?>
</p>

