<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("L'account è stato creato correttamente. Le è stata inviata una mail con le credenziali d'accesso che ha scelto");?>.</p>

<p><?php echo gtext("Vai all'");?> <a href="<?php echo $this->baseUrl."/area-riservata";?>"><?php echo strtolower(gtext("Area riservata"));?></a></p>
