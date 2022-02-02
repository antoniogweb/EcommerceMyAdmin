<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p>
	<?php echo gtext("Gentile",false);?> [NOME_CLIENTE],<br />
	<?php echo gtext("il suo account è stato attivato.")?>
</p>

<p><?php echo gtext("Potrà accedere alla propria area riservata tramite username e password che ha scelto e visitando il seguente",false);?> <a href="<?php echo Domain::$publicUrl."/area-riservata";?>"><?php echo gtext("indirizzo web",false);?></a>.
</p>

<p><?php echo gtext("Cordiali saluti", false);?>.</p>
