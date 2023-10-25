<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action === "contenuti") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/contenuti/form/insert";?>?partial=Y&nobuttons=N&id_page=<?php echo $id_page;?>"><?php echo gtext("Aggiungi fascia");?></a></p>

<?php } ?>

<?php include(ROOT."/Application/Views/gestisci_associato_contenuti.php");?>

<?php include($this->viewPath("gestisci_associato_pagine_correlate"));?>

