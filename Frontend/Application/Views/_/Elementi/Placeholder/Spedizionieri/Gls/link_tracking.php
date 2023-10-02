<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p>
	<?php echo gtext("Ecco il link per vedere il tracking della tua spedizione");?>: <a href="<?php echo $modulo->getUrlTracking($idSpedizione)?>"><?php echo gtext("vai al tracking");?></a>
</p>
