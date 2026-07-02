<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<li class="<?php echo tm($tm, "righe");?>">
	<a href="<?php echo $this->baseUrl."/righe/daordinare?da_ordinare=D";?>">
		<i class="fa fa-bell"></i> <span><?php echo gtext("Da ordinare");?></span>
		<?php
		$numeroDaOrdinare = count(OrdiniModel::idRigheDaOrdinare());
		
		if ($numeroDaOrdinare) { ?>
		<span class="label label-warning"><?php echo $numeroDaOrdinare;?></span>
		<?php } ?>
	</a>
</li>
