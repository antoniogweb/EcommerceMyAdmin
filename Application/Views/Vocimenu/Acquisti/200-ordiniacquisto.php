<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="<?php echo tm($tm, array("ordiniacquisto","ordiniacquistorighe","righe"));?> treeview help_ordini_acquisto">
	<a href="#">
		<i class="fa fa-book"></i>
		<span><?php echo gtext("Ordini acquisto"); ?></span>
	</a>
	<ul class="treeview-menu">
		<li><a href="<?php echo $this->baseUrl."/ordiniacquisto/form/insert/0";?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi ordine"); ?></a></li>
		<li <?php if ($this->controller == "ordiniacquisto") { ?>class="active"<?php } ?>><a href="<?php echo $this->baseUrl."/ordiniacquisto/main/1";?>"><i class="fa fa-list"></i> <?php echo gtext("Lista ordini"); ?></a></li>
		<li <?php if ($this->controller == "ordiniacquistorighe") { ?>class="active"<?php } ?>>
			<a href="<?php echo $this->baseUrl."/ordiniacquistorighe/main/1";?>">
				<i class="fa fa-list"></i>
				<?php echo gtext("Lista righe aquisto"); ?>
				<?php
				$numeroDaCollegare = OrdiniacquistorigheModel::numeroNonCollegate();
				
				if ($numeroDaCollegare) { ?>
				<span class="label label-warning"><?php echo $numeroDaCollegare;?></span>
				<?php } ?>
			</a>
		</li>
		<li <?php if ($this->controller == "righe") { ?>class="active"<?php } ?>>
			<a href="<?php echo $this->baseUrl."/righe/daordinare?da_ordinare=D";?>">
				<i class="fa fa-list"></i>
				<?php echo gtext("Da ordinare"); ?>
				<?php
				$numeroDaOrdinare = count(OrdiniModel::idRigheDaOrdinare());
				
				if ($numeroDaOrdinare) { ?>
				<span class="label label-warning"><?php echo $numeroDaOrdinare;?></span>
				<?php } ?>
			</a>
		</li>
	</ul>
</li>
