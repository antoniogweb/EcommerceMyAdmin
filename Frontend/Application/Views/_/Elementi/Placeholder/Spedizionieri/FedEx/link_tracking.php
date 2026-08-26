<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p>
	<?php echo gtextPlain("Ecco il link per vedere il tracking della tua spedizione");?>: <a href="<?php echo $modulo->getUrlTracking($idSpedizione)?>"><?php echo gtextPlain("vai al tracking");?></a>
	<br />
	<?php echo gtextPlain("Il tracking della spedizione potrebbe essere disponibile da domani.")?>
</p>
