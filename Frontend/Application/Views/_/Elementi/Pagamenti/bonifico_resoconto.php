<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<h2 class="uk-heading-bullet"><?php echo gtext("Dettagli pagamento:");?></h2>

<?php if (v("attiva_gestione_pagamenti")) { ?>
<div class="uk-margin">
	<?php echo htmlentitydecode(pfield(OrdiniModel::$pagamentiFull[$codPag],"istruzioni_pagamento"));?>
</div>
<?php } else { ?>
<p><?php echo testo("Esegua il bonifico alle seguenti coordinate bancarie ...");?></p>
<?php } ?>
