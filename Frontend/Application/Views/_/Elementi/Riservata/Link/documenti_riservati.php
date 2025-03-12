<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("documenti_in_clienti")) { ?>
<li class="<?php if ($attiva == "documenti-riservati") { ?>uk-active<?php } ?>">
	<a href="<?php echo $this->baseUrl."/documenti-riservati/";?>" title="<?php echo gtext("Documenti riservati", false);?>"><?php echo gtext("Documenti riservati");?></a>
</li>
<?php } ?>
