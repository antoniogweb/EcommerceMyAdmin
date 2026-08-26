<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p>
	<?php echo gtextPlain("Gentile",false);?> [NOME_CLIENTE],<br />
	<?php echo gtextPlain("il suo account è stato attivato.")?>
</p>

<p><?php echo gtextPlain("Potrà accedere alla propria area riservata tramite username e password che ha scelto e visitando il seguente",false);?> <a href="<?php echo Domain::$publicUrl."/[LINGUA]/area-riservata";?>"><?php echo gtextPlain("indirizzo web",false);?></a>.
</p>

<p><?php echo gtextPlain("Cordiali saluti", false);?>.</p>
